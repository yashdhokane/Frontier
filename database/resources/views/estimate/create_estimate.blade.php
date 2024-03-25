@extends('home')
@section('content')
<style>
.required-field::after {
    content: " *";
    color: red;
}
</style>
<!-- Page wrapper  -->
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb" style="display:inline;">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Add Estimate templates</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('estimate.index')}}">Estimate templates</a></li>
                            <li class="breadcrumb-item"><a href="#">Installation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Estimate templates</li>
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
            <div class="col-lg-9 col-md-12">

                <!-- Card -->
                <div class="card card-body">
                    <h4>Add TEMPLATE</h4>
                    <form action="{{ route('estimate.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="control-label required-field">Template Name (required)</label>
                            <input type="text" name="template_name" id="firstName" class="form-control" placeholder="">
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="mb-3">
                            <label for="" class="control-label col-form-label required-field">Category
                            </label>
                            <select class="form-select me-sm-2" name="template_category_id" required>
                                <option selected disabled value="">Select category...</option>
                                @foreach($category as $category)
                                <option value="{{ $category->id }}">{{
                                    $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-md-8">
                                <label class="control-label required-field">Description</label>
                                <textarea id="text" name="template_description" class="form-control"
                                    style="height: 180px;">
							</textarea>
                                <small id="textHelp" class="form-text text-muted"></small>
                            </div>
                         {{--   <div class="col-md-3">
                                <div class="edimg" style="margin-top:25px;">
                                    <img class="img-responsive" src="{{ asset('public/images/inspection.jpg') }}"
                                        alt="Card image cap" width="90px;" height="90px;" />
                                    <div class="dropdown dropstart edrop">
                                        <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                            aria-expanded="false"><i data-feather="more-vertical"
                                                class="feather-sm"></i></a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#">Edit Image</a></li>
                                            <li><a class="dropdown-item" href="#">Delete Image</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div> --}} 
                        </div>
                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card card-body">
                    <h4>Services</h4>
                    <div class="row">
                        <div class="col-6">

                            <div class="mb-3">
                                <label for="service" class="control-label col-form-label required-field">Service Item</label>
                                <select class="form-select me-sm-2" id="service" name="service_id" required>
                                    <option selected disabled value="">Select service...</option>
                                    @foreach($service as $service)
                                    <option value="{{ $service->service_id }}">{{ $service->service_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="control-label">Description (optional)</label>
                                <textarea id="text1" name="service_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="mb-3">
                                <label class="control-label required-field">Qty</label>
                                <input type="number" id="qty" name="service_quantity" class="form-control" placeholder="">
                            </div>
                            
                            
                             <div class="mb-3">
                                <label class="control-label required-field">Discount </label>
                                <input type="number" id="service_discount" name="service_discount" class="form-control"
                                    placeholder="">
                            </div>
                             <div class="mb-3">
                                <label class="control-label required-field">Tax</label>
                                <input type="number" id="service_tax" name="service_tax" class="form-control"
                                    placeholder="">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="mb-3">
                                <label class="control-label required-field">Unit Price</label>
                                <input type="number" id="unitprice" name="service_price" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="control-label required-field">Line Total</label>
                                <input type="number" id="unitcost" name="service_cost" class="form-control"
                                    placeholder="">
                            </div>
                            
                        </div>
                    </div>
                    {{-- <div class="r-separator">
                        <div class="form-group mb-3 row pb-3" style="border-bottom:1px solid #ccc;"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="control-label required-field">Item Name</label>
                                <input type="text" id="itname" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">Description (optional)</label>
                                <textarea id="text1" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label class="control-label required-field">Qty</label>
                                <input type="number" id="qty" class="form-control" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="control-label required-field">Unit Cost</label>
                                <input type="number" id="unitcost" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label class="control-label required-field">Unit Price</label>
                                <input type="number" id="unitprice" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <span class="unprice">$200.00 <i class="fas fa-times-circle"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="aditem">
                        <div class="mb-3 pb-3"><button type="button"
                                class="justify-content-center btn btn-primary align-items-center">+ Services
                                Item</button></div>
                    </div> --}}
                    <h4>Materials</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="product" class="control-label col-form-label required-field">Material Item</label>
                                <select class="form-select me-sm-2" id="serviceone" name="product_id" required>
                                    <option selected disabled value="">Select material...</option>
                                    @foreach($products as $productItem) {{-- Use a different variable name here --}}
                                    <option value="{{ $productItem->product_id }}">{{ $productItem->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="control-label">Description (optional)</label>
                                <textarea id="text1" name="product_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label class="control-label required-field">Qty</label>
                                <input type="number" id="qtyone" name="product_quantity" class="form-control"
                                    placeholder="">
                            </div>
                             <div class="mb-3">
                                <label class="control-label required-field">Discount</label>
                                <input type="number" id="discount" name="discount" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="control-label required-field">Tax</label>
                                <input type="number" id="tax" name="tax" class="form-control"
                                    placeholder="">
                            </div>
                           
                           
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label class="control-label required-field">Unit Price</label>
                                <input type="number" id="unitpriceone" name="product_price" class="form-control"
                                    placeholder="">
                            </div>
                             <div class="mb-3">
                                <label class="control-label required-field">Line Total</label>
                                <input type="number" id="unitcostone" name="product_cost" class="form-control"
                                    placeholder="">
                            </div>
                        </div>
                        {{-- <div class="col-sm-2">
                            <div class="mb-3">
                                <span class="unprice">$12.500.00 <i class="fas fa-times-circle"></i></span>
                            </div>
                        </div> --}}
                    </div>
                    {{-- <div class="aditem">
                        <div class="mb-3 pb-3"><button type="button"
                                class="justify-content-center btn btn-primary align-items-center"> + Material
                                Item</button></div>
                    </div> --}}
                    <div class="r-separator">
                        <div class="form-group mb-3 row pb-3" style="border-bottom:1px solid #ccc;"></div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <div class="mb-3"><span class="tltext required-field" style="margin-left:83%">Subtotal</span></div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3"><input type="number" id="estimatesubtotal" name="estimate_subtotal"
                                    class="form-control" placeholder="subtotal"></div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <div class="mb-3"><span class="tltext required-field" style="margin-left:83%">Discount</span>
                                <small id="textHelp" class="form-text text-muted"
                                    style="text-align:right;display: block;"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3"><input type="number" id="estimatediscount" name="estimate_discount"
                                    class="form-control" placeholder="discount"></div>
                        </div>
                    </div>

                    <div style="margin-left:75%; display:flex; ">
                        <span class="tltext required-field">Total </span>
                        <input style="margin-left:5%;" type="number" id="estimatetotal" name="estimate_total" class="form-control"
                            placeholder="total">
                    </div>

                    <div class="mb-3 pb-3" style="margin-top: 1%"><button type="submit" class="
                                                                          justify-content-center
                                                                          btn btn-primary
                                                                          align-items-center
                                                                        " style="margin-left:90%">
                            Submit
                        </button></div>

                    </form>
                </div>
                <!-- Card -->

            </div>
            <!-- column -->



        </div>
        <!-- Row -->




        <!-- -------------------------------------------------------------- -->
        <!-- Recent comment and chats -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <!-- column -->
            <div class="col-lg-6">
                <br />
            </div>
            <!-- column -->
            <div class="col-lg-6">
                <br />
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Recent comment and chats -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
</div>



{{--
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">ADD Estimate templates </h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Estimate templates</a></li>
                            <li class="breadcrumb-item"><a href="">Estimate </a></li>
                            <li class="breadcrumb-item active" aria-current="">Estimate templates</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">

                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12">
                <!-- ---------------------
                                start About Product
                            ---------------- -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('estimate.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Template Name</label>
                                            <input type="text" name="template_name" id="firstName" class="form-control"
                                                placeholder="" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Material Item</label>
                                            <input type="text" name="Pending" id="firstName" class="form-control"
                                                placeholder="" />
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Description</label>
                                            <input type="text" name="template_description" id="lastName"
                                                class="form-control" placeholder="" required />
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="service" class="control-label col-form-label required-field">Services
                                                Item</label>
                                            <select class="form-select me-sm-2" id="service" name="service_id" required>
                                                <option selected disabled value="">Select service...</option>
                                                @foreach($service as $service)
                                                <option value="{{ $service->service_id }}">{{
                                                    $service->service_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="service" class="control-label col-form-label required-field">Category
                                            </label>
                                            <select class="form-select me-sm-2" id="service" name="template_category_id"
                                                required>
                                                <option selected disabled value="">Select category...</option>
                                                @foreach($category as $service)
                                                <option value="{{ $service->id }}">{{
                                                    $service->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Subtotal</label>
                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" name="estimate_subtotal"
                                                    placeholder="" aria-label="price" aria-describedby="basic-addon1"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Tax</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        data-feather="dollar-sign" class="feather-sm"></i></span>
                                                <input type="text" class="form-control" name="tax" placeholder=""
                                                    aria-label="price" aria-describedby="basic-addon1" required />
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Discount</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="ri-scissors-2-line"></i></span>

                                                <input type="text" id="discountestimate" name="discountestimate" class="form-control" placeholder=""
                                                    aria-label="Discount" aria-describedby="basic-addon2" required />
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success rounded-pill px-4">Save</button>
                                    <button type="button" class="btn btn-dark rounded-pill px-4">Cancel</button>
                                </div>
                        </form>
                    </div>
                </div>
                <!-- ---------------------
                                end About Product
                            ---------------- -->
            </div>
            <!-- Column -->
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Right sidebar -->
        <!-- -------------------------------------------------------------- -->
        <!-- .right-sidebar -->
        <!-- -------------------------------------------------------------- -->
        <!-- End Right sidebar -->
        <!-- -------------------------------------------------------------- -->
    </div>
</div>
</div>

--}}
</div>
@section('script')
    <script>
        $(document).ready(function () {
            // ... Your existing code ...

            // Call calculateDiscountEstimate on document ready
            calculateDiscountEstimate();

            $('#serviceone').change(function () {
                var selectedProductId = $(this).val();
                var url = "{{ route('estimate.product', ":id") }}";
                url = url.replace(':id', selectedProductId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#qtyone').val(data.stock);
                        $('#unitcostone').val(data.unitcostone);
                        $('#unitpriceone').val(data.base_price);
                        $('#discount').val(data.discount);
                        $('#tax').val(data.tax);

                        // Calculate discountestimate value
                        calculateDiscountEstimate();
                    },
                });
            });

            $('#service').change(function () {
                var selectedServiceId = $(this).val();
                var url = "{{ route('estimate.service', ":id") }}";
                url = url.replace(':id', selectedServiceId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#unitprice').val(data.service_cost);
                        $('#qty').val(data.service_quantity);
                        $('#unitcost').val(data.unitcost);
                        $('#service_tax').val(data.service_tax);
                        $('#service_discount').val(data.service_discount);

                        // Calculate discountestimate value
                        calculateDiscountEstimate();
                    },
                });
            });

            // Function to calculate discountestimate value
   
         
           function calculateDiscountEstimate() {
                  $('#estimatediscount').val(0);
            $('#estimatesubtotal').val(0);
    $('#estimatetotal').val(0); // Reset the total field
    var serviceDiscount = parseFloat($('#service_discount').val()) || 0;
    var productDiscount = parseFloat($('#discount').val()) || 0;
    var unitCost = parseFloat($('#unitcost').val()) || 0;
    var unitCostOne = parseFloat($('#unitcostone').val()) || 0;

    // Calculate total discountestimate value
    var discountEstimate = serviceDiscount + productDiscount;

    // Update the discountestimate field
    $('#estimatediscount').val(discountEstimate);

    // Calculate subtotal
    var subtotal = unitCost + unitCostOne;

    // Subtract discount from subtotal
    var discountedtotal = subtotal - discountEstimate;

    // Update the estimatesubtotal field
    $('#estimatesubtotal').val(subtotal);

    // Update the estimatetotal field (if needed)
    // You may want to replace '0' with any other value or calculation
    $('#estimatetotal').val(discountedtotal);
}


            });
             </script>
@endsection



@endsection



