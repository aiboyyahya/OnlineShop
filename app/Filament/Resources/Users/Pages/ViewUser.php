<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Filament\Widgets\CustomerStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->hidden(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerStatsWidget::class,
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Customer Information')
                ->schema([
                    TextEntry::make('name')->label('Nama'),
                    TextEntry::make('email'),
                    TextEntry::make('phone_number')->label('No. Telepon'),
                    TextEntry::make('whatsapp')
                        ->label('Hubungi via WhatsApp')
                        ->state('Kirim Pesan WhatsApp')
                        ->url(fn ($record) => $record->phone_number ? 'https://wa.me/' . preg_replace('/\D/', '', $record->phone_number) : null)
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->visible(fn ($record) => !empty($record->phone_number)),
                    TextEntry::make('role'),
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Tabs::make('User Details')
                ->tabs([
                    Tab::make('Transactions')
                        ->schema([
                            Section::make('Checkout History')
                                ->schema([
                                    RepeatableEntry::make('transactions')
                                        ->schema([
                                            Section::make()
                                                ->schema([


                                                    TextEntry::make('order_code')
                                                        ->label('Order Code')
                                                        ->weight(FontWeight::Bold),


                                                    TextEntry::make('status')
                                                        ->label('Status')
                                                        ->badge()
                                                        ->color(fn(string $state): string => match ($state) {
                                                            'pending' => 'gray',
                                                            'paid' => 'warning',
                                                            'sent' => 'info',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger',
                                                            default => 'gray',
                                                        }),


                                                    TextEntry::make('items')
                                                        ->label('Products')
                                                        ->html()

                                                        ->formatStateUsing(function ($state, $record) {
                                                            $items = is_string($record->items)
                                                                ? json_decode($record->items, true)
                                                                : $record->items;

                                                            return collect($items)->map(function ($item) {
                                                                $product = data_get($item, 'product.product_name')
                                                                    ?? data_get($item, 'product_name')
                                                                    ?? '-';

                                                                $qty = data_get($item, 'quantity', 1);
                                                                $price = data_get($item, 'price', 0);
                                                                $subtotal = $qty * $price;

                                                                return "
                                <div style='margin-bottom:6px'>
                                    <strong>{$product}</strong><br>
                                    Qty: {$qty} × Rp " . number_format($price, 0, ',', '.') . "<br>
                                    Subtotal: <strong>Rp " . number_format($subtotal, 0, ',', '.') . "</strong>
                                </div>
                            ";
                                                            })->implode('<hr>');
                                                        }),

                                                    TextEntry::make('payment_method')
                                                        ->label('Payment Method'),

                                                        TextEntry::make('courier')
                                                        ->label('Courier')
                                                        ->formatStateUsing(fn($state) => strtoupper($state ?? '-')),

                                                         TextEntry::make('tracking_number')
                                                        ->label('Tracking Number')
                                                        ->copyable(),

                                                    TextEntry::make('address')
                                                        ->label('Address')
                                                        ->formatStateUsing(fn($state) => nl2br($state))
                                                        ->html(),

                                                    TextEntry::make('total')
                                                        ->label('Total')
                                                        ->formatStateUsing(
                                                            fn($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')
                                                        )
                                                        ->weight(FontWeight::Bold)
                                                        ->color('success'),


                                                    TextEntry::make('view_order')
                                                        ->state('View Order')
                                                        ->label('')
                                                        ->url(
                                                            fn($record) =>
                                                            TransactionResource::getUrl('view', ['record' => $record->id])
                                                        )
                                                        ->openUrlInNewTab()
                                                        ->badge()
                                                        ->extraAttributes([
                                                            'class' =>
                                                            'bg-primary-600 text-white font-semibold px-3 py-1 rounded-lg hover:bg-primary-700'
                                                        ])
                                                        ->columnSpanFull(),
                                                ])
                                                ->columns(2),
                                        ])
                                        ->columnSpanFull(),

                                ])
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),

                    Tab::make('Reviews')
                        ->schema([
                            Section::make('Review History')
                                ->schema([
                                    RepeatableEntry::make('reviews')
                                        ->schema([
                                            TextEntry::make('product.product_name')
                                                ->label('Product'),

                                            TextEntry::make('rating')
                                                ->badge()
                                                ->color(fn(int $state): string => match ($state) {
                                                    1 => 'danger',
                                                    2, 3 => 'warning',
                                                    4, 5 => 'success',
                                                    default => 'gray',
                                                }),

                                            TextEntry::make('comment')
                                                ->label('Comment')
                                                ->limit(50),
                                        ]),
                                ])
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
