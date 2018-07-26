<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Branding Image -->
            <a class="navbar-brand" 
                href="{{ route('dashboard.customer') }}">
                Elite Riders Limited
            </a>
            
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <ul class="nav navbar-nav">
                <li><a href="{{ route('dashboard.customer') }}">Dashboard</a></li>
                <!-- <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->usertype != 'cusclerk')
                        <li><a href="{{ route('portal.users.index') }}">Users</a></li>
                        @endif
                        <li><a href="{{ route('portal.rates.index') }}">Rates</a></li>
                        <li><a href="{{ route('portal.parcel.index') }}">Parcels</a></li>
                     </ul>
                </li> -->
                
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reporting <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('portal.shipments.index') }}">Shipments</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('portal.shipments.add') }}">Book Shipment</a></li>
                <li><a href="{{ route('portal.shipments.awb') }}">AWB Search</a></li>
                
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <a class="navbar-brand" 
                    href="{{ route('dashboard.customer') }}">
                    {{session('courier.companyname') }}
                </a>
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('users.signin') }}">Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i> <?php echo Auth::user()->firstname;  ?> <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::check())
                                <li><a href="{{ route('portal.users.profile') }}">Profile</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('users.logout') }}">Logout</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>