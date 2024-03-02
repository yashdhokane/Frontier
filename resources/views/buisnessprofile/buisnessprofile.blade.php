@extends('home')

@section('content')

<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->


    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Business Profile & Settings</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Business Profile </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">
                    <div class="me-2">
                        <div class="lastmonth"></div>
                    </div>
                    <div class="">
                        <small>LAST MONTH</small>
                        <h4 class="text-info mb-0 font-medium">$58,256</h4>
                    </div>
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

        <div class="row pb-4">
            <div class="col-md-12 col-xl-2">
                <div class="row ">
                    <div class="col-md-9 col-xl-2">
                        <h4 class="card-title">Business information</h4>
                    </div>
                    <div class="col-md-3 col-xl-2 justify-content-md-end">
                        <button type="button" onclick="toggleForm()"
                            class="btn waves-effect waves-light btn-outline-info">EDIT</button>
                    </div>

                    @foreach ($businessProfiles as $businessProfile)
                    <div id="businessInfo">
                        <div class="row pt-2">
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Business name</small>
                                <h6>{{ $businessProfile->business_name }}</h6>
                            </div>
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Address</small>
                                <h6>{{ $businessProfile->address }}</h6>
                            </div>
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Support email</small>
                                <h6>{{ $businessProfile->email }}</h6>
                            </div>
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">License number</small>
                                <h6>{{ $businessProfile->license_number ?? null }}</h6>
                            </div>
                        </div>
                        <div class="row pt-4">
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Business phone</small>
                                <h6>{{ $businessProfile->phone }}</h6>
                            </div>
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Website</small>
                                <h6>{{ $businessProfile->website }}</h6>
                            </div>
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Legal entity name</small>
                                <h6>{{ $businessProfile->legal_name }}</h6>
                            </div>
                            <div class="col-md-3 col-xl-2">
                                <small class="text-muted pt-4 db">Heating & Air Conditioning</small>
                                <h6>{{ $businessProfile->hvac ?? null }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-container" style="display: none;">
                <form action="{{ route('buisnessprofile.update') }}" method="post"
                    style="display: flex; flex-wrap: wrap;" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $businessProfile->id }}" required>
                    <div class="form-group col-md-3">
                        <label for="businessName" class="control-label col-form-label">Business Name</label>
                        <input type="text" id="businessName" value="{{ $businessProfile->business_name }}"
                            name="businessName" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="address" class="control-label col-form-label">Address</label>
                        <input type="text" value="{{ $businessProfile->address }}" id="address" name="address"
                            class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="supportEmail" class="control-label col-form-label">Support Email</label>
                        <input type="email" id="supportEmail" value="{{ $businessProfile->email }}" name="supportEmail"
                            class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="licenseNumber" class="control-label col-form-label">License Number</label>
                        <input type="text" id="licenseNumber" value="{{ $businessProfile->license_number ?? null }}"
                            name="licenseNumber" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="businessPhone" class="control-label col-form-label">Business Phone</label>
                        <input type="text" value="{{ $businessProfile->phone }}" name="phone" class="form-control"
                            required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="website" class="control-label col-form-label">Website</label>
                        <input type="text" id="website" value="{{ $businessProfile->website }}" name="website"
                            class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="legalEntityName" class="control-label col-form-label">Legal Entity Name</label>
                        <input type="text" id="legalEntityName" name="legal_name"
                            value="{{ $businessProfile->legal_name }}" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="heatingAndAirConditioning" class="control-label col-form-label">Heating & Air
                            Conditioning</label>
                        <input type="text" id="heatingAndAirConditioning" name="heatingAndAirConditioning"
                            class="form-control" placeholder="" value="{{ $businessProfile->hvac ?? null }}">
                    </div>

                    <div class="form-group col-md-3" style="margin-top:5px;">
                        <button type="submit"
                            class="btn btn-info rounded-pill px-4 waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>




            <div class="row pt-4 pb-4">
                <div class="col-md-12 col-xl-2">

                    <div class="row ">
                        <div class="col-md-9 col-xl-2">
                            <h4 class="card-title">Company description</h4>
                        </div>
                        <div class="col-md-3 col-xl-2 justify-content-md-end">
                            <button type="button" onclick="companydescription()"
                                class="btn waves-effect waves-light btn-outline-info">EDIT</button>
                        </div>

                        <div>
                            <div id="businessInfo-one">
                                <div class="row pt-2">
                                    <div class="col-md-12 col-xl-2">
                                        <p>{!! $businessProfile->description_long ?? null !!}</p>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div style="display:grid;">

                            <div class="form-group1 col-md-12" style="display:none; padding:7px;">
                                <form action="{{ route('bpcompanydiscription.update') }}" method="post"
                                    style="display: flex; flex-wrap: wrap;" enctype="multipart/form-data"
                                    class="d-block w-75">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $businessProfile->id }}" required>
                                    <textarea id="description" class="form-control" name="description_long"
                                        required> {{ $businessProfile->description_long ?? null }}</textarea>
                                    <div class="form-group " style="margin-top:5px;">
                                        <button type="submit"
                                            class="btn btn-info rounded-pill px-4 waves-effect waves-light">Submit</button>
                                    </div>
                            </div>


                        </div>
                        </form>
                    </div>

                    <div class="row pt-4 pb-4">
                        <div class="col-md-12 col-xl-2">
                            <div class="row ">
                                <div class="col-md-9 col-xl-2">
                                    <h4 class="card-title">Message on invoice, receipt, and estimate</h4>
                                </div>
                                <div class="col-md-3 col-xl-2 justify-content-md-end">
                                    <button type="button" onclick="messageondocs()"
                                        class="btn waves-effect waves-light btn-outline-info">EDIT</button>
                                </div>
                            </div>
                            <div id="businessInfo-two">
                                <div class="row pt-2">
                                    <div class="col-md-12 col-xl-2">
                                        <p>{!! $businessProfile->message_on_docs ?? null !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group2 col-md-12" style="display:none; padding:7px;">
                            <form action="{{ route('moicompanydiscription.update') }}" method="post"
                                style="display: flex; flex-wrap: wrap;" enctype="multipart/form-data"
                                class="d-block w-75">
                                @csrf
                                <input type="hidden" name="id" value="{{ $businessProfile->id }}" required>
                                <textarea id="message_on_docs" class="form-control" name="message_on_docs"
                                    required> {{ $businessProfile->message_on_docs ?? null }}</textarea>

                                <div class="form-group col-md-3" style="margin-top:5px;">
                                    <button type="submit"
                                        class="btn btn-info rounded-pill px-4 waves-effect waves-light">Submit</button>
                                </div>
                        </div>
                        </form>
                    </div>

                    <div class="row pt-4 pb-4">
                        <div class="col-md-12 col-xl-2">


                            <div class="row ">
                                <div class="col-md-9 col-xl-2">
                                    <h4 class="card-title">Terms and conditions</h4>
                                </div>
                                <div class="col-md-3 col-xl-2 justify-content-md-end">
                                    <button type="button" onclick="termsandcondition()"
                                        class="btn waves-effect waves-light btn-outline-info">EDIT</button>
                                </div>
                            </div>
                            <div id="businessInfo-three">
                                <div class="row pt-2">
                                    <div class="col-md-12 col-xl-2">
                                        <p>{!! $businessProfile->terms_condition ?? null !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group3 col-md-12" style="display:none; padding:7px;">
                            <form action="{{ route('taccompanydiscription.update') }}" method="post"
                                style="display: flex; flex-wrap: wrap;" enctype="multipart/form-data"
                                class="d-block w-75">
                                @csrf
                                <input type="hidden" name="id" value="{{ $businessProfile->id }}" required>
                                <textarea id="terms_condition" class="form-control" name="terms_condition"
                                    required> {{ $businessProfile->terms_condition ?? null }}</textarea>

                                <div class="form-group col-md-3" style="margin-top:5px;">
                                    <button type="submit"
                                        class="btn btn-info rounded-pill px-4 waves-effect waves-light">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach









                    <!-- -------------------------------------------------------------- -->
                    <!-- Recent comment and chats -->
                    <!-- -------------------------------------------------------------- -->
                    <div class="row">
                        <!-- column -->
                        <div class="col-lg-6">
                            <br />
                        </div>
                        <!-- column -->
                        <div class="col-lg-6">
                            <br />
                        </div>
                    </div>
                    <!-- -------------------------------------------------------------- -->
                    <!-- Recent comment and chats -->
                    <!-- -------------------------------------------------------------- -->
                </div>
            </div>
        </div>

        <!-- --------------------------------------------------------------- -->

        <script src="{{ asset('public/admin/dist/libs/tinymce/tinymce.min.js') }}"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
              tinymce.init({
                  selector: 'textarea#description, textarea#message_on_docs, textarea#terms_condition'
              });
          });
        </script>

        @stop