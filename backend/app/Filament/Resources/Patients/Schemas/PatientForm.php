<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Enums\PatientTypeEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('patient_id')
                        ->label('ID pacjenta')
                        ->required()
                        ->numeric()
                        ->unique(ignoreRecord: true),
                    Select::make('sex')
                        ->label('Płeć')
                        ->required()
                        ->options(
                            collect(PatientTypeEnum::cases())
                                ->mapWithKeys(fn($enum) => [
                                    $enum->value => ucfirst($enum->name)
                                ])
                                ->toArray()
                        ),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Imię')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('surname')
                        ->label('Nazwisko')
                        ->required()
                        ->maxLength(255),
                ]),
                DatePicker::make('birth')
                    ->label('Data urodzenia')
                    ->required(),
                TextInput::make('login')
                    ->label('Login')
                    ->disabled()
                    ->dehydrated(false)
            ]);
    }
}
