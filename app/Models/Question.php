<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_id',
        'title',
        'description',
        'type',
        'required',
        'sort_order',
        'placeholder',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'type' => QuestionType::class,
        ];
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
