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
                <div class="col-sm-4">
                    <a href="{{ route('shipments.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$shipments}} </h2>
                            <h4>Current Month Total Shipments</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ route('users.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$customers}}  </h2>
                            <h4>Customers</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ route('users.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$drivers}}  </h2>
                            <h4>Drivers</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Sales -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Current Month Shipments per Company</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
	                <th class="col-md-6">Customer Name</th>
	                <th class="col-md-6">Total Shipments</th>
                </tr>
                @foreach($percompany as $company)
                <tr>
                    <td class="col-md-6">{{$company['sender_company']['name']}}</td>
                    <td class="col-md-6">{{$company['shipment_count']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
        <div class="panel-heading">
            <h3 class="panel-title">Current Month Shipments per Rider</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th class="col-md-6">Rider Name</th>
                    <th class="col-md-6">Total Shipments</th>
                </tr>
                @foreach($perrider as $rider)
                <tr>
                    <td class="col-md-6">{{$rider['driver']['fullname']}}</td>
                    <td class="col-md-6">{{$rider['shipment_count']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
    </div>

</div>

@endsection
