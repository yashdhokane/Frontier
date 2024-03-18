@extends('home')

@section('content')
<link rel="stylesheet"
    href="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
<style>
    .required-field::after {

        content: " *";

        color: red;

    }
</style>



<!-- End Left Sidebar - style you can find in sidebar.scss  -->

<!-- -------------------------------------------------------------- -->

<!-- -------------------------------------------------------------- -->

<!-- Page wrapper  -->

<!-- -------------------------------------------------------------- -->

<div class="page-wrapper" style="display: inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">User Permissions</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#.">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Permissions</li>
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
        <!-- basic table -->
        <div class="row">
            <div class="col-lg-12">

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <h4 class="card-title mb-4">PROFILES</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Customer <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View, Add, Edit & Block Customer's Database</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Technician <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View, Add, Edit & Block Technician's Database</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Admin <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View, Add, Edit & Block Admin's Database</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Dispatcher <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View, Add, Edit & Block Dispatcher's Database</p>
                                                </div>
                                            </div>

                                            <h4 class="card-title mt-4 mb-4">SETTINGS</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Business Profile <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Update company account info</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Working Hours <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Update Working Hours and Business Hours</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Service Area <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete Service Area</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Tax <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Modify Tax information</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Tags <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete of Tags</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Lead Source <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete of Lead Source</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Lead Job Fields <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete of Job Fields</p>
                                                </div>
                                            </div>

                                            <h4 class="card-title mt-4 mb-4">CHAT & MESSAGES</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Support Chat <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Allow user to chat with Technicians</p>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-6">

                                            <h4 class="card-title mb-4">JOBS & PAYMENTS</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Schedule <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Schedule a Job</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Map / Reschedule <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Reschedule a Job. View Job on Map</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Job List <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>See & edit Job Database</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Payment <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>See & edit Payments Database</p>
                                                </div>
                                            </div>

                                            <h4 class="card-title mt-4 mb-4">PRICE BOOK</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Services <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete Services</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Parts <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete Parts</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Assigned Parts <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>Parts assigned to technicians</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Manufacturer <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete Manufacturer</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Estimate <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View / Add / Edit / Delete Estimates</p>
                                                </div>
                                            </div>

                                            <h4 class="card-title mt-4 mb-4">REPORTING</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Performance Matrix <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View Performance Report of the Technician</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Dispatcher Report <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View Performance Report of the Dispatcher</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Activity Report <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View Activity Details of the Admin & Dispatcher</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4 bt-switch">
                                                        <input type="checkbox" checked data-on-color="success"
                                                            data-off-color="danger" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <h5 class="card-title mb-0">Call Report <i
                                                            class="px-2 fas fa-info-circle"></i></h5>
                                                    <p>View Call Details </p>
                                                </div>
                                            </div>








                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>


            </div>




        </div>
    </div>



    @section('script')
    <script src="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script>
        $('#zero_config').DataTable();

    </script>
    <script>
        setTimeout(function() {

            document.getElementById('successMessage').style.display = 'none';

        }, 5000); // 5000 milliseconds = 5 seconds

    </script>

    <script>
        $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
          var radioswitch = (function () {
            var bt = function () {
              $('.radio-switch').on('switch-change', function () {
                $('.radio-switch').bootstrapSwitch('toggleRadioState');
              }),
                $('.radio-switch').on('switch-change', function () {
                  $('.radio-switch').bootstrapSwitch('toggleRadioStateAllowUncheck');
                }),
                $('.radio-switch').on('switch-change', function () {
                  $('.radio-switch').bootstrapSwitch('toggleRadioStateAllowUncheck', !1);
                });
            };
            return {
              init: function () {
                bt();
              },
            };
          })();
          $(document).ready(function () {
            radioswitch.init();
          });
    </script>

    @endsection

    @endsection
