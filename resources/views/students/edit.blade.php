@extends('layouts.app')

@section('title', 'Edit student')
@section('header', 'Edit '.$student->full_name)

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('students.update', $student) }}" novalidate>
                @method('PUT')
                @include('students._form')
            </form>
        </div>
    </div>
@endsection
