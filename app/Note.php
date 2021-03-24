<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public const LIMIT_NOTE_BY_USER = 128;

    protected $table = 'notes';

    protected $fillable = [
        'body', 'is_favorite'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
