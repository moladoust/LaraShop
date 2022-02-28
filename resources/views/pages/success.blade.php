@extends('layouts.app')
@section('title', 'Operation succeeded')

@section('content')
  <div class="card">
    <div class="card-header">Operation succeeded</div>
    <div class="card-body text-center">
      <h1>{{ $msg }}</h1>
      <a class="btn btn-primary" href="{{ route('root') }}">Back to Home</a>
    </div>
  </div>
@endsection
