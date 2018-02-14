@extends('layouts.app')

@section('content')
    <h1>Create Vehicle</h1>
    <a href="{{ route('vehicle.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'VehiclesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Vehicle Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Vehicle Name'])}}
        </div>
        @if(Auth::user()->usertype == 'superadmin')
            <?php
                $companies[0] = 'Select Company';
            ?>
            <div class="form-group">
                {{Form::label('company_id', 'Company Name')}}
                {{Form::select('company_id', $companies, '', ['class' => 'form-control'])}}
            </div>
       	@endif
        <div class="form-group">
            {{Form::label('status', 'Vehicle Active Status')}}
            {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control', 'placeholder' => 'Vehicle Active Status'])}}
        </div>

        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}

@if(Auth::user()->usertype == 'superadmin')    
<script>
    $('#company_id').on('change', '', function(e){
        var company_id = e.target.value;
        console.log(company_id);
        $.get("{{ route('user.getowners') }}"+company_id, function(data){
            $('#user_id').empty();
            $.each(data, function(index, ownerObj){
                $('#user_id').append('<option value="'+ownerObj.id+'">'+ownerObj.fullname+'</option>');
            });
        });
    });

</script>
@endif

@endsection