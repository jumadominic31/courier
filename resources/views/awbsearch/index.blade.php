<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CourierApp') }}</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/bootstrap-datepicker.css') }}">
    <script type="text/javascript" src="{{ URL::to('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('js/bootstrap-datepicker.js') }}"></script>
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('users.signin') }}">Login</a></li>
            </ul>
        </div>
    </nav>
    <div id="app">
        <div class="container"> 
            <h1>AWB Search</h1>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! Form::open(['action' => 'TxnsController@getAwbsearch', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="input-group">
                        <span class="input-group-addon" >AWB Number</span>
                        <input type="text" id="awb_num" name="awb_num" value="" class="form-control" placeholder="AWB Num" aria-describedby="basic-addon1">
                    </div>
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <br>
        @if(count($txn) > 0)
        <?php
            $colcount = count($txn);
            $i = 1;
        ?>
        <div class="container"> 
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">AWB</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->awb_num}} <br>
                            {{$txn[0]->parcel_type_name}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Origin</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->origin_name}}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Destination</h3>
                        </div>
                        <div class="panel-body">
                            {{$txn[0]->dest_name}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Shipment Details</div>
                <table class="table">
                    <tr>
                        <th>Date/Time</th>
                        <th>Status</th>
                    </tr>
                    @foreach($statusDet as $stDet)
                    <tr>
                        <td>{{$stDet->updated_at}}</td>
                        <td>{{$stDet->description}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @else
            <div class="container"> 
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Enter a valid AWB</h4>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <footer id="footer">
    <!-- style = "background:#333333;color:#ffffff;text-align:center;padding:30px;margin-top:30px;" -->
        Developed by Avanet Technologies 2017    
    </footer>
</body>
</html>