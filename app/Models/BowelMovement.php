<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BowelMovement extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'logged_at',
        'bristol_type',
        'notes',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($poop) {
            if (empty($poop->uuid)) {
                $poop->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
