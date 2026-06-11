<?php

namespace App\Filament\Resources\Surveys\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\InviteStatus;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class InvitesRelationManager extends RelationManager
{
    protected static string $relationship = 'invites';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('token')
            ->columns([
                TextColumn::make('token')
                    ->label('Token')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state->value) {
                        'pending' => 'warning',
                        'answered' => 'success',
                        'expired' => 'danger',
                    }),

                TextColumn::make('link')
                    ->label('Link')
                    ->state(
                        fn ($record) => route('survey.respond', [
                            'token' => $record->token,
                        ])
                    )
                    ->copyable()
                    ->limit(50),

                TextColumn::make('generated_batch')
                    ->label('Lote'),

                TextColumn::make('responded_at')
                    ->label('Respondido em')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('generateInvites')
                    ->label('Gerar Convites')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->form([
                        TextInput::make('quantity')
                            ->label('Quantidade')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(10000),

                        TextInput::make('generated_batch')
                            ->label('Lote')
                            ->placeholder('Ex.: Clientes Julho'),
                    ])
                    ->action(function (array $data) {

                        for ($i = 0; $i < $data['quantity']; $i++) {

                            $this->ownerRecord
                                ->invites()
                                ->create([
                                    'token' => (string) Str::ulid(),
                                    'status' => InviteStatus::Pending,
                                    'generated_batch' => $data['generated_batch'],
                                ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('Convites gerados')
                            ->body("{$data['quantity']} convites criados com sucesso.")
                            ->send();
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(fn ($record) => $record->isPending()),
            ]);
    }

    
}
