@extends('home')
@section('content')
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display: inline;">
    <!-- ------------------------------------------------- -->


    <!-- Bread crumb and right sidebar toggle -->

    <!-- -------------------------------------------------------------- -->


    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Frontier Services Inc</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Service Area </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-7 align-self-center">
                    <div class="d-flex no-block justify-content-end align-items-center">
                        <div class="me-2">
                            <div class="lastmonth"></div>
                        </div>
                        <div class="">
                            <small>LAST MONTH</small>
                            <h4 class="text-info mb-0 font-medium">$58,256</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->

        <div class="container-fluid">

            <!-- -------------------------------------------------------------- -->

            <!-- Start Page Content -->

            <!-- -------------------------------------------------------------- -->

            <!-- 1. card with img -->




            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-xl-2">
                        <h4 class="mb-0 mt-2">Service Area</h4>
                    </div>
                    <div class="
                        col-md-8 col-xl-10
                        text-end
                        d-flex
                        justify-content-md-end justify-content-center
                        mt-3 mt-md-0
                      ">

                        <a href="{{ route('servicearea.create') }}" id="btn-add-contact" class="btn btn-info">
                            <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                            Add Service Area</a>
                    </div>


                </div>
            </div>


            <!-- Add Popup Model -->
            <div id="service-area" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true" >
                <div class="modal-dialog" style="max-width:500px;">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="myModalLabel">Edit Service Area</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                fdprocessedid="k7jfjv"></button>
                        </div>
                        <div class="modal-body" id="appendbodyservicearea">

                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!-- End Popup Model -->

            <!-- view popup model -->
            <div id="service-area-view" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" style="max-width:800px;">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                         {{--   <h4 class="modal-title" id="myModalLabel">Service Area Details</h4>--}}
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                fdprocessedid="k7jfjv"></button>
                        </div>
                        <div class="modal-body" id="appendbodyserviceareaview">

                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!--end -->


            <!-- Row -->

            <div class="row">

                <!-- column -->
                @foreach ($servicearea as $item)
                <div class="col-lg-4 col-md-6 col-xl-2">

                    <!-- Card -->



                    <div class="card">

                        <div class="mparea">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d774440.6850054913!2d-75.16222184954196!3d40.69249737782041!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1704723153396!5m2!1sen!2sin"
                                width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>

                        <div class="card-body">

                            <h4 class="card-title">{{ $item->area_name ?? null }}</h4>

                            <p class="card-text">

                                {{ $item->area_description ?? null }}

                            </p>

                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#service-area"
                                class="btn btn-primary serviceareaedit" id="{{ $item->area_id }}">Edit</a>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#service-area-view"
                                class="btn btn-primary serviceareaview" id="{{ $item->area_id }}">View</a>


                        </div>

                    </div>

                    <!-- Card -->

                </div>

                @endforeach
                <!-- column -->

                <!-- column -->



                <!-- column -->

                <!-- column -->


                <!-- column -->

                <!-- column -->



                <!-- column -->

                <!-- column -->



                <!-- column -->

                <!-- column -->

                <!-- column -->

            </div>

            <!-- Row -->

            <!-- 1. end card with img -->



            <!-- 2. card with body -->



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
</div>
@section('script')
  <script>
    $(document).on('click', '.serviceareaedit', function() { $("#service-area").modal({

        backdrop: "static",
        keyboard: false,
        });
        var entry_id = $(this).attr('id');
        $("#appendbodyservicearea").empty();
        $.ajax({
        url: 'editservicearea',
        type: 'get',
        data: {
        entry_id: entry_id

        },
        dataType: 'json',
        success: function(data) {
        $("#appendbodyservicearea").html(data.html);
        }
        });
        });


</script>

<script>
    $(document).on('click', '.serviceareaview', function() { $("#service-area-view").modal({

        backdrop: "static",
        keyboard: false,
        });
        var entry_id = $(this).attr('id');
        $("#appendbodyserviceareaview").empty();
        $.ajax({
        url: 'viewservicearea',
        type: 'get',
        data: {
        entry_id: entry_id

        },
        dataType: 'json',
        success: function(data) {
        $("#appendbodyserviceareaview").html(data.html);
        }
        });
        });


</script>
@endsection
@endsection