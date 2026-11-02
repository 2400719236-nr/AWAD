@extends('layouts.app')

@section('title', 'Add student')
@section('header', 'Add student')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('students.store') }}" novalidate>
                @include('students._form')
            </form>
        </div>
    </div>
@endsection
