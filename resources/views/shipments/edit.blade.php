@extends('layouts.app')

@section('content')
<h2>Edit Shipment</h2>
<div class="row">
    <div class="col-sm-2"><a class="btn btn-default btn-xs" target="_blank" href="{{ route('shipments.print', ['awb' => $txn->id ]) }}">Print</a></div>
    <div class="col-sm-2"><a href="{{ route('shipments.index') }}" class=" btn btn-default btn-xs">Go Back</a></div>
    <div class="col-sm-2">
        @if ($txn->parcel_status_id == '7')
            {!!Form::open(['action' => ['TxnsController@cancel', $txn->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure you want to cancel this booking?")'])!!}
              {{Form::hidden('_method', 'PUT')}}
              {{Form::submit('Cancel', ['class' => 'btn btn-danger btn-xs'])}}
            {!! Form::close() !!}
        @elseif ($txn->parcel_status_id == '6')
            {!!Form::open(['action' => ['TxnsController@uncancel', $txn->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure you want to uncancel this booking?")'])!!}
              {{Form::hidden('_method', 'PUT')}}
              {{Form::submit('Uncancel', ['class' => 'btn btn-primary btn-xs'])}}
            {!! Form::close() !!}
        @endif
    </div>
</div>

<hr>
{!!Form::open(['action' => ['TxnsController@update', $txn->id],'method' => 'POST'])!!}
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">AWB and Status</h3>
                </div>
                <div class="panel-body">
                    <strong>{{$txn['awb_num']}}</strong>
                    <p>{{$txn->parcel_status['name']}}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Shipment Type</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Type</span>
                            {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, $txn->parcel_type_id, ['class' => 'form-control', 'disabled' => 'true'])}}
                    </div>
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
                    {{Form::text('origin_addr', $origin_addr, ['class' => 'form-control', 'disabled' => 'true'])}}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Destination</h3>
                </div>
                <div class="panel-body">
                    {{Form::text('dest_addr', $dest_addr, ['class' => 'form-control', 'disabled' => 'true'])}}
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
                        <input type="text" id="sender_name" name="sender_name" value="{{$txn['sender_name']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone</span>
                        <input type="text" id="sender_phone" name="sender_phone" value="{{$txn['sender_phone']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
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
                        <input type="text" id="receiver_name" name="receiver_name" value="{{$txn['receiver_name']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone</span>
                        <input type="text" id="receiver_phone" name="receiver_phone" value="{{$txn['receiver_phone']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >ID Num</span>
                        <input type="text" id="receiver_id_num" name="receiver_id_num" value="{{$txn['receiver_id_num']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Rider Details</h3>
                </div>
                <div class="panel-body">
                    <!-- <input type="text" id="driver_id" name="driver_id" value="{{$txn['driver']['fullname']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true"> -->
                    @if ($txn['parcel_status_id'] == '2' || $txn['parcel_status_id'] == '2')
                        {{Form::select('driver_id', ['' => ''] + $drivers, $txn['driver_id'], ['class' => 'form-control'])}}
                    @else
                        {{Form::select('driver_id', ['' => ''] + $drivers, $txn['driver_id'], ['class' => 'form-control', 'disabled' => 'true'])}}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Units</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >No of units *</span>
                            <input type="text" id="units" name="units" value="{{$txn->units}}" class="form-control" aria-describedby="basic-addon1" disabled="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Booking Mode</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Mode *</span>
                        {{Form::select('mode', ['' => '', 1 => 'Express', 0 => 'Normal'], $txn->mode, ['class' => 'form-control', 'disabled' => 'true'])}}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Round trip *</span>
                        {{Form::select('round', ['' => '', 1 => 'Yes', 0 => 'No'], $txn->round, ['class' => 'form-control', 'disabled' => 'true'])}}
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