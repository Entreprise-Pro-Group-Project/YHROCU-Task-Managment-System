@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Task Details</h2>
        <p><strong>Task Name:</strong> {{ $task->task_name }}</p>
        <p><strong>Assigned Staff:</strong> {{ $task->assigned_staff }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
        <a href="/admin/dashboard" class="inline-block bg-blue-400 text-white py-2 px-4 rounded hover:bg-blue-500 mt-4">
            Back
        </a>
    </div>
@endsection