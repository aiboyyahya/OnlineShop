<?php

namespace App\Filament\Resources\Pendapatans\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Pendapatans\PendapatanResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ListPendapatans extends ListRecords
{
    protected static string $resource = PendapatanResource::class;

    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];

    public string $totalPendapatan = 'Rp 0';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\PendapatanFilterWidget::class,
            \App\Filament\Widgets\PendapatanStatsWidget::class,
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('total')->label('Total')->money('IDR', true),
            TextColumn::make('is_business_customer')->label('Business Customer')->formatStateUsing(fn($state) => $state ? 'Yes' : 'No'),
            TextColumn::make('created_at')->label('Tanggal')->date('d/m/Y H:i'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('is_business_customer')
                ->label('Business Customer')
                ->options([
                    '1' => 'Yes',
                    '0' => 'No',
                ])
                ->query(function ($query, array $data) {
                    if ($data['value'] === '1') {
                        $query->where('is_business_customer', true);
                    } elseif ($data['value'] === '0') {
                        $query->where('is_business_customer', false);
                    }
                }),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')
                        ->label('Created From'),
                    DatePicker::make('created_until')
                        ->label('Created Until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        $this->hitungTotalPendapatan();
    }

    public function updatedTableFilters(): void
    {
        $this->hitungTotalPendapatan();
    }

    private function hitungTotalPendapatan(): void
    {
        $query = static::getResource()::getModel()::query();
        $filters = $this->tableFilters ?? [];

        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }

        if (!empty($filters['created_until'])) {
            $query->whereDate('created_at', '<=', $filters['created_until']);
        }

        if (!empty($filters['is_business_customer'])) {
            $value = $filters['is_business_customer'];
            if ($value === '1') {
                $query->where('is_business_customer', true);
            } elseif ($value === '0') {
                $query->where('is_business_customer', false);
            }
        }

        $this->totalPendapatan = 'Rp ' . number_format($query->sum('total'), 0, ',', '.');
    }
}
