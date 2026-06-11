<?php

namespace App\Models;

use App\Enums\SurveyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\InviteStatus;

class Survey extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'anonymous',
        'starts_at',
        'ends_at',
        'thank_you_message',
    ];

    protected function casts(): array
    {
        return [
            'anonymous' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => SurveyStatus::class,
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function invites()
    {
        return $this->hasMany(Invite::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function questionsCount(): int
    {
        return $this->questions()->count();
    }

    public function invitesCount(): int
    {
        return $this->invites()->count();
    }

    public function responsesCount(): int
    {
        return $this->responses()->count();
    }

    public function totalInvites(): int
    {
        return $this->invitesCount();
    }

    public function totalResponses(): int
    {
        return $this->responsesCount();
    }

    public function averageRating5(): float
    {
        $answers = $this->responses()
            ->with('items.question')
            ->get()
            ->flatMap(function ($response) {

                return $response->items
                    ->filter(
                        fn ($item) =>
                            $item->question->type->value === 'rating_5'
                    )
                    ->pluck('answer');
            });

        if ($answers->isEmpty()) {
            return 0;
        }

        return round($answers->avg(), 2);
    }

    public function averageRating10(): float
    {
        $answers = $this->responses()
            ->with('items.question')
            ->get()
            ->flatMap(function ($response) {

                return $response->items
                    ->filter(
                        fn ($item) =>
                            $item->question->type->value === 'rating_10'
                    )
                    ->pluck('answer');
            });

        if ($answers->isEmpty()) {
            return 0;
        }

        return round($answers->avg(), 2);
    }

    public function textAnswers()
    {
        return $this->responses()
            ->with('items.question')
            ->get()
            ->flatMap(function ($response) {

                return $response->items
                    ->filter(
                        fn ($item) =>
                            $item->question->type->value === 'text'
                    )
                    ->pluck('answer');
            })
            ->filter()
            ->values();
    }

    public function isActive(): bool
    {
        return $this->status === \App\Enums\SurveyStatus::Active;
    }

    public function isClosed(): bool
    {
        return $this->status === \App\Enums\SurveyStatus::Closed;
    }

    public function isDraft(): bool
    {
        return $this->status === \App\Enums\SurveyStatus::Draft;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', SurveyStatus::Active);
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', SurveyStatus::Closed);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', SurveyStatus::Draft);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query
            ->where('status', SurveyStatus::Active)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
            });
    }

    public function isAvailable(): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function responseRate(): float
    {
        $invites = $this->invites()->count();

        if ($invites === 0) {
            return 0;
        }

        return round(
            ($this->responses()->count() / $invites) * 100,
            2
        );
    }

    public function answeredInvitesCount(): int
    {
        return $this->invites()
            ->where('status', InviteStatus::Answered)
            ->count();
    }

    public function pendingInvitesCount(): int
    {
        return $this->invites()
            ->where('status', InviteStatus::Pending)
            ->count();
    }

    public function hasQuestions(): bool
    {
        return $this->questions()->exists();
    }

    public function hasResponses(): bool
    {
        return $this->responses()->exists();
    }

    public function duplicate(): self
    {
        $survey = $this->replicate([
            'questions_count',
            'invites_count',
            'responses_count',
            'responses_avg',
        ]);

        $survey->title = "{$survey->title} (Cópia)";
        $survey->status = SurveyStatus::Draft;

        $survey->save();

        foreach ($this->questions as $question) {

            $newQuestion = $question->replicate();

            $newQuestion->survey_id = $survey->id;

            $newQuestion->save();
        }

        return $survey;
    }
}
