@if (isset($technician) && !empty($technician))
    <div class="container-fluid">
        <div class="card">
            <div class="card-body wizard-content">
                <h6 class="card-subtitle mb-2"></h6>
                <form action="#" class="tab-wizard vertical wizard-circle mt-1" style="display: none" id="createScheduleForm"
                    enctype="multipart/form-data">
                    <input type="hidden" id="technician_id" name="technician_id" value="{{ $technician->id }}">
                    <input type="hidden" id="job_id" name="job_id" value="">

                    <!-- Step 1 -->

                    <h6>Customer & Schedule</h6>
                    <section data-step="0">

                        <div class="row radioDiv">
                            <div class="col-md-8">
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="new"
                                                id="customControlValidation2" name="radio-stacked" checked>
                                            <label class="custom-control-label" for="customControlValidation2">New
                                                Call</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="old"
                                                id="customControlValidation3" name="radio-stacked">
                                            <label class="custom-control-label"
                                                for="customControlValidation3">Reschedule Existing Call</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row searchexistingJob" style="margin-bottom: 226px;display: none">
                            <div class="col-md-8">
                                <div class="mt-2">
                                    <h6 class="card-title"><i class="fas fa-user"></i> Search </h6>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-light-info text-info" type="button">
                                                <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control serchOldJob" id="serchOldJob"
                                            name="serchOldJob" placeholder="Ticket Number or Customer Name"
                                            aria-label="" aria-describedby="basic-addon1">
                                    </div>
                                    <span class="error" id="serchOldJobError"></span>
                                </div>
                            </div>
                        </div>

                        <div class="new_schedule">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mt-2">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Customer
                                        </h6>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-light-info text-info" type="button">
                                                    <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control customerAuto" name="customer_name"
                                                placeholder="Name, Email, Phone, Address" aria-label=""
                                                aria-describedby="basic-addon1">
                                            <input class="customer_id" type="hidden" name="customer_id" value="">
                                        </div>
                                        <div class="input-link"><a href="#" class="card-link">+ Add Customer</a></div>
                                        <span class="error" id="customer_name"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-2">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Technician
                                        </h6>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-light-info text-info" type="button">
                                                    <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control technician_name" readonly
                                                value="{{ $technician->name }}" aria-label="" name="technician_name"
                                                aria-describedby="basic-addon1">
                                        </div>
                                        <span class="error" id="technician_name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mt-3">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Title </h6>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-light-info text-info" type="button">
                                                    <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control job_title" name="job_title"
                                                placeholder="Add Job Title Here" aria-label=""
                                                aria-describedby="basic-addon1">
                                        </div>
                                        <span class="error" id="job_title"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-3">
                                        <h6 class="card-title"><i class="far fa-calendar-alt"></i>
                                            Date & Time </h6>
                                        <div class="form-group mb-3">
                                            <input type="datetime-local" name="datetime"
                                                class="form-control datetime" readonly
                                                min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ $dateTime }}">
                                        </div>
                                        <span class="error" id="datetime"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mt-3">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Job Code
                                        </h6>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-light-info text-info" type="button">
                                                    <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control job_code" name="job_code"
                                                placeholder="Job Code here" aria-label=""
                                                aria-describedby="basic-addon1">
                                        </div>
                                        <span class="error" id="job_code"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-3">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Type </h6>
                                        <div class="form-group">
                                            <select class="form-control job_type" id="exampleFormControlSelect1"
                                                name="job_type">
                                                <option value="">--Select Type--</option>
                                                <option value="heating_and_air_conditioning">Heating & Air Conditioning
                                                </option>
                                                <option value="appliances">Appliances</option>
                                                <option value="audio_and_tv">Audio & TV</option>
                                                <option value="insurance">Insurance</option>
                                            </select>
                                        </div>
                                        <span class="error" id="job_type"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-3">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Priority
                                        </h6>
                                        <div class="form-group">
                                            <select class="form-control priority" id="exampleFormControlSelect1"
                                                name="priority">
                                                <option value="">--Select priority--</option>
                                                <option value="high">High</option>
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mt-3">
                                        <h6 class="card-title"><i class="fas fa-user"></i> Description
                                        </h6>
                                        <div class="form-group">
                                            <textarea class="form-control description" rows="1" placeholder="Text Here..." name="description"></textarea>
                                            <small id="textHelp" class="form-text text-muted">All all
                                                details of the job Here</small>
                                        </div>
                                        <span class="error" id="description"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>

                    <!-- Step 2 -->

                    <h6>Select Services & Products</h6>
                    <section data-step="1">
                        <div class="row">
                            <h6 class="card-title"><i class="fas fa-align-justify"></i> Select
                                Services</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <label for="jobTitle1">Item Name</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mt-2">
                                    <label for="videoUrl1">Qty</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mt-2">
                                    <label for="videoUrl1">Unit Price</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mt-2">
                                    <label for="videoUrl1">Line Total</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <input type="text" class="form-control searchServices" id="jobTitle1"
                                        placeholder="Search Services" value="" name="service_name" />
                                    <input class="service_id" type="hidden" name="service_id" value="">
                                    <span class="error" id="service_error"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="videoUrl1">Description</label>
                                    <input type="text" class="form-control service_description" id="jobTitle1"
                                        placeholder="Service Description (Optional)" value=""
                                        name="service_description" />
                                </div>
                                <span class="error" id="service_id"></span>
                                <div class="mb-2">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control service_quantity" id="videoUrl1"
                                        placeholder="1" name="service_quantity" value="" />
                                    <input class="service_added_quantity_price" type="hidden" value="">
                                    <input class="pre_service_quantity" type="hidden" value="1">
                                </div>
                                <div class="mb-2">
                                    <label for="videoUrl1">Discount</label>
                                    <input class="form-control service_discount" type="number"
                                        name="service_discount" value="" placeholder="Discount">
                                    <input type="hidden" class="pre_service_discount" value="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control service_cost" id="videoUrl1"
                                        placeholder="$0.00" name="service_cost" value="" />
                                </div>
                                <div class="mb-2">
                                    <label for="videoUrl1">Tax</label>
                                    <input class="form-control service_tax" type="number" name="service_tax"
                                        value="" placeholder="Tax">
                                    <input type="hidden" class="pre_service_tax" value="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input class="service_total" type="hidden" name="service_total" value="">
                                <div class="service_line_total">$0.00</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <h6 class="card-title"><i class="fas fa-align-justify"></i> Select
                                Product</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <input type="text" class="form-control searchProduct" id="jobTitle1"
                                        name="product_name" placeholder="Search Product" value="" />
                                    <input class="product_id" type="hidden" name="product_id" value="">
                                    <span class="error" id="product_error"></span>
                                </div>
                                <div class="mb-2">
                                    <input type="text" class="form-control product_description" id="jobTitle1"
                                        placeholder="Product Description (Optional)" value=""
                                        name="product_description" />
                                </div>
                                <div class="mb-2">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control product_quantity" id="videoUrl1"
                                        placeholder="1.00" name="product_quantity" value="" />
                                    <input class="product_added_quantity_price" type="hidden" value="">
                                    <input class="pre_product_quantity" type="hidden" value="1">
                                </div>
                                <div class="mb-2">
                                    <input class="form-control product_discount" type="number"
                                        name="product_discount" value="" placeholder="Discount">
                                    <input type="hidden" class="pre_product_discount" value="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control product_cost" id="videoUrl1"
                                        placeholder="$0.00" name="product_cost" value="" />
                                </div>
                                <div class="mb-2">
                                    <input class="form-control product_tax" type="number" name="product_tax"
                                        value="" placeholder="Tax">
                                    <input type="hidden" class="pre_product_tax" value="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input class="product_total" type="hidden" name="product_total" value="">
                                    <div class="product_line_total">$0.00</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <span class="error" id="select_error"></span>
                        </div>
                        <div class="row mb-2" style="border-top: 1px solid #343434;">
                            <div class="col-md-4 mt-2">&nbsp;</div>
                            <div class="col-md-4 mt-2">&nbsp;</div>
                            <div class="col-md-4 mt-2 text-right" style="text-align: right;padding-right: 36px;">
                                <h5 style="display: inline-flex;">Sub Total:&nbsp;<div class="subtotaltext">$0
                                    </div>
                                </h5>
                                <input type="hidden" class="subtotal" name="subtotal" value="0"><br>
                                <h5 style="display: inline-flex;">Discount:&nbsp;<div class="discounttext">$0
                                    </div>
                                </h5>
                                <input type="hidden" class="discount" name="discount" value="0"><br>
                                <h4 style="display: inline-flex;">Total:&nbsp;<div class="totaltext">$0
                                    </div>
                                </h4>
                                <input type="hidden" class="total" name="total" value="0">
                            </div>
                        </div>
                    </section>

                    <!-- Step 3 -->
                    <h6>Address & Other Details</h6>
                    <section data-step="2">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mt-0">
                                    <h6 class="card-title"><i class="fas fa-tasks"></i> Notes to
                                        Technician </h6>
                                    <div class="form-group">
                                        <textarea class="form-control technician_notes" rows="1" placeholder="Text Here..." name="technician_notes"></textarea>
                                        <small id="textHelp" class="form-text text-muted">Technician must read this
                                            note before start of the job.</small><br>
                                        <span class="error" id="technician_notes"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mt-0">
                                    <h6 class="card-title"><i class="fas fa-tasks"></i> Job Fields
                                    </h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-light-info text-info" type="button">
                                                <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Job Fields here"
                                            name="job_fields" aria-label="" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mt-4">
                                    <h6 class="card-title"><i class="far fa-images"></i> Address
                                    </h6>
                                    <div class="form-group">
                                        <select class="form-control address" id="exampleFormControlSelect1"
                                            name="address">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mt-4">
                                    <h6 class="card-title"><i class="fas fa-clock"></i> Duration
                                    </h6>
                                    <div class="form-group">
                                        <select class="form-control duration" id="exampleFormControlSelect1"
                                            name="duration">
                                            <option value="">-- Select Duration --</option>
                                            <option value="60">1 Hours</option>
                                            <option value="120">2 Hours</option>
                                            <option value="180">3 Hours</option>
                                            <option value="240">4 Hours</option>
                                            <option value="300">5 Hours</option>
                                        </select>
                                        <span class="error" id="duration"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mt-4">
                                    <h6 class="card-title"><i class="far fa-images"></i> Photos /
                                        Attachments </h6>
                                    <div class="input-group">
                                        <input class="form-control" type="file" id="formFile" name="photos[]"
                                            multiple style="width: 150px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mt-4">
                                    <h6 class="card-title"><i class="fas fa-tags"></i> Tags </h6>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-light-info text-info" type="button">
                                                <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Job Tags here"
                                            name="tags" aria-label="" aria-describedby="basic-addon1">
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
