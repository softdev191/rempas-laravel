@extends('layouts.appnew')
@section('content')

<div class="thank-you-section">
<div class="container">
<div class="inner-box">
<span class="large-text">Thank You!</span>	
<p>@php echo $msg @endphp</p>
<a href="{{route('innerhome')}}" class="back-btn"><i class="fa fa-angle-left"></i> Back</a>
</div>
</div>
</div>


@endsection	