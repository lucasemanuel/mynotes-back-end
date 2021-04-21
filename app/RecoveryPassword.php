<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecoveryPassword extends Model
{
    protected $fillable = [
        'user_id',
    ];

    protected $hidden = [
        'id',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
