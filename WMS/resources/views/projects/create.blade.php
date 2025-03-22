@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Add New Project</h2>

    @if (session('error'))
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

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
    <form method="POST" action="{{ route('projects.store') }}" id="projectForm">
        @csrf
        <div class="mb-4">
            <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
            <input type="text" name="project_name" id="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('project_name') }}" required>
        </div>

        <div class="mb-4">
            <label for="project_description" class="block text-sm font-medium text-gray-700">Project Description</label>
            <textarea name="project_description" id="project_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('project_description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="project_date" class="block text-sm font-medium text-gray-700">Project Date</label>
            <input type="date" name="project_date" id="project_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('project_date') }}" required>
        </div>
        
        <div class="mb-4">
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('due_date') }}" required>
        </div>

        <div class="mb-4">
            <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
            <input type="text" name="supervisor_name" id="supervisor_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('supervisor_name') }}" required>
        </div>

        <!-- Hidden Input for Tasks -->
        <input type="hidden" name="tasks" id="tasksInput" value="{{ old('tasks') }}">

        <!-- Tasks Section -->
        <div class="mb-4">
            <h3 class="text-xl font-semibold mb-2">Tasks</h3>
            <div id="tasks-list"></div>

            <!-- Add Task Button -->
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="openTaskModal()">
                Add Task
            </button>
        </div>

        <!-- Submit Project Button -->
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Submit Project
        </button>
    </form>
</div>

<!-- Task Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h3 class="text-lg font-semibold mb-4">Add Task</h3>
        <form id="taskForm" onsubmit="return submitTask(event)">
            <div class="mb-4">
                <label for="task_name" class="block text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" id="task_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="task_description" class="block text-sm font-medium text-gray-700">Task Description</label>
                <textarea id="task_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>

            <div class="mb-4">
                <label for="assigned_staff" class="block text-sm font-medium text-gray-700">Assigned Staff</label>
                <input type="text" id="assigned_staff" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="assigned_date" class="block text-sm font-medium text-gray-700">Assigned Date</label>
                <input type="date" id="assigned_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" id="task_due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Task (Optional)</label>
                <select id="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">None</option>
                    <!-- Project tasks will be loaded here dynamically -->
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Task
            </button>
            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeTaskModal()">
                Cancel
            </button>
        </form>
    </div>
</div>

<script>
    let tasksArray = {!! old('tasks') ? json_encode(json_decode(old('tasks'))) : '[]' !!};

    function openTaskModal() {
        document.getElementById('taskModal').classList.remove('hidden');
    }

    function closeTaskModal() {
        document.getElementById('taskModal').classList.add('hidden');
    }

    function submitTask(event) {
        event.preventDefault();

        let parentTaskId = document.getElementById("parent_id").value;

        let task = {
            task_name: document.getElementById("task_name").value,
            task_description: document.getElementById("task_description").value,
            assigned_staff: document.getElementById("assigned_staff").value,
            assigned_date: document.getElementById("assigned_date").value,
            due_date: document.getElementById("task_due_date").value,
            parent_id: parentTaskId || null // Assign parent ID or null if "None" is selected
        };

        tasksArray.push(task);
        updateTasksDisplay();
        document.getElementById("taskForm").reset();
        closeTaskModal();
    }

    function updateTasksDisplay() {
        let taskList = document.getElementById("tasks-list");
        taskList.innerHTML = "";
        tasksArray.forEach(task => {
            let div = document.createElement("div");
            div.classList.add("task", "mb-4", "p-4", "border", "rounded", "bg-gray-100");
            div.innerHTML = `<p><strong>Task Name:</strong> ${task.task_name}</p>
                             <p><strong>Task Description:</strong> ${task.task_description}</p>
                             <p><strong>Assigned Staff:</strong> ${task.assigned_staff}</p>
                             <p><strong>Assigned Date:</strong> ${task.assigned_date}</p>
                             <p><strong>Due Date:</strong> ${task.due_date}</p>
                             <p><strong>Parent Task:</strong> ${task.parent_id ? tasksArray.find(t => t.task_name === task.parent_id)?.task_name || 'Unknown' : 'None'}</p>`;
            taskList.appendChild(div);
        });

        document.getElementById("tasksInput").value = JSON.stringify(tasksArray);
        updateParentTaskDropdown();
    }

    function updateParentTaskDropdown() {
        let parentDropdown = document.getElementById("parent_id");
        parentDropdown.innerHTML = '<option value="">None</option>';
        
        tasksArray.forEach(task => {
            let option = document.createElement("option");
            option.value = task.task_name;
            option.textContent = task.task_name;
            parentDropdown.appendChild(option);
        });
    }

    document.getElementById("projectForm").addEventListener("submit", function () {
        document.getElementById("tasksInput").value = JSON.stringify(tasksArray);
    });

    updateTasksDisplay();
</script>

@endsection