<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    protected $fillable = [
        'invite_id',
        'name',
        'email',
    ];

    public function invite()
    {
        return $this->belongsTo(Invite::class);
    }

    public function response()
    {
        return $this->hasOne(Response::class);
    }
}
