@extends('home')
@section('content')
    <link rel="stylesheet" href="{{ asset('public/admin/dist/libs/select2/dist/css/select2.min.css') }}">
    <!-- -------------------------------------------------------------- -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- -------------------------------------------------------------- -->
    <div id="main-wrapper">
        <!-- Page wrapper  -->
        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Assign Parts to Technician</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="#.">Calls / Ticket</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Assign Parts to Technician</li>
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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
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

                                        <form action="{{ url('store/assign-product') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="technician"
                                                        class="control-label bold mb5">Technician</label>
                                                    <select class="select2-with-menu-bg form-control" name="technician_id[]"
                                                        id="menu-bg-multiple2" multiple="multiple" data-bgcolor="light"
                                                        data-bgcolor-variation="accent-3" data-text-color="blue"
                                                        style="width: 100%" required>
                                                        @foreach ($technician as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="parts" class="control-label bold mb5">Parts</label>
                                                    <select class="select2-with-menu-bg form-control" name="product_id[]"
                                                        id="menu-bg-multiple" multiple="multiple" data-bgcolor="light"
                                                        data-bgcolor-variation="accent-3" data-text-color="blue"
                                                        style="width: 100%" required>
                                                        @foreach ($product as $item)
                                                            <option value="{{ $item->product_id }}">
                                                                {{ $item->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn waves-effect waves-light btn-primary"
                                                        style="margin-top: 25px;margin-left: 20px;width: 140px;">Assign</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        @foreach ($technician as $item)
                            <div class="col-md-3">

                                <div class="mb-2">
                                    <div class="card">
                                        <div class="card-body text-left">
                                            <h4 class="card-title mb-2">{{ $item->name }}</h4>
                                            <h6 class="mb-2">Assigned to </h6>
                                            <ul class="list-group list-group-horizontal-xl">

                                                @foreach ($assign->where('technician_id', $item->id) as $assignment)
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <i class="text-info fas fa-user mx-2"></i>
                                                        {{ $assignment->Product->product_name ?? null }}
                                                    </li>
                                                @endforeach

                                                @if ($assign->where('technician_id', $item->id)->isEmpty())
                                                    <p>No Part Assigned</p>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>






                </div>




            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Container fluid  -->


        <!-- -------------------------------------------------------------- -->
        <!-- End Page wrapper  -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Wrapper -->
    <!-- -------------------------------------------------------------- -->

    <script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- This page JavaScript -->
    <!-- --------------------------------------------------------------- -->
    <script src="{{ asset('public/admin/dist/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/admin/dist/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('public/admin/dist/js/pages/forms/select2/select2.init.js') }}"></script>
@endsection
