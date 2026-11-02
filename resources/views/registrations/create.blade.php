@extends('layouts.app')

@section('title', 'New registration')
@section('header', $isAdmin ? 'Register a student for a course' : 'Register for a course')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($courses->isEmpty())
                <p class="text-muted mb-0">There are no more courses available for you to register for.</p>
            @else
                <form method="POST" action="{{ route('registrations.store') }}" novalidate>
                    @csrf

                    <div class="row g-3">
                        @if ($isAdmin)
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">Student</label>
                                <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                    <option value="">Choose a student...</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                            {{ $student->registration_number }}: {{ $student->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label for="course_id" class="form-label">Course</label>
                            <select id="course_id" name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                <option value="">Choose a course...</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" @selected(old('course_id', $selectedCourse) == $course->id)
                                            @disabled($course->students_count >= $course->capacity)>
                                        {{ $course->code }}: {{ $course->title }}
                                        ({{ $course->students_count }}/{{ $course->capacity }}{{ $course->students_count >= $course->capacity ? ', full' : '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Register</button>
                        <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
