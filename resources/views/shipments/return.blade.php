@extends('layouts.app')

@section('content')

    <h2>Return Shipment </h2> 
    <a href="{{ route('shipments.booked') }}" class=" btn btn-default btn-xs pull-right">Go Back</a>

<br>
<br>

{!!Form::open(['action' => ['TxnsController@postreturnShipment', $txn->id],'method' => 'POST'])!!}
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Reason for Return</h3>
            </div>
            <div class="panel-body">
                <input type="text" id="return_reason" name="return_reason" value="{{ old('return_reason') }}" class="form-control"  aria-describedby="basic-addon1" placeholder="Enter reason for return">
            </div>
        </div>
    </div>
</div>
{{Form::hidden('_method', 'PUT')}}
{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
<br>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">AWB</h3>
            </div>
            <div class="panel-body">
                {{$txn->awb_num}} <br>
                {{$txn->parcel_type_name}}
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">AWB Status</h3>
            </div>
            <div class="panel-body">
                {{$txn->parcel_status->name}} 
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
                {{$txn->origin_addr}}
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Destination</h3>
            </div>
            <div class="panel-body">
                {{$txn->dest_addr}}
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
                Name: {{$txn->sender_name}} <br>
                Company: {{$txn->sender_company_name}} <br>
                Phone:  {{$txn->sender_phone}} <br>
                <!-- <img src="{{$txn->sender_sign}}" alt="Sender Sign" style="width:80px; height:80px"> -->
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Receiver Details</h3>
            </div>
            <div class="panel-body">
                Name: {{$txn->receiver_name}} <br>
                Company: {{$txn->receiver_company_name}} <br>
                Phone:  {{$txn->receiver_phone}} <br>
                <!-- <img src="{{$txn->receiver_sign}}" alt="Receiver Sign" style="width:80px; height:80px"> -->
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
                @if ($txn->mode == '0')
                    Mode: Normal
                @elseif ($txn->mode == '1')
                    Mode: Express
                @endif
                <br>
                @if ($txn->acknowledge == '0')
                    Acknowledgement Reqd: No
                @elseif ($txn->acknowledge == '1')
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
                {{$txn->driver['fullname']}} <br>
            </div>
        </div>
    </div>
    
</div>
    
    
@endsection