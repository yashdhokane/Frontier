@if (Route::currentRouteName() != 'dash')
@extends('home')
@section('content')
@endif
<div class="page-breadcrumb ">
    <div class="row withoutthreedottest">
        <div class="col-5 align-self-center">
            <h4 class="page-title">Parts</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#.">Asset Management</a></li>
                        <li class="breadcrumb-item"><a href="#">Parts</a></li>


                    </ol>
                </nav>
            </div>
        </div>
        @include('header-top-nav.asset-nav')
    </div>
</div>
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
<div class="container-fluid pt-2">
    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->

    <div class="card threedottest" style="display:none;">
        <div class="row card-body">
            <!-- Search Input on the Left -->
            <div class="col-6 align-self-center">
                <form>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Parts"
                        onkeyup="filterTable()" />
                </form>
            </div>

            <!-- Three Dot Dropdown on the Right -->
            <div class="col-6 align-self-center">
                <div class="d-flex justify-content-end">
                    <!-- Dropdown Menu for Filters -->
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="dropdown">
                            <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-more-vertical feather-sm">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="12" cy="5" r="1"></circle>
                                    <circle cx="12" cy="19" r="1"></circle>
                                </svg>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <!-- Filters Section -->

                                <!-- Parts, Tools, Vehicles -->
                                <li>
                                    <a href="{{ route('product.index') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : '' }}">Parts</a>
                                </li>
                                <li>
                                    <a href="{{ route('tool.index') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : '' }}">Tools</a>
                                </li>
                                <li>
                                    <a href="{{ route('vehicles') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : '' }}">Vehicles</a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : '' }}"
                                        href="#">Assign</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_product' ? 'btn-info' : '' }}"
                                                href="{{ route('assign_product') }}">Parts</a></li>
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool' ? 'btn-info' : '' }}"
                                                href="{{ route('assign_tool') }}">Tools</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : '' }}"
                                        href="#">Add New</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct' ? 'btn-info' : '' }}"
                                                href="{{ route('product.createproduct') }}">Parts</a></li>
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : '' }}"
                                                href="{{ route('tool.createtool') }}">Tools</a></li>
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'addvehicle' ? 'btn-info' : '' }}"
                                                href="{{ route('addvehicle') }}">Vehicles</a></li>
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
                <label for="category_name" class="form-label"><strong>Category</strong></label>
                <select class="form-select" id="category_name" name="product_category_id">
                    <option value="">All</option>
                    @foreach ($product as $product)
                    <option value="{{ $product->category_name }}">
                        {{ $product->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label for="manufacturer_filter" class="form-label"><strong>Manufacturer</strong></label>
                <select class="form-select" name="manufacturer" id="manufacturer_filter">
                    <option value="">All</option>
                    @foreach ($manufacture as $manufacturer)
                    <option value="{{ $manufacturer->manufacturer_name }}">
                        {{ $manufacturer->manufacturer_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label for="status_filter" class="form-label"><strong>Status</strong></label>
                <select class="form-select" name="status" id="status_filter">
                    <option value="">All</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>


    <div class="row card card-border shadow mr-0">
        <!-- Column -->
        <div class="col-12 card-body table-responsive">
            <table class="table table-striped table-bordered display text-nowrap product-overview" id="zero_config">
                <div class="row withoutthreedottest">
                    <div class="col-md-3 mb-3">
                        <label for="category_name" class="form-label"><strong>Category</strong></label>
                        <select class="form-select" id="category_name1" name="product_category_id">
                            <option value="">All</option>
                            @foreach ($product1 as $product1)
                            <option value="{{ $product1->category_name ?? '' }}">
                                {{ $product1->category_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="manufacturer_filter" class="form-label"><strong>Manufacturer</strong></label>
                        <select class="form-select" name="manufacturer" id="manufacturer_filter1">
                            <option value="">All</option>
                            @foreach ($manufacture as $manufacturer1)
                            <option value="{{ $manufacturer1->manufacturer_name ?? '' }}">
                                {{ $manufacturer1->manufacturer_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status_filter" class="form-label"><strong>Status</strong></label>
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
                        <th>Parts</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <!--  <th>Sold</th>
                                                                                                                                                                    <th>Quantity</th> -->
                        <th>Price</th>
                        <!-- <th>Stock</th> -->
                        <th>status</th>
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
                                    alt="{{ $item->product_name }}" class="rounded-circle" width="45" />
                                @else
                                <img src="{{ asset('public/images/default-part-image.png') }}"
                                    alt="{{ $item->product_name }}" class="rounded-circle" width="45" />
                                @endif


                                <div class="ms-2">
                                    <div class="user-meta-info"><a
                                            href="{{ route('product.edit', ['product_id' => $item->product_id]) }}">
                                            <h6 class="user-name mb-0" data-name="name">
                                                {{ $item->product_name }}</h6>
                                        </a></div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->categoryProduct->category_name ?? null }}</td>
                        <td>{{ $item->manufacturername->manufacturer_name ?? null }}</td>
                        <!-- <td>{{ $item->sold }}</td>
                                                                                                                                                                        <td>{{ $item->stock }}</td> -->
                        <td>${{ $item->base_price ?? '' }}</td>
                        <!--  <td>
                                                                                                                                                                            @if ($item->stock_status == 'in_stock')
    <span class="status_icons status_icon1"><i
                                                                                                                                                                                                                                    class="ri-check-fill"></i></span>
@elseif($item->stock_status == 'out_of_stock')
    <span class="status_icons status_icon2"><i
                                                                                                                                                                                                                                                                                class="ri-close-line"></i></span>
    @endif
                                                                                                                                                                        </td> -->
                        <td>
                            @if ($item->status == 'Publish')
                            Active
                            @elseif($item->status == 'Draft')
                            Inactive
                            @endif
                        </td>
                        <td data-status="{{ $item->status }}">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light-primary text-primary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-settings-3-fill align-middle fs-5"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if ($item->status == 'Publish')
                                    <a class="dropdown-item" href="{{ url('inactive/parts/' . $item->product_id) }}"><i
                                            data-feather="edit-2" class="feather-sm me-2"></i>
                                        Inactive</a>
                                    @else
                                    <a class="dropdown-item" href="{{ url('active/parts/' . $item->product_id) }}"><i
                                            data-feather="edit-2" class="feather-sm me-2"></i>
                                        Active</a>
                                    @endif
                                    <a href="{{ route('product.edit', ['product_id' => $item->product_id]) }}"
                                        class="text-dark pe-2 dropdown-item">
                                        <i class="fa fa-edit edit-sm fill-white pe-2"></i> Edit
                                    </a>
                                    <form method="post"
                                        action="{{ route('product.destroy', ['id' => $item->product_id]) }}">
                                        @csrf
                                        @method('DELETE')

                                        <a href="{{ route('product.destroy', ['id' => $item->product_id]) }}"
                                            class="text-dark dropdown-item">
                                            <i data-feather="trash-2" class="feather-sm fill-white"></i>
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
    <!-- Column -->
</div>
<!-- -------------------------------------------------------------- -->
<!-- End PAge Content -->
<!-- -------------------------------------------------------------- -->


@include('product.scriptIndex')

<script>
    $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#zero_config')) {
                $('#zero_config').DataTable().destroy();
            }

            var table = $('#zero_config').DataTable({
                "dom": '<"top"f>rt<"bottom d-flex justify-content-between mt-4"lp><"clear">',
                "paging": true,
                "info": false,
                "pageLength": 50, // Set default pagination length to 50
                "language": {
                    "search": ""
                }
            });
          });
</script>

<script>
    function filterTable() {
              // Get the search input value
              var input = document.getElementById("searchInput");
              var filter = input.value.toLowerCase();

              // Get the table and rows
              var table = document.getElementById("zero_config");
              var rows = table.getElementsByTagName("tr");

              // Loop through all table rows, and hide those who don't match the search query
              for (var i = 1; i < rows.length; i++) { // Start at 1 to skip table header
                  var row = rows[i];
                  var cells = row.getElementsByTagName("td");
                  var match = false;

                  // Loop through each cell in the row
                  for (var j = 0; j < cells.length; j++) {
                      var cell = cells[j];
                      if (cell) {
                          if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                              match = true;
                              break;
                          }
                      }
                  }

                  // Show or hide the row based on match status
                  if (match) {
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