@extends('layouts.sapp')


@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-5xl">
            <!-- Admin Header Bar -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold">Supervisor Task Management</h2>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm backdrop-blur-sm">Task #{{ $task->id }}</span>
                    </div>
                </div>

                <div class="p-4 border-b flex flex-wrap items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-sm text-gray-800 font-medium">Task Details</span>
                </div>
            </div>

            <!-- Task Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">{{ $task->task_name }}</h1>
                        
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
                <!-- Left Column: Task Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Details Card -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Task Information
                            </h2>
                        </div>

                        <div class="p-6">
                            <!-- Task Description -->
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Description</h3>
                                <div class="prose prose-blue max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg">
                                    {{ $task->task_description }}
                                </div>
                            </div>

                            <!-- Task Meta Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Assignment Details</h3>
                                    
                                    <div class="space-y-4">
                                        <!-- Assigned To -->
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Assigned To</p>
                                                <div class="mt-1 flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                                        {{ strtoupper(substr($task->assigned_staff, 0, 2)) }}
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-700">{{ $task->assigned_staff }}</span>
                                                </div>
                                            </div>
                                        </div>
                                                
                                        <!-- Assigned Date -->
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Assigned Date</p>
                                                <p class="text-sm text-gray-700 mt-1">{{ \Carbon\Carbon::parse($task->assigned_date)->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Timeline</h3>
                                            
                                    <div class="space-y-4">
                                        <!-- Due Date -->
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 {{ $isOverdue ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Due Date</p>
                                                <p class="text-sm {{ $isOverdue ? 'text-red-500 font-medium' : 'text-gray-700' }} mt-1">
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                                    
                                                    @php
                                                        $daysLeft = (int)\Carbon\Carbon::parse($task->due_date)->diffInDays(\Carbon\Carbon::now(), false);
                                                    @endphp
                                                        
                                                    @if($isOverdue)
                                                        <span class="text-red-500 font-medium">
                                                            ({{ abs($daysLeft) }} {{ abs($daysLeft) == 1 ? 'day' : 'days' }} overdue)
                                                        </span>
                                                    @elseif($daysLeft < 0)
                                                        <span class="text-yellow-600">
                                                            ({{ abs($daysLeft) }} {{ abs($daysLeft) == 1 ? 'day' : 'days' }} left)
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                                
                                        <!-- Created At -->
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Created</p>
                                                <p class="text-sm text-gray-700 mt-1">{{ \Carbon\Carbon::parse($task->created_at)->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Staff Comments
                            </h2>
                        </div>

                        <div class="p-6">
                            <!-- Existing Comments -->
                            <div class="space-y-6 mb-8">
                                @if(isset($task->comments) && $task->comments->count() > 0)
                                    @foreach($task->comments as $comment)
                                        <div class="flex space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                            </div>

                                            <div class="flex-1 bg-gray-50 rounded-lg px-4 py-3 sm:px-6 sm:py-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <h3 class="text-sm font-medium text-gray-900">
                                                        {{ $comment->user->name ?? 'User' }} 
                                                        @if(isset($comment->user->role))
                                                        ({{ ucfirst($comment->user->role) }})
                                                        @endif
                                                    </h3>
                                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y \a\t g:i A') }}</p>
                                                </div>

                                                <div class="text-sm text-gray-700 whitespace-pre-line">
                                                    {{ $comment->comment }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(isset($task->comment) && !empty($task->comment))
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($task->assigned_staff, 0, 1)) }}
                                            </div>
                                        </div>

                                        <div class="flex-1 bg-gray-50 rounded-lg px-4 py-3 sm:px-6 sm:py-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <h3 class="text-sm font-medium text-gray-900">System Comment</h3>
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($task->updated_at)->format('M d, Y \a\t g:i A') }}</p>
                                            </div>
                                            <div class="text-sm text-gray-700 whitespace-pre-line">
                                                {{ $task->comment }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8 px-4">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No comments yet</h3>
                                        <p class="mt-1 text-sm text-gray-500">No staff comments have been added to this task yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Add Comment Form (Admin) -->
                            <div class="border-t border-gray-100 pt-6">
                                <h3 class="text-base font-medium text-gray-900 mb-4">Add a supervsior comment</h3>
                                <form action="{{ route('comment', $task->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea 
                                            name="comment" 
                                            id="comment-{{ $task->id }}" 
                                            rows="3" 
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Add your comment here..."
                                            maxlength="500">
                                        </textarea>
                                        <div class="mt-1 text-xs text-right text-gray-400">
                                            <span id="charCount-{{ $task->id }}">0</span>/500 characters
                                        </div>
                                    </div>
                                            
                                    <!-- Quick Comment Suggestions -->
                                    <div class="mb-4">
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" class="comment-suggestion text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full transition-colors">
                                                Please provide an update
                                            </button>
                                            <button type="button" class="comment-suggestion text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full transition-colors">
                                                Priority has changed                                                
                                            </button>
                                            <button type="button" class="comment-suggestion text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full transition-colors">
                                                Due date extended
                                            </button>
                                            <button type="button" class="comment-suggestion text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full transition-colors">
                                                Additional resources available
                                            </button>
                                        </div>
                                    </div>
                                            
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Post Supervisor Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Back to Dashboard -->
                    <a href="{{ route('supervisor.dashboard') }}" class="block text-center bg-white-100 rounded-xl shadow-sm py-4 px-6 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors font-medium">
                        <svg class="h-5 w-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
             // Character counter for comment field
            const textarea = document.getElementById('comment-{{ $task->id }}');
            const charCount = document.getElementById('charCount-{{ $task->id }}');
                    
            if (textarea && charCount) {
                // Update character count on load
                charCount.textContent = textarea.value.length;
                        
                // Update character count on input
                textarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                            
                    // Visual feedback for character limit
                    if (this.value.length > 400) {
                        charCount.classList.add('text-yellow-500');
                        charCount.classList.remove('text-red-500', 'text-gray-400');
                    } else if (this.value.length > 450) {
                        charCount.classList.add('text-red-500');
                        charCount.classList.remove('text-yellow-500', 'text-gray-400');
                    } else {
                        charCount.classList.add('text-gray-400');
                        charCount.classList.remove('text-yellow-500', 'text-red-500');
                    }
                });
            }
                    
            // Comment suggestions
            document.querySelectorAll('.comment-suggestion').forEach(button => {
                button.addEventListener('click', function() {
                    if (textarea) {
                        const suggestionText = this.textContent.trim();
                
                        // If textarea is empty, just add the suggestion
                        // Otherwise, add a space and then the suggestion
                        if (textarea.value.trim() === '') {
                            textarea.value = suggestionText;
                        } else {
                            textarea.value = textarea.value.trim() + '. ' + suggestionText;
                        }
                                
                        // Update character count
                        if (charCount) {
                            charCount.textContent = textarea.value.length;
                        }
                                
                        // Focus the textarea and move cursor to the end
                        textarea.focus();
                        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
                    }
                });
            });
        });
    </script>
@endsection