<?php

namespace App\Filament\Widgets;

use App\Enums\SurveyStatus;
use App\Models\Invite;
use App\Models\Response;
use App\Models\Survey;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SurveyStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalInvites = Invite::count();

        $totalResponses = Response::count();

        $responseRate = $totalInvites > 0
            ? round(($totalResponses / $totalInvites) * 100, 2)
            : 0;

        return [

            Stat::make(
                'Pesquisas Ativas',
                Survey::active()->count()
            )
                ->description('Pesquisas em andamento')
                ->descriptionIcon('heroicon-m-play'),

            Stat::make(
                'Pesquisas Encerradas',
                Survey::closed()->count()
            )
                ->description('Pesquisas já concluídas')
                ->descriptionIcon('heroicon-m-check'),

            Stat::make(
                'Convites Gerados',
                $totalInvites
            )
                ->description('Convites totais')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text'),

            Stat::make(
                'Respostas Recebidas',
                $totalResponses
            )
                ->description('Total de resposta recebidas')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right'),

            Stat::make(
                'Taxa de Resposta',
                "{$responseRate}%"
            )
                ->description('Percentual de respostas')
                ->descriptionIcon('heroicon-m-check-badge'),

        ];
    }
}