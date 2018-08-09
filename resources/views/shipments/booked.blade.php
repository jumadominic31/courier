@extends('layouts.app')

@section('content')

<a href="{{ route('shipments.index') }}" class="btn btn-success">Shipments Home</a><br>
<div class="panel-heading"><h1>Booked Shipments </h1> </div>
{!! Form::open(['action' => 'TxnsController@assignpickupShipments', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="input-group">
    <span class="input-group-addon" >Rider Name *</span>
    {{Form::select('rider_id', ['' => ''] + $riders, old('rider_id'), ['class' => 'form-control', 'id' => 'rider_id'])}}
</div>
<br>
{{Form::submit('Assign to Rider for Collection', ['class'=>'btn btn-primary', 'id' => 'submit-btn'])}}
<br><br>
@if(count($txns) > 0)
<?php
	$colcount = count($txns);
	$i = 1;
?>
<table class="table table-striped" >
    <tr>
    	<th></th>
        <th width="10.33%">Sender Company</th>
        <th width="9.33%">AWB#</th>
        <th width="13.33%">Origin</th>
        <th width="13.33%">Destination</th>
        <th width="8.33%">Parcel Type</th>
        <th width="8.33%">Rider</th>
        <th width="8.33%">Mode</th>
        <th width="8.33%">Parcel Status</th>         
        <th width="11.33%">Date/Time Created</th>
    </tr>
    @foreach($txns as $txn)
    <tr>
    	<th><input type="checkbox" name="txn_id[]" value="{{$txn['id']}}"></th>
        <td>{{$txn['sender_company_name']}}</td>
        <td>{{$txn['awb_num']}}</td>
        <td>{{$txn['origin_addr']}}</td>
        <td>{{$txn['dest_addr']}}</td>
        <td>{{$txn['parcel_type']['name']}}</td>
        <td>{{$txn['driver']['fullname']}}</td>
        @if ($txn['mode'] == 0)
        <td>Normal</td>
        @else ($txn['mode'] == 1)
        <td>Express</td>
        @endif
        <td>{{$txn['parcel_status']['name']}}</td>
        <td>{{$txn['created_at']}}</td>
    </tr>
      @endforeach
</table>


@else
	<p>No Transactions To Display</p>
@endif
{!! Form::close() !!}



<!-- Ajax test -->
{{ csrf_field() }}
  <div class="table-responsive text-center">
    <table class="table table-striped" >
    <tr>
        <th width="10.33%">Sender Company</th>
        <th width="9.33%">AWB#</th>
        <th width="13.33%">Origin</th>
        <th width="13.33%">Destination</th>
        <th width="8.33%">Parcel Type</th>
        <th width="8.33%">Mode</th>
        <th width="8.33%">Parcel Status</th>         
        <th width="11.33%">Date/Time Created</th>
        <th></th>
    </tr>
    @foreach($txns as $txn)
    
        <tr>
        
            <td>{{$txn['sender_company_name']}}</td>
            <td id="awb_num">{{$txn['awb_num']}}</td>
            <td>{{$txn['origin_addr']}}</td>
            <td>{{$txn['dest_addr']}}</td>
            <td>{{$txn['parcel_type']['name']}}</td>
            @if ($txn['mode'] == 0)
            <td>Normal</td>
            @else ($txn['mode'] == 1)
            <td>Express</td>
            @endif
            <td>{{$txn['parcel_status']['name']}}</td>
            <td>{{$txn['created_at']}}</td>
            <!-- <td>{{Form::select('driver_id', ['' => ''] + $riders, '', ['class' => 'form-control'])}}</td> -->
            <td><button class="btn btn-primary" class="edit-modal btn btn-info" data-id="{{$txn->id}}"
                data-name="{{$txn->origin_addr}}">Assign Rider</button></td>
        
        </tr>

      @endforeach
    </table>
  </div>

  <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <label class="control-label col-sm-2" for="id">ID:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="fid" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="name">Name:</label>
                <div class="col-sm-10">
                  <input type="name" class="form-control" id="n">
                </div>
              </div>
            </form>
            <div class="modal-footer">
              <button type="button" class="btn actionBtn" data-dismiss="modal">
                <span id="footer_action_button" class='glyphicon'> </span>
              </button>
              <button type="button" class="btn btn-warning" data-dismiss="modal">
                <span class='glyphicon glyphicon-remove'></span> Close
              </button>
            </div>
          </div>
        </div>
      </div>

<script>
 jQuery(document).ready(function(){

    $(document).on('click', '.edit-modal', function() {
        $('#footer_action_button').text("Update");
        $('#footer_action_button').addClass('glyphicon-check');
        $('#footer_action_button').removeClass('glyphicon-trash');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('edit');
        $('.modal-title').text('Edit');
        $('.deleteContent').hide();
        $('.form-horizontal').show();
        $('#fid').val($(this).data('id'));
        $('#n').val($(this).data('name'));
        $('#myModal').modal('show');
    });

    $('.modal-footer').on('click', '.edit', function() {

        $.ajax({
            type: 'post',
            url: '/shipments/assignpickup',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $("#fid").val(),
                'name': $('#n').val()
            },
            success: function(data) {
                $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "' ><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
            }
        });
    });

    jQuery('#ajaxSubmit').click(function(e){
       e.preventDefault();
       $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });
       jQuery.ajax({
          url: "{{ url('/shipments/assignpickup') }}",
          method: 'post',
          data: {
             name: jQuery('#driver_id').val(),
             name: jQuery('#awb_num').val()
          },
          success: function(result){
             jQuery('.alert').show();
             jQuery('.alert').html(result.success);
          }});
       });
    });
</script>

@endsection

