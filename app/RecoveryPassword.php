<?php

namespace App;

use Carbon\Carbon;
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

    public function scopeNotExpired($query)
    {
        return $query->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()]);
    }
}
