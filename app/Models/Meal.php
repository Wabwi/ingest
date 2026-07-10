<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'meal_type',
        'description',
        'eaten_at',
    ];

    protected $casts = [
        'eaten_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($meal) {
            if (empty($meal->uuid)) {
                $meal->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
