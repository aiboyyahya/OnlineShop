<?php

namespace App\Filament\Resources\CampaignMessages\Pages;

use App\Filament\Resources\CampaignMessages\CampaignMessageResource;
use App\Models\CampaignMessage;
use App\Models\Customer;
use App\Models\User;
use App\Services\WhatsAppService;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaignMessage extends CreateRecord
{
    protected static string $resource = CampaignMessageResource::class;

    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = env('WA_API_URL', 'https://api.fonnte.com/send');
        $this->token = env('WA_API_TOKEN');
    }

    protected function handleRecordCreation(array $data): CampaignMessage
    {
        return new CampaignMessage();
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

      
        if ($data['mode'] === 'all') {
            $users = User::where('role', 'user')->get();
        } else {
            $users = User::whereIn('id', $data['customer_ids'])->get();
        }

        foreach ($users as $user) {

          
            $history = CampaignMessage::create([
                'customer_id' => $user->id,
                'title'       => $data['title'],
                'message'     => $data['message'],
                'status'      => 'pending',
            ]);

          
            $response = $this->sendMessage($user->phone_number ?? $user->phone, $data['message']);

            $history->update([
                'status'          => ($response['status'] ?? false) ? 'sent' : 'failed',
                'fonnte_response' => json_encode($response),
                'sent_at'         => now(),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function sendMessage($phone_number, $message)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $phone_number,
                    'message' => $message,
                    'countryCode' => '62',
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . env('WA_API_TOKEN'),
                ),
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                \Illuminate\Support\Facades\Log::error('Fonnte CURL ERROR', ['error' => $error]);
                return [
                    'status' => false,
                    'message' => 'CURL Error: ' . $error
                ];
            }

            $data = json_decode($response, true);

            \Illuminate\Support\Facades\Log::info('Fonnte API Response', ['response' => $data]);

            if (isset($data['status']) && $data['status'] == 'success') {
                \Illuminate\Support\Facades\Log::info('Fonnte message sent', [
                    'phone' => $phone_number,
                    'message' => $message,
                ]);
                return [
                    'status' => true,
                    'message' => 'Message sent successfully'
                ];
            }

            \Illuminate\Support\Facades\Log::error('Fonnte failed to send message', [
                'phone' => $phone_number,
                'message' => $message,
                'response' => $response,
            ]);

            return [
                'status' => false,
                'message' => 'Failed to send message: ' . ($data['message'] ?? 'Unknown error')
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Exception: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
