<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
<div class="container-fluid">

    <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
        <div class="row">
          
             <div class="col-5 align-self-center">
                    <h4 class="page-title">Add New Vehicles </h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{route('vehicles')}}">Vehicles </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add New Vehicle </li>
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
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <form action="{{ route('fleet.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body card-border shadow">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <label for="vehicle_name"
                                            class="control-label bold col-form-label required-field">Vehicle
                                            Name</label>
                                        <input name="vehicle_name" id="vehicle_name" class="form-control"
                                            required></input>
                                    </div>
                                    <div class="mb-2">
                                        <label for="vehicle_no"
                                            class="control-label bold col-form-label required-field">Vehicle
                                            No.</label>
                                        <input name="vehicle_no" id="vehicle_no" class="form-control" required></input>
                                    </div>
                                    <div class="mb-2">
                                        <label for="vehicle_description"
                                            class="control-label bold col-form-label required-field">Vehicle
                                            Details</label>
                                        <textarea rows="3" name="vehicle_description" id="vehicle_description"
                                            class="form-control" placeholder="Add Vehicle Details" required></textarea>
                                    </div>
                                    {{-- <div class="mb-2">
                                        <label for="vehicle_summary"
                                            class="control-label bold col-form-label required-field">Vehicle
                                            Summary</label>
                                        <textarea rows="3" name="vehicle_summary" id="vehicle_summary"
                                            class="form-control" placeholder="Add complete summary about vehicle"
                                            required></textarea>
                                    </div> --}}

                                    <div class="mb-2">
                                        <label for="technician_id"
                                            class="control-label bold mb5 col-form-label required-field">Select
                                            Technician</label>
                                        <select name="technician_id" id="" class="form-control" required>
                                            <option value="">----- Select Technician -----</option>
                                            @foreach ($user as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="control-label col-form-label required-field bold">Vehicle
                                            Image</label>
                                        <div class="btn waves-effect waves-light">
                                            <input id="file" type="file" onchange="showImagePreview()"
                                                name="vehicle_image" class="upload" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="el-element-overlay">
                                        <div class="el-card-item">
                                            <div class="el-card-avatar el-overlay-1">
                                                <img src="" class="w-50" id="imagePreview" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 mt-4">
                                <button type="submit" class="btn btn-primary"> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
</div>

@include('fleet.script')
@endsection
