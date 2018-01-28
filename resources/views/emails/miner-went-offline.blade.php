@component('mail::message')
# Miner went offline

Hi {{ $miner->user->nick }},

your miner *{{ $miner->address }}{{ $miner->note ? ' (' . $miner->note . ')' : '' }}* went offline at {{ $miner->updated_at->format('Y-m-d H:i:s') }} GMT. You may want to check your miners.

@component('mail::button', ['url' => 'https://xdagpool.com/user/miners'])
Check my miners
@endcomponent

{{ config('app.name') }}
@endcomponent
