@extends('layouts.cusapp')

@section('content')

	<div class="container"> 
            <h1>AWB Search</h1>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! Form::open(['action' => 'CusportalController@getAwb', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="input-group">
                        <span class="input-group-addon" >AWB Number</span>
                        <input type="text" id="awb_num" name="awb_num" value="" class="form-control" placeholder="AWB Num" aria-describedby="basic-addon1">
                    </div>
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <br>
        @if(count($txn) > 0)
        <?php
            $colcount = count($txn);
            $i = 1;
        ?>
        <div class="container"> 
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">AWB</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->awb_num}} <br>
                            {{$txn[0]->parcel_type_name}}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">AWB Status</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->description}} 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Origin</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->origin_addr}}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Destination</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->dest_addr}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sender Details</h3>
                        </div>
                        <div class="panel-body">
                            Name: {{$txn[0]->sender_name}} <br>
                            Company: {{$txn[0]->sender_company_name}} <br>
                            Phone: 	{{$txn[0]->sender_phone}} <br>
                            <!-- <img src="{{$txn[0]->sender_sign}}" alt="Sender Sign" style="width:80px; height:80px"> -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Receiver Details</h3>
                        </div>
                        <div class="panel-body">
                            Name: {{$txn[0]->receiver_name}} <br>
                            Company: {{$txn[0]->receiver_company_name}} <br>
                            Phone: 	{{$txn[0]->receiver_phone}} <br>
                            ID Num: {{$txn[0]->receiver_id_num}} <br>
                            <!-- <img src="{{$txn[0]->receiver_sign}}" alt="Receiver Sign" style="width:80px; height:80px"> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Booking Details</h3>
                        </div>
                        <div class="panel-body">
                            @if ($txn[0]->mode == '0')
                                Mode: Normal
                            @elseif ($txn[0]->mode == '1')
                                Mode: Express
                            @endif
                            <br>
                            @if ($txn[0]->round == '0')
                                Round Trip: No
                            @elseif ($txn[0]->round == '1')
                                Round Trip: Yes
                            @endif
                            <br>
                            @if ($txn[0]->acknowledge == '0')
                                Acknowledgement Reqd: No
                            @elseif ($txn[0]->acknowledge == '1')
                                Acknowledgement Reqd: Yes
                            @endif
                        </div>
                    </div>
                </div>
            	<div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Rider Details</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->driver['fullname']}} <br>
                            {{$txn[0]->vehicle['name']}} <br>
                            <!-- <img src="{{$txn[0]->pick_driver_sign}}" alt="Driver Sign" style="width:80px; height:80px"> -->
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Shipment Details</div>
                <table class="table">
                    <tr>
                        <th>Date/Time</th>
                        <th>Status</th>
                        <th>Updated By</th>
                    </tr>
                    @foreach($statusDet as $stDet)
                    <tr>
                        <td>{{$stDet->updated_at}}</td>
                        <td>{{$stDet->description}}</td>
                        <td>{{$stDet->fullname}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @else
            <div class="container"> 
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Enter a valid AWB</h4>
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection