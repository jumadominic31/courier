@extends('layouts.app')

@section('content')

<div>
	<h3>Parcel Types and Rates</h3>
	<a href="{{ route('parcel.create') }}" class="btn btn-success">Add ParcelType</a>
	<br>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Unit</th>
				<th>Rate (KShs.)</th>
				@if(Auth::user()->usertype == 'superadmin')
              	<th>Company</th>
              	@endif
		        <th></th>
		        <th></th>
			</tr>
		</thead>
		@foreach($parceltype as $parceltyp)
		<tr>
			<td>{{$parceltyp['name']}}</td>
			<td>{{$parceltyp['unit']}}</td>
			<td>{{$parceltyp['rate']}}</td>
			@if(Auth::user()->usertype == 'superadmin')
          	<td>{{$parceltyp['company']['name']}}</td>
          	@endif
          	<td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('parcel.edit', ['parceltype' => $parceltyp->id ]) }}">Edit</a></span></td>
          	<td><span class="center-block">
            {!!Form::open(['action' => ['ParcelsController@destroy', $parceltyp->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger btn-xs'])}}
            {!! Form::close() !!}
          	</span></td>
		</tr>
		@endforeach
	</table>
</div>
<div>
	<h3>Parcel Status</h3>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Stage</th>
				<th>Status</th>
				<th>Description</th>
			</tr>
		</thead>
		@foreach($parcelstatus as $parcelst)
		<tr>
			<td>{{$parcelst['id']}}</td>
			<td>{{$parcelst['name']}}</td>
			<td>{{$parcelst['description']}}</td>
		</tr>
		@endforeach
	</table>
</div>

@endsection