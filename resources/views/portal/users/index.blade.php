@extends('layouts.cusapp')

@section('content')
<div class="panel-heading">
<h1> Customer User Administration </h1>
    <a href="{{ route('portal.users.create') }}" class="btn btn-success">Add User</a>
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
            </tr>
            @foreach($users as $user)
            <tr class='clickable-row' data-href="{{ route('portal.users.edit', ['user' => $user->id ]) }}">
                <td>{{$user['username']}}</td>
                <td>{{$user['fullname']}}</td>
                <td>{{$user['phone']}}</td>
                <td>{{$user['email']}}</td>
                <td>{{$user['station']['name']}}</td>
                <td>{{$user['usertype']}}</td>
                <td>{{$user['company']['name']}}</td>
                <td><?php if ($user['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
            </tr>
            @endforeach
        </table>
    @else
      <p>No users To Display</p>
    @endif

<script type="text/javascript">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
@endsection