<?php

namespace App\Filament\Resources\Pendapatans\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Pendapatans\PendapatanResource;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewPendapatan extends ViewRecord
{
    protected static string $resource = PendapatanResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make('Informasi Pendapatan')
                ->schema([

                    TextEntry::make('id')
                        ->label('ID'),

                    TextEntry::make('created_at')
                        ->label('Tanggal')
                        ->dateTime(),

                    TextEntry::make('total')
                        ->label('Jumlah')
                        ->money('IDR'),

                    TextEntry::make('lihat_transaksi')
                        ->label('')
                        ->state('Lihat Detail Transaksi')
                        ->url(fn($record) =>
                            TransactionResource::getUrl('view', ['record' => $record->id])
                        )
                        ->badge()
                        ->extraAttributes([
                            'class' =>
                                'bg-primary-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-primary-700 inline-block'
                        ])
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpanFull(),

        ]);
    }
}
