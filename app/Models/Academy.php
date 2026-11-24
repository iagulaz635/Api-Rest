<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academy extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'phone',
        'email',
    ];

    // Una academia pertenece a un usuario (lado "N" de la relación 1:N)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Una academia tiene muchos cursos (lado "1" de la relación 1:N)
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}