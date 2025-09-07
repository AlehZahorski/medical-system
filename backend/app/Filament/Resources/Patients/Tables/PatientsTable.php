<?php

namespace App\Filament\Resources\Patients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Imię')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('surname')
                    ->label('Nazwisko')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('login')
                    ->label('Login')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sex')
                    ->label('Płeć')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => ucfirst($state->label())),
                TextColumn::make('birth')
                    ->label('Data urodzenia')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
