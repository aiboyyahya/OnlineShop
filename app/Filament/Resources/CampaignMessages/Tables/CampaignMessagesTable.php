<?php

namespace App\Filament\Resources\CampaignMessages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class CampaignMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->groups([
                Group::make('title')
                    ->label('Judul Pesan')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('message')
                    ->label('Isi Pesan')
                    ->wrap()
                    ->limit(100),
                TextColumn::make('customer.nama')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('sent_at')
                    ->label('Terkirim pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(
                null
            )
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
