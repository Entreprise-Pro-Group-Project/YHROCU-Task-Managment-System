@extends('layouts.sapp')

@section('content')
    <div class="container mx-auto p-6 max-w-5xl">
        <div class="bg-white rounded-lg shadow-xl p-8 mb-6">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h2 class="text-3xl font-bold text-gray-800">Edit Project</h2>
                </div>
                <a href="/supervisor/dashboard" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <div id="projectFormErrors" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-8"></div>
            
            <form method="POST" action="{{ route('projects.update', $project->id) }}" onsubmit="return validateProjectUpdate()" novalidate>
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="relative">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                        </div>
                        <input type="text" name="project_name" id="project_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ $project->project_name }}" required>
                    </div>
                    
                    <div class="relative">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                        </div>
                        
                        <select name="supervisor_name" id="supervisor_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" required>
                            <option value="">Select Supervisor</option>
                            @foreach ($supervisors as $supervisor)
                            <option value="{{ $supervisor->first_name }}" 
                                @if ($project->supervisor_name === $supervisor->first_name) selected @endif>
                                {{ ucfirst($supervisor->first_name) }} {{ ucfirst($supervisor->last_name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <label for="project_description" class="block text-sm font-medium text-gray-700">Project Description</label>
                    </div>
                    <textarea name="project_description" id="project_description" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base" required>{{ $project->project_description }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="relative">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <label for="project_date" class="block text-sm font-medium text-gray-700">Project Start Date</label>
                        </div>
                        <input type="date" name="project_date" id="project_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ $project->project_date }}" required>
                    </div>
                    
                    <div class="relative">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Project Due Date</label>
                        </div>
                        <input type="date" name="due_date" id="project_due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3" value="{{ $project->due_date }}" required>
                    </div>
                </div>

                <!-- Task List Section -->
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
                        @if(count($project->tasks) > 0)
                            <ul id="task-list" class="divide-y divide-gray-200">
                                @foreach($project->tasks as $task)
                                    <li class="task-item py-5 flex flex-col">
                                        <input type="hidden" name="tasks[{{ $loop->index }}][id]" value="{{ $task->id }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <span class="font-medium text-gray-800 text-lg">{{ $task->task_name }}</span>
                                                    <div class="mt-1 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        <span class="text-sm text-gray-500">Assigned to: {{ $task->assigned_staff }}</span>
                                                    </div>
                                                    @if($task->parent_id)
                                                        <div class="mt-1 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            <span class="text-xs text-gray-500">Parent Task: {{ optional($task->parent)->task_name }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <input type="hidden" name="tasks[{{ $loop->index }}][task_name]" value="{{ $task->task_name }}">
                                        <input type="hidden" name="tasks[{{ $loop->index }}][task_description]" value="{{ $task->task_description }}">
                                        <input type="hidden" name="tasks[{{ $loop->index }}][assigned_staff]" value="{{ $task->assigned_staff }}">
                                        <input type="hidden" name="tasks[{{ $loop->index }}][assigned_date]" value="{{ $task->assigned_date }}">
                                        <input type="hidden" name="tasks[{{ $loop->index }}][due_date]" value="{{ $task->due_date }}">
                                        <input type="hidden" name="tasks[{{ $loop->index }}][parent_id]" value="{{ $task->parent_id }}">
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-8 flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-gray-500 text-lg">No tasks added yet.</p>
                                <p class="text-gray-400 mt-1">Click "Add New Task" to create your first task.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end mt-10">
                    <button type="submit" class="inline-flex items-center px-8 py-4 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Project
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
            
            <div class="space-y-5">
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <label for="task_name" class="block text-sm font-medium text-gray-700">Task Name</label>
                    </div>
                    <input type="text" id="task_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3">
                </div>
                
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <label for="task_description" class="block text-sm font-medium text-gray-700">Task Description</label>
                    </div>
                    <textarea id="task_description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base"></textarea>
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
                        <input type="date" id="assigned_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3">
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        </div>
                        <input type="date" id="due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3">
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <label for="parent_task" class="block text-sm font-medium text-gray-700">Parent Task (Optional)</label>
                    </div>
                    <select id="parent_task" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base py-3">
                        <option value="">None</option>
                        @foreach($project->tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->task_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button onclick="closeTaskModal()" class="inline-flex items-center px-5 py-3 bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 focus:ring-opacity-50 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </button>
                <button onclick="addTask()" class="inline-flex items-center px-5 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Task
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Script --}}
    <script>
        // Cache DOM elements for better performance
        let taskModal = null;
        let taskNameInput = null;
        let taskDescInput = null;
        let assignedStaffInput = null;
        let assignedDateInput = null;
        let dueDateInput = null;
        let parentTaskSelect = null;
        
        // Initialize elements on page load for better performance
        document.addEventListener('DOMContentLoaded', function() {
            taskModal = document.getElementById('taskModal');
            taskNameInput = document.getElementById('task_name');
            taskDescInput = document.getElementById('task_description');
            assignedStaffInput = document.getElementById('assigned_staff');
            assignedDateInput = document.getElementById('assigned_date');
            dueDateInput = document.getElementById('due_date');
            parentTaskSelect = document.getElementById('parent_task');
            
            // Add event listeners for keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Close modal on Escape key
                if (e.key === 'Escape' && !taskModal.classList.contains('hidden')) {
                    closeTaskModal();
                }
            });
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
            taskNameInput.value = '';
            taskDescInput.value = '';
            assignedStaffInput.value = '';
            assignedDateInput.value = '';
            dueDateInput.value = '';
            parentTaskSelect.value = '';
        }

        function addTask() {
            let taskName = taskNameInput.value.trim();
            let taskDesc = taskDescInput.value;
            let assignedStaff = assignedStaffInput.value.trim();
            let assignedDate = assignedDateInput.value;
            let dueDate = dueDateInput.value;
            let parentTaskId = parentTaskSelect.value;
            let parentTaskName = '';
            
            // Validate required fields
            if (!validateTaskInputs(taskName, assignedStaff, assignedDate, dueDate)) {
                return;
            }
            
            if (parentTaskId) {
                let selectedOption = parentTaskSelect.options[parentTaskSelect.selectedIndex];
                parentTaskName = selectedOption.text;
            }
            
            let taskList = document.getElementById('task-list');
            
            // If task list doesn't exist yet (no tasks), create it
            if (!taskList) {
                let taskContainer = document.querySelector('.bg-gray-50');
                taskContainer.innerHTML = '<ul id="task-list" class="divide-y divide-gray-200"></ul>';
                taskList = document.getElementById('task-list');
                
                // Remove the empty state message
                let emptyState = taskContainer.querySelector('div.text-center');
                if (emptyState) {
                    emptyState.remove();
                }
            }
            
            let taskIndex = taskList.children.length;
            
            // Format the date for display
            let formattedDueDate = new Date(dueDate).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            
            let taskItem = document.createElement('li');
            taskItem.className = 'task-item py-5 flex flex-col';
            taskItem.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <span class="font-medium text-gray-800 text-lg">${taskName}</span>
                            <div class="mt-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm text-gray-500">Assigned to: ${assignedStaff}</span>
                            </div>
                            ${parentTaskId ? `
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
                <input type="hidden" name="tasks[${taskIndex}][task_name]" value="${taskName}">
                <input type="hidden" name="tasks[${taskIndex}][task_description]" value="${taskDesc}">
                <input type="hidden" name="tasks[${taskIndex}][assigned_staff]" value="${assignedStaff}">
                <input type="hidden" name="tasks[${taskIndex}][assigned_date]" value="${assignedDate}">
                <input type="hidden" name="tasks[${taskIndex}][due_date]" value="${dueDate}">
                <input type="hidden" name="tasks[${taskIndex}][parent_id]" value="${parentTaskId}">
            `;
            
            taskList.appendChild(taskItem);
            closeTaskModal();
            
            // Show success notification
            showNotification('Task added successfully!', 'success');
        }
        
        // Validate task inputs
        function validateTaskInputs(taskName, assignedStaff, assignedDate, dueDate) {
            if (!taskName || !assignedStaff) {
                showNotification("Task name and assigned staff are required.", "error");
                return false;
            }
            
            if (!assignedDate || !dueDate) {
                showNotification("Both Assigned Date and Due Date are required.", "error");
                return false;
            }
            
            let assignedDateObj = new Date(assignedDate);
            let dueDateObj = new Date(dueDate);
            if (dueDateObj < assignedDateObj) {
                showNotification("Due Date must be on or after the Assigned Date.", "error");
                return false;
            }
            
            return true;
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
    </script>

    {{-- Validation Script --}}
    <script>
        function validateProjectUpdate() {
            // Clear any previous errors
            let errorDiv = document.getElementById('projectFormErrors');
            errorDiv.innerHTML = "";
            errorDiv.classList.add("hidden");

            // Retrieve and trim input values
            let projectName = document.getElementById('project_name').value.trim();
            let projectDescription = document.getElementById('project_description').value.trim();
            let projectDate = document.getElementById('project_date').value;
            let dueDate = document.getElementById('project_due_date').value;
            let supervisorName = document.getElementById('supervisor_name').value.trim();

            let errors = [];

            if (!projectName) {
                errors.push("Project name cannot be empty.");
            }
            if (!projectDescription) {
                errors.push("Project description is required.");
            }
            if (!projectDate) {
                errors.push("Project start date is required.");
            }
            if (!dueDate) {
                errors.push("Project due date is required.");
            }
            if (!supervisorName) {
                errors.push("Supervisor name is required.");
            }

            if (projectDate && dueDate) {
                let projDateObj = new Date(projectDate);
                let dueDateObj = new Date(dueDate);
                if (dueDateObj < projDateObj) {
                    errors.push("Due date must be on or after the project start date.");
                }
            }

            if (errors.length > 0) {
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="font-medium">Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc pl-10 mt-2">
                        ${errors.map(e => "<li>" + e + "</li>").join('')}
                    </ul>
                `;
                errorDiv.classList.remove("hidden");
                
                // Smooth scroll to error message
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
            return true;
        }
        
        // Add form field validation on input
        document.addEventListener('DOMContentLoaded', function() {
            const requiredFields = [
                'project_name',
                'project_description',
                'project_date',
                'project_due_date',
                'supervisor_name'
            ];
            
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            this.classList.add('border-red-500');
                            
                            // Add error message below field if not exists
                            let errorMsg = this.nextElementSibling;
                            if (!errorMsg || !errorMsg.classList.contains('text-red-500')) {
                                errorMsg = document.createElement('p');
                                errorMsg.className = 'text-red-500 text-xs mt-1';
                                errorMsg.textContent = 'This field is required';
                                this.parentNode.insertBefore(errorMsg, this.nextSibling);
                            }
                        } else {
                            this.classList.remove('border-red-500');
                            
                            // Remove error message if exists
                            let errorMsg = this.nextElementSibling;
                            if (errorMsg && errorMsg.classList.contains('text-red-500')) {
                                errorMsg.remove();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection