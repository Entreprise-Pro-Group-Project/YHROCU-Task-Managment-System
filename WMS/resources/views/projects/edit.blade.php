@extends('layouts.app')

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
                        @if($task->parent_id)
                            <br><small>Parent Task: {{ optional($task->parent)->task_name }}</small>
                        @endif
                        <input type="hidden" name="tasks[{{ $loop->index }}][task_name]" value="{{ $task->task_name }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][task_description]" value="{{ $task->task_description }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][assigned_staff]" value="{{ $task->assigned_staff }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][assigned_date]" value="{{ $task->assigned_date }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][due_date]" value="{{ $task->due_date }}">
                        <input type="hidden" name="tasks[{{ $loop->index }}][parent_id]" value="{{ $task->parent_id }}">
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
    <div id="taskModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-bold mb-3">Add Task</h2>
            
            <label class="block text-sm font-medium text-gray-700">Task Name</label>
            <input type="text" id="task_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            
            <label class="block text-sm font-medium text-gray-700">Task Description</label>
            <textarea id="task_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
        
            <label class="block text-sm font-medium text-gray-700">Assigned Staff</label>
            <input type="text" id="assigned_staff" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        
            <label class="block text-sm font-medium text-gray-700">Assigned Date</label>
            <input type="date" id="assigned_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        
            <label class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        
            <label class="block text-sm font-medium text-gray-700">Parent Task (Optional)</label>
            <select id="parent_task" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">None</option>
                @foreach($project->tasks as $task)
                    <option value="{{ $task->id }}">{{ $task->task_name }}</option>
                @endforeach
            </select>

            <div class="flex justify-end space-x-2 mt-3">
                <button onclick="addTask()" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Add Task</button>
                <button onclick="closeTaskModal()" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-sm">Cancel</button>
            </div>
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
            let parentTask = document.getElementById('parent_task').value; // Get parent task ID
             
            if (taskName && assignedStaff) {
                let taskList = document.getElementById('task-list');
                let taskIndex = taskList.children.length;
                
                let taskItem = `
                    <li class="task-item border p-2 rounded mb-2">
                        <strong>${taskName}</strong> (${assignedStaff})
                        ${parentTask ? `<br><small>Parent Task: ${parentTask}</small>` : ""}
                        <input type="hidden" name="tasks[${taskIndex}][task_name]" value="${taskName}">
                        <input type="hidden" name="tasks[${taskIndex}][task_description]" value="${taskDesc}">
                        <input type="hidden" name="tasks[${taskIndex}][assigned_staff]" value="${assignedStaff}">
                        <input type="hidden" name="tasks[${taskIndex}][assigned_date]" value="${assignedDate}">
                        <input type="hidden" name="tasks[${taskIndex}][due_date]" value="${dueDate}">
                        <input type="hidden" name="tasks[${taskIndex}][parent_id]" value="${parentTask}">
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