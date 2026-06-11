<?php

namespace App\Models;

use App\Enums\InviteStatus;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'survey_id',
        'token',
        'status',
        'generated_batch',
        'responded_at',
        'responded_ip',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
            'status' => InviteStatus::class,
        ];
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function respondent()
    {
        return $this->hasOne(Respondent::class);
    }

    public function response()
    {
        return $this->hasOne(Response::class);
    }

    public function isPending(): bool
    {
        return $this->status === InviteStatus::Pending;
    }

    public function isAnswered(): bool
    {
        return $this->status === InviteStatus::Answered;
    }

    public function isExpired(): bool
    {
        return $this->status === InviteStatus::Expired;
    }

    public function canBeAnswered(): bool
    {
        if (! $this->isPending()) {
            return false;
        }

        return $this->survey->isAvailable();
    }

    public function unavailableReason(): string
    {
        if ($this->isAnswered()) {
            return 'answered';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if (! $this->survey->isAvailable()) {
            return 'survey_unavailable';
        }

        return 'invalid';
    }
}
