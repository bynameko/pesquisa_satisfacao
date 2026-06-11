@php

$messages = [

    'answered' => [
        'icon' => '✅',
        'title' => 'Pesquisa já respondida',
        'message' => 'Sua resposta já foi registrada. Obrigado pela participação.',
    ],

    'expired' => [
        'icon' => '⏰',
        'title' => 'Prazo encerrado',
        'message' => 'O período para responder esta pesquisa foi encerrado.',
    ],

    'survey_unavailable' => [
        'icon' => '🔒',
        'title' => 'Pesquisa indisponível',
        'message' => 'Esta pesquisa não está disponível para resposta no momento.',
    ],

    'invalid' => [
        'icon' => '⚠️',
        'title' => 'Link inválido',
        'message' => 'O link informado não é válido.',
    ],

];

$current = $messages[$reason] ?? $messages['invalid'];

@endphp


<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa indisponível</title>


<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: #f5f7fa;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }

    .card {
        width: 100%;
        max-width: 520px;
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
    }

    .icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    h1 {
        margin: 0 0 15px;
        color: #1f2937;
    }

    p {
        color: #6b7280;
        line-height: 1.6;
        margin: 0;
    }

    .footer {
        margin-top: 30px;
        font-size: 14px;
        color: #9ca3af;
    }

</style>


</head>

<body>

<div class="card">


<div class="icon">
    {{ $current['icon'] }}
</div>

<h1>
    {{ $current['title'] }}
</h1>

<p>
    {{ $current['message'] }}
</p>

<div class="footer">
    Caso você acredite que isso seja um erro,
    entre em contato com a empresa responsável pela pesquisa.
</div>


</div>

</body>

</html>
