@extends('layouts.app')

@section('title', 'Courses')
@section('header', auth()->user()->hasRole('lecturer') ? 'My Assigned Courses' : 'Courses')

@section('actions')
    @can('create', App\Models\Course::class)
        <a href="{{ route('courses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add course</a>
    @endcan
@endsection

@section('content')
    <form method="GET" action="{{ route('courses.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search by code or title">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary"><i class="bi bi-search"></i> Search</button>
            @if ($search)
                <a href="{{ route('courses.index') }}" class="btn btn-link">Clear</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Lecturer</th>
                        <th>Credit units</th>
                        <th>Semester</th>
                        <th>Registered</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="fw-semibold">{{ $course->code }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->lecturer?->name ?? 'Not assigned' }}</td>
                            <td>{{ $course->credit_units }}</td>
                            <td>{{ $course->semester }}</td>
                            <td>
                                <span class="badge {{ $course->students_count >= $course->capacity ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $course->students_count }} / {{ $course->capacity }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">@include('courses._actions')</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No courses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $courses->links() }}</div>
@endsection
