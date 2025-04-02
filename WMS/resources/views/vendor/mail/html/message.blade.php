@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => url('/login')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} YHROCU TASK MANAGEMENT SYSTEM. All rights reserved.
@endcomponent
@endslot
@endcomponent
