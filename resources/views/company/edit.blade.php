@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Company <a 
                @if (Auth::user()->usertype == 'superadmin') 
                    href="{{ route('company.index') }}" 
                @else
                    href="{{ route('users.profile') }}"
                @endif
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
                {!! Form::open(['action' => ['CompaniesController@update', $company->id],'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-group">
                        {{Form::label('name', 'Company Name')}}
                        {{Form::text('name', $company->name, ['class' => 'form-control', 'placeholder' => 'Company Name'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('address', 'Company Address')}}
                        {{Form::text('address', $company->address, ['class' => 'form-control', 'placeholder' => 'Company Address'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('city', 'City')}}
                        {{Form::text('city', $company->city, ['class' => 'form-control', 'placeholder' => 'City'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('pin', 'PIN Number')}}
                        {{Form::text('pin', $company->pin, ['class' => 'form-control', 'placeholder' => 'PIN Number'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('phone', 'Phone Number')}}
                        {{Form::text('phone', $company->phone, ['class' => 'form-control', 'placeholder' => 'Phone Number'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('email', 'Email Address')}}
                        {{Form::text('email', $company->email, ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('logo', 'Logo Image')}}
                        {{Form::file('logo')}}
                    </div>
                    @if (Auth::user()->usertype == 'superadmin')
                    <div class="form-group">
                        {{Form::label('status', 'Company Active Status')}}
                        {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], $company->status, ['class' => 'form-control', 'placeholder' => 'Company Active Status'])}}
                    </div>
                    @endif
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection