@extends('layouts.app') <!-- Extend the layout -->

@section('content') <!-- Define the content section -->
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Add New Task</h2>

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <form method="POST" action="{{ route('tasks.store') }}" novalidate>
            @csrf
            <div class="mb-4">
                <label for="task_name" class="block text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" name="task_name" id="task_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="task_description" class="block text-sm font-medium text-gray-700">Task Description</label>
                <textarea name="task_description" id="task_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>

            <div class="mb-4">
                <label for="assigned_staff" class="block text-sm font-medium text-gray-700">Assigned Staff</label>
                <input type="text" name="assigned_staff" id="assigned_staff" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="assigned_date" class="block text-sm font-medium text-gray-700">Assigned Date</label>
                <input type="date" name="assigned_date" id="assigned_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Task (Optional)</label>
                <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">None</option>
                    @foreach (\App\Models\Task::all() as $task)
                        <option value="{{ $task->id }}">{{ $task->task_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button to Add Task -->
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Add Task
            </button>
        </form>
    </div>
@endsection