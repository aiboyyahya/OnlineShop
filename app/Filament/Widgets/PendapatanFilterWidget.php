<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;

class PendapatanFilterWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.pendapatan-filter-widget';

    protected int|string|array $columnSpan = 'full';
    public string $businessCustomer = '';
    public string $startDate = '';
    public string $endDate = '';

    public function mount(): void
    {
        $filter = session('pendapatan_filter', []);

        $this->form->fill([
            'businessCustomer' => $filter['businessCustomer'] ?? '',
            'startDate' => $filter['startDate'] ?? '',
            'endDate' => $filter['endDate'] ?? now()->format('Y-m-d'),
        ]);
    }
    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('businessCustomer')
                            ->label('Business customers only')
                            ->options([
                                '1' => 'Yes',
                                '0' => 'No',
                            ])
                            ->placeholder('-')
                            ->live()
                            ->afterStateUpdated(fn () => $this->syncFilter()),

                        DatePicker::make('startDate')
                            ->label('Start date')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn () => $this->syncFilter()),

                        DatePicker::make('endDate')
                            ->label('End date')
                            ->displayFormat('d/m/Y')
                            ->minDate(fn (Get $get) => $get('startDate'))
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn () => $this->syncFilter()),
                    ]),
                ])
                ->compact(),
        ]);
    }

   protected function syncFilter(): void
{
    $isEmpty =
        empty($this->businessCustomer) &&
        empty($this->startDate) &&
        empty($this->endDate);

    if ($isEmpty) {
        session()->forget('pendapatan_filter');
    } else {
        session()->put('pendapatan_filter', [
            'businessCustomer' => $this->businessCustomer,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    $this->dispatch('pendapatan-filter-updated');
}
}
