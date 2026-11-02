<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'credit_units',
        'semester',
        'capacity',
        'lecturer_id',
    ];

    protected function casts(): array
    {
        return [
            'credit_units' => 'integer',
            'semester' => 'integer',
            'capacity' => 'integer',
        ];
    }

    /**
     * The lecturer assigned to teach this course.
     */
    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    /**
     * Registration records for this course.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class);
    }

    /**
     * Students registered for this course, through course_registrations.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_registrations')
            ->withPivot('id', 'academic_year')
            ->withTimestamps();
    }

    public function isFull(): bool
    {
        return $this->registrations()->count() >= $this->capacity;
    }
}
