@extends('layouts.app')

@section('title', 'Lecturer Dashboard')

@section('content')
    <div class="card welcome-card mb-4">
        <div class="card-body p-4">
            <h1 class="h4 fw-bold mb-1">Hello, {{ auth()->user()->name }}</h1>
            <p class="text-white-75 mb-0">
                {{ $lecturer?->department ? $lecturer->department.' department' : 'Lecturer' }}
                @if ($lecturer) &middot; Staff no. {{ $lecturer->staff_number }} @endif
            </p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6">@include('partials.stat-card', ['label' => 'Assigned courses', 'value' => $courses->count(), 'icon' => 'journal-bookmark', 'color' => 'primary'])</div>
        <div class="col-sm-6">@include('partials.stat-card', ['label' => 'Students taught', 'value' => $totalStudents, 'icon' => 'people', 'color' => 'success'])</div>
    </div>

    <h2 class="h5 fw-bold mb-3">My Assigned Courses</h2>
    <div class="row g-4">
        @forelse ($courses as $course)
            @php($percent = $course->capacity ? min(100, round($course->students_count / $course->capacity * 100)) : 0)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-soft-primary">{{ $course->code }}</span>
                            <span class="small text-muted">Semester {{ $course->semester }} &middot; {{ $course->credit_units }} CU</span>
                        </div>
                        <h3 class="h6 fw-semibold">{{ $course->title }}</h3>
                        <div class="d-flex justify-content-between small text-muted mt-3 mb-1">
                            <span>Registered students</span>
                            <span>{{ $course->students_count }} / {{ $course->capacity }}</span>
                        </div>
                        <div class="progress mb-3"><div class="progress-bar bg-success" style="width: {{ $percent }}%"></div></div>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-people"></i> View class list</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card"><div class="card-body text-center text-muted py-5">No courses have been assigned to you yet.</div></div>
            </div>
        @endforelse
    </div>
@endsection
