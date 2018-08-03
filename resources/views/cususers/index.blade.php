@extends('layouts.app')

@section('content')
<div class="panel-heading">
<h1> Customer User Administration for {{$company_name}} </h1>
    <a href="{{ route('customer.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a>
    <a href="{{ route('cususers.create') }}" class="btn btn-success">Add User</a>
<br>
</div>
    @if(count($users) > 0)
        <?php
            $colcount = count($users);
            $i = 1;
        ?>
        
        <table class="table table-striped" >
            <tr>
                <th>User Name</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Home Station</th>
                <th>User Type</th>
                <th>Company</th>
                <th>Status</th>
                <!-- <th></th> -->
                <th></th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{$user['username']}}</td>
                <td>{{$user['fullname']}}</td>
                <td>{{$user['phone']}}</td>
                <td>{{$user['email']}}</td>
                <td>{{$user['station']['name']}}</td>
                <td>{{$user['usertype']}}</td>
                <td>{{$user['company']['name']}}</td>
                <td><?php if ($user['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
                <td><span class="center-block">
                    {!!Form::open(['action' => ['CustomersController@cususerdestroy', $user->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
                      {{Form::hidden('_method', 'DELETE')}}
                      {{Form::submit('Delete', ['class' => 'btn btn-danger btn-xs'])}}
                    {!! Form::close() !!}
                </span></td>
            </tr>
            @endforeach
        </table>
    @else
      <p>No users To Display</p>
    @endif
@endsection