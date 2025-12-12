<?php

namespace App\Filament\Resources\CampaignMessages\Schemas;

use App\Models\Customer;
use App\Models\User;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CampaignMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Radio::make('mode')
                    ->label('Jenis Pengiriman')
                    ->options([
                        'all' => 'Semua Customer',
                        'selected' => 'Pilih Customer Tertentu',
                    ])
                    ->default('all')
                    ->inline()
                    ->reactive()
                    ->required(),

                Select::make('customer_ids')
                    ->label('Pilih Customer')
                    ->multiple()
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn($get) => $get('mode') === 'selected')
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->label('Judul Campaign')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('message')
                    ->label('Isi Pesan')
                    ->rows(6)
                    ->required()
                    ->columnSpanFull()
            ]);
    }
}
