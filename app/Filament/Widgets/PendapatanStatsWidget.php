<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PendapatanStatsWidget extends BaseWidget
{
    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];

    protected function getStats(): array
    {
        $filter = session('pendapatan_filter', []);

        // If no filters are active, show current month data
        if (empty($filter['startDate']) && empty($filter['endDate']) && empty($filter['businessCustomer'])) {
            $query = Transaction::where('status', 'done')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
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

        $todayIncome = (clone $query)->whereDate('created_at', Carbon::today())->sum('total');
        $monthIncome = (clone $query)->whereMonth('created_at', Carbon::now()->month)
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
