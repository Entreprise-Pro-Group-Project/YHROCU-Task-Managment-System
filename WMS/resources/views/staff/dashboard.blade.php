<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  @vite(['resources/css/app.css'])
  <style>
    /* Custom animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .task-card {
      animation: fadeIn 0.3s ease-out;
      transition: all 0.2s ease;
    }
    .task-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .status-badge {
      transition: all 0.2s ease;
    }
    .priority-indicator {
      width: 4px;
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      border-radius: 4px 0 0 4px;
    }
    /* Add dropdown animation */
    .dropdown-appear {
      animation: dropdownFade 0.2s ease-out;
    }
    @keyframes dropdownFade {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  @include('layouts.navigation')

  <main class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">My Tasks</h1>
        <p class="text-gray-600 mt-2">Manage and update your assigned tasks</p>
      </div>
      
      <!-- Admin Button (if needed) -->
      @if(auth()->user()->is_admin ?? false)
      <div class="relative">
        <button
          id="adminButton"
          class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2 font-medium"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span>Admin</span>
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <!-- Admin Dropdown -->
        <div id="adminDropdown" class="hidden absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-20 overflow-hidden">
          <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-gray-700 hover:bg-purple-50 border-b border-gray-100">Dashboard</a>
          <a href="{{ route('admin.users') }}" class="block px-4 py-3 text-gray-700 hover:bg-purple-50 border-b border-gray-100">Manage Users</a>
          <a href="{{ route('admin.tasks') }}" class="block px-4 py-3 text-gray-700 hover:bg-purple-50">Manage Tasks</a>
        </div>
      </div>
      @endif
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm p-5 mb-8">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <!-- Filter Dropdown -->
        <div class="relative">
          <button
            id="filterButton"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2 font-medium"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span>Filter Tasks</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Filter Dropdown -->
          <div id="filterDropdown" class="hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-20 overflow-hidden">
            <a href="{{ route('staff.dashboard', ['status' => 'all']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">All Tasks</a>
            <a href="{{ route('staff.dashboard', ['status' => 'assigned']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">Assigned</a>
            <a href="{{ route('staff.dashboard', ['status' => 'in progress']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">In Progress</a>
            <a href="{{ route('staff.dashboard', ['status' => 'completed']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 border-b border-gray-100">Completed</a>
            <a href="{{ route('staff.dashboard', ['status' => 'overdue']) }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50">Over Due</a>
          </div>
        </div>

        <!-- Search Input -->
        <div class="relative w-full md:w-auto md:min-w-[300px]">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            type="text"
            id="searchInput"
            placeholder="Search tasks..."
            class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>
    </div>

    <!-- Tasks Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="tasksGrid">
      @if($tasks->isEmpty())
        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
          <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          <h3 class="text-xl font-medium text-gray-700">No Tasks Assigned</h3>
          <p class="text-gray-500 mt-2">You don't have any tasks assigned to you at the moment.</p>
        </div>
      @else
        @foreach ($tasks as $task)
          @php
            // Determine status color and badge style
            $statusColor = 'bg-gray-100 text-gray-800';
            $priorityColor = 'bg-gray-300';
            $status = strtolower($task->status);
            $isOverdue = \Carbon\Carbon::parse($task->due_date) < \Carbon\Carbon::now() && $status !== 'completed';
            
            if ($status === 'completed') {
              $statusColor = 'bg-green-100 text-green-800';
              $priorityColor = 'bg-green-500';
            } elseif ($status === 'in progress') {
              $statusColor = 'bg-blue-100 text-blue-800';
              $priorityColor = 'bg-blue-500';
            } elseif ($status === 'assigned') {
              $statusColor = 'bg-yellow-100 text-yellow-800';
              $priorityColor = 'bg-yellow-500';
            }
            
            if ($isOverdue) {
              $statusColor = 'bg-red-100 text-red-800';
              $priorityColor = 'bg-red-500';
            }
            
            // Format due date
            $dueDate = \Carbon\Carbon::parse($task->due_date);
            $formattedDate = $dueDate->format('M d, Y');
            $daysLeft = (int)$dueDate->diffInDays(\Carbon\Carbon::now(), false);
          @endphp
          
          <div class="task-card bg-white rounded-xl shadow-sm overflow-hidden relative" data-task-name="{{ strtolower($task->task_name) }}">
            <!-- Priority Indicator -->
            <div class="priority-indicator {{ $priorityColor }}"></div>
            
            <!-- Card Content -->
            <div class="p-5 pl-6">
              <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-gray-800 pr-2">{{ $task->task_name }}</h3>
                <span class="status-badge px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                  {{ ucfirst($task->status) }}
                </span>
              </div>
              
              <!-- Due Date -->
              <div class="flex items-center mb-4 text-sm">
                <svg class="h-4 w-4 mr-2 {{ $isOverdue ? 'text-red-500' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="{{ $isOverdue ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                  {{ $formattedDate }}
                  @if($isOverdue)
                    <span class="text-red-500 font-medium">
                      ({{ abs($daysLeft) }} {{ abs($daysLeft) == 1 ? 'day' : 'days' }} overdue)
                    </span>
                  @elseif($daysLeft < 0)
                    <span class="text-yellow-600">
                      ({{ abs($daysLeft) }} {{ abs($daysLeft) == 1 ? 'day' : 'days' }} left)
                    </span>
                  @endif
                </span>
              </div>
              
              <!-- Status Update Form (Auto-submit on change) -->
              <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="mb-4 auto-submit-form">
                @csrf
                @method('PUT')
                
                <!-- Status Dropdown Only -->
                <div class="flex flex-col">
                  <label for="status-{{ $task->id }}" class="block text-sm font-medium text-gray-700 mb-1">Update Status:</label>
                  <div class="relative">
                    <select name="status" id="status-{{ $task->id }}" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                      @if($isOverdue && $task->status !== 'completed')
                        <option value="over due" selected>Over Due</option>
                        <option value="in progress">Mark as In Progress</option>
                        <option value="completed">Mark as Completed</option>
                      @else
                        <option value="assigned" {{ $task->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                      @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                      <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>
                </div>
                <!-- Note: The update button is removed. The form will auto-submit when the selection changes. -->
              </form>
              
              <!-- Action Button: View Details (where the comment section is located) -->
              <div class="flex justify-center py-2"> 
                <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  View Details
                </a>
              </div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Admin dropdown
      const adminButton = document.getElementById('adminButton');
      const adminDropdown = document.getElementById('adminDropdown');
      
      // Filter dropdown
      const filterButton = document.getElementById('filterButton');
      const filterDropdown = document.getElementById('filterDropdown');

      // Function to toggle dropdown with animation
      function toggleDropdown(dropdown) {
        if (dropdown.classList.contains('hidden')) {
          dropdown.classList.remove('hidden');
          dropdown.classList.add('dropdown-appear');
        } else {
          dropdown.classList.add('hidden');
        }
      }
      
      // Function to close all dropdowns
      function closeAllDropdowns() {
        if (adminDropdown) adminDropdown.classList.add('hidden');
        if (filterDropdown) filterDropdown.classList.add('hidden');
      }

      if (adminButton && adminDropdown) {
        adminButton.addEventListener('click', function (e) {
          e.stopPropagation(); 
          // Close filter dropdown if open
          if (filterDropdown) filterDropdown.classList.add('hidden');
          // Toggle admin dropdown visibility
          toggleDropdown(adminDropdown);
        });
      }

      if (filterButton && filterDropdown) {
        filterButton.addEventListener('click', function (e) {
          e.stopPropagation();
          // Close admin dropdown if open
          if (adminDropdown) adminDropdown.classList.add('hidden');
          // Toggle filter dropdown visibility
          toggleDropdown(filterDropdown);
        });
      }

      // Hide both dropdowns when clicking anywhere else
      document.addEventListener('click', closeAllDropdowns);
      
      // Auto-submit status changes
      document.querySelectorAll('.auto-submit-form select').forEach(select => {
        select.addEventListener('change', function() {
          this.closest('form').submit();
        });
      });
      
      // Search functionality
      const searchInput = document.getElementById('searchInput');
      const taskCards = document.querySelectorAll('.task-card');
      
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const searchTerm = this.value.toLowerCase().trim();
          let hasVisibleCards = false;
          
          taskCards.forEach(card => {
            const taskName = card.getAttribute('data-task-name');
            if (taskName.includes(searchTerm)) {
              card.classList.remove('hidden');
              hasVisibleCards = true;
            } else {
              card.classList.add('hidden');
            }
          });
          
          // Show "no results" message if needed
          const noResultsElement = document.getElementById('noSearchResults');
          const tasksGrid = document.getElementById('tasksGrid');
          
          if (!hasVisibleCards && searchTerm !== '') {
            // Create "no results" message if it doesn't exist
            if (!noResultsElement) {
              const noResults = document.createElement('div');
              noResults.id = 'noSearchResults';
              noResults.className = 'col-span-full text-center py-10';
              noResults.innerHTML = `
                <svg class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-gray-500">No tasks matching "<span id="searchTermDisplay"></span>"</p>
              `;
              tasksGrid.appendChild(noResults);
              document.getElementById('searchTermDisplay').textContent = searchTerm;
            } else {
              noResultsElement.classList.remove('hidden');
              document.getElementById('searchTermDisplay').textContent = searchTerm;
            }
          } else if (noResultsElement) {
            noResultsElement.classList.add('hidden');
          }
        });
      }
      
      // Character counter for comment fields
      document.querySelectorAll('[id^="comment-"]').forEach(textarea => {
        const taskId = textarea.id.split('-')[1];
        const charCount = document.getElementById(`charCount-${taskId}`);
        
        if (charCount) {
          // Update character count on load
          charCount.textContent = textarea.value.length;
          
          // Update character count on input
          textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            
            // Visual feedback for character limit
            if (this.value.length > 180) {
              charCount.classList.add('text-yellow-500');
              charCount.classList.remove('text-red-500', 'text-gray-400');
            } else if (this.value.length > 200) {
              charCount.classList.add('text-red-500');
              charCount.classList.remove('text-yellow-500', 'text-gray-400');
            } else {
              charCount.classList.add('text-gray-400');
              charCount.classList.remove('text-yellow-500', 'text-red-500');
            }
          });
        }
      });
      
      // Comment suggestions
      document.querySelectorAll('.comment-suggestion').forEach(button => {
        button.addEventListener('click', function() {
          // Find the closest textarea
          const form = this.closest('form');
          const textarea = form.querySelector('textarea[name="comment"]');
          const charCount = form.querySelector('[id^="charCount-"]');
          
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
  @vite(['resources/js/app.js'])
</body>
</html>