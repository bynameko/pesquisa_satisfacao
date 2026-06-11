<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Obrigado</title>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f5f7fa;
        font-family: Arial, Helvetica, sans-serif;

        display: flex;
        align-items: center;
        justify-content: center;

        min-height: 100vh;
        padding: 20px;
    }

    .card {
        width: 100%;
        max-width: 600px;

        background: white;

        border-radius: 16px;

        padding: 40px;

        text-align: center;

        box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }

    .icon {
        font-size: 72px;
        margin-bottom: 20px;
    }

    h1 {
        margin: 0 0 15px;
        color: #111827;
    }

    .message {
        color: #6b7280;
        line-height: 1.7;
        font-size: 16px;
    }

    .footer {
        margin-top: 30px;
        color: #9ca3af;
        font-size: 14px;
    }

</style>

</head>

<body>

<div class="card">

<div class="icon">
    ✅
</div>

<h1>Obrigado pela sua participação!</h1>

<div class="message">

    {{ $survey->thank_you_message
        ?? 'Sua resposta foi registrada com sucesso.' }}

</div>

<div class="footer">
    Sua opinião é muito importante para nós.
</div>

</div>

</body>
</html>
