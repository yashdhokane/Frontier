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
  

    <div class="container-fluid pt-2">

        <!-- Row -->
        <div class="row">

            <!-- column -->
            <div class="col-lg-8 col-md-8 card card-body card-border shadow">

                    <form action="{{ route('services.storeServices') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}

                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="control-label bold mb5 required-field">Name</label>
                            <input type="text" name="service_name" id="firstName" class="form-control" placeholder=""
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label bold mb5 required-field">Description</label>
                            <textarea id="text" name="service_description" class="form-control" style="height: 120px;" required></textarea>
                        </div>




                        <div class="row">
                            <div class="col-md-4 col-xl-2">
                                <div class="mb-3">
                                    <label for="service"
                                        class="control-label bold mb5 col-form-label required-field">Category</label>
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
                                    <label class="control-label bold mb5 col-form-label required-field">Service
                                        Code</label>
                                    <input type="text" name="service_code" id="task" class="form-control"
                                        placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-2">
                                <div class="mb-3">
                                    <label class="control-label bold mb5 col-form-label required-field">Service
                                        Duration</label>
                                    <select class="form-control form-select" name="hours" data-placeholder="Choose hours"
                                        tabindex="1" required>
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
                                        class="control-label bold mb5 col-form-label required-field">Manufacturer</label>
                                    <select class="select2-with-menu-bg form-control  me-sm-2" name="manufacturer_ids[]"
                                        id="menu-bg-multiple" multiple="multiple" data-bgcolor="light"
                                        data-bgcolor-variation="accent-3" data-text-color="blue" style="width: 100%"
                                        required>
                                        @foreach ($manufacturers as $manufacturer)
                                            <option value="{{ $manufacturer->id }}">
                                                {{ $manufacturer->manufacturer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2 mb-4">
                            <div class="col-md-6">
                                <label class="control-label bold mb5 ">Troubleshooting Questions</label>
                                <input type="text" name="troubleshooting_question1" id="task" class="form-control"
                                    placeholder="" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label bold mb5 ">Additional Troubleshooting Questions</label>
                                <input type="text" name="troubleshooting_question2" id="task" class="form-control"
                                    placeholder="" />
                            </div>
                        </div>




                </div>
                <!-- Card -->



            <!-- column -->
            <div class="col-lg-4 col-md-4">

                <div class="card card-body  card-border shadow">
                    <label class="required-field bold mb5">Warranty</label>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="in_warranty" type="checkbox" value="yes"
                                id="flexSwitchCheckChecked">
                            <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in
                                Warranty</label>
                        </div>
                    </div>
                    <label class="required-field bold mb5">Job Schedule</label>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="service_online" type="checkbox" value="yes"
                                id="flexSwitchCheckChecked">
                            <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in Job
                                Schedule</label>
                        </div>
                    </div>
                    <label class="required-field bold mb5">Job Estimate</label>
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
                <div class="card card-body  card-border shadow">
                    <h4>Pricing</h4>

                    <div class="mb-3">
                        <label class="control-label bold mb5 required-field">Price (Unit Price)</label>
                        <input type="text" id="service" class="form-control" name="service_cost" placeholder=""
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label bold mb5 required-field">Discount (In Percentage)</label>
                        <input type="text" id="service" name="service_discount" class="form-control"
                            placeholder="" required>
                        <small id="name" class="form-text text-muted">It should be in percentage</small>
                    </div>
                
                    <div class="mb-3">
                        <label class="control-label bold mb5 required-field">Total</label>
                        <input type="text" id="service" name="service_total" class="form-control" placeholder=""
                            required>
                        <small id="name" class="form-text text-muted">Gross Total = Unit Price - Discount +
                            Tax</small>
                    </div>

                </div>


            </div>

            <!-- column -->
            <div class="col-lg-12 col-md-12" style="text-align: center;">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>


            </form>

        </div>
        <!-- Row -->

       

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
