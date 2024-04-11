@extends('home')
@section('content')

    <link rel="stylesheet" href="{{ url('public/admin/schedule/style.css') }}">

    <link href="{{ asset('public/admin/dist/css/style.min.css') }}" rel="stylesheet" />
    <div class="createScheduleData">

        @if (isset($technician) && !empty($technician))

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body wizard-content">
                        <h6 class="card-subtitle mb-2"></h6>
                        <form action="#" class="tab-wizard vertical wizard-circle mt-1" id="createScheduleForm"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" class="technician_id" name="technician_id" value="{{ $technician->id }}">
                            <input type="hidden" class="datetime" name="datetime" value="{{ $dateTime }}">
                            <input type="hidden" class="customer_id" id="" name="customer_id" value="">
                            <input type="hidden" class="job_id" id="" name="job_id" value="">
                            <input type="hidden" class="scheduleType" id="" name="scheduleType" value="job">
                            <input type="hidden" class="address_type" id="" name="address_type" value="">
                            <!-- Step 1 -->
                            <h6>Customer Information </h6>
                            <section>
                                <div class="row">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-users feather-sm fill-white me-1">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>Add New Customer
                                            </a>
                                        </div>

                                        {{-- to add new customer modal --}}




                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6 customersSuggetions" style="display: none">
                                        <div class="card">
                                            <div class="card-body">
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
                                                    <div class="col-md-9">
                                                        <h5 class="font-weight-medium mb-2" style="position: relative;">
                                                            Reschedule Pending Jobs </h5>
                                                    </div>
                                                    <div class="col-md-3" id="makedescending" style="cursor: pointer;"><i
                                                            class="ri-sort-asc"></i></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 d-flex align-items-baseline"><input
                                                            class="mx-1" type="radio" name="teritory" id="newyork"
                                                            data-state="NY" checked> Show Open jobs in New York</div>
                                                    <div class="col-md-6 d-flex align-items-baseline"><input
                                                            class="mx-1" type="radio" name="teritory"
                                                            id="techall"> Show Open jobs of Technician</div>
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
                                    <div class="col-md-8">
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
                                    <div class="col-md-4">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-ticket"></i> Ticket
                                                Number </h6>
                                            <div class="form-group">
                                                <input type="text" class="form-control job_code"
                                                    placeholder="Job Code here" name="job_code" aria-label=""
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-television"></i>
                                                Appliances </h6>
                                            <div class="form-group">
                                                <select class="form-control appliances" id="exampleFormControlSelect1"
                                                    name="appliances">
                                                    <option disabled>-- Select Appliances -- </option>
                                                    @if (isset($appliances) && !empty($appliances))
                                                        @foreach ($appliances as $value)
                                                            <option value="{{ $value->appliance_id }}">
                                                                {{ $value->appliance_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="text" name="new_appliance"
                                                    class="form-control my-2 appliancefield" style="display:none;"
                                                    placeholder="Add Appliances Here">
                                                <div class="text-primary" style="cursor: pointer;" id="add_appliance">+
                                                    Add New</div>
                                                <div class="text-danger" style="cursor: pointer;display:none;"
                                                    id="remove_appliance">- remove</div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-industry"></i>
                                                Manufacturer </h6>
                                            <div class="form-group">
                                                <select class="form-control manufaturer" id="exampleFormControlSelect1"
                                                    name="manufacturer">
                                                    <option disabled>-- Select Manufacturer -- </option>
                                                    @if (isset($manufacturers) && !empty($manufacturers))
                                                        @foreach ($manufacturers as $value)
                                                            <option value="{{ $value->id }}">
                                                                {{ $value->manufacturer_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="text" name="new_manufacturer"
                                                    class="form-control my-2 manufaturerfield" style="display:none;"
                                                    placeholder="Add Manufaturer Here">
                                                <div class="text-primary" style="cursor: pointer;" id="add_manufaturer">+
                                                    Add New</div>
                                                <div class="text-danger" style="cursor: pointer;display:none;"
                                                    id="remove_manufaturer">- remove</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field required-field"><i
                                                    class="fas fa fa-hashtag"></i> Serial Number </h6>
                                            <div class="form-group">
                                                <input type="text" class="form-control serial_number"
                                                    placeholder="Serial Number here" aria-label=""
                                                    aria-describedby="basic-addon1" name="serial_number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="card-title required-field"><i class="fas fa fa-calendar-check-o"></i>
                                            Duration</h6>
                                        <div class="form-group">
                                            <select class="form-control duration" id="exampleFormControlSelect1"
                                                name="duration">
                                                <option value="240">4 Hours</option>
                                                <option value="180">3 Hours</option>
                                                <option value="120" selected>2 Hours</option>
                                                <option value="60">1 Hours</option>
                                                <option value="30">30 min</option>
                                            </select>
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
                                                <textarea class="form-control job_description" rows="1" placeholder="Text Here..." name="job_description"></textarea>
                                                <small id="textHelp" class="form-text text-muted">All all details of the
                                                    job
                                                    Here</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i
                                                    class="fas fa fa-pencil-square-o"></i> Notes to
                                                Technician </h6>
                                            <div class="form-group">
                                                <textarea class="form-control technician_notes" rows="1" placeholder="Text Here..." name="technician_notes"></textarea>
                                                <small id="textHelp" class="form-text text-muted">Technician must read
                                                    this
                                                    note before start of the job.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title"><i class="far fa fa-photo"></i> Photos / Attachments
                                            </h6>
                                            <div class="input-group">
                                                <input class="form-control" type="file" id="formFile"
                                                    name="photos[]" multiple style="width: 150px;" multiple>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title"><i class="fas fa fa-tags"></i> Tags </h6>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-light-info text-info" type="button">
                                                        <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                                    </button>
                                                </div>
                                                <select class="form-control me-sm-2 tags" id="" name="tags[]"
                                                    multiple="multiple" required>
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->tag_id }}">
                                                            {{ $tag->tag_name }}</option>
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
                                            <div class="form-group">
                                                <select class="form-control job_type" id="exampleFormControlSelect1"
                                                    name="job_type">
                                                    <option value="">Please select</option>
                                                    <option value="in_warranty">In Warranty</option>
                                                    <option value="out_warranty">Out of Warranty</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <h6 class="card-title"><i class="fas ri-dashboard-3-line"></i> Select Services</h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <div class="form-group">
                                                <select class="form-control services" id="exampleFormControlSelect1"
                                                    name="services">
                                                    <option value="" selected>-- Select Services --</option>
                                                    @if (isset($getServices) && !empty($getServices))
                                                        @foreach ($getServices as $value)
                                                            <option value="{{ $value->service_id }}"
                                                                data-code="{{ $value->service_code }}">
                                                                {{ $value->service_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <input type="hidden" class="pre_service_id" value="">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="input-link" id="addnewservice"><a href="#"
                                                    class="card-link">+ Add New</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input type="number" class="form-control service_cost" id="videoUrl1"
                                                placeholder="$0.00" name="service_cost" value="" />
                                            <input type="hidden" class="pre_service_cost" value="">
                                            <small id="name" class="form-text text-muted">Price</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input class="form-control service_discount" type="number"
                                                name="service_discount" value="" placeholder="$0.00">
                                            <input type="hidden" class="pre_service_discount" value="">
                                            <small id="name" class="form-text text-muted">Discount(%)</small>
                                        </div>
                                    </div>
                                    <input class="service_tax" type="hidden" name="service_tax" value="">
                                    <div class="col-md-2">
                                        <div class="mb-2 service_total_text">
                                            $0
                                        </div>
                                        <small id="name" class="form-text text-muted">Line Total</small>
                                        <input class="service_total" type="hidden" name="service_total" value="">
                                    </div>
                                </div>
                                <div class="row" id="new-service" style="display: none;">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <div class="form-group">
                                                <input type="text" name="new_service" id="new_service"
                                                    class="form-control" placeholder="Add New Part">

                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="input-link" id="removenewservice"><a href="#"
                                                    class="card-link text-danger">- Remove</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input type="text" class="form-control new_service_cost"
                                                id="new_service_cost" placeholder="$0.00" name="new_service_cost"
                                                value="" />
                                            <small id="name" class="form-text text-muted">Price</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input class="form-control new_service_discount" id="new_service_discount"
                                                type="number" name="new_service_discount" value=""
                                                placeholder="$0.00">
                                            <small id="" class="form-text text-muted">Discount(%)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input class="form-control new_service_total" id="new_service_total"
                                            type="text" name="new_service_total" value="">
                                        <small id="name" class="form-text text-muted">Line Total</small>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <h6 class="card-title"><i class="fas fa fa-cart-plus"></i> Select Parts</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <div class="form-group">
                                                <select class="form-control products" id="exampleFormControlSelect1"
                                                    name="products">
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
                                        <div class="mb-2">
                                            <div class="input-link" id="addnewpart"><a href="#"
                                                    class="card-link">+ Add New</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input type="number" class="form-control product_cost" id="videoUrl1"
                                                placeholder="$0.00" name="product_cost" value="" />
                                            <input type="hidden" class="pre_product_cost" value="">
                                            <small id="name" class="form-text text-muted">Price</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input class="form-control product_discount" type="number"
                                                name="product_discount" value="" placeholder="$0.00">
                                            <input type="hidden" class="pre_product_discount" value="">
                                            <small id="name" class="form-text text-muted">Discount(%)</small>
                                        </div>
                                    </div>
                                    <input class="product_tax" type="hidden" name="product_tax" value="">

                                    <div class="col-md-2">
                                        <div class="mb-2 product_total_text">
                                            $0
                                        </div>
                                        <small id="name" class="form-text text-muted">Line Total</small>
                                        <input class="product_total" type="hidden" name="product_total" value="">
                                    </div>
                                </div>
                                <div class="row" id="new-part" style="display: none;">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <div class="form-group">
                                                <input type="text" placeholder="Add New Part" class="form-control"
                                                    name="new_product" value="">
                                                <small id="name" class="form-text text-muted">New Part</small>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="input-link" id="removenewpart"><a href="#"
                                                    class="card-link text-danger">- Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input type="text" class="form-control new_product_cost" id="new_product_cost"
                                                placeholder="$0.00" name="new_product_cost" value="" />
                                            <small id="name" class="form-text text-muted">Price</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-2">
                                            <input class="form-control new_product_discount" type="number" id="new_product_discount"
                                                name="new_product_discount" value="" placeholder="$0.00">
                                            <small id="name" class="form-text text-muted">Discount(%)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <input class="form-control new_product_total" type="text" id="new_product_total"
                                            name="new_product_total" value="">
                                        <small id="name" class="form-text text-muted">Line Total</small>
                                    </div>
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
                                        <h4 class="font-weight-medium mb-2">CUSTOMER DETAILS</h4>
                                        <div class="confirm_job_box">
                                            <div class="row">
                                                <div class="col-md-12" style="display: inline-flex;">
                                                    <h6 class="font-weight-medium mb-0 show_customer_name">

                                                    </h6>&nbsp;
                                                    <small class="text-muted show_customer_area"> </small>
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
                                        <h4 class="font-weight-medium mb-2">TECHNICIAN DETAILS</h4>
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
                                    <div class="col-md-9">
                                        <h4 class="font-weight-medium mb-2">JOB DETAILS</h4>
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
                                                <div class="col-md-12 reschedule_job show_job_information"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 reschedule_job ">
                                                    <p class="show_job_description"></p>
                                                    <p class="show_job_duration" style="margin-top: -16px;">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
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
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-0"><label>Tax</label></div>
                                                </div>
                                                <div class="col-md-2 service_css">
                                                    <div class="mt-0"><label>Total</label></div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-7">
                                                    <div class="mt-1" style="display: inline-flex;">
                                                        <h6 class="font-weight-medium mb-0 show_service_code_name"> </h6>
                                                        &nbsp;
                                                        <small class="text-muted show_warranty"> </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_service_cost"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_service_discount"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_service_tax"></div>
                                                </div>
                                                <div class="col-md-2 service_css">
                                                    <div class="mt-1 show_service_total"></div>
                                                </div>
                                            </div>
                                            <div class="row mb-2" id="new_service_list" style="display: none;">
                                                <div class="col-md-7">
                                                    <div class="mt-1" style="display: inline-flex;">
                                                        <h6 class="font-weight-medium mb-0 show_service_code_new"> </h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_service_cost_new"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_service_discount_new"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_service_tax_new">$0</div>
                                                </div>
                                                <div class="col-md-2 service_css">
                                                    <div class="mt-1 show_service_total_new"></div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-7">
                                                    <div class="mt-1">
                                                        <h6 class="font-weight-medium mb-0 show_product_code_name"></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_product_cost"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_product_discount"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_product_tax"></div>
                                                </div>
                                                <div class="col-md-2 service_css">
                                                    <div class="mt-1 show_product_total"></div>
                                                </div>
                                            </div>
                                            <div class="row mb-2" id="new_product_list" style="display: none;">
                                                <div class="col-md-7">
                                                    <div class="mt-1">
                                                        <h6 class="font-weight-medium mb-0 show_product_code_new"></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_product_cost_new"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_product_discount_new"></div>
                                                </div>
                                                <div class="col-md-1 service_css">
                                                    <div class="mt-1 show_product_tax_new">$0</div>
                                                </div>
                                                <div class="col-md-2 service_css">
                                                    <div class="mt-1 show_product_total_new"></div>
                                                </div>
                                            </div>
                                            <div class="row" style="border-top: 2px dotted #343434">
                                                <div class="col-md-7">&nbsp;</div>
                                                <div class="col-md-1">
                                                    <div class="mt-2">&nbsp;</div>
                                                </div>
                                                <div class="col-md-1 total_css">
                                                    <div class="mt-2">
                                                        <p><strong class="show_total_discount"></strong></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 total_css">
                                                    <div class="mt-2">
                                                        <p><strong class="show_total_tax"></strong></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 total_css">
                                                    <div class="mt-2">
                                                        <h4><strong class="show_total"></strong></h4>
                                                    </div>
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
            $(document).on('click', '#add_appliance', function() {
                $('.appliancefield').show();
                $('#add_appliance').hide();
                $('#remove_appliance').show();
            });
            $(document).on('click', '#remove_appliance', function() {
                $('.appliancefield').hide();
                $('#add_appliance').show();
                $('#remove_appliance').hide();
            });
            $(document).on('click', '#add_manufaturer', function() {
                $('.manufaturerfield').show();
                $('#add_manufaturer').hide();
                $('#remove_manufaturer').show();
            });
            $(document).on('click', '#remove_manufaturer', function() {
                $('.manufaturerfield').hide();
                $('#add_manufaturer').show();
                $('#remove_manufaturer').hide();
            });
            $(document).on('click', '#addnewservice', function() {
                $('#new-service').show();
                $('#new_service_list').show();
                $('#addnewservice').hide();
            });
            $(document).on('click', '#removenewservice', function() {
                $('#new-service').hide();
                $('#new_service_list').hide();
                $('#addnewservice').show();
            });
            $(document).on('click', '#addnewpart', function() {
                $('#new-part').show();
                $('#new_product_list').show();
                $('#addnewpart').hide();
            });
            $(document).on('click', '#removenewpart', function() {
                $('#new-part').hide();
                $('#new_product_list').hide();
                $('#addnewpart').show();
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
                    // Check if navigating forward to the next step
                    if (newIndex > currentIndex) {
                        // Assuming the user address step index is 3 (adjust if necessary)
                        if (currentIndex === 0) {
                            // Check if user address is selected
                            var selectedAddress = $('.customer_address').val();
                            if (!selectedAddress) {
                                // User address is not selected, prevent navigation
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Please select a user address before proceeding.'
                                });
                                return false; // Prevent navigation to the next step
                            }
                        } else if (currentIndex === 1) {
                            // Check if all required fields are filled for step 2
                            var isValid = validateStep2Fields();
                            if (!isValid) {
                                // Required fields are not filled, prevent navigation
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Please fill in all required fields before proceeding.'
                                });
                                return false; // Prevent navigation to the next step
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
                    }
                    if (newIndex < currentIndex) {
                        return true;
                    }

                    if (newIndex == 3) {
                        showAllInformation(newIndex);
                    }

                    // Allow navigation to the previous step or to step 3
                    return true;
                },

                onFinished: function(event, currentIndex) {

                    var form = $('#createScheduleForm')[0];
                    var params = new FormData(form);

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

                                Swal.fire({
                                    title: "Success!",
                                    text: "Job Has Been Reschedule",
                                    icon: "success"
                                }).then((
                                    result) => {
                                    if (result.isConfirmed ||
                                        result.dismiss === Swal
                                        .DismissReason.backdrop
                                    ) {
                                        window.location.href =
                                            "{{ route('schedule') }}";
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

                                $('.btn-close').trigger(
                                    'click');

                                Swal.fire({
                                    title: "Success!",
                                    text: "Job Has Been Created",
                                    icon: "success"
                                }).then((result) => {
                                    // Reload the page after the user clicks the 'OK' button on the success message
                                    if (result.isConfirmed ||
                                        result.isDismissed) {
                                        window.location.href =
                                            "{{ route('schedule') }}";
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

                                // Initial check if 'newyork' checkbox is checked
                                if (newyork.prop('checked')) {
                                    $('.pending_jobs2').addClass('d-none');
                                }

                                // Function to toggle the order of elements
                                function toggleOrder() {
                                    var ascendingOrder =
                                        true; // Flag to track sorting order
                                    // Get the list of .pending_jobs2 elements
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
                                    $pendingJobs.appendTo($pendingJobs
                                        .parent());
                                }

                                // Event handler for clicking on #makedescending
                                $('#makedescending').on('click',
                                    toggleOrder);

                                // Iterate over each .pending_jobs2 element
                                $('.pending_jobs2').each(function() {
                                    var $element = $(
                                        this
                                    ); // Store reference to the element
                                    var technicianId = $element
                                        .data('technician-id');
                                    $.ajax({
                                        method: 'get',
                                        url: "{{ route('userstate') }}",
                                        data: {
                                            technicianId: technicianId
                                        },
                                        success: function(
                                            values) {
                                            var code =
                                                values
                                                .state_code;

                                            // Function to update visibility based on checkboxes
                                            function updateVisibility() {
                                                if (newyork
                                                    .prop(
                                                        'checked'
                                                    ) &&
                                                    code ===
                                                    'NY'
                                                ) {
                                                    $element
                                                        .removeClass(
                                                            'd-none'
                                                        );
                                                } else if (
                                                    techAll
                                                    .prop(
                                                        'checked'
                                                    )
                                                ) {
                                                    $element
                                                        .removeClass(
                                                            'd-none'
                                                        );
                                                } else {
                                                    $element
                                                        .addClass(
                                                            'd-none'
                                                        );
                                                }
                                            }

                                            // Update visibility initially
                                            updateVisibility
                                                ();

                                            // Event handler for 'newyork' checkbox change
                                            $('#newyork')
                                                .change(
                                                    function() {
                                                        updateVisibility
                                                            ();
                                                    });

                                            // Event handler for 'techall' checkbox change
                                            $('#techall')
                                                .change(
                                                    function() {
                                                        updateVisibility
                                                            ();
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
                        // Handle success response here
                        console.log(data.success); // Logging the value of data.success

                        if (data.success === true) {
                            // If success is true, close the current modal
                            $('#newCustomer').modal('hide');
                            // Display a success message using SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Operation completed successfully.'
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
                                                    '<option>', {
                                                        value: element
                                                            .address_type,
                                                        text: addressString
                                                    });

                                                option.attr(
                                                    'data-city',
                                                    element.city
                                                );

                                                selectElement
                                                    .append(option);
                                            });
                                        }
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
                    url: 'get/user/by_number',
                    method: 'GET',
                    data: {
                        phone: phone
                    }, // send the phone number to the server
                    success: function(data) {
                        // Handle the response from the server here
                        console.log(data);
                        $('.rescheduleJobs').empty();

                        $('.customers2').empty();

                        if (data.customers) {
                            $('.customers2').append(data.customers);
                        } else {
                            $('.customers2').html(
                                '<div class="customer_sr_box"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                            );
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
                                    value: element.address_type,
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

        function showAllInformation(params) {

            var customer_address = $('.customer_address').find(':selected');
            var cityValue = customer_address.data('city');
            var selectedText = customer_address.text();
            $('.show_customer_area').text(cityValue + ' Area');
            $('.show_customer_adderss').text(selectedText);

            $('.show_job_title').text($('.job_title').val());
            $('.show_job_code').text($('.job_code').val());
            $('.show_job_information').text($('.appliances').val() + ' / Model No.: ' + $('.model_number').val() +
                ' / Serial Number: ' + $('.serial_number').val());
            $('.show_job_description').text($('.job_description').val());
            $('.show_job_duration').text('Duration: ' + $('.duration option:selected').text());

            var services = $('.services').find(':selected');
            var newservices = $('#new_service').val();
            var services_code = services.data('code');
            var servicesText = services.text();
            var service_cost = $('.service_cost').val();
            var newservice_cost = $('.new_service_cost').val();
            var service_discount = $('.service_discount').val();
            var newservice_discount = $('.new_service_discount').val();
            var service_tax = $('.service_tax').val();
            var service_total = $('.service_total').val();
            var newservice_total = $('.new_service_total').val();

            $('.show_service_code_name').text(services_code + ' - ' + servicesText);
            $('.show_service_code_new').text('CODE' + ' - ' + newservices);
            $('.show_warranty').text($('.job_type option:selected').text());
            $('.show_service_cost').text('$' + service_cost);
            $('.show_service_cost_new').text('$' + newservice_cost);
            $('.show_service_discount').text('$' + service_discount);
            $('.show_service_discount_new').text('$' + newservice_discount);
            $('.show_service_tax').text('$' + service_tax);
            $('.show_service_total').text('$' + service_total);
            $('.show_service_total_new').text('$' + newservice_total);

            var products = $('.products').find(':selected');
            var newproducts = $('#new_products').val();
            var products_code = products.data('code');
            var productsText = products.text();
            var product_cost = $('.product_cost').val();
            var newproduct_cost = $('.new_product_cost').val();
            var product_discount = $('.product_discount').val();
            var newproduct_discount = $('.new_product_discount').val();
            var product_tax = $('.product_tax').val();
            var product_total = $('.product_total').val();
            var newproduct_total = $('.new_product_total').val();

            $('.show_product_code_name').text(products_code + ' - ' + productsText);
            $('.show_product_code_new').text('CODE' + ' - ' + newproducts);
            $('.show_product_cost').text('$' + product_cost);
            $('.show_product_cost_new').text('$' + newproduct_cost);
            $('.show_product_discount').text('$' + product_discount);
            $('.show_product_discount_new').text('$' + newproduct_discount);
            $('.show_product_tax').text('$' + product_tax);
            $('.show_product_total').text('$' + product_total);
            $('.show_product_total_new').text('$' + newproduct_total);

            var getDiscount = $('.discount').val().trim();
            var getTax = parseInt(service_tax) - parseInt(product_tax);
            var getTotal = $('.total').val().trim();


            $('.show_total_discount').text(getDiscount >= 0 ? '$' + getDiscount : '$' + Math.abs(getDiscount));
            $('.show_total_tax').text(getTax >= 0 ? '$' + getTax : '$' + Math.abs(getTax));
            $('.show_total').text(getTotal >= 0 ? '$' + getTotal : '$' + Math.abs(getTotal));

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
                        $('.appliances option').each(function() {
                            // Check if the value of the current option matches the appliances_id
                            if ($(this).val() == appliances_id) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        var manufaturer_id = data.job_details.manufacturer_id;

                        // Iterate through each option in the select element
                        $('.manufaturer option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == manufaturer_id) {
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

                        $('.model_number').val(data.job_details.model_number);
                        $('.serial_number').val(data.job_details.serial_number);

                        var duration = data.job_assign.duration;

                        // Iterate through each option in the select element
                        $('.duration option').each(function() {
                            // Check if the value of the current option matches the manufaturer_id
                            if ($(this).val() == duration) {
                                // Set the selected attribute for the matching option
                                $(this).prop('selected', true);
                            }
                        });

                        $('.job_description').val(data.description);
                        $('.technician_notes').val(data.job_note.note);
                        $('.tags').val(data.tag_ids);

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
                        $('.service_total_text').text('$' + data.jobserviceinfo.sub_total);
                        $('.service_total').val(data.jobserviceinfo.sub_total);

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

                        // for total section  services
                        var service_cost = $('.service_cost').val();

                        var service_discount = $('.service_discount').val();

                        var service_tax = $('.service_tax').val();

                        var getSubTotalVal = $('.subtotal').val().trim();
                        var subTotal = parseInt(getSubTotalVal) - parseInt(service_cost);
                        $('.subtotal').val(Math.abs(subTotal));
                        $('.subtotaltext').text('$' + Math.abs(subTotal));

                        var getDiscount = $('.discount').val().trim();
                        var discount = parseInt(getDiscount) - parseInt(service_discount);
                        $('.discount').val(Math.abs(discount));
                        $('.discounttext').text('$' + Math.abs(discount));

                        var getTotal = $('.total').val().trim();
                        var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(
                            service_discount) - parseInt(
                            service_tax);
                        $('.total').val(Math.abs(total));
                        $('.totaltext').text('$' + Math.abs(total));

                        // product 

                        var product_cost = $('.product_cost').val();

                        var product_discount = $('.product_discount').val();

                        var product_tax = $('.product_tax').val();

                        var getSubTotalVal = $('.subtotal').val().trim();
                        var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                        $('.subtotal').val(Math.abs(subTotal));
                        $('.subtotaltext').text('$' + Math.abs(subTotal));

                        var getDiscount = $('.discount').val().trim();
                        var discount = parseInt(getDiscount) - parseInt(product_discount);
                        $('.discount').val(Math.abs(discount));
                        $('.discounttext').text('$' + Math.abs(discount));

                        var getTotal = $('.total').val().trim();
                        var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(
                            product_discount) - parseInt(
                            product_tax);
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
                                    .state_code + '');
                            },
                        });

                    }
                });


            });

            $(document).on('click', '.selectCustomer', function() {

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
                                    value: element.address_type,
                                    text: addressString
                                });

                                option.attr('data-city', element.city);

                                selectElement.append(option);
                            });
                        }
                    }
                });
            });

            $(document).on('change', '.services', function(event) {

                event.stopPropagation();
                var customerId = $('.selectCustomer').data('customer-id');
                console.log(customerId);

                var id = $(this).val().trim();

                if ($('.pre_service_id').val() != '') {

                    var service_cost = $('.service_cost').val();

                    var service_discount = $('.service_discount').val();

                    var service_tax = $('.service_tax').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(service_cost);
                    $('.subtotal').val(Math.abs(subTotal));
                    $('.subtotaltext').text('$' + Math.abs(subTotal));

                    var getDiscount = $('.discount').val().trim();
                    var discount = parseInt(getDiscount) - parseInt(service_discount);
                    $('.discount').val(Math.abs(discount));
                    $('.discounttext').text('$' + Math.abs(discount));

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(service_discount) -
                        parseInt(
                            service_tax);
                    $('.total').val(Math.abs(total));
                    $('.totaltext').text('$' + Math.abs(total));

                    $('.service_cost').val(0);

                    $('.service_discount').val(0);

                    $('.service_tax_text').text('$0');

                    $('.service_total_text').text('$0');

                    $('.pre_service_id').val('');
                }

                if (id.length != 0) {

                    if (ajaxRequestForService) {
                        ajaxRequestForService.abort();
                    }

                    ajaxRequestForService = $.ajax({
                        url: "{{ route('services.details') }}",
                        data: {
                            id: id,
                        },
                        type: 'GET',
                        success: function(data) {

                            if (data) {

                                $('.pre_service_id').val(id);

                                $('.service_cost').val(data.service_cost);
                                $('.pre_service_cost').val(data.service_cost);

                                $('.service_discount').val(data.service_discount);
                                $('.pre_service_discount').val(data.service_discount);

                                $('.service_tax_text').text('$' + data.service_tax);
                                $('.service_tax').val(data.service_tax);

                                $('.service_total_text').text('$' + data.service_cost);
                                $('.service_total').val(data.service_cost);

                                var getSubTotalVal = $('.subtotal').val().trim();
                                var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                    .service_cost);
                                $('.subtotal').val(Math.abs(subTotal));
                                $('.subtotaltext').text('$' + Math.abs(subTotal));

                                var getDiscount = $('.discount').val().trim();
                                var discount = parseInt(getDiscount) + parseInt(data
                                    .service_discount);
                                $('.discount').val(Math.abs(discount));
                                $('.discounttext').text('$' + Math.abs(discount));

                                var getTotal = $('.total').val().trim();
                                var total = parseInt(getTotal) + parseInt(data.service_cost) -
                                    parseInt(data
                                        .service_discount) + parseInt(data.service_tax);
                                $('.total').val(Math.abs(total));
                                $('.totaltext').text('$' + Math.abs(total));

                            }

                        }
                    });
                }

                $.ajax({
                    url: "{{ route('usertax') }}",
                    data: {
                        customerId: customerId,
                    },
                    type: 'GET',
                    success: function(data) {
                        console.log(data);
                        $('.taxcodetext').empty();
                        console.log(data);
                        $('.taxcodetext').append('' + data.state_tax + '% for ' + data
                            .state_code + '');
                    },
                });

            });

            $(document).on('change', '.service_cost', function() {

                var pre_service_cost = parseInt($('.pre_service_cost').val());

                var service_cost = $(this).val();

                if ((/-/.test(service_cost) || /\./.test(service_cost) || isNaN(service_cost))) {
                    $(this).val(pre_service_cost);
                    return true;
                }

                $('.service_cost').val(service_cost);
                $('.pre_service_cost').val(service_cost);

                $('.service_total_text').text('$' + service_cost);
                $('.service_total').val(service_cost);

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(pre_service_cost);
                var currentSubTotal = parseInt(subTotal) + parseInt(service_cost);
                $('.subtotal').val(currentSubTotal);
                $('.subtotaltext').text('$' + currentSubTotal);

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(pre_service_cost);
                var currentTotal = parseInt(total) + parseInt(service_cost);
                $('.total').val(currentTotal);
                $('.totaltext').text('$' + currentTotal);

            });

            $(document).on('change', '.service_discount', function() {

                var service_discount = $(this).val();

                var pre_service_discount = parseInt($('.pre_service_discount').val());

                if ((/-/.test(service_discount) || /\./.test(service_discount) || isNaN(
                    service_discount))) {
                    $(this).val(pre_service_discount);
                    return true;
                }

                var getDiscount = parseInt($('.discount').val());
                var totalDiscount = parseInt(getDiscount) - parseInt(pre_service_discount);
                var finnalDiscount = parseInt(totalDiscount) + parseInt(service_discount);
                $('.discount').val(finnalDiscount);
                $('.discounttext').text('$' + finnalDiscount);

                var getPreTotal = parseInt($('.total').val());
                var getTotal = parseInt(getPreTotal) + parseInt(pre_service_discount);
                var total = parseInt(getTotal) - parseInt(service_discount);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.pre_service_discount').val(service_discount);

            });

            $(document).on('change', '.products', function(event) {

                event.stopPropagation();
                var customerId = $('.selectCustomer').data('customer-id');

                var id = $(this).val().trim();

                if ($('.pre_product_id').val() != '') {

                    var product_cost = $('.product_cost').val();

                    var product_discount = $('.product_discount').val();

                    var product_tax = $('.product_tax').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                    $('.subtotal').val(Math.abs(subTotal));
                    $('.subtotaltext').text('$' + Math.abs(subTotal));

                    var getDiscount = $('.discount').val().trim();
                    var discount = parseInt(getDiscount) - parseInt(product_discount);
                    $('.discount').val(Math.abs(discount));
                    $('.discounttext').text('$' + Math.abs(discount));

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(product_discount) -
                        parseInt(
                            product_tax);
                    $('.total').val(Math.abs(total));
                    $('.totaltext').text('$' + Math.abs(total));

                    $('.product_cost').val(0);

                    $('.product_discount').val(0);

                    $('.product_tax_text').text('$0');

                    $('.product_total_text').text('$0');

                    $('.pre_product_id').val('');
                }

                if (id.length != 0) {

                    if (ajaxRequestForProduct) {
                        ajaxRequestForProduct.abort();
                    }

                    ajaxRequestForProduct = $.ajax({
                        url: "{{ route('product.details') }}",
                        data: {
                            id: id,
                        },
                        type: 'GET',
                        success: function(data) {

                            if (data) {

                                $('.pre_product_id').val(id);

                                $('.product_cost').val(data.base_price);
                                $('.pre_product_cost').val(data.base_price);

                                $('.product_discount').val(data.discount);
                                $('.pre_product_discount').val(data.discount);

                                $('.product_tax_text').text('$' + data.tax);
                                $('.product_tax').val(data.tax);

                                $('.product_total_text').text('$' + data.base_price);
                                $('.product_total').val(data.base_price);

                                var getSubTotalVal = $('.subtotal').val().trim();
                                var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                    .base_price);
                                $('.subtotal').val(Math.abs(subTotal));
                                $('.subtotaltext').text('$' + Math.abs(subTotal));

                                var getDiscount = $('.discount').val().trim();
                                var discount = parseInt(getDiscount) + parseInt(data.discount);
                                $('.discount').val(Math.abs(discount));
                                $('.discounttext').text('$' + Math.abs(discount));

                                var getTotal = $('.total').val().trim();
                                var total = parseInt(getTotal) + parseInt(data.base_price) -
                                    parseInt(data.discount) + parseInt(data.tax);

                                console.log(total, getTotal, data.base_price, data.discount,
                                    data.tax);
                                $('.total').val(Math.abs(total));
                                $('.totaltext').text('$' + Math.abs(total));

                            }

                        }
                    });
                }
                $.ajax({
                    url: "{{ route('usertax') }}",
                    data: {
                        customerId: customerId,
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.taxcodetext').empty();

                        $('.taxcodetext').append('' + data.state_tax + '% for ' + data
                            .state_code + '');
                    },
                });

            });

            $(document).on('change', '.product_cost', function() {

                var pre_product_cost = parseInt($('.pre_product_cost').val());

                var product_cost = $(this).val();

                if ((/-/.test(product_cost) || /\./.test(product_cost) || isNaN(product_cost))) {
                    $(this).val(pre_product_cost);
                    return true;
                }

                $('.product_cost').val(product_cost);
                $('.pre_product_cost').val(product_cost);

                $('.product_total_text').text('$' + product_cost);
                $('.product_total').val(product_cost);

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(pre_product_cost);
                var currentSubTotal = parseInt(subTotal) + parseInt(product_cost);
                $('.subtotal').val(currentSubTotal);
                $('.subtotaltext').text('$' + currentSubTotal);

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(pre_product_cost);
                var currentTotal = parseInt(total) + parseInt(product_cost);
                $('.total').val(currentTotal);
                $('.totaltext').text('$' + currentTotal);


            });

            $(document).on('change', '.product_discount', function() {

                var product_discount = $(this).val();

                var pre_product_discount = parseInt($('.pre_product_discount').val());

                if ((/-/.test(product_discount) || /\./.test(product_discount) || isNaN(
                    product_discount))) {
                    $(this).val(pre_product_discount);
                    return true;
                }

                var getDiscount = parseInt($('.discount').val());
                var totalDiscount = parseInt(getDiscount) - parseInt(pre_product_discount);
                var finnalDiscount = parseInt(totalDiscount) + parseInt(product_discount);
                $('.discount').val(finnalDiscount);
                $('.discounttext').text('$' + finnalDiscount);

                var getPreTotal = parseInt($('.total').val());
                var getTotal = parseInt(getPreTotal) + parseInt(pre_product_discount);
                var total = parseInt(getTotal) - parseInt(product_discount);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.pre_product_discount').val(product_discount);

            });

            var previousServiceCost = 0; // Variable to store the previous service cost

            $('#new_service_cost').change(function() {
                var newServiceCost = parseInt($(this).val().trim()) || 0;

                var subtotal = parseInt($('.subtotal').val().trim()) || 0;
                var updatedSubtotal = subtotal - previousServiceCost;

                // Add the new service cost to the existing subtotal
                var updatedSubtotal1 = updatedSubtotal + newServiceCost;

                $('.subtotal').val(updatedSubtotal1); // Update the subtotal value displayed in the UI
                $('.subtotaltext').text('$' +
                updatedSubtotal1); // Update the subtotal text displayed in the UI with a dollar sign

                // Store the current service cost as the previous service cost for the next change event
                previousServiceCost = newServiceCost;
            });

            var previousdiscount = 0; // Variable to store the previous service cost

            $('#new_service_discount').change(function() {
                var new_service_discount = parseInt($(this).val().trim()) || 0;

                var discount = parseInt($('.discount').val().trim()) || 0;
                var updateddiscount = discount - previousdiscount;

                // Add the new service cost to the existing subtotal
                var updateddiscount1 = updateddiscount + new_service_discount;

                $('.discount').val(updateddiscount1); // Update the subtotal value displayed in the UI
                $('.discounttext').text('$' +
                updateddiscount1); // Update the subtotal text displayed in the UI with a dollar sign

                // Store the current service cost as the previous service cost for the next change event
                previousdiscount = new_service_discount;
            });

            var previoustotal = 0; // Variable to store the previous service cost

            $('#new_service_total').change(function() {
                var new_service_total = parseInt($(this).val().trim()) || 0;

                var total = parseInt($('.total').val().trim()) || 0;
                var updatedtotal = total - previoustotal;

                // Add the new service cost to the existing subtotal
               var updatedtotal1 = updatedtotal + new_service_total;

                $('.total').val(updatedtotal1); // Update the subtotal value displayed in the UI
                $('.totaltext').text('$' +
                updatedtotal1); // Update the subtotal text displayed in the UI with a dollar sign

                // Store the current service cost as the previous service cost for the next change event
                previoustotal = new_service_total;
            });

            var previousProductCost = 0; // Variable to store the previous service cost

            $('#new_product_cost').change(function() {
                var newProductCost = parseInt($(this).val().trim()) || 0;

                var subtotal = parseInt($('.subtotal').val().trim()) || 0;
                var updatedSubtotal = subtotal - previousProductCost;

                // Add the new service cost to the existing subtotal
                var updatedSubtotal1 = updatedSubtotal + newProductCost;

                $('.subtotal').val(updatedSubtotal1); // Update the subtotal value displayed in the UI
                $('.subtotaltext').text('$' +
                updatedSubtotal1); // Update the subtotal text displayed in the UI with a dollar sign

                // Store the current service cost as the previous service cost for the next change event
                previousProductCost = newProductCost;
            });

            var Productpreviousdiscount = 0; // Variable to store the previous service cost

            $('#new_product_discount').change(function() {
                var new_Product_discount = parseInt($(this).val().trim()) || 0;

                var discount = parseInt($('.discount').val().trim()) || 0;
                var updateddiscount = discount - Productpreviousdiscount;

                // Add the new service cost to the existing subtotal
                var updateddiscount1 = updateddiscount + new_Product_discount;

                $('.discount').val(updateddiscount1); // Update the subtotal value displayed in the UI
                $('.discounttext').text('$' +
                updateddiscount1); // Update the subtotal text displayed in the UI with a dollar sign

                // Store the current service cost as the previous service cost for the next change event
                Productpreviousdiscount = new_Product_discount;
            });

            var Productprevioustotal = 0; // Variable to store the previous service cost

            $('#new_product_total').change(function() {
                var new_Product_total = parseInt($(this).val().trim()) || 0;

                var total = parseInt($('.total').val().trim()) || 0;
                var updatedtotal = total - Productprevioustotal;

                // Add the new service cost to the existing subtotal
                var updatedtotal1 = updatedtotal + new_Product_total;

                $('.total').val(updatedtotal1); // Update the subtotal value displayed in the UI
                $('.totaltext').text('$' +
                updatedtotal1); // Update the subtotal text displayed in the UI with a dollar sign

                // Store the current service cost as the previous service cost for the next change event
                Productprevioustotal = new_Product_total;
            });




        });


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
            var ticketNumber = $('.job_code').val().trim();
            if (ticketNumber === '') {
                isValid = false;
            }

            // Check if appliances is selected
            var appliances = $('.appliances').val();
            if (!appliances) {
                isValid = false;
            }

            // Check if manufacturer is selected
            var manufacturer = $('select[name="manufacturer"]').val();
            if (!manufacturer) {
                isValid = false;
            }

            // Check if priority is selected
            var priority = $('select[name="priority"]').val();
            if (!priority) {
                isValid = false;
            }

            // Check if model number is filled
            var modelNumber = $('.model_number').val().trim();
            if (modelNumber === '') {
                isValid = false;
            }

            // Check if serial number is filled
            var serialNumber = $('.serial_number').val().trim();
            if (serialNumber === '') {
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
@endsection
@endsection
