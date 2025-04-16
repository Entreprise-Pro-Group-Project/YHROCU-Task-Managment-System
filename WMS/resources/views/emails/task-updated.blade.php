@component('mail::message')
# Task Updated: {{ $task->task_name }}

Dear {{ $notifiable->first_name }},

A task assigned to you has been updated with the following details:

**Task Name:** {{ $task->task_name }}
**Status:** {{ $task->status }}
**Due Date:** {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}

@if($task->comment)
**Latest Comment:** {{ $task->comment }}
@endif

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
View Task Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent