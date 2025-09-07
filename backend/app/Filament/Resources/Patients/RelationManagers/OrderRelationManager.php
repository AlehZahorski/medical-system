<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;

class OrderRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Zamówienia';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')
                    ->label('ID zamówienia')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Data utworzenia')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('order_id', 'asc')
            ->headerActions([])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.orders.view', ['record' => $record]))

            ]);
    }
}
