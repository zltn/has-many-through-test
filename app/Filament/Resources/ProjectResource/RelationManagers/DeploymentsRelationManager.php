<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class DeploymentsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'deployments';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('status')->required(),
                BelongsToSelect::make('environmentId')
                    ->relationship('environment', 'name')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('category'), //this should not work, should be environment.category
                Tables\Columns\TextColumn::make('environment.name')
            ])
            ->filters([
                //
            ]);
    }
}
