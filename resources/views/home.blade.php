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

    <!-- schedule Custom CSS -->


    <link rel="stylesheet" href="{{ url('public/admin/schedule/style.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/schedule/custom.css') }}">



    <!-- Custom CSS -->

    <link href="{{ asset('public/admin/dist/css/style.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('public/admin/dist/css/custom.css') }}" rel="stylesheet" />
 
    <link href="{{ asset('public/admin/dist/libs/select2/dist/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('public/admin/dist/libs/dragula/dist/dragula.min.js') }}" rel="stylesheet" />
    <link href="{{ asset('public/admin/dist/libs/prismjs/themes/prism-okaidia.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
        
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"> 

        

    @if (request()->query('sidebar') === 'off')
        <style>
            #main-wrapper[data-layout="vertical"][data-sidebartype="full"] .page-wrapper {
                margin-left: 0px;
            }
        </style>
    @endif


    <style>

    /*!-- for left side  bar --> */
      #main-wrapper[data-layout="vertical"][data-sidebartype="mini-sidebar"] .page-wrapper {
        margin-left: 0px !important;
    }

    /* .card-body {
                                                                                                                                                                                                                        padding: 0px !important;
   

    
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
      <style>
        #frontier_loader {
            display: none;
        }

        .select2 {
            width: 100% !important;
        }

        /* Dim background */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            /* Dim background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Ensure it's above other elements */
        }

        /* Loader styling */
        .loader {
            width: 50px;
            aspect-ratio: 1;
            display: grid;
            border-radius: 50%;
            background:
                linear-gradient(0deg, rgb(0 0 0 / 50%) 30%, #0000 0 70%, rgb(0 0 0 / 100%) 0) 50%/8% 100%,
                linear-gradient(90deg, rgb(0 0 0 / 25%) 30%, #0000 0 70%, rgb(0 0 0 / 75%) 0) 50%/100% 8%;
            background-repeat: no-repeat;
            animation: l23 1s infinite steps(12);

        }

        .loader::before,
        .loader::after {
            content: "";
            grid-area: 1/1;
            border-radius: 50%;
            background: inherit;
            opacity: 0.915;
            transform: rotate(30deg);
        }

        .loader::after {
            opacity: 0.83;
            transform: rotate(60deg);
        }

        @keyframes l23 {
            100% {
                transform: rotate(1turn)
            }
        }
    </style>
	
    <style>
aside.left-sidebar {
display: none;
}
@media (min-width: 768px) {
	#main-wrapper[data-layout=vertical][data-sidebar-position=fixed][data-sidebartype=full] .topbar .top-navbar .navbar-collapse, #main-wrapper[data-layout=vertical][data-sidebar-position=fixed][data-sidebartype=overlay] .topbar .top-navbar .navbar-collapse {
		margin-left: 0px;
	}
}
#main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
	margin-left: 0px;
}
    </style>


</head>



<body>
    @php

        $currentRoute = Route::current();

        $routeUri = $currentRoute->uri();

        $routeSegments = explode('/', $routeUri);

        $prefix = isset($routeSegments[0]) && !empty($routeSegments[0]) ? $routeSegments[0] : '';

    @endphp

    <!-- -------------------------------------------------------------- -->

    <!-- Preloader - style you can find in spinners.css -->

    <!-- -------------------------------------------------------------- -->

    <div class="preloader">


        <div class="preload_img"><img
                src="https://gaffis.in/frontier/website/public/admin/assets/images/loading-loader2.gif"
                alt="Frontier Tech Services" /></div>

    </div>
     <div id="frontier_loader">
        <span class="loader-overlay">
            <span class="loader"></span>
        </span>
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



        @if ($prefix == 'schedule')
            @include('schedule.sidebar')
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

{{-- @include('admin.costomizer') --}}

    <div class="chat-windows"></div>



    @include('admin.script')
    @yield('script')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                // Trigger click event on the element with class .sidebartoggler
                $('.sidebartoggler').click();
            }); // Adjust the delay time as needed
            
            document.querySelectorAll('table[style*="width: 0px"]').forEach(table => {
                    table.removeAttribute('style');
                });
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
                    // console.log(response);
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
