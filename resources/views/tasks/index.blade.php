@extends('layouts.app')

@section('title', 'Tasks Page')

@section('content')

<div class="container mt-5">

    <div class="row py-2">
        <div class="col-sm-7">
            <h1>Welcome to Tasks Lists</h1>
        </div>
        <div class="col-sm-5 d-flex h-25">
            <div class="input-group me-2">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input id='search' type="text" class="form-control" placeholder="Search...">
            </div>
            <button class="btn btn-primary new-task-btn w-75">
                <i class="bi bi-plus-circle"></i>
                New Task
            </button>
        </div>
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