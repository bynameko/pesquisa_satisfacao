<?php

namespace App\Services;

use App\Models\Survey;
use Illuminate\Support\Str;

class SurveyCsvExporter
{
    public function export(Survey $survey): string
    {
        $directory = storage_path('app/exports');

        $survey->load([
            'questions',
            'invites',
            'responses.respondent',
            'responses.items',
        ]);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = sprintf(
            '%s/%s-%s.csv',
            $directory,
            Str::slug($survey->title),
            $survey->id
        );

        $handle = fopen($file, 'w');

        /*
        |--------------------------------------------------------------------------
        | Pesquisa
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, ['PESQUISA'], ';');

        fputcsv($handle, [
            'Título',
            $survey->title,
        ], ';');

        fputcsv($handle, [
            'Status',
            $survey->status->value,
        ], ';');

        fputcsv($handle, [
            'Criada em',
            $survey->created_at?->format('d/m/Y H:i'),
        ], ';');

        fputcsv($handle, [], ';');

        /*
        |--------------------------------------------------------------------------
        | Métricas
        |--------------------------------------------------------------------------
        */

        $totalInvites = $survey->invites->count();

        $totalResponses = $survey->responses->count();

        $responseRate = $totalInvites > 0
            ? round(($totalResponses / $totalInvites) * 100, 2)
            : 0;

        fputcsv($handle, ['MÉTRICAS'], ';');

        fputcsv($handle, [
            'Convites Gerados',
            $totalInvites,
        ], ';');

        fputcsv($handle, [
            'Respostas Recebidas',
            $totalResponses,
        ], ';');

        fputcsv($handle, [
            'Taxa de Resposta',
            "{$responseRate}%",
        ], ';');

        fputcsv($handle, [], ';');

        /*
        |--------------------------------------------------------------------------
        | Convites
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, ['CONVITES'], ';');

        fputcsv($handle, [
            'Token',
            'Status',
            'Respondido em',
        ], ';');

        foreach ($survey->invites as $invite) {

            fputcsv($handle, [
                $invite->token,
                $invite->status->value,
                $invite->responded_at?->format('d/m/Y H:i'),
            ], ';');
        }

        fputcsv($handle, [], ';');

        /*
        |--------------------------------------------------------------------------
        | Respostas
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, ['RESPOSTAS'], ';');

        $headers = [
            'Nome',
            'E-mail',
            'Respondido em',
        ];

        foreach ($survey->questions->sortBy('sort_order') as $question) {
            $headers[] = $question->title;
        }

        fputcsv($handle, $headers, ';');

        foreach ($survey->responses->sortBy('submitted_at') as $response) {

            $row = [
                $response->respondent?->name ?? 'Anônimo',
                $response->respondent?->email ?? '',
                $response->submitted_at?->format('d/m/Y H:i'),
            ];

            foreach ($survey->questions->sortBy('sort_order') as $question) {

                $row[] = $response->answerFor(
                    $question->id
                );
            }

            fputcsv($handle, $row, ';');
        }

        fclose($handle);

        return $file;
    }
}