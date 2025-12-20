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

    protected $listeners = [
        'pendapatan-filter-updated' => '$refresh',
    ];

    protected function getStats(): array
    {
        $filter = session('pendapatan_filter', []);

        $startDate = !empty($filter['startDate'])
            ? Carbon::parse($filter['startDate'])->startOfDay()
            : null;

        $endDate = !empty($filter['endDate'])
            ? Carbon::parse($filter['endDate'])->endOfDay()
            : null;

        $baseQuery = Transaction::query()
            ->where('status', 'done')
            ->when($startDate && $endDate, fn ($q) =>
                $q->whereBetween('created_at', [$startDate, $endDate])
            )
            ->when($startDate && !$endDate, fn ($q) =>
                $q->where('created_at', '>=', $startDate)
            )
            ->when(!$startDate && $endDate, fn ($q) =>
                $q->where('created_at', '<=', $endDate)
            )
            ->when(!empty($filter['businessCustomer']), fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where('is_business', $filter['businessCustomer'] === '1')
                )
            );

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $todayIncome = (!$startDate || $today->gte($startDate)) &&
            (!$endDate || $today->lte($endDate))
            ? (clone $baseQuery)->whereDate('created_at', $today)->sum('total')
            : 0;

        $monthIncome = (
            (!$startDate || $monthEnd->gte($startDate)) &&
            (!$endDate || $monthStart->lte($endDate))
        )
            ? (clone $baseQuery)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total')
            : 0;

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

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayIncome, 0, ',', '.'))
                ->description('Pendapatan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($chart)
                ->columnSpan(1)
                ->extraAttributes($statStyle),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthIncome, 0, ',', '.'))
            ->description('Pendapatan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart($chart)
                ->columnSpan(1)
                ->extraAttributes($statStyle),
        ];
    }
}
