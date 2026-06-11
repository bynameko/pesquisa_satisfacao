<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'survey_id',
        'invite_id',
        'respondent_id',
        'submitted_at',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function invite()
    {
        return $this->belongsTo(Invite::class);
    }

    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }

    public function items()
    {
        return $this->hasMany(ResponseItem::class);
    }

    public function answerFor(int $questionId): ?string
    {
        return $this->items
            ->firstWhere('question_id', $questionId)
            ?->answer;
    }

    public function formattedAnswers(): array
    {
        return $this->items
            ->map(function ($item) {

                return [
                    'question' => $item->question->title,
                    'answer' => $item->answer,
                ];
            })
            ->toArray();
    }

    public function getAnswersHtmlAttribute(): string
    {
        return $this->items
            ->map(function ($item) {

                $answer = e($item->answer);

                if (is_numeric($item->answer)) {
                    $answer = "<strong>{$answer}</strong>";
                }

                return "
                    <div style='margin-bottom:16px'>
                        <div style='font-weight:600'>
                            {$item->question->title}
                        </div>

                        <div>
                            {$answer}
                        </div>
                    </div>
                ";
            })
            ->implode('');
    }
}
