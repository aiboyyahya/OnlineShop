<?php

namespace App\Filament\Resources\CampaignMessages;

use App\Filament\Resources\CampaignMessages\Pages\CreateCampaignMessage;
use App\Filament\Resources\CampaignMessages\Pages\EditCampaignMessage;
use App\Filament\Resources\CampaignMessages\Pages\ListCampaignMessages;
use App\Filament\Resources\CampaignMessages\Schemas\CampaignMessageForm;
use App\Filament\Resources\CampaignMessages\Tables\CampaignMessagesTable;
use App\Models\CampaignMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CampaignMessageResource extends Resource
{
    protected static ?string $model = CampaignMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Konten';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CampaignMessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampaignMessagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaignMessages::route('/'),
            'create' => CreateCampaignMessage::route('/create'),
            'edit' => EditCampaignMessage::route('/{record}/edit'),
        ];
    }
}
