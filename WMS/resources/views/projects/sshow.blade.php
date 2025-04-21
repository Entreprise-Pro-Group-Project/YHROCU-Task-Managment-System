@extends('layouts.sapp')

@section('content')
    <div class="container mx-auto p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Activity Log Section -->
            <div class="mb-10">
                @livewire('activity-log-table', ['projectId' => $project->id])
            </div>
            
            <!-- Project Details Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header with gradient background -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Project Details
                        </h2>
                        <a href="/supervisor/dashboard" class="inline-flex items-center px-4 py-2 bg-white text-blue-700 rounded-md font-medium text-sm hover:bg-gray-50 transition-colors duration-150 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
                
                <!-- Project Information -->
                <div class="p-8">
                    <!-- Project Name and Status -->
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $project->project_name }}</h3>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Active
                        </div>
                    </div>
                    
                    <!-- Project Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Supervisor -->
                        <div class="bg-gray-50 rounded-lg p-5">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Supervisor</h4>
                            </div>
                            <p class="text-lg font-medium text-gray-800">{{ $project->supervisor_name }}</p>
                        </div>
                        
                        <!-- Timeline -->
                        <div class="bg-gray-50 rounded-lg p-5">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Timeline</h4>
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">Start Date</p>
                                    <p class="text-md font-medium text-gray-800">{{ \Carbon\Carbon::parse($project->project_date)->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center text-gray-400 px-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Due Date</p>
                                    <p class="text-md font-medium text-gray-800">{{ \Carbon\Carbon::parse($project->due_date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Description -->
                    <div class="bg-gray-50 rounded-lg p-5 mb-6">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Description</h4>
                        </div>
                        <p class="text-gray-700 leading-relaxed">{{ $project->project_description }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Progress Card -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">Project Progress</h3>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-4 mb-6">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-4 rounded-full" style="width: 65%"></div>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Total Tasks</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $project->tasks->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Completed</p>
                        <p class="text-2xl font-bold text-green-600">{{ $project->tasks->where('status', 'completed')->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">In Progress</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $project->tasks->where('status', 'in progress')->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @php
                            $days = (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($project->due_date), false);
                        @endphp
                        
                        <p class="text-sm text-gray-500 mb-1">
                            @if($days < 0)
                                Days Overdue
                            @else
                                Days Left
                            @endif
                        </p>

                        <p class="text-2xl font-bold text-indigo-600">
                            @if($days < 0)
                                {{ abs($days) }}
                            @else
                                {{ $days }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection