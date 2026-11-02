<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_number',
        'department',
        'phone',
    ];

    /**
     * The login account of this lecturer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Courses assigned to this lecturer.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function getNameAttribute(): string
    {
        return $this->user?->name ?? $this->staff_number;
    }
}
