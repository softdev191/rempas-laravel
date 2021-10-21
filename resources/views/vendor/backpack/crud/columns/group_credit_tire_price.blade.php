
@if($entry && $column['credit_tire'])
	@php
		$creditTire = $column['credit_tire'];
		$groupCreditTire = $creditTire->tuningCreditGroups()->where('tuning_credit_group_id', $entry->id)->withPivot('from_credit', 'for_credit')->first();
	@endphp
	{{ config('site.currency_sign') }} {{ number_format(@$groupCreditTire->pivot->from_credit, 2) }}
	->
	{{ config('site.currency_sign') }} {{ number_format(@$groupCreditTire->pivot->for_credit, 2) }}
@endif
