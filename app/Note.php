<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $fillable = [
        'body', 'is_favorite'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
