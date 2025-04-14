@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    <div class="bg-white rounded-lg shadow-xl p-8 mb-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h2 class="text-3xl font-bold text-gray-800">Add New Project</h2>
            </div>
            <a href="/admin/dashboard" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-8">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-8">
            <div class="flex items-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-medium">Please correct the following errors:</span>
            </div>
            <ul class="list-disc pl-10">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <!-- Project Form -->
        <form method="POST" action="{{ route('projects.store') }}" id="projectForm" novalidate>
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="relative">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                    </div>
                    <input type="text" name="project_name" id="project_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ old('project_name') }}" required>
                </div>
                
                <div class="relative">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                    </div>
                    <input type="text" name="supervisor_name" id="supervisor_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ old('supervisor_name') }}" required>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <label for="project_description" class="block text-sm font-medium text-gray-700">Project Description</label>
                </div>
                <textarea name="project_description" id="project_description" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base" required>{{ old('project_description') }}</textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="relative">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <label for="project_date" class="block text-sm font-medium text-gray-700">Project Start Date</label>
                    </div>
                    <input type="date" name="project_date" id="project_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ old('project_date') }}" required>
                </div>
                
                <div class="relative">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Project Due Date</label>
                    </div>
                    <input type="date" name="due_date" id="due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ old('due_date') }}" required>
                </div>
            </div>

            <!-- Hidden Input for Tasks -->
            <input type="hidden" name="tasks" id="tasksInput" value="{{ old('tasks') }}">

            <!-- Tasks Section -->
            <div class="mt-12 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-800">Project Tasks</h3>
                    </div>
                    <button type="button" class="inline-flex items-center px-5 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out duration-150" onclick="openTaskModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Task
                    </button>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6 shadow-inner">
                    <div id="tasks-list" class="divide-y divide-gray-200">
                        <!-- Tasks will be displayed here -->
                    </div>
                    
                    <!-- Empty state message -->
                    <div id="empty-tasks" class="text-center py-8 flex flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500 text-lg">No tasks added yet.</p>
                        <p class="text-gray-400 mt-1">Click "Add New Task" to create your first task.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-10">
                <button type="submit" class="inline-flex items-center px-8 py-4 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Submit Project
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Task Modal -->
<div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">Add New Task</h2>
            </div>
            <button onclick="closeTaskModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Error container for task modal -->
        <div id="taskFormErrors" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6"></div>
        
        <form id="taskForm" onsubmit="return submitTask(event)" novalidate>
            <div class="space-y-5">
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <label for="task_name" class="block text-sm font-medium text-gray-700">Task Name</label>
                    </div>
                    <input type="text" id="task_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" required>
                </div>
                
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <label for="task_description" class="block text-sm font-medium text-gray-700">Task Description</label>
                    </div>
                    <textarea id="task_description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base" required></textarea>
                </div>
                
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <label for="assigned_staff" class="block text-sm font-medium text-gray-700">Assigned Staff</label>
                    </div>
                    <select id="assigned_staff" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" required>
                        <option value="">Select Staff Member</option>
                        @foreach ($users as $staffMember)
                        @if ($staffMember->role === 'staff')
                        <option value="{{ $staffMember->first_name }}">
                            {{ ucfirst($staffMember->first_name) }} {{ ucfirst($staffMember->last_name) }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <label for="assigned_date" class="block text-sm font-medium text-gray-700">Assigned Date</label>
                        </div>
                        <input type="date" id="assigned_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" required>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <label for="task_due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        </div>
                        <input type="date" id="task_due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" required>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Task (Optional)</label>
                    </div>
                    <select id="parent_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3">
                        <option value="">None</option>
                        <!-- Project tasks will be loaded here dynamically -->
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button type="button" class="inline-flex items-center px-5 py-3 bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 focus:ring-opacity-50 transition ease-in-out duration-150" onclick="closeTaskModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center px-5 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Initialize tasks array from old input if available
    let tasksArray = {!! old('tasks') ? json_encode(json_decode(old('tasks'))) : '[]' !!};
    
    // Cache DOM elements for better performance
    let taskModal = null;
    let taskNameInput = null;
    let taskDescInput = null;
    let assignedStaffInput = null;
    let assignedDateInput = null;
    let dueDateInput = null;
    let parentTaskSelect = null;
    let tasksList = null;
    let emptyTasksMessage = null;
    
    // Initialize elements on page load for better performance
    document.addEventListener('DOMContentLoaded', function() {
        taskModal = document.getElementById('taskModal');
        taskNameInput = document.getElementById('task_name');
        taskDescInput = document.getElementById('task_description');
        assignedStaffInput = document.getElementById('assigned_staff');
        assignedDateInput = document.getElementById('assigned_date');
        dueDateInput = document.getElementById('task_due_date');
        parentTaskSelect = document.getElementById('parent_id');
        tasksList = document.getElementById('tasks-list');
        emptyTasksMessage = document.getElementById('empty-tasks');
        
        // Add event listeners for keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Close modal on Escape key
            if (e.key === 'Escape' && !taskModal.classList.contains('hidden')) {
                closeTaskModal();
            }
        });
        
        // Initialize tasks display
        updateTasksDisplay();
    });

    function openTaskModal() {
        taskModal.classList.remove('hidden');
        taskModal.classList.add('flex');
        // Focus on the first input for better UX
        setTimeout(() => taskNameInput.focus(), 100);
    }

    function closeTaskModal() {
        taskModal.classList.remove('flex');
        taskModal.classList.add('hidden');
        
        // Clear form fields
        document.getElementById("taskForm").reset();
        
        // Clear any error messages
        const errorDiv = document.getElementById("taskFormErrors");
        errorDiv.innerHTML = "";
        errorDiv.classList.add("hidden");
    }

    function submitTask(event) {
        event.preventDefault();

        // Clear previous errors
        const errorDiv = document.getElementById("taskFormErrors");
        errorDiv.innerHTML = "";
        errorDiv.classList.add("hidden");

        // Retrieve and trim input values
        let taskName = taskNameInput.value.trim();
        let taskDescription = taskDescInput.value.trim();
        let assignedStaff = assignedStaffInput.value.trim();
        let assignedDate = assignedDateInput.value.trim();
        let dueDate = dueDateInput.value.trim();
        let parentTaskId = parentTaskSelect.value;

        let errors = [];

        // Validate required fields
        if (!taskName) {
            errors.push("Task Name is required.");
        }
        if (!taskDescription) {
            errors.push("Task Description is required.");
        }
        if (!assignedStaff) {
            errors.push("Assigned Staff is required.");
        }
        if (!assignedDate) {
            errors.push("Assigned Date is required.");
        }
        if (!dueDate) {
            errors.push("Due Date is required.");
        }

        // Validate that due date is not before assigned date
        if (assignedDate && dueDate) {
            let assignedDateObj = new Date(assignedDate);
            let dueDateObj = new Date(dueDate);
            if (dueDateObj < assignedDateObj) {
                errors.push("Due Date must be on or after Assigned Date.");
            }
        }

        // If errors exist, display them and do not add the task
        if (errors.length > 0) {
            errorDiv.innerHTML = `
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-medium">Please correct the following errors:</span>
                </div>
                <ul class="list-disc pl-10">
                    ${errors.map(err => `<li>${err}</li>`).join("")}
                </ul>
            `;
            errorDiv.classList.remove("hidden");
            return false;
        }

        // If validation passes, create the task object
        let task = {
            task_name: taskName,
            task_description: taskDescription,
            assigned_staff: assignedStaff,
            assigned_date: assignedDate,
            due_date: dueDate,
            parent_id: parentTaskId || null // Use null if no parent is selected
        };

        tasksArray.push(task);
        updateTasksDisplay();
        document.getElementById("taskForm").reset();
        closeTaskModal();
        
        // Show success notification
        showNotification('Task added successfully!', 'success');
        return false;
    }

    function updateTasksDisplay() {
        tasksList.innerHTML = "";
        
        if (tasksArray.length === 0) {
            // Show empty state message
            emptyTasksMessage.classList.remove('hidden');
        } else {
            // Hide empty state message
            emptyTasksMessage.classList.add('hidden');
            
            // Add tasks to the list
            tasksArray.forEach((task, index) => {
                // Format the date for display
                let formattedDueDate = new Date(task.due_date).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
                
                let parentTaskName = '';
                if (task.parent_id) {
                    const parentTask = tasksArray.find(t => t.task_name === task.parent_id);
                    parentTaskName = parentTask ? parentTask.task_name : 'Unknown';
                }
                
                let taskItem = document.createElement('div');
                taskItem.className = 'task-item py-5 flex flex-col';
                taskItem.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="font-medium text-gray-800 text-lg">${task.task_name}</span>
                                <div class="mt-1 text-sm text-gray-600">${task.task_description}</div>
                                <div class="mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm text-gray-500">Assigned to: ${task.assigned_staff}</span>
                                </div>
                                ${task.parent_id ? `
                                    <div class="mt-1 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <span class="text-xs text-gray-500">Parent Task: ${parentTaskName}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Due: ${formattedDueDate}
                        </div>
                    </div>
                    <div class="flex justify-end mt-2">
                        <button type="button" class="text-red-500 hover:text-red-700 text-sm flex items-center" onclick="removeTask(${index})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove
                        </button>
                    </div>
                `;
                
                tasksList.appendChild(taskItem);
            });
        }

        // Update the hidden input with the tasks array
        document.getElementById("tasksInput").value = JSON.stringify(tasksArray);
        
        // Update parent task dropdown
        updateParentTaskDropdown();
    }

    function removeTask(index) {
        if (confirm('Are you sure you want to remove this task?')) {
            // Check if any tasks have this task as a parent
            const taskName = tasksArray[index].task_name;
            const hasChildren = tasksArray.some(task => task.parent_id === taskName);
            
            if (hasChildren) {
                alert('This task cannot be removed because it is a parent to other tasks. Please remove the child tasks first.');
                return;
            }
            
            tasksArray.splice(index, 1);
            updateTasksDisplay();
            showNotification('Task removed successfully!', 'info');
        }
    }

    function updateParentTaskDropdown() {
        parentTaskSelect.innerHTML = '<option value="">None</option>';
        
        tasksArray.forEach(task => {
            let option = document.createElement("option");
            option.value = task.task_name;
            option.textContent = task.task_name;
            parentTaskSelect.appendChild(option);
        });
    }

    // Show notification
    function showNotification(message, type = 'info') {
        // Create notification element if it doesn't exist
        let notification = document.getElementById('notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg z-50 transform transition-all duration-300 translate-x-full';
            document.body.appendChild(notification);
        }
        
        // Set notification style based on type
        if (type === 'success') {
            notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg z-50 transform transition-all duration-300 bg-green-100 text-green-800 border-l-4 border-green-500';
        } else if (type === 'error') {
            notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg z-50 transform transition-all duration-300 bg-red-100 text-red-800 border-l-4 border-red-500';
        } else {
            notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg z-50 transform transition-all duration-300 bg-blue-100 text-blue-800 border-l-4 border-blue-500';
        }
        
        notification.innerHTML = message;
        
        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
        }, 3000);
    }

    // Form validation
    document.getElementById("projectForm").addEventListener("submit", function(event) {
        let isValid = true;
        let firstInvalidField = null;
        
        // Validate project fields
        const requiredFields = [
            'project_name',
            'project_description',
            'project_date',
            'due_date',
            'supervisor_name'
        ];
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        // Validate project dates
        const projectDate = document.getElementById('project_date').value;
        const dueDate = document.getElementById('due_date').value;
        
        if (projectDate && dueDate) {
            let projectDateObj = new Date(projectDate);
            let dueDateObj = new Date(dueDate);
            if (dueDateObj < projectDateObj) {
                alert("Project Due Date must be on or after the Project Start Date.");
                isValid = false;
                if (!firstInvalidField) firstInvalidField = document.getElementById('due_date');
            }
        }
        
        // Update the hidden input with the tasks array
        document.getElementById("tasksInput").value = JSON.stringify(tasksArray);
        
        if (!isValid) {
            event.preventDefault();
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
@endsection