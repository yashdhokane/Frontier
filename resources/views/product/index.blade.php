@extends('home')
@section('content')
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Parts and Accessories</h4>
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
                        <div class="card-body card-border shadow">
                            <div class="table-responsive">

                                <table class="table product-overview" id="zero_config">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="service"
                                                    class="control-label bold md5 col-form-label required-field">Category</label>
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
                                                    class="control-label bold md5  col-form-label required-field">Manufacturer</label>
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
                                                    class="control-label bold md5  col-form-label required-field">Stock</label>
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
                                                    class="control-label bold md5  col-form-label required-field">Status</label>
                                                <select class="form-select" name="manufacturer" id="status_filter"
                                                    data-placeholder="Choose a Manufacturer" tabindex="1">
                                                    <option value="">All</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
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
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Stock</th>
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
                                                                alt="{{ $item->product_name }}" class="rounded-circle"
                                                                width="45" />
                                                        @else
                                                            <img src="{{ asset('public/images/default-part-image.png') }}"
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
                                                <td>${{ $item->total }}</td>
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
                                                                    href="{{ url('inactive/parts/' . $item->product_id) }}"><i
                                                                        data-feather="edit-2" class="feather-sm me-2"></i>
                                                                    Inactive</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ url('active/parts/' . $item->product_id) }}"><i
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
                                                                    <i data-feather="trash-2"
                                                                        class="feather-sm fill-white"></i> Delete
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

    @include('product.scriptIndex')

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
@endsection
