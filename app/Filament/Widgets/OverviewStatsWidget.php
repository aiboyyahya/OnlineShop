<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OverviewStatsWidget extends BaseWidget
{
    protected array | int | null $columns = 4;

    protected function getStats(): array
    {
        $todayIncome = Transaction::where('status', 'done')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        $monthIncome = Transaction::where('status', 'done')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        $chart = [2, 4, 3, 5, 7, 9, 12];

        $statStyle = [
            'class' => 'text-sm leading-relaxed text-center min-h-[110px] flex flex-col justify-center',
        ];

        return [
            Stat::make('Total Products', Product::count())
                ->description('Total Produk')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success')
                ->chart($chart)
                ->extraAttributes($statStyle),

            Stat::make('Total Categories', Category::count())
                ->description('Total Kategori')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info')
                ->chart($chart)
                ->extraAttributes($statStyle),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayIncome, 0, ',', '.'))
                ->description('pendapatan hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($chart)
                ->extraAttributes($statStyle),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthIncome, 0, ',', '.'))
                ->description('pendapatan bulan ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart($chart)
                ->extraAttributes($statStyle),
        ];
    }
}
