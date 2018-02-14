@extends('layouts.app')

@section('content')
<div class="container">
@include('inc.messages')

<div class="row">
    <section id="breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
            <li class="active"><h2>Dashboard</h2></li>
            </ol>
        </div>
    </section>


    </div>
    <!-- Website Overview -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg">
            <h3 class="panel-title">Overview</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{ route('shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> {{$sales}} </h2>
                            <h4>Today Sales</h4>
                        </div>
                    </a>
                </div>
            </div>
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
                            <h4>Today Dispatched</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$delivered}} </h2>
                            <h4>Today Delivered</h4>
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
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{ route('vehicle.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><i class="material-icons" style="font-size:36px">directions_bus</i> {{$vehicles}} </h2>
                            <h4>Vehicles</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('station.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> {{$stations}} </h2>
                            <h4>Stations</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('users.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$drivers}}  </h2>
                            <h4>Drivers</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('users.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$clerks}} </h2>
                            <h4>Clerks</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Sales -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Top 3 Stations - Sales</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
	                <th class="col-md-6">Station Name</th>
	                <th class="col-md-6">Total Sales</th>
                </tr>
                @foreach($topsales as $topsale)
                <tr>
                    <td class="col-md-6">{{$topsale['origin']['name']}}</td>
                    <td class="col-md-6">{{$topsale['total_sales']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
        <div class="panel-heading">
            <h3 class="panel-title">Parcels</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
	                <th class="col-sm-2">Station Name</th>
	                <th class="col-sm-2">Created</th>
                    <th class="col-sm-2">Dispatched</th>
                    <th class="col-sm-2">Delivered</th>
                    <th class="col-sm-2">Received</th>
                    <th class="col-sm-2">Lost</th>
                </tr>
                @foreach($parcels as $parcel)
                <tr>
                    <td class="col-sm-2">{{$parcel['origin']['name']}}</td>
                    <td class="col-sm-2">{{$parcel['created']}}</td>
                    <td class="col-sm-2">{{$parcel['dispatched']}}</td>
                    <td class="col-sm-2">{{$parcel['delivered']}}</td>
                    <td class="col-sm-2">{{$parcel['received']}}</td>
                    <td class="col-sm-2">{{$parcel['lost']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
    </div>

</div>

@endsection
