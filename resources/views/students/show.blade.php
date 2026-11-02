@extends('layouts.app')

@section('title', $student->full_name)
@section('header', $student->full_name)

@section('actions')
    @include('students._actions')
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Student details</div>
                <dl class="card-body row mb-0">
                    <dt class="col-5">Reg. number</dt><dd class="col-7">{{ $student->registration_number }}</dd>
                    <dt class="col-5">Email</dt><dd class="col-7 text-break">{{ $student->email }}</dd>
                    <dt class="col-5">Phone</dt><dd class="col-7">{{ $student->phone ?? '—' }}</dd>
                    <dt class="col-5">Gender</dt><dd class="col-7 text-capitalize">{{ $student->gender }}</dd>
                    <dt class="col-5">Date of birth</dt><dd class="col-7">{{ $student->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                    <dt class="col-5">Programme</dt><dd class="col-7">{{ $student->programme }}</dd>
                    <dt class="col-5">Year</dt><dd class="col-7">{{ $student->year_of_study }}</dd>
                    <dt class="col-5">Login</dt>
                    <dd class="col-7">
                        @if ($student->user)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">No account</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Registered courses ({{ $student->courses->count() }})</div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Lecturer</th>
                                <th>Credit units</th>
                                <th>Academic year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($student->courses as $course)
                                <tr>
                                    <td class="fw-semibold">{{ $course->code }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->lecturer?->name ?? 'Not assigned' }}</td>
                                    <td>{{ $course->credit_units }}</td>
                                    <td>{{ $course->pivot->academic_year }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">Not registered for any courses.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
