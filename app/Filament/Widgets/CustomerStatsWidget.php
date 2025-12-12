<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStatsWidget extends BaseWidget
{
    public ?User $record = null;

    protected function getStats(): array
    {
        $customer = $this->record ?? User::find(request()->route('record'));

        return [
            Stat::make('Total Checkouts', $customer ? Transaction::where('customer_id', $customer->id)->count() : 0)
                ->description('Total number of checkouts')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->chart([1, 2, 1, 4, 6, 8, 12])
                ->extraAttributes([
                    'class' => 'text-center',
                ]),

            Stat::make('Total Reviews', $customer ? Review::where('customer_id', $customer->id)->count() : 0)
                ->description('Total number of reviews')
                ->descriptionIcon('heroicon-m-star')
                ->color('success')
                ->chart([5, 3, 4, 6, 8, 6, 10])
                ->extraAttributes([
                    'class' => 'text-center',
                ]),
        ];
    }
}
