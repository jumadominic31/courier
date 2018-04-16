@extends('layouts.app')

@section('content')

<div class="container"> 
    <h1>Add Shipment</h1>
</div>
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
                        <span class="input-group-addon" >Name</span>
                        <input type="text" id="sender_name" name="sender_name" value="" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Company</span>
                        <input type="text" id="sender_company" name="sender_company" value="{{$company_id}}" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone</span>
                        <input type="text" id="sender_phone" name="sender_phone" value="" class="form-control"  aria-describedby="basic-addon1">
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
                        <input type="text" id="receiver_name" name="receiver_name" value="" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Company</span>
                        <input type="text" id="receiver_company" name="receiver_company" value="" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Phone</span>
                        <input type="text" id="receiver_phone" name="receiver_phone" value="" class="form-control"  aria-describedby="basic-addon1">
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
                		<span class="input-group-addon" >Name</span>
                    	<input type="text" id="origin_id" name="origin_id" value="{{$origin_id}}" class="form-control"  aria-describedby="basic-addon1" disabled="true">
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
                        <span class="input-group-addon" >Name</span>
                    	{{Form::select('dest_id', ['' => ''] + $stations, '', ['class' => 'form-control'])}}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Physical Address</span>
                    	<input type="text" id="dest_addr" name="dest_addr" value="" class="form-control"  aria-describedby="basic-addon1" >
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
                        <span class="input-group-addon" >Type</span>
                    	{{Form::select('parcel_type_id', ['' => ''] + $parcel_types, '', ['class' => 'form-control'])}}
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" >Description</span>
                        <input type="text" id="parcel_desc" name="parcel_desc" value="" class="form-control"  aria-describedby="basic-addon1">
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
                        <span class="input-group-addon" >Mode</span>
                        {{Form::select('mode', ['' => '', 1 => 'Express', 0 => 'Normal'], 0, ['class' => 'form-control'])}}
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
                        <input type="text" id="price" name="price" value="" class="form-control"  aria-describedby="basic-addon1">
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

@endsection