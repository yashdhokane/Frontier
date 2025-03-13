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
      <!-- -------------------------------------------------------------- -->
      <!-- Bread crumb and right sidebar toggle -->
      <!-- -------------------------------------------------------------- -->
      <div class="page-breadcrumb ms-2">
          <div class="row">
              <div class="col-4 align-self-center">
                  <h4 class="page-title">Add New Part</h4>
                  <div class="d-flex align-items-center">
                      <nav aria-label="breadcrumb">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Parts</a></li>
                              <li class="breadcrumb-item active" aria-current="page">Add </li>
                          </ol>
                      </nav>
                  </div>
              </div>
        <div class="col-8 text-end px-4">
                @include('header-top-nav.asset-nav')
        </div>
          </div>
      </div>
      <!-- -------------------------------------------------------------- -->
      <!-- End Bread crumb and right sidebar toggle -->
      <!-- -------------------------------------------------------------- -->
      <!-- -------------------------------------------------------------- -->
      <!-- Container fluid  -->
      @if (session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif
      <!-- -------------------------------------------------------------- -->
      <div class="container-fluid pt-2">
          <!-- -------------------------------------------------------------- -->
          <!-- Start Page Content -->
          <!-- -------------------------------------------------------------- -->
          <div class="row">

              <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                  @csrf

                  <div class="row">
                      <!-- Column -->

                      <div class="col-md-9">
                          <!-- ---------------------
                                            start About Product
                                        ---------------- -->
                          <div class="card">
                              <div class="card-body card-border shadow">

                                  <div class="form-body">
                                      <div class="row">
                                          <div class="col-md-9">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Parts Name</label>
                                                  <input type="text" name="product_name" class="form-control" required />
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Part Code</label>
                                                  <input type="text" name="product_code" class="form-control" required />
                                              </div>
                                          </div>
                                          <div class="col-md-12">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Short
                                                      Description</label>
                                                  <input type="text" name="product_short" class="form-control"
                                                      required />
                                              </div>
                                          </div>
                                          <div class="col-md-12">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Long
                                                      Description</label>
                                                  <textarea name="product_description" class="form-control" rows="4" required></textarea>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="mb-3">
                                                  <label for="product_category_id"
                                                      class="control-label col-form-label required-field bold">Parts
                                                      Category</label>
                                                  <select class="form-select me-sm-2" id="product_category_id"
                                                      name="product_category_id" required>
                                                      <option selected disabled value="">Select Parts Category...
                                                      </option>
                                                      @foreach ($product as $product)
                                                          <option value="{{ $product->id }}">
                                                              {{ $product->category_name }}
                                                          </option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="mb-3">
                                                  <label
                                                      class="control-label col-form-label required-field bold">Manufacturer</label>
                                                  <select class="form-select" name="product_manu_id"
                                                      data-placeholder="Choose a Manufacturer" tabindex="1">
                                                      @foreach ($manufacture as $manufacturer)
                                                          <option value="{{ $manufacturer->id }}">
                                                              {{ $manufacturer->manufacturer_name }}
                                                          </option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>

                                      </div>

                                  </div>



                                  <div class="row">
                                      <div class="col-md-8">
                                          <div class="mb-3">
                                              <label class="control-label col-form-label required-field bold">Product
                                                  Image</label>
                                              <div class="btn waves-effect waves-light">
                                                  <input id="file" type="file" onchange="showImagePreview()"
                                                      name="product_image" class="upload" />
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="el-element-overlay">
                                              <div class="el-card-item">
                                                  <div class="el-card-avatar el-overlay-1">
                                                      <img src="" class="w-50" id="imagePreview" />
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>





                              </div>


                          </div>

                          <div class="card">
                              <div class="card-body card-border shadow">
                                  <h5 class="card-title">GENERAL INFO</h5>
                                  <div class="table-responsive">
                                      <table class="table td-padding">
                                          <tbody>
                                              <tr>
                                                  <td>
                                                      <label class="control-label mb-2">Color</label>
                                                      <input type="text" name="Color" class="form-control"
                                                          placeholder="" />
                                                  </td>
                                                  <td>
                                                      <label class="control-label mb-2">Sizes</label>
                                                      <input type="text" name="Sizes" class="form-control"
                                                          placeholder="" />
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>
                                                      <label class="control-label mb-2">Material</label>
                                                      <input type="text" name="material" class="form-control"
                                                          placeholder="" />
                                                  </td>
                                                  <td>
                                                      <label class="control-label mb-2">Weight</label>
                                                      <input type="text" name="weight" class="form-control"
                                                          placeholder="" />
                                                  </td>
                                              </tr>




                                              <!-- end row -->
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>



                      </div>

                      <div class="col-md-3">

                          <div class="card">
                              <div class="card-body card-border shadow">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="mb-4">
                                              <label class="control-label required-field bold mb5">Stock Status</label>
                                              <div class="form-check form-switch">
                                                  <input class="form-check-input" name="stock_status" type="checkbox"
                                                      value="yes" id="flexSwitchCheckChecked" checked>
                                                  <label class="form-check-label" for="flexSwitchCheckChecked"> In Stock
                                                      or
                                                      Out
                                                      of Stock</label>
                                              </div>
                                          </div>
                                          <div class="mb-2">
                                              <label class="control-label required-field bold mb5">Quantity</label>
                                              <input type="number" name="stock" class="form-control" required />
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="card">
                              <div class="card-body card-border shadow">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="mb-4">
                                              <label class="required-field bold mb5">Unit Price</label>
                                              <div class="input-group mb-3">

                                                  <input type="text" class="form-control" name="base_price"
                                                      placeholder="" aria-label="price" aria-describedby="basic-addon1"
                                                      required />
                                              </div>
                                          </div>
                                          <div class="mb-4">
                                              <label class="required-field bold mb5">Discount (In Percentage)</label>
                                              <div class="input-group mb-3">
                                                  <input type="number" name="discount" class="form-control"
                                                      placeholder="" aria-label="Discount"
                                                      aria-describedby="basic-addon2" required />
                                              </div>
                                          </div>
                                          <div class="mb-4">
                                              <label class="required-field bold mb5">Total</label>
                                              <div class="input-group mb-3"><input type="text" class="form-control"
                                                      name="total" placeholder="" aria-label="price"
                                                      aria-describedby="basic-addon1" required />
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="card">
                              <div class="card-body card-border shadow">
                                  <h5 class="card-title">PART ASSIGNMENT</h5>
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="mb-3">
                                              <label class="control-label required-field">Assign to</label>
                                              <select id="productAssignmentSelect" name="assigned_to" class="form-select"
                                                  data-placeholder="Choose an Option" tabindex="1">
                                                  <option value="pending">
                                                      Please select
                                                  </option>
                                                  <option value="all">
                                                      All
                                                      Technicians</option>
                                                  <option value="selected">
                                                      Selected Technicians
                                                  </option>
                                              </select>
                                          </div>
                                          <div class="mb-3">
                                              <div id="selectedTechnicians" style="">
                                                  <label class="control-label">Select Technicians</label>
                                                  <div class="technician-checkboxes">
                                                      <!-- Add checkboxes for each technician -->
                                                      @foreach ($technicians as $technician)
                                                          <div class="form-check">
                                                              <input class="form-check-input" type="checkbox"
                                                                  name="technician_id[]" value="{{ $technician->id }}">
                                                              <label class="form-check-label">{{ $technician->name }}
                                                              </label>
                                                              @php
                                                                  $assign = DB::table('products_assigned')
                                                                      ->where('technician_id', $technician->id)
                                                                      ->where('product_id', $product->product_id)
                                                                      ->get();

                                                                  $quantityCount = $assign->isEmpty()
                                                                      ? 0
                                                                      : $assign->sum('quantity');
                                                              @endphp

                                                              @if ($assign)
                                                                  ({{ $quantityCount }})
                                                              @endif
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

                      <div class="row">
                          <div class="col-md-12">
                              <div class="card card text-center">
                                  <div class="card-body card-border shadow">
                                      <div class="row">
                                          <div class="form-actions">
                                              <button type="submit"
                                                  class="btn btn-success rounded-pill px-4">Save</button>
                                              <button type="button" class="btn btn-dark rounded-pill px-4"
                                                  onclick="cancelRedirect()">Cancel</button>

                                              <script>
                                                  function cancelRedirect() {
                                                      window.location.href = "{{ route('product.index') }}";
                                                  }
                                              </script>
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
              </form>
              <!-- Column -->
          </div>

      </div>

      @section('script')
          <script>
              document.addEventListener('DOMContentLoaded', function() {
                  var productAssignmentSelect = document.querySelector('#productAssignmentSelect');
                  var selectedTechniciansDiv = document.querySelector('#selectedTechnicians');
                  var technicianCheckboxes = document.querySelectorAll('input[name="technician_id[]"]');

                  productAssignmentSelect.addEventListener('change', function() {
                      if (productAssignmentSelect.value === 'selected') {
                          selectedTechniciansDiv.style.display = 'block';

                          // Uncheck all technician checkboxes
                          technicianCheckboxes.forEach(function(checkbox) {
                              checkbox.checked = false;
                          });
                      } else if (productAssignmentSelect.value === 'all') {
                          selectedTechniciansDiv.style.display = 'block';

                          // Check all technician checkboxes
                          technicianCheckboxes.forEach(function(checkbox) {
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
                      technicianCheckboxes.forEach(function(checkbox) {
                          checkbox.checked = true;
                      });
                  } else {
                      selectedTechniciansDiv.style.display = 'none';
                  }
              });
          </script>
      @endsection
      @if (Route::currentRouteName() != 'dash')
      @endsection
  @endif
