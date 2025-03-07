<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  @vite(['resources/css/app.css'])
</head>
<body>
  @include('layouts.navigation')

  <main class="p-6">
    <!-- Search Section -->
    <div class="flex items-center justify-end space-x-2 mb-6">
      <span class="font-bold text-[#0284c7]">Search:</span>
      <input type="text" placeholder="Search" class="border rounded px-4 py-2 focus:outline-none" />
    </div>

    <!-- Tasks Section -->
    <div class="container mx-auto">
      <h2 class="text-2xl font-bold mb-4">My Tasks</h2>
      
      @if($tasks->isEmpty())
        <p>No tasks assigned.</p>
      @else
        @foreach ($tasks as $task)
          <div class="mb-6 p-4 border rounded">
            <div class="flex justify-between items-center">
              <p><strong>Task:</strong> {{ $task->task_name }}</p>
              <div class="flex space-x-2">
                <a href="{{ route('tasks.show', $task->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                  View
                </a>
                
              </div>
            </div>
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
          </div>
        @endforeach
      @endif
    </div>
  </main>

  @vite(['resources/js/app.js'])
</body>
</html>
