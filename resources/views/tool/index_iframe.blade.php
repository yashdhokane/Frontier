  @if (Route::currentRouteName() != 'dash')
      @extends('home')
      @section('content')
      @endif <!-- Page wrapper  -->

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
      <div class="page-wrapper" style="display:inline;">
          <!-- -------------------------------------------------------------- -->
          <!-- Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->

          <!-- -------------------------------------------------------------- -->
          <!-- End Bread crumb and right sidebar toggle -->
          <!-- -------------------------------------------------------------- -->
          <!-- -------------------------------------------------------------- -->
          <!-- Container fluid  -->
          @if (Session::has('success'))
              <div class="alert_wrap">
                  <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                      {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                          aria-label="Close"></button>
                  </div>
              </div>
          @endif

          @if (Session::has('error'))
              <div class="alert_wrap">
                  <div class="alert alert-danger">
                      {{ Session::get('error') }}
                  </div>
              </div>
          @endif
          <!-- -------------------------------------------------------------- -->
          <div class="container-fluid">
              <!-- -------------------------------------------------------------- -->
              <!-- Start Page Content -->
              <!-- -------------------------------------------------------------- -->

              <div class="card threedottest" style="display:block;">
                  <div class="row card-body">
                      <!-- Search Input on the Left -->
                      <div class="col-6 align-self-center">
                          <input type="text" class="form-control" id="searchInput" placeholder="Search Tools"
                              onkeyup="filterTable()" />
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

              <div id="filterDiv" class="card card-body shadow" style="display: none;">

                  <div class="row">
                      <div class="col-md-12 mb-3">
                          <label for="technician_filter" class="form-label">Technician</label>
                          <select class="form-select" name="technician" id="technician_filter">
                              <option value="">All</option>
                              @foreach ($technician as $tech)
                                  <option value="{{ $tech->name }}">{{ $tech->name }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-12 mb-3">
                          <label for="stock_filter" class="form-label">Stock</label>
                          <select class="form-select" name="stock" id="stock_filter">
                              <option value="">All</option>
                              <option value="in_stock">In Stock</option>
                              <option value="out_of_stock">Out of Stock</option>
                          </select>
                      </div>
                      <div class="col-md-12 mb-3">
                          <label for="status_filter" class="form-label">Status</label>
                          <select class="form-select" name="status" id="status_filter">
                              <option value="">All</option>
                              <option value="Active">Active</option>
                              <option value="Inactive">Inactive</option>
                          </select>
                      </div>
                      {{-- <div class="col-md-12">
                                            <button class="btn btn-primary w-100" onclick="applyFilters()">Apply
                                                Filters</button>
                                        </div> --}}
                  </div>

              </div>

              <div class="row ">
                  <!-- Column -->
                  <div class="col-lg-12">

                      <!-- ---------------------
                                                                                                                                                                                                                                start Product Orders
                                                                                                                                                                                                                            ---------------- -->
                      <div class="card">
                          <div class="card-body card-border shadow">
                              <div class="table-responsive">
                                  <table class="table product-overview" id="zero_config">
                                      <div class="row withoutthreedottest">
                                          <div class="col-md-3 mb-3">
                                              <label for="technician_filter" class="form-label">Technician</label>
                                              <select class="form-select" name="technician" id="technician_filter1">
                                                  <option value="">All</option>
                                                  @foreach ($technician as $tech)
                                                      <option value="{{ $tech->name }}">{{ $tech->name }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-3 mb-3">
                                              <label for="stock_filter" class="form-label">Stock</label>
                                              <select class="form-select" name="stock" id="stock_filter1">
                                                  <option value="">All</option>
                                                  <option value="in_stock">In Stock</option>
                                                  <option value="out_of_stock">Out of Stock</option>
                                              </select>
                                          </div>
                                          <div class="col-md-3 mb-3">
                                              <label for="status_filter" class="form-label">Status</label>
                                              <select class="form-select" name="status" id="status_filter1">
                                                  <option value="">All</option>
                                                  <option value="Active">Active</option>
                                                  <option value="Inactive">Inactive</option>
                                              </select>
                                          </div>


                                      </div>

                                      <thead>
                                          <tr>
                                              <th>ID</th>
                                              <th>Tools</th>
                                              <th>Category</th>
                                              <th>Quantity</th>
                                              <th>Price</th>
                                              <th>Stock</th>
                                              <th>Status</th>
                                              <th>Actions</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach ($products as $index => $item)
                                              <tr>
                                                  <td>{{ $index + 1 }}</td>
                                                  <td>
                                                      <div class="d-flex align-items-center">
                                                          @if ($item->product_image)
                                                              <img src="{{ asset('public/product_image/' . $item->product_image) }}"
                                                                  alt="{{ $item->product_name }}" class="rounded-circle"
                                                                  width="45" />
                                                          @else
                                                              <img src="{{ asset('public/images/default-part-image.png') }}"
                                                                  alt="{{ $item->product_name }}" class="rounded-circle"
                                                                  width="45" />
                                                          @endif
                                                          <div class="ms-2">
                                                              <div class="user-meta-info">
                                                                  <a
                                                                      href="{{ route('tool.edit.iframe', ['product_id' => $item->product_id]) }}">
                                                                      @if ($item->toolassign->isNotEmpty())
                                                                          @php
                                                                              $technicianNames = $item->toolassign
                                                                                  ->pluck('Technician.name')
                                                                                  ->unique()
                                                                                  ->filter()
                                                                                  ->implode('<br>');
                                                                          @endphp
                                                                          <h6 class="user-name mb-0" data-name="name"
                                                                              data-bs-toggle="tooltip" data-bs-html="true"
                                                                              title="{!! $technicianNames !!}">
                                                                              {{ $item->product_name }}
                                                                          </h6>
                                                                      @else
                                                                          <h6 class="user-name mb-0" data-name="name">
                                                                              {{ $item->product_name }}
                                                                          </h6>
                                                                      @endif
                                                                  </a>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </td>
                                                  <td>{{ $item->categoryProduct->category_name ?? null }}</td>
                                                  <td>{{ $item->stock }}</td>
                                                  <td>${{ $item->base_price }}</td>
                                                  <td>
                                                      @if ($item->stock_status == 'in_stock')
                                                          <span class="status_icons status_icon1"><i
                                                                  class="ri-check-fill"></i></span>
                                                      @elseif($item->stock_status == 'out_of_stock')
                                                          <span class="status_icons status_icon2"><i
                                                                  class="ri-close-line"></i></span>
                                                      @endif
                                                  </td>
                                                  <td>
                                                      @if ($item->status == 'Publish')
                                                          Active
                                                      @elseif($item->status == 'Draft')
                                                          Inactive
                                                      @endif
                                                  </td>
                                                  <td data-status="{{ $item->status }}">
                                                      <div class="btn-group">
                                                          <button type="button"
                                                              class="btn btn-light-primary text-primary dropdown-toggle"
                                                              data-bs-toggle="dropdown" aria-haspopup="true"
                                                              aria-expanded="false">
                                                              <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                          </button>
                                                          <div class="dropdown-menu">
                                                              @if ($item->status == 'Publish')
                                                                  <a class="dropdown-item"
                                                                      href="{{ url('inactive/tool-iframe/' . $item->product_id) }}">
                                                                      <i data-feather="edit-2"
                                                                          class="feather-sm me-2"></i>
                                                                      Inactive
                                                                  </a>
                                                              @else
                                                                  <a class="dropdown-item"
                                                                      href="{{ url('active/tool-iframe/' . $item->product_id) }}">
                                                                      <i data-feather="edit-2"
                                                                          class="feather-sm me-2"></i>
                                                                      Active
                                                                  </a>
                                                              @endif
                                                              <a href="{{ route('tool.edit.iframe', ['product_id' => $item->product_id]) }}"
                                                                  class="text-dark pe-2 dropdown-item">
                                                                  <i class="fa fa-edit edit-sm fill-white pe-2"></i> Edit
                                                              </a>
                                                              <form method="post"
                                                                  action="{{ route('tool.iframe.destroy', ['id' => $item->product_id]) }}">
                                                                  @csrf
                                                                  @method('DELETE')
                                                                  <a href="{{ route('tool.iframe.destroy', ['id' => $item->product_id]) }}"
                                                                      class="text-dark dropdown-item">
                                                                      <i data-feather="trash-2"
                                                                          class="feather-sm fill-white"></i>
                                                                      Delete
                                                                  </a>
                                                              </form>
                                                          </div>
                                                      </div>
                                                  </td>
                                              </tr>
                                          @endforeach
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                      <!-- ---------------------
                                                                                                                                                                                                                                end Product Orders
                                                                                                                                                                                                                            ---------------- -->
                  </div>
                  <!-- Column -->
              </div>
              <!-- -------------------------------------------------------------- -->
              <!-- End PAge Content -->
              <!-- -------------------------------------------------------------- -->
          </div>
      </div>
      </div>

      @include('tool.scriptIndex')

      <script>
          $(document).ready(function() {
              if ($.fn.DataTable.isDataTable('#zero_config')) {
                  $('#zero_config').DataTable().destroy();
              }

              $('#zero_config').DataTable({
                  "order": [
                      [0, "desc"]
                  ],
                  "pageLength": 25,
              });
          });
      </script>

      <script>
          function filterTable() {
              var input = document.getElementById("searchInput");
              var filter = input.value.toLowerCase();
              var table = document.getElementById("zero_config");
              var rows = table.getElementsByTagName("tr");

              for (var i = 1; i < rows.length; i++) {
                  var row = rows[i];
                  var cells = row.getElementsByTagName("td");
                  var match = false;

                  for (var j = 0; j < cells.length; j++) {
                      var cell = cells[j];
                      if (cell) {
                          if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                              match = true;
                              break;
                          }
                      }
                  }

                  if (match) {
                      row.style.display = "";
                  } else {
                      row.style.display = "none";
                  }
              }
          }

          function applyFilters() {
              var technicianFilter = document.getElementById("technician_filter").value.toLowerCase();
              var stockFilter = document.getElementById("stock_filter").value.toLowerCase();
              var statusFilter = document.getElementById("status_filter").value.toLowerCase();
              var table = document.getElementById("zero_config");
              var rows = table.getElementsByTagName("tr");

              for (var i = 1; i < rows.length; i++) {
                  var row = rows[i];
                  var cells = row.getElementsByTagName("td");
                  var technicianMatch = technicianFilter === "" || cells[1].textContent.toLowerCase().indexOf(
                      technicianFilter) > -1;
                  var stockMatch = stockFilter === "" || cells[3].textContent.toLowerCase().indexOf(stockFilter) > -1;
                  var statusMatch = statusFilter === "" || cells[5].textContent.toLowerCase().indexOf(statusFilter) > -1;

                  if (technicianMatch && stockMatch && statusMatch) {
                      row.style.display = "";
                  } else {
                      row.style.display = "none";
                  }
              }
          }
      </script>

      <script>
          // Wait until the DOM is fully loaded
          document.addEventListener("DOMContentLoaded", function() {
              // Get the filter button and the filter div
              const filterButton = document.getElementById('filterButton');
              const filterDiv = document.getElementById('filterDiv');

              // Add a click event listener to the filter button
              filterButton.addEventListener('click', function() {
                  // Toggle the display of the filter div
                  if (filterDiv.style.display === 'none' || filterDiv.style.display === '') {
                      filterDiv.style.display = 'block'; // Show the filter section
                  } else {
                      filterDiv.style.display = 'none'; // Hide the filter section
                  }
              });
          });
      </script>
      <script>
          $(document).ready(function() {
              $('.dropdown-submenu .dropdown-toggle').on("click", function(e) {
                  var $subMenu = $(this).next('.dropdown-menu');
                  if (!$subMenu.hasClass('show')) {
                      $('.dropdown-menu .show').removeClass('show');
                      $subMenu.addClass('show');
                  } else {
                      $subMenu.removeClass('show');
                  }
                  e.stopPropagation();
              });

              // Ensure dropdown closes when clicking outside of it
              $(document).on("click", function() {
                  $('.dropdown-menu .show').removeClass('show');
              });
          });
      </script>

      @if (Route::currentRouteName() != 'dash')
      @endsection
  @endif