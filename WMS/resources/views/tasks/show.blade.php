@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Task Details</h2>
        <p><strong>Task Name:</strong> {{ $task->task_name }}</p>
        <p><strong>Task Description:</strong> {{ $task->task_description }}</p>
        <p><strong>Assigned Staff:</strong> {{ $task->assigned_staff }}</p>
        <p><strong>Assigned Date:</strong> {{ $task->assigned_date }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
                <!-- show comments -->
        <p><strong>Comments:</strong> {{ $task->comment }}</p>


        <!-- Add a back button to go back to the dashboard -->
        <a href="/admin/dashboard" class="inline-block bg-blue-400 text-white py-2 px-4 rounded hover:bg-blue-500 mt-4">
            Back
        </a>
    </div>
@endsection