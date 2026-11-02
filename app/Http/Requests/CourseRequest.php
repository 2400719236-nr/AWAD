<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validation for creating and updating courses.
 */
class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $course instanceof Course
            ? $this->user()->can('update', $course)
            : $this->user()->can('create', Course::class);
    }

    protected function prepareForValidation(): void
    {
        // Course codes are stored in upper case, e.g. BUC3131
        if ($this->filled('code')) {
            $this->merge(['code' => strtoupper(trim($this->input('code')))]);
        }
    }

    public function rules(): array
    {
        $course = $this->route('course');

        return [
            'code' => ['required', 'string', 'max:10', 'regex:/^[A-Z]{2,4}\d{3,4}$/', Rule::unique('courses')->ignore($course)],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'credit_units' => ['required', 'integer', 'between:1,6'],
            'semester' => ['required', 'integer', Rule::in([1, 2])],
            'capacity' => [
                'required', 'integer', 'between:1,500',
                // Capacity cannot drop below the students already registered
                'min:'.($course?->registrations()->count() ?: 1),
            ],
            'lecturer_id' => ['nullable', 'integer', 'exists:lecturers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'The course code must look like BUC3131.',
            'capacity.min' => 'Capacity cannot be lower than the number of students already registered (:min).',
        ];
    }

    public function attributes(): array
    {
        return ['lecturer_id' => 'lecturer'];
    }
}
