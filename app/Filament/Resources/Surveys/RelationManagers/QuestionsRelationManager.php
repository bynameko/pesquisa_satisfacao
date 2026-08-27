<?php

namespace App\Filament\Resources\Surveys\RelationManagers;

use App\Enums\QuestionType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Perguntas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Pergunta')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),

                Select::make('type')
                    ->label('Tipo')
                    ->options(QuestionType::class)
                    ->required(),

                Toggle::make('required')
                    ->label('Resposta Obrigatória')
                    ->default(true),

                TextInput::make('sort_order')
                    ->label('Ordem')
                    ->numeric()
                    ->default(fn () => ($this->ownerRecord
                            ->questions()
                            ->max('sort_order') ?? 1) + 1),

                TextInput::make('placeholder')
                    ->label('Placeholder')
                    ->visible(fn ($get) => $get('type') === QuestionType::Text->value),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Pergunta')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(
                        fn (QuestionType $state) => $state->getLabel()
                    ),

                IconColumn::make('required')
                    ->label('Obrigatória')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Criar Pergunta')
                    ->visible(fn () => $this->ownerRecord->isDraft()),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => $this->ownerRecord->isDraft()),

                DeleteAction::make()
                    ->visible(fn () => $this->ownerRecord->isDraft()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}