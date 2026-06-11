<?php

namespace App\Filament\Resources\Surveys\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    protected static ?string $title = 'Respostas';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn ($query) => $query->with([
                    'respondent',
                    'items.question',
                ])
            )
            ->recordTitleAttribute('submitted_at')
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('respondent.name')
                    ->label('Nome')
                    ->state(
                        fn ($record) =>
                            $record->respondent?->name ?? 'Anônimo'
                    )
                    ->searchable(),

                TextColumn::make('respondent.email')
                    ->label('E-mail')
                    ->searchable(),

                TextColumn::make('submitted_at')
                    ->label('Respondido em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->infolist([
                        Section::make('Respondente')
                            ->schema([
                                TextEntry::make('respondent.name')
                                    ->label('Nome')
                                    ->default('Anônimo'),

                                TextEntry::make('respondent.email')
                                    ->label('E-mail')
                                    ->default('-'),

                                TextEntry::make('submitted_at')
                                    ->label('Respondido em')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                        Section::make('Respostas')
                            ->schema([
                                TextEntry::make('answers_html')
                                    ->html(),
                            ]),
                    ]),
            ])
            ->toolbarActions([
                //
            ]);
    }
}