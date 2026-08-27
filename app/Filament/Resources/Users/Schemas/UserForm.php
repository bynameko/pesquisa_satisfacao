<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados do Usuário')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->helperText('Deixe em branco para manter a senha atual.'),

                        Select::make('role')
                            ->label('Perfil')
                            ->options(UserRole::class)
                            ->default(UserRole::Usuario)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Usuário ativo')
                            ->default(true)
                            ->disabled(fn ($record) => $record?->is(auth()->user())),
                    ])
                    ->columns(2),
            ]);
    }
}
