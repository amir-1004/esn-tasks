$("form#new-task-form").on("submit", function (e) {
    e.preventDefault();

    const title = $(this).find("input[name='title']").val();
    $.ajax({
        url: "/tasks",
        method: "POST",
        data: {
            title,
        },
        success: function (response) {
            appendTaskRowHtml(response.data);
            toastMessage((message = response.message));
            $(".new-task-wrapper").slideUp();
            $(".new-task-btn").attr("disabled", false);
        },
        error: function (xhr, status, error) {
            toastMessage("Error creating task", "danger");
        },
    });
});

$(document).on("submit", "form[id^='task-form-']", function (e) {
    e.preventDefault();
    const taskId = $(this).attr("id").replace("task-form-", "");
    const title = $(this).find("input[name='title']").val();
    $.ajax({
        url: "/tasks/" + taskId,
        method: "PUT",
        data: {
            title,
        },
        success: function (response) {
            // update the task row in the list
            $("div#task-title-" + taskId)
                .text(title)
                .removeClass("d-none");
            $("input#task-title-" + taskId).addClass("d-none");
            $("button#save-task-" + taskId).addClass("d-none");
            $("button#cancel-task-" + taskId).addClass("d-none");

            $("button#complete-task-" + taskId).removeClass("d-none");
            $("button#delete-task-" + taskId).removeClass("d-none");
            $("button#edit-task-" + taskId).removeClass("d-none");

            toastMessage((message = response.message));
        },
        error: function (xhr, status, error) {
            toastMessage("Error updating task", "danger");
        },
    });
});

//open new task form (first row in the list)
$(".new-task-btn").on("click", function (e) {
    e.preventDefault();
    $(".new-task-wrapper").slideDown();
    $("#save-new-task").removeClass("d-none");
    $("#cancel-new-task").removeClass("d-none");
    $(".new-task-wrapper").parent().removeClass("py-0");

    $(this).attr("disabled", true);
    $(".new-task-wrapper input[name='title']").focus();
});

// cancel new task form
$(".new-task-wrapper .cancel-btn").on("click", function (e) {
    $(".new-task-wrapper").slideUp();
    $(".new-task-wrapper").parent().addClass("py-0");
    $(".new-task-btn").attr("disabled", false);
});

// cancel edit mode for the task row
$(".task-row-wrapper .cancel-btn").on("click", function (e) {
    const id = $(this).attr("id").replace("cancel-task-", "");
    $(document)
        .find("input#task-title-" + id)
        .addClass("d-none");
    $("div#task-title-" + id).removeClass("d-none");
    $("button#save-task-" + id).addClass("d-none");

    $("button#complete-task-" + id).removeClass("d-none");
    $("button#delete-task-" + id).removeClass("d-none");
    $("button#edit-task-" + id).removeClass("d-none");

    $(this).addClass("d-none");
});

// toggle task completion , will not send data from the form!
$(document).on("click", "button[id^='complete-task-']", function (e) {
    e.preventDefault();
    $.ajax({
        url:
            "/tasks/" +
            $(this).attr("id").replace("complete-task-", "") +
            "/toggle",
        method: "PUT",
        success: function (response) {
            // update the task row in the list
            const taskId = response.data.id;
            const isCompleted = response.data.completed;
            const button = $("#complete-task-" + taskId);
            button.removeClass("link-success link-secondary");
            button.addClass(isCompleted ? "link-success" : "link-secondary");
            button.find("i").removeClass("bi-check-circle bi-circle");
            button
                .find("i")
                .addClass(isCompleted ? "bi-check-circle" : "bi-circle");
            toastMessage((message = response.message));
        },
        error: function (xhr, status, error) {
            toastMessage("Error toggling task", "danger");
        },
    });
});

$(document).on("click", "button[id^='delete-task-']", function (e) {
    e.preventDefault();
    const taskId = $(this).attr("id").replace("delete-task-", "");
    $.ajax({
        url: "/tasks/" + taskId,
        method: "DELETE",
        success: function (response) {
            // remove the task row from the list
            $("#delete-task-" + taskId)
                .closest("li")
                .remove();
            toastMessage((message = response.message));
        },
        error: function (xhr, status, error) {
            toastMessage("Error deleting task", "danger");
        },
    });
});

$(document).on("click", "button[id^='edit-task-']", function (e) {
    e.preventDefault();

    const taskId = $(this).attr("id").replace("edit-task-", "");
    const taskTitle = $("div#task-title-" + taskId)
        .text()
        .trim();

    $(document)
        .find("input#task-title-" + taskId)
        .val(taskTitle)
        .removeClass("d-none")
        .focus();
    $(document)
        .find("div#task-title-" + taskId)
        .addClass("d-none");
    $(document)
        .find("button#save-task-" + taskId)
        .removeClass("d-none");
    $(document)
        .find("button#cancel-task-" + taskId)
        .removeClass("d-none");

    $(document)
        .find("button#complete-task-" + taskId)
        .addClass("d-none");
    $(document)
        .find("button#delete-task-" + taskId)
        .addClass("d-none");
    $(document)
        .find("button#edit-task-" + taskId)
        .addClass("d-none");
});

function appendTaskRowHtml(taskRowHtml) {
    $(".list-group").append(taskRowHtml);
}

function toastMessage(message, type = "success") {
    let $toastElement = $("#liveToast").clone();
    $toastElement.attr("id", new Date().getTime()).removeClass("d-none");
    $toastElement.find("#toast-message").text(message);
    $toastElement.appendTo(".toast-container");

    $toastElement.removeClass("bg-success bg-danger").addClass("bg-" + type);

    //built in in bs5
    let toast = new bootstrap.Toast($toastElement[0]);
    toast.show();
}

//search filter mechanism for the tasks list
$(() => {
    $("input#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".task-row-wrapper").filter(function () {
            $(this).toggle(
                $(this).text().trim().toLowerCase().indexOf(value) > -1,
            );
        });
    });
});
