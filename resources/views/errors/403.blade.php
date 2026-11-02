@extends('layouts.app')

@section('title', 'Access denied')

@section('content')
    <div class="text-center py-5">
        <i class="bi bi-shield-x display-1 text-danger"></i>
        <h1 class="h3 mt-3">Access denied</h1>
        <p class="text-muted">You do not have permission to view this page or record.</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="btn btn-primary">Go back</a>
    </div>
@endsection
