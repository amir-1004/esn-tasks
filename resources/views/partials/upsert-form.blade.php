 <form class="form-group justify-content-between d-flex gap-2" id="new-task-form">
     @isset($task)
     <div class="span-title">{{ $task->title }}</div>
     @endisset
     <input type="text" class="form-control py-0 {{isset($task) ? 'd-none' : ''}}" name="title"
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
         <button

             title="Save Task"
             class="link-primary">
             <i class="bi bi-save"></i>
         </button>
         @endisset
         <button
             title="Cancel"
             type="button"
             class="link-danger d-none">
             <i class="bi bi-x-circle"></i>
         </button>

     </div>
 </form>