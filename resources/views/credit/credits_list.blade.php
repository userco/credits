<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Credits List</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  </head>
  <body>
	<div class="container">
	<h1>Credits List</h2>
		@if ($errors->any())
			  <div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					  <li>{{ $error }}</li>
					@endforeach
				</ul>
			  </div><br />
		@endif
		{{ Form::open(array('method'=>'post', 'url' => '/credits_list','id' => 'form')) }}
		<table class="table">
		<tr>
		<td>	{{Form::label('min_period', 'Min period')}} </td>
		<td>	{{ Form::text('min_period', null, array('id' => 'minp'))}} </td>
		<td>	{{Form::label('max_period', 'Max period')}} </td>
		<td>	{{ Form::text('max_period', null, array('id' => 'maxp'))}} </td>
		</tr>
		<tr>
		<td>	{{Form::label('min_amount', 'Min amount')}} </td>
		<td>	{{ Form::text('min_amount', null, array('id' => 'mina'))}}&nbsp;BGN </td>
		<td>	{{Form::label('max_amount', 'Max amount')}} </td>
		<td>	{{ Form::text('max_amount', null, array('id' => 'maxa'))}}&nbsp;BGN </td>
		</tr>
		<tr>
		<td colspan="4" align="center">
			{{Form::submit('Find', null, array('class' => 'btn btn-primary'))}}
		</td>
		</tr>
		{{ Form::close() }}
		<br>
		
		@isset($credits)
		@if(count($credits)== 0)
			<div class="alert alert-warning">
				<strong>Sorry!</strong> No Credits Found.
			</div>                                      
		@else
		<table class="table">
		<tr>
			<td>Credit ID</td>
			<td> Period </td>
			<td> Amount </td>
			<td>Invested amount</td>
			<td>Action</td>
		<tr>
		
			@foreach($credits as $credit) 
        <tr>
            <td>{{$credit->id}}</td>
			<td>{{$credit->period}}&nbsp;months</td>
			<td>{{$credit->total}}&nbsp;BGN</td>
			<td>{{$credit->invested_amount}}&nbsp;BGN</td>
			<td>{{ Html::linkRoute('invest', "Invest" , ['creditId' => $credit->id],['class' => 'btn btn-danger']) }}</td>
		</tr>	
			@endforeach
		</table>
			
		{{ $credits->links() }}
		@endif
		@endisset
		
		
	</div>
	
  </body>
</html>