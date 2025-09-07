<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\Test;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Relations\Relation;

class TestRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Wyniki badań';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name')
                    ->label('Nazwa testu'),
                TextColumn::make('item.value')
                    ->label('Wynik'),
                TextColumn::make('item.reference')
                    ->label('Referencja'),
            ]);
    }

    protected function getRelationshipQuery(): Relation
    {
        return parent::getRelationshipQuery()
            ->where('item_type', Test::class)
            ->with('item');
    }
}
