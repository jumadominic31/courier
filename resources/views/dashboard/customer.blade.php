@extends('layouts.cusapp')

@section('content')
<div class="container">
@include('inc.messages')

	<div class="row">
	    <section id="breadcrumb" style="text-align: center;">
	        <div class="container">
	            <ol class="breadcrumb">
	               <li class="active">
                        <h2>Dashboard</h2>
                   </li>
	            </ol>
	        </div>
	    </section>
	</div>
    <!-- Common Tasks -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg" style="text-align: center;">
            <h4 class="panel-title">Quick Links</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3" style="text-align: center;">
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <a class="btn btn-lg btn-primary" href="{{ route('portal.shipments.add') }}" role="button">Book Shipment</a></p>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <a class="btn btn-lg btn-primary" href="{{ route('portal.shipments.awb') }}" role="button">Track Shipment</a></p>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                </div>
            </div>
        </div>
    </div>

    <!-- Website Overview -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg" style="text-align: center;">
            <h3 class="panel-title">Overview</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$booked}} </h2>
                            <h4>Today Booked</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$booked}} </h2>
                            <h4>On Transit</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$received}} </h2>
                            <h4>Today Received</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading" style="text-align: center;">
            <h3 class="panel-title">Consignment Statistics</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
	                <th class="col-sm-2">Sender Name</th>
	                <th class="col-sm-2">Booked</th>
                    <th class="col-sm-2">Received</th>
                </tr>
                @foreach($parcels as $parcel)
                <tr>
                    <td class="col-sm-2">{{$parcel['sender_name']}}</td>
                    <td class="col-sm-2">{{$parcel['booked']}}</td>
                    <td class="col-sm-2">{{$parcel['received']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
    </div>

</div>

@endsection
