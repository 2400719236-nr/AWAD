@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header', 'Administrator Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        @foreach ([
            ['label' => 'Students', 'count' => $studentCount, 'icon' => 'people', 'color' => 'primary', 'route' => 'students.index'],
            ['label' => 'Courses', 'count' => $courseCount, 'icon' => 'journal-bookmark', 'color' => 'success', 'route' => 'courses.index'],
            ['label' => 'Registrations', 'count' => $registrationCount, 'icon' => 'card-checklist', 'color' => 'warning', 'route' => 'registrations.index'],
        ] as $stat)
            <div class="col-md-4">
                <a href="{{ route($stat['route']) }}" class="text-decoration-none text-reset">
                    <div class="card shadow-sm border-0 border-start border-4 border-{{ $stat['color'] }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">{{ $stat['label'] }}</div>
                                <div class="fs-2 fw-semibold">{{ $stat['count'] }}</div>
                            </div>
                            <i class="bi bi-{{ $stat['icon'] }} fs-1 text-{{ $stat['color'] }}"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold d-flex justify-content-between">
            <span>Latest registrations</span>
            <a href="{{ route('registrations.create') }}" class="small">Register a student</a>
        </div>
        <ul class="list-group list-group-flush">
            @forelse ($latestRegistrations as $registration)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $registration->student->full_name }} &rarr; <span class="fw-semibold">{{ $registration->course->code }}</span></span>
                    <span class="text-muted small">{{ $registration->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No registrations yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
