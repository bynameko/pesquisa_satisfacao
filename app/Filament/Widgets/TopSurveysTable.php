<?php

namespace App\Filament\Widgets;

use App\Models\Survey;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopSurveysTable extends TableWidget
{
    protected static ?string $heading = 'Pesquisas Mais Respondidas';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Survey::query()
                    ->withCount('responses')
                    ->orderByDesc('responses_count')
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Pesquisa')
                    ->searchable(),

                TextColumn::make('responses_count')
                    ->label('Respostas')
                    ->badge()
                    ->sortable(),
                
                TextColumn::make('response_rate')
                    ->label('Taxa')
                    ->state(
                        fn (Survey $record) => $record->responseRate() . '%'
                    ),
            ])
            ->paginated([10]);
    }
}