@extends('layouts.app')

@section('title', 'My Page')

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Welcome to Tasks Lists</h1>
        <button class="btn btn-primary new-task-btn">New Task <i class="bi bi-plus-circle"></i></button>
    </div>

    <ul class="list-group">
        @include('tasks.new-task-row')
        @foreach ($tasks as $task)
        @include('tasks.task-row', ['task' => $task])
        @endforeach

    </ul>

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/tasks.js') }}"></script>
@endsection