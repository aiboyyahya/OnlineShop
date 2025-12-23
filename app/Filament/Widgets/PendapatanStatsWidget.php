<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PendapatanStatsWidget extends BaseWidget
{
    protected $listeners = [
        'pendapatan-filter-updated' => '$refresh',
    ];

    protected function getStats(): array
    {
        $filter = session('pendapatan_filter', []);

        $month = isset($filter['month']) && is_numeric($filter['month'])
            ? max(1, min(12, (int) $filter['month']))
            : now()->month;

        $year = isset($filter['year']) && is_numeric($filter['year'])
            ? (int) $filter['year']
            : now()->year;

        $monthName = Carbon::create($year, $month, 1)->translatedFormat('F');


        $yearStart  = Carbon::create($year, 1, 1)->startOfDay();
        $yearEnd    = Carbon::create($year, 12, 31)->endOfDay();

        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd   = Carbon::create($year, $month, 1)->endOfMonth();

         
        $baseQuery = Transaction::query()
            ->where('status', 'done');



        $yearIncome = (clone $baseQuery)
            ->whereBetween('created_at', [$yearStart, $yearEnd])
            ->sum('total');

    
        $monthIncome = (clone $baseQuery)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total');

 
        $today = Carbon::today();
        $todayIncome = (
            $today->between($yearStart, $yearEnd)
                ? (clone $baseQuery)
                    ->whereDate('created_at', $today)
                    ->sum('total')
                : 0
        );

        return [
            Stat::make(
                'Pendapatan Hari Ini',
                'Rp ' . number_format($todayIncome, 0, ',', '.')
            )
                ->description(' Total Hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([2, 4, 3, 5, 7, 9, 12])
                ->extraAttributes(['class' => 'text-center']),

            Stat::make(
                'Pendapatan Bulan ' . $monthName ,
                'Rp ' . number_format($monthIncome, 0, ',', '.')
            )
                ->description('Total Pendapatan Bulan' . ' ' . $monthName)
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart([5, 3, 4, 6, 8, 6, 10])
                ->extraAttributes(['class' => 'text-center']),

            Stat::make(
                'Pendapatan Tahun ' . $year,
                'Rp ' . number_format($yearIncome, 0, ',', '.')
            )
                ->description('Total Pendapatan Tahun ' . $year)
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([10, 15, 12, 18, 20, 22, 25])
                ->extraAttributes(['class' => 'text-center']),
        ];
    }
}
