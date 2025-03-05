@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Edit Task</h2>
        <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="task_name" class="block text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" name="task_name" id="task_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $task->task_name }}" required>
            </div>
            <div class="mb-4">
                <label for="assigned_staff" class="block text-sm font-medium text-gray-700">Assigned Staff</label>
                <input type="text" name="assigned_staff" id="assigned_staff" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $task->assigned_staff }}" required>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $task->due_date }}" required>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Update Task
            </button>
        </form>
    </div>
@endsection