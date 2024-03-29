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
    <div class="page-breadcrumb" style="padding-top: 0px;">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">{{ $product->product_name }}</h4>
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
            
			
							
			<div class="col-md-9">
			
				<form action="{{ route('product.update', $product->product_id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                <!-- ---------------------
                            start About Product
                        ---------------- -->
                <div class="card">
                    <div class="card-body card-border">
  
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="mb-3">
                                            <label class="control-label required-field bold mb5">Parts Name</label>
                                            <input type="text" name="product_name" class="form-control"
                                                value="{{ $product->product_name }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="control-label required-field bold mb5">Part Code</label>
                                            <input type="text" name="product_code" class="form-control"
                                                value="{{ $product->product_code }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="control-label required-field bold mb5">Short Description</label>
                                            <input type="text" name="product_short" class="form-control"
                                                value="{{ $product->product_short }}" required />
                                        </div>
                                    </div>
									<div class="col-md-12">
										<div class="mb-3">
											<label class="control-label required-field bold mb5">Long Description</label>
											<textarea name="product_description" class="form-control" rows="4"
												required>{{ $product->product_description }}</textarea>
										</div>
									</div>
									<div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="product_category_id" class="control-label col-form-label required-field bold">Parts Category</label>
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
                                   <div class="col-md-4">
										<div class="mb-3">
											<label class="control-label col-form-label required-field bold">Manufacturer</label>
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
                                            <label class="control-label col-form-label required-field bold">Status</label>
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
								<div class="col-md-8">
									 <div class="mb-3">
 										<label class="control-label required-field bold mb5">Upload Image</label>
                                        <input id="file" value="{{$product->product_image}}" type="file"
                                            onchange="showImagePreview()" name="product_image" class="upload form-control" />
                                    </div>
								</div>
								<div class="col-md-4">
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
                                             </div>
                                        </div>
                                    </div>
                                 </div>
							</div>

 

                            
							
                    </div>

                   
                </div>
				
				<div class="card">
                    <div class="card-body card-border">
						<h5 class="card-title">GENERAL INFO</h5>
						<div class="table-responsive">
							<table class="table td-padding">
								<tbody>
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
				</div>
				
				<div class="card card text-center">
                    <div class="card-body card-border">
						<div class="row">
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
			
			<div class="col-md-3">
			
				<div class="card">
                    <div class="card-body card-border">
                         <div class="row">
							<div class="col-md-12">
 								<div class="mb-4">
									<label class="control-label required-field bold mb5">Stock Status</label>
									<div class="form-check form-switch">
 										<input class="form-check-input" name="stock_status" type="checkbox" value="yes"
											id="flexSwitchCheckChecked" {{ $product->stock_status == 'in_stock' ? 'checked' : '' }}>
										<label class="form-check-label" for="flexSwitchCheckChecked"> In Stock or Out of Stock</label>
									</div>
								</div>
								<div class="mb-2">
									<label class="control-label required-field bold mb5">Quantity</label>
									<input type="number" name="stock" class="form-control"
										value="{{ $product->stock }}" required />
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="card">
                    <div class="card-body card-border">
                         <div class="row">
							<div class="col-md-12">
  								<div class="mb-4">
									<label class="required-field bold mb5">Unit Price</label>
									<div class="input-group mb-3">
									 
										<input type="number" class="form-control" name="base_price"
											value="{{ $product->base_price }}" placeholder="" aria-label="price"
											aria-describedby="basic-addon1" required />
									</div>
								</div>
 								<div class="mb-4">
									<label class="required-field bold mb5">Discount (In Percentage)</label>
									<div class="input-group mb-3">
 										<input type="number" name="discount" class="form-control"
											value="{{ $product->discount }}" placeholder="" aria-label="Discount"
											aria-describedby="basic-addon2" required />
									</div>
								</div>
								<div class="mb-4">
									<label class="control-label required-field bold mb5">Tax (In Percentage)</label>
									<input type="number" name="tax" class="form-control" value="{{ $product->tax }}"
										required />
								</div>
								<div class="mb-4">
									<label class="required-field bold mb5">Total</label>
									<div class="input-group mb-3"><input type="number" class="form-control" name="total" value="{{ $product->total }}" placeholder="" aria-label="price" aria-describedby="basic-addon1" required />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="card">
                    <div class="card-body card-border">
 						<h5 class="card-title">PART ASSIGNMENT</h5>
						<div class="row">
							 <div class="col-md-12">
								<div class="mb-3">
									<label class="control-label required-field">Assign to</label>
									<select id="productAssignmentSelect" name="assigned_to" class="form-select"
										data-placeholder="Choose an Option" tabindex="1">
										<option value="pending" @if($product->assigned_to == 'pending') selected
											@endif>Please select
										</option>
										<option value="all" @if($product->assigned_to == 'all') selected
											@endif>All Technicians</option>
										<option value="selected" @if($product->assigned_to == 'selected') selected
											@endif>Selected Technicians
										</option>
									</select>
								</div>
								<div class="mb-3">
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
							</div>
						</div>
 					</div>
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