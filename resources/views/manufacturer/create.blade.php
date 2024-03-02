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
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display: inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->

        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title"> Manufacture </h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Setting</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('manufacturer.index') }}">Manufacturer
                                        List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Manufacture</li>
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
                <div class="col-lg-9 col-md-12">

                    <!-- Card -->
                    <form action="{{ route('manufacture.store') }}" method="post" enctype="multipart/form-data">
                        <div class="card card-body">
                            <h4>Add Manufacture</h4>

                            @csrf
                            <div class="mb-3">
                                <label class="control-label required-field">Manufacturer Name</label>
                                <input type="text" name="manufacturer_name" id="firstName" class="form-control"
                                    placeholder="">
                                <small id="textHelp" class="form-text text-muted"></small>
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-8">
                                    <label class="control-label required-field">Description</label>
                                    <textarea id="text" name="manufacturer_description" class="form-control"
                                        style="height: 180px;">
							</textarea>
                                    <small id="textHelp" class="form-text text-muted"></small>
                                </div>

                            </div>
                            <div class="row justify-content-between">
                                <div class="col-md-4">
                                    <label class="control-label required-field"
                                        style="display: block; font-size: 1rem; margin-bottom: 0.5rem;">Manufacturer
                                        Image</label>
                                    <input type="file" name="manufacture_image" id=""
                                        style="display: block; width: 100%; padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
                                    <small id="textHelp" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <!-- Card -->



                        <div class="mb-3 pb-3" style="margin-top: 1%"><button type="submit" class="
                                                                          justify-content-center
                                                                          btn btn-primary
                                                                          align-items-center
                                                                        " style="margin-left:90%">
                                Submit
                            </button></div>

                    </form>
                </div>
                <!-- Card -->

            </div>
            <!-- column -->



        </div>
        <!-- Row -->




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
    <!-- -------------------------------------------------------------- -->
</div>
@endsection