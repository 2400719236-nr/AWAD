@extends('layouts.app')

@section('title', 'Lecturer Dashboard')
@section('header', 'My Assigned Courses')

@section('content')
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Semester</th>
                        <th>Registered students</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="fw-semibold">{{ $course->code }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->semester }}</td>
                            <td>{{ $course->students_count }} / {{ $course->capacity }}</td>
                            <td class="text-end">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-people"></i> Class list</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No courses have been assigned to you yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
