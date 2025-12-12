<?php

namespace App\Filament\Resources\Pendapatans\Pages;

use App\Models\Transaction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Pendapatans\PendapatanResource;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPendapatans extends ListRecords
{
    protected static string $resource = PendapatanResource::class;

    public string $totalPendapatan = 'Rp 0';

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
        $query = Transaction::query();

        $filters = $this->getTableFilters();

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        if (!empty($filters['status'])) {
            $query->where('payment_status', $filters['status']);
        }

        $this->totalPendapatan = 'Rp ' . number_format($query->sum('total'), 0, ',', '.');
    }
    public function getTabs(): array
{
    return [
        Tab::make('All'), 

        Tab::make('Pending')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),

        Tab::make('Packing')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'packing')),

        Tab::make('Sent')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'sent')),

        Tab::make('Done')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'done')),

        Tab::make('Cancelled')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled')),
    ];
}

}
