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
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/bootstrap-datepicker.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <script type="text/javascript" src="{{ URL::to('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('js/bootstrap-datepicker.js') }}"></script>
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('inc.cusnavbar')
        <div class="container">
            @include('inc.messages')
            @yield('content')   
        </div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript">
        $('.date').datepicker({  
        format: 'yyyy-mm-dd'
        });
        $('.first_date').datepicker({  
        format: 'yyyy-mm-dd'
        }); 
        $('.last_date').datepicker({  
        format: 'yyyy-mm-dd'
        }); 
    </script> 
    <script type="text/javascript">
        $('.month').datepicker({  
        format: 'yyyy-mm',
        viewMode: "months", 
        minViewMode: "months"
        });  
    </script>
    <script>
      function checkfilter(str) {
        if(str==true) {
          jQuery('div#filteroptions').show();
        }
        else {
          jQuery('div#filteroptions').hide();
        }
      }
    </script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <footer id="footer">
    <!-- style = "background:#333333;color:#ffffff;text-align:center;padding:30px;margin-top:30px;" -->
        Developed by Avanet Technologies 2018    
    </footer>
</body>
</html>