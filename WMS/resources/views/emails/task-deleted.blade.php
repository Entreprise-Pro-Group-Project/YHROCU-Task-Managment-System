@component('mail::message')
# Task Deleted

The task **{{ $task->task_name }}** has been deleted.

If you believe this was a mistake, please contact support.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
