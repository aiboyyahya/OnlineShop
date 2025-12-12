<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CustomerDataWidget extends BaseWidget
{
    protected static ?string $heading = 'Customer Data';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->with(['transactions', 'reviews.product'])
                    ->withCount(['transactions', 'reviews'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transactions_count')
                    ->label('Checkout Count'),
                Tables\Columns\TextColumn::make('reviews_count')
                    ->label('Review Count'),
                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('IDR')
                    ->getStateUsing(function (User $record) {
                        return $record->transactions->sum('total');
                    }),
                Tables\Columns\TextColumn::make('reviewed_products')
                    ->label('Reviewed Products')
                    ->getStateUsing(function (User $record) {
                        return $record->reviews->pluck('product.name')->unique()->join(', ');
                    }),
            ])
            ->defaultSort('name');
    }
}
