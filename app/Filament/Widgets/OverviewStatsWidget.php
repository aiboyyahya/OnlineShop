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
    protected array|int|null $columns = 4;

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

        $yearStart = Carbon::create($year, 1, 1)->startOfDay();
        $yearEnd   = Carbon::create($year, 12, 31)->endOfDay();

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

        $chart = [2, 4, 3, 5, 7, 9, 12];

        $statStyle = [
            'class' => 'text-sm text-center min-h-[110px] flex flex-col justify-center',
        ];

        return [
            Stat::make('Total Produk', Product::count())
                ->description('Total Produk')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success')
                ->chart($chart)
                ->columnSpan(1)
                ->extraAttributes($statStyle),

            Stat::make('Total Kategori', Category::count())
                ->description('Total Kategori')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info')
                ->chart($chart)
                ->columnSpan(1)
                ->extraAttributes($statStyle),

            Stat::make(
                'Pendapatan Bulan ' . Carbon::create($year, $month)->translatedFormat('F Y'),
                'Rp ' . number_format($monthIncome, 0, ',', '.')
            )
                ->description('Total Pendapatan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart($chart)
                ->columnSpan(1)
                ->extraAttributes($statStyle),

            Stat::make(
                'Pendapatan Tahun ' . $year,
                'Rp ' . number_format($yearIncome, 0, ',', '.')
            )
                ->description('Total Pendapatan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($chart)
                ->columnSpan(1)
                ->extraAttributes($statStyle),
        ];
    }
}
