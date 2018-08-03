@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Customers Administration </h1> 

    <a href="{{ route('customer.create') }}" class="btn btn-success">Add customer</a>
<br>
</div>
    @if(count($customers) > 0)
      <?php
        $colcount = count($customers);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Customer Name</th>
		          <th>Address</th>
		          <th>City</th>
		          <th>PIN</th>
		          <th>Phone</th>
		          <th>Email</th>
		          <th>Status</th>
		          <th></th>
		          <th></th>
		          <th></th>
	          </tr>
	          @foreach($customers as $customer)
	          <tr>
		          <td>{{$customer['name']}}</td>
		          <td>{{$customer['address']}}</td>
		          <td>{{$customer['city']}}</td>
		          <td>{{$customer['pin']}}</td>
		          <td>{{$customer['phone']}}</td>
		          <td>{{$customer['email']}}</td>
		          <td><?php if ($customer['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('cususers.index', ['customer' => $customer->id ]) }}">Users</a></span></td>
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('customer.edit', ['customer' => $customer->id ]) }}">Edit</a></span></td>
		          <td><span class="center-block">
		          </span><a class="pull-right btn btn-default btn-xs" href="#">Branches</a></td>
		     </tr>
	          @endforeach
          </table>

    @else
      <p>No customer To Display</p>
    @endif
@endsection