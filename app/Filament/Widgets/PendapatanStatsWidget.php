<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PendapatanStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $todayIncome = Transaction::where('status', 'done')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        $monthIncome = Transaction::where('status', 'done')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        return [
            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayIncome, 0, ',', '.'))
                ->description('Total pendapatan hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([2, 4, 3, 5, 7, 9, 12])
                ->extraAttributes([
                    'class' => 'text-center',
                ]),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthIncome, 0, ',', '.'))
                ->description('Total pendapatan bulan ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart([5, 3, 4, 6, 8, 6, 10])
                ->extraAttributes([
                    'class' => 'text-center',
                ]),
        ];
    }
}
