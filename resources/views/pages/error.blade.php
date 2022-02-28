@extends('layouts.app')
@section('title', 'error')

@section('content')
<div class="card">
    <div class="card-header">error</div>
    <div class="card-body text-center">
        <h1>{{ $msg }}</h1>
        <a class="btn btn-primary" href="{{ route('root') }}">Back to Home</a>
    </div>
</div>
@endsection
