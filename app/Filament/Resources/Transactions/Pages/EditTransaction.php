<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
{
    return [
        Action::make('whatsapp')
            ->label('Hubungi WA')
            ->icon('heroicon-o-chat-bubble-oval-left')
            ->color('success')
            ->url(fn () =>
                $this->record?->phone_number
                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $this->record->phone_number)
                    : null
            )
            ->visible(fn () =>
                filled($this->record?->phone_number)
            )
            ->openUrlInNewTab(),

        DeleteAction::make(),
    ];
}
}
