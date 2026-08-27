<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        h1 {
            margin: 0;
            color: #1f2937;
        }

        h2 {
            margin-top: 30px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #ddd;
            color: #1f2937;
        }

        h3 {
            margin: 0 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            width: 25%;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .summary strong {
            display: block;
            font-size: 18px;
            margin-bottom: 4px;
        }

        .card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .stats {
            margin-top: 8px;
        }

        .stats td {
            padding: 3px 0;
        }

        .bar {
            height: 16px;
            background: #eeeeee;
            margin-bottom: 6px;
        }

        .fill {
            height: 16px;
            background: #3b82f6;
        }

        .badge {
            display: inline-block;
            color: white;
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 3px;
        }

        .comment {
            border-left: 4px solid #d1d5db;
            background: #f8f8f8;
            padding: 8px;
            margin-bottom: 8px;
        }

        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #888;
        }
    </style>

</head>

<body>

<h1>{{ $survey->title }}</h1>

<p>

    <strong>Status:</strong>
    {{ $survey->status->getLabel() }}

    <br>

    <strong>Data do relatório:</strong>
    {{ now()->format('d/m/Y H:i') }}

    <br>

    <strong>Data de início:</strong>
    {{ $survey->starts_at->format('d/m/Y H:i') }}

    <br>

    <strong>Data de conclusão:</strong>
    {{ $survey->ends_at->format('d/m/Y H:i') }}

</p>

<h2>Resumo Executivo</h2>

<table class="summary">

<tr>

<td>

<strong>{{ $summary['responses'] }}</strong>

Respostas

</td>

<td>

<strong>{{ $summary['rating_questions'] }}</strong>

Perguntas avaliativas

</td>

<td>

<strong>{{ $summary['text_questions'] }}</strong>

Perguntas abertas

</td>

<td>

<strong>{{ $summary['average'] ?? '-' }}</strong>

Média Geral

</td>

</tr>

</table>

<h2>Resultados</h2>

@foreach($questions as $item)

<div class="card">

<h3>

{{ $item['question']->sort_order }}.

{{ $item['question']->title }}

</h3>

@if($item['question']->type === \App\Enums\QuestionType::Text)

@foreach($item['comments']->take(15) as $comment)

<div class="comment">

{{ $comment }}

</div>

@endforeach

@if($item['comments']->count() > 15)

<p>

Mostrando 15 de
{{ $item['comments']->count() }}
comentários.

</p>

@endif

@else

<table class="stats">

<tr>

<td width="25%">

<b>Média</b>

</td>

<td>

{{ $item['average'] }}

</td>

</tr>

<tr>

<td>

<b>Respostas</b>

</td>

<td>

{{ $item['count'] }}

</td>

</tr>

<tr>

<td>

<b>Maior Nota</b>

</td>

<td>

{{ $item['max'] }}

</td>

</tr>

<tr>

<td>

<b>Menor Nota</b>

</td>

<td>

{{ $item['min'] }}

</td>

</tr>

</table>

<br>

<span
class="badge"
style="background: {{ $item['performance']['color'] }}"
>

{{ $item['performance']['label'] }}

</span>

<br><br>

@php

$max = max($item['distribution']->toArray());

@endphp

@foreach($item['distribution'] as $nota => $total)

<table>

<tr>

<td width="35">

{{ $nota }}

</td>

<td width="420">

<div class="bar">

<div
class="fill"
style="width: {{ ($total / $max) * 100 }}%;">

</div>

</div>

</td>

<td>

{{ $total }}

</td>

</tr>

</table>

@endforeach

@endif

</div>

@endforeach

<div class="footer">

Pesquisa de Satisfação • Emitido em {{ now()->format('d/m/Y') }}

</div>

</body>

</html>