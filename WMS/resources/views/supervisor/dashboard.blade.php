@extends('layouts.sapp')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Display Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="flex flex-col md:flex-row items-center gap-4 mb-6">
        <!-- Filter Dropdown -->
        <div class="relative inline-block">
            <button
                id="filterButton"
                class="bg-blue-400 text-black text-sm px-4 py-2 rounded hover:bg-blue-500 flex items-center space-x-1"
            >
                <span>Filter by Status</span>
                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
                id="filterDropdown"
                class="hidden absolute top-full left-0 mt-1 w-40 bg-white rounded-md shadow-lg z-20 text-sm"
            >
                <a href="{{ route('supervisor.dashboard', ['status' => 'all']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">All Tasks</a>
                <a href="{{ route('supervisor.dashboard', ['status' => 'assigned']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">Assigned</a>
                <a href="{{ route('supervisor.dashboard', ['status' => 'in progress']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">In Progress</a>
                <a href="{{ route('supervisor.dashboard', ['status' => 'completed']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">Completed</a>
                <a href="{{ route('supervisor.dashboard', ['status' => 'overdue']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50">Over Due</a>
            </div>
        </div>

        <!-- Search Input -->
        <div class="flex-1 flex items-center justify-end gap-2 w-full md:w-auto">
            <span class="font-bold text-blue-600">Search:</span>
            <input
                type="text"
                id="searchInput"
                placeholder="Search projects..."
                class="border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 max-w-md"
            />
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-6">Supervisor Dashboard</h2>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="projectsGrid">
        @foreach ($projects as $project)
            @php
                // Calculate progress percentage: (completed tasks / total tasks) * 100
                $totalTasks = $project->tasks->count();
                $completedTasks = $project->tasks->where('status', 'completed')->count();
                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                // Determine header color based on progress percentage
                $headerColor = 'bg-red-500';
                if ($progress >= 75) {
                    $headerColor = 'bg-green-500';
                } elseif ($progress >= 50) {
                    $headerColor = 'bg-blue-500';
                } elseif ($progress >= 25) {
                    $headerColor = 'bg-yellow-500';
                }
            @endphp
            
            <div class="project-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" data-project-name="{{ strtolower($project->project_name) }}">
                <!-- Card Header -->
                <div class="{{ $headerColor }} text-white p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold truncate">{{ $project->project_name }}</h3>
                        <span class="bg-white/20 text-white text-sm px-2 py-1 rounded-full">
                            {{ $progress }}%
                        </span>
                    </div>
                </div>
                
                <!-- Card Content -->
                <div class="p-4">
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-500">Start:</span>
                            <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($project->project_date)->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm">
                            <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-gray-500">Due:</span>
                            <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($project->due_date)->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm">
                            <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-gray-500">Supervisor:</span>
                            <span class="ml-2 font-medium">{{ $project->supervisor_name }}</span>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium">Progress</span>
                            <span class="text-sm font-medium">{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $headerColor }}" style="width: '{{ $progress }}%';"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="border-t">
                    <!-- Tasks Toggle Button -->
                    <button 
                        class="w-full flex items-center justify-center py-2 text-gray-600 hover:bg-gray-50 transition-colors duration-200 task-toggle"
                        data-project-id="{{ $project->id }}"
                    >
                        <span class="show-text">Show Tasks</span>
                        <span class="hide-text hidden">Hide Tasks</span>
                        <svg class="chevron-down ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <svg class="chevron-up ml-2 h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    
                    <!-- Tasks Container (Hidden by default unless filtered) -->
                    
<div class="tasks-container {{ ($status === 'all') || ($status !== 'all' && count($project->tasks) > 0) ? '' : 'hidden' }} border-t">

                        <div class="p-4 space-y-3">
                            @if(count($project->tasks) > 0)
                                @foreach($project->tasks as $task)
                                    @php
                                        // Check if task is overdue
                                        $isOverdue = \Carbon\Carbon::parse($task->due_date) < \Carbon\Carbon::now() && strtolower($task->status) !== 'completed';
                                        
                                        // Determine status color and display label
                                        $statusColor = 'bg-gray-100 text-gray-800 border-gray-200';
                                        $taskStatus = strtolower($task->status);
                                        
                                        if ($isOverdue) {
                                            $statusColor = 'bg-red-100 text-red-800 border-red-200';
                                            $displayStatus = 'Over Due';
                                        } elseif ($taskStatus === 'completed') {
                                            $statusColor = 'bg-green-100 text-green-800 border-green-200';
                                            $displayStatus = 'Completed';
                                        } elseif ($taskStatus === 'in progress') {
                                            $statusColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                            $displayStatus = 'In Progress';
                                        } elseif ($taskStatus === 'assigned') {
                                            $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                            $displayStatus = 'Assigned';
                                        } else {
                                            $displayStatus = ucfirst($task->status);
                                        }
                                    @endphp
                                    
                                    <div class="task-item border rounded-md overflow-hidden">
                                        <div class="p-3 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium">{{ $task->task_name }}</h4>
                                                    <div class="mt-1 text-sm text-gray-600">
                                                        @if ($task->parent_id)
                                                        <div>Parent Task: 
                                                            <span class="font-medium">
                                                                {{ optional($task->parent)->task_name }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                        <div>Staff: <span class="font-medium">{{ $task->assigned_staff }}</span></div>
                                                        <div>Assigned: <span class="font-medium">{{ \Carbon\Carbon::parse($task->assigned_date)->format('M d, Y') }}</span></div>
                                                        <div>Due: <span class="font-medium">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span></div>
                                                    </div>
                                                </div>
                                                
                                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }} whitespace-nowrap">
    {{ $displayStatus }}
</span>

                                            </div>
                                            
                                            @if($task->Comment)
                                                <div class="mt-2 text-sm">
                                                    <span class="font-medium">Comments:</span> {{ $task->Comment }}
                                                </div>
                                            @endif
                                            
                                            <div class="flex mt-3 space-x-2">
                                                <a href="{{ route('tasks.show', $task->id) }}" class="px-3 py-1 text-sm border border-blue-200 text-blue-500 rounded hover:bg-blue-50">
                                                    View
                                                </a>
                                                <a href="{{ route('tasks.edit', $task->id) }}" class="px-3 py-1 text-sm border border-yellow-200 text-yellow-500 rounded hover:bg-yellow-50">
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                        
                                        @if(count($task->subtasks) > 0)
                                            <div class="subtasks-section">
                                                <button 
                                                    class="w-full flex items-center justify-center py-1 text-xs text-gray-600 hover:bg-gray-50 transition-colors duration-200 border-t subtask-toggle"
                                                    data-task-id="{{ $task->id }}"
                                                >
                                                    <span class="show-text">Show Subtasks</span>
                                                    <span class="hide-text hidden">Hide Subtasks</span>
                                                    <svg class="chevron-down ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    <svg class="chevron-up ml-1 h-3 w-3 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </button>
                                                
                                                <div class="subtasks-container hidden border-t">
                                                    <div class="p-2 space-y-2">
                                                        @foreach($task->subtasks as $subtask)
                                                            @php
                                                                // Check if subtask is overdue
                                                                $isSubtaskOverdue = \Carbon\Carbon::parse($subtask->due_date) < \Carbon\Carbon::now() && strtolower($subtask->status) !== 'completed';
                                                                
                                                                // Determine subtask status color and display label
                                                                $subtaskStatusColor = 'bg-gray-100 text-gray-800 border-gray-200';
                                                                $subtaskStatus = strtolower($subtask->status);
                                                                
                                                                if ($isSubtaskOverdue) {
                                                                    $subtaskStatusColor = 'bg-red-100 text-red-800 border-red-200';
                                                                    $displaySubtaskStatus = 'Over Due';
                                                                } elseif ($subtaskStatus === 'completed') {
                                                                    $subtaskStatusColor = 'bg-green-100 text-green-800 border-green-200';
                                                                    $displaySubtaskStatus = 'Completed';
                                                                } elseif ($subtaskStatus === 'in progress') {
                                                                    $subtaskStatusColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                                                    $displaySubtaskStatus = 'In Progress';
                                                                } elseif ($subtaskStatus === 'assigned') {
                                                                    $subtaskStatusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                                                    $displaySubtaskStatus = 'Assigned';
                                                                } else {
                                                                    $displaySubtaskStatus = ucfirst($subtask->status);
                                                                }
                                                            @endphp
                                                            
                                                            <div class="border rounded-md p-2 text-sm">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <div class="font-medium">{{ $subtask->task_name }}</div>
                                                                        <div class="text-gray-600">
                                                                            <div>Assigned to: {{ $subtask->assigned_staff }}</div>
                                                                            <div>Due: {{ \Carbon\Carbon::parse($subtask->due_date)->format('M d, Y') }}</div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <span class="px-2 py-1 text-xs rounded-full {{ $subtaskStatusColor }}">
                                                                        {{ $displaySubtaskStatus }}
                                                                    </span>
                                                                </div>
                                                                
                                                                <div class="flex mt-2 space-x-2">
                                                                    <a href="{{ route('tasks.show', $subtask->id) }}" class="px-2 py-1 text-xs border border-blue-200 text-blue-500 rounded hover:bg-blue-50">
                                                                        View
                                                                    </a>
                                                                    <a href="{{ route('tasks.edit', $subtask->id) }}" class="px-2 py-1 text-xs border border-yellow-200 text-yellow-500 rounded hover:bg-yellow-50">
                                                                        Edit
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 text-center py-2">
                                    No tasks for this project
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex w-full border-t">
                        <a href="{{ route('projects.show', $project->id) }}" class="flex-1 py-2 text-center text-blue-500 hover:text-blue-700 hover:bg-blue-50 transition-colors duration-200">
                            View
                        </a>
                        <div class="w-px bg-gray-200"></div>
                        <a href="{{ route('projects.edit', $project->id) }}" class="flex-1 py-2 text-center text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 transition-colors duration-200">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- JavaScript for interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter dropdown toggle
        const filterButton = document.getElementById('filterButton');
        const filterDropdown = document.getElementById('filterDropdown');
        
        if (filterButton && filterDropdown) {
            filterButton.addEventListener('click', function() {
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
                    filterDropdown.classList.add('hidden');
                }
            });
        }
        
        // Task toggles
        const taskToggles = document.querySelectorAll('.task-toggle');
        taskToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const projectCard = this.closest('.project-card');
                const tasksContainer = projectCard.querySelector('.tasks-container');
                const showText = this.querySelector('.show-text');
                const hideText = this.querySelector('.hide-text');
                const chevronDown = this.querySelector('.chevron-down');
                const chevronUp = this.querySelector('.chevron-up');
                
                tasksContainer.classList.toggle('hidden');
                showText.classList.toggle('hidden');
                hideText.classList.toggle('hidden');
                chevronDown.classList.toggle('hidden');
                chevronUp.classList.toggle('hidden');
            });
        });
        
        // Subtask toggles
        const subtaskToggles = document.querySelectorAll('.subtask-toggle');
        subtaskToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const taskItem = this.closest('.task-item');
                const subtasksContainer = taskItem.querySelector('.subtasks-container');
                const showText = this.querySelector('.show-text');
                const hideText = this.querySelector('.hide-text');
                const chevronDown = this.querySelector('.chevron-down');
                const chevronUp = this.querySelector('.chevron-up');
                
                subtasksContainer.classList.toggle('hidden');
                showText.classList.toggle('hidden');
                hideText.classList.toggle('hidden');
                chevronDown.classList.toggle('hidden');
                chevronUp.classList.toggle('hidden');
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const projectCards = document.querySelectorAll('.project-card');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                projectCards.forEach(card => {
                    const projectName = card.getAttribute('data-project-name');
                    if (projectName.includes(searchTerm)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>
@endsection
