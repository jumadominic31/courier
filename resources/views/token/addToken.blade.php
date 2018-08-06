@extends('layouts.app')

@section('content')


<h1>Token Administration</h1>

<br>
    {!! Form::open(['action' => 'TokensController@storeToken', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('sender_company_id', 'Company Name')}}
            {{Form::select('sender_company_id', ['' => ''] + $companies, '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('amount', 'Number of Tokens')}}
            {{Form::text('amount', '', ['class' => 'form-control', 'placeholder' => 'Number of Tokens Purchased'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}

<hr>
    @if(count($tokens) > 0)
        <?php
            $colcount = count($tokens);
            $i = 1;
        ?>
        
        <table class="table table-striped" >
            <tr>
                <th>Company Name</th>
                <th>Balance</th>
            </tr>
            @foreach($tokens as $token)
            <tr>
                <td>{{$token['sender_company']['name']}}</td>
                <td>{{$token['balance']}}</td>
            </tr>
            @endforeach
        </table>
        
    @else
      <p>No tokens To Display</p>
    @endif
@endsection