<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrderLineChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pelanggan';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Transaction::whereDate('created_at', $date->toDateString())->distinct('customer_id')->count('customer_id');
            $data[] = $count;
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
            'labels' => ['6 hari lalu', '5 hari lalu', '4 hari lalu', '3 hari lalu', '2 hari lalu', 'Kemarin', 'Hari ini'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
