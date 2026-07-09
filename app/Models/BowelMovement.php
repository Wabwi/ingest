<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BowelMovement extends Model
{
    protected $fillable = [
        'user_id',
        'logged_at',
        'bristol_type',
        'notes',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
