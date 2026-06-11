<h1>Pesquisa de Satisfação</h1>
<h2>{{ $survey->title }}</h1>

<p>
    Status:
    {{ $survey->status->value }}
</p>

<hr>

<h2>Métricas</h2>

<ul>
    <li>Convites: {{ $survey->totalInvites() }}</li>

    <li>Respostas: {{ $survey->totalResponses() }}</li>

    <li>Taxa: {{ $survey->responseRate() }}%</li>
</ul>

<hr>

<h2>Avaliações</h2>

<ul>
    <li>
        Média 1-5:
        {{ $survey->averageRating5() }}
    </li>

    <li>
        Média 1-10:
        {{ $survey->averageRating10() }}
    </li>
</ul>

<hr>

<h2>Comentários</h2>

@foreach($survey->textAnswers() as $comment)

    <p>
        • {{ $comment }}
    </p>

@endforeach