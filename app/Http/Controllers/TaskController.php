<?php

namespace App\Http\Controllers;

use App\Models\Task;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function store(TaskStoreRequest $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task = Task::create($request->only('title'));
        $taskRowHtml = view('tasks.task-row', compact('task'))->render();
        return $this->apiSuccess($taskRowHtml, 'Task created successfully.');
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {

        $task->update($request->only('title'));

        return $this->apiSuccess($task, 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return $this->apiSuccess(null, 'Task deleted successfully.');
    }

    public function toggleComplete(Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        return $this->apiSuccess($task, 'Task completion status  successfully to' . ($task->completed ? ' completed' : ' not completed') . '.');
    }
}
