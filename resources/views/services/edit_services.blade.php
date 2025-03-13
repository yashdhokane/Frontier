@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
    @endif
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-4 align-self-center">
                <h4 class="page-title">Edit Services</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('services.listingServices') }}">Services</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Services</li>
                        </ol>
                    </nav>
                </div>
            </div> <div class="col-8 text-end px-4">
                @include('header-top-nav.asset-nav')
        </div>

        </div>
    </div>

    <div class="container-fluid pt-2">

        <!-- Row -->
        <div class="row">

            <!-- column -->
            <div class="col-lg-8 col-md-8 card card-body card-border shadow">

                <form action="{{ url('book-list/services/' . $service->service_id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}

                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="control-label bold md5 required-field">Name</label>
                        <input type="text" value="{{ $service->service_name }}" name="service_name" id="firstName"
                            class="form-control" placeholder="" required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label bold md5 required-field">Description</label>
                        <textarea name="service_description" class="form-control" style="height: 120px;" required>{{ $service->service_description }}</textarea>
                    </div>




                    <div class="row">
                        <div class="col-md-4 col-xl-2">
                            <div class="mb-3">
                                <label for="service"
                                    class="control-label bold md5 col-form-label required-field">Category</label>
                                <select class="form-select" id="service" name="service_category_id" required>
                                    <option selected disabled value="">Select Category...</option>
                                    @foreach ($services as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $service->service_category_id ? 'selected' : '' }}>
                                            {{ $item->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2">
                            <div class="mb-3">
                                <label class="control-label bold md5 col-form-label required-field">Service
                                    Code</label>
                                <input type="text" value="{{ $service->service_code }}" name="service_code"
                                    id="task" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2">
                            <div class="mb-3">
                                <label class="control-label bold md5 col-form-label required-field">Service
                                    Duration</label>
                                <select class="form-control form-select" name="hours" data-placeholder="Choose hours"
                                    tabindex="1" required>
                                    <option value="30" {{ $service->service_category_id == '30' ? 'selected' : '' }}>30
                                        Mins
                                    </option>
                                    <option value="60" {{ $service->service_category_id == '60' ? 'selected' : '' }}>60
                                        Mins
                                    </option>
                                    <option value="90" {{ $service->service_category_id == '90' ? 'selected' : '' }}>90
                                        Mins
                                    </option>
                                    <option value="120" {{ $service->service_category_id == '120' ? 'selected' : '' }}>
                                        120 Mins
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-xl-2">
                            <div class="mb-3">
                                <label for="manufacturer_ids"
                                    class="control-label bold md5 col-form-label required-field">Manufacturer</label>

                                <select class="select2-with-menu-bg form-control me-sm-2" name="manufacturer_ids[]"
                                    id="menu-bg-multiple" multiple="multiple" data-bgcolor="light"
                                    data-bgcolor-variation="accent-3" data-text-color="blue" style="width: 100%" required>
                                    @foreach ($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}"
                                            @if (
                                                !is_null($service->manufacturer_ids) &&
                                                    is_array(json_decode($service->manufacturer_ids)) &&
                                                    in_array($manufacturer->id, json_decode($service->manufacturer_ids))) selected @endif>
                                            {{ $manufacturer->manufacturer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2 mb-4">
                        <div class="col-md-6">
                            <label class="control-label bold md5 ">Troubleshooting Questions</label>
                            <input type="text" value="{{ $service->troubleshooting_question1 }}"
                                name="troubleshooting_question1" id="task" class="form-control" placeholder="" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label bold md5 ">Additional Troubleshooting Questions</label>
                            <input type="text" value="{{ $service->troubleshooting_question2 }}"
                                name="troubleshooting_question2" id="task" class="form-control" placeholder="" />
                        </div>
                    </div>









            </div>
            <!-- column -->

            <!-- column -->
            <div class="col-lg-4 col-md-4">

                <div class="card card-body card-border shadow">
                    <label class="required-field">Warranty</label>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="in_warranty" type="checkbox" value="yes"
                                id="flexSwitchCheckChecked" {{ $service->in_warranty == 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in
                                Warranty</label>
                        </div>
                    </div>
                    <label class="required-field">Job Schedule</label>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="service_online" type="checkbox" value="yes"
                                id="flexSwitchCheckChecked" {{ $service->job_online == 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in Job
                                Schedule</label>
                        </div>
                    </div>
                    <label class="required-field">Job Estimate</label>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="estimate_online" type="checkbox" value="yes"
                                id="flexSwitchCheckChecked" {{ $service->estimate_online == 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in Job
                                Estimate</label>
                        </div>
                    </div>
                </div>

                <!-- Card -->
                <div class="card card-body card-border shadow">
                    <h4>Pricing</h4>

                    <div class="mb-3">
                        <label class="control-label bold md5 required-field">Price (Unit Price)</label>
                        <input type="text" value="{{ $service->service_cost }}" id="service" class="form-control"
                            name="service_cost" placeholder="" required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label bold md5 required-field">Discount (In Percentage)</label>
                        <input type="text" value="{{ $service->service_discount }}" id="service"
                            name="service_discount" class="form-control" placeholder="" required>
                        <small id="name" class="form-text text-muted">It should be in percentage</small>
                    </div>

                    <div class="mb-3">
                        <label class="control-label bold md5 required-field">Total</label>
                        <input type="text" value="{{ $service->service_total }}" id="service" name="service_total"
                            class="form-control" placeholder="" required>
                        <small id="name" class="form-text text-muted">Gross Total = Unit Price - Discount +
                            Tax</small>
                    </div>

                </div>


            </div>
            <!-- column -->
            <div style="text-align: center;">
                <div class="col-md-2" style="display: inline-block;">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            </form>

        </div>


    </div>



    <script>
        $(document).ready(function() {
            $('#manufacturer_ids').select2();
        });
    </script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                // Trigger click event on the element with class .sidebartoggler
                $('.sidebartoggler').click();
            }); // Adjust the delay time as needed
        });
    </script>

    @if (Route::currentRouteName() != 'dash')
    @stop
@endif
<!-- -------------------------------------------------------------- -->
