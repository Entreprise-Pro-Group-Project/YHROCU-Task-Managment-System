@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Project Details</h2>
        <p><strong>Project Name:</strong> {{ $project->project_name }}</p>
        <p><strong>Start Date:</strong> {{ $project->project_date }}</p>
        <p><strong>Due Date:</strong> {{ $project->due_date }}</p>
        <p><strong>Supervisor:</strong> {{ $project->supervisor_name }}</p>
    </div>
@endsection