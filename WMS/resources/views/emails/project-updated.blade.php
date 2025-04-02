@component('mail::message')
# Hello {{ $notifiable->first_name }},

Your project **{{ $project->project_name }}** has been updated.

**Project Date:** {{ $project->project_date }}  
**Due Date:** {{ $project->due_date }}

@component('mail::button', ['url' => url('/projects/' . $project->id)])
View Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
