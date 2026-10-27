@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header', 'My Dashboard')

@section('actions')
    @if ($student)
        <a href="{{ route('students.show', $student) }}" class="btn btn-outline-primary"><i class="bi bi-person"></i> My profile</a>
    @endif
@endsection

@section('content')
    @if ($student)
        <p class="text-muted">
            {{ $student->full_name }} &middot; {{ $student->registration_number }} &middot; {{ $student->programme }}, Year {{ $student->year_of_study }}
        </p>
    @else
        <div class="alert alert-warning">Your account is not linked to a student record yet. Please contact the administrator.</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                    <span>My registered courses ({{ $courses->count() }})</span>
                    <a href="{{ route('registrations.index') }}" class="small">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($courses as $course)
                        <li class="list-group-item">
                            <span class="fw-semibold">{{ $course->code }}</span> {{ $course->title }}
                            <span class="badge bg-light text-dark float-end">{{ $course->credit_units }} CU</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">You have not registered for any courses yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Available courses</div>
                <ul class="list-group list-group-flush">
                    @forelse ($available as $course)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold">{{ $course->code }}</span> {{ $course->title }}
                                <div class="small text-muted">{{ $course->lecturer?->name ?? 'Lecturer not assigned' }} &middot; {{ $course->students_count }}/{{ $course->capacity }} registered</div>
                            </div>
                            @can('create', App\Models\CourseRegistration::class)
                                @if ($course->students_count < $course->capacity)
                                    <form method="POST" action="{{ route('registrations.store') }}">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button class="btn btn-sm btn-primary">Register</button>
                                    </form>
                                @else
                                    <span class="badge bg-danger">Full</span>
                                @endif
                            @endcan
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No other courses are available.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
