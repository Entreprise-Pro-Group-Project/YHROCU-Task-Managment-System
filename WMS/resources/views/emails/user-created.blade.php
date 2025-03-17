@component('mail::message')
# Your Account Has Been Created

Hello {{ $user->first_name }} {{ $user->last_name }},

An administrator has created an account for you in the {{ config('app.name') }}.

## Your Login Credentials
**Username:** {{ $user->username }}  
**Email:** {{ $user->email }}  
**Password:** {{ $plainPassword }}  
**Role:** {{ ucfirst($user->role) }}

Please log in using your email address and password.

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent 