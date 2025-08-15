<?php

namespace App\Filament\Resources\Units\Tables;

use App\Actions\Units\ListUnitsAction;
use App\Models\Unit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class UnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Unit::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('symbol')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('si_unit')
                    ->searchable(),
                TextColumn::make('conversion_factor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dimension')
                    ->searchable(),
                TextColumn::make('system')
                    ->searchable(),
                IconColumn::make('is_base_unit')
                    ->boolean(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('unit_system')
                    ->searchable(),
                TextColumn::make('unit_group')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->searchable(),
                TextColumn::make('updated_by')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
