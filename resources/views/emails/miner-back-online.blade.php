@component('mail::message')
# Miner back online

Hi {{ $miner->user->display_nick }},

Great news! Your miner *{{ $miner->address }}{{ $miner->note ? ' (' . $miner->note . ')' : '' }}* appeared back online at {{ $miner->updated_at->format('Y-m-d H:i:s') }} GMT.

@component('mail::button', ['url' => 'https://xdagpool.com/user/miners'])
View my miners
@endcomponent

{{ config('app.name') }}
@endcomponent
