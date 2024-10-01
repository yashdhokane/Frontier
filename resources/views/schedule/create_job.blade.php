  @if(Route::currentRouteName() != 'dash')
@extends('home')
@section('content')
 @endif
<style>
    .select2-results__option--disabled{
    font-weight: 900;
    }
    .select2-container {
  width: 100% !important;
}
</style>
    <link rel="stylesheet" href="{{ url('public/admin/schedule/style.css') }}">

    <link href="{{ asset('public/admin/dist/css/style.min.css') }}" rel="stylesheet" />
    <div class="createScheduleData">

        <input type="hidden" class="travel_input" id="travel_input" name="travel_input" value="">
        <input type="hidden" class="technician_name" value="{{ $technician->name }}">
        @if (isset($technician) && !empty($technician))

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h6 class="card-subtitle mb-2"></h6>
                        <form action="#" class="tab-wizard vertical wizard-circle mt-1" id="createScheduleForm"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" class="technician_id" name="technician_id" value="{{ $technician->id }}"
                                data-start-hours="{{ (int) $hours->start_time }}"
                                data-end-hours="{{ (int) $hours->end_time }}" data-start-time="{{ $time }}">
                            <input type="hidden" class="datetime" name="datetime" id="datetime"
                                value="{{ $dateTime }}">
                            <input type="hidden" class="customer_id" id="" name="customer_id" value="">
                            <input type="hidden" class="job_id" id="" name="job_id" value="">
                            <input type="hidden" class="scheduleType" id="" name="scheduleType" value="job">
                            <input type="hidden" class="address_type" id="" name="address_type" value="">
                            <input type="hidden" class="status_slot" value="">
                            <input type="hidden" class="tax_total" name="tax_total" value="">
                            <input type="hidden" class="service_area_id" name="service_area_id" value="">

                            <!-- Step 1 -->
                            <h6>Customer Information </h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end pe-5">
                                        <div class="form-group w-25 text-end">
                                            <label for="newdatetime">Date and Time</label>
                                            <div class="d-flex">
                                                <input type="date" class="form-control" id="newdate" name="newdate"
                                                    value="{{ $date }}">
                                                <select class="form-control w-50" id="newtime" name="newtime">
                                                    @foreach ($timeIntervals as $interval)
                                                        @php
                                                            $timeDisplay = date('h:i A', strtotime($interval));
                                                            $selected =
                                                                substr($dateTime, 11, 5) === substr($interval, 0, 5)
                                                                    ? 'selected'
                                                                    : '';
                                                        @endphp
                                                        <option value="{{ $interval }}" {{ $selected }}>
                                                            {{ $timeDisplay }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mt-0">
                                            <h6 class="card-title"><i class="fa fa-search" aria-hidden="true"></i> Search
                                                Customer OR Pending Jobs</h6>

                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mt-0">
                                            <div class="input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-light-info text-info" type="button">
                                                        <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control searchCustomer"
                                                    name="customer_name" placeholder="Customer or Pending job"
                                                    aria-label="" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
                                            <a href="#." id="btn-add-contact1" class="btn btn-info"
                                                data-bs-toggle="modal" data-bs-target="#newCustomer">
                                                <i class="ri-user-add-line"></i> Add New Customer
                                            </a>
                                        </div>

                                        {{-- to add new customer modal --}}




                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6 customersSuggetions" style="display: none">
                                        <div class="card">
                                            <div class="mt-4">
                                                <div class="">
                                                    <h5 class="font-weight-medium mb-2">Select Customer</h5>
                                                    <div class="customers">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 pendingJobsSuggetions" style="display: none">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="row">

                                                    <div class="col-md-3" id="makedescending" style="cursor: pointer;"><i
                                                            class="ri-sort-asc"></i></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 d-flex align-items-baseline">
                                                        <h5 class="font-weight-medium mb-2 d-flex"
                                                            style="position: relative;">
                                                            <input class="mx-1" type="radio" name="teritory"
                                                                id="techall" checked> Reschedule Pending Jobs
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-12 d-flex align-items-baseline">
                                                        <h5 class="font-weight-medium mb-2 d-flex"
                                                            style="position: relative;"><input class="mx-1"
                                                                type="radio" name="teritory" id="newyork"
                                                                data-state="NY"> <span id="stateNameArea">Show Open jobs
                                                                in </span> </h5>
                                                    </div>
                                                    <div class="col-md-12 d-flex align-items-baseline">
                                                        <h5 class="font-weight-medium mb-2 d-flex"
                                                            style="position: relative;">
                                                            <input class="mx-1 techName" type="radio" name="teritory"
                                                                id="techonly" class="techonly"> Show Open jobs of
                                                            {{ $technician->name }} (Technician)
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="rescheduleJobs">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 CustomerAdderss" style="display: none">
                                        <div class="mb-2">
                                            <h6 class="card-title"><i class="fas fa-map-marker" aria-hidden="true"></i>
                                                Customer
                                                Address </h6>
                                            <div class="form-group">
                                                <input type="hidden" name="addres_lat" id="addres_lat" value="">
                                                <select class="form-control customer_address" name="customer_address"
                                                    id="exampleFormControlSelect1">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>

                            <!-- Step 2 -->

                            <h6>Job Information</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-sticky-note"></i>
                                                Job Title </h6>
                                            <div class="form-group">
                                                <input type="text" name="job_title" class="form-control job_title"
                                                    placeholder="Add Job Title Here" aria-label=""
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa-user"></i> Priority
                                            </h6>
                                            <div class="form-group">
                                                <select class="form-control priority" id="exampleFormControlSelect1"
                                                    name="priority">
                                                    <option value="high">High</option>
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="card-title required-field"><i class="fas fa fa-calendar-check-o"></i>
                                            Duration</h6>
                                        <div class="form-group">
                                            <select class="form-control duration" id="duration" name="duration">
                                                <option value="240">4 Hours</option>
                                                <option value="180">3 Hours</option>
                                                <option value="120" selected>2 Hours</option>
                                                <option value="60">1 Hours</option>
                                                <option value="30">30 min</option>
                                            </select>
                                            <small id="result_travel" class="text-success"
                                                style="display: none;"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i
                                                    class="fas fa fa-pencil-square-o"></i> Job Description
                                            </h6>
                                            <div class="form-group">
                                                <textarea class="form-control job_description" rows="2" placeholder="Add Description  Here..."
                                                    name="job_description"></textarea>
                                                <small id="textHelp" class="form-text text-muted">All all details of the
                                                    job goes here</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i
                                                    class="fas fa fa-pencil-square-o"></i> Notes to Technician </h6>
                                            <div class="form-group">
                                                <textarea class="form-control technician_notes" rows="2" placeholder="Add Technician Notes  Here..."
                                                    name="technician_notes"></textarea>
                                                <small id="textHelp" class="form-text text-muted">Technician will see the
                                                    notes before starting the job.</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="card-title required-field"><i class="fas fa fa-television"></i>Select
                                            Existing Appliances </h6>
                                        <div class="form-group">
                                            <select class="form-control appl_id exist_appl_id" id="appl_id"
                                                name="exist_appl_id">
                                                <option value=""> -- Select existig appliances -- </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-4"><a href="#." id="add_new_appl">Add New</a></div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="show_new_appl">
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-television"></i>
                                                Appliances </h6>
                                            <div class="form-group">
                                                <select class="form-control appliances" id="appliances"
                                                    name="appliances">
                                                    <option value="">-- Select Appliances -- </option>
                                                    @if (isset($appliances) && !empty($appliances))
                                                        @foreach ($appliances as $value)
                                                            <option value="{{ $value->appliance_type_id }}"
                                                                data-name="{{ $value->appliance_name }}">
                                                                {{ $value->appliance_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="text-success" id="resp_text"></small>
                                                <div class="text-primary" style="cursor: pointer;" id="add_appliance">+
                                                    Add New</div>
                                                <div class="my-2 appliancefield" style="display:none;">
                                                    <div class="d-flex ">
                                                        <input type="text" name="new_appliance"
                                                            class="form-control rounded-0 " id="new_appliance"
                                                            placeholder="Add Appliances Here">
                                                        <button type="button" class="btn btn-cyan p-0 px-2 rounded-0"
                                                            style="cursor: pointer;" id="addAppl">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-industry"></i>
                                                Manufacturer </h6>
                                            <div class="form-group">
                                                <select class="form-control manufacturer" id="manufacturer"
                                                    name="manufacturer">
                                                    <option value="">-- Select Manufacturer -- </option>
                                                    @if (isset($manufacturers) && !empty($manufacturers))
                                                        @foreach ($manufacturers as $value)
                                                            <option value="{{ $value->id }}"
                                                                data-name="{{ $value->manufacturer_name }}">
                                                                {{ $value->manufacturer_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="text-success" id="resp_texts"></small>
                                                <div class="text-primary" style="cursor: pointer;" id="add_manufaturer">+
                                                    Add New</div>
                                                <div class="my-2 manufaturerfield" style="display:none;">
                                                    <div class="d-flex ">
                                                        <input type="text" name="new_manufacturer"
                                                            class="form-control rounded-0 " id="new_manufacturer"
                                                            placeholder="Add Manufaturer Here">
                                                        <button type="button" class="btn btn-cyan p-0 px-2 rounded-0"
                                                            style="cursor: pointer;" id="addManu">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-hashtag"></i> Model
                                                Number </h6>
                                            <div class="form-group">
                                                <input type="text" class="form-control model_number"
                                                    placeholder="Model Number here" aria-label=""
                                                    aria-describedby="basic-addon1" name="model_number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field required-field"><i
                                                    class="fas fa fa-hashtag"></i> Serial Number </h6>
                                            <div class="form-group">
                                                <input type="text" class="form-control serial_number"
                                                    placeholder="Serial Number here" aria-label=""
                                                    aria-describedby="basic-addon1" name="serial_number"
                                                    id="check_serial_number">
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-3 w-100" id="serial_number_detail"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-3 mb-3">
                                            <h6 class="card-title"><i class="far fa fa-photo"></i> Photos / Attachments
                                            </h6>
                                            <div class="input-group">
                                                <input class="form-control" type="file" id="formFile"
                                                    name="photos[]" multiple style="width: 150px;" multiple>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3 mb-3">
                                            <h6 class="card-title"><i class="fas fa fa-tags"></i> Tags </h6>
                                            <div class="input-group">
                                                <select class="form-control me-sm-2 tags  "name="tags[]"
                                                    multiple="multiple" style="width: 100%">
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->field_id }}">
                                                            {{ $tag->field_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Step 3 -->

                            <h6>Warranty, Services & Parts</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-check-square"></i>
                                                Warranty </h6>
                                            <div class="form-group d-flex gap-2">
                                                <select class="form-control job_type" id="check_job_type"
                                                    name="job_type">
                                                    <option value="">Please select</option>
                                                    <option value="in_warranty">In Warranty</option>
                                                    <option value="out_warranty">Out of Warranty</option>
                                                </select>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Warranty Number" name="warranty_ticket"
                                                    id="warranty_ticket">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <h6 class="card-title"><i class="fas ri-dashboard-3-line"></i> Select Services</h5>
                                </div>

                                <div class="container">
                                    <!-- Container for all rows -->
                                    <div id="serviceRows">
                                        <!-- Initial Row -->
                                        <div class="row service-row">
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <div class="form-group">
                                                        <select class="form-control services w-100" name="services[]">
                                                            <option value="" selected>-- Select Services --</option>
                                                            @foreach ($serviceCat as $category)
                                                                <option class="text-black fw-bold" disabled>
                                                                    {{ $category->category_name }}</option>
                                                                @if (isset($category->Services) && count($category->Services) > 0)
                                                                    @foreach ($category->Services as $service)
                                                                        <option class="ps-3"
                                                                            value="{{ $service->service_id }}"
                                                                            data-code="{{ $service->service_code }}">
                                                                            {{ $service->service_name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" class="pre_service_id" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <input type="number" class="form-control service_cost"
                                                        placeholder="$0.00" name="service_cost[]" value="" />
                                                    <input type="hidden" class="pre_service_cost" value="">
                                                    <small class="form-text text-muted">Price</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <input class="form-control service_discount" type="number"
                                                        name="service_discount[]" value="" placeholder="$0.00">
                                                    <input type="hidden" class="pre_service_discount" value="">
                                                    <input type="hidden" class="service_discount_amount"
                                                        name="service_discount_amount[]" value="">
                                                    <small class="form-text text-muted">Discount(%)</small>
                                                </div>
                                            </div>
                                            <input class="service_tax" type="hidden" name="service_tax[]"
                                                value="">
                                            <div class="col-md-2">
                                                <div class="mb-2 service_total_text">$0</div>
                                                <small class="form-text text-muted">Line Total</small>
                                                <input class="service_total" type="hidden" name="service_total[]"
                                                    value="">
                                            </div>
                                            <div class="col-md-2">
                                                <a type="button" class="text-danger remove-row"><i class="ri-delete-bin-line ft15"></i> </a>
                                            </div>
                                        </div>
                                    </div>

                                        <a type="button" id="addRow" class="text-primary"><i class="ri-add-line"></i> Add More Service</a>
                                </div>

                                <div class="row mt-3">
                                    <h6 class="card-title"><i class="fas fa fa-cart-plus"></i> Select Parts</h5>
                                </div>
                                <div class="container mt-2">
                                    <!-- Container for all parts rows -->
                                    <div id="partsRows">
                                        <!-- Initial Row -->
                                        <div class="row parts-row">
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <div class="form-group">
                                                        <select class="form-control products w-100" name="products[]">
                                                            <option value="" selected>-- Select Parts --</option>
                                                            @if (isset($getProduct) && !empty($getProduct))
                                                                @foreach ($getProduct as $value)
                                                                    <option value="{{ $value->product_id }}"
                                                                        data-code="{{ $value->product_code }}">
                                                                        {{ $value->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <input type="hidden" class="pre_product_id" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <input type="number" class="form-control product_cost"
                                                        placeholder="$0.00" name="product_cost[]" value="" />
                                                    <input type="hidden" class="pre_product_cost" value="">
                                                    <small class="form-text text-muted">Price</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <input class="form-control product_discount" type="number"
                                                        name="product_discount[]" value="" placeholder="$0.00">
                                                    <input type="hidden" class="pre_product_discount" value="">
                                                    <input type="hidden" class="product_discount_amount"
                                                        name="product_discount_amount[]" value="">
                                                    <small class="form-text text-muted">Discount(%)</small>
                                                </div>
                                            </div>
                                            <input class="product_tax" type="hidden" name="product_tax[]"
                                                value="">
                                            <div class="col-md-2">
                                                <div class="mb-2 product_total_text">$0</div>
                                                <small class="form-text text-muted">Line Total</small>
                                                <input class="product_total" type="hidden" name="product_total[]"
                                                    value="">
                                            </div>
                                            <div class="col-md-2">
                                                    <a type="button" class="text-danger remove-part-row"><i class="ri-delete-bin-line ft15"></i> </a>
                                            </div>
                                        </div>
                                    </div>

                                    <a type="button" id="addPartRow" class="text-primary mb-2"><i class="ri-add-line"></i> Add More Part</a>
                                </div>

                                <div class="row mb-2" style="border-top: 1px solid #343434;">
                                    <div class="col-md-4 mt-2">&nbsp;</div>
                                    <div class="col-md-4 mt-2">&nbsp;</div>
                                    <div class="col-md-4 mt-2 text-right" style="text-align: right;padding-right: 36px;">
                                        <h5 style="display: inline-flex;">Sub Total:&nbsp;&nbsp;<div class="subtotaltext">
                                                $0
                                            </div>
                                        </h5><br>
                                        <h5 style="display: inline-flex;">Discount:&nbsp;<div class="discounttext">$0
                                            </div>
                                        </h5><br>
                                        <h5 style="display: inline-flex;">Tax:&nbsp;<div class="taxcodetext">$0</div>
                                        </h5><br>
                                        <h4 style="display: inline-flex;">Total:&nbsp;<div class="totaltext">$0</div>
                                        </h4>
                                        <input type="hidden" class="subtotal" name="subtotal" value="0">
                                        <input type="hidden" class="discount" name="discount" value="0">
                                        <input type="hidden" class="total" name="total" value="0">
                                    </div>
                                </div>
                            </section>

                            <!-- Step 4 -->
                            <h6>Confirm Order</h6>
                            <section>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <h4 class="font-weight-medium mb-2">CUSTOMER </h4>
                                        <div class="confirm_job_box">
                                            <div class="row">
                                                <div class="col-md-12" style="display: inline-flex;">
                                                    <h6 class="font-weight-medium mb-0 show_customer_name">

                                                    </h6>&nbsp;
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: -13px">
                                                <div class="col-md-12 reschedule_job">
                                                    <p class="customer_number_email"></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 reschedule_job">
                                                    <p class="show_customer_adderss c_address"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="font-weight-medium mb-2">TECHNICIAN </h4>
                                        <div class="confirm_job_box">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6 class="font-weight-medium mb-0">{{ $technician->name }} <small
                                                            class="text-muted">{{ $technician->city }} Area</small></h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 reschedule_job">{{ $technician->email }} /
                                                    {{ $technician->mobile }}
                                                    {{-- <br />6 Jobs completed --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <h4 class="font-weight-medium mb-2">JOB</h4>
                                        <div class="confirm_job_box">
                                            <div class="row">
                                                <div class="col-md-12" style="display: inline-flex;">
                                                    <h6 class="font-weight-medium mb-0 show_job_title"> </h6>
                                                    &nbsp;
                                                    <small class="text-muted reschedule_job show_job_code">
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 reschedule_job ">
                                                    <p class="show_job_description m-0"></p>
                                                    <p class="show_job_duration m-0"></p>
                                                    <p class="schedule_date m-0"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="font-weight-medium mb-2">APPLIANCES</h4>
                                        <div class="show_appliance"></div>
                                        <div class="show_manufacturer"></div>
                                        <div class="show_model_number"></div>
                                        <div class="show_serial_number"></div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <h4 class="font-weight-medium mb-0">SERVICES & PARTS</h4>
                                        <div class="confirm_job_box">
                                            <div class="row mb-2">
                                                <div class="col-md-7">
                                                    <div class="mt-0">&nbsp;</div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="mt-0 service_css"><label>Unit Price</label></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-0"><label>Discount</label></div>
                                                </div>
                                                <div class="col-md-2 service_css">
                                                    <div class="mt-0"><label>Total</label></div>
                                                </div>
                                            </div>

                                            <div id="service-container">
                                                <!-- Dynamically added service rows will go here -->
                                            </div>
                                            <div id="product-container">
                                                <!-- Dynamically added product rows will go here -->
                                            </div>



                                            <div class="row" style="border-top: 2px dotted #343434">
                                                <div class="col-md-7 align-self-end d-flex">
                                                    <h4> Do you want to Confirm the job ?</h4>

                                                    <div class="form-check form-switch ms-4">
                                                        <input class="form-check-input" name="is_confirmed"
                                                            type="checkbox" value="yes" id="flexSwitchCheckChecked">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="mt-2">&nbsp;</div>
                                                </div>
                                                <div class="col-md-4 mt-2 text-right"
                                                    style="text-align: right;padding-right: 36px;">
                                                    <h5 style="display: inline-flex;">Sub Total:&nbsp;&nbsp;<div
                                                            class="subtotaltext">
                                                            $0
                                                        </div>
                                                    </h5><br>
                                                    <h5 style="display: inline-flex;">Discount:&nbsp;<div
                                                            class="discounttext">$0
                                                        </div>
                                                    </h5><br>
                                                    <h5 style="display: inline-flex;">Tax:&nbsp;<div class="taxcodetext">
                                                            $0</div>
                                                    </h5><br>
                                                    <h4 style="display: inline-flex;">Total:&nbsp;<div class="totaltext">
                                                            $0</div>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>

                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <!-- Modal -->

    <div class="modal fade" id="newCustomer" tabindex="-1" aria-labelledby="scroll-long-inner-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                    <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;color: #2962ff;">ADD
                        NEW CUSTOMER
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body createCustomerData">

                    @include('schedule.new_customer')

                </div>

            </div>



        </div>
    </div>

    {{-- @include('admin.script') --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


    <script>
        $(document).ready(function() {

            // Initialize datetime value on page load
            updateCombinedDateTime();

            // Event listener for date change
            $(document).on('change', '#newdate', function() {
                updateCombinedDateTime();
            });

            // Event listener for time change
            $(document).on('change', '#newtime', function() {
                updateCombinedDateTime();
            });

            // Function to update combined datetime value
            function updateCombinedDateTime() {
                var dateValue = $('#newdate').val();
                var timeValue = $('#newtime').val();
                var combinedDateTime = dateValue + ' ' + timeValue + ':00';
                $('#datetime').val(combinedDateTime); // Update hidden input value
            }

            function showAndInitSelect2() {
                $('.services').show().select2(); // Show and initialize Select2
            }

            function showproductAndInitSelect2() {
                $('.products').show().select2(); // Show and initialize Select2
            }
            setTimeout(function() {
                $('.services:first').select2();
                $('.products:first').select2();
                showAndInitSelect2(); // Example: Show and initialize Select2 after some time
                showproductAndInitSelect2(); // Example: Show and initialize Select2 after some time
            }, 2000);

            // Function to add a new service row
            $(document).on('click', '#addRow', function() {
                let newServiceRow = $('.service-row:first').clone();
                newServiceRow.find('input').val('');
                newServiceRow.find('select').prop('selectedIndex', 0); // Reset selected index
                newServiceRow.find('.service_total_text').text('$0');
                newServiceRow.find('.service_total').val('');

                // Remove select2 from the cloned row to prevent duplicates
                newServiceRow.find('.services').removeClass('select2-hidden-accessible').next(
                    '.select2-container').remove();
                $('.services:first').select2();

                $('#serviceRows').append(newServiceRow);

                // Initialize select2 only for the newly appended row
                newServiceRow.find('.services').select2();
            });


            // Function to remove a service row
            $(document).on('click', '.remove-row', function() {
                if ($('.service-row').length > 1) {
                    $(this).closest('.service-row').remove();
                    calculateServiceTotal();
                }
            });

            // Function to gather services data into an array and save
            function gatherServicesData() {
                let services = [];
                $('#serviceRows .service-row').each(function() {
                    let service = {
                        service_id: $(this).find('.services').val(),
                        service_cost: $(this).find('.service_cost').val(),
                        service_discount: $(this).find('.service_discount').val(),
                        service_total: $(this).find('.service_total').val()
                    };
                    services.push(service);
                });
                // You can now send this array to your server or handle it as needed
                console.log(services);
            }

            // Calculate and update line totals for services
            function calculateServiceTotal() {
                $('#serviceRows .service-row').each(function() {
                    let cost = parseFloat($(this).find('.service_cost').val()) || 0;
                    let discount = parseFloat($(this).find('.service_discount').val()) || 0;
                    let discountAmount = (cost * discount) / 100;
                    let total = cost - discountAmount;

                    $(this).find('.service_total_text').text('$' + total.toFixed(2));
                    $(this).find('.service_total').val(total.toFixed(2));
                });

                calculateSubTotalAndTax();
            }

            // Calculate subtotal, tax, and update UI
            function calculateSubTotalAndTax() {
                var getDiscount = parseFloat($('.discount').val()) || 0;
                var serviceTotalVal = 0;
                var newServiceTotalVal = 0;
                var productTotalVal = 0;
                var newProductTotalVal = 0;

                $('#serviceRows .service-row').each(function() {
                    serviceTotalVal += parseFloat($(this).find('.service_total').val()) || 0;
                });

                var subTotal = serviceTotalVal + newServiceTotalVal + productTotalVal + newProductTotalVal;
                $('.subtotal').val(subTotal.toFixed(2));
                $('.subtotaltext').text('$' + subTotal.toFixed(2));

                var customerId = $('.selectCustomer').data('customer-id');

                $.ajax({
                    url: "{{ route('usertax') }}",
                    data: {
                        customerId: customerId,
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.taxcodetext').empty();
                        var taxpercent = data.state_tax;
                        var total_amount = subTotal * (taxpercent / 100);
                        $('.tax_total').val(total_amount.toFixed(2));
                        var tax_value = parseFloat($('.tax_total').val()) || 0;
                        var tax_amount = tax_value.toFixed(2);

                        var getTotal = $('.total').val().trim();
                        var total = parseFloat(subTotal) + parseFloat(total_amount);
                        $('.total').val(total.toFixed(2));
                        $('.totaltext').text('$' + total.toFixed(2));

                        $('.taxcodetext').append('' + data.state_tax +
                            '% for ' + data
                            .state_code + ': $' + tax_amount);

                    },
                });

                var discount = 0;
                $('#serviceRows .service-row').each(function() {
                    discount += parseFloat($(this).find('.service_discount_amount').val()) || 0;
                });

                $('.discount').val(Math.abs(discount).toFixed(2));
                $('.discounttext').text('$' + Math.abs(discount).toFixed(2));

                $.ajax({
                    url: "{{ route('usertax') }}",
                    data: {
                        customerId: customerId,
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.taxcodetext').empty();
                        $('.service_area_id').val(data.service_area_id);

                        var taxpercent = data.state_tax;
                        var total_amount = subTotal * (taxpercent / 100);
                        $('.tax_total').val(total_amount.toFixed(2));

                        var tax_value = parseFloat($('.tax_total').val()) || 0;
                        var tax_amount = tax_value.toFixed(2);

                        var getTotal = $('.total').val().trim();
                        var total = parseFloat(subTotal) + parseFloat(total_amount);
                        $('.total').val(total.toFixed(2));
                        $('.totaltext').text('$' + total.toFixed(2));

                        $('.taxcodetext').append('' + data.state_tax +
                            '% for ' + data
                            .state_code + ': $' + tax_amount);

                    },
                });

            }

            // Event listener for service cost change
            $(document).on('change', '.service_cost', function() {
                var service_cost = parseFloat($(this).val()) || 0;
                var service_discount = parseFloat($(this).closest('.service-row').find('.service_discount')
                    .val()) || 0;

                $(this).closest('.service-row').find('.service_cost').val(service_cost);
                $(this).closest('.service-row').find('.pre_service_cost').val(service_cost);

                var discount_amount = service_cost * (service_discount / 100);
                var s_total = service_cost - discount_amount;

                $(this).closest('.service-row').find('.service_total_text').text('$' + s_total.toFixed(2));
                $(this).closest('.service-row').find('.service_total').val(s_total.toFixed(2));

                calculateSubTotalAndTax();
            });

            // Event listener for service discount change
            $(document).on('change', '.service_discount', function() {
                var service_discount = parseFloat($(this).val()) || 0;
                var s_cost = parseFloat($(this).closest('.service-row').find('.service_cost').val()) || 0;

                var service_discount_amount = s_cost * (service_discount / 100);
                var s_total = s_cost - service_discount_amount;

                $(this).closest('.service-row').find('.service_total_text').text('$' + s_total.toFixed(2));
                $(this).closest('.service-row').find('.service_total').val(s_total.toFixed(2));
                $(this).closest('.service-row').find('.service_discount_amount').val(service_discount_amount
                    .toFixed(2));

                calculateSubTotalAndTax();
            });

            // Example save button click event to gather service data
            $('#finish').click(function() {
                gatherServicesData();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to add a new part row
            $(document).on('click', '#addPartRow', function() {
                let newPartRow = $('.parts-row:first').clone();
                newPartRow.find('input').val('');
                newPartRow.find('select').prop('selectedIndex', 0);
                newPartRow.find('.product_total_text').text('$0');
                newPartRow.find('.product_total').val('');
                newPartRow.find('.products').removeClass('select2-hidden-accessible').next(
                    '.select2-container').remove();
                $('.products:first').select2();

                $('#partsRows').append(newPartRow);

                // Initialize select2 only for the newly appended row
                newPartRow.find('.products').select2();
            });

            // Function to remove a part row
            $(document).on('click', '.remove-part-row', function() {
                if ($('.parts-row').length > 1) {
                    $(this).closest('.parts-row').remove();
                    calculateTotal();
                }
            });

            // Event listener for product change
            $(document).on('change', '.products', function(event) {
                event.stopPropagation();
                var customerId = $('.selectCustomer').data('customer-id');
                var row = $(this).closest('.parts-row');
                var productId = $(this).val();

                if (productId) {
                    $.ajax({
                        url: "{{ route('product.details') }}",
                        data: {
                            id: productId,
                            customerId: customerId,
                        },
                        type: 'GET',
                        success: function(data) {
                            if (data) {
                                row.find('.product_cost').val(data.product.base_price);
                                row.find('.product_discount').val(data.product.discount);
                                row.find('.product_tax').val(data.product.tax);
                                var productDiscountAmount = data.product.base_price * (data
                                    .product.discount / 100);
                                var productTotal = data.product.base_price -
                                    productDiscountAmount;
                                row.find('.product_total_text').text('$' + productTotal.toFixed(
                                    2));
                                row.find('.product_total').val(productTotal.toFixed(2));
                                calculateTotal();
                            }
                        }
                    });
                } else {
                    // Handle empty product selection
                    row.find('.product_cost').val(0);
                    row.find('.product_discount').val(0);
                    row.find('.product_total_text').text('$0');
                    row.find('.product_total').val(0);
                    calculateTotal();
                }
            });

            // Event listeners for dynamic calculation
            $(document).on('input', '.product_cost, .product_discount', function() {
                calculateTotal();
            });

            // Calculate and update line totals
            function calculateTotal() {
                var subtotal = 0;
                $('.parts-row').each(function() {
                    var cost = parseFloat($(this).find('.product_cost').val()) || 0;
                    var discount = parseFloat($(this).find('.product_discount').val()) || 0;
                    var discountAmount = (cost * discount) / 100;
                    var total = cost - discountAmount;
                    $(this).find('.product_total_text').text('$' + total.toFixed(2));
                    $(this).find('.product_total').val(total.toFixed(2));
                    subtotal += total;
                });

                var discountTotal = parseFloat($('.discount').val()) || 0;
                var allSubTotal = subtotal + discountTotal;
                $('.subtotal').val(allSubTotal.toFixed(2));
                $('.subtotaltext').text('$' + allSubTotal.toFixed(2));

                var customerId = $('.selectCustomer').data('customer-id');
                $.ajax({
                    url: "{{ route('usertax') }}",
                    data: {
                        customerId: customerId
                    },
                    type: 'GET',
                    success: function(data) {
                        var taxpercent = data.state_tax;
                        var taxAmount = allSubTotal * (taxpercent / 100);
                        $('.tax_total').val(taxAmount.toFixed(2));
                        $('.taxcodetext').empty().append('' + data.state_tax + '% for ' + data
                            .state_code + ': $' + taxAmount.toFixed(2));

                        var total = allSubTotal + taxAmount;
                        $('.total').val(total.toFixed(2));
                        $('.totaltext').text('$' + total.toFixed(2));
                    }
                });
            }

            // Initial calculation when the page loads
            calculateTotal();

            // Example save button click event to gather data
            $('#finish').click(function() {
                // Example function to gather and save parts data
                gatherPartsData();
            });

            // Function to gather parts data into an array
            function gatherPartsData() {
                var parts = [];
                $('.parts-row').each(function() {
                    var part = {
                        product_id: $(this).find('.products').val(),
                        product_cost: $(this).find('.product_cost').val(),
                        product_discount: $(this).find('.product_discount').val(),
                        product_total: $(this).find('.product_total').val()
                    };
                    parts.push(part);
                });
                console.log(parts); // Example: log parts data to console
                // Here you can send 'parts' array to your server for further processing
            }
        });
    </script>



    <script>
        $(document).ready(function() {
            var minute = parseInt($('.duration').val(), 10);
            var rawDate = $('#datetime').val();

            if (rawDate) {
                var startMoment = moment(rawDate);
                if (startMoment.isValid()) {
                    var startdate = startMoment.format("Do MMMM YYYY, h:mm A");

                    var newMoment = startMoment.add(minute, 'minutes');

                    var enddate = newMoment.format("h:mm A");

                    $('.schedule_date').text(`${startdate} - ${enddate}`);
                } else {
                    console.error("Invalid date provided.");
                }
            } else {
                console.error("Start date is empty or invalid.");
            }

        });
        $(document).ready(function() {
            $('#warranty_ticket').hide();
            $(document).on('change', '#check_job_type', function() {
                if ($(this).val() == 'in_warranty') {
                    $('#warranty_ticket').show();
                } else {
                    $('#warranty_ticket').hide();
                }
            });


            // checkTechnicianSchedule();

            $(document).on('click', '#add_new_appl', function() {
                $('#show_new_appl').toggle();
            });


            $(document).on('change', '.duration', function() {
                var minute = parseInt($(this).val(), 10);
                var rawDate = $('#datetime').val();

                if (rawDate) {
                    var startMoment = moment(rawDate);
                    if (startMoment.isValid()) {
                        var startdate = startMoment.format("Do MMMM YYYY, h:mm A");

                        var newMoment = startMoment.add(minute, 'minutes');

                        var enddate = newMoment.format("h:mm A");

                        $('.schedule_date').text(`${startdate} - ${enddate}`);
                    } else {
                        console.error("Invalid date provided.");
                    }
                } else {
                    console.error("Start date is empty or invalid.");
                }

                // checkTechnicianSchedule();
            });


        });

        $(document).ready(function() {
            $(document).on('input', '#check_serial_number', function() {
                var serialNumber = $(this).val();
                  var baseUrl = "{{ url('/') }}";
                if (serialNumber.length > 0) {
                    $.ajax({
                        url: '{{ route('check.serial.number') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            serial_number: serialNumber
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var detailsHtml = '';
                                response.data.forEach(function(detail) {
                                    // Check if detail.user exists and is an array
                                    if (Array.isArray(detail.user) && detail.user
                                        .length > 0) {
                                        // Iterate over the users if there are multiple
                                        detail.user.forEach(function(user) {
                                            detailsHtml += `
                        <div class="alert alert-success">
                            <strong>Serial Number matches with existing customer <a href="${baseUrl}/customers/show/${user.id}">${user.name}</a></strong>
                            <!-- Add more fields as necessary -->
                        </div>
                    `;
                                        });
                                    } else {
                                        // Handle case where detail.user is not an array or is empty
                                        detailsHtml += `
                    <div class="alert alert-warning">
                        No user information available for appliance with serial number: ${detail.serial_number}
                    </div>
                `;
                                    }
                                });
                                $('#serial_number_detail').html(detailsHtml);
                            } else {
                                $('#serial_number_detail').html(`
            <div class="alert alert-danger">
                ${response.message}
            </div>
        `);
                            }
                        }
                    });
                } else {
                    $('#serial_number_detail').html('');
                }
            });

            $(document).on('change', '.appliances', function() {
                var selectedOption = $(this).find('option:selected');
                var applianceName = selectedOption.data('name');
                $('.show_appliance').text('Appliance: ' + applianceName);
            });

            // Debugging when changing the manufacturer dropdown
            $(document).on('change', '.manufacturer', function() {
                var selectedOption = $(this).find('option:selected');
                var manufacturerName = selectedOption.data('name');

                $('.show_manufacturer').text('Manufacturer: ' + manufacturerName);
            });

            $(document).on('input', '.model_number', function() {
                var model_number = $(this).val();
                $('.show_model_number').text('Model Number: ' + model_number);
            });

            $(document).on('input', '.serial_number', function() {
                var serial_number = $(this).val();
                $('.show_serial_number').text('Serial Number: ' + serial_number);
            });

            $(document).on('change', '.exist_appl_id', function() {
                var selectedOption = $(this).find('option:selected');
                var appName = selectedOption.data('appname');
                var manuName = selectedOption.data('manuname');
                var model = selectedOption.data('model');
                var serial = selectedOption.data('serial');

                $('.show_appliance').text('Appliance: ' + appName);
                $('.show_manufacturer').text('Manufacturer: ' + manuName);
                $('.show_model_number').text('Model Number: ' + model);
                $('.show_serial_number').text('Serial Number: ' + serial);
            });
        });
    </script>
    <script>
        $(document).ready(function() {



            $(document).on('change', '#duration', function(event) {
                var duration = parseInt($(this).val()); // Parse the first value to an integer
                var time = $('#travel_input').val(); // Get the second value

                var days = 0;
                var hours = 0;
                var minutes = 0;
                if (time.includes('day')) {
                    var parts = time.split(' ');
                    days = parseInt(parts[0]); // Parse the days part to an integer
                }
                if (time.includes('hour')) {
                    var parts = time.split(' ');
                    hours = parseInt(parts[0]); // Parse the hours part to an integer
                }
                if (time.includes('mins')) {
                    var parts = time.split(' ');
                    minutes = parseInt(parts[2]); // Parse the minutes part to an integer
                }

                // Convert duration to hours and minutes
                var durationHours = Math.floor(duration / 60);
                var durationMinutes = duration % 60;

                // Calculate the total time in hours and minutes
                var totalHours = days * 24 + hours + durationHours;
                var totalMinutes = minutes + durationMinutes;

                // Adjust total hours if total minutes exceed 60
                if (totalMinutes >= 60) {
                    totalHours += Math.floor(totalMinutes / 60);
                    totalMinutes %= 60;
                }

                // Display the result
                $('#result_travel').show();
                $('#result_travel').text('Travel time: ' + totalHours + ' hours ' + totalMinutes +
                    ' minutes');
            });


        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '#add_appliance', function() {
                $('.appliancefield').show();
                $('#add_appliance').hide();
            });
            $(document).on('click', '#add_manufaturer', function() {
                $('.manufaturerfield').show();
                $('#add_manufaturer').hide();
            });

            $(document).on('click', '#addAppl', function() {
                var appliance = $('#new_appliance').val();
                $.ajax({
                    url: "{{ url('add/new/appliance') }}",
                    data: {
                        appliance: appliance,
                    },
                    method: 'get',
                    success: function(data) {
                        // Clear existing options
                        $('#appliances').empty();
                        // Check if data is not empty and has appliances array
                        if (data && data && data.length > 0) {
                            // Append new options
                            $.each(data, function(index, value) {
                                $('#appliances').append($('<option value="' + value
                                    .appliance_type_id + '">' + value
                                    .appliance_name + '</option>'));
                            });
                        }
                        $('.appliancefield').hide();
                        $('#add_appliance').show();
                        $('#resp_text').text('Appliance added successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            $(document).on('click', '#addManu', function() {
                var manufacturer = $('#new_manufacturer').val();
                $.ajax({
                    url: "{{ url('add/new/manufacturer') }}",
                    data: {
                        manufacturer: manufacturer,
                    },
                    method: 'get',
                    success: function(data) {
                        // Clear existing options
                        $('#manufacturer').empty();
                        // Check if data is not empty and has appliances array
                        if (data && data && data.length > 0) {
                            // Append new options
                            $.each(data, function(index, value) {
                                $('#manufacturer').append($('<option value="' + value
                                    .id + '">' + value.manufacturer_name +
                                    '</option>'));
                            });
                        }
                        $('.manufaturerfield').hide();
                        $('#add_manufaturer').show();
                        $('#resp_texts').text('Manufacturer added successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            var ajaxRequestForCustomer;
            $('.tab-wizard').steps({
                headerTag: 'h6',
                bodyTag: 'section',
                transitionEffect: 'fade',
                titleTemplate: '<span class="step">#index#</span> #title#',
                labels: {
                    finish: 'Submit Job',
                },
                onStepChanging: function(event, currentIndex, newIndex) {
                    // Allow navigation to the previous step without validation
                    if (newIndex < currentIndex) {
                        return true;
                    }

                    // Check if navigating forward to the next step
                    if (currentIndex === 1) {
                        function checkAllConditions() {
                            var isValid = validateStep2Fields(); // Validate required fields in step 2
                            var isStatusSlotAvailable = $('.status_slot')
                                .val(); // Get the value from the input field

                            // Convert to a boolean to handle potential falsy values
                            var isStatusSlotBool = isStatusSlotAvailable === "true" ||
                                isStatusSlotAvailable === true;

                            // If any condition is false, show an appropriate error message
                            if (!isValid) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: 'Please ensure all required fields are filled before proceeding.',
                                });
                                return false; // Prevent further action
                            }

                            {{-- 
                            if (!isStatusSlotBool) { // If status slot is false or not "true"
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Slot Error',
                                    text: 'Please ensure the status slot is available before proceeding.',
                                });
                                return false; // Prevent further action
                            } --}}

                            return true; // All conditions are met
                        }

                        // Validate the conditions
                        if (!checkAllConditions()) {
                            // If false, do not proceed to the next step and show an error
                            console.log("Validation failed. Staying on the same step.");
                            return false; // Stop further action to prevent navigation
                        }
                    } else if (currentIndex === 2) {
                        // Check if all required fields are filled for step 3
                        var isValid = validateStep3Fields();
                        if (!isValid) {
                            // Required fields are not filled, prevent navigation
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Please fill in warranty fields before proceeding.'
                            });
                            return false; // Prevent navigation to the next step
                        }
                    }

                    // If navigating to step 3, call showAllInformation function
                    if (newIndex == 3) {
                        showAllInformation(newIndex);
                    }

                    // Allow navigation to the next step
                    return true;
                },


                onFinished: function(event, currentIndex) {

                    var form = $('#createScheduleForm')[0];
                    var params = new FormData(form);
                    var newdate = $('#newdate').val();

                    $.ajax({
                        url: "{{ route('schedule.create.post') }}",
                        data: params,
                        method: 'post',
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                .attr('content')
                        },
                        success: function(data) {


                            $('a[href="#finish"]:eq(0)').text(
                                'Submit Job');

                            if (data.start_date) {

                                $('.btn-close').trigger(
                                    'click');
                                var schedule_id = data.schedule_id;
                                $.ajax({
                                    url: "{{ url('get/mail/schedule') }}",
                                    data: {
                                        schedule_id: schedule_id,
                                        type: 'reschedule',
                                    },
                                    type: 'GET',
                                    success: function(data) {},
                                });


                                Swal.fire({
                                    title: "Done",
                                    text: "A job has been reschedule and assigned to technician.",
                                    icon: "success"
                                }).then((
                                    result) => {
                                    if (result.isConfirmed ||
                                        result.dismiss === Swal
                                        .DismissReason.backdrop
                                    ) {
                                        window.location.href =
                                            "{{ route('schedule') }}?date=" +
                                            newdate;
                                    }
                                });

                                var elements = $('[data-slot_time="' +
                                    data.start_date +
                                    '"][data-technician_id="' + data
                                    .technician_id + '"]');

                                elements.empty();

                                elements.append(data
                                    .html)

                            } else if (data ==
                                'false') {

                                $('.btn-close').trigger(
                                    'click');
                              
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Something went wrong !",
                                });

                            } else {

                                $('.btn-close').trigger('click');
                                var schedule_id = data.schedule_id;
                                $.ajax({
                                    url: "{{ url('get/mail/schedule') }}",
                                    data: {
                                        schedule_id: schedule_id,
                                        type: 'schedule',
                                    },
                                    type: 'GET',
                                    success: function(data) {
                                        console.log(data);
                                    },
                                });


                                Swal.fire({
                                    title: "Done",
                                    text: "A new job has been created and assigned to technician.",
                                    icon: "success"
                                }).then((result) => {
                                    // Reload the page after the user clicks the 'OK' button on the success message
                                    if (result.isConfirmed ||
                                        result.isDismissed) {
                                        window.location.href =
                                            "{{ route('schedule') }}?date=" +
                                            newdate;
                                    }
                                });

                            }

                        }
                    });
                },
            });

            $('.searchCustomer').keyup(function() {

                var name = $(this).val().trim();

                $('.customersSuggetions').show();

                $('.pendingJobsSuggetions').show();

                $('.customers').empty();

                $('.rescheduleJobs').empty();

                if (ajaxRequestForCustomer) {
                    ajaxRequestForCustomer.abort();
                }

                if (name.length != 0) {

                    ajaxRequestForCustomer = $.ajax({
                        url: "{{ route('autocomplete.customer') }}",
                        data: {
                            name: name,
                        },
                        beforeSend: function() {

                            $('.rescheduleJobs').text('Processing...');

                            $('.customers').text('Processing...');
                        },
                        type: 'GET',
                        success: function(data) {

                            $('.rescheduleJobs').empty();

                            $('.customers').empty();

                            if (data.customers) {
                                $('.customers').append(data.customers);
                            } else {
                                $('.customers').html(
                                    '<div class="customer_sr_box"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                                );
                            }
                            if (data.pendingJobs) {
                                $('.rescheduleJobs').append(data
                                    .pendingJobs);

                                // Hide elements initially

                                var newyork = $('#newyork');
                                var techAll = $('#techall');
                                var technicianOnly = $('.techName');


                                // Initial check if 'newyork' checkbox is checked
                                if (newyork.prop('checked')) {
                                    $('.pending_jobs2').addClass('d-none');
                                }
                                if (technicianOnly.prop('checked')) {
                                    $('.pending_jobs2').addClass('d-none');
                                }

                                // Function to toggle the order of elements
                                function toggleOrder() {
                                    var ascendingOrder = true; // Flag to track sorting order
                                    var $pendingJobs = $('.pending_jobs2');
                                    // Toggle the sorting order flag
                                    ascendingOrder = !ascendingOrder;

                                    // Sort the elements based on their positions in the DOM
                                    $pendingJobs.sort(function(a, b) {
                                        if (ascendingOrder) {
                                            return $(a).index() - $(
                                                b).index();
                                        } else {
                                            return $(b).index() - $(
                                                a).index();
                                        }
                                    });

                                    // Append the sorted elements to their parent
                                    $pendingJobs.appendTo($pendingJobs.parent());
                                }

                                // Event handler for clicking on #makedescending
                                $('#makedescending').on('click', toggleOrder);

                                // Iterate over each .pending_jobs2 element
                                $('.pending_jobs2').each(function() {
                                    var $element = $(
                                        this); // Store reference to the element
                                    var technicianId = $element.data('technician-id');
                                    var technicianName = $element.data(
                                        'technician-name');
                                    var customerName = $element.data('customer-name');
                                    var customerState_id = $element.data('state-id');
                                    var technician_name = $('.technician_name').val();
                                    $.ajax({
                                        method: 'get',
                                        url: "{{ route('userstate') }}",
                                        data: {
                                            technicianId: technicianId,
                                            technician_name: technician_name
                                        },
                                        success: function(values) {
                                            $('#techonly').change(
                                                function() {
                                                    updateVisibility();
                                                });
                                            var code = values.address
                                                .state_code;
                                            var stateIds = values.result;

                                            $.ajax({
                                                method: 'get',
                                                url: "{{ route('get_tech_state') }}",
                                                data: {
                                                    stateIds: stateIds,
                                                },
                                                success: function(
                                                    data) {
                                                    $('#stateNameArea')
                                                        .text(
                                                            'Show Open jobs in ' +
                                                            data
                                                        );
                                                }
                                            });


                                            // Function to update visibility based on checkboxes
                                            function updateVisibility() {

                                                // Checkboxes and visibility logic
                                                if (newyork.prop(
                                                        'checked') &&
                                                    stateIds
                                                    .includes(
                                                        customerState_id)) {
                                                    $element.removeClass(
                                                        'd-none');
                                                } else if (techAll.prop(
                                                        'checked') &&
                                                    customerName.includes(
                                                        name)) {
                                                    $element.removeClass(
                                                        'd-none');
                                                } else if (technicianOnly
                                                    .prop('checked') &&
                                                    technicianName
                                                    .toLowerCase().includes(
                                                        technician_name
                                                        .toLowerCase())) {
                                                    $element.removeClass(
                                                        'd-none');
                                                } else {
                                                    $element.addClass(
                                                        'd-none');
                                                }
                                            }

                                            // Update visibility initially
                                            updateVisibility();

                                            // Event handler for 'newyork' checkbox change
                                            $('#newyork').change(
                                                function() {
                                                    updateVisibility();
                                                });

                                            // Event handler for 'techall' checkbox change
                                            $('#techall').change(
                                                function() {
                                                    updateVisibility();
                                                });
                                        },
                                        error: function(xhr,
                                            status,
                                            error) {
                                            console.log(
                                                'Error occurred during AJAX request:',
                                                error
                                            );
                                        }
                                    });
                                });


                            } else {
                                $('.rescheduleJobs').html(
                                    '<div class="pending_jobs2"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                                );
                            }
                        }
                    });

                } else {

                    $('.customersSuggetions').hide();

                    $('.pendingJobsSuggetions').hide();

                }



            });

        });
    </script>
    <script>
        $(document).ready(function() {
            // Use setTimeout to wait a short period after the document is ready


            $('#myForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this); // 'this' refers to the form DOM element

                // Make an AJAX request to submit the form data
                $.ajax({
                    url: $(this).attr('action'), // Get the form action attribute
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {

                        if (data.success === true) {
                            // If success is true, close the current modal
                            $('#newCustomer').modal('hide');
                            // Display a success message using SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Done',
                                text: 'New Customer Added Successfully.'
                            }).then(function() {
                                // Reset form fields
                                $('#myForm')[0].reset();
                                // Open another modal
                                $('#create').modal('show');

                                var id = data.user.id;
                                var name = data.user.name;
                                $('.customer_id').val(id);
                                $('.searchCustomer').val(name);
                                // $('.searchCustomer').prop('disabled', true);
                                $('.customersSuggetions').hide();
                                $('.pendingJobsSuggetions').hide();
                                $('.CustomerAdderss').show();

                                var selectElement = $('.customer_address');
                                selectElement.empty();

                                var option = $('<option>', {
                                    value: '',
                                    text: '-- Select Address --'
                                });

                                selectElement.append(option);

                                $.ajax({
                                    url: "{{ route('customer.details') }}",
                                    data: {
                                        id: id,
                                    },
                                    type: 'GET',
                                    success: function(data) {
                                        if (data) {
                                            $('.customer_number_email')
                                                .text(data.mobile + ' / ' +
                                                    data.email);
                                            $('.show_customer_name').text(
                                                data.name);
                                        }
                                        if (data.address && $.isArray(data
                                                .address)) {
                                            $.each(data.address, function(
                                                index, element) {
                                                var addressString =
                                                    $.ucfirst(
                                                        element
                                                        .address_type
                                                    ) + ':  ' +
                                                    element
                                                    .address_line1 +
                                                    ', ' + element
                                                    .city +
                                                    ', ' + element
                                                    .state_name +
                                                    ', ' + element
                                                    .zipcode;
                                                var option = $(
                                                    '<option value="' +
                                                    element
                                                    .address_type +
                                                    ' " selected>' +
                                                    addressString +
                                                    '</option>');

                                                option.attr(
                                                    'data-city',
                                                    element.city
                                                );

                                                selectElement
                                                    .append(option);
                                            });
                                        }
                                        var nextAnchor = $(
                                            'a[href="#next"]')

                                        nextAnchor.trigger('click');
                                    }
                                });
                            });
                        }
                        if (data.success === false) {
                            // Display an error message using SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Operation failed. Please try again.'
                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('Error submitting form data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Operation failed. Please try again.' + error
                        });
                    }
                });
            });

            $('#mobile_phone').keyup(function() {
                var phone = $(this).val();
                if (phone.length >= 10) {
                    $('.customersSuggetions2').show();
                } else {
                    $('.customersSuggetions2').hide();
                }

                $.ajax({
                    url: '{{ url('get/user/by_number') }}',
                    method: 'GET',
                    data: {
                        phone: phone
                    }, // send the phone number to the server
                    success: function(data) {
                        $('.rescheduleJobs').empty();

                        $('.customers2').empty();

                        if (data.customers) {
                            $('.customers2').append(data.customers);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.selectCustomer2', function() {
                $('#newCustomer').modal('hide');
                $('#create').modal('show');
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                $('.customer_id').val(id);
                $('.searchCustomer').val(name);
                // $('.searchCustomer').prop('disabled', true);
                $('.customersSuggetions').hide();
                $('.pendingJobsSuggetions').hide();
                $('.CustomerAdderss').show();

                var selectElement = $('.customer_address');
                selectElement.empty();

                var option = $('<option>', {
                    value: '',
                    text: '-- Select Address --'
                });

                selectElement.append(option);

                $.ajax({
                    url: "{{ route('customer.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            $('.customer_number_email').text(data.mobile + ' / ' + data.email);
                            $('.show_customer_name').text(data.name);
                        }
                        if (data.address && $.isArray(data.address)) {
                            $.each(data.address, function(index, element) {
                                var addressString = $.ucfirst(element.address_type) +
                                    ':  ' +
                                    element.address_line1 + ', ' + element.city +
                                    ', ' + element.state_name + ', ' + element
                                    .zipcode;
                                var option = $('<option>', {
                                    value: addressString,
                                    text: addressString
                                });

                                option.attr('data-city', element.city);

                                selectElement.append(option);
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#jobdetail', function() {
                setTimeout(function() {
                    var nextAnchor = $('a[href="#previous"]');

                    // Trigger click event on the anchor tag with href="#next" three times
                    for (var i = 0; i < 2; i++) {
                        nextAnchor.trigger('click');
                    }
                }); // Adjust the delay value as needed

            });

            $(document).on('click', '#service_parts', function() {
                setTimeout(function() {
                    var nextAnchor = $('a[href="#previous"]');

                    // Trigger click event on the anchor tag with href="#next" three times

                    nextAnchor.trigger('click');

                }); // Adjust the delay value as needed

            });



        });
    </script>

    <script>
        var ajaxRequestForCustomer;

        var ajaxRequestForService;

        var ajaxRequestForProduct;

        $.ucfirst = function(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        };
        //   this function for showing all details in last step 

        function showAllInformation() {
            // Clear existing rows
            $('#service-container').empty();
            $('#product-container').empty();

            // Fetch customer and job information
            var customer_address = $('.customer_address').find(':selected').data('city');
            var selectedText = $('.customer_address').find(':selected').text();
            $('.show_customer_area').text(customer_address + ' Area');
            $('.show_customer_adderss').text(selectedText);
            $('.show_job_title').text($('.job_title').val());
            $('.show_job_code').text($('.job_code').val());
            $('.show_job_information').text($('.appliances').val() + ' / Model No.: ' + $('.model_number').val() +
                ' / Serial Number: ' + $('.serial_number').val());
            $('.show_job_description').text($('.job_description').val());
            $('.show_job_duration').text('Duration: ' + $('.duration option:selected').text());

            // Fetch and display service information
            var services = $('.services').find(':selected');
            services.each(function(index) {
                var service_code = $(this).data('code');
                var serviceText = $(this).text();
                var service_cost = $('.service_cost').eq(index).val();
                var service_discount = $('.service_discount').eq(index).val();
                var service_total = $('.service_total').eq(index).val();

                var serviceRow = `
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <div class="mt-1" style="display: inline-flex;">
                                    <h6 class="font-weight-medium mb-0">${service_code} - ${serviceText}</h6>
                                    &nbsp;
                                    <small class="text-muted">${$('.job_type option:selected').text()}</small>
                                </div>
                            </div>
                            <div class="col-md-1 service_css">
                                <div class="mt-1">$${service_cost}</div>
                            </div>
                            <div class="col-md-1 service_css">
                                <div class="mt-1">$${service_discount}</div>
                            </div>
                            <div class="col-md-2 service_css">
                                <div class="mt-1">$${service_total}</div>
                            </div>
                        </div>
                    `;

                $('#service-container').append(serviceRow);
            });

            // Fetch and display product information
            var products = $('.products').find(':selected');
            products.each(function(index) {
                var product_code = $(this).data('code');
                var productText = $(this).text();
                var product_cost = $('.product_cost').eq(index).val();
                var product_discount = $('.product_discount').eq(index).val();
                var product_total = $('.product_total').eq(index).val();

                var productRow = `
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <div class="mt-1">
                                    <h6 class="font-weight-medium mb-0">${product_code} - ${productText}</h6>
                                </div>
                            </div>
                            <div class="col-md-1 service_css">
                                <div class="mt-1">$${product_cost}</div>
                            </div>
                            <div class="col-md-1 service_css">
                                <div class="mt-1">$${product_discount}</div>
                            </div>
                            <div class="col-md-2 service_css">
                                <div class="mt-1">$${product_total}</div>
                            </div>
                        </div>
                    `;

                $('#product-container').append(productRow);
            });

            // Fetch additional service and product information
            var newservices = $('#new_service').val();
            var newproducts = $('#new_product').val();

            $.ajax({
                url: "{{ url('get/service/product') }}",
                data: {
                    newservices: newservices,
                    newproducts: newproducts,
                },
                type: 'GET',
                success: function(data) {
                    // Populate additional costs, discounts, and totals for new services and products
                    var newServiceRow = `
                            <div class="row mb-2">
                                <div class="col-md-7">
                                    <div class="mt-1" style="display: inline-flex;">
                                        <h6 class="font-weight-medium mb-0">${data.newservices.service_code} - ${data.newservices.service_name}</h6>
                                        &nbsp;
                                        <small class="text-muted">${$('.job_type option:selected').text()}</small>
                                    </div>
                                </div>
                                <div class="col-md-1 service_css">
                                    <div class="mt-1">$${data.newservices.service_cost}</div>
                                </div>
                                <div class="col-md-1 service_css">
                                    <div class="mt-1">$${data.newservices.service_discount}</div>
                                </div>
                                <div class="col-md-2 service_css">
                                    <div class="mt-1">$${data.newservices.service_total}</div>
                                </div>
                            </div>
                        `;

                    $('#service-container').append(newServiceRow);

                    var newProductRow = `
                            <div class="row mb-2">
                                <div class="col-md-7">
                                    <div class="mt-1">
                                        <h6 class="font-weight-medium mb-0">${data.newproducts.product_code} - ${data.newproducts.product_name}</h6>
                                    </div>
                                </div>
                                <div class="col-md-1 service_css">
                                    <div class="mt-1">$${data.newproducts.product_cost}</div>
                                </div>
                                <div class="col-md-1 service_css">
                                    <div class="mt-1">$${data.newproducts.product_discount}</div>
                                </div>
                                <div class="col-md-2 service_css">
                                    <div class="mt-1">$${data.newproducts.product_total}</div>
                                </div>
                            </div>
                        `;

                    $('#product-container').append(newProductRow);
                },
            });
        }

        //   new changes  
        // on clicking on pendingJobs 
        $(document).ready(function() {

            $(document).on('click', '.pending_jobs2', function() {

                var job_id = $(this).attr('data-id');
                var address = $(this).attr('data-address');
                var customerId = $(this).data('customer-id');

                // Append the address value as a selected option to the select element
                $('.customer_address').append('<option value="' + address + '" selected>' + address +
                    '</option>');

                $.ajax({
                    url: "{{ route('customer_appliances') }}",
                    data: {
                        id: customerId, // Ensure 'id' is defined and valid
                    },
                    type: 'GET', // If it's an API, 'GET' is generally correct
                    success: function(data) {
                        if (Array.isArray(data)) {
                            $('.exist_appl_id').empty(); // Clear existing options
                            $('.exist_appl_id').append(
                                '<option value=""> -- Select existing appliances -- </option>'
                            );
                            // Loop over the array to create new options
                            $.each(data, function(index,
                                value) { // 'index' is needed to reference current item
                                var optionText =
                                    `${value.appliance.appliance_name} / ${value.manufacturer.manufacturer_name} / ${value.model_number} / ${value.serial_number}`;
                                $('.exist_appl_id').append('<option value="' + value
                                    .appliance_id + '" data-appName="' + value
                                    .appliance.appliance_name +
                                    '" data-manuName="' + value.manufacturer
                                    .manufacturer_name + '" data-model="' + value
                                    .model_number + '" data-serial="' + value
                                    .serial_number + '">' + optionText + '</option>'
                                );
                            });
                        } else {
                            console.error("Unexpected data format:", data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", error); // Handle errors
                        alert(
                            "An error occurred while fetching the data. Please try again later."
                        ); // Notify user
                    }
                });


                $.ajax({
                    url: "{{ route('pending_jobs') }}",
                    data: {
                        job_id: job_id,
                    },
                    type: 'GET',
                    success: function(data) {
                        $('a[href="#next"]').click();

                        $('.job_id').val(data.id);
                        $('.customer_id').val(data.customer_id);
                        $('.job_title').val(data.job_title);
                        $('.job_code').val(data.job_code);
                        $('.address_type').val(data.address_type);

                        var appliances_id = data.appliances_id;

                        // Iterate through each option in the select element
                        $('.exist_appl_id option').each(function() {
                            // Check if the value of the current option matches the appliances_id
                            if ($(this).val() == appliances_id) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        var priority = data.priority;

                        // Iterate through each option in the select element
                        $('.priority option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == priority) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });



                        $('.job_description').val(data.description);
                        const note = data.job_note?.note;
                        $('.technician_notes').val(note || '');

                        var tags_ids = data.tag_ids;
                        var tagsArray = tags_ids.split(',');
                        $('.tags option').each(function() {
                            if (tagsArray.includes($(this).val())) {
                                $(this).prop('selected', true);
                            }
                        });
                        $('.tags').trigger('change');

                        const duration = data.job_assign?.duration;

                        // Iterate through each option in the select element
                        $('.duration option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == duration || '') {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        // step 3 

                        var warranty_type = data.warranty_type;

                        // Iterate through each option in the select element
                        $('.job_type option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == warranty_type) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        var service_id = data.jobserviceinfo.service_id;

                        // Iterate through each option in the select element
                        $('.services option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == service_id) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        $('.pre_service_id').val(data.jobserviceinfo.service_id);
                        $('.pre_service_cost').val(data.jobserviceinfo.base_price);
                        $('.service_cost').val(data.jobserviceinfo.base_price);
                        $('.pre_service_discount').val(data.jobserviceinfo.discount);
                        $('.service_discount').val(data.jobserviceinfo.discount);
                        $('.service_tax_text').text('$' + data.jobserviceinfo.tax);
                        $('.service_tax').val(data.jobserviceinfo.tax);

                        var s_cost = data.jobserviceinfo.base_price;
                        var s_discount = data.jobserviceinfo.discount;

                        var discount_amount = s_cost * (s_discount / 100);
                        var s_total = s_cost - discount_amount;


                        $('.service_total_text').text('$' + s_total);
                        $('.service_total').val(s_total);

                        var product_id = data.jobproductinfo.product_id;

                        // Iterate through each option in the select element
                        $('.products option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == product_id) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        $('.pre_product_id').val(data.jobproductinfo.product_id);
                        $('.pre_product_cost').val(data.jobproductinfo.base_price);
                        $('.product_cost').val(data.jobproductinfo.base_price);
                        $('.pre_product_discount').val(data.jobproductinfo.discount);
                        $('.product_discount').val(data.jobproductinfo.discount);
                        $('.product_tax_text').text('$' + data.jobproductinfo.tax);
                        $('.product_tax').val(data.jobproductinfo.tax);
                        $('.product_total_text').text('$' + data.jobproductinfo.sub_total);
                        $('.product_total').val(data.jobproductinfo.sub_total);

                        // for total section  services and products
                        var product_tax = $('.product_tax').val();
                        var service_tax = $('.service_tax').val();

                        var service_cost = $('.service_cost').val();
                        var product_cost = $('.product_cost').val();

                        var serviceCost = parseInt(service_cost) || 0;
                        var productCost = parseInt(product_cost) || 0;

                        var getSubTotalVal = $('.subtotal').val().trim();
                        var subTotal = serviceCost + productCost;
                        $('.subtotal').val(Math.abs(subTotal));
                        $('.subtotaltext').text('$' + Math.abs(subTotal));


                        var service_discount = $('.service_discount').val();
                        var product_discount = $('.product_discount').val();
                        var serviceDiscount = parseInt(service_discount) || 0;
                        var productDiscount = parseInt(product_discount) || 0;
                        // Calculate the total discount
                        var discount = serviceDiscount + productDiscount;

                        var getDiscount = $('.discount').val().trim();
                        $('.discount').val(Math.abs(discount));
                        $('.discounttext').text('$' + Math.abs(discount));

                        var service_total = $('.service_total').val();
                        var product_total = $('.product_total').val();

                        var serviceTotal = parseInt(service_total) || 0;
                        var productTotal = parseInt(product_total) || 0;

                        var getTotal = $('.total').val().trim();
                        var total = serviceTotal + productTotal;
                        $('.total').val(Math.abs(total));
                        $('.totaltext').text('$' + Math.abs(total));


                        // customer detail step 4 
                        $('.customer_number_email').text(data.user.mobile + ' / ' + data.user
                            .email);
                        $('.show_customer_name').text(data.user.name);
                        $('.show_customer_area').text(data.city + ' Area');
                        $('.c_address').text(data.address_type + ': ' + data.address + ' ' +
                            data.city +
                            ' ' + data.state + ' ' + data.zipcode);

                        var tax_value = $('.tax_total').val() || 0;
                        var tax_amount = tax_value;

                        $.ajax({
                            url: "{{ route('usertax') }}",
                            data: {
                                customerId: customerId,
                            },
                            type: 'GET',
                            success: function(data) {
                                $('.taxcodetext').empty();

                                $('.taxcodetext').append('' + data.state_tax +
                                    '% for ' + data
                                    .state_code + ': $' + tax_amount);
                            },
                        });

                    }
                });


            });

            $(document).on('click', '.selectCustomer', function() {

                var nextAnchor = $('a[href="#next"]')
                nextAnchor.trigger('click');

                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                $('.customer_id').val(id);
                $('.searchCustomer').val(name);
                // $('.searchCustomer').prop('disabled', true);
                $('.customersSuggetions').hide();
                $('.pendingJobsSuggetions').hide();
                $('.CustomerAdderss').show();

                var selectElement = $('.customer_address');
                selectElement.empty();

                var option = $('<option>', {
                    value: '',
                    text: '-- Select Address --'
                });

                selectElement.append(option);

                $.ajax({
                    url: "{{ route('customer.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            $('.customer_number_email').text(data.mobile + ' / ' + data.email);
                            $('.show_customer_name').text(data.name);
                        }


                        if (data.address && $.isArray(data.address)) {
                            $.each(data.address, function(index, element) {
                                var addressString = $.ucfirst(element.address_type) +
                                    ':  ' +
                                    element.address_line1 + ', ' + element.city +
                                    ', ' + element.state_name + ', ' + element
                                    .zipcode;
                                var option = $('<option value="' + element
                                    .address_type + '" selected>' + addressString +
                                    '</option>');
                                $('#addres_lat').val(element.latitude + ',' + element
                                    .longitude)

                                option.attr('data-city', element.city);

                                selectElement.append(option);

                                var customer_add = element.latitude + ',' + element
                                    .longitude;
                                console.log(customer_add);
                                var tech_id = $('.technician_id').val();

                                $.ajax({
                                    url: '{{ route('travel_time') }}',
                                    type: 'GET', // Use GET instead of get
                                    dataType: 'json',
                                    data: {
                                        tech_id: tech_id,
                                        customer_add: customer_add,
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        // Check if the travel_time key exists in the response
                                        if (response.hasOwnProperty(
                                                'travel_time')) {
                                            // Display the travel time
                                            $('#travel_input').val(response
                                                .travel_time);
                                        } else {
                                            // Handle the case where travel_time is not present in the response
                                            $('#travel_input').val(
                                                'Travel time not available.'
                                            );
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                    }
                                });
                            });
                        }
                    }
                });

                $.ajax({
                    url: "{{ route('customer_appliances') }}",
                    data: {
                        id: id, // Ensure 'id' is defined and valid
                    },
                    type: 'GET', // If it's an API, 'GET' is generally correct
                    success: function(data) {
                        if (Array.isArray(data)) {
                            $('.exist_appl_id').empty(); // Clear existing options
                            $('.exist_appl_id').append(
                                '<option value=""> -- Select existing appliances -- </option>'
                            );
                            // Loop over the array to create new options
                            $.each(data, function(index,
                                value) { // 'index' is needed to reference current item
                                var optionText =
                                    `${value.appliance.appliance_name} / ${value.manufacturer.manufacturer_name} / ${value.model_number} / ${value.serial_number}`;
                                $('.exist_appl_id').append('<option value="' + value
                                    .appliance_id + '" data-appName="' + value
                                    .appliance.appliance_name +
                                    '" data-manuName="' + value.manufacturer
                                    .manufacturer_name + '" data-model="' + value
                                    .model_number + '" data-serial="' + value
                                    .serial_number + '">' + optionText + '</option>'
                                );
                            });
                        } else {
                            console.error("Unexpected data format:", data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", error); // Handle errors
                        alert(
                            "An error occurred while fetching the data. Please try again later."
                        ); // Notify user
                    }
                });



            });


        });

        function checkTechnicianSchedule() {
            // Gather the necessary data for the AJAX request
            var tech_id = $('.technician_id').val();
            var date = $('.datetime').val();
            var duration = $('.duration').val();
            var start_time = $('.technician_id').data('start-time');
            var start_hours = $('.technician_id').data('start-hours');
            var end_hours = $('.technician_id').data('end-hours');

            // Perform the AJAX request
            $.ajax({
                url: '{{ route('technician_schedule') }}', // Adjust this as needed
                type: 'GET',
                dataType: 'json',
                data: {
                    tech_id: tech_id,
                    date: date,
                    duration: duration,
                    start_time: start_time,
                    start_hours: start_hours,
                    end_hours: end_hours,
                },
                success: function(response) {
                    console.log("AJAX Response:", response);

                    // Check the response and set the status slot accordingly
                    if (response.available === false) {
                        $('.status_slot').val('false'); // Set to 'false'
                    } else {
                        $('.status_slot').val('true'); // Set to 'true'
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error:", textStatus, errorThrown); // Log any errors
                }
            });
        }
        // new changes 
        function validateStep2Fields() {
            // Validate all fields in Step 2
            var isValid = true;

            // Check if job title is filled
            var jobTitle = $('.job_title').val().trim();
            if (jobTitle === '') {
                isValid = false;
            }

            // Check if ticket number is filled

            // Get existing appliance ID
            var exist_appl_id = $('.exist_appl_id').val();

            // Get other required fields
            var appliances = $('.appliances').val();
            var manufacturer = $('select[name="manufacturer"]').val();
            var modelNumber = $('.model_number').val().trim();
            var serialNumber = $('.serial_number').val().trim();

            // Condition 1: If exist_appl_id is filled
            if (!exist_appl_id) {
                if (appliances && manufacturer && modelNumber !== '' && serialNumber !== '') {
                    isValid = true; // It's valid if all other fields are filled
                } else {
                    isValid = false; // If neither condition is met, it's invalid
                }
            } else {

                isValid = true;
            }








            // Check if priority is selected
            var priority = $('select[name="priority"]').val();
            if (!priority) {
                isValid = false;
            }
            // Check if job description is filled
            var jobDescription = $('.job_description').val().trim();
            if (jobDescription === '') {
                isValid = false;
            }
            // Check if technician notes is filled
            var technicianNotes = $('.technician_notes').val().trim();
            if (technicianNotes === '') {
                isValid = false;
            }


            return isValid;
        }

        function validateStep3Fields() {
            // Validate all fields in Step 3
            var isValid = true;

            // Check if job type is selected
            var jobType = $('.job_type').val();
            if (jobType === '') {
                isValid = false;
            }



            return isValid;
        }
        //  end new changes 
    </script>

    <script>
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const displayNameInput = document.getElementById('display_name');

        // Function to update the display name field
        function updateDisplayName() {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();

            // Concatenate first and last name
            const displayName = firstName + ' ' + lastName;

            // Set the display name input value
            displayNameInput.value = displayName;
        }

        // Listen for input changes on first and last name fields
        firstNameInput.addEventListener('input', updateDisplayName);
        lastNameInput.addEventListener('input', updateDisplayName);
    </script>



    <script>
        function addNewAddress() {

            var addressCardTwo = document.getElementById("adresscardtwo");

            if (addressCardTwo.style.display === "none") {

                addressCardTwo.style.display = "block";

            } else {

                addressCardTwo.style.display = "none";

            }

            var addressCardTwoone = document.getElementById("adresscardtwo1");

            if (addressCardTwoone.style.display === "none") {

                addressCardTwoone.style.display = "block";

            } else {

                addressCardTwoone.style.display = "none";

            }

        }
    </script>
@endsection
  @if(Route::currentRouteName() != 'dash')
@endsection
 @endif