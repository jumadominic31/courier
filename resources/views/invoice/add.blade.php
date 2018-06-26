@extends('layouts.app')

@section('content')

<div class="container"> 
    <h1>Create Invoice</h1>
    <a href="{{ route('invoice.index') }}" class="btn btn-success">Go back</a><br><br>
</div>
<div>
    <div class="input-group">
        <span class="input-group-addon" >Sender Company *</span>
        {{Form::select('sender_company_id', ['' => ''] + $cuscompanies, '', ['class' => 'form-control'])}}
    </div> <br>
</div>

{!! Form::open(['action' => 'InvoicesController@storeInvoice', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div>
    <table class="table table-striped" id="seltxns">
        
    </table>
</div>
<div class="row">
	<div class="col-md-12 text-center"> 
	    {{Form::submit('Submit', ['class'=>'btn btn-primary btn-xl disabled', 'id' => 'submit-btn'])}}
	</div>
</div>
{!! Form::close() !!}

<script type="text/javascript">
jQuery(document).ready(function($) {
    // $('.txns').on( 'click', function() {
    //     var checked_txns = $( '.txns:checked' ).length;

    //     console.log(checked_txns);
    //     if($( '.txns:checked' ).length > 0) {
    //         $('#submit-btn').prop('disabled', false);
    //         alert('enabled');
    //     }
    //     else {
    //         $('#submit-btn').prop('disabled', true);
    //         alert('disabled');
    //     }  
    // });

    $('select[name="sender_company_id"]').on('change', function() {
        var sender_company_id = this.value;
        $.get("/invoice/seltxns/"+sender_company_id, function(response){
            console.log(response.length);
            $('#seltxns').empty();
            if (response.length == 0){
                $('#seltxns').append('No transactions');
                $('#submit-btn').prop('disabled', true);
            }
            else {
                $('#submit-btn').prop('disabled', false);
                var thead = $('<tr>').append(
                        $('<th width="10.33%">').text("Sender Company"),
                        $('<th width="9.33%">').text("AWB #"),
                        $('<th width="13.33%">').text("Origin"),
                        $('<th width="13.33%">').text("Destination"),
                        $('<th width="8.33%">').text("Parcel Type"),
                        $('<th width="4.33%">').text("Price"),
                        $('<th width="4.33%">').text("VAT"),
                        $('<th width="8.33%">').text("Mode"),
                        $('<th width="8.33%">').text("Parcel Status"),
                        $('<th width="11.33%">').text("Date/Time Created"),
                        $('<th width="3.33%">').text("Invoiced"),
                        $('<th>').text("")
                    ).appendTo('#seltxns');
                $.each(response, function(i, item) {
                    var $tr = $('<tr>').append(
                        $('<td width="10.33%">').text(item.sender_company_name),
                        $('<td width="9.33%">').text(item.awb_num),
                        $('<td width="13.33%">').text(item.origin_addr),
                        $('<td width="13.33%">').text(item.dest_addr),
                        $('<td width="8.33%">').text(item.parcel_type),
                        $('<td width="4.33%">').text(item.price),
                        $('<td width="4.33%">').text(item.vat),
                        $('<td width="8.33%">').text(item.mode),
                        $('<td width="8.33%">').text(item.parcel_status),
                        $('<td width="11.33%">').text(item.created_at),
                        $('<td width="3.33%">').text(item.invoiced),
                        $('<td>').append(
                            $('<input />', { type: 'checkbox', name: 'txn_id[]', class: 'txns', value: item.id }))
                    ).appendTo('#seltxns');
                    // console.log($tr.wrap('<p>').html());
                });
            }
        });
        
    });

    if ($("#seltxns input:checkbox:checked").length > 0)
    {
        console.log('yes');
    }
    else
    {
       console.log('no');
    }

});
</script>

@endsection