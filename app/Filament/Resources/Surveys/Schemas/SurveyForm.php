<?php

namespace App\Filament\Resources\Surveys\Schemas;

use App\Enums\SurveyStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados da Pesquisa')
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Configuração')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(SurveyStatus::class)
                            ->default(SurveyStatus::Draft)
                            ->required(),

                        Toggle::make('anonymous')
                            ->label('Pesquisa Anônima')
                            ->helperText('Quando habilitado, nome e e-mail não serão solicitados.')
                            ->default(false),
                    ])
                    ->columns(2),

                Section::make('Vigência')
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Início'),

                        DateTimePicker::make('ends_at')
                            ->label('Fim')
                            ->after('starts_at'),
                    ])
                    ->columns(2),

                Section::make('Mensagem Final')
                    ->schema([
                        Textarea::make('thank_you_message')
                            ->label('Mensagem de Agradecimento')
                            ->rows(4)
                            ->placeholder('Obrigado pela sua participação. Sua opinião é muito importante para nós.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}