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

                        <h4 class="page-title">Invoice Number: #{{ $payment->invoice_number }}</h4>

                        <div class="d-flex align-items-center">

                            <nav aria-label="breadcrumb">

                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>

                                    <li class="breadcrumb-item"><a href="#.">Payments</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">Invoice Number:
                                        #{{ $payment->invoice_number }}</li>

                                </ol>

                            </nav>

                        </div>

                    </div>

                    <div class="col-7 align-self-center">

                        <div class="d-flex no-block justify-content-end align-items-center">

                            <div class="m-r-10">

                                <div class="lastmonth"></div>

                            </div>

                            <div class="ps-2">

                                <small>LAST MONTH</small>

                                <h4 class="text-info m-b-0 font-medium">$58,256</h4>

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

                <div class="row">

                    <div class="col-md-12">

                        <!-- ---------------------

                                                start INVOICE

                                            ---------------- -->



                        <div class="card card-body printableArea">



                            <div class="row">

                                <div class="col-md-12">

                                    <div class="card bg-success">

                                        <div class="card-body text-white">

                                            <div class="d-flex flex-row">

                                                <div class="round align-self-center bg-light-success text-success">

                                                    <i data-feather="credit-card" class="feather-sm"></i>

                                                </div>

                                                <div class="ms-3 align-self-center">

                                                    <h2 class="mb-0">Frontier Inc.</h2>

                                                    <span class="text-white">400 N Saint Paul St. Suite 870, Dallas, TX
                                                        75201</span>

                                                </div>

                                                <div class="ms-auto align-self-center">

                                                    <p><b>Invoice Number</b><br /><span class="pull-right"
                                                            style="font-size: 22px;">#{{ $payment->invoice_number }}</span>
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-md-7">

                                    <div class="pull-left">

                                        <address>

                                            <h6>To,</h6>

                                            <h3><b class="text-success">{{ $payment->user->name }}</b></h3>

                                            <p class="text-muted m-l-30"> {{ $payment->UserAddress->address_line1 ?? null }}</p>

                                        </address>

                                    </div>

                                </div>

                                <div class="col-md-5">

                                    <div class="pull-right text-end">

                                        <address>

                                            <p class="m-t-30"><b>Invoice Date:</b> <i class="fas fa-calendar-alt"></i>
                                                {{ $payment->issue_date }}</p>

                                            <p><b>Due Date: </b> <i class="fas fa-calendar-alt"></i>
                                                {{ $payment->due_date }}</p>

                                            <p><b>Invoice Amount: </b> <i
                                                    class="fas fa-dollar-sign"></i>{{ $payment->total }} <br><span
                                                    class="mb-1 badge bg-danger" style="padding: 10px;">PAYMENT
                                                    PENDING</span></p>

                                        </address>

                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="table-responsive m-t-40" style="clear: both">

                                        <table class="table table-hover">

                                            <thead>

                                                <tr>

                                                    <th class="text-center">#</th>

                                                    <th>Item Name</th>

                                                    <th class="text-end">Unit Price</th>

                                                    <th class="text-end">Quantity</th>

                                                    <th class="text-end">Discount</th>

                                                    <th class="text-end">Tax</th>

                                                    <th class="text-end">Total</th>

                                                </tr>

                                            </thead>

                                            <tbody>
                                                    <tr>

                                                        <td class="text-center">1</td>

                                                        <td>
                                                            <h6 class="font-weight-medium mb-0">
                                                                {{ $job->job_code ?? null }} <small
                                                                    class="text-muted">{{ $job->jobserviceinfo->service_name  ?? null}}</small>
                                                            </h6>
                                                        </td>

                                                        <td class="text-end">
                                                            ${{ $job->jobserviceinfo->base_price  ?? null}}
                                                        </td>

                                                        <td class="text-end">
                                                            {{ $job->jobserviceinfo->quantity  ?? null}}
                                                        </td>

                                                        <td class="text-end">
                                                            ${{ $job->jobserviceinfo->discount ?? null }}
                                                        </td>

                                                        <td class="text-end">
                                                            ${{ $job->jobserviceinfo->tax  ?? null }}
                                                        </td>

                                                        <td class="text-end">
                                                            ${{ $job->jobserviceinfo->sub_total ?? null  }}
                                                        </td>

                                                    </tr>

                                                <tr>

                                                    <td class="text-center">2</td>
            
                                                    <td><h6 class="font-weight-medium mb-0">{{ $job->job_code ?? null }} <small class="text-muted">{{ $job->jobproductinfo->product_name  ?? null}}</small></h6></td>
            
                                                    <td class="text-end">${{ $job->jobproductinfo->base_price  ?? null}}</td>
            
                                                    <td class="text-end">{{ $job->jobproductinfo->quantity  ?? null}}</td>
            
                                                    <td class="text-end">${{ $job->jobproductinfo->discount ?? null }}</td>
            
                                                    <td class="text-end">${{ $job->jobproductinfo->tax  ?? null }}</td>
            
                                                    <td class="text-end">${{ $job->jobproductinfo->sub_total ?? null  }}</td>
            
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="pull-right m-t-30 text-end">

                                        <!--<p>Sub - Total amount: $297.00</p><p>Tax (10%) : $27</p><hr />-->

                                        <h3><b>Total :</b>
                                            ${{ $job->gross_total ?? null }}
                                        </h3>

                                    </div>

                                    <div class="clearfix"></div>

                                    <hr />

                                    <div class="text-end">

                                        <button class="btn btn-danger" type="submit">Mark Paid</button>

                                        <button id="print" class="btn btn-default btn-outline" type="button">

                                            <span><i data-feather="printer" class="feather-sm"></i> Print</span>

                                        </button>

                                    </div>

                                </div>



                            </div>

                        </div>

                        <!-- ---------------------

                                                end INVOICE

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

        <!-- -------------------------------------------------------------- -->

        <!-- End Page wrapper  -->

        <!-- -------------------------------------------------------------- -->

    </div>
@endsection
