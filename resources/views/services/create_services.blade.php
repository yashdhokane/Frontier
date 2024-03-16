@extends('home')
@section('content')
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display: inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Add New Services</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Price Book</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('services.listingServices') }}">Services</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Add New Services</li>
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

            <!-- Row -->
            <div class="row">

                <!-- column -->
                <div class="col-lg-8 col-md-8">

                    <!-- Card -->
                    <div class="card card-body">
                        <form action="{{ route('services.storeServices') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}

                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="control-label required-field">Name</label>
                                <input type="text" name="service_name" id="firstName" class="form-control" placeholder=""
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="control-label required-field">Description</label>
                                <textarea id="text" name="service_description" class="form-control" style="height: 120px;" required></textarea>
                            </div>




                            <div class="row">
                                <div class="col-md-4 col-xl-2">
                                    <div class="mb-3">
                                        <label for="service"
                                            class="control-label col-form-label required-field">Category</label>
                                        <select class="form-select" id="service" name="service_category_id" required>
                                            <option selected disabled value="">Select Category...</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-2">
                                    <div class="mb-3">
                                        <label class="control-label col-form-label required-field">Service Code</label>
                                        <input type="text" name="service_code" id="task" class="form-control"
                                            placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-2">
                                    <div class="mb-3">
                                        <label class="control-label col-form-label required-field">Service Duration</label>
                                        <select class="form-control form-select" name="hours"
                                            data-placeholder="Choose hours" tabindex="1" required>
                                            <option value="30">30 Mins</option>
                                            <option value="60">60 Mins</option>
                                            <option value="90">90 Mins</option>
                                            <option value="120">120 Mins</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xl-2">
                                    <div class="mb-3">
                                        <label for="manufacturer"
                                            class="control-label col-form-label required-field">Manufacturer</label>
                                        <select class="form-select me-sm-2" id="manufacturer" name="manufacturer_ids[]"
                                            multiple required>
                                            <option selected disabled value="">Select Manufacturers...</option>
                                            @foreach ($manufacturers as $manufacturer)
                                                <option value="{{ $manufacturer->id }}">
                                                    {{ $manufacturer->manufacturer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>




                    </div>
                    <!-- Card -->


                    <!-- Card -->
                    <div class="card card-body">
                        <h4>TROUBLESHOOTING</h4>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="control-label required-field">Troubleshooting Questions</label>
                                <input type="text" name="troubleshooting_question1" id="task" class="form-control"
                                    placeholder="" required />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label required-field">Additional Troubleshooting Questions</label>
                                <input type="text" name="troubleshooting_question2" id="task" class="form-control"
                                    placeholder="" required />
                            </div>
                        </div>

                    </div>

                </div>
                <!-- column -->

                <!-- column -->
                <div class="col-lg-4 col-md-4">

                    <div class="card card-body">
                        <h4 class="required-field">Warranty</h4>
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="in_warranty" type="checkbox" value="yes"
                                    id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in
                                    Warranty</label>
                            </div>
                        </div>
                        <h4 class="required-field">Job Schedule</h4>
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="service_online" type="checkbox" value="yes"
                                    id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in Job
                                    Schedule</label>
                            </div>
                        </div>
                        <h4 class="required-field">Job Estimate</h4>
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="estimate_online" type="checkbox" value="yes"
                                    id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in Job
                                    Estimate</label>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class="card card-body">
                        <h4>Pricing</h4>

                        <div class="mb-3">
                            <label class="control-label required-field">Price (Unit Price)</label>
                            <input type="number" id="service" class="form-control" name="service_cost"
                                placeholder="" required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label required-field">Discount (In Percentage)</label>
                            <input type="number" id="service" name="service_discount" class="form-control"
                                placeholder="" required>
                            <small id="name" class="form-text text-muted">It should be in percentage</small>
                        </div>
                        <div class="mb-3">
                            <label class="control-labe required-field">Tax (In Percentage)</label>
                            <input type="number" id="service" name="service_tax" class="form-control" placeholder=""
                                required>
                            <small id="name" class="form-text text-muted">If applicable only</small>
                        </div>
                        <div class="mb-3">
                            <label class="control-label required-field">Total</label>
                            <input type="number" id="service" name="service_total" class="form-control"
                                placeholder="" required>
                            <small id="name" class="form-text text-muted">Gross Total = Unit Price - Discount +
                                Tax</small>
                        </div>

                    </div>


                </div>
                <!-- column -->
                <div class="col-md-2" style="margin-top:5px; margin-left:260px;">
                    <button type="submit" class="btn btn-primary">Submit</button>


                </div>
                </form>

            </div>
            <!-- Row -->

            <div class="row">
                <div class="col-lg-6">
                    <br />
                </div>
                <div class="col-lg-6">
                    <br />
                </div>
            </div>

        </div>
    </div>

    </div>
@stop
<!-- -------------------------------------------------------------- -->
