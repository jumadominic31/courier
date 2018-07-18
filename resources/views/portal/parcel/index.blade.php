@extends('layouts.cusapp')

@section('content')

<div>
	<h3>Parcel Types and Rates</h3>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Unit</th>
				<!-- <th>Rate (KShs.)</th> -->
				@if(Auth::user()->usertype == 'superadmin')
              	<th>Company</th>
              	@endif
			</tr>
		</thead>
		@foreach($parceltype as $parceltyp)
		<tr>
			<td>{{$parceltyp['name']}}</td>
			<td>{{$parceltyp['unit']}}</td>
			<!-- <td>{{$parceltyp['rate']}}</td> -->
			@if(Auth::user()->usertype == 'superadmin')
          	<td>{{$parceltyp['company']['name']}}</td>
          	@endif
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
		<tr><td>1</td><td>Booked</td><td>Parcel booked by customer</td></tr>
		<tr><td>2</td><td>Picked to sort facility</td><td>Parcel picked by rider from origin to sorting facility</td></tr>
		<tr><td>3</td><td>Received at sort facility</td><td>Parcel received at sort facility</td></tr>
		<tr><td>4</td><td>Dispatched</td><td>Parcel dispatched from sort facility. On transit</td></tr>
		<tr><td>5</td><td>Received at destination</td><td>Parcel taken by receiver at destination</td></tr>
		<tr><td>6</td><td>Cancelled</td><td>Parcel shipment cancelled</td></tr>
		<tr><td>7</td><td>Lost</td><td>Parcel cannot be traced</td></tr>
	</table>
</div>

@endsection