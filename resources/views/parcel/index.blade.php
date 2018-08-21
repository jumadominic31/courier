@extends('layouts.app')

@section('content')

<div>
	<h3>Parcel Types</h3>
	<a href="{{ route('parcel.create') }}" class="btn btn-success">Add ParcelType</a>
	<br>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Unit</th>
				<th>Status</th>
				@if(Auth::user()->usertype == 'superadmin')
              	<th>Company</th>
              	@endif
		        <th></th>
			</tr>
		</thead>
		@foreach($parceltype as $parceltyp)
		<tr>
			<td>{{$parceltyp['name']}}</td>
			<td>{{$parceltyp['unit']}}</td>
			<td><?php if ($parceltyp['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
			@if(Auth::user()->usertype == 'superadmin')
          	<td>{{$parceltyp['company']['name']}}</td>
          	@endif
          	<td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('parcel.edit', ['parceltype' => $parceltyp->id ]) }}">Edit</a></span></td>
          	
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
		<tr>
			<td>1</td>
			<td>Booked</td>
			<td>Parcel booked by/is at customer</td>
		</tr>
		<tr>
			<td>2</td>
			<td>Received at sort facility</td>
			<td>Parcel received at sort facility</td>
		</tr>
		<tr>
			<td>3</td>
			<td>Dispatched</td>
			<td>Parcel dispatched from sort facility. On transit</td>
		</tr>
		<tr>
			<td>4</td>
			<td>Received at destination</td>
			<td>Parcel taken by receiver at destination</td>
		</tr>
		<tr>
			<td>5</td>
			<td>Cancelled</td>
			<td>Shipment cancelled</td>
		</tr><tr>
			<td>6</td>
			<td>Lost</td>
			<td>Parcel cannot be traced</td>
		</tr>
	</table>
</div>

@endsection