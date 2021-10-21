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

    @yield('after_styles')
    @stack('after_styles')

    <style type="text/css">
        html {
            font-size:16px;
        }

    	/**********chat-messages************/
    	.box.box-success.direct-chat.direct-chat-success{max-width: 700px;}
    	    .direct-chat-messages {
            padding: 30px;
            height: 550px;
        	    /*background: #fbfcfd;*/
        }
        .direct-chat-name {
            font-weight: 600;
            background:#eceff2;
            width: 35px;
            height: 35px;
            text-align: center;
            border-radius: 100px;
            line-height: 35px;
            text-transform: uppercase;
            font-size: 14px;
        }
        .direct-chat-timestamp {
            color: #737373;
            font-size: 14px;
            padding-top: 7px;
        	    font-weight: 400;
        }

        textarea.form-control{
            border: 1px solid #e3e3e3;
            border-radius: 0;
            color: #000000;
            font-size: 14px;
            height:100px;
            box-shadow:0 2px 0 #e3e3e3 !important;
        	padding:10px 15px;
           font-size: 15px;
        }
        .direct-chat-success .right>.direct-chat-text {
            background: #eceff2;
            border-color:#eceff2;
            color: #000;
        	font-weight: 400;
        }
        .direct-chat-success .right>.direct-chat-text:after, .direct-chat-success .right>.direct-chat-text:before {
            border-left-color:#eceff2;
        }
        .box-footer {
            padding:20px 30px;background: #fbfcfd;
        }
        .box-footer form{
        }
        .file-caption.form-control.kv-fileinput-caption {
            padding: 0;
        }
        input.file-caption-name {
            border: none;
            width: 100%;
            height: 32px;
            padding: 0 10px;
        }
        .direct-chat-text a {
            color: #000;
        }
        .direct-chat-success .btn.btn-success.btn-flat {
            font-size: 18px;
            font-weight: 400;
            text-transform: uppercase;
            padding: 8px 20px 6px;
            border-bottom: solid 3px #0ba6cb !important;
            border-radius: 4px;
            letter-spacing: 1px;
            background: #00c0ef;
            border: none;
        }
        .direct-chat-success .btn-primary{background: #00c0ef;border-color: #04a9d2;}
        a.ticket-file {
            color: #00c0ef;
        }

		.direct-chat .ticket-file {
			font-size: 21px;
			display: block;
		}

		.direct-chat .ticket-file span.filename {
			font-size: 16px;
		}

        /**********chat-messages-end************/


        .main-header .logo{ height:auto;}
        .main-header .navbar{ height:50px;}
        .pagination > .active > a{
            color: #fff;
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
            border-color: {{ ($company->theme_color != null)?$company->theme_color:"#d73925" }};
        }

        .pagination > .active > a:hover{
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
            border-color: {{ ($company->theme_color != null)?$company->theme_color:"#d73925" }};
        }
        .skin-red-light .main-header .logo {
            width: 230px;
            padding: 5px;
            text-align: center;
            background: none;
        }

        .skin-red-light .main-header .logo:hover{
            background: none;
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

        .navbar-nav > .user-menu .user-image {
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
        }

        .skin-red-light .main-header .navbar {
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
        }

        .nav-tabs-custom > .nav-tabs > li.active {
            border-top-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
        }

        .skin-red-light .main-header .navbar .sidebar-toggle:hover {
            background-color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
        }

        .skin-red-light .main-header .logo img {
            width: auto;
            vertical-align: top;
            /*height: 100%;*/ max-width: 100%;
        }

        .dis-block {
            display: block;
        }
        .navbar-nav > .user-menu a {
            padding: 8px 12px;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-align: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }
        .navbar-nav > .user-menu .user-image {
            width: 34px;
            height: 34px;
            background-color: #fff;
            color: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }};
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-align: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
            justify-content: space-around;
            font-weight: bold;
            font-size: 16px;
            margin-top: 0;
        }

        .navbar-nav > .user-menu .user-image small {
            font-size: 100%;
        }
        .navbar-nav > .user-menu a .right-user-data {
            max-width: 100px;
            line-height: normal;
        }
        .navbar-nav > .user-menu a .right-user-data span {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            font-weight: bold;
        }
        .navbar-nav > .user-menu a .right-user-data .company-name {
            font-size: 12px;
            font-weight: normal;
        }
        .navbar-nav > .user-menu .dropdown-menu {
            width: 100%;
            padding: 10px;
        }
        .navbar-nav > .user-menu .dropdown-menu li a {
            padding: 2px 10px;
        }

        .dropdown.user.user-menu > a:hover {
            background: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }} !important;
        }

		.dropdown.user.user-menu > a:focus {
            background: {{ ($company->theme_color != null)?$company->theme_color:"#dd4b39" }} !important;
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

        @media (max-width: 767px){

            .logo{ width: 100% !important; background-color: #f9fafc !important;}
            .logo img{ width: 200px !important;  display: inline-block; }

            .navbar-custom-menu .navbar-nav > li > a {
                padding-top: 8px;
                padding-bottom: 8px;
            }

            .navbar-custom-menu .navbar-nav li .dropdown-menu
            {
                right: 0px;
            }
            .navbar-custom-menu .navbar-nav li .dropdown-menu li a{ color: #333 !important; }
            .navbar-custom-menu .navbar-nav li .dropdown-menu li a:hover{
                background:#f1f1f1 !important;  color: #333 !important;
            }
        }

        @if(\Request::is('admin/*'))
            .main-sidebar {
                padding-top: 133px;
            }
        @else
            .main-sidebar {
                padding-top: 88px;
            }
        @endif


    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('backpack.base.skin') }} sidebar-mini">
	<script type="text/javascript">
		/* Recover sidebar state */
		(function () {
			if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
				var body = document.getElementsByTagName('body')[0];
				body.className = body.className + ' sidebar-collapse';
			}
		})();
	</script>
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('backpack::base.toggle_navigation') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                @include('backpack::inc.menu')
            </nav>
        </header>

          <!-- =============================================== -->

          @include('backpack::inc.sidebar')

          <!-- =============================================== -->

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
        @yield('scripts')
    @yield('after_scripts')
    @stack('after_scripts')

    <!-- JavaScripts -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}

</body>
</html>
