@extends('layouts.app')
@section('content')
	<div class="container">
	{!! Html::linkRoute('credits_list', "<< Credits List",null,  array('class' => 'btn btn-primary'))!!}
	<br>
	<h1>Invest in credit #{{$creditObj->external_id}}</h2>
	<div class="alert alert-info">
	<p>Total: {{$creditObj->total}} &nbsp;BGN</p>
	@isset($invested_amount)
		<p>Invested amount: <b>{{$invested_amount}} &nbsp;BGN</b></p>
	@endisset	
	@isset($investment)
		<p>You have invested in this credit: <b>{{$investment}}&nbsp;BGN</b></p>
	@endisset
	</div>
	@isset($notify)
		<div class="alert alert-danger">
			<p>{{$notify}}</p>
		</div>
	@endisset
	@isset($notif)
		<div class="alert alert-success">
			<p>{{$notif}}</p>
		</div>
	@endisset
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				  <li>{{ $error }}</li>
				@endforeach
			</ul>
		</div><br />
	@endif
	{{ Form::open(array('method'=>'post', 'url' => 'invest/'.$creditObj->id,'id' => 'form')) }}
		<div class="form-group row">
			<div class="col-sm-2">
			{{Form::label('investment', 'Invest')}}
			</div>
			<div class="col-sm-3">
			{{ Form::text('investment')}} BGN
			</div>
		</div>	
		<div class="form-group row">
			<div class="offset-sm-2 col-sm-3">
			{!!Form::submit('Save', array('class' => 'btn btn-primary'))!!}
			</div>
		</div>	
	{{ Form::close() }}

@endsection