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
        $filter = session('pendapatan_filter', []);

        // If no filters are active, return empty query
        if (empty($filter['startDate']) && empty($filter['endDate']) && empty($filter['businessCustomer'])) {
            $query = Transaction::whereRaw('1 = 0'); // Empty query
        } else {
            $query = Transaction::where('status', 'done');

            if (!empty($filter['startDate']) && !empty($filter['endDate'])) {
                $query->whereBetween('created_at', [$filter['startDate'], $filter['endDate']]);
            } elseif (!empty($filter['startDate'])) {
                $query->whereDate('created_at', '>=', $filter['startDate']);
            } elseif (!empty($filter['endDate'])) {
                $query->whereDate('created_at', '<=', $filter['endDate']);
            }

            if (!empty($filter['businessCustomer'])) {
                if ($filter['businessCustomer'] == '1') {
                    $query->whereHas('user', function ($q) {
                        $q->where('is_business', true);
                    });
                } elseif ($filter['businessCustomer'] == '0') {
                    $query->whereHas('user', function ($q) {
                        $q->where('is_business', false);
                    });
                }
            }
        }

        return $table
            ->query($query)
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
