<!--<!DOCTYPE html>
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
  <body>-->
@extends('layouts.app')
@section('content')
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
		{{ Form::open(array('method'=>'post', 'url' => '/home','id' => 'form')) }}
		<div class="form-group row">
			<div class="col-sm-2">
				{{Form::label('min_period', 'Min period')}}
			</div>	
			<div class="col-sm-4">
				{{ Form::text('min_period', null, array('id' => 'minp'))}} 
			</div>	
			<div class="col-sm-2">
		    	{{Form::label('max_period', 'Max period')}} 
			</div>	
			<div class="col-sm-4">
				{{ Form::text('max_period', null, array('id' => 'maxp'))}}
			</div>
		</div>	
		<br/>
		<div class="form-group row">
		
			<div class="col-sm-2">
				{{Form::label('min_amount', 'Min amount')}} 
			</div>
			<div class="col-sm-4">	
				{{ Form::text('min_amount', null, array('id' => 'mina'))}}&nbsp;BGN 
			</div>
			<div class="col-sm-2">	
				{{Form::label('max_amount', 'Max amount')}} 
			</div>
			<div class="col-sm-4">	
				{{ Form::text('max_amount', null, array('id' => 'maxa'))}}&nbsp;BGN 
			</div>
		</div>	
		<div class="form-group row"> 
			<div class="offset-sm-5 col-sm-3">
			{!!Form::submit('Find', array('class' => 'btn btn-primary'))!!}
			</div>
		</div>
		{{ Form::close() }}
	
		@isset($notice)
		{!!$notice!!}
		@endisset
			
		@isset($credits)
		@if(count($credits)== 0)
			<div class="alert alert-warning">
				<strong>Sorry!</strong> No Credits Found.
			</div>                                      
		@else
		<table class="table table-stripped">
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
@endsection	
  <!--</body>
</html>-->