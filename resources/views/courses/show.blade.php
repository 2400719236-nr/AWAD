@extends('layouts.app')

@section('title', $course->code)
@section('header', $course->code.': '.$course->title)

@section('actions')
    @include('courses._actions')
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Course details</div>
                <dl class="card-body row mb-0">
                    <dt class="col-5">Lecturer</dt><dd class="col-7">{{ $course->lecturer?->name ?? 'Not assigned' }}</dd>
                    <dt class="col-5">Credit units</dt><dd class="col-7">{{ $course->credit_units }}</dd>
                    <dt class="col-5">Semester</dt><dd class="col-7">{{ $course->semester }}</dd>
                    <dt class="col-5">Capacity</dt><dd class="col-7">{{ $course->capacity }}</dd>
                </dl>
                @if ($course->description)
                    <div class="card-footer bg-white text-muted small">{{ $course->description }}</div>
                @endif
            </div>

            @role('student')
                @php($registered = auth()->user()->student?->courses()->whereKey($course->id)->exists())
                <div class="mt-3">
                    @if ($registered)
                        <span class="badge bg-success p-2"><i class="bi bi-check2-circle"></i> You are registered for this course</span>
                    @else
                        <form method="POST" action="{{ route('registrations.store') }}">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <button class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Register for this course</button>
                        </form>
                    @endif
                </div>
            @endrole
        </div>

        <div class="col-lg-8">
            @if ($students !== null)
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">Registered students ({{ $students->count() }} / {{ $course->capacity }})</div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Reg. number</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Year</th>
                                    <th>Registered on</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td class="fw-semibold">{{ $student->registration_number }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->year_of_study }}</td>
                                        <td>{{ $student->pivot->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No students registered yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
