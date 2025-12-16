<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->latest()->limit(10)
            )
            ->columns([
                TextColumn::make('order_code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Tanggal Pesanan')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date) =>
                                    $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date) =>
                                    $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
