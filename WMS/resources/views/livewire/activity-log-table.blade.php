<div class="bg-white dark:bg-gray-900 shadow rounded-lg overflow-hidden">
    <!-- Header Section with improved layout -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-800">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Project Title (Centered on mobile, left-aligned on desktop) -->
            <div class="order-2 md:order-1 text-center md:text-left">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $projectName ?? 'Unknown Project' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Activity History</p>
            </div>
            
            <!-- Export Actions -->
            <div class="order-1 md:order-2 flex items-center justify-center md:justify-end gap-3">
                <a href="{{ route('logs.export.csv', ['project' => $projectId]) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-primary text-primary-foreground hover:bg-primary/90 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('logs.export.pdf', ['project' => $projectId]) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-primary text-primary-foreground hover:bg-primary/90 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Table Section with improved styling -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Entity</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Changes</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
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
                    @endphp

                    {{-- TASK BLOCK --}}
                    @if($entityType === 'task')
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24"  class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $logs->links() }}
    </div>
</div>