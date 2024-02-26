@extends('home')

@section('content')
    <style>
        #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
            padding-top: 35px;
        }

        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
            margin-left: 130px;
        }
    </style>

    <div id="main-wrapper">
        <div class="page-wrapper">
            <!-- -------------------------------------------------------------- -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- -------------------------------------------------------------- -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Payments</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Payments</li>
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
                    <div class="col-12">
                        <!-- ---------------------
                                                        start Payments
                                                    ---------------- -->
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle">
                                    In these invoice, with these below buttons(CSV,Copy,Excel,PDF,Print) you can
                                    save this content ad per requirments.
                                </h6>
                                <div class="table-responsive">
                                    <table id="file_export" class="table table-bordered">
                                        <thead>
                                            <!-- start row -->
                                            <tr>
                                                <th>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customControlValidation1" required />
                                                        <label class="form-check-label"
                                                            for="customControlValidation1"></label>
                                                    </div>
                                                </th>
                                                <th>Action</th>
                                                <th>Invoice Id</th>
                                                <th>Job Code</th>
                                                <th>Job Title</th>
                                                <th>Customer Name</th>
                                                <th>Invoice Date</th>
                                                <th>Gross Amount</th>
                                            </tr>
                                            <!-- end row -->
                                        </thead>
                                        <tbody>
                                            <!-- start row -->
                                            @foreach ($payment as $item)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="customControlValidation2" required />
                                                            <label class="form-check-label"
                                                                for="customControlValidation2"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="eye" class="feather-sm me-2"></i>
                                                                    View</a>
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="message-circle"
                                                                        class="feather-sm me-2"></i>
                                                                    Comments</a>
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="edit-2" class="feather-sm me-2"></i>
                                                                    Mark Complete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><a
                                                            href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number }}</a>
                                                    </td>
                                                    <td>{{ $item->JobModel->job_code }}</td>
                                                    <td>{{ $item->JobModel->job_title }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td>{{ $item->total }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <!-- start row -->
                                            <tr>
                                                <th>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customControlValidation35" required />
                                                        <label class="form-check-label"
                                                            for="customControlValidation35"></label>
                                                    </div>
                                                </th>
                                                <th>Action</th>
                                                <th>Invoice Id</th>
                                                <th>Job Code</th>
                                                <th>Job Title</th>
                                                <th>Customer Name</th>
                                                <th>Invoice Date</th>
                                                <th>Gross Amount</th>
                                            </tr>
                                            <!-- end row -->
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- ---------------------
                                                        end Payments
                                                    ---------------- -->
                    </div>
                </div>
                <!-- -------------------------------------------------------------- -->
                <!-- End PAge Content -->
                <!-- -------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------- -->
                <!-- Right sidebar -->
                <!-- -------------------------------------------------------------- -->
                <!-- .right-sidebar -->
                <!-- -------------------------------------------------------------- -->
                <!-- End Right sidebar -->
                <!-- -------------------------------------------------------------- -->
            </div>

        </div>
    </div>



    <!-- start - This is for export functionality only -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

    <script>
        //=============================================//
        //    File export                              //
        //=============================================//
        $(document).ready(function() {
            $('#file_export').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            });

            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
                'btn btn-cyan text-white me-1'
            );
        });
    </script>
@endsection
