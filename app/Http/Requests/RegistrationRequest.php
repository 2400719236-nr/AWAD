<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\CourseRegistration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Validation for registering a student on a course.
 */
class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CourseRegistration::class);
    }

    protected function prepareForValidation(): void
    {
        // Students can only register themselves, whatever the form sends
        if (! $this->user()->can('manage registrations')) {
            $this->merge(['student_id' => $this->user()->student?->id]);
        }
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'course_id' => [
                'required', 'integer', 'exists:courses,id',
                // Duplicate registration check: one row per student per course
                Rule::unique('course_registrations')->where('student_id', $this->input('student_id')),
            ],
        ];
    }

    /**
     * Extra check once the basic rules pass: the course must have space.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $course = Course::find($this->input('course_id'));
                if ($course->isFull()) {
                    $validator->errors()->add('course_id', "{$course->code} is full ({$course->capacity} students).");
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Your account is not linked to a student record. Please contact the administrator.',
            'course_id.unique' => 'This student is already registered for that course.',
        ];
    }

    public function attributes(): array
    {
        return ['student_id' => 'student', 'course_id' => 'course'];
    }
}
