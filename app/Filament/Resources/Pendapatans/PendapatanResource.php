<?php

namespace App\Filament\Resources\Pendapatans;

use App\Filament\Resources\Pendapatans\Pages\CreatePendapatan;
use App\Filament\Resources\Pendapatans\Pages\EditPendapatan;
use App\Filament\Resources\Pendapatans\Pages\ListPendapatans;
use App\Filament\Resources\Pendapatans\Schemas\PendapatanForm;
use App\Filament\Resources\Pendapatans\Tables\PendapatansTable;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PendapatanResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Order';

      protected static ?string $navigationLabel = 'Pendapatan';

      protected static ?string $pluralModelLabel = 'Pendapatan';

    public static function form(Schema $schema): Schema
    {
        return PendapatanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PendapatansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPendapatans::route('/'),
            'create' => CreatePendapatan::route('/create'),
            'edit' => EditPendapatan::route('/{record}/edit'),
            'view' => Pages\ViewPendapatan::route('/{record}'),
        ];
    }
}
