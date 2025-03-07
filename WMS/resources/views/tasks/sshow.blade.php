@extends('layouts.sapp')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Task Details</h2>
        <p><strong>Task Name:</strong> {{ $task->task_name }}</p>
        <p><strong>Assigned Staff:</strong> {{ $task->assigned_staff }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
    </div>
@endsection