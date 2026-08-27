<?php

namespace App\Filament\Resources\Surveys\Tables;

use App\Enums\SurveyStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Services\SurveyCsvExporter;
use App\Services\SurveyPdfExporter;

class SurveysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Pesquisa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (SurveyStatus $state): string => $state->getLabel()
                    )
                    ->color(
                        fn (SurveyStatus $state): string => match ($state) {
                            SurveyStatus::Draft => 'gray',
                            SurveyStatus::Active => 'success',
                            SurveyStatus::Closed => 'danger',
                        }
                    ),

                IconColumn::make('anonymous')
                    ->label('Anônima')
                    ->boolean(),

                TextColumn::make('questions_count')
                    ->label('Perguntas')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('invites_count')
                    ->label('Convites')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('responses_count')
                    ->label('Respostas')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('response_rate')
                    ->label('Taxa')
                    ->state(
                        fn ($record): string => $record->responseRate() . '%'
                    )
                    ->badge(),

                TextColumn::make('starts_at')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('availability')
                    ->label('Disponibilidade')
                    ->badge()
                    ->state(function ($record) {

                        return $record->isAvailable()
                            ? 'Disponível'
                            : 'Indisponível';
                    })
                    ->color(function ($record) {

                        return $record->isAvailable()
                            ? 'success'
                            : 'danger';
                    }),

                TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Excluída em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        SurveyStatus::Draft->value => 'Rascunho',
                        SurveyStatus::Active->value => 'Ativa',
                        SurveyStatus::Closed->value => 'Encerrada',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                
                EditAction::make(),

                DeleteAction::make()
                    ->before(function ($record) {

                        if ($record->hasResponses()) {

                            Notification::make()
                                ->danger()
                                ->title('Não é possível excluir pesquisas respondidas.')
                                ->send();

                            return false;
                        }
                    }),

                Action::make('duplicate')
                    ->label('Duplicar')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->authorize(fn ($record) => auth()->user()->can('duplicate', $record))
                    ->action(function ($record) {

                        $newSurvey = $record->duplicate();

                        Notification::make()
                            ->success()
                            ->title('Pesquisa duplicada')
                            ->body("Nova pesquisa: {$newSurvey->title}")
                            ->send();
                    }),

                Action::make('activate')
                    ->label('Ativar')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn ($record): bool => $record !== null && $record->isDraft())
                    ->requiresConfirmation()
                    ->authorize(fn ($record) => auth()->user()->can('activate', $record))
                    ->action(function ($record) {

                        if (! $record->hasQuestions()) {

                            Notification::make()
                                ->danger()
                                ->title('A pesquisa precisa possuir perguntas.')
                                ->send();

                            return;
                        }

                        $record->update([
                            'status' => SurveyStatus::Active,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pesquisa ativada.')
                            ->send();
                    }),
                    
                Action::make('close')
                    ->label('Encerrar')
                    ->icon('heroicon-o-stop')
                    ->color('danger')
                    ->visible(fn ($record) => $record?->isActive() ?? false)
                    ->requiresConfirmation()
                    ->authorize(fn ($record) => auth()->user()->can('close', $record))
                    ->action(function ($record) {

                        $record->update([
                            'status' => SurveyStatus::Closed,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pesquisa encerrada.')
                            ->send();
                    }),

                Action::make('exportCsv')
                    ->label('CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($record) {

                        $file = app(
                                SurveyCsvExporter::class
                            )->export($record);

                        return response()->download($file);
                    }),

                Action::make('exportPdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->color('danger')
                    ->authorize(fn ($record) => auth()->user()->can('export', $record))
                    ->action(function ($record) {
                        $file = app(
                            SurveyPdfExporter::class
                        )->export($record);

                        return response()->download($file);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($record) {

                            if ($record->hasResponses()) {

                                Notification::make()
                                    ->danger()
                                    ->title('Não é possível excluir pesquisas respondidas.')
                                    ->send();

                                return false;
                            }
                        }),

                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}