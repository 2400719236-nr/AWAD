<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validation for creating and updating students.
 */
class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $student instanceof Student
            ? $this->user()->can('update', $student)
            : $this->user()->can('create', Student::class);
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $userId = $student?->user_id;

        return [
            'registration_number' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student)],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('students')->ignore($student),
                Rule::unique('users')->ignore($userId),
            ],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'date_of_birth' => ['nullable', 'date', 'before:-15 years'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\s-]{10,15}$/'],
            'programme' => ['required', 'string', 'max:100'],
            'year_of_study' => ['required', 'integer', 'between:1,5'],
            // Optional: giving a password creates (or resets) the student's login account
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'date_of_birth.before' => 'The student must be at least 15 years old.',
            'phone.regex' => 'Enter a valid phone number, e.g. 0701000001.',
        ];
    }
}
