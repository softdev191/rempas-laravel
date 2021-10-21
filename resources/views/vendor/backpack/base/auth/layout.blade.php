<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ isset($title) ? $title.' :: '.$company->name : $company->name }}
    </title>

    @yield('before_styles')
    @stack('before_styles')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/backpack/backpack.base.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/overlays/backpack.bold.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">

    <style type="text/css">
        html {
            font-size:16px;
        }
        .login-col {
            max-width: 400px;
            width: 100%;
            margin: auto;
            webkit-box-shadow: 0 0 1px 1px rgba(0,0,0,.3);
            box-shadow: 0 0 1px 1px rgba(0,0,0,.3);
            border-radius: 3px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
        }
        .login-col .box{
            border:none;
            border-radius: 3px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            padding: 10px;
        }
        .login-col .box-header{padding: 0;}
        .login-col .box-header .logo-admin {
            display: inline-block;padding: 10px 40px;
            background: #fff;
            vertical-align: top;
            text-align: center;
            margin: -10px -10px 0px -10px;
        }
        .login-col .box-header .logo-admin img {
            display: inline-block;
            max-width: 100%;
        }
        .login-col .box-header .box-title {
            display: inline-block;
            width: 100%;
            vertical-align: top;
            text-align: center;
            margin:0 0 20px;
            font-size: 22px;
            font-weight: 700;
            line-height: 26px;
        }
        .login-col .btn{display: block; width: 100%;}
        .login-col .form-group:last-child{margin-bottom: 0;}

        .login-col .login-title {
            font-size: 22px;
            font-weight: bold;
            display: table;
            border-bottom: 3px solid #000;
            margin-bottom: 13px;
            padding-bottom: 3px;
        }
        .btn-danger,.btn-danger:visited, .btn-danger:hover, .btn-danger:active, .btn-danger:focus {
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
            border-color: {{ ($company->theme_color != null)?$company->theme_color:"#d73925" }};
        }

        .btn-success,.btn-success:visited, .btn-success:hover, .btn-success:active, .btn-success:focus{
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
            border-color: {{ ($company->theme_color != null)?$company->theme_color:"#d73925" }};
        }

        .btn-primary,.btn-primary:visited, .btn-primary:hover, .btn-primary:active, .btn-primary:focus{
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
            border-color: {{ ($company->theme_color != null)?$company->theme_color:"#d73925" }};
        }

		.envelope, .envelope-open {
			background-color: {{ ($company->theme_color != null)?$company->theme_color:"#000" }} !important;
			color: #fff;
			padding: 0px 6px 3px 6px;
			display: inline-block;
			vertical-align: middle;
			border-radius: 3px;
		}
		.envelope i, .envelope-open i {
			font-size: 11px;
		}

		.sidebar-menu .bg-blue {
			background-color: {{ ($company->theme_color != null)?$company->theme_color:"#0073b7" }} !important;
		}

        .login-container {
            width: 1000px;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
        }
        .col-left { width: 40%; border-right: 1px solid #455a64; }
        .col-right { width: 60%; }
        .col-left-browser { width: 30%; border-right: 1px solid #455a64; }
        .col-right-browser { width: 70%; }
        .login-container .box {
            height: 100%;
        }
        .login-title {
            font-size: 24px;
            font-weight: bold;
        }
        .car-title {
            font-size: 30px;
            font-weight: bold;
        }
        .button-container .btn {
            width: 200px;
            margin: 5px;
            float: right;
        }
        .login-container .box-body {
            padding: 20px 40px;
        }
        .login-container .box {
            margin-bottom: 0;
        }
        .logo-admin {
            text-align: center;
        }
        .logo-admin img {
            max-width: 100%;
        }
        .reg-box ul {
            margin-bottom: 20px;
        }
        .reg-box li {
            margin-bottom: 10px;
        }
        @media (max-width: 1100px) {
            .login-container {
                margin-left: 24px;
                margin-right: 24px;
            }
        }
        @media (max-width: 700px) {
            .col-left { width: 100% !important; border-right: 0px solid #455a64; }
            .col-right { width: 100% !important; }
            .col-left-browser { width: 100% !important; border-right: 0px solid #455a64; }
            .col-right-browser { width: 100% !important; }
        }

    </style>

    @yield('after_styles')
    @stack('after_styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('backpack.base.skin') }} pace-done sidebar-collapse">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            {{--
            <!-- Logo -->
            <a href="{{ url('admin') }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('backpack.base.logo_mini') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('backpack.base.logo_lg') !!}</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">

            </nav>
            --}}
        </header>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
                @yield('header')

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            {{ $company->copy_right_text }}
        </footer>

    </div>
    <!-- ./wrapper -->

    @yield('before_scripts')
    @stack('before_scripts')

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('vendor/adminlte') }}/plugins/jQuery/jQuery-2.2.3.min.js"><\/script>')</script> --}}
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    {{-- <script src="{{ asset('vendor/adminlte') }}/bower_components/fastclick/lib/fastclick.js"></script> --}}
    <script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.min.js"></script>

    <!-- page script -->
    <script type="text/javascript">
        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
          event.preventDefault();
          if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
          } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
          }
        });
        // To make Pace works on Ajax calls
        $(document).ajaxStart(function() { Pace.restart(); });

        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        // Set active state on menu element
        var current_url = "{{ Request::fullUrl() }}";
        var full_url = current_url+location.search;
        var $navLinks = $("ul.sidebar-menu li a");
        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        // If not found, look for the link that starts with the url
        if(!$curentPageLink.length > 0){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')); }
            );
        }

        $curentPageLink.parents('li').addClass('active');
        {{-- Enable deep link to tab --}}
        var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
        location.hash && activeTab && activeTab.tab('show');
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            location.hash = e.target.hash.replace("#tab_", "#");
        });
    </script>

    @include('backpack::inc.alerts')

    @yield('after_scripts')
    @stack('after_scripts')

    <!-- JavaScripts -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
</body>
</html>
