<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Models\Transaction;

class TransactionsTable
{
    public static function getMonthWeeks(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $weeks = [];
        $w = 1;
        $date = $start->copy();

        while ($date->lte($end)) {
            $weeks[] = [
                'week_number' => $w,
                'start_date' => $date->copy()->startOfWeek()->format('Y-m-d'),
                'end_date' => $date->copy()->endOfWeek()->gt($end)
                    ? $end->format('Y-m-d')
                    : $date->copy()->endOfWeek()->format('Y-m-d'),
            ];

            $date = $date->copy()->endOfWeek()->addDay();
            $w++;
        }

        return $weeks;
    }

    public static function configure(Table $table): Table
    {
        $status = request('status');
        $month = request('month');
        $week = request('week');

        $table->modifyQueryUsing(function (Builder $query) use ($status, $month, $week) {
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            if ($month) {
                $query->whereMonth('created_at', $month);
            }

            if ($week) {
                $weeks = self::getMonthWeeks(now()->year, $month ?: now()->month);
                $wdata = collect($weeks)->firstWhere('week_number', $week);
                if ($wdata) {
                    $query->whereBetween('created_at', [$wdata['start_date'], $wdata['end_date']]);
                }
            }
        });

        return $table
       
            ->columns([
                TextColumn::make('customer.name')->label('Customer')->sortable(),
                TextColumn::make('province')->label('Provinsi'),
                TextColumn::make('city')->label('Kota'),
                TextColumn::make('district')->label('Kecamatan'),
                TextColumn::make('postal_code')->label('Kode Pos'),
                TextColumn::make('order_code')->label('Kode Pesanan')->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'packing' => 'info',
                        'sent' => 'primary',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('payment_method')->label('Pembayaran')->badge(),
                TextColumn::make('courier')->badge(),
                TextColumn::make('courier_service'),
                TextColumn::make('shipping_cost')->label('Ongkir')->money('IDR'),
                TextColumn::make('total')->money('IDR'),
                TextColumn::make('tracking_number'),
                TextColumn::make('created_at')->dateTime(),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
