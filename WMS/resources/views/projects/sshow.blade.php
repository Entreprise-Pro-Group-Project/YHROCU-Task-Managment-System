@extends('layouts.sapp')

@section('content')
    <div class="container mx-auto p-4">
    @livewire('activity-log-table', ['projectId' => $project->id])
    <div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Project Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div>
                <p class="font-semibold">Project Name:</p>
                <p>{{ $project->project_name }}</p>
            </div>

            <div>
                <p class="font-semibold">Supervisor:</p>
                <p>{{ $project->supervisor_name }}</p>
            </div>

            <div>
                <p class="font-semibold">Start Date:</p>
                <p>{{ $project->project_date }}</p>
            </div>

            <div>
                <p class="font-semibold">Due Date:</p>
                <p>{{ $project->due_date }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="font-semibold">Project Description:</p>
                <p>{{ $project->project_description }}</p>
            </div>
        </div>

        <div class="mt-6 text-right">
            <a href="/supervisor/dashboard" class="inline-block bg-blue-500 text-white py-2 px-6 rounded hover:bg-blue-600 transition">
                Back
            </a>
        </div>
    </div>
</div>
@endsection