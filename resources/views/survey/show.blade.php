<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ $survey->title }}</title>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f5f7fa;
        font-family: Arial, Helvetica, sans-serif;
        color: #1f2937;
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }

    .card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }

    h1 {
        margin-top: 0;
    }

    .description {
        color: #6b7280;
        margin-bottom: 30px;
    }

    .question {
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e5e7eb;
    }

    .question:last-child {
        border-bottom: none;
    }

    .question-title {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .question-description {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .required {
        color: #dc2626;
    }

    input[type="text"],
    input[type="email"],
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
    }

    textarea {
        resize: vertical;
    }

    .rating {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .rating label {
        display: flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }

    .submit-button {
        width: 100%;
        border: none;
        border-radius: 10px;
        background: #2563eb;
        color: white;
        padding: 14px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }

    .submit-button:hover {
        background: #1d4ed8;
    }

    .field {
        margin-bottom: 20px;
    }

    .errors {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .rating-scale {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }

    .rating-scale input[type="radio"] {
        display: none;
    }

    .rating-scale label {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #d1d5db;
        border-radius: 8px;

        cursor: pointer;

        font-weight: 600;

        transition: .2s;
    }

    .rating-scale label:hover {
        background: #eff6ff;
        border-color: #2563eb;
    }

    .rating-scale input:checked + label {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .rating-legend {
        display: flex;
        justify-content: space-between;

        margin-top: 8px;

        font-size: 13px;
        color: #6b7280;
    }

</style>

</head>

<body>

<div class="container">

<div class="card">

    <h1>{{ $survey->title }}</h1>

    @if($survey->description)
        <div class="description">
            {{ $survey->description }}
        </div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <strong>Existem campos pendentes:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('survey.submit', $invite->token) }}">

        @csrf

        @unless($survey->anonymous)

            <div class="field">
                <label>Nome</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="field">
                <label>E-mail</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >
            </div>

        @endunless

        @foreach($survey->questions->sortBy('sort_order') as $question)

            <div class="question">

                <div class="question-title">
                    {{ $question->title }}

                    @if($question->required)
                        <span class="required">*</span>
                    @endif
                </div>

                @if($question->description)
                    <div class="question-description">
                        {{ $question->description }}
                    </div>
                @endif

                @if($question->type->value === 'rating_5')

                    <div>

                        <div class="rating-scale">

                            @for($i = 1; $i <= 5; $i++)

                                <input
                                    id="q{{ $question->id }}_{{ $i }}"
                                    type="radio"
                                    name="questions[{{ $question->id }}]"
                                    value="{{ $i }}"
                                    @checked(old("questions.{$question->id}") == $i)
                                >

                                <label for="q{{ $question->id }}_{{ $i }}">
                                    {{ $i }}
                                </label>

                            @endfor

                        </div>

                        <div class="rating-legend">
                            <span>Muito insatisfeito</span>
                            <span>Muito satisfeito</span>
                        </div>

                    </div>

                @endif

                @if($question->type->value === 'rating_10')

                    <div>

                        <div class="rating-scale">

                            @for($i = 1; $i <= 10; $i++)

                                <input
                                    id="q{{ $question->id }}_{{ $i }}"
                                    type="radio"
                                    name="questions[{{ $question->id }}]"
                                    value="{{ $i }}"
                                    @checked(old("questions.{$question->id}") == $i)
                                >

                                <label for="q{{ $question->id }}_{{ $i }}">
                                    {{ $i }}
                                </label>

                            @endfor

                        </div>

                        <div class="rating-legend">
                            <span>Pouco provável</span>
                            <span>Muito provável</span>
                        </div>

                    </div>

                @endif

                @if($question->type->value === 'text')

                    <textarea
                        rows="4"
                        name="questions[{{ $question->id }}]"
                        placeholder="Digite sua resposta"
                    >{{ old("questions.{$question->id}") }}</textarea>

                @endif

            </div>

        @endforeach

        <button
            type="submit"
            class="submit-button"
        >
            Enviar Pesquisa
        </button>

    </form>

</div>

</div>

</body>
</html>
