@extends('home')
@section('content')
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">

                    </h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Price Book</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Parts </a></li>

                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-7 text-end">
                    <a href="{{ route('product.createproduct') }}" id="btn-show-categories" class="btn btn-primary mx-3"><i
                            class="fas fa-plus "></i> New Part</a>
                    <a href="{{ route('partCategory') }}" id="btn-show-categories" class="btn btn-info"><i
                            class="fas fa-eye"></i> Part Categories</a>

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
            <div class="row">
                <!-- Column -->
                <div class="col-lg-12">
                    <!-- ---------------------
                                                start Product Orders
                                            ---------------- -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table product-overview" id="zero_config">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="service"
                                                    class="control-label col-form-label required-field">Category</label>
                                                <select class="form-select me-sm-2" id="category_name"
                                                    name="product_category_id" required>
                                                    <option value="">All
                                                    </option>
                                                    @foreach ($product as $product)
                                                        <option value="{{ $product->category_name }}">
                                                            {{ $product->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="service"
                                                    class="control-label col-form-label required-field">Manufacturer</label>
                                                <select class="form-select" name="manufacturer" id="manufacturer_filter"
                                                    data-placeholder="Choose a Manufacturer" tabindex="1">
                                                    <option value="">All</option>
                                                    @foreach ($manufacture as $manufacturer)
                                                        <option value="{{ $manufacturer->manufacturer_name }}">
                                                            {{ $manufacturer->manufacturer_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="service"
                                                    class="control-label col-form-label required-field">Stock</label>
                                                <select class="form-select" name="manufacturer" id="stock_filter"
                                                    data-placeholder="Choose a Manufacturer" tabindex="1">
                                                    <option value="">All</option>
                                                    <option value="in_stock">In Stock</option>
                                                    <option value="out_of_stock">Out of stock</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="service"
                                                    class="control-label col-form-label required-field">Status</label>
                                                <select class="form-select" name="manufacturer" id="status_filter"
                                                    data-placeholder="Choose a Manufacturer" tabindex="1">
                                                    <option value="">All</option>
                                                    <option value="Publish">Active</option>
                                                    <option value="Draft">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Parts</th>
                                            <th>Category</th>
                                            <th>Manufacturer</th>
                                            <th>Sold</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Stock Status</th>
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
                                                            <img src="{{ asset('public/images/1.png') }}"
                                                                alt="{{ $item->product_name }}" class="rounded-circle"
                                                                width="45" />
                                                        @endif


                                                        <div class="ms-2">
                                                            <div class="user-meta-info"><a href="#.">
                                                                    <h6 class="user-name mb-0" data-name="name">
                                                                        {{ $item->product_name }}</h6>
                                                                </a></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->categoryProduct->category_name ?? null }}</td>
                                                <td>{{ $item->manufacturername->manufacturer_name ?? null }}</td>
                                                <td>{{ $item->sold }}</td>
                                                <td>{{ $item->stock }}</td>
                                                <td>{{ $item->base_price }}</td>
                                                <td>{{ $item->stock_status }}</td>
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
                                                                    href="{{ url('inactive/parts/' . $item->product_id) }}"><i
                                                                        data-feather="edit-2" class="feather-sm me-2"></i>
                                                                    Inactive</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ url('active/parts/' . $item->product_id) }}"><i
                                                                        data-feather="edit-2" class="feather-sm me-2"></i>
                                                                    Active</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div style="display:flex;">
                                                        <div>
                                                            <a href="{{ route('product.edit', ['product_id' => $item->product_id]) }}"
                                                                class="text-dark pe-2">
                                                                <i data-feather="edit-2"
                                                                    class="feather-sm fill-white"></i>
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <form method="post"
                                                                action="{{ route('product.destroy', ['id' => $item->product_id]) }}">
                                                                @csrf
                                                                @method('DELETE')

                                                                <a href="{{ route('product.destroy', ['id' => $item->product_id]) }}"
                                                                    class="text-dark">
                                                                    <i data-feather="trash-2"
                                                                        class="feather-sm fill-white"></i>
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



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category_name').on('change', function() {
                var selectedStatus = $(this).val();
                console.log(selectedStatus);
                if (selectedStatus) {
                    $('#zero_config').DataTable().column(2).search('^' + selectedStatus + '$', true, false)
                        .draw();
                } else {
                    // If no status is selected, clear the filter
                    $('#zero_config').DataTable().column(2).search('').draw();
                }
            });

            $('#manufacturer_filter').on('change', function() {
                var selectedStatus = $(this).val();
                console.log(selectedStatus);
                if (selectedStatus) {
                    $('#zero_config').DataTable().column(3).search('^' + selectedStatus + '$', true, false)
                        .draw();
                } else {
                    // If no status is selected, clear the filter
                    $('#zero_config').DataTable().column(3).search('').draw();
                }
            });

            $('#stock_filter').on('change', function() {
                var selectedStatus = $(this).val();
                console.log(selectedStatus);
                if (selectedStatus) {
                    $('#zero_config').DataTable().column(7).search('^' + selectedStatus + '$', true, false)
                        .draw();
                } else {
                    // If no status is selected, clear the filter
                    $('#zero_config').DataTable().column(7).search('').draw();
                }
            });

            $('#status_filter').on('change', function() {
                var selectedStatus = $(this).val();
                var table = $('#zero_config').DataTable();
                var columnIndex = 8; // Change this to the index of your target column

                table.column(columnIndex).nodes().each(function(cell, index) {
                    var cellStatus = $(cell).data('status');
                    console.log('Cell Status:', cellStatus); // Check cell status
                    if (selectedStatus === 'all' || cellStatus === selectedStatus) {
                         table.column(8).search('^' + selectedStatus === cellStatus+ '$', true, false).draw();
                    } else {
                        table.column(8).search('').draw();
                    }
                });

            });







            // Function to handle dropdown change event
            $('#service, #manufacturer').change(function() {
                var category_id = $('#service').val();
                var manufacturer_id = $('#manufacturer').val();

                // Send AJAX request
                $.ajax({
                    url: '{{ route('productsaxaclist') }}', // Replace with your route
                    method: 'GET',
                    data: {
                        category_id: product_category_id,
                        manufacturer_id: product_manu_id
                    },
                    success: function(response) {
                        // Update the table with the response data
                        $('.product-table').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });


        });
    </script>
@endsection
