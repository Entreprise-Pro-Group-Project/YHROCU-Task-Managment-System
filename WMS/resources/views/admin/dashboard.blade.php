@extends('layouts.app')

@section('content')
    <div class="min-h-screen">
        <!-- Layout: Sidebar + Main Content -->
        <div class="flex">
            <!-- Sidebar -->
            <!-- Add your sidebar code here if needed -->

            <!-- Main Content -->
            <main class="flex-1 bg-gray-100 p-6">
                <!-- Display Success Message -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Filter & Search -->
                <div class="flex items-center space-x-4 mb-6">
                    <!-- Filter Dropdown (vanilla JS) -->
                    <div class="relative inline-block">
                        <button
                            id="filterButton"
                            class="bg-blue-400 text-black text-sm px-3 py-1 rounded hover:bg-blue-500 flex items-center space-x-1"
                        >
                            <span>Filter by Status</span>
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            id="filterDropdown"
                            class="hidden absolute top-full left-0 mt-1 w-32 bg-white rounded-md shadow-lg z-20 text-sm"
                        >
                            <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Incomplete</a>
                            <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">In Progress</a>
                            <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-100">Completed</a>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="flex-1 px-5 flex items-center justify-center space-x-2">
                        <span class="font-bold text-[#0284c7]">Search:</span>
                        <input
                            type="text"
                            placeholder="Search"
                            class="border rounded px-4 py-2 focus:outline-none"
                        />
                    </div>
                </div>
                
                <div class="container mx-auto p-4">
                    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
                    
                    <!-- Display Projects -->
                    @foreach ($projects as $project)
                    <div class="mb-6 p-4 border rounded">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">{{ $project->project_name }}</h3>
                    <div class="flex space-x-2">
                        <!-- View Project Button -->
                        <a href="{{ route('projects.show', $project->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            View
                        </a>
                        <!-- Edit Project Button -->
                        <a href="{{ route('projects.edit', $project->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        <!-- Delete Project Button -->
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                <p><strong>Start Date:</strong> {{ $project->project_date }}</p>
                <p><strong>Due Date:</strong> {{ $project->due_date }}</p>
                <p><strong>Supervisor:</strong> {{ $project->supervisor_name }}</p>

                <!-- Display Tasks -->
                @foreach ($project->tasks as $task)
                    <div class="ml-4 mt-2 p-2 border rounded">
                        <div class="flex justify-between items-center">
                            <p><strong>Task:</strong> {{ $task->task_name }}</p>
                            <div class="flex space-x-2">
                                <!-- View Task Button -->
                                <a href="{{ route('tasks.show', $task->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    View
                                </a>
                                <!-- Edit Task Button -->
                                <a href="{{ route('tasks.edit', $task->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Edit
                                </a>
                                <!-- Delete Task Button -->
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p><strong>Assigned Staff:</strong> {{ $task->assigned_staff }}</p>
                        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

                        <!-- Display Subtasks -->
                        @foreach ($task->subtasks as $subtask)
                            <div class="ml-4 mt-2 p-2 border rounded">
                                <div class="flex justify-between items-center">
                                    <p><strong>Subtask:</strong> {{ $subtask->task_name }}</p>
                                    <div class="flex space-x-2">
                                        <!-- View Subtask Button -->
                                        <a href="{{ route('tasks.show', $subtask->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            View
                                        </a>
                                        <!-- Edit Subtask Button -->
                                        <a href="{{ route('tasks.edit', $subtask->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                            Edit
                                        </a>
                                        <!-- Delete Subtask Button -->
                                        <form action="{{ route('tasks.destroy', $subtask->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subtask?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <p><strong>Assigned Staff:</strong> {{ $subtask->assigned_staff }}</p>
                                <p><strong>Due Date:</strong> {{ $subtask->due_date }}</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- JavaScript for click-based dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Admin dropdown
            const adminButton = document.getElementById('adminButton');
            const adminDropdown = document.getElementById('adminDropdown');

            adminButton.addEventListener('click', function (e) {
                e.stopPropagation(); 
                // Close filter dropdown if open
                filterDropdown.classList.add('hidden');
                // Toggle admin dropdown
                adminDropdown.classList.toggle('hidden');
            });

            // Filter dropdown
            const filterButton = document.getElementById('filterButton');
            const filterDropdown = document.getElementById('filterDropdown');

            filterButton.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close admin dropdown if open
                adminDropdown.classList.add('hidden');
                // Toggle filter dropdown
                filterDropdown.classList.toggle('hidden');
            });

            // Hide both dropdowns when clicking anywhere else
            document.addEventListener('click', function () {
                adminDropdown.classList.add('hidden');
                filterDropdown.classList.add('hidden');
            });
        });
    </script>
@endsection