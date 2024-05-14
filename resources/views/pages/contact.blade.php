@extends('home')
@section('content')

        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Contact Us</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
                            </ol>
                        </nav>
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
                <div class="col-12">
                    <!-- ----------------------------------------- -->
                    <!-- 1. Basic Form -->
                    <!-- ----------------------------------------- -->
                    <!-- ---------------------
                                    start Basic Form
                                ---------------- -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3 pb-3 border-bottom">Contact Us</h4>
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="tb-fname"
                                                placeholder="Enter Name here" />
                                            <label for="tb-fname">Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="tb-email"
                                                placeholder="name@example.com" />
                                            <label for="tb-email">Email address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="tb-pwd"
                                                placeholder="Password" />
                                            <label for="tb-pwd">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="tb-cpwd"
                                                placeholder="Password" />
                                            <label for="tb-cpwd">Confirm Password</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-md-flex align-items-center mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault" />
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Remember me
                                                </label>
                                            </div>
                                            <div class="ms-auto mt-3 mt-md-0">
                                                <button type="submit" class="btn btn-info font-medium rounded-pill px-4">
                                                    <div class="d-flex align-items-center">
                                                        <i data-feather="send" class="feather-sm fill-white me-2"></i>
                                                        Submit
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- -------------------------------------------------------------- -->
                <!-- End Page wrapper  -->
                <!-- -------------------------------------------------------------- -->
            </div>
        </div>
   




@section('script')
@endsection
@endsection
