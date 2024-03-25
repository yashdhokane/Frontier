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
                <h4 class="page-title">{{ \App\Models\ProductCategory::find($product_id)->category_name ?? null }}

                </h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('product.index')}}">Price Book</a></li>
                            <li class="breadcrumb-item"><a href="{{route('product.index')}}">Parts </a></li>
                            @if($product_id)
                            <li class="breadcrumb-item">
                                {{ \App\Models\ProductCategory::find($product_id)->category_name ?? null }}
                            </li>
                            @else
                            <li class="breadcrumb-item"><a href="{{ url('services-category.html') }}">Category</a></li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">

                    <a href="{{ route('product.createproduct') }}" id="btn-add-contact" class="btn btn-info">
                        <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                        Add New Parts</a>

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
                                            <label for="service" class="control-label col-form-label required-field">Category</label>
                                            <select class="form-select me-sm-2" id="service" name="product_category_id"
                                                required>
                                                <option selected disabled value="">Select Part Category...</option>
                                                @foreach($product as $product)
                                                <option value="{{ $product->id }}">{{
                                                    $product->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
 									<div class="col-md-3">
                                        <div class="mb-3">
 											<label for="service" class="control-label col-form-label required-field">Manufacturer</label>
                                            <select class="form-select" name="manufacturer" id="manufacturer"
                                                data-placeholder="Choose a Manufacturer" tabindex="1">
                                                @foreach($manufacture as $manufacturer)
                                                <option value="{{ $manufacturer->id }}">{{
                                                    $manufacturer->manufacturer_name }}
                                                </option>
                                                @endforeach
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
												
												@if($item->product_image)
												<img src="{{ asset('public/product_image/' . $item->product_image) }}"
													alt="{{ $item->product_name }}" class="rounded-circle" width="45" />
												@else
												<img src="{{ asset('public/images/1.png') }}"
													alt="{{ $item->product_name }}" class="rounded-circle" width="45" />
												@endif
												
												
												<div class="ms-2">
												<div class="user-meta-info"><a href="#."><h6 class="user-name mb-0" data-name="name"> {{ $item->product_name }}</h6></a></div>
												</div>
											</div>
										</td>
										<td>Category here</td>
										<td>{{$item->manufacturername->manufacturer_name ?? null}}</td> 
										<td>{{ $item->sold }}</td>
										<td>{{ $item->stock }}</td>
										<td>{{ $item->base_price }}</td>
                                         <td><span class="badge bg-danger font-weight-100">{{ $item->status }}</span>
                                        </td>

                                        <td>
                                            <div style="display:flex;">
                                                <div>
                                                    <a href="{{ route('product.edit',['product_id' => $item->product_id]) }}"
                                                        class="text-dark pe-2">
                                                        <i data-feather="edit-2" class="feather-sm fill-white"></i>
                                                    </a>
                                                </div>
                                                <div>
                                                    <form method="post"
                                                        action="{{ route('product.destroy', ['id' => $item->product_id]) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <a href="{{ route('product.destroy', ['id' => $item->product_id]) }}"
                                                            class="text-dark">
                                                            <i data-feather="trash-2" class="feather-sm fill-white"></i>
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


@section('script')
<script>
    $(document).ready(function() {
        // Function to handle dropdown change event
        $('#service, #manufacturer').change(function() {
            var category_id = $('#service').val();
            var manufacturer_id = $('#manufacturer').val();

            // Send AJAX request
            $.ajax({
               '{{ route('productsaxaclist') }}',// Replace with your route
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
@endsection