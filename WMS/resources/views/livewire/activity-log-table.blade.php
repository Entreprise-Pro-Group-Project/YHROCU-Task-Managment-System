<div class="bg-white dark:bg-gray-900 shadow rounded-lg overflow-hidden" x-data="activityLogTable()">
    <!-- Header Section with improved layout and more prominent buttons -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-800">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Project Title (Centered on mobile, left-aligned on desktop) -->
            <div class="order-2 md:order-1 text-center md:text-left">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $projectName ?? 'Unknown Project' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Activity History</p>
            </div>
            
            <!-- Export Actions - More Prominent -->
            <div class="order-1 md:order-2 flex flex-wrap items-center justify-center md:justify-end gap-3">
                <!-- Search Input -->
                <div class="w-full md:w-auto mb-3 md:mb-0">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search logs..." 
                            x-model="searchTerm" 
                            @input="filterTable()"
                            class="w-full px-4 py-2 pr-10 text-sm border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Export Buttons Group -->
                <div class="flex items-center space-x-2">
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-primary text-primary-foreground hover:bg-primary/90 transition-colors shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10"
                            style="display: none;"
                        >
                            <div class="py-1">
                                <a href="{{ route('logs.export.csv', ['project' => $projectId]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export as CSV
                                </a>
                                <a href="{{ route('logs.export.pdf', ['project' => $projectId]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Export as PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Button -->
                    <button 
                        @click="showFilters = !showFilters" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        :class="{ 'ring-2 ring-primary': showFilters }"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filters
                    </button>
                    
                    <!-- Refresh Button -->
                    <button 
                        wire:click="refresh" 
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Filters Section (Collapsible) -->
        <div x-show="showFilters" x-transition class="mt-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Action Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action Type</label>
                    <select 
                        x-model="filters.action" 
                        @change="filterTable()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                    >
                        <option value="">All Actions</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </div>
                
                <!-- Entity Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entity Type</label>
                    <select 
                        x-model="filters.entityType" 
                        @change="filterTable()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                    >
                        <option value="">All Entities</option>
                        <option value="task">Tasks</option>
                        <option value="task_comment">Comments</option>
                        <option value="project">Projects</option>
                    </select>
                </div>
                
                <!-- Date Range Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Range</label>
                    <select 
                        x-model="filters.dateRange" 
                        @change="filterTable()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                    >
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <button 
                    @click="resetFilters()" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section with datatable functionality -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800" id="activity-log-table">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortTable('user')">
                        <div class="flex items-center">
                            <span>User</span>
                            <span class="ml-1">
                                <template x-if="sortColumn === 'user' && sortDirection === 'asc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </template>
                                <template x-if="sortColumn === 'user' && sortDirection === 'desc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </template>
                            </span>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortTable('action')">
                        <div class="flex items-center">
                            <span>Action</span>
                            <span class="ml-1">
                                <template x-if="sortColumn === 'action' && sortDirection === 'asc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </template>
                                <template x-if="sortColumn === 'action' && sortDirection === 'desc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </template>
                            </span>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortTable('entity')">
                        <div class="flex items-center">
                            <span>Entity</span>
                            <span class="ml-1">
                                <template x-if="sortColumn === 'entity' && sortDirection === 'asc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </template>
                                <template x-if="sortColumn === 'entity' && sortDirection === 'desc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </template>
                            </span>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Changes
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortTable('time')">
                        <div class="flex items-center">
                            <span>Time</span>
                            <span class="ml-1">
                                <template x-if="sortColumn === 'time' && sortDirection === 'asc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </template>
                                <template x-if="sortColumn === 'time' && sortDirection === 'desc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </template>
                            </span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800" id="activity-log-tbody">
                @forelse ($logs as $log)
                    @php
                        $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
                        $action = $changesArray['action'] ?? 'updated';
                        $before = $changesArray['before'] ?? [];
                        $after  = $changesArray['after'] ?? [];
                        $entityType = $log->entity_type;
                        
                        // Define action colors
                        $actionColors = [
                            'created' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                            'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                            'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                        ];
                        $actionColor = $actionColors[$action] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
                        
                        // Data attributes for filtering and sorting
                        $dataAttributes = "data-user='" . ($log->user ? strtolower($log->user->first_name . ' ' . $log->user->last_name) : 'unknown') . "' ";
                        $dataAttributes .= "data-action='" . $action . "' ";
                        $dataAttributes .= "data-entity-type='" . $entityType . "' ";
                        $dataAttributes .= "data-time='" . $log->created_at->timestamp . "' ";
                        
                        // Entity name for data attribute
                        $entityName = '';
                        if ($entityType === 'task') {
                            $entityName = $after['task_name'] ?? $before['task_name'] ?? 'Unknown Task';
                        } elseif ($entityType === 'project') {
                            $entityName = $after['project_name'] ?? $before['project_name'] ?? 'Unknown Project';
                        } elseif ($entityType === 'task_comment') {
                            $entityName = isset($after['task_name']) ? 'Comment for ' . $after['task_name'] : 'Comment #' . $log->entity_id;
                        } else {
                            $entityName = ucfirst($entityType) . ' #' . $log->entity_id;
                        }
                        $dataAttributes .= "data-entity='" . strtolower($entityName) . "' ";
                    @endphp

                    {{-- TASK BLOCK --}}
                    @if($entityType === 'task')
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" {!! $dataAttributes !!}>
                            <!-- USER column with avatar placeholder -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        {{ $log->user ? substr($log->user->first_name, 0, 1) : '?' }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->user ? $log->user->role : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- ACTION column with badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                                    {{ ucfirst($action) }} Task
                                </span>
                            </td>

                            <!-- ENTITY column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $taskName = $after['task_name'] 
                                                ?? $before['task_name'] 
                                                ?? 'Unknown Task';
                                @endphp
                                <div class="text-sm text-gray-900 dark:text-white font-medium">Task #{{ $log->entity_id }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $taskName }}</div>
                            </td>

                            <!-- CHANGES column with improved styling -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @php
                                        // Exclude these fields from diff display
                                        $excluded = ['created_at','updated_at','deleted_at'];
                                    @endphp

                                    @if($action === 'created')
                                        @php
                                            $afterFiltered = array_diff_key($after, array_flip($excluded));
                                        @endphp
                                        @if(count($afterFiltered))
                                            <div class="space-y-1.5">
                                                @foreach($afterFiltered as $field => $value)
                                                    <div class="flex items-start">
                                                        <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $field)) }}:</span>
                                                        <span class="ml-2 text-xs text-green-600 dark:text-green-400">{{ $value ?: 'N/A' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No details available</span>
                                        @endif

                                    @elseif($action === 'updated')
                                        @php
                                            $beforeFiltered = array_diff_key($before, array_flip($excluded));
                                            $afterFiltered  = array_diff_key($after, array_flip($excluded));
                                            $diffs = [];
                                            foreach ($afterFiltered as $field => $newVal) {
                                                $oldVal = $beforeFiltered[$field] ?? null;
                                                // Compare trimmed strings to avoid whitespace issues
                                                if (trim((string)$oldVal) !== trim((string)$newVal)) {
                                                    $diffs[$field] = ['before' => $oldVal, 'after' => $newVal];
                                                }
                                            }
                                        @endphp
                                        @if(count($diffs))
                                            <div class="space-y-2">
                                                @foreach($diffs as $field => $vals)
                                                    <div>
                                                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ ucwords(str_replace('_', ' ', $field)) }}:</div>
                                                        <div class="flex flex-col space-y-1">
                                                            @if(!is_null($vals['before']))
                                                                <div class="flex items-center">
                                                                    <span class="inline-block w-4 h-4 rounded-full bg-red-100 dark:bg-red-900 flex-shrink-0 mr-1.5"></span>
                                                                    <span class="text-xs text-red-600 dark:text-red-400 line-through">{{ $vals['before'] ?: 'N/A' }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex items-center">
                                                                <span class="inline-block w-4 h-4 rounded-full bg-green-100 dark:bg-green-900 flex-shrink-0 mr-1.5"></span>
                                                                <span class="text-xs text-green-600 dark:text-green-400">{{ $vals['after'] ?: 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No key changes</span>
                                        @endif

                                    @elseif($action === 'deleted')
                                        @php
                                            // 'after' is empty, so show fields from 'before' in strikethrough
                                            $beforeFiltered = array_diff_key($before, array_flip($excluded));
                                        @endphp
                                        @if(count($beforeFiltered))
                                            <div class="space-y-1.5">
                                                @foreach($beforeFiltered as $field => $value)
                                                    <div class="flex items-start">
                                                        <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $field)) }}:</span>
                                                        <span class="ml-2 text-xs text-red-500 dark:text-red-400 line-through">{{ $value ?: 'N/A' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No details available</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">No changes available</span>
                                    @endif
                                </div>
                            </td>

                            <!-- TIME column with relative time -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <time datetime="{{ $log->created_at->toISOString() }}" class="text-xs">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                                    </time>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                    {{-- COMMENT BLOCK --}}
                    @elseif($entityType === 'task_comment')
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" {!! $dataAttributes !!}>
                            <!-- USER column with avatar placeholder -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        {{ $log->user ? substr($log->user->first_name, 0, 1) : '?' }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->user ? $log->user->role : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- ACTION column with badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                                    {{ ucfirst($action) }} Comment
                                </span>
                            </td>

                            <!-- ENTITY column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">Comment #{{ $log->entity_id }}</div>
                                @if(isset($after['task_name']))
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Task: {{ $after['task_name'] }}</div>
                                @endif
                            </td>

                            <!-- CHANGES column with improved styling -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if(isset($after['comment']))
                                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 text-xs">
                                            <div class="font-medium text-gray-500 dark:text-gray-400 mb-1">Comment:</div>
                                            <div class="text-gray-900 dark:text-white">{{ $after['comment'] }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">No comment content</span>
                                    @endif
                                    
                                    @if(isset($after['user_name']))
                                        <div class="mt-2 text-xs">
                                            <span class="font-medium text-gray-500 dark:text-gray-400">Comment By:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $after['user_name'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- TIME column with relative time -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <time datetime="{{ $log->created_at->toISOString() }}" class="text-xs">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                                    </time>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                    {{-- PROJECT BLOCK --}}
                    @elseif($entityType === 'project')
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" {!! $dataAttributes !!}>
                            <!-- USER column with avatar placeholder -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        {{ $log->user ? substr($log->user->first_name, 0, 1) : '?' }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->user ? $log->user->role : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- ACTION column with badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                                    {{ ucfirst($action) }} Project
                                </span>
                            </td>

                            <!-- ENTITY column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $projectName = $after['project_name'] 
                                                ?? $before['project_name'] 
                                                ?? 'Unknown Project';
                                @endphp
                                <div class="text-sm text-gray-900 dark:text-white font-medium">Project #{{ $log->entity_id }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $projectName }}</div>
                            </td>

                            <!-- CHANGES column with improved styling -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @php
                                        $excluded = ['created_at','updated_at','deleted_at'];

                                        if($action === 'created') {
                                            // For newly created projects, just show the "after" fields
                                            $afterFiltered = array_diff_key($after, array_flip($excluded));
                                        } elseif($action === 'updated') {
                                            // Compare "before" and "after" for updated
                                            $beforeFiltered = array_diff_key($before, array_flip($excluded));
                                            $afterFiltered  = array_diff_key($after, array_flip($excluded));
                                            $diffs = [];
                                            foreach ($afterFiltered as $field => $newVal) {
                                                $oldVal = $beforeFiltered[$field] ?? null;
                                                if (trim((string)$oldVal) !== trim((string)$newVal)) {
                                                    $diffs[$field] = ['before' => $oldVal, 'after' => $newVal];
                                                }
                                            }
                                        } elseif($action === 'deleted') {
                                            // For deleted projects, "after" is empty, so we show "before" in strikethrough
                                            $beforeFiltered = array_diff_key($before, array_flip($excluded));
                                        }
                                    @endphp

                                    @if($action === 'created')
                                        @if(count($afterFiltered))
                                            <div class="space-y-1.5">
                                                @foreach($afterFiltered as $field => $value)
                                                    <div class="flex items-start">
                                                        <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $field)) }}:</span>
                                                        <span class="ml-2 text-xs text-green-600 dark:text-green-400">{{ $value ?: 'N/A' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No details available</span>
                                        @endif

                                    @elseif($action === 'updated')
                                        @if(!empty($diffs))
                                            <div class="space-y-2">
                                                @foreach($diffs as $field => $vals)
                                                    <div>
                                                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ ucwords(str_replace('_', ' ', $field)) }}:</div>
                                                        <div class="flex flex-col space-y-1">
                                                            @if(!is_null($vals['before']))
                                                                <div class="flex items-center">
                                                                    <span class="inline-block w-4 h-4 rounded-full bg-red-100 dark:bg-red-900 flex-shrink-0 mr-1.5"></span>
                                                                    <span class="text-xs text-red-600 dark:text-red-400 line-through">{{ $vals['before'] ?: 'N/A' }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex items-center">
                                                                <span class="inline-block w-4 h-4 rounded-full bg-green-100 dark:bg-green-900 flex-shrink-0 mr-1.5"></span>
                                                                <span class="text-xs text-green-600 dark:text-green-400">{{ $vals['after'] ?: 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No key changes</span>
                                        @endif

                                    @elseif($action === 'deleted')
                                        @if(count($beforeFiltered))
                                            <div class="space-y-1.5">
                                                @foreach($beforeFiltered as $field => $value)
                                                    <div class="flex items-start">
                                                        <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $field)) }}:</span>
                                                        <span class="ml-2 text-xs text-red-500 dark:text-red-400 line-through">{{ $value ?: 'N/A' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No details available</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">No changes available</span>
                                    @endif
                                </div>
                            </td>

                            <!-- TIME column with relative time -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <time datetime="{{ $log->created_at->toISOString() }}" class="text-xs">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                                    </time>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                    {{-- DEFAULT BLOCK for other entity types --}}
                    @else
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" {!! $dataAttributes !!}>
                            <!-- USER column with avatar placeholder -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        {{ $log->user ? substr($log->user->first_name, 0, 1) : '?' }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->user ? $log->user->role : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- ACTION column with badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                                    {{ ucfirst($action) }} {{ ucfirst($entityType) }}
                                </span>
                            </td>

                            <!-- ENTITY column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ ucfirst($entityType) }} #{{ $log->entity_id }}
                                </div>
                            </td>

                            <!-- CHANGES column -->
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-400 dark:text-gray-500 italic">Details not available</span>
                            </td>

                            <!-- TIME column with relative time -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <time datetime="{{ $log->created_at->toISOString() }}" class="text-xs">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                                    </time>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">No activity logs found</p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Activity will appear here as users make changes</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination with improved styling -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center space-x-4">
        <div class="flex items-center">
            <label for="rows-per-page" class="text-sm text-gray-600 dark:text-gray-400 mr-3">Rows per page:</label>
            <select 
                id="rows-per-page"
                x-model="perPage" 
                @change="changePerPage()"
                class="w-16 px-2 py-1 text-sm border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
            >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span x-text="paginationInfo.from"></span> to <span x-text="paginationInfo.to"></span> of <span x-text="paginationInfo.total"></span> entries
        </div>
    </div>
    
    <div class="flex items-center space-x-2">
        <button 
            @click="previousPage()" 
            :disabled="currentPage === 1"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
            class="px-3 py-1 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
            Previous
        </button>
        
        <template x-for="page in pageNumbers" :key="page">
            <button 
                @click="goToPage(page)" 
                :class="{ 'bg-primary text-primary-foreground': currentPage === page }"
                class="px-3 py-1 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                x-text="page"
            ></button>
        </template>
        
        <button 
            @click="nextPage()" 
            :disabled="currentPage === totalPages"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
            class="px-3 py-1 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
            Next
        </button>
    </div>
</div>
</div>

<!-- Alpine.js script for datatable functionality -->
<script>
    function activityLogTable() {
        return {
            searchTerm: '',
            sortColumn: 'time',
            sortDirection: 'desc',
            currentPage: 1,
            perPage: 10,
            showFilters: false,
            filters: {
                action: '',
                entityType: '',
                dateRange: ''
            },
            allRows: [],
            filteredRows: [],
            paginatedRows: [],
            paginationInfo: {
                from: 0,
                to: 0,
                total: 0
            },
            
            init() {
                // Get all rows from the table
                this.allRows = Array.from(document.querySelectorAll('#activity-log-tbody tr'));
                this.filteredRows = [...this.allRows];
                
                // Initialize pagination
                this.updatePagination();
                
                // Initial sort
                this.sortTable('time', true);
            },
            
            filterTable() {
                // Reset to first page when filtering
                this.currentPage = 1;
                
                // Apply search term filter
                let filtered = this.allRows.filter(row => {
                    if (!this.searchTerm) return true;
                    
                    const searchText = this.searchTerm.toLowerCase();
                    const rowText = row.textContent.toLowerCase();
                    return rowText.includes(searchText);
                });
                
                // Apply action filter
                if (this.filters.action) {
                    filtered = filtered.filter(row => {
                        return row.getAttribute('data-action') === this.filters.action;
                    });
                }
                
                // Apply entity type filter
                if (this.filters.entityType) {
                    filtered = filtered.filter(row => {
                        return row.getAttribute('data-entity-type') === this.filters.entityType;
                    });
                }
                
                // Apply date range filter
                if (this.filters.dateRange) {
                    const now = new Date();
                    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() / 1000;
                    const yesterday = today - 86400;
                    const thisWeekStart = today - (now.getDay() * 86400);
                    const thisMonthStart = new Date(now.getFullYear(), now.getMonth(), 1).getTime() / 1000;
                    
                    filtered = filtered.filter(row => {
                        const timestamp = parseInt(row.getAttribute('data-time'));
                        
                        switch(this.filters.dateRange) {
                            case 'today':
                                return timestamp >= today;
                            case 'yesterday':
                                return timestamp >= yesterday && timestamp < today;
                            case 'week':
                                return timestamp >= thisWeekStart;
                            case 'month':
                                return timestamp >= thisMonthStart;
                            default:
                                return true;
                        }
                    });
                }
                
                this.filteredRows = filtered;
                this.updatePagination();
            },
            
            sortTable(column, init = false) {
                // If clicking the same column, toggle direction
                if (this.sortColumn === column && !init) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortColumn = column;
                    this.sortDirection = 'asc';
                }
                
                // Sort the filtered rows
                this.filteredRows.sort((a, b) => {
                    let valueA, valueB;
                    
                    switch(column) {
                        case 'user':
                            valueA = a.getAttribute('data-user');
                            valueB = b.getAttribute('data-user');
                            break;
                        case 'action':
                            valueA = a.getAttribute('data-action');
                            valueB = b.getAttribute('data-action');
                            break;
                        case 'entity':
                            valueA = a.getAttribute('data-entity');
                            valueB = b.getAttribute('data-entity');
                            break;
                        case 'time':
                            valueA = parseInt(a.getAttribute('data-time'));
                            valueB = parseInt(b.getAttribute('data-time'));
                            break;
                        default:
                            valueA = a.textContent.trim();
                            valueB = b.textContent.trim();
                    }
                    
                    // For numeric values
                    if (!isNaN(valueA) && !isNaN(valueB)) {
                        return this.sortDirection === 'asc' 
                            ? valueA - valueB 
                            : valueB - valueA;
                    }
                    
                    // For string values
                    return this.sortDirection === 'asc'
                        ? String(valueA).localeCompare(String(valueB))
                        : String(valueB).localeCompare(String(valueA));
                });
                
                this.updatePagination();
            },
            
            updatePagination() {
                const total = this.filteredRows.length;
                const start = (this.currentPage - 1) * this.perPage;
                const end = Math.min(start + this.perPage, total);
                
                this.paginationInfo = {
                    from: total > 0 ? start + 1 : 0,
                    to: end,
                    total: total
                };
                
                // Get the paginated subset of rows
                this.paginatedRows = this.filteredRows.slice(start, end);
                
                // Update the DOM - hide all rows first
                this.allRows.forEach(row => {
                    row.style.display = 'none';
                });
                
                // Show only the paginated rows
                this.paginatedRows.forEach(row => {
                    row.style.display = '';
                });
            },
            
            get totalPages() {
                return Math.max(1, Math.ceil(this.filteredRows.length / this.perPage));
            },
            
            get pageNumbers() {
                const pages = [];
                const maxPages = 5; // Show max 5 page numbers
                
                if (this.totalPages <= maxPages) {
                    // If we have 5 or fewer pages, show all
                    for (let i = 1; i <= this.totalPages; i++) {
                        pages.push(i);
                    }
                } else {
                    // Always include first page
                    pages.push(1);
                    
                    // Calculate start and end of page numbers around current page
                    let start = Math.max(2, this.currentPage - 1);
                    let end = Math.min(this.totalPages - 1, this.currentPage + 1);
                    
                    // Adjust if we're at the beginning or end
                    if (this.currentPage <= 2) {
                        end = 4;
                    } else if (this.currentPage >= this.totalPages - 1) {
                        start = this.totalPages - 3;
                    }
                    
                    // Add ellipsis if needed
                    if (start > 2) {
                        pages.push('...');
                    }
                    
                    // Add page numbers
                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    
                    // Add ellipsis if needed
                    if (end < this.totalPages - 1) {
                        pages.push('...');
                    }
                    
                    // Always include last page
                    pages.push(this.totalPages);
                }
                
                return pages;
            },
            
            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.updatePagination();
                }
            },
            
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.updatePagination();
                }
            },
            
            goToPage(page) {
                if (page !== '...') {
                    this.currentPage = page;
                    this.updatePagination();
                }
            },
            
            changePerPage() {
                this.currentPage = 1;
                this.updatePagination();
            },
            
            resetFilters() {
                this.searchTerm = '';
                this.filters = {
                    action: '',
                    entityType: '',
                    dateRange: ''
                };
                this.filterTable();
            }
        };
    }
</script>



