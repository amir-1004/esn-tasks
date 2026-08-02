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

                console.log(response);
                appendTaskRowHtml(response.data);
                toastMessage(message = response.message);
                $(".new-task-wrapper").slideUp();
                console.log($(".new-task-wrapper"))
            },
            error: function(xhr, status, error) {
                toastMessage("Error creating task", 'danger');
            }
        });
    });

    $(document).on("submit", "form[id^='task-form-']", function(e) {
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

    //open new task form (first row in the list)
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
        $(document).find("input#task-title-" + id).addClass("d-none");
        $("div#task-title-" + id).removeClass("d-none");
        $("button#save-task-" + id).addClass("d-none");

        $("button#complete-task-" + id).removeClass("d-none");
        $("button#delete-task-" + id).removeClass("d-none");
        $("button#edit-task-" + id).removeClass("d-none");

        $(this).addClass("d-none");
    });

    $(document).on("click", "button[id^='complete-task-']", function(e) {

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

    $(document).on("click", "button[id^='delete-task-']", function(e) {

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

    $(document).on("click", "button[id^='edit-task-']", function(e) {

        e.preventDefault();

        const taskId = $(this).attr("id").replace("edit-task-", "");
        const taskTitle = $("div#task-title-" + taskId).text().trim();

        $(document).find("input#task-title-" + taskId).val(taskTitle).removeClass("d-none").focus();
        $(document).find("div#task-title-" + taskId).addClass("d-none");
        $(document).find("button#save-task-" + taskId).removeClass("d-none");
        $(document).find("button#cancel-task-" + taskId).removeClass("d-none");

        $(document).find("button#complete-task-" + taskId).addClass("d-none");
        $(document).find("button#delete-task-" + taskId).addClass("d-none");
        $(document).find("button#edit-task-" + taskId).addClass("d-none");



    })


    function appendTaskRowHtml(taskRowHtml) {
        $(".list-group").append(taskRowHtml);
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