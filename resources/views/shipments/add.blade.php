@extends('layouts.app')

@section('content')

<div class="container"> 
    <h1>Add Shipment - Pick Up</h1>
    <a href="{{ route('shipments.index') }}" class="btn btn-default">Go Back</a>
</div><br>
{!! Form::open(['action' => 'TxnsController@storeShipment', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sender Details</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Name *</span>
                        <input type="text" id="sender_name" name="sender_name" value="{{ old('sender_name') }}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Company *</span>
                        {{Form::select('sender_company', ['' => ''] + $companies + ['0' => 'Others'], old('sender_company'), ['class' => 'form-control', 'id' => 'sender_company'])}}
                    </div>
                    <div class="input-group" id="other_company_name" style="display: none ;">
                        <span class="input-group-addon">Company Name *</span>
                        <input type="text" id="other_company" name="other_company" value="{{ old('other_company') }}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone *</span>
                        <input type="text" id="sender_phone" name="sender_phone" value="{{ old('sender_phone') }}" class="form-control" placeholder="e.g. 254723000000" aria-describedby="basic-addon1">
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
                        <input type="text" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Company *</span>
                        <input type="text" id="receiver_company" name="receiver_company" value="{{ old('receiver_company') }}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone *</span>
                        <input type="text" id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone') }}" class="form-control" placeholder="e.g. 254723000000" aria-describedby="basic-addon1">
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
                        <input type="text" id="origin_addr" name="origin_addr" value="{{ old('origin_addr')  }}" class="form-control"  aria-describedby="basic-addon1" >
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
                        <input type="text" id="dest_addr" name="dest_addr" value="{{ old('dest_addr') }}" class="form-control"  aria-describedby="basic-addon1" >
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
                        {{Form::select('parcel_type_id', ['' => ''] + $parcel_types, old('parcel_type_id'), ['class' => 'form-control'])}}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Description</span>
                        <input type="text" id="parcel_desc" name="parcel_desc" value="{{ old('parcel_desc') }}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Booking Mode</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Mode *</span>
                        {{Form::select('mode', ['' => '', 1 => 'Express', 0 => 'Normal'],  old('mode') , ['class' => 'form-control'])}}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Round trip *</span>
                        {{Form::select('round', ['' => '', 1 => 'Yes', 0 => 'No'], old('round'), ['class' => 'form-control'])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Units</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >No of units *</span>
                        <input type="text" id="units" name="units" value="{{ old('units')}}" class="form-control" placeholder="e.g. 1" aria-describedby="basic-addon1">
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
                        {{Form::select('rider_id', ['' => ''] + $riders , old('rider_id'), ['class' => 'form-control'])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Price</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon" >Amount</span>
                        <input type="text" id="price" name="price" value="{{ old('price')}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-12 text-center"> 
	    {{Form::submit('Submit', ['class'=>'btn btn-primary btn-xl'])}}
	</div>
</div>
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