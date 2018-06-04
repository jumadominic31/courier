@extends('layouts.app')

@section('content')

<div class="container"> 
    <h1>Create Invoice</h1>
</div>
<div>
    <div class="input-group">
        <span class="input-group-addon" >Sender Company *</span>
        {{Form::select('sender_company_id', ['' => ''] + $cuscompanies, '', ['class' => 'form-control'])}}
    </div>
</div>
{!! Form::open(['action' => 'InvoicesController@storeInvoice', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="container">
    @if(count($txns) > 0)
        <?php
            $colcount = count($txns);
            $i = 1;
        ?>
        Up to 50 records
        <table class="table table-striped" id="seltxns">
            <tr>
                <th width="10.33%">Sender Company</th>
                <th width="9.33%">AWB#</th>
                <th width="13.33%">Origin</th>
                <th width="13.33%">Destination</th>
                <th width="8.33%">Parcel Type</th>
                <th width="4.33%">Price</th>
                <th width="4.33%">VAT</th>
                <th width="8.33%">Mode</th>
                <th width="8.33%">Parcel Status</th>         
                <th width="11.33%">Date/Time Created</th>
                <th width="3.33%">Invoiced</th>
                <th></th>
            </tr>
            @foreach($txns as $txn)
              <tr>
                <td>{{$txn['sender_company_name']}}</td>
                <td>{{$txn['awb_num']}}</td>
                <td>{{$txn['origin_addr']}}</td>
                <td>{{$txn['dest_addr']}}</td>
                <td>{{$txn['parcel_type']['name']}}</td>
                <td>{{$txn['price']}}</td>
                <td>{{$txn['vat']}}</td>
                @if ($txn['mode'] == 0)
                <td>Normal</td>
                @else ($txn['mode'] == 1)
                <td>Express</td>
                @endif
                <td>{{$txn['parcel_status']['name']}}</td>
                <td>{{$txn['created_at']}}</td>
                @if ($txn['invoiced'] == 0)
                <td>No</td>
                @else ($txn['invoiced'] == 1)
                <td>Yes</td>
                @endif
                <th><input type="checkbox" name="txn_id[]" value="{{$txn['id']}}"></th>
            </tr>
            @endforeach
        </table>
    @else
        <p>No Transactions To Display</p>
    @endif
</div>
<div class="row">
	<div class="col-md-12 text-center"> 
	    {{Form::submit('Submit', ['class'=>'btn btn-primary btn-xl'])}}
	</div>
</div>
{!! Form::close() !!}

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('select[name="sender_company_id"]').on('change', function() {
        var sender_company_id = this.value;
        $.get("/invoice/seltxns/"+sender_company_id, function(data){
            $('#seltxns').empty();
            $.each(data, function(index, ownerObj){
                $('#seltxns').append('<tr><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td></tr>');
            });
        });
        
    });
});
</script>

@endsection