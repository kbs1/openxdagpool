@component('mail::message')
# {{ $subject }}

Hi {{ $user->nick }},

{!! nl2br(e($message)) !!}

@component('mail::button', ['url' => 'http://' . $website_domain])
Visit the pool
@endcomponent

{{ $pool_name }}
@endcomponent
