
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
                   <div class="col-7 text-end px-4">
                 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a href="{{ route('product.index') }}"
                        class="btn {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : 'btn-light-info text-info' }}">Parts</a>
                    <a href="{{ route('tool.index') }}"
                        class="btn {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : 'btn-light-info text-info' }}">Tools</a>
                         <a href="{{ route('vehicles') }}"
                        class="btn {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : 'btn-light-info text-info' }}">Vehicles</a>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button"
                            class="btn {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
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
                            class="btn {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
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
                        class="btn {{ Route::currentRouteName() === 'partCategory' ? 'btn-info' : 'btn-light-info text-info' }}">Categories</a>
                </div>
            </div>        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Container fluid  -->
          @if (Session::has('success'))
                <div class="alert_wrap">
					<div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
					{{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
        <div class="container-fluid mt-3">
            <!-- -------------------------------------------------------------- -->
            <!-- Start Page Content -->
            <!-- -------------------------------------------------------------- -->
            <div class="row">
                <!-- Column -->
                <div class="col-12" style="margin-left:0px; padding-left:0px;">
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
                                        <!-- <div class="col-md-3" >
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
                                        </div> -->
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
                                                                alt="{{ $item->product_name }}" class="rounded-circle"
                                                                width="45" />
                                                        @else
                                                            <img src="{{ asset('public/images/default-part-image.png') }}"
                                                                alt="{{ $item->product_name }}" class="rounded-circle"
                                                                width="45" />
                                                        @endif


                                                        <div class="ms-2">
                                                            <div class="user-meta-info"><a href="{{ route('product.edit', ['product_id' => $item->product_id]) }}">
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
