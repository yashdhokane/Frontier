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
      <style>
          #main-wrapper[data-layout="vertical"][data-sidebartype="mini-sidebar"] .page-wrapper {
              margin-left: 0px !important;
          }

          /* .card-body {
                                                                                                                                                                                                                        padding: 0px !important;
                                                                                                                                                                                                                    } */

          .container-fluid {
              padding: 0px !important;
          }

          #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar {
              display: none !important;
          }

          #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar {
              display: none !important;
          }

          #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
              margin-left: 0px !important;
          }

          #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
              padding-top: 0px !important;
          }

          .page-wrapper {
              padding: 0px !important;
          }

          /* Make iframe content scrollable */
          html,
          body {
              overflow: auto !important;
              /* Allow scrolling */
              margin: 0;
              /* Remove default margins */
              padding: 0;
              /* Remove default padding */
          }
      </style>
      <!-- Page wrapper  -->
      <!-- -------------------------------------------------------------- -->
      <div class="page-wrapper" style="display:inline;">
          <!-- -------------------------------------------------------------- -->
          <!-- Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->
          <div class="card threedottest" style="display:block;">
              <div class="row card-body">
                  <!-- Search Input on the Left -->
                  <div class="col-6 align-self-center">
                      {{-- <form>
                                  <input type="text" class="form-control" id="searchInput" placeholder="Search Parts"
                                      onkeyup="filterTable()" />
                              </form> --}}
                      <h4 class="breadcrumb-item active" aria-current="page">Edit</h4>

                  </div>

                  <!-- Three Dot Dropdown on the Right -->
                  <div class="col-6 align-self-center">
                      <div class="d-flex justify-content-end">
                          <!-- Dropdown Menu for Filters -->
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                              <div class="dropdown">
                                  <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                      aria-expanded="false">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round"
                                          class="feather feather-more-vertical feather-sm">
                                          <circle cx="12" cy="12" r="1"></circle>
                                          <circle cx="12" cy="5" r="1"></circle>
                                          <circle cx="12" cy="19" r="1"></circle>
                                      </svg>
                                  </a>
                                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                      <!-- Filters Section -->
                                      <li>
                                          <a href="{{ route('tool.index_iframe') }}" class="dropdown-item ">Back</a>
                                      </li>

                                      <!-- Parts, Tools, vehicle_iframe_index -->
                                      <li>
                                          <a href="{{ route('product.index_iframe') }}"
                                              class="dropdown-item {{ Route::currentRouteName() === 'product.index_iframe' ? 'btn-info' : '' }}">Parts</a>
                                      </li>
                                      <li>
                                          <a href="{{ route('tool.index_iframe') }}"
                                              class="dropdown-item {{ Route::currentRouteName() === 'tool.index_iframe' ? 'btn-info' : '' }}">Tools</a>
                                      </li>
                                      <li>
                                          <a href="{{ route('vehicle_iframe_index') }}"
                                              class="dropdown-item {{ Route::currentRouteName() === 'vehicle_iframe_index' ? 'btn-info' : '' }}">vehicle</a>
                                      </li>
                                      <li class="dropdown-submenu">
                                          <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'iframe_part_assign' || Route::currentRouteName() === 'assign_tool.iframe' ? 'btn-info' : '' }}"
                                              href="#">Assign</a>
                                          <ul class="dropdown-menu">
                                              <li><a class="dropdown-item {{ Route::currentRouteName() === 'iframe_part_assign' ? 'btn-info' : '' }}"
                                                      href="{{ route('iframe_part_assign') }}">Parts</a></li>
                                              <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool.iframe' ? 'btn-info' : '' }}"
                                                      href="{{ route('assign_tool.iframe') }}">Tools</a></li>
                                          </ul>
                                      </li>
                                      <li class="dropdown-submenu">
                                          <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'iframeaddvehicle' || Route::currentRouteName() === 'product.createproduct.iframe' || Route::currentRouteName() === 'tool.createtool.iframe' ? 'btn-info' : '' }}"
                                              href="#">Add New</a>
                                          <ul class="dropdown-menu">
                                              <li><a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct.iframe' ? 'btn-info' : '' }}"
                                                      href="{{ route('product.createproduct.iframe') }}">Parts</a>
                                              </li>
                                              <li><a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool.iframe' ? 'btn-info' : '' }}"
                                                      href="{{ route('tool.createtool.iframe') }}">Tools</a></li>
                                              <li><a class="dropdown-item {{ Route::currentRouteName() === 'iframeaddvehicle' ? 'btn-info' : '' }}"
                                                      href="{{ route('iframeaddvehicle') }}">vehicle</a>
                                              </li>
                                          </ul>
                                      </li>
                                      <li>
                                          <a href="{{ route('partCategoryiframe') }}"
                                              class="dropdown-item {{ Route::currentRouteName() === 'partCategoryiframe' ? 'btn-info' : '' }}">Categories</a>
                                      </li>
                                      <li>
                                          <hr class="dropdown-divider">
                                      </li>
                                      {{-- <li>
                                          <a href="#." id="filterButton" class="dropdown-item">
                                              <i class="ri-filter-line"></i> Filters
                                          </a>
                                      </li> --}}

                                  </ul>
                              </div>
                          </div>


                      </div>
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
          <div class="container-fluid">
              <!-- -------------------------------------------------------------- -->
              <!-- Start Page Content -->
              <!-- -------------------------------------------------------------- -->



              <form action="{{ route('tool.iframe_update', $product->product_id) }}" method="post"
                  enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                  <div class="row">
                      <!-- Column -->

                      <div class="col-md-9">
                          <!-- ---------------------
                                                                                                            start About Product
                                                                                                        ---------------- -->
                          <div class="card">
                              <div class="card-body card-border">

                                  <div class="form-body">
                                      <div class="row">
                                          <div class="col-md-9">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Tools Name</label>
                                                  <input type="text" name="product_name" class="form-control"
                                                      value="{{ $product->product_name }}" required />
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Tool Code</label>
                                                  <input type="text" name="product_code" class="form-control"
                                                      value="{{ $product->product_code }}" required />
                                              </div>
                                          </div>
                                          {{-- <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="control-label required-field bold mb5">Short
                                                Description</label>
                                            <input type="text" name="product_short" class="form-control"
                                                value="{{ $product->product_short }}" required />
                                        </div>
                                    </div> --}}
                                          <div class="col-md-12">
                                              <div class="mb-3">
                                                  <label class="control-label required-field bold mb5">Long
                                                      Description</label>
                                                  <textarea name="product_description" class="form-control" rows="4" required>{{ $product->product_description }}</textarea>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="mb-3">
                                                  <label for="product_category_id"
                                                      class="control-label col-form-label required-field bold">Tools
                                                      Category</label>
                                                  <select class="form-select me-sm-2" id="product_category_id"
                                                      name="product_category_id" required>
                                                      <option selected disabled value="">Select Tools Category...
                                                      </option>
                                                      @foreach ($productCategories as $category)
                                                          <option value="{{ $category->id }}"
                                                              @if ($product->product_category_id == $category->id) selected @endif>
                                                              {{ $category->category_name }}
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
                                                          <option value="{{ $manufacturer->id }}"
                                                              @if ($product->product_manu_id == $manufacturer->id) selected @endif>
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
                                              <label class="control-label  bold mb5">Upload Image</label>
                                              <input id="file" value="{{ $product->product_image }}" type="file"
                                                  onchange="showImagePreview()" name="product_image"
                                                  class="upload form-control" />
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="el-element-overlay">
                                              <div class="el-card-item">
                                                  <div class="el-card-avatar el-overlay-1">
                                                      @if ($product->product_image)
                                                          <img id="imagePreview"
                                                              src="{{ asset('public/product_image/' . $product->product_image) }}"
                                                              alt="product_image" />
                                                      @endif
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>





                              </div>


                          </div>

                          <div class="card">
                              {{-- <div class="card-body card-border">
                            <h5 class="card-title">GENERAL INFO</h5>
                            <div class="table-responsive">
                                <table class="table td-padding">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label class="control-label mb-2">Color</label>
                                                <input type="text" name="Color" value="{{ old('Color', $Color) }}"
                                                    class="form-control" placeholder="" />
                                            </td>
                                            <td>
                                                <label class="control-label mb-2">Sizes</label>
                                                <input type="text" name="Sizes" value="{{ old('Sizes', $Sizes) }}"
                                                    class="form-control" placeholder="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control-label mb-2">Material</label>
                                                <input type="text" name="material"
                                                    value="{{ old('material', $material) }}" class="form-control"
                                                    placeholder="" />
                                            </td>
                                            <td>
                                                <label class="control-label mb-2">Weight</label>
                                                <input type="text" name="weight" value="{{ old('weight', $weight) }}"
                                                    class="form-control" placeholder="" />
                                            </td>
                                        </tr>




                                        <!-- end row -->
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                          </div>



                      </div>

                      <div class="col-md-3">

                          <div class="card">
                              <div class="card-body card-border">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="mb-4">
                                              <label class="control-label required-field bold mb5">Stock Status</label>
                                              <div class="form-check form-switch">
                                                  <input class="form-check-input" name="stock_status" type="checkbox"
                                                      value="yes" id="flexSwitchCheckChecked"
                                                      {{ $product->stock_status == 'in_stock' ? 'checked' : '' }}>
                                                  <label class="form-check-label" for="flexSwitchCheckChecked"> In Stock
                                                      or
                                                      Out
                                                      of Stock</label>
                                              </div>
                                          </div>
                                          <div class="mb-2">
                                              <label class="control-label required-field bold mb5">Quantity</label>
                                              <input type="number" name="stock" class="form-control"
                                                  value="{{ $product->stock }}" required />
                                          </div>
                                          <div class="mb-2">
                                              <label class="control-label required-field bold mb5">Unit Price</label>

                                              <input type="number" class="form-control" name="base_price"
                                                  value="{{ $product->base_price }}" placeholder="" aria-label="price"
                                                  aria-describedby="basic-addon1" required />
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>



                          <div class="card">
                              <div class="card-body card-border">
                                  <h5 class="card-title">Tool ASSIGNMENT</h5>
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="mb-3">
                                              <label class="control-label required-field">Assign to</label>
                                              <select id="productAssignmentSelect" name="assigned_to" class="form-select"
                                                  data-placeholder="Choose an Option" tabindex="1">
                                                  <option value="pending"
                                                      @if ($product->assigned_to == 'pending') selected @endif>
                                                      Please select
                                                  </option>
                                                  <option value="all" @if ($product->assigned_to == 'all') selected @endif>
                                                      All
                                                      Technicians</option>
                                                  <option value="selected"
                                                      @if ($product->assigned_to == 'selected') selected @endif>
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
                                                                  name="technician_id[]" value="{{ $technician->id }}"
                                                                  {{ collect($selectedTechnicians)->contains($technician->id) ? 'checked' : '' }}>
                                                              <label
                                                                  class="form-check-label">{{ $technician->name }}</label>

                                                              @php
                                                                  $assign = DB::table('tool_assigned')
                                                                      ->where('technician_id', $technician->id)
                                                                      ->where('product_id', $product->product_id)
                                                                      ->get();

                                                                  $quantityCount = $assign->isEmpty()
                                                                      ? 0
                                                                      : $assign->sum('quantity');
                                                              @endphp

                                                              @if ($assign->isNotEmpty())
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
                                  <div class="card-body card-border">
                                      <div class="row">
                                          <div class="form-actions">
                                              <button type="submit"
                                                  class="btn btn-success rounded-pill px-4">Save</button>
                                              <button type="button" class="btn btn-dark rounded-pill px-4"
                                                  onclick="cancelRedirect()">Cancel</button>

                                              <script>
                                                  function cancelRedirect() {
                                                      window.location.href = "{{ route('tool.index_iframe') }}";
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
                          selectedTechniciansDiv.style.display = 'none';

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
                      selectedTechniciansDiv.style.display = 'block';

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
