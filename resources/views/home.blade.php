<!DOCTYPE html>

<html dir="ltr" lang="en">

<head>

    <meta charset="utf-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Tell the browser to be responsive to screen width -->

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="keywords" content="Technician Management, System Management, Frontier Tech Services" />

    <meta name="description" content="Frontier Tech Services - Technician management and system management." />

    <meta name="robots" content="noindex,nofollow" />

    <title>Frontier Tech Services - Web Application to manage technicians</title>

    <link rel="canonical" href="{{route('home')}}" />

    <!-- Favicon icon -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/admin/assets/images/favicon.png') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom CSS -->



    <!-- Custom CSS -->

    <link href="{{ asset('public/admin/dist/css/style.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('public/admin/dist/css/custom.css') }}" rel="stylesheet" />

    @if (request()->routeIs('map'))
        <style>
            #navbarSupportedContent {
                background: #2962ff !important;
            }

            .nav-link {
                color: #FFF !important;
            }
        </style>
    @endif

<style>
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.container-fluid,
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.container-lg,
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.container-md,
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.container-sm,
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.container-xl,
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.container-xxl,
#main-wrapper[data-layout=vertical][data-boxed-layout=boxed] .page-wrapper>.page-breadcrumb {
    max-width: 100% !important;
    margin: 0 auto;
    position: relative
}

</style>
    <style>
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background-color: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #e0e0e0;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #c3c3c3;
        }

        ::-webkit-scrollbar-corner {
            background-color: #dfdfdf;
        }
 		 
		.preload_img {
			top: 20%;
			position: absolute;
			left: 40%;
			width: 20%;
		}
		.preload_img img {
			width: 100%;
		}

    </style>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

</head>



<body>

  
    <!-- -------------------------------------------------------------- -->

    <!-- Preloader - style you can find in spinners.css -->

    <!-- -------------------------------------------------------------- -->

    <div class="preloader">

		<!--
        <svg class="tea lds-ripple" width="37" height="48" viewbox="0 0 37 48" fill="none"
            xmlns="http://www.w3.org/2000/svg">

            <path
                d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z"
                stroke="#2962FF" stroke-width="2"></path>

            <path
                d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34"
                stroke="#2962FF" stroke-width="2"></path>

            <path id="teabag" fill="#2962FF" fill-rule="evenodd" clip-rule="evenodd"
                d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z">
            </path>

            <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" stroke="#2962FF"></path>

            <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13" stroke="#2962FF"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>

        </svg>-->
		
		<div class="preload_img"><img src="https://gaffis.in/frontier/website/public/admin/assets/images/loading-loader2.gif" alt="Frontier Tech Services" /></div>

    </div>

    <!-- -------------------------------------------------------------- -->

    <!-- Main wrapper - style you can find in pages.scss -->

    <!-- -------------------------------------------------------------- -->

    <div id="main-wrapper"
        @if (request()->routeIs('map')) data-theme="light" data-layout="vertical" data-navbarbg="skin1" data-sidebartype="mini-sidebar" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full" class="mini-sidebar" @endif>

        <!-- -------------------------------------------------------------- -->

        <!-- Topbar header - style you can find in pages.scss -->

        <!-- -------------------------------------------------------------- -->

        @include('admin.header')

        <!-- -------------------------------------------------------------- -->

        <!-- End Topbar header -->

        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->

        <!-- Left Sidebar - style you can find in sidebar.scss  -->

        <!-- -------------------------------------------------------------- -->

        @php

            $currentRoute = Route::current();

            $routeUri = $currentRoute->uri();

            $routeSegments = explode('/', $routeUri);

            $prefix = isset($routeSegments[0]) && !empty($routeSegments[0]) ? $routeSegments[0] : '';

        @endphp

        @if ($prefix == 'setting')
            @include('admin.setting_sidebar')
        @elseif ($prefix == 'schedule')
            @include('schedule.sidebar')
        @else
            @include('admin.sidebar')
        @endif


        <!-- -------------------------------------------------------------- -->

        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->

        <!-- Page wrapper  -->

        <!-- -------------------------------------------------------------- -->

        <div class="page-wrapper">

            <!-- -------------------------------------------------------------- -->

            <!-- Bread crumb and right sidebar toggle -->

            <!-- -------------------------------------------------------------- -->

            @yield('content')

            <!-- -------------------------------------------------------------- -->

            <!-- End Container fluid  -->

            <!-- -------------------------------------------------------------- -->

            <!-- -------------------------------------------------------------- -->

            <!-- footer -->

            <!-- -------------------------------------------------------------- -->

            @include('admin.footer')



            <!-- -------------------------------------------------------------- -->

            <!-- End footer -->

            <!-- -------------------------------------------------------------- -->

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- End Page wrapper  -->

        <!-- -------------------------------------------------------------- -->

    </div>

    <!-- -------------------------------------------------------------- -->

    <!-- End Wrapper -->

    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->

    <!-- customizer Panel -->

    <!-- -------------------------------------------------------------- -->

    @include('admin.costomizer')

    <div class="chat-windows"></div>



    @include('admin.script')
    @yield('script')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                // Trigger click event on the element with class .sidebartoggler
                $('.sidebartoggler').click();
            }); // Adjust the delay time as needed
        });
    </script>

</body>

</html>
