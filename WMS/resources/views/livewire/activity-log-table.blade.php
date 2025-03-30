<div>
  <div class="bg-white dark:bg-gray-900 shadow rounded-lg overflow-hidden">
    <!-- Header Section -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-800 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-900">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <!-- Project Title -->
        <div class="order-2 md:order-1 text-center md:text-left">
          <div class="flex items-center mb-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ $projectName ?? 'Unknown Project' }}
            </h1>
            <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
              Activity Log
            </span>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Last updated {{ now()->diffForHumans() }}
          </p>
        </div>

        <!-- Search & Actions -->
        <div class="order-1 md:order-2 flex flex-col md:flex-row items-center justify-center md:justify-end gap-4">
          <!-- Search Input -->
          <div class="w-full md:w-auto">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input 
                type="text" 
                placeholder="Search logs..." 
                wire:model.live.debounce.300ms="search"
                class="w-full md:w-64 pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
              >
              @if($search)
              <button 
                wire:click="resetSearch" 
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                title="Clear search"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              @endif
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-3">
            <!-- Refresh Button -->
            <button 
              wire:click="refresh" 
              wire:loading.attr="disabled"
              class="inline-flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <span>Refresh</span>
              <div wire:loading wire:target="refresh" class="ml-1.5">
                <svg class="animate-spin h-4 w-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
              </div>
            </button>

            <!-- Export Dropdown -->
            <div class="relative" x-data="{ open: false }">
              <button 
                @click="open = !open" 
                class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <div 
                x-cloak
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10 divide-y divide-gray-100 dark:divide-gray-700"
                style="display: none;"
              >
                <div class="py-1">
                  <a href="{{ route('logs.export.csv', ['project' => $projectId]) }}" class="group flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 mr-3 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-medium">CSV Format</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">Download as spreadsheet</p>
                    </div>
                  </a>
                  <a href="{{ route('logs.export.pdf', ['project' => $projectId]) }}" class="group flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 mr-3 group-hover:bg-red-200 dark:group-hover:bg-red-800 transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-medium">PDF Format</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">Download as document</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <!-- End Export Dropdown -->
          </div>
          <!-- End Action Buttons -->
        </div>
      </div>

      <!-- Active Search Bar -->
      @if(!empty($search))
      <div class="mt-4 px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 mr-2">Search results for:</span>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
          <span class="font-semibold">{{ $search }}</span>
          <button wire:click="resetSearch" class="ml-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
          </button>
        </span>
        <span class="ml-auto text-xs text-gray-500 dark:text-gray-400">
          {{ $logs->total() }} results found
        </span>
      </div>
      @endif
    </div>
    <!-- End Header Section -->

    <!-- Table Section -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('user_id')">
              <div class="flex items-center">
                <span>User</span>
                <span class="ml-1">
                  @if($sortField === 'user_id')
                    @if($sortDirection === 'asc')
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                      </svg>
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    @endif
                  @endif
                </span>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('changes')">
              <div class="flex items-center">
                <span>Action</span>
                <span class="ml-1">
                  @if($sortField === 'changes')
                    @if($sortDirection === 'asc')
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                      </svg>
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    @endif
                  @endif
                </span>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('entity_type')">
              <div class="flex items-center">
                <span>Entity</span>
                <span class="ml-1">
                  @if($sortField === 'entity_type')
                    @if($sortDirection === 'asc')
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                      </svg>
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    @endif
                  @endif
                </span>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Changes
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
              <div class="flex items-center">
                <span>Time</span>
                <span class="ml-1">
                  @if($sortField === 'created_at')
                    @if($sortDirection === 'asc')
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                      </svg>
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    @endif
                  @endif
                </span>
              </div>
            </th>
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
                <!-- USER column -->
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
                <!-- ACTION column -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                    {{ ucfirst($action) }} Task
                  </span>
                </td>
                <!-- ENTITY column -->
                <td class="px-6 py-4 whitespace-nowrap">
                  @php
                    $taskName = $after['task_name'] ?? $before['task_name'] ?? 'Unknown Task';
                  @endphp
                  <div class="text-sm text-gray-900 dark:text-white font-medium">Task #{{ $log->entity_id }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ $taskName }}</div>
                </td>
                <!-- CHANGES column -->
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">
                    @php $excluded = ['created_at','updated_at','deleted_at']; @endphp
                    @if($action === 'created')
                      @php $afterFiltered = array_diff_key($after, array_flip($excluded)); @endphp
                      @if(count($afterFiltered))
                        <div class="space-y-1.5">
                          @foreach($afterFiltered as $field => $value)
                            <div class="flex items-start">
                              <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                              </span>
                              <span class="ml-2 text-xs text-green-600 dark:text-green-400">
                                {{ $value ?: 'N/A' }}
                              </span>
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
                          if (trim((string)$oldVal) !== trim((string)$newVal)) {
                            $diffs[$field] = ['before' => $oldVal, 'after' => $newVal];
                          }
                        }
                      @endphp
                      @if(count($diffs))
                        <div class="space-y-2">
                          @foreach($diffs as $field => $vals)
                            <div>
                              <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                              </div>
                              <div class="flex flex-col space-y-1">
                                @if(!is_null($vals['before']))
                                  <div class="flex items-center">
                                    <span class="inline-block w-4 h-4 rounded-full bg-red-100 dark:bg-red-900 flex-shrink-0 mr-1.5"></span>
                                    <span class="text-xs text-red-600 dark:text-red-400 line-through">
                                      {{ $vals['before'] ?: 'N/A' }}
                                    </span>
                                  </div>
                                @endif
                                <div class="flex items-center">
                                  <span class="inline-block w-4 h-4 rounded-full bg-green-100 dark:bg-green-900 flex-shrink-0 mr-1.5"></span>
                                  <span class="text-xs text-green-600 dark:text-green-400">
                                    {{ $vals['after'] ?: 'N/A' }}
                                  </span>
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      @else
                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">No key changes</span>
                      @endif

                    @elseif($action === 'deleted')
                      @php $beforeFiltered = array_diff_key($before, array_flip($excluded)); @endphp
                      @if(count($beforeFiltered))
                        <div class="space-y-1.5">
                          @foreach($beforeFiltered as $field => $value)
                            <div class="flex items-start">
                              <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                              </span>
                              <span class="ml-2 text-xs text-red-500 dark:text-red-400 line-through">
                                {{ $value ?: 'N/A' }}
                              </span>
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
                <!-- TIME column -->
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
                <!-- USER column -->
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
                <!-- ACTION column -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                    {{ ucfirst($action) }} Comment
                  </span>
                </td>
                <!-- ENTITY column -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white font-medium">
                    Comment #{{ $log->entity_id }}
                  </div>
                  @if(isset($after['task_name']))
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      Task: {{ $after['task_name'] }}
                    </div>
                  @endif
                </td>
                <!-- CHANGES column -->
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
                <!-- TIME column -->
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
                <!-- USER column -->
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
                <!-- ACTION column -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                    {{ ucfirst($action) }} Project
                  </span>
                </td>
                <!-- ENTITY column -->
                <td class="px-6 py-4 whitespace-nowrap">
                  @php
                    $projectName = $after['project_name'] ?? $before['project_name'] ?? 'Unknown Project';
                  @endphp
                  <div class="text-sm text-gray-900 dark:text-white font-medium">Project #{{ $log->entity_id }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ $projectName }}</div>
                </td>
                <!-- CHANGES column -->
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">
                    @php
                      $excluded = ['created_at','updated_at','deleted_at'];
                      if($action === 'created') {
                        $afterFiltered = array_diff_key($after, array_flip($excluded));
                      } elseif($action === 'updated') {
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
                        $beforeFiltered = array_diff_key($before, array_flip($excluded));
                      }
                    @endphp

                    @if($action === 'created')
                      @if(count($afterFiltered))
                        <div class="space-y-1.5">
                          @foreach($afterFiltered as $field => $value)
                            <div class="flex items-start">
                              <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                              </span>
                              <span class="ml-2 text-xs text-green-600 dark:text-green-400">
                                {{ $value ?: 'N/A' }}
                              </span>
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
                              <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                              </div>
                              <div class="flex flex-col space-y-1">
                                @if(!is_null($vals['before']))
                                  <div class="flex items-center">
                                    <span class="inline-block w-4 h-4 rounded-full bg-red-100 dark:bg-red-900 flex-shrink-0 mr-1.5"></span>
                                    <span class="text-xs text-red-600 dark:text-red-400 line-through">
                                      {{ $vals['before'] ?: 'N/A' }}
                                    </span>
                                  </div>
                                @endif
                                <div class="flex items-center">
                                  <span class="inline-block w-4 h-4 rounded-full bg-green-100 dark:bg-green-900 flex-shrink-0 mr-1.5"></span>
                                  <span class="text-xs text-green-600 dark:text-green-400">
                                    {{ $vals['after'] ?: 'N/A' }}
                                  </span>
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
                              <span class="inline-block min-w-24 text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                              </span>
                              <span class="ml-2 text-xs text-red-500 dark:text-red-400 line-through">
                                {{ $value ?: 'N/A' }}
                              </span>
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
                <!-- TIME column -->
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
                <!-- USER column -->
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
                <!-- ACTION column -->
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
                <!-- TIME column -->
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
    <!-- End Table Section -->

<!-- Pagination -->
<div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
  <div class="flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0 sm:space-x-4">
    <!-- Rows Per Page -->
    <div class="flex items-center space-x-2">
      <label for="rows-per-page" class="text-sm font-medium text-gray-700 dark:text-gray-400">
        Rows per page:
      </label>
      <select
        id="rows-per-page"
        wire:model.live="perPage"
        class="w-16 px-2 py-1 text-sm border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
      >
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>

    <!-- Pagination Links -->
    <div class="pagination-livewire">
    {{ $logs->links('vendor.pagination.tailwind') }}


    </div>
  </div>
</div>



    <!-- Updated Pagination Styles -->
    <style>
      .pagination-livewire nav ul {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
      }
      .pagination-livewire nav ul li {
        margin: 0 0.25rem;
      }
      .pagination-livewire nav ul li a,
      .pagination-livewire nav ul li span {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        background-color: #ffffff;
        color: #374151;
        font-weight: 500;
        transition: background-color 0.2s;
      }
      .pagination-livewire nav ul li a:hover {
        background-color: #f3f4f6;
      }
      .pagination-livewire nav ul li span {
        cursor: default;
      }
      .pagination-livewire nav ul li span[aria-current="page"] {
        background-color: #3b82f6;
        color: #ffffff;
        border-color: #3b82f6;
      }
      @media (prefers-color-scheme: dark) {
        .pagination-livewire nav ul li a,
        .pagination-livewire nav ul li span {
          background-color: #1f2937;
          border-color: #374151;
          color: #e5e7eb;
        }
        .pagination-livewire nav ul li a:hover {
          background-color: #374151;
        }
        .pagination-livewire nav ul li span[aria-current="page"] {
          background-color: #3b82f6;
          color: #ffffff;
        }
      }
    </style>
    <!-- End Pagination -->
  </div>
</div>
