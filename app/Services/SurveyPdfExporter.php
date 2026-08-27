<?php

namespace App\Services;

use App\Enums\QuestionType;
use App\Models\Survey;
use Barryvdh\DomPDF\Facade\Pdf;

class SurveyPdfExporter
{
    public function export(Survey $survey): string
    {
        $survey->load([
            'questions',
            'responses.items.question',
        ]);

        $summary = $this->buildSummary($survey);

        $questions = $survey->questions
            ->sortBy('sort_order')
            ->map(fn ($question) => $this->buildQuestionData($survey, $question));

        $pdf = Pdf::loadView(
            'pdf.survey-report',
            [
                'survey' => $survey,
                'summary' => $summary,
                'questions' => $questions,
            ]
        );

        $file = storage_path(
            'app/exports/pesquisa ' .
            $survey->title .
            ' - ' .
            $survey->id .
            '.pdf'
        );

        $pdf->save($file);

        return $file;
    }

    private function buildSummary(Survey $survey): array
    {
        $ratingQuestions = $survey->questions->whereIn('type', [
            QuestionType::Rating5,
            QuestionType::Rating10,
        ]);

        $averages = [];

        foreach ($ratingQuestions as $question) {

            $answers = $this->answers($survey, $question->id)
                ->map(fn ($value) => (float) $value);

            if ($answers->isEmpty()) {
                continue;
            }

            $average = $answers->avg();

            // Normaliza todas as notas para a escala de 1 a 5
            $average = match ($question->type) {
                QuestionType::Rating5 => $average,
                QuestionType::Rating10 => ($average / 10) * 5,
                default => null,
            };

            $averages[] = $average;
        }

        return [
            'responses' => $survey->responses->count(),

            'rating_questions' => $ratingQuestions->count(),

            'text_questions' => $survey->questions
                ->where('type', QuestionType::Text)
                ->count(),

            // Média geral na escala de 5
            'average' => count($averages) . " / 5"
                ? round(array_sum($averages) / count($averages), 2) . " / 5"
                : null,
        ];
    }

    private function buildQuestionData(Survey $survey, $question): array
    {
        $answers = $this->answers($survey, $question->id);

        if ($question->type === QuestionType::Text) {

            return [
                'question' => $question,
                'comments' => $answers
                    ->filter()
                    ->values(),
            ];
        }

        $values = $answers
            ->map(fn ($value) => (float) $value);

        return [
            'question' => $question,

            'count' => $values->count(),

            'average' => $values->count()
                ? round($values->avg(), 2)
                : null,

            'min' => $values->count()
                ? $values->min()
                : null,

            'max' => $values->count()
                ? $values->max()
                : null,

            'distribution' => $values
                ->countBy()
                ->sortKeys(),

            'performance' => $this->performance(
                $question->type,
                $values->avg()
            ),
        ];
    }

    private function answers(Survey $survey, int $questionId)
    {
        return $survey->responses
            ->flatMap->items
            ->where('question_id', $questionId)
            ->pluck('answer');
    }

    private function performance(
        QuestionType $type,
        ?float $average
    ): array {

        if ($average === null) {
            return [
                'label' => 'Sem respostas',
                'color' => '#9ca3af',
            ];
        }

        $percent = match ($type) {
            QuestionType::Rating5 => ($average / 5) * 100,
            QuestionType::Rating10 => ($average / 10) * 100,
            default => 0,
        };

        return match (true) {
            $percent >= 90 => [
                'label' => 'Excelente',
                'color' => '#16a34a',
            ],

            $percent >= 70 => [
                'label' => 'Bom',
                'color' => '#ca8a04',
            ],

            default => [
                'label' => 'Necessita atenção',
                'color' => '#dc2626',
            ],
        };
    }
}