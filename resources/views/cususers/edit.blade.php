@extends('layouts.cusapp')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit User Details <a 
                href="{{ route('cususers.index', ['id' => $company_id ]) }}"
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
                {!!Form::open(['action' => ['CustomersController@updateUser', $user->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('company_name', 'Company Name')}}
                    {{Form::text('company_name', $company_name, ['class' => 'form-control', 'placeholder' => 'Company Name', 'disabled' => 'true'])}}
                </div>
                <div class="form-group">
                    {{Form::label('username', 'Username')}}
                    {{Form::text('username', $user->username, ['class' => 'form-control', 'placeholder' => 'Username', 'disabled' => 'true'])}}
                </div>
                <div class="form-group">
                    {{Form::label('firstname', 'First Name')}}
                    {{Form::text('firstname', $user->firstname, ['class' => 'form-control', 'placeholder' => 'First Name'])}}
                </div>
                <div class="form-group">
                    {{Form::label('lastname', 'Last Name')}}
                    {{Form::text('lastname', $user->lastname, ['class' => 'form-control', 'placeholder' => 'Last Name'])}}
                </div>
                <div class="form-group">
                    {{Form::label('phone', 'Phone Number')}}
                    {{Form::text('phone', $user->phone, ['class' => 'form-control', 'placeholder' => 'Format example 254722000000'])}}
                </div>
                <div class="form-group">
                    {{Form::label('email', 'Email')}}
                    {{Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
                </div>
                <div class="form-group">
                    {{Form::label('station_id', 'Branch/Department')}}
                    {{Form::select('station_id', ['' => ''] + $stations, $user->station_id, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('status', 'Status')}}
                    {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], $user->status, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection