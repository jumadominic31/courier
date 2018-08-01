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
            <h3 class="panel-title">Today's Overview</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$booked}} </h2>
                            <h4>Booked</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$dispatched}} </h2>
                            <h4>On Transit</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$cancelled}} </h2>
                            <h4>Cancelled</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('portal.shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$received}} </h2>
                            <h4>Received</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

	

</div>

@endsection
