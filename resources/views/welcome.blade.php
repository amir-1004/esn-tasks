@extends('layouts.app')

@section('title', 'My Page')

@section('content')
<h1>Welcome to Tasks Lists</h1>
<p>This content is dynamically injected into the master layout.</p>

<ul class="list-group">


    @foreach ([1,2,3,4,5] as $task)
    <li class="list-group-item">{{ $task }}
        <div class="icon-group d-inline-block float-end fs-5">
            <a href="#" class="link-success text-decoration-none  p-1 border-0 lh-1">
                <i class="bi bi-plus-circle"></i>
            </a>

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


<!--   Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

@endsection