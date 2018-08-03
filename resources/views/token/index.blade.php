@extends('layouts.app')

@section('content')

<a href="{{ route('token.addToken') }}" class="btn btn-primary">Add Tokens</a><br>
<div class="panel-heading"><h1> Token Statement </h1> </div>
<hr>
<h3>Token Balance: {{$token_bal}} </h3>

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
                <td>{{$token->sender_company_name}}</td>
                <td>{{$token->balance}}</td>
            </tr>
            @endforeach
        </table>
        
    @else
      <p>No tokens To Display</p>
    @endif

@endsection