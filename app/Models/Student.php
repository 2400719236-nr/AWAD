<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_number',
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth',
        'phone',
        'programme',
        'year_of_study',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'year_of_study' => 'integer',
        ];
    }

    /**
     * The login account of this student (if one has been created).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registration records belonging to this student.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class);
    }

    /**
     * Courses this student is registered for, through course_registrations.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_registrations')
            ->withPivot('id', 'academic_year')
            ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
