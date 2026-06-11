<?php

namespace App\Services;

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

        $pdf = Pdf::loadView(
            'pdf.survey-report',
            [
                'survey' => $survey,
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
}