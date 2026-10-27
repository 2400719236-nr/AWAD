@extends('layouts.app')

@section('title', 'Add course')
@section('header', 'Add course')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('courses.store') }}" novalidate>
                @include('courses._form')
            </form>
        </div>
    </div>
@endsection
