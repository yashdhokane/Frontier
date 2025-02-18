  @if (Route::currentRouteName() != 'dash')
      @extends('home')
      @section('content')
      @endif

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
      <!-- -------------------------------------------------------------- -->
      <!-- Main wrapper - style you can find in pages.scss -->
      <!-- -------------------------------------------------------------- -->
      <div id="main-wrapper">
          <!-- Page wrapper  -->
          <!-- -------------------------------------------------------------- -->

          <!-- -------------------------------------------------------------- -->
          <!-- Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->
          {{-- <div class="page-breadcrumb">
              <div class="row">
                  <div class="col-5 align-self-center">
                      <h4 class="page-title">Assign Parts</h4>
                      <div class="d-flex align-items-center">
                          <nav aria-label="breadcrumb">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                                  <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Parts</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Assign Parts</li>
                              </ol>
                          </nav>
                      </div>
                  </div>
                  <div class="col-7 text-end px-4">
                      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                          <a href="{{ route('product.index') }}"
                              class="btn {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : 'btn-secondary text-white' }}">Parts</a>
                          <a href="{{ route('tool.index') }}"
                              class="btn {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : 'btn-secondary text-white' }}">Tools</a>
                          <a href="{{ route('vehicles') }}"
                              class="btn {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : 'btn-secondary text-white' }}">Vehicles</a>
                          <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button"
                                  class="btn {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'btn-secondary text-white' }} dropdown-toggle"
                                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Assign
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item {{ Route::currentRouteName() === 'assign_product' ? 'btn-info' : 'text-info' }}"
                                      href="{{ route('assign_product') }}">Parts</a>
                                  <a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'text-info' }}"
                                      href="{{ route('assign_tool') }}">Tools</a>

                              </div>
                          </div>
                          <div class="btn-group" role="group">
                              <button id="btnGroupDrop2" type="button"
                                  class="btn {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'btn-secondary text-white' }} dropdown-toggle"
                                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Add New
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                                  <a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct' ? 'btn-info' : 'text-info' }}"
                                      href="{{ route('product.createproduct') }}">Parts</a>
                                  <a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'text-info' }}"
                                      href="{{ route('tool.createtool') }}">Tools</a>
                                  <a class="dropdown-item {{ Route::currentRouteName() === 'addvehicle' ? 'btn-info' : 'text-info' }}"
                                      href="{{ route('addvehicle') }}">Vehicles</a>

                              </div>
                          </div>
                          <a href="{{ route('partCategory') }}"
                              class="btn {{ Route::currentRouteName() === 'partCategory' ? 'btn-info' : 'btn-secondary text-white' }}">Categories</a>
                      </div>
                  </div>


              </div>
          </div> --}}

          <div class="card threedottest" style="display:block;">
              <div class="row card-body">
                  <!-- Search Input on the Left -->
                  <div class="col-6 align-self-center">
                      {{-- <form>
                                  <input type="text" class="form-control" id="searchInput" placeholder="Search Parts"
                                      onkeyup="filterTable()" />
                              </form> --}}
                      <h4 class="breadcrumb-item active" aria-current="page">Assign Parts</h4>

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

                                      <!-- Parts, Tools, vehicle_iframe_index -->
                                      <li>
                                          <a href="{{ route('product.index_iframe') }}" class="dropdown-item ">Back</a>
                                      </li>
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

                                          <form action="{{ url('store/assign-product') }}" method="POST">
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

                      <h4>Inactive Technicians</h4>

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
      </div>

      <!-- -------------------------------------------------------------- -->
      <!-- End Container fluid  -->


      <!-- -------------------------------------------------------------- -->
      <!-- End Page wrapper  -->
      <!-- -------------------------------------------------------------- -->
      </div>
      <!-- -------------------------------------------------------------- -->
      <!-- End Wrapper -->
      <!-- -------------------------------------------------------------- -->
      <!-- Include Masonry.js -->
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
