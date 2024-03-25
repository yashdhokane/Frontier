@extends('home')
@section('content')
<style>
.required-field::after {
    content: " *";
    color: red;
}

</style>
<!-- Page wrapper  -->
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">ADD Parts </h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Price Book</a></li>
                            <li class="breadcrumb-item"><a href="#">Parts </a></li>
                            <li class="breadcrumb-item active" aria-current="page">ADD Parts </li>
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
                        <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Part Name</label>
                                            <input type="text" name="product_name" id="firstName" class="form-control"
                                                placeholder="" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Part Code</label>
                                            <input type="text" name="product_code" id="firstName" class="form-control"
                                                placeholder="" required />
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Short Description</label>
                                            <input type="text" name="product_short" id="lastName" class="form-control"
                                                placeholder="" required />
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="service" class="control-label col-form-label required-field">Category</label>
                                            <select class="form-select me-sm-2" id="service" name="product_category_id"
                                                required>
                                                <option selected disabled value="">Select Part Category...</option>
                                                @foreach($product as $product)
                                                <option value="{{ $product->id }}">{{
                                                    $product->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4" style="margin-top: 5px;">
                                        <div class="mb-3">
                                            <label>Status</label>
                                            <br />
                                            <div class="form-check">
                                                <input type="radio" id="customRadioInline1" name="status"
                                                    value="Publish" class="form-check-input" required />
                                                <label class="form-check-label required-field" for="customRadioInline1">Publish</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="customRadioInline2" name="status" value="Draft"
                                                    class="form-check-input" required />
                                                <label class="form-check-label" for="customRadioInline2">Draft</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Price</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        data-feather="dollar-sign" class="feather-sm"></i></span>
                                                <input type="number" class="form-control" name="base_price"
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
                                                <input type="number" class="form-control" name="tax" placeholder=""
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

                                                <input type="number" name="discount" class="form-control" placeholder=""
                                                    aria-label="Discount" aria-describedby="basic-addon2" required />
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <div class="row">

                                    <!--/span-->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Quantity</label>
                                            <input type="number" name="stock" id="lastName" class="form-control"
                                                placeholder="" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="margin-top: 13px;">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Manufacturer</label>
                                            <select class="form-select" name="product_manu_id"
                                                data-placeholder="Choose a Manufacturer" tabindex="1">
                                                @foreach($manufacture as $manufacturer)
                                                <option value="{{ $manufacturer->id }}">{{
                                                    $manufacturer->manufacturer_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div style="margin-top: 0px;">
                                        <label class="control-label required-field">Short Description</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <textarea name="product_description" class="form-control" rows="4"
                                                        required>
                                                     </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                        </div>

                                        <!--/row-->
                                        <div class="row">
                                            <!--/span-->
                                            <div class="col-md-5">
                                                <label class="control-label  required-field">Featured Image</label>
                                                <div class="el-element-overlay">
                                                    <div class="el-card-item">
                                                        <div class="el-card-avatar el-overlay-1">
                                                            <img src="{{ asset('public/images/1.png') }}"
                                                                id="imagePreview" alt="user" />
                                                            <div class="el-overlay">
                                                                <!-- Remove the search and link icons -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn btn-info waves-effect waves-light">
                                                        <span>Upload Another Image</span>
                                                        <input id="file" type="file" onchange="showImagePreview()"
                                                            name="product_image" class="upload" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" style="margin-top: 13px;">
                                                <div class="mb-3">
                                                    <label class="control-label required-field">Part Assignment</label>
                                                    <select id="productAssignmentSelect" name="assigned_to"
                                                        class="form-select" data-placeholder="Choose an Option"
                                                        tabindex="1">
                                                        <option value="pending">Select to assign technicians</option>
                                                        <option value="all">Assign to All</option>
                                                        <option value="selected">Assign to Selected Technicians</option>
                                                    </select>
                                                </div>

                                                <div id="selectedTechnicians" style="display: none;">
                                                    <label class="control-label">Select Technicians</label>
                                                    <div class="technician-checkboxes">
                                                        <!-- Add checkboxes for each technician -->
                                                        @foreach($technicians as $technician)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="technician_id[]" value="{{ $technician->id }}">
                                                            <label class="form-check-label">{{ $technician->name
                                                                }}</label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="card-title mt-5">GENERAL INFO</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered td-padding">
                                                            <tbody>
                                                                <!-- start row -->

                                                                <tr>
                                                                    <td>
                                                                        <label class="control-label mb-2">Color</label>
                                                                        <input type="text" name="Color"
                                                                            class="form-control" placeholder="" />
                                                                    </td>
                                                                    <td>
                                                                        <label class="control-label mb-2">Sizes</label>
                                                                        <input type="text" name="Sizes"
                                                                            class="form-control" placeholder="" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label
                                                                            class="control-label mb-2">Material</label>
                                                                        <input type="text" name="material"
                                                                            class="form-control" placeholder="" />
                                                                    </td>
                                                                    <td>
                                                                        <label class="control-label mb-2">Weight</label>
                                                                        <input type="text" name="weight"
                                                                            class="form-control" placeholder="" />
                                                                    </td>
                                                                </tr>

                                                                <!-- end row -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit"
                                                class="btn btn-success rounded-pill px-4">Save</button>
                                            <button type="button" class="btn btn-dark rounded-pill px-4"
                                                onclick="cancelRedirect()">Cancel</button>

                                            <script>
                                                function cancelRedirect() {
                                            window.location.href = "{{ route('product.listingproduct') }}";
                                        }
                                            </script>

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
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var productAssignmentSelect = document.querySelector('#productAssignmentSelect');
        var selectedTechniciansDiv = document.querySelector('#selectedTechnicians');
        var technicianCheckboxes = document.querySelectorAll('input[name="technician_id[]"]');

        productAssignmentSelect.addEventListener('change', function () {
            if (productAssignmentSelect.value === 'selected') {
                selectedTechniciansDiv.style.display = 'block';

                // Uncheck all technician checkboxes
                technicianCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = false;
                });
            } else if (productAssignmentSelect.value === 'all') {
                selectedTechniciansDiv.style.display = 'block';

                // Check all technician checkboxes
                technicianCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = true;
                });
            } else {
                selectedTechniciansDiv.style.display = 'none';
            }
        });

        // Initial check/uncheck based on the selected option
        if (productAssignmentSelect.value === 'selected') {
            selectedTechniciansDiv.style.display = 'block';
        } else if (productAssignmentSelect.value === 'all') {
            selectedTechniciansDiv.style.display = 'none';

            // Check all technician checkboxes
            technicianCheckboxes.forEach(function (checkbox) {
                checkbox.checked = true;
            });
        } else {
            selectedTechniciansDiv.style.display = 'none';
        }
    });
</script>
@endsection
@endsection