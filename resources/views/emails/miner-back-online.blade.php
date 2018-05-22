@component('mail::message')
# Miner back online

Hi {{ $miner->user->nick }},

Great news! Your miner *{{ $miner->address }}{{ $miner->note ? ' (' . $miner->note . ')' : '' }}* appeared back online at {{ $miner->updated_at->format('Y-m-d H:i:s') }} GMT.

@component('mail::button', ['url' => 'http://' . $website_domain . '/user/miners'])
View my miners
@endcomponent

{{ $pool_name }}
@endcomponent
