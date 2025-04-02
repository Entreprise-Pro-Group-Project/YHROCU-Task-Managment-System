@component('mail::message')
# Your Account Has Been Updated

Hello {{ $user->first_name }} {{ $user->last_name }},

An administrator has updated your account information in the {{ config('app.name') }} .

## Updated Information:
@foreach($changedFields as $field => $value)
**{{ ucfirst(str_replace('_', ' ', $field)) }}:** {{ $value }}
@endforeach

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent 