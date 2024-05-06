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

    <title>{{ $siteSettings->business_name ?? null }} - Web Application to manage technicians</title>

    <link rel="canonical" href="{{ route('home') }}" />

    <!-- Favicon icon -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/admin/assets/images/favicon.png') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom CSS -->



    <!-- Custom CSS -->

    <link href="{{ asset('public/admin/dist/css/style.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('public/admin/dist/css/custom.css') }}" rel="stylesheet" />

    <link href="{{ asset('public/admin/dist/libs/select2/dist/css/select2.min.css') }}" rel="stylesheet" />

	
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
			display: none;
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

    <div class="preloader" >
 

        <div class="preload_img"><img
                src="https://gaffis.in/frontier/website/public/admin/assets/images/loading-loader2.gif"
                alt="Frontier Tech Services" /></div>

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

    <script>
        function updateNotification(userId, noticeId) {
            // Get CSRF token from the page's meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Make AJAX call to update value
            $.ajax({
                url: '{{ route('update.notification') }}',
                method: 'POST',
                data: {
                    user_id: userId,
                    notice_id: noticeId,
                    _token: csrfToken // Include CSRF token in the data
                },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    </script>


</body>

</html>
