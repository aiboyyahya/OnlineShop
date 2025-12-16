<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PendapatanFullChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendapatan';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sum = Transaction::where('status', 'done')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');
            $data[] = $sum;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => array_map(function ($i) {
                return Carbon::now()->subDays($i)->format('d/m');
            }, range(29, 0)),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
