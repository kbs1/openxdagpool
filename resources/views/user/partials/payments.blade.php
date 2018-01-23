<table class="table is-fullwidth">
	<thead>
		<tr>
			<th>Date and time</th>
			<th>Sender</th>
			<th>Recipient</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@forelse ($payments as $payment)
			<tr>
				<td class="tooltip" data-tooltip="{{ $payment->precise_made_at->format('Y-m-d H:i:s.u') }}">{{ $payment->made_at->format('Y-m-d H:i:s') }}</td>
				<td class="tooltip" data-tooltip="{{ $payment->sender }}">{{ $payment->short_sender }}</td>
				<td class="tooltip" data-tooltip="{{ $payment->recipient }}">{{ $payment->short_recipient }}</td>
				<td>{{ number_format($payment->amount, 9, '.', ',') }} XDAG</td>
			</tr>
		@empty
			<tr>
				<td colspan="4">No payments yet, check back later! ;-)</td>
			</tr>
		@endforelse
	</tbody>
	<tfoot>
		@if ($payments->count())
			<tr>
				<th colspan="3">TOTAL</th>
				<th>{{ number_format($payments->sum('amount'), 9, '.', ',') }} XDAG</th>
			</tr>
		@endif
	</tfoot>
</table>
