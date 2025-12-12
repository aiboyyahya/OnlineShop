<?php

namespace App\Filament\Resources\Pendapatans\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class PendapatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi')
                ->schema([
                    TextInput::make('order_code')
                        ->label('Kode Pesanan')
                        ->disabled()
                        ->readOnly(),

                    TextInput::make('total')
                        ->label('Total')
                        ->disabled()
                        ->readOnly(),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->disabled()
                        ->readOnly(),
                ])->columns(2),
        ]);
    }
}
