@extends('layouts.app')

@section('title', 'Edit course')
@section('header', 'Edit '.$course->code)

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('courses.update', $course) }}" novalidate>
                @method('PUT')
                @include('courses._form')
            </form>
        </div>
    </div>
@endsection
