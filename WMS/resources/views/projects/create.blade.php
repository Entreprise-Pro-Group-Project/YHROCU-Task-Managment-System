@extends('layouts.app') <!-- Extend the layout -->

@section('content') <!-- Define the content section -->
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Add New Project</h2>

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <!-- Project Form -->
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div class="mb-4">
                <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input type="text" name="project_name" id="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="project_description" class="block text-sm font-medium text-gray-700">Project Description</label>
                <textarea name="project_description" id="project_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>

            <div class="mb-4">
                <label for="project_date" class="block text-sm font-medium text-gray-700">Project Date</label>
                <input type="date" name="project_date" id="project_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                <input type="text" name="supervisor_name" id="supervisor_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <!-- Tasks Section -->
            <div class="mb-4">
                <h3 class="text-xl font-semibold mb-2">Tasks</h3>
                <div id="tasks-list">
                    @if (session('tasks'))
                        @foreach (session('tasks') as $task)
                            <div class="task mb-4 p-4 border rounded">
                                <p><strong>Task Name:</strong> {{ $task['task_name'] }}</p>
                                <p><strong>Assigned Staff:</strong> {{ $task['assigned_staff'] }}</p>
                                <p><strong>Due Date:</strong> {{ $task['due_date'] }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Add Task Button -->
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Add Task
                </a>
            </div>

            <!-- Submit Project Button -->
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Submit Project
            </button>
        </form>
    </div>
@endsection