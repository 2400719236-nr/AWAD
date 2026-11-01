@extends('layouts.app')

@section('title', 'Administrator Dashboard')

@section('content')
    <div class="card welcome-card mb-4">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h4 fw-bold mb-1">Administrator Dashboard</h1>
                <p class="text-white-75 mb-0">Welcome back, {{ auth()->user()->name }}. Here is what is happening this academic year.</p>
            </div>
            <a href="{{ route('registrations.create') }}" class="btn btn-light text-primary fw-semibold"><i class="bi bi-plus-lg"></i> Register a student</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">@include('partials.stat-card', ['label' => 'Students', 'value' => $studentCount, 'icon' => 'people', 'color' => 'primary', 'url' => route('students.index')])</div>
        <div class="col-sm-6 col-xl-3">@include('partials.stat-card', ['label' => 'Courses', 'value' => $courseCount, 'icon' => 'journal-bookmark', 'color' => 'success', 'url' => route('courses.index')])</div>
        <div class="col-sm-6 col-xl-3">@include('partials.stat-card', ['label' => 'Lecturers', 'value' => $lecturerCount, 'icon' => 'person-video3', 'color' => 'info'])</div>
        <div class="col-sm-6 col-xl-3">@include('partials.stat-card', ['label' => 'Registrations', 'value' => $registrationCount, 'icon' => 'card-checklist', 'color' => 'warning', 'url' => route('registrations.index')])</div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold">Course enrolment</span>
                    <a href="{{ route('courses.index') }}" class="small">All courses</a>
                </div>
                <div class="card-body">
                    @forelse ($courses as $course)
                        @php($percent = $course->capacity ? min(100, round($course->students_count / $course->capacity * 100)) : 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span><a href="{{ route('courses.show', $course) }}" class="fw-semibold">{{ $course->code }}</a> {{ $course->title }}</span>
                                <span class="text-muted">{{ $course->students_count }}/{{ $course->capacity }}</span>
                            </div>
                            <div class="progress"><div class="progress-bar" style="width: {{ $percent }}%"></div></div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No courses yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header bg-white fw-semibold py-3">Quick actions</div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach ([
                            ['route' => 'students.create', 'icon' => 'person-plus', 'label' => 'Add student', 'color' => 'primary'],
                            ['route' => 'courses.create', 'icon' => 'journal-plus', 'label' => 'Add course', 'color' => 'success'],
                            ['route' => 'registrations.create', 'icon' => 'clipboard-plus', 'label' => 'New registration', 'color' => 'warning'],
                            ['route' => 'registrations.index', 'icon' => 'list-check', 'label' => 'All registrations', 'color' => 'info'],
                        ] as $action)
                            <div class="col-6">
                                <a href="{{ route($action['route']) }}" class="quick-action d-block border rounded-3 p-3 text-center">
                                    <span class="stat-icon bg-soft-{{ $action['color'] }} mb-2 mx-auto" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-{{ $action['icon'] }}"></i>
                                    </span>
                                    <span class="small fw-semibold">{{ $action['label'] }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white fw-semibold py-3">Latest registrations</div>
                <ul class="list-group list-group-flush">
                    @forelse ($latestRegistrations as $registration)
                        <li class="list-group-item d-flex align-items-center gap-3 py-3">
                            <span class="avatar">{{ strtoupper(substr($registration->student->first_name, 0, 1)) }}</span>
                            <div class="flex-grow-1 lh-sm">
                                <div class="fw-semibold small">{{ $registration->student->full_name }}</div>
                                <div class="text-muted small">registered for {{ $registration->course->code }}</div>
                            </div>
                            <span class="text-muted small">{{ $registration->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No registrations yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
