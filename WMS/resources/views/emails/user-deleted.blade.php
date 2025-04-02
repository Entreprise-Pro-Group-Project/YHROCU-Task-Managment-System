@component('mail::message')
# Your Account Has Been Removed

Hello {{ $user->first_name }} {{ $user->last_name }},

This email is to inform you that your account in the {{ config('app.name') }} has been removed by an administrator.

We want to thank you for your contributions and service. We appreciate your dedication and wish you the very best in your future endeavors.

If you believe this action was taken in error, please contact your administrator.

Best regards and good luck with your future career!

Sincerely,<br>
{{ config('app.name') }} 
@endcomponent 