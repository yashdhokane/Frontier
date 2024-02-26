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
                <h4 class="page-title">EDIT Parts </h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Price Book</a></li>
                            <li class="breadcrumb-item"><a href="#">Parts </a></li>
                            <li class="breadcrumb-item active" aria-current="page">EDIT Parts </li>
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
                        <form action="{{ route('product.update', $product->product_id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Parts Name</label>
                                            <input type="text" name="product_name" class="form-control"
                                                value="{{ $product->product_name }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Part Code</label>
                                            <input type="text" name="product_code" class="form-control"
                                                value="{{ $product->product_code }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="control-label required-field">Short Description</label>
                                            <input type="text" name="product_short" class="form-control"
                                                value="{{ $product->product_short }}" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="product_category_id"
                                                class="control-label col-form-label required-field">Parts Category</label>
                                            <select class="form-select me-sm-2" id="product_category_id"
                                                name="product_category_id" required>
                                                <option selected disabled value="">Select Parts Category...
                                                </option>
                                                @foreach($productCategories as $category)
                                                <option value="{{ $category->id }}" @if($product->
                                                    product_category_id ==
                                                    $category->id) selected @endif>
                                                    {{ $category->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                   <div class="col-md-4" style="margin-top: 13px;">
                                        <div class="mb-3">
    <label class="control-label required-field">Manufacturer</label>
    <select class="form-select" name="product_manu_id" data-placeholder="Choose a Manufacturer" tabindex="1">
        @foreach($manufacture as $manufacturer)
            <option value="{{ $manufacturer->id }}" @if($product->product_manu_id == $manufacturer->id) selected @endif>
                {{ $manufacturer->manufacturer_name }}
            </option>
        @endforeach
    </select>
</div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="required-field">Status</label>
                                            <br />
                                            <div class="form-check">
                                                <input type="radio" id="customRadioInline1" name="status"
                                                    value="Publish" class="form-check-input" {{ $product->status ==
                                                'Publish' ? 'checked' : '' }} required />
                                                <label class="form-check-label " for="customRadioInline1">Publish</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="customRadioInline2" name="status" value="Draft"
                                                    class="form-check-input" {{ $product->status ==
                                                'Draft' ? 'checked'
                                                : '' }} required />
                                                <label class="form-check-label" for="customRadioInline2">Draft</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="required-field">Price</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    data-feather="dollar-sign" class="feather-sm"></i></span>
                                            <input type="number" class="form-control" name="base_price"
                                                value="{{ $product->base_price }}" placeholder="" aria-label="price"
                                                aria-describedby="basic-addon1" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="required-field">Discount</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon2"><i
                                                    class="ri-scissors-2-line"></i></span>
                                            <input type="number" name="discount" class="form-control"
                                                value="{{ $product->discount }}" placeholder="" aria-label="Discount"
                                                aria-describedby="basic-addon2" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="control-label required-field">Quantity</label>
                                        <input type="number" name="stock" class="form-control"
                                            value="{{ $product->stock }}" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="control-label required-field">tax</label>
                                        <input type="number" name="tax" class="form-control" value="{{ $product->tax }}"
                                            required />
                                    </div>
                                </div>
                            </div>





                            <h5 class="card-title mt-5 required-field">Parts Description</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="product_description" class="form-control" rows="4"
                                            required>{{ $product->product_description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="card-title mt-3 required-field">Upload Image</h5>
                                    <div class="el-element-overlay">
                                        <div class="el-card-item">
                                            <div class="el-card-avatar el-overlay-1">
                                                @if($product->product_image)
                                                <img id="imagePreview"
                                                    src="{{ asset('public/product_image/' . $product->product_image) }}"
                                                    alt="product_image" />
                                                @else
                                                <img id="imagePreview" src="public/images/1.png" alt="default_image" />
                                                @endif
                                                <div class="el-overlay">
                                                    <!-- Add any overlay elements as needed -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn btn-info waves-effect waves-light">
                                        <span>Upload Another Image</span>
                                        <input id="file" value="{{$product->product_image}}" type="file"
                                            onchange="showImagePreview()" name="product_image" class="upload" />
                                    </div>
                                </div>

                                <div class="col-md-4" style="margin-top: 13px;">
                                    <div class="mb-3">
                                        <label class="control-label required-field">Part Assignment</label>
                                        <select id="productAssignmentSelect" name="assigned_to" class="form-select"
                                            data-placeholder="Choose an Option" tabindex="1">
                                            <option value="pending" @if($product->assigned_to == 'pending') selected
                                                @endif>Select to assign technicians
                                            </option>
                                            <option value="all" @if($product->assigned_to == 'all') selected
                                                @endif>Assign to All</option>
                                            <option value="selected" @if($product->assigned_to == 'selected') selected
                                                @endif>Assign to Selected
                                                Technicians
                                            </option>
                                        </select>
                                    </div>

                                    <div id="selectedTechnicians" style="">
                                        <label class="control-label">Select Technicians</label>
                                        <div class="technician-checkboxes">
                                            <!-- Add checkboxes for each technician -->
                                           @foreach($technicians as $technician)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="technician_id[]"
            value="{{ $technician->id }}"
            @if(collect($selectedTechnicians)->contains($technician->id)) checked @endif>
        <label class="form-check-label">{{ $technician->name }}</label>
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
                                                                value="{{ old('Color', $Color) }}" class="form-control"
                                                                placeholder="" />
                                                        </td>
                                                        <td>
                                                            <label class="control-label mb-2">Sizes</label>
                                                            <input type="text" name="Sizes"
                                                                value="{{ old('Sizes', $Sizes) }}" class="form-control"
                                                                placeholder="" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="control-label mb-2">Material</label>
                                                            <input type="text" name="material"
                                                                value="{{ old('material', $material) }}"
                                                                class="form-control" placeholder="" />
                                                        </td>
                                                        <td>
                                                            <label class="control-label mb-2">Weight</label>
                                                            <input type="text" name="weight"
                                                                value="{{ old('weight', $weight) }}"
                                                                class="form-control" placeholder="" />
                                                        </td>
                                                    </tr>




                                                    <!-- end row -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success rounded-pill px-4">Save</button>
                                        <button type="button" class="btn btn-dark rounded-pill px-4"
                                            onclick="cancelRedirect()">Cancel</button>

                                        <script>
                                            function cancelRedirect() {
                                            window.location.href = "{{ route('product.listingproduct') }}";
                                        }
                                        </script>
                                    </div>
                                </div>
                            </div>
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
                selectedTechniciansDiv.style.display = 'none';

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
            selectedTechniciansDiv.style.display = 'block';

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