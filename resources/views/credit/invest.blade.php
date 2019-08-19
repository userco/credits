<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Invest</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  </head>
  <body>
	<div class="container">
	<h1>Invest in credit</h2>
	@isset($invested_amount)
		<p>Invested amount: {{$invested_amount}}</p>
	@endisset	
	@isset($investment)
		<p>You have invested in this credit: {{$investment}}</p>
	@endisset
	
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
		{{ Form::open(array('method'=>'post', 'url' => 'invest/'.$creditId,'id' => 'form')) }}
		<table>
		<tr>
		<td>	{{Form::label('investment', 'Invest')}} </td>
		<td>	{{ Form::text('investment')}} BGN</td>
		</tr>
		<tr>
		<td colspan="2" align="center">
			{{Form::submit('Save', null, array('class' => 'btn btn-primary'))}}
		</td>
		</tr>
		{{ Form::close() }}
	
	</div>
	
  </body>
</html>