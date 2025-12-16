<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrderLineChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pelanggan Bulanan';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $count = Transaction::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->distinct('customer_id')
                ->count('customer_id');

            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pelanggan',
                    'data' => $data,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
