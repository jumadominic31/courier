@extends('layouts.cusapp')

@section('content')

<div>
	<h3>Parcel Types and Rates</h3>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Unit</th>
				<th>Rate (KShs.)</th>
				@if(Auth::user()->usertype == 'superadmin')
              	<th>Company</th>
              	@endif
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