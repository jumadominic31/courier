@extends('layouts.app')

@section('content')
<div class="row">
    <div >
        <h2>Edit Shipment</h2><br>
        <a href="{{ route('shipments.index') }}" class=" btn btn-default btn-xs">Go Back</a><br><br>
    </div>
</div>
@if ($txn->parcel_status_id != '6')
    <div class="row">
        <div class="col-md-12 text-center"> 
            {!!Form::open(['action' => ['TxnsController@cancelShipment', $txn->id],'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure you want to cancel this shipment?")'])!!}
              {{Form::hidden('_method', 'PUT')}}
              {{Form::submit('Cancel Shipment', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
        </div>
    </div>
@endif
<br>
{!!Form::open(['action' => ['TxnsController@update', $txn->id],'method' => 'POST'])!!}
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">AWB and Status</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >AWB Num:</span>
                        <input type="text" id="awb_num" name="awb_num" value="{{$txn['awb_num']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Parcel Status: *</span>
                        {{Form::text('parcel_status', $txn->parcel_status['name'], ['class' => 'form-control', 'disabled' => 'true'])}}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Ship Date: *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="txn_date" name="txn_date" value="{{$txn['txn_date']}}" class="form-control first_date"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="txn_date" name="txn_date" value="{{$txn['txn_date']}}" class="form-control first_date"  aria-describedby="basic-addon1">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Rider</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Name *</span>
                        @if ($txn->parcel_status_id == '6')
                            {{Form::select('rider_id', ['' => ''] + $riders , $txn->driver_id, ['class' => 'form-control', 'disabled' => 'true'])}}
                        @else
                            {{Form::select('rider_id', ['' => ''] + $riders , $txn->driver_id, ['class' => 'form-control'])}}
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
                    <h3 class="panel-title">Sender Details</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Name *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="sender_name" name="sender_name" value="{{$txn['sender_name']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="sender_name" name="sender_name" value="{{$txn['sender_name']}}" class="form-control"  aria-describedby="basic-addon1">
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Company *</span>
                        @if ($txn->parcel_status_id == '6')
                            {{Form::select('sender_company', ['' => ''] + $companies + ['0' => 'Others'], $txn['sender_company_id'], ['class' => 'form-control', 'id' => 'sender_company', 'disabled' => 'true'])}}
                        @else
                            {{Form::select('sender_company', ['' => ''] + $companies + ['0' => 'Others'], $txn['sender_company_id'], ['class' => 'form-control', 'id' => 'sender_company'])}}
                        @endif
                    </div>
                    <div class="input-group" id="other_company_name" style="display: none ;">
                        <span class="input-group-addon">Company Name *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="other_company" name="other_company" value="{{ old('other_company') }}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="other_company" name="other_company" value="{{ old('other_company') }}" class="form-control"  aria-describedby="basic-addon1">
                        @endif
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
                        <span class="input-group-addon" >Name *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="receiver_name" name="receiver_name" value="{{$txn['receiver_name']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="receiver_name" name="receiver_name" value="{{$txn['receiver_name']}}" class="form-control"  aria-describedby="basic-addon1">
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Company *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="receiver_company" name="receiver_company" value="{{$txn['receiver_company_name']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="receiver_company" name="receiver_company" value="{{$txn['receiver_company_name']}}" class="form-control"  aria-describedby="basic-addon1">
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
                    <div class="input-group">
                        <span class="input-group-addon" >Physical Address *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="origin_addr" name="origin_addr" value="{{$txn['origin_addr']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="origin_addr" name="origin_addr" value="{{$txn['origin_addr']}}" class="form-control"  aria-describedby="basic-addon1" >
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Destination</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Physical Address *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="dest_addr" name="dest_addr" value="{{$txn['dest_addr']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="dest_addr" name="dest_addr" value="{{$txn['dest_addr']}}" class="form-control"  aria-describedby="basic-addon1" >
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
                    <h3 class="panel-title">Shipment Type</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Type *</span>
                        @if ($txn->parcel_status_id == '6')
                            {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, $txn->parcel_type_id, ['class' => 'form-control', 'disabled' => 'true'])}}
                        @else
                            {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, $txn->parcel_type_id, ['class' => 'form-control'])}}
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >No of units *</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="units" name="units" value="{{$txn['units']}}" class="form-control" placeholder="e.g. 1" aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="units" name="units" value="{{$txn['units']}}" class="form-control" placeholder="e.g. 1" aria-describedby="basic-addon1">
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Description</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="parcel_desc" name="parcel_desc" value="{{$txn['parcel_desc']}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
                        @else
                            <input type="text" id="parcel_desc" name="parcel_desc" value="{{$txn['parcel_desc']}}" class="form-control"  aria-describedby="basic-addon1">
                        @endif  
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Other Options</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Outside Coverage *</span>
                        @if ($txn->parcel_status_id == '6')
                            {{Form::select('out_coverage', ['' => '', 0 => 'No', 1 => 'Yes'],  $txn['out_coverage'] , ['class' => 'form-control', 'disabled' => 'true'])}}
                        @else
                            {{Form::select('out_coverage', ['' => '', 0 => 'No', 1 => 'Yes'],  $txn['out_coverage'] , ['class' => 'form-control'])}}
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Big Luggage *</span>
                        @if ($txn->parcel_status_id == '6')
                            {{Form::select('big_luggage', ['' => '', 0 => 'No', 1 => 'Yes'],  $txn['big_luggage'] , ['class' => 'form-control', 'disabled' => 'true'])}}
                        @else
                            {{Form::select('big_luggage', ['' => '', 0 => 'No', 1 => 'Yes'],  $txn['big_luggage'] , ['class' => 'form-control'])}}
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Page</span>
                        @if ($txn->parcel_status_id == '6')
                            <input type="text" id="page" name="page" value="{{ $txn['page'] }}" class="form-control"  aria-describedby="basic-addon1", disabled="true">
                        @else
                            <input type="text" id="page" name="page" value="{{ $txn['page'] }}" class="form-control"  aria-describedby="basic-addon1">
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
{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#sender_company').change(function(){
            var i= $('#sender_company').val();
            if (i == '0'){
                $('#other_company_name').show();
            }
            else {
                $('#other_company_name').hide();
            }
        });
    });
</script>

@endsection