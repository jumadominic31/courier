@extends('layouts.cusapp')

@section('content')
<h2>Edit Shipment</h2>
<div class="row">
    <div class="col-sm-2"><a class="btn btn-default btn-xs" target="_blank" href="{{ route('portal.shipments.print', ['awb' => $txn->id ]) }}">Print</a></div>
    <div class="col-sm-2"><a href="{{ route('portal.shipments.index') }}" class=" btn btn-default btn-xs">Go Back</a></div>
    <div class="col-sm-2">
        @if ($txn->parcel_status_id == '7')
            {!!Form::open(['action' => ['CusportalController@cancel', $txn->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure you want to cancel this booking?")'])!!}
              {{Form::hidden('_method', 'PUT')}}
              {{Form::submit('Cancel', ['class' => 'btn btn-danger btn-xs'])}}
            {!! Form::close() !!}
        @endif
    </div>
</div>

<hr>    

{!!Form::open(['action' => ['CusportalController@update', $txn->id],'method' => 'POST'])!!}

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
                        @if ($txn->parcel_status_id == '7')
                            {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, $txn->parcel_type_id, ['class' => 'form-control'])}}
                        @else
                            {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, $txn->parcel_type_id, ['class' => 'form-control', 'disabled' => 'true'])}}
                        @endif
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
                    @if ($txn->parcel_status_id == '7')
                        {{Form::text('origin_addr', $origin_addr, ['class' => 'form-control'])}}
                    @else
                        {{Form::text('origin_addr', $origin_addr, ['class' => 'form-control', 'disabled' => 'true'])}}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Destination</h3>
                </div>
                <div class="panel-body">
                    @if ($txn->parcel_status_id == '7')
                        {{Form::text('dest_addr', $dest_addr, ['class' => 'form-control'])}}
                    @else
                        {{Form::text('dest_addr', $dest_addr, ['class' => 'form-control', 'disabled' => 'true'])}}
                    @endif
                    
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
                    @if ($txn->parcel_status_id == '7')
                        <div class="input-group">
                            <span class="input-group-addon" >Name</span>
                            <input type="text" id="receiver_name" name="receiver_name" value="{{$txn['receiver_name']}}" class="form-control"  aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">Phone</span>
                            <input type="text" id="receiver_phone" name="receiver_phone" value="{{$txn['receiver_phone']}}" class="form-control"  aria-describedby="basic-addon1">
                        </div>
                    @else
                        <div class="input-group">
                            <span class="input-group-addon" >Name</span>
                            <input type="text" id="receiver_name" name="receiver_name" value="{{$txn['receiver_name']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">Phone</span>
                            <input type="text" id="receiver_phone" name="receiver_phone" value="{{$txn['receiver_phone']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        </div>
                    @endif
                    
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
                    <input type="text" id="driver_id" name="driver_id" value="{{$txn['driver']['fullname']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
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
                        @if ($txn->parcel_status_id == '7')
                            <input type="text" id="units" name="units" value="{{$txn->units}}" class="form-control" aria-describedby="basic-addon1">
                        @else
                            <input type="text" id="units" name="units" value="{{$txn->units}}" class="form-control" aria-describedby="basic-addon1" disabled="true">
                        @endif

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
                @if ($txn->parcel_status_id == '7')
                    <div class="input-group">
                            <span class="input-group-addon" >Mode *</span>
                            {{Form::select('mode', ['' => '', 1 => 'Express', 0 => 'Normal'], $txn->mode, ['class' => 'form-control'])}}
                        </div>
                @else
                        <div class="input-group">
                            <span class="input-group-addon" >Mode *</span>
                            {{Form::select('mode', ['' => '', 1 => 'Express', 0 => 'Normal'], $txn->mode, ['class' => 'form-control', 'disabled' => 'true'])}}
                        </div>
                @endif
                        <div class="input-group">
                            <span class="input-group-addon" >Round trip *</span>
                            {{Form::select('round', ['' => '', 1 => 'Yes', 0 => 'No'], $txn->round, ['class' => 'form-control', 'disabled' => 'true'])}}
                        </div>
                    </div>
                
               
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Acknowledgement Receipt</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon">Required</span>
                        @if ($txn->parcel_status_id == '7')
                            {{Form::select('acknowledge', ['' => '', 1 => 'Yes', 0 => 'No'], $txn->acknowledge, ['class' => 'form-control'])}}
                        @else
                            {{Form::select('acknowledge', ['' => '', 1 => 'Yes', 0 => 'No'], $txn->acknowledge, ['class' => 'form-control', 'disabled' => 'true'])}}
                        @endif
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
@if ($txn->parcel_status_id == '7')
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
@else
    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'disabled' => 'true'])}}
@endif

{!! Form::close() !!}
@endsection