@extends('home')
@section('content')
   <!-- -------------------------------------------------------------- -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- -------------------------------------------------------------- -->
    <div id="main-wrapper">
        <!-- Page wrapper  -->
        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
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
            </div>
                
             
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
                                                    <label for="technician"
                                                        class="control-label bold mb5">Technician</label>
                                                    <select class="select2-with-menu-bg form-control" name="technician_id[]"
                                                        id="menu-bg-multiple2" multiple="multiple" data-bgcolor="light"
                                                        data-bgcolor-variation="accent-3" data-text-color="blue"
                                                        style="width: 100%" required>
                                                        @foreach ($technician as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="parts" class="control-label bold mb5">Parts</label>
                                                    <select class="select2-with-menu-bg form-control" name="product_id[]"
                                                        id="menu-bg-multiple" multiple="multiple" data-bgcolor="light"
                                                        data-bgcolor-variation="accent-3" data-text-color="blue"
                                                        style="width: 100%" required>
                                                        @foreach ($product as $item)
                                                            <option value="{{ $item->product_id }}">
                                                                {{ $item->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="parts" class="control-label bold mb5">Quantity</label>
                                                   <input type="number" class="form-control" name="quantity" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn waves-effect waves-light btn-primary"
                                                        style="margin-top: 25px;margin-left: 20px;width: 140px;">Assign</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($technician as $item)
                            <div class="col-md-3">
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
                                                            $assignedProducts[$assignment->product_id] = $assignment->quantity;
                                                        @endphp
                                                    @else
                                                        @php
                                                            $assignedProducts[$assignment->product_id] += $assignment->quantity;
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
                                                                <i class="text-danger fas fa-exclamation-triangle mx-2"></i>
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
    <!-- -------------------------------------------------------------- -->
    <!-- End Wrapper -->
    <!-- -------------------------------------------------------------- -->

@endsection
