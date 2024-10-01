  @if (Route::currentRouteName() != 'dash')
      @extends('home')
      @section('content')
      @endif
      <!-- -------------------------------------------------------------- -->
      <!-- Main wrapper - style you can find in pages.scss -->
      <!-- -------------------------------------------------------------- -->
      <div id="main-wrapper">
          <!-- Page wrapper  -->
          <!-- -------------------------------------------------------------- -->

          <!-- -------------------------------------------------------------- -->
          <!-- Bread crumb and right sidebar toggle -->
          <div class="card threedottest" style="display:block;">
              <div class="row card-body">
                  <!-- Search Input on the Left -->
                  <div class="col-6 align-self-center">
                      {{-- <form>
                                  <input type="text" class="form-control" id="searchInput" placeholder="Search Parts"
                                      onkeyup="filterTable()" />
                              </form> --}}
                      <h4 class="breadcrumb-item active" aria-current="page">Assign Tool</h4>

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
                                          <a href="{{ route('tool.index_iframe') }}" class="dropdown-item">Back</a>
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
                                      <li>
                                          <a href="#." id="filterButton" class="dropdown-item">
                                              <i class="ri-filter-line"></i> Filters
                                          </a>
                                      </li>

                                  </ul>
                              </div>
                          </div>


                      </div>
                  </div>
              </div>
          </div>
          @if (Session::has('success'))
              <div class="alert_wrap">
                  <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                      {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                          aria-label="Close"></button>
                  </div>
              </div>
          @endif
          <!-- -------------------------------------------------------------- -->
          <!-- End Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->
          <!-- -------------------------------------------------------------- -->
          <!-- Container fluid  -->
          <!-- -------------------------------------------------------------- -->
          <div class="container-fluid px-3 mt-2">
              <!-- -------------------------------------------------------------- -->
              <!-- Start Page Content -->
              <!-- -------------------------------------------------------------- -->
              <!-- basic table -->
              <div class="row">
                  <div class="col-md-12">

                      <div class="row">
                          <div class="col-md-12">
                              <div class="mb-2">
                                  <div class="card">
                                      <div class="card-body card-border shadow">

                                          <form action="{{ url('store/assign-tool') }}" method="POST">
                                              @csrf
                                              <div class="row">
                                                  <div class="col-md-4">
                                                      <label for="technician" class="control-label bold mb5"> Technicians
                                                      </label>
                                                      <select class="select2-with-menu-bg form-control"
                                                          name="technician_id[]" id="menu-bg-multiple2" multiple="multiple"
                                                          data-bgcolor="light" data-bgcolor-variation="accent-3"
                                                          data-text-color="blue" style="width: 100%" required>
                                                          @foreach ($technician as $item)
                                                              <option value="{{ $item->id }}">{{ $item->name }}
                                                              </option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                                  <div class="col-md-4">
                                                      <label for="parts" class="control-label bold mb5">Parts</label>
                                                      <select class="select2-with-menu-bg form-control"
                                                          name="product_id[]" id="menu-bg-multiple" multiple="multiple"
                                                          data-bgcolor="light" data-bgcolor-variation="accent-3"
                                                          data-text-color="blue" style="width: 100%" required>
                                                          @foreach ($product as $item)
                                                              <option value="{{ $item->product_id }}">
                                                                  {{ $item->product_name }}</option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="parts"
                                                          class="control-label bold mb5">Quantity</label>
                                                      <input type="number" class="form-control" name="quantity"
                                                          required>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <button type="submit"
                                                          class="btn waves-effect waves-light btn-primary"
                                                          style="margin-top: 25px;margin-left: 20px;width: 140px;">Assign</button>
                                                  </div>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="row" id="masonry-grid">
                          @foreach ($technician as $item)
                              <div class="col-md-3 masonry-item">
                                  <div class="mb-2">
                                      <div class="card">
                                          <div class="card-body card-border shadow text-left">
                                              <h4 class="card-title mb-2">{{ $item->name }}</h4>
                                              <h6 class="mb-2">Assigned to </h6>
                                              <ul class="list-group list-group-horizontal-xl">
                                                  @php
                                                      $assignedProducts = [];
                                                  @endphp

                                                  @foreach ($assign->where('technician_id', $item->id) as $assignment)
                                                      @if (!isset($assignedProducts[$assignment->product_id]))
                                                          @php
                                                              $assignedProducts[$assignment->product_id] =
                                                                  $assignment->quantity;
                                                          @endphp
                                                      @else
                                                          @php
                                                              $assignedProducts[$assignment->product_id] +=
                                                                  $assignment->quantity;
                                                          @endphp
                                                      @endif
                                                  @endforeach

                                                  @if (empty($assignedProducts))
                                                      <p>No Part Assigned</p>
                                                  @else
                                                      @foreach ($assignedProducts as $productId => $quantity)
                                                          @php
                                                              $productItem = $product->find($productId);
                                                          @endphp
                                                          @if ($productItem)
                                                              <li class="list-group-item d-flex align-items-center">
                                                                  <i class="text-info fas fa-user mx-2"></i>
                                                                  {{ $productItem->product_name }} ({{ $quantity }})
                                                              </li>
                                                          @else
                                                              <li class="list-group-item d-flex align-items-center">
                                                                  <i
                                                                      class="text-danger fas fa-exclamation-triangle mx-2"></i>
                                                                  Product not found ({{ $quantity }})
                                                              </li>
                                                          @endif
                                                      @endforeach
                                                  @endif
                                              </ul>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>

                      <h4>Inactive Technicians </h4>
                      <div class="row" id="masonry-grid">
                          @foreach ($technician1 as $item)
                              <div class="col-md-3 masonry-item">
                                  <div class="mb-2">
                                      <div class="card">
                                          <div class="card-body card-border shadow text-left">
                                              <h4 class="card-title mb-2">{{ $item->name }}</h4>
                                              <h6 class="mb-2">Assigned to </h6>
                                              <ul class="list-group list-group-horizontal-xl">
                                                  @php
                                                      $assignedProducts = [];
                                                  @endphp

                                                  @foreach ($assign->where('technician_id', $item->id) as $assignment)
                                                      @if (!isset($assignedProducts[$assignment->product_id]))
                                                          @php
                                                              $assignedProducts[$assignment->product_id] =
                                                                  $assignment->quantity;
                                                          @endphp
                                                      @else
                                                          @php
                                                              $assignedProducts[$assignment->product_id] +=
                                                                  $assignment->quantity;
                                                          @endphp
                                                      @endif
                                                  @endforeach

                                                  @if (empty($assignedProducts))
                                                      <p>No Part Assigned</p>
                                                  @else
                                                      @foreach ($assignedProducts as $productId => $quantity)
                                                          @php
                                                              $productItem = $product->find($productId);
                                                          @endphp
                                                          @if ($productItem)
                                                              <li class="list-group-item d-flex align-items-center">
                                                                  <i class="text-info fas fa-user mx-2"></i>
                                                                  {{ $productItem->product_name }} ({{ $quantity }})
                                                              </li>
                                                          @else
                                                              <li class="list-group-item d-flex align-items-center">
                                                                  <i
                                                                      class="text-danger fas fa-exclamation-triangle mx-2"></i>
                                                                  Product not found ({{ $quantity }})
                                                              </li>
                                                          @endif
                                                      @endforeach
                                                  @endif
                                              </ul>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                  </div>




              </div>
          </div>
          <!-- -------------------------------------------------------------- -->
          <!-- End Container fluid  -->


          <!-- -------------------------------------------------------------- -->
          <!-- End Page wrapper  -->
          <!-- -------------------------------------------------------------- -->
      </div>
      </div>

      <!-- -------------------------------------------------------------- -->
      <!-- End Wrapper -->
      <!-- -------------------------------------------------------------- -->
      <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
      <!-- Include imagesLoaded.js -->
      <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
      <script>
          window.onload = function() {
              var elem = document.querySelector('#masonry-grid');
              imagesLoaded(elem, function() {
                  var msnry = new Masonry(elem, {
                      itemSelector: '.masonry-item',
                      columnWidth: '.masonry-item',
                      percentPosition: true
                  });
              });
          };
      </script>
      @if (Route::currentRouteName() != 'dash')
      @endsection
  @endif
