<?php

namespace App\Filament\Resources\Transactions\Pages;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\DateFilter;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Filters\Filter;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;



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
protected function getTableFilters(): array
{
    return [
        Filter::make('created_at')
            ->label('Tanggal Transaksi')
            ->form([
                DatePicker::make('date_from')->label('Dari'),
                DatePicker::make('date_until')->label('Sampai'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['date_from'], fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                    ->when($data['date_until'], fn($q, $d) => $q->whereDate('created_at', '<=', $d));
            }),
    ];
}
}

