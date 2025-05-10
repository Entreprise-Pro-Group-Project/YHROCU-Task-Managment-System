@extends('layouts.sapp')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-5xl">
            <!-- Supervisor Header Bar -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold">Supervisor Task Management</h2>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm backdrop-blur-sm">Task #{{ $task->id }}</span>
                    </div>
                </div>
                <div class="p-4 border-b flex flex-wrap items-center gap-4">
                    <a href="{{ route('supervisor.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Task Details
                    </a>
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-sm text-gray-800 font-medium">Edit Task</span>
                </div>
            </div>

            <!-- Task Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Edit Task: {{ $task->task_name }}</h1>
                
                <!-- Task Status Badge -->
                @php
                    $statusColor = 'bg-gray-100 text-gray-800';
                    $status = strtolower($task->status ?? 'assigned');
                    $isOverdue = \Carbon\Carbon::parse($task->due_date) < \Carbon\Carbon::now() && $status !== 'completed';
                    
                    if ($status === 'completed') {
                        $statusColor = 'bg-green-100 text-green-800';
                    } elseif ($status === 'in progress') {
                        $statusColor = 'bg-blue-100 text-blue-800';
                    } elseif ($status === 'assigned') {
                        $statusColor = 'bg-yellow-100 text-yellow-800';
                    }
                    
                    if ($isOverdue) {
                        $statusColor = 'bg-red-100 text-red-800';
                        $status = 'overdue';
                    }
                @endphp
                
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $statusColor }} inline-flex items-center">
                    @if($status === 'completed')
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @elseif($status === 'in progress')
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($status === 'overdue')
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    @endif
                    {{ ucfirst($status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Task Edit Form -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Task Information
                            </h2>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <!-- Task Name -->
                                <div>
                                    <label for="task_name" class="block text-sm font-medium text-gray-700 mb-1">Task Name</label>
                                    <input 
                                        type="text" 
                                        name="task_name" 
                                        id="task_name" 
                                        value="{{ $task->task_name }}" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        required
                                    >
                                </div>
                                
                                <!-- Task Description -->
                                <div>
                                    <label for="task_description" class="block text-sm font-medium text-gray-700 mb-1">Task Description</label>
                                    <textarea 
                                        name="task_description" 
                                        id="task_description" 
                                        rows="5" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        required
                                    >{{ $task->task_description }}</textarea>
                                </div>
                                
                                <!-- Task Details Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Assigned Staff -->
                                    <div>
                                        <label for="assigned_staff" class="block text-sm font-medium text-gray-700 mb-1">Assigned Staff</label>
                                        <input 
                                            type="text" 
                                            name="assigned_staff" 
                                            id="assigned_staff" 
                                            value="{{ $task->assigned_staff }}" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                    </div>
                                    
                                    <!-- Assigned Date -->
                                    <div>
                                        <label for="assigned_date" class="block text-sm font-medium text-gray-700 mb-1">Assigned Date</label>
                                        <input 
                                            type="date" 
                                            name="assigned_date" 
                                            id="assigned_date" 
                                            value="{{ \Carbon\Carbon::parse($task->assigned_date)->format('Y-m-d') }}" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                    </div>
                                    
                                    <!-- Due Date -->
                                    <div>
                                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                        <input 
                                            type="date" 
                                            name="due_date" 
                                            id="due_date" 
                                            value="{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                    </div>
                                    
                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select 
                                            name="status" 
                                            id="status" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                            <option value="assigned" {{ $task->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="flex justify-end pt-4">
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Supervisor Actions -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Supervisor Actions
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Reassign Task</h3>
                                    <form action="{{ route('tasks.reassign', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center">
                                            <select name="assigned_staff" class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                                                <option value="">Select Staff Member</option>
                                                @foreach ($users as $staffMember)
                                                <option value="{{ $staffMember->email }}">
                                                    {{ ucfirst($staffMember->first_name) }} {{ ucfirst($staffMember->last_name) }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Reassign
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="mt-6">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Set Parent Task</h3>
                                    <form action="{{ route('tasks.updateParent', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center">
                                            <select name="parent_id" class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                                                <option value="">None</option>
                                                @foreach ($projectTasks as $ptask)
                                                <option value="{{ $ptask->id }}" {{ $task->parent_id == $ptask->id ? 'selected' : '' }}>
                                                    {{ $ptask->task_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                ParentID
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="border-t border-gray-100 mt-6 pt-6 space-y-3">
                                    <a href="{{ route('tasks.show', $task->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Task Details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Back to Dashboard -->
                        <a href="{{ route('supervisor.dashboard') }}" class="block text-center bg-white rounded-xl shadow-sm py-4 px-6 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors font-medium">
                            <svg class="h-5 w-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
@endsection