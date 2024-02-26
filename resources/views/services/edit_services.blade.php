@extends('home')
@section('content')
<style>
.required-field::after {
    content: " *";
    color: red;
}
</style>
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display: inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Edit Services</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('services.index')}}">Price Book</a></li>
                            <li class="breadcrumb-item"><a href="{{route('services.listingServices')}}">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Services</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->

    <div class="container-fluid">

        <!-- Row -->
        <div class="row">

            <!-- column -->
            <div class="col-lg-8 col-md-8">

                <!-- Card -->
                <div class="card card-body">
                    <form action="{{ route('services.update', $service->service_id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}

                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="control-label required-field">Name</label>
                            <input type="text" name="service_name"
                                value="{{ old('service_name', $service->service_name) }}" id="firstName"
                                class="form-control" placeholder="Custom Job" required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label required-field">Description</label>
                            <textarea id="text" name="service_description" class="form-control" style="height: 120px;"
                                required>{{ old('service_description', $service->service_description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="control-label required-field">Service Code</label>
                            <input type="text" name="service_code"
                                value="{{ old('service_code', $service->service_code) }}" id="task" class="form-control"
                                placeholder="Task Code" required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label required-field">Featured Image</label>
                            <input type="file" value="{{ old('service_image', $service->service_image) }}"
                                name="service_image" id="image" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="service" class="control-label required-field col-form-label">Service Category</label>
                            <select class="form-select me-sm-2" id="service" name="service_category_id" required>
                                <option selected disabled value="">Select Service...</option>
                                @foreach($serviceCategories as $serviceOption)
                                {{-- <option value="{{ $serviceOption->id }}" {{ $service->servicecategoryid &&
                                    $serviceOption->id ==
                                    $service->servicecategoryid->id ? 'selected' : '' }}> --}}
                                <option value="{{ $serviceOption->id }}" @if($service->service_category_id
                                    ==$serviceOption->id ) selected @endif>
                                    {{ $serviceOption->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
    <label for="manufacturer" class="control-label col-form-label required-field">Manufacturer</label>
    <select class="form-select me-sm-2" id="manufacturer" name="manufacturer_ids[]" multiple required>
        <option selected disabled value="">Select Manufacturers...</option>
        @foreach($manufacturers as $manufacturer)
            <option value="{{ $manufacturer->id }}">{{ $manufacturer->manufacturer_name }}</option>
        @endforeach
    </select>
</div>


                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card card-body">
                    <h4 class="required-field">BOOKING</h4>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="service_online" type="checkbox" value="yes"
                                id="flexSwitchCheckChecked" {{ $service->service_online == 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckChecked"> Show this service in Online
                                Booking</label>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>Duration</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label required-field">Hours</label>
                                <select class="form-control form-select" name="hours" data-placeholder="Choose hours"
                                    tabindex="1" required>
                                    <option value="1" {{ old('hours', $hours)==1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('hours', $hours)==2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('hours', $hours)==3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('hours', $hours)==4 ? 'selected' : '' }}>4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h6 style="height: 17px;"></h6>
                            <div class="mb-3">
                                <select class="form-control form-select" name="minutes"
                                    data-placeholder="Choose minutes" tabindex="1" required>
                                    <option value="0" {{ old('minutes', $minutes)==0 ? 'selected' : '' }}>0</option>
                                    <option value="15" {{ old('minutes', $minutes)==15 ? 'selected' : '' }}>15</option>
                                    <option value="30" {{ old('minutes', $minutes)==30 ? 'selected' : '' }}>30</option>
                                    <option value="45" {{ old('minutes', $minutes)==45 ? 'selected' : '' }}>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">&nbsp;</div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="required-field">Book this service as:</h6>
                            <div class="form-check">
                                <input type="radio" id="customRadio11" name="service_for" value="Job"
                                    class="form-check-input" {{ old('service_for', $service->service_for) == 'Job' ?
                                'checked' : '' }} required>
                                <label class="form-check-label" for="customRadio11"
                                    style="margin-right:40px;">Job</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="customRadio1" name="service_for" value="Estimate"
                                    class="form-check-input" {{ old('service_for', $service->service_for) == 'Estimate'
                                ? 'checked' : '' }} required>
                                <label class="form-check-label" for="customRadio11">Estimate</label>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Card -->

                <!-- Card -->
                <div class="card card-body">
                    <h4>TROUBLESHOOTING</h4>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="control-label required-field">Troubleshooting Questions</label>
                            <input type="text" name="troubleshooting_question1"
                                value="{{ old('troubleshooting_question1', $service->troubleshooting_question1) }}"
                                id="task" class="form-control" placeholder="Troubleshooting Question" required />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label required-field">Additional Troubleshooting Questions</label>
                            <input type="text" name="troubleshooting_question2"
                                value="{{ old('troubleshooting_question2', $service->troubleshooting_question2) }}"
                                id="task" class="form-control" placeholder="Additional Troubleshooting Questions"
                                required />
                        </div>
                    </div>

                </div>

            </div>

            <!-- column -->

            <!-- column -->
            <div class="col-lg-4 col-md-4">

                <!-- Card -->
                <div class="card card-body">
                    <h4>Pricing</h4>

                    <div class="mb-3">
                        <label class="control-label required-field">Cost (Base Price)</label>
                        <input type="number" id="service" class="form-control"
                            value="{{ old('service_cost', $service->service_cost) }}" name="service_cost"
                            placeholder="" required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label required-field">Quantity</label>
                        <input type="text" id="service" name="service_quantity"
                            value="{{ old('service_quantity', $service->service_quantity) }}" class="form-control"
                            placeholder="" required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label required-field">Discount</label>
                        <input type="text" id="service" name="service_discount"
                            value="{{ old('service_discount', $service->service_discount) }}" class="form-control"
                            placeholder="" required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label required-field">Tax</label>
                        <input type="text" id="service" name="service_tax"
                            value="{{ old('service_tax', $service->service_tax) }}" class="form-control"
                            placeholder="" required>
                    </div>
                    <div class="mb-3">
                        <label class="control-label required-field">Total</label>
                        <input type="text" id="service" name="service_total"
                            value="{{ old('service_total', $service->service_total) }}" class="form-control"
                            placeholder="" required>
                    </div>

                </div>

            </div>
        </div>
        <!-- column -->
        <div class="col-md-2" style="margin-top:5px; margin-left:290px;">
            <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
    <!-- Row -->

    <div class="row">
        <div class="col-lg-6">
            <br />
        </div>
        <div class="col-lg-6">
            <br />
        </div>
    </div>

</div>
</div>

</div>
@stop
<!-- -------------------------------------------------------------- -->