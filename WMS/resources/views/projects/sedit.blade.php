@extends('layouts.sapp')

@section('content')
<div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Edit Project</h2>
        <form method="POST" action="{{ route('projects.update', $project->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input type="text" name="project_name" id="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->project_name }}" required>
            </div>
            <div class="mb-4">
                <label for="project_description" class="block text-sm font-medium text-gray-700">Project Description</label>
                <textarea name="project_description" id="project_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ $project->project_description }}</textarea>
            </div>
            <div class="mb-4">
                <label for="project_date" class="block text-sm font-medium text-gray-700">Project Date</label>
                <input type="date" name="project_date" id="project_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->project_date }}" required>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->due_date }}" required>
            </div>
            <div class="mb-4">
                <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                <input type="text" name="supervisor_name" id="supervisor_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->supervisor_name }}" required>
            </div>

            {{-- Task List --}}
            <h3 class="text-xl font-semibold mt-6 mb-2">Project Tasks</h3>
            <ul id="task-list" class="mb-4">
                @foreach($project->tasks as $task)
                    <li class="task-item border p-2 rounded mb-2">
                        <input type="hidden" name="tasks[{{ $loop->index }}][id]" value="{{ $task->id }}">
                        <strong>{{ $task->task_name }}</strong> ({{ $task->assigned_staff }})
                        <input type="hidden" name="tasks[{{ $loop->index }}][task_name]" value="{{ $task->task_name }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][task_description]" value="{{ $task->task_description }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][assigned_staff]" value="{{ $task->assigned_staff }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][assigned_date]" value="{{ $task->assigned_date }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][due_date]" value="{{ $task->due_date }}">
                    </li>
                @endforeach
            </ul>

            {{-- Add More Tasks Button --}}
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="openTaskModal()">
                Add More Tasks
            </button>

            {{-- Submit Form --}}
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mt-4">
                Update Project
            </button>
        </form>
        <a href="/admin/dashboard" class="inline-block bg-blue-400 text-white py-2 px-4 rounded hover:bg-blue-500 mt-4">
            Back
        </a>
    </div>

    {{-- Task Modal --}}
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Add Task</h2>
            <label>Task Name</label>
            <input type="text" id="task_name" class="w-full border rounded p-2 mb-2">
            <label>Task Description</label>
            <textarea id="task_description" class="w-full border rounded p-2 mb-2"></textarea>
            <label>Assigned Staff</label>
            <input type="text" id="assigned_staff" class="w-full border rounded p-2 mb-2">
            <label>Assigned Date</label>
            <input type="date" id="assigned_date" class="w-full border rounded p-2 mb-2">
            <label>Due Date</label>
            <input type="date" id="due_date" class="w-full border rounded p-2 mb-2">
            <button onclick="addTask()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Task</button>
            <button onclick="closeTaskModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancel</button>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function openTaskModal() {
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.add('hidden');
        }

        function addTask() {
            let taskName = document.getElementById('task_name').value;
            let taskDesc = document.getElementById('task_description').value;
            let assignedStaff = document.getElementById('assigned_staff').value;
            let assignedDate = document.getElementById('assigned_date').value;
            let dueDate = document.getElementById('due_date').value;

            if (taskName && assignedStaff) {
                let taskList = document.getElementById('task-list');
                let taskIndex = taskList.children.length;

                let taskItem = `
                    <li class="task-item border p-2 rounded mb-2">
                        <strong>${taskName}</strong> (${assignedStaff})
                        <input type="hidden" name="tasks[${taskIndex}][task_name]" value="${taskName}">
                        <input type="hidden" name="tasks[${taskIndex}][task_description]" value="${taskDesc}">
                        <input type="hidden" name="tasks[${taskIndex}][assigned_staff]" value="${assignedStaff}">
                        <input type="hidden" name="tasks[${taskIndex}][assigned_date]" value="${assignedDate}">
                        <input type="hidden" name="tasks[${taskIndex}][due_date]" value="${dueDate}">
                    </li>
                `;

                taskList.innerHTML += taskItem;
                closeTaskModal();
            } else {
                alert("Task name and assigned staff are required.");
            }
        }
    </script>
@endsection