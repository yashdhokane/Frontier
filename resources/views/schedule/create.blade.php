@if (isset($technician) && !empty($technician))

<div class="container-fluid">
    <div class="card">
        <div class="card-body wizard-content">
            <h6 class="card-subtitle mb-2"></h6>
            <form action="#" class="tab-wizard vertical wizard-circle mt-1" id="createScheduleForm"
                enctype="multipart/form-data">

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
                                    <input type="text" class="form-control searchCustomer" name="customer_name"
                                        placeholder="Customer or Pending job" aria-label=""
                                        aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-0">
                                <a href="#." id="btn-add-contact1" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#newCustomer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
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
                                            <h5 class="font-weight-medium mb-2" style="position: relative;">Reschedule
                                                Pending Jobs </h5>
                                        </div>
                                        <div class="col-md-3" id="makedescending" style="cursor: pointer;"><i
                                                class="ri-sort-asc"></i></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 d-flex align-items-baseline"><input class="mx-1"
                                                type="radio" name="teritory" id="newyork" data-state="NY" checked> Show
                                            Open jobs in New York</div>
                                        <div class="col-md-6 d-flex align-items-baseline"><input class="mx-1"
                                                type="radio" name="teritory" id="techall"> Show Open jobs of Technician
                                        </div>
                                    </div>
                                    <div class="rescheduleJobs">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 CustomerAdderss" style="display: none">
                            <div class="mb-2">
                                <h6 class="card-title"><i class="fas fa-map-marker" aria-hidden="true"></i> Customer
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
                                <h6 class="card-title required-field"><i class="fas fa fa-sticky-note"></i> Job Title
                                </h6>
                                <div class="form-group">
                                    <input type="text" name="job_title" class="form-control job_title"
                                        placeholder="Add Job Title Here" aria-label="" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title required-field"><i class="fas fa fa-ticket"></i> Job Number </h6>
                                <div class="form-group">
                                    <input type="text" class="form-control job_code" placeholder="Job Code here"
                                        name="job_code" aria-label="" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title required-field"><i class="fas fa fa-television"></i> Appliances
                                </h6>
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
                                    <div class="input-link"><a href="#" class="card-link">+ Add New</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title required-field"><i class="fas fa fa-industry"></i> Manufacturer
                                </h6>
                                <div class="form-group">
                                    <select class="form-control manufaturer" id="exampleFormControlSelect1"
                                        name="manufacturer">
                                        <option disabled>-- Select Manufacturer -- </option>
                                        @if (isset($manufacturers) && !empty($manufacturers))
                                        @foreach ($manufacturers as $value)
                                        <option value="{{ $value->id }}">{{ $value->manufacturer_name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="input-link"><a href="#" class="card-link">+ Add New</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title required-field"><i class="fas fa-user"></i> Priority </h6>
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
                                <h6 class="card-title required-field"><i class="fas fa fa-hashtag"></i> Model Number
                                </h6>
                                <div class="form-group">
                                    <input type="text" class="form-control model_number" placeholder="Model Number here"
                                        aria-label="" aria-describedby="basic-addon1" name="model_number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title required-field required-field"><i class="fas fa fa-hashtag"></i>
                                    Serial Number </h6>
                                <div class="form-group">
                                    <input type="text" class="form-control serial_number"
                                        placeholder="Serial Number here" aria-label="" aria-describedby="basic-addon1"
                                        name="serial_number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="card-title required-field"><i class="fas fa fa-calendar-check-o"></i> Duration
                            </h6>
                            <div class="form-group">
                                <select class="form-control duration" id="exampleFormControlSelect1" name="duration">
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
                                <h6 class="card-title required-field"><i class="fas fa fa-pencil-square-o"></i> Job
                                    Description
                                </h6>
                                <div class="form-group">
                                    <textarea class="form-control job_description" rows="1" placeholder="Text Here..."
                                        name="job_description"></textarea>
                                    <small id="textHelp" class="form-text text-muted">All all details of the job
                                        Here</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title required-field"><i class="fas fa fa-pencil-square-o"></i> Notes to
                                    Technician </h6>
                                <div class="form-group">
                                    <textarea class="form-control technician_notes" rows="1" placeholder="Text Here..."
                                        name="technician_notes"></textarea>
                                    <small id="textHelp" class="form-text text-muted">Technician must read this
                                        note before start of the job.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mt-0 mb-3">
                                <h6 class="card-title"><i class="far fa fa-photo"></i> Photos / Attachments </h6>
                                <div class="input-group">
                                    <input class="form-control" type="file" id="formFile" name="photos[]" multiple
                                        style="width: 150px;" multiple>
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
                                    <select class="form-control me-sm-2 tags" id="" name="tags[]" multiple="multiple"
                                        required>
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
                                <h6 class="card-title required-field"><i class="fas fa fa-check-square"></i> Warranty
                                </h6>
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
                                        <option value="{{ $value->service_id }}" data-code="{{ $value->service_code }}">
                                            {{ $value->service_name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>

                                    <input type="hidden" class="pre_service_id" value="">
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="input-link"><a href="#" class="card-link">+ Add New</a></div>
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
                                <input class="form-control service_discount" type="number" name="service_discount"
                                    value="" placeholder="$0.00">
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
                                        <option value="{{ $value->product_id }}" data-code="{{ $value->product_code }}">
                                            {{ $value->product_name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" class="pre_product_id" value="">
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="input-link"><a href="#" class="card-link">+ Add New</a></div>
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
                                <input class="form-control product_discount" type="number" name="product_discount"
                                    value="" placeholder="$0.00">
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
                    <div class="row mb-2" style="border-top: 1px solid #343434;">
                        <div class="col-md-4 mt-2">&nbsp;</div>
                        <div class="col-md-4 mt-2">&nbsp;</div>
                        <div class="col-md-4 mt-2 text-right" style="text-align: right;padding-right: 36px;">
                            <h5 style="display: inline-flex;">Sub Total:&nbsp;&nbsp;<div class="subtotaltext">$0
                                </div>
                            </h5><br>
                            <h5 style="display: inline-flex;">Discount:&nbsp;<div class="discounttext">$0</div>
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
                                            <h6 class="font-weight-medium mb-0 show_service_code_name"> </h6>&nbsp;
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