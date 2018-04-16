@extends('layouts.app')

@section('content')
<div class="row">
    <div ><h2>Edit Shipment <br> <a href="{{ route('shipments.index') }}" class=" btn btn-default btn-xs">Go Back</a></h2></div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-5">
        <span class="center-block">
            {!!Form::open(['action' => ['TxnsController@resetReceivercode', $txn->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure you want to reset receiver code?")'])!!}
              {{Form::hidden('_method', 'POST')}}
              {{Form::submit('Reset Receiver Code', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
        </span>
    </div>
</div>

<hr>
{!!Form::open(['action' => ['TxnsController@update', $txn->id],'method' => 'POST'])!!}
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">AWB</h3>
                </div>
                <div class="panel-body">
                    {{$txn['awb_num']}}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Shipment Status and Type</h3>
                </div>
                <div class="panel-body">
                    {{Form::select('parcel_status_id', ['' => ''] + $parcel_statuses, $txn->parcel_status_id, ['class' => 'form-control'])}}
                    {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, $txn->parcel_type_id, ['class' => 'form-control'])}}
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
                    {{$txn['zone_origin']['name']}}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Destination</h3>
                </div>
                <div class="panel-body">
                    {{Form::select('dest_id', ['' => ''] + $stations, $txn->dest_id, ['class' => 'form-control'])}}
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
                    <div class="input-group">
                        <span class="input-group-addon" >Name</span>
                        <input type="text" id="sender_name" name="sender_name" value="{{$txn['sender_name']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone</span>
                        <input type="text" id="sender_phone" name="sender_phone" value="{{$txn['sender_phone']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >ID Num</span>
                        <input type="text" id="sender_id_num" name="sender_id_num" value="{{$txn['sender_id_num']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Receiver Details</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Name</span>
                        <input type="text" id="receiver_name" name="receiver_name" value="{{$txn['receiver_name']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone</span>
                        <input type="text" id="receiver_phone" name="receiver_phone" value="{{$txn['receiver_phone']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >ID Num</span>
                        <input type="text" id="receiver_id_num" name="receiver_id_num" value="{{$txn['receiver_id_num']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Driver and Vehicle Details</h3>
                </div>
                <div class="panel-body">
                    {{Form::select('driver_id', ['' => ''] + $drivers, $txn['driver_id'], ['class' => 'form-control'])}}
                    {{Form::select('vehicle_id', ['' => ''] + $vehicles, $txn->vehicle_id, ['class' => 'form-control'])}}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Price</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon">Price (KShs.)</span>
                        <input type="text" id="price" name="price" value="{{$txn['price']}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >VAT (KShs.)</span>
                        <input type="text" id="vat" name="vat" value="{{$txn['vat']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                    </div>
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
    
{{Form::hidden('_method', 'PUT')}}
{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
@endsection