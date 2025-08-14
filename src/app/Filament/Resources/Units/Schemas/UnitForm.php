<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('symbol'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('si_unit'),
                TextInput::make('conversion_factor')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('dimension'),
                TextInput::make('system')
                    ->required()
                    ->default('metric'),
                Toggle::make('is_base_unit')
                    ->required(),
                TextInput::make('category'),
                TextInput::make('unit_system'),
                TextInput::make('unit_group'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('metadata'),
                TextInput::make('created_by'),
                TextInput::make('updated_by'),
            ]);
    }
}
