<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Support\Carbon;

class PendapatanFilterWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.pendapatan-filter-widget';
    protected int|string|array $columnSpan = 'full';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?int $month = null;
    public ?int $year = null;

    public function mount(): void
    {
        $filter = session('pendapatan_filter', []);

        $month = is_numeric($filter['month'] ?? null)
            ? (int) $filter['month']
            : now()->month;

        $year = is_numeric($filter['year'] ?? null)
            ? (int) $filter['year']
            : now()->year;

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = Carbon::create($year, $month, 1)->endOfMonth();

        if ($year === now()->year && $month === now()->month) {
            $end = now();
        }

        $this->form->fill([
            'startDate' => $filter['startDate'] ?? $start->toDateString(),
            'endDate'   => $filter['endDate'] ?? $end->toDateString(),
            'month'     => $month,
            'year'      => $year,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()
                ->schema([
                    Grid::make(4)->schema([
                        DatePicker::make('startDate')
                            ->label('Start date')
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn () => $this->syncFilter()),

                        DatePicker::make('endDate')
                            ->label('End date')
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn () => $this->syncFilter()),

                        Select::make('month')
                            ->label('Month')
                            ->options([
                                1 => 'January', 2 => 'February', 3 => 'March',
                                4 => 'April',   5 => 'May',      6 => 'June',
                                7 => 'July',    8 => 'August',   9 => 'September',
                                10 => 'October',11 => 'November',12 => 'December',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                if (!$state || !$get('year')) {
                                    return;
                                }

                                $start = Carbon::create((int) $get('year'), (int) $state, 1)->startOfMonth();
                                $end   = Carbon::create((int) $get('year'), (int) $state, 1)->endOfMonth();

                                if ((int) $get('year') === now()->year && (int) $state === now()->month) {
                                    $end = now();
                                }

                                $set('startDate', $start->toDateString());
                                $set('endDate', $end->toDateString());

                                $this->syncFilter();
                            }),

                        Select::make('year')
                            ->label('Year')
                            ->options(
                                collect(range(now()->year, now()->year - 5))
                                    ->mapWithKeys(fn ($y) => [$y => $y])
                                    ->toArray()
                            )
                            ->live()
                            ->afterStateUpdated(fn () => $this->syncFilter()),
                    ]),
                ])
                ->compact(),
        ]);
    }

    protected function syncFilter(): void
    {
        session()->put('pendapatan_filter', [
            'startDate' => $this->startDate,
            'endDate'   => $this->endDate,
            'month'     => (int) $this->month,
            'year'      => (int) $this->year,
        ]);


        $this->dispatch('pendapatan-filter-updated');
    }
}
