@component('mail::message')
# Project Deleted

Hello {{ $notifiable->first_name }},

We wanted to let you know that your project **{{ $project->project_name }}** has been deleted.

If you believe this was a mistake or need further assistance, please contact support.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
