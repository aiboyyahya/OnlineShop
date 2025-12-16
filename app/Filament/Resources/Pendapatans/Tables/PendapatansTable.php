<?php

namespace App\Filament\Resources\Pendapatans\Tables;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;

class PendapatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Transaction::where('status', 'done'))
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Jumlah')
                    ->numeric()
                    ->money('IDR', true)
                    ->sortable()
                    ->summarize(Sum::make()->money('IDR')),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'done',
                        'warning' => 'pending',
                        'danger'  => 'cancel',
                    ]),

                TextColumn::make('products_list')
                    ->label('Produk Terjual')
                    ->getStateUsing(function ($record) {
                        return $record->items
                            ->map(fn($item) => $item->product->product_name ?? '-')
                            ->implode(', ');
                    })
                    ->limit(40),

            ])

            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')->label('Dari'),
                        DatePicker::make('sampai')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['dari'] ?? null,
                                fn($q, $value) =>
                                $q->whereDate('created_at', '>=', $value)
                            )
                            ->when(
                                $data['sampai'] ?? null,
                                fn($q, $value) =>
                                $q->whereDate('created_at', '<=', $value)
                            );
                    })
                    ->label('Filter Tanggal'),
            ])

            ->recordActions([
                ViewAction::make()
                    ->url(fn($record) => route('filament.admin.resources.pendapatans.view', $record))
                    ->openUrlInNewTab(false),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
