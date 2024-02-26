@if (isset($jobDetails) && !empty($jobDetails))
    <div class="container-fluid">
        <div class="card">
            <div class="border-bottom title-part-padding">
                <h4 class="card-title">{{ $jobDetails->job_title }} (Update Job)</h4>
            </div>
            <div class="card-body wizard-content">
                <h6 class="card-subtitle mb-3"></h6>
                <form action="#" class="tab-wizard2 vertical wizard-circle mt-5" id="updateScheduleForm"
                    enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="{{ $jobDetails->id }}">
                    <h6>Customer & Schedule</h6>
                    <section>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <h6 class="card-title"><i class="fas fa-user"></i> Customer </h6>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-light-info text-info" type="button">
                                                <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control" readonly name="customer_name"
                                            value="{{ $jobDetails->customername }}" aria-label=""
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="mb-3" style="margin-top: 36px;">
                                    <h6 class="card-title"><i class="far fa-calendar-alt"></i> Schedule </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emailAddress1">From :</label>
                                                <input type="datetime-local" class="form-control start_date_time" name="start_date_time"
                                                    value="{{ $jobDetails->start_date_time }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>
                                                    <input type="hidden" name="old_start_date_time" value="{{ $jobDetails->start_date_time }}">
                                            </div>
                                            <span class="error" id="start_date_time"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emailAddress1">To :</label>
                                                <input type="datetime-local" class="form-control end_date_time" name="end_date_time"
                                                    value="{{ $jobDetails->end_date_time }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>
                                            </div>
                                            <span class="error" id="end_date_time"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" style="margin-top: 36px;">
                                    <h6 class="card-title"><i class="fas fa-user"></i> Technician </h6>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-light-info text-info" type="button">
                                                <i class="far fa-edit fill-white" style="font-size: 17px;"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control searchtechnician technician_name" name="technician_name"
                                            value="{{ $jobDetails->technicianname }}" aria-label=""
                                            aria-describedby="basic-addon1">
                                        <input type="hidden" class="technician_id" name="technician_id"
                                            value="{{ $jobDetails->technician_id }}">
                                            <br><br>
                                    </div>
                                    <span class="error" id="technician_name"></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <h6>Selected Services & Products</h6>
                    <section data-step="1">
                        <div class="row">
                            <h6 class="card-title"><i class="fas fa-align-justify"></i> Selected
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
                                        placeholder="Search Services" value="{{ $jobDetails->service_name }}"
                                        name="service_name" />
                                    <input class="service_id" type="hidden" name="service_id"
                                        value="{{ $jobDetails->service_id }}">
                                    <span class="error" id="service_error"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="videoUrl1">Description</label>
                                    <input type="text" class="form-control service_description" id="jobTitle1"
                                        placeholder="Service Description (Optional)"
                                        value="{{ $jobDetails->service_description }}" name="service_description" />
                                </div>
                                <span class="error" id="service_id"></span>
                                <div class="mb-2">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control service_quantity" id="videoUrl1"
                                        placeholder="1" name="service_quantity"
                                        value="{{ $jobDetails->service_quantity }}" />
                                    <input class="service_added_quantity_price" type="hidden"
                                        value="{{ $jobDetails->service_total }}">
                                    <input class="pre_service_quantity" type="hidden"
                                        value="{{ isset($jobDetails->service_quantity) ? $jobDetails->service_quantity : 1 }}">
                                </div>
                                <div class="mb-2">
                                    <label for="videoUrl1">Discount</label>
                                    <input class="form-control service_discount" type="number"
                                        name="service_discount" value="{{ $jobDetails->service_discount }}"
                                        placeholder="Discount">
                                    <input type="hidden" class="pre_service_discount"
                                        value="{{ $jobDetails->service_discount }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control service_cost" id="videoUrl1"
                                        placeholder="$0.00" name="service_cost"
                                        value="{{ $jobDetails->service_cost }}" />
                                </div>
                                <div class="mb-2">
                                    <label for="videoUrl1">Tax</label>
                                    <input class="form-control service_tax" type="number" name="service_tax"
                                        value="{{ $jobDetails->service_tax }}" placeholder="Tax">
                                    <input type="hidden" class="pre_service_tax"
                                        value="{{ $jobDetails->service_tax }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input class="service_total" type="hidden" name="service_total"
                                    value="{{ $jobDetails->service_total }}">
                                <div class="service_line_total">${{ $jobDetails->service_total }}</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <h6 class="card-title"><i class="fas fa-align-justify"></i> Selected
                                Product</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <input type="text" class="form-control searchProduct" id="jobTitle1"
                                        name="product_name" placeholder="Search Product"
                                        value="{{ $jobDetails->product_name }}" />
                                    <input class="product_id" type="hidden" name="product_id"
                                        value="{{ $jobDetails->product_id }}">
                                    <span class="error" id="product_error"></span>
                                </div>
                                <div class="mb-2">
                                    <input type="text" class="form-control product_description" id="jobTitle1"
                                        placeholder="Product Description (Optional)"
                                        value="{{ $jobDetails->product_description }}" name="product_description" />
                                </div>
                                <div class="mb-2">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control product_quantity" id="videoUrl1"
                                        placeholder="1.00" name="product_quantity"
                                        value="{{ $jobDetails->product_quantity }}" />
                                    <input class="product_added_quantity_price" type="hidden"
                                        value="{{ $jobDetails->product_total }}">
                                    <input class="pre_product_quantity" type="hidden"
                                        value="{{ isset($jobDetails->product_quantity) ? $jobDetails->product_quantity : 1 }}">
                                </div>
                                <div class="mb-2">
                                    <input class="form-control product_discount" type="number"
                                        name="product_discount" value="{{ $jobDetails->product_discount }}"
                                        placeholder="Discount">
                                    <input type="hidden" class="pre_product_discount"
                                        value="{{ $jobDetails->product_discount }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input type="number" class="form-control product_cost" id="videoUrl1"
                                        placeholder="$0.00" name="product_cost"
                                        value="{{ $jobDetails->product_cost }}" />
                                </div>
                                <div class="mb-2">
                                    <input class="form-control product_tax" type="number" name="product_tax"
                                        value="{{ $jobDetails->product_tax }}" placeholder="Tax">
                                    <input type="hidden" class="pre_product_tax"
                                        value="{{ $jobDetails->product_tax }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2">
                                    <input class="product_total" type="hidden" name="product_total"
                                        value="{{ $jobDetails->product_total }}">
                                    <div class="product_line_total">${{ $jobDetails->product_total }}</div>
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
                                <h5 style="display: inline-flex;">Sub Total:&nbsp;<div class="subtotaltext">
                                        ${{ $jobDetails->commission_total }}
                                    </div>
                                </h5>
                                <input type="hidden" class="subtotal" name="subtotal"
                                    value="{{ $jobDetails->commission_total }}"><br>
                                <h5 style="display: inline-flex;">Discount:&nbsp;<div class="discounttext">
                                        ${{ $jobDetails->discount }}
                                    </div>
                                </h5>
                                <input type="hidden" class="discount" name="discount"
                                    value="{{ $jobDetails->discount }}"><br>
                                <h4 style="display: inline-flex;">Total:&nbsp;<div class="totaltext">
                                        ${{ $jobDetails->gross_total }}
                                    </div>
                                </h4>
                                <input type="hidden" class="total" name="total"
                                    value="{{ $jobDetails->gross_total }}">
                            </div>
                        </div>
                    </section>

                    <h6>Notes & Other Details</h6>
                    <section>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="decisions1">Notes to Technician</label>
                                    <div class="mb-3">
                                        <textarea class="form-control technician_notes" rows="1" placeholder="Text Here..." name="technician_notes">{{ $jobDetails->note }}</textarea>
                                        <span class="error" id="technician_notes"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <ul class="list-group list-group-horizontal-xl">
                                    <li class="list-group-item d-flex align-items-center"
                                        style="padding: 12px; margin-bottom: 12px;">
                                        <i class="fas fa-tasks"
                                            style="color: #2962ff; font-size: 16px; margin-right: 11px;"></i>
                                        <h5 style="margin-top: 10px;">Job Fields</h5>
                                        <span
                                            class="badge bg-light-info text-info font-weight-medium rounded-pill ms-auto"
                                            style="font-size: 21px; width: 48px; text-align: center;cursor: pointer;">+</span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center"
                                        style="padding: 12px; margin-bottom: 12px;">
                                        <i class="fas fa-tags"
                                            style="color: #2962ff; font-size: 16px; margin-right: 11px;"></i>
                                        <h5 style="margin-top: 10px;">Job Tag</h5>
                                        <span
                                            class="badge bg-light-info text-info font-weight-medium rounded-pill ms-auto"
                                            style="font-size: 21px; width: 48px; text-align: center;cursor: pointer;">+</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-5">
                                <ul class="list-group list-group-horizontal-xl">
                                    <li class="list-group-item d-flex align-items-center"
                                        style="padding: 12px; margin-bottom: 12px;">
                                        <i class="far fa-images"
                                            style="color: #2962ff; font-size: 16px; margin-right: 11px;"></i>
                                        <h5 style="margin-top: 10px;">Photos / Attachments</h5>
                                        <span
                                            class="badge bg-light-info text-info font-weight-medium rounded-pill ms-auto"
                                            style="font-size: 21px; width: 48px; text-align: center;cursor: pointer;">+</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                </form>
            </div>
        </div>
    </div>
@endif
