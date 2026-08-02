@extends('layouts.app')

@section('title', 'My Page')

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Welcome to Tasks Lists</h1>
        <button class="btn btn-primary new-task-btn">New Task <i class="bi bi-plus-circle"></i></button>
    </div>

    <ul class="list-group">
        @include('new-task-row')
        @foreach ($tasks as $task)
        @include('task-row', ['task' => $task])
        @endforeach

    </ul>

</div>

<!--   Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

@endsection

@section('scripts')
<script>
    //jquery on ready function
    $(() => {
        // DOM  
        console.log("Dom ready");
    });

    $("form#new-task-form input").on("input", function(e) {

        console.log(e.target.value);
    });

    $("form").on("submit", function(e) {

        $(".new-task-wrapper").slideDown
        e.preventDefault();
        const title = $(this).find("input[name='title']").val();
        $.ajax({
            url: '/tasks',
            method: 'POST',
            data: {
                title: title,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                appendTaskToList(response.data);
                toastMessage(message = response.message);
            },
            error: function(xhr, status, error) {
                toastMessage("Error creating task", 'danger');
            }
        });
    });

    $(".new-task-btn").on("click", function(e) {
        e.preventDefault();
        $(".new-task-wrapper").slideDown();
        $(".new-task-wrapper").parent().removeClass("py-0")
        $(this).attr("disabled", true);
        $(".new-task-wrapper input[name='title']").focus();
    });

    $(".new-task-wrapper .link-danger").on("click", function(e) {

        $(".new-task-wrapper").slideUp();
        $(".new-task-wrapper").parent().addClass("py-0")
        $(".new-task-btn").attr("disabled", false);

    });

    $("button[id^='complete-task-']").on("click", function(e) {

        $.ajax({
            url: '/tasks/' + $(this).attr("id").replace("complete-task-", "") + '/toggle',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // update the task row in the list
                const taskId = response.data.id;
                const isCompleted = response.data.completed;
                const button = $("#complete-task-" + taskId);
                button.removeClass("link-success link-secondary");
                button.addClass(isCompleted ? "link-success" : "link-secondary");
                button.find("i").removeClass("bi-check-circle bi-circle");
                button.find("i").addClass(isCompleted ? "bi-check-circle" : "bi-circle");
                toastMessage(message = response.message);
            },
            error: function(xhr, status, error) {
                toastMessage("Error toggling task", 'danger');
            }
        });
    });

    $("button[id^='delete-task-']").on("click", function(e) {

        const taskId = $(this).attr("id").replace("delete-task-", "");
        $.ajax({
            url: '/tasks/' + taskId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // remove the task row from the list
                $("#delete-task-" + taskId).closest("li").remove();
                toastMessage(message = response.message);
            },
            error: function(xhr, status, error) {
                toastMessage("Error deleting task", 'danger');
            }
        });
    });

    $("button[id^='edit-task-']").on("click", function(e) {
        e.preventDefault();
        const currentRow = $(this).parent().parent();
        currentRow.find(".span-title").addClass("d-none");
        const TaskTitle = $(this).parent().parent().text().trim();

        const taskId = $(this).attr("id").replace("edit-task-", "");
        $(this).addClass("d-none");
        currentRow.find("input[name='title']").removeClass("d-none").val(TaskTitle).focus();

    })

    //onfocus title hide the rest of the buttons and show save and cancel buttons
    $("input[name='title']").on("focus", function(e) {

      
        const currentRow = $(this).parent();
        currentRow.find(".icon-group button").addClass("d-none");
        currentRow.find(".icon-group .link-primary, .icon-group .link-danger").removeClass("d-none");
    });

    function appendTaskToList(data) {
        // create a new task row using the data and append it to the list
        const newTaskRow = `
            <li class="list-group-item">
                <span class="span-title">${data.title}</span>
                <div class="icon-group d-inline-block float-end fs-5">
                    <button href="#"
                        id="complete-task-${data.id}"
                        class="link-secondary">
                        <i class="bi bi-circle"></i>
                    </button>
                    <button href="#"
                        id="delete-task-${data.id}" class="link-danger">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </li>
        `;
        $(".list-group").append(newTaskRow);
        //bootsrap toast success message
    }

    function toastMessage(message, type = 'success') {
        let $toastElement = $('#liveToast');

        // עדכון הטקסט
        $('#toast-message').text(message);


        $toastElement.removeClass('bg-success bg-danger').addClass('bg-' + type);

        //built in in bs5 
        let toast = new bootstrap.Toast($toastElement[0]);
        toast.show();
    }
</script>
@endsection