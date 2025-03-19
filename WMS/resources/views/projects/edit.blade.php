@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Edit Project</h2>
        <form method="POST" action="{{ route('projects.update', $project->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="project_name" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input type="text" name="project_name" id="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->project_name }}" required>
            </div>
            <div class="mb-4">
                <label for="project_description" class="block text-sm font-medium text-gray-700">Project Description</label>
                <textarea name="project_description" id="project_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ $project->project_description }}</textarea>
            </div>
            <div class="mb-4">
                <label for="project_date" class="block text-sm font-medium text-gray-700">Project Date</label>
                <input type="date" name="project_date" id="project_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->project_date }}" required>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->due_date }}" required>
            </div>
            <div class="mb-4">
                <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                <input type="text" name="supervisor_name" id="supervisor_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $project->supervisor_name }}" required>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Update Project
            </button>
        </form>
        <a href="/admin/dashboard" class="inline-block bg-blue-400 text-white py-2 px-4 rounded hover:bg-blue-500 mt-4">
            Back
        </a>
    </div>
@endsection