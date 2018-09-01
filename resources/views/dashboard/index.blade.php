@extends('layouts.app')

@section('content')
<div class="container">
@include('inc.messages')

<div class="row">
    <section id="breadcrumb">
        <div class="container">
            <ol class="breadcrumb" style="text-align: center;">
            <li class="active"><h2>Dashboard</h2></li>
            </ol>
        </div>
    </section>


    </div>
    <!-- Website Overview -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg">
            <h3 class="panel-title" style="text-align: center;">Overview</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{ route('shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$booked}} </h2>
                            <h4>Today Booked</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$dispatched}} </h2>
                            <h4>Today On transit</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$received}} </h2>
                            <h4>Today Received</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('shipments.booked') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$unallocated}} </h2>
                            <h4>Unallocated Shipments</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="well dash-box">
                        <h2><i class="material-icons" style="font-size:36px">directions_bus</i> {{$vehicles}} </h2>
                        <h4>Vehicles</h4>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="well dash-box">
                        <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$drivers}}  </h2>
                        <h4>Drivers</h4>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="well dash-box">
                        <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$clerks}} </h2>
                        <h4>Clerks</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Sales -->

    <div class="panel panel-default">
        <div class="panel-heading" style="text-align: center;">
            <h3 class="panel-title">Today's Shipments per Customer</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
	                <th class="col-sm-2">Customer Name</th>
	                <th class="col-sm-2">Booked</th>
                    <th class="col-sm-2">Picked</th>
                    <th class="col-sm-2">Received</th>
                </tr>
                @foreach($parcels as $parcel)
                <tr>
                    <td class="col-sm-2">{{$parcel['sender_company']['name']}}</td>
                    <td class="col-sm-2">{{$parcel['booked']}}</td>
                    <td class="col-sm-2">{{$parcel['picked']}}</td>
                    <td class="col-sm-2">{{$parcel['received']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
    </div>

</div>

@endsection
