<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\RelationManagers\TestRelationManager;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getHeaderActions(): array
    {
        return [];
    }

    protected function getRelations(): array
    {
        return [
            TestRelationManager::class,
        ];
    }
}
