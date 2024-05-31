<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container-fluid">

        <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Add New Vehicles</h4>
                </div>
                <div class="col-7 text-end">
                    <a href="https://dispatchannel.com/portal/vehicles"
                        class="justify-content-center d-flex align-items-center"><i class="ri-truck-line"
                            style="margin-right: 8px;"></i> Back to Vehicles List </a>
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
                    <div class="card-body card-border shadow">

                        <form action="{{ route('fleet.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <label for="vehicle_description"
                                            class="control-label bold col-form-label required-field">Vehicle Details</label>
                                        <textarea rows="3" name="vehicle_description" id="vehicle_description" class="form-control"
                                            placeholder="Add Vehicle Details" required></textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label for="vehicle_summary"
                                            class="control-label bold col-form-label required-field">Vehicle Summary</label>
                                        <textarea rows="3" name="vehicle_summary" id="vehicle_summary" class="form-control"
                                            placeholder="Add complete summary about vehicle" required></textarea>
                                    </div>
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
                                    <div class="mb-3 mt-4">
                                        <button type="submit" class="btn btn-primary"> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('fleet.script')
@endsection
