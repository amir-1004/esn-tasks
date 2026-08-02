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

    $("form#new-task-form").on("submit", function(e) {

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

    $("form[id^='task-form-']").on("submit", function(e) {
        e.preventDefault();
        const taskId = $(this).attr("id").replace("task-form-", "");
        const title = $(this).find("input[name='title']").val();
        $.ajax({
            url: '/tasks/' + taskId,
            method: 'PUT',
            data: {
                title: title,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // update the task row in the list
                $("div#task-title-" + taskId).text(title).removeClass("d-none");
                $("input#task-title-" + taskId).addClass("d-none");
                $("button#save-task-" + taskId).addClass("d-none");
                $("button#cancel-task-" + taskId).addClass("d-none");

                $("button#complete-task-" + taskId).removeClass("d-none");
                $("button#delete-task-" + taskId).removeClass("d-none");
                $("button#edit-task-" + taskId).removeClass("d-none");

                toastMessage(message = response.message);
            },
            error: function(xhr, status, error) {
                toastMessage("Error updating task", 'danger');
            }
        });
    });

    $(".new-task-btn").on("click", function(e) {
        e.preventDefault();
        $(".new-task-wrapper").slideDown();
        $("#save-new-task").removeClass("d-none");
        $("#cancel-new-task").removeClass("d-none");
        $(".new-task-wrapper").parent().removeClass("py-0")

        $(this).attr("disabled", true);
        $(".new-task-wrapper input[name='title']").focus();
    });

    $(".new-task-wrapper .cancel-btn").on("click", function(e) {
        // cancel new task form
        $(".new-task-wrapper").slideUp();
        $(".new-task-wrapper").parent().addClass("py-0")
        $(".new-task-btn").attr("disabled", false);

    });

    $(".task-row-wrapper .cancel-btn").on("click", function(e) {
        // cancel edit mode for the task row
        const id = $(this).attr("id").replace("cancel-task-", "");
        $("input#task-title-" + id).addClass("d-none");
        $("div#task-title-" + id).removeClass("d-none");
        $("button#save-task-" + id).addClass("d-none");

        $("button#complete-task-" + id).removeClass("d-none");
        $("button#delete-task-" + id).removeClass("d-none");
        $("button#edit-task-" + id).removeClass("d-none");

        $(this).addClass("d-none");
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

        const taskId = $(this).attr("id").replace("edit-task-", "");
        const taskTitle = $("div#task-title-" + taskId).text();


        $("input#task-title-" + taskId).val(taskTitle).removeClass("d-none").focus();

        //hide the title and show the input field
        $("button#edit-task-" + taskId).addClass("d-none");
        $("button#delete-task-" + taskId).addClass("d-none");
        $("button#complete-task-" + taskId).addClass("d-none");

        $("div#task-title-" + taskId).addClass("d-none");

        //show save and cancel buttons
        $("button#save-task-" + taskId).removeClass("d-none");
        $("button#cancel-task-" + taskId).removeClass("d-none");

    })


    function appendTaskToList(data) {
        // create a new task row using the data and append it to the list
        const newTaskRow = `
            <li class="list-group-item">
                <div class="task-title">${data.title}</div>
                <div class="icon-group d-inline-block float-end fs-5">
                    <button 
                        id="complete-task-${data.id}"
                        class="link-secondary">
                        <i class="bi bi-circle"></i>
                    </button>
                    <button 
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