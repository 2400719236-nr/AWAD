@extends('layouts.app')

@section('title', 'Students')
@section('header', 'Students')

@section('actions')
    @can('create', App\Models\Student::class)
        <a href="{{ route('students.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add student</a>
    @endcan
@endsection

@section('content')
    <form method="GET" action="{{ route('students.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="search" name="search" value="{{ $search }}" class="form-control"
                   placeholder="Search by name, registration number or email">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary"><i class="bi bi-search"></i> Search</button>
            @if ($search)
                <a href="{{ route('students.index') }}" class="btn btn-link">Clear</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Reg. number</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Programme</th>
                        <th>Year</th>
                        <th>Courses</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="fw-semibold">{{ $student->registration_number }}</td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->programme }}</td>
                            <td>{{ $student->year_of_study }}</td>
                            <td><span class="badge bg-secondary">{{ $student->courses_count }}</span></td>
                            <td class="text-end text-nowrap">
                                @include('students._actions')
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $students->links() }}</div>
@endsection
