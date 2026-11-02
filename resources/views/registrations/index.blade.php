@extends('layouts.app')

@section('title', $seesAll ? 'Registrations' : 'My Registrations')
@section('header', $seesAll ? 'Course Registrations' : 'My Registrations')

@section('actions')
    @can('create', App\Models\CourseRegistration::class)
        <a href="{{ route('registrations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> {{ $seesAll ? 'Register a student' : 'Register for a course' }}
        </a>
    @endcan
@endsection

@section('content')
    @if ($seesAll)
        <form method="GET" action="{{ route('registrations.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <select name="course_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All courses</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->code }}: {{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        @if ($seesAll)
                            <th>Student</th>
                        @endif
                        <th>Course</th>
                        <th>Lecturer</th>
                        <th>Academic year</th>
                        <th>Registered on</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $registration)
                        <tr>
                            @if ($seesAll)
                                <td>
                                    <a href="{{ route('students.show', $registration->student) }}">{{ $registration->student->full_name }}</a>
                                    <div class="small text-muted">{{ $registration->student->registration_number }}</div>
                                </td>
                            @endif
                            <td>
                                <a href="{{ route('courses.show', $registration->course) }}" class="fw-semibold">{{ $registration->course->code }}</a>
                                <div class="small text-muted">{{ $registration->course->title }}</div>
                            </td>
                            <td>{{ $registration->course->lecturer?->name ?? 'Not assigned' }}</td>
                            <td>{{ $registration->academic_year }}</td>
                            <td>{{ $registration->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                @can('delete', $registration)
                                    <form method="POST" action="{{ route('registrations.destroy', $registration) }}" class="d-inline"
                                          onsubmit="return confirm('Remove this registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> {{ $seesAll ? 'Remove' : 'Drop' }}</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $seesAll ? 6 : 5 }}" class="text-center text-muted py-4">No registrations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $registrations->links() }}</div>
@endsection
