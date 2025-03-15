@component('mail::message')
# New Task Assigned

Hello {{ $user->name }},

A new task has been assigned to you.

**Task:** {{ $task->task_name }}  
**Due Date:** {{ $task->due_date }}

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
View Task
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
