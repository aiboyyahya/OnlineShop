<?php

namespace App\Filament\Resources\Pendapatans\Pages;

use App\Filament\Resources\Pendapatans\PendapatanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPendapatan extends EditRecord
{
    protected static string $resource = PendapatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
