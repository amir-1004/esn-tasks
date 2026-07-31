@extends('layouts.app')

@section('title', 'My Page')

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Welcome to Tasks Lists</h1>
        <button class="btn btn-primary">New Task <i class="bi bi-plus-circle"></i></button>
    </div>

    <ul class="list-group">

        @foreach ($tasks as $task)
        <li class="list-group-item">{{ $task->title }}
            <div class="icon-group d-inline-block float-end fs-5">

                <a href="#" class="link-secondary text-decoration-none ">
                    <i class="bi bi-circle"></i>
                </a>

                <a href="#" class="link-danger text-decoration-none ">
                    <i class="bi bi-trash3"></i>
                </a>

                <a href="#" class="link-success text-decoration-none">
                    <i class="bi bi-check-circle"></i>
                </a>
            </div>

        </li>

        @endforeach

    </ul>

</div>

<!--   Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

@endsection