 <form class="form-group justify-content-between d-flex gap-2"
     @isset($task)
     id="task-form-{{ $task->id }}"
     @else
     id="new-task-form"
     @endisset>
     @isset($task)
     <div class="task-title"
         id="task-title-{{ $task->id }}">{{ $task->title }}</div>
     @endisset
     <input type="text"
         class="form-control py-0 {{isset($task) ? 'd-none' : ''}}"
         name="title"
         @isset($task)
         id="task-title-{{ $task->id }}"
         @endisset
         placeholder="Enter task title">
     <div class="icon-group d-flex gap-1 float-end fs-5">
         @isset($task)
         <button
             id="complete-task-{{ $task->id }}"
             class="link-{{ $task->completed ? 'success' : 'secondary' }}">
             <i class="bi bi-{{ $task->completed ? 'check-circle' : 'circle' }}"></i>
         </button>
         <button
             id="edit-task-{{ $task->id }}"
             class="link-primary">
             <i class="bi bi-pencil"></i>
         </button>
         <button
             id="delete-task-{{ $task->id }}"
             class="link-danger">
             <i class="bi bi-trash3"></i>
         </button>
         @endisset
         <button
             @isset($task)
             id="save-task-{{ $task->id }}"
             title="Update Task"
             @else
             id="save-new-task"
             title="Save Task"
             @endisset
             class="link-primary d-none">
             <i class="bi bi-save"></i>
         </button>
         <button
             title="Cancel"
             type="button"
             @isset($task)
             id="cancel-task-{{ $task->id }}"
             @endisset
             class="link-danger cancel-btn d-none">
             <i class="bi bi-x-circle"></i>
         </button>

     </div>
 </form>