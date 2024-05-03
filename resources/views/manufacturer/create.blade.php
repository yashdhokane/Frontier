@extends('home')
@section('content')
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
    <!-- -------------------------------------------------------------- -->
    
            <!-- -------------------------------------------------------------- -->
            <!-- Bread crumb and right sidebar toggle -->

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"> Add Manufacture </h4>
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
                    <div class="col-lg-6 col-md-12">

                        <!-- Card -->
                        <form action="{{ route('manufacture.store') }}" method="post" enctype="multipart/form-data">
                            <div class="card card-body card-border shadow">
 
                                @csrf
                                <div class="mb-3">
                                    <label class="control-label bold mb5 required-field">Name</label>
                                    <input type="text" name="manufacturer_name" id="firstName" class="form-control"
                                        placeholder="">
                                    <small id="textHelp" class="form-text text-muted"></small>
                                </div>

                                <div class="mb-3 justify-content-between">
                                         <label class="control-label bold mb5 required-field">Description</label>
                                        <textarea id="text" name="manufacturer_description" class="form-control" style="height: 180px;">
							</textarea>
                                        <small id="textHelp" class="form-text text-muted"></small>
								</div>
								
                                <div class="mb-3 justify-content-between">
                                         <label class="control-label bold mb5 required-field"
                                            style="display: block; font-size: 1rem; margin-bottom: 0.5rem;">Image</label>
                                        <input type="file" class="form-control" name="manufacture_image" id="" >
                                        <small id="textHelp" class="form-text text-muted"></small>
                                 </div>
                            </div>
                            <!-- Card -->



                            <div class="mb-3 pb-3" style="margin-top: 1%"><button type="submit"
                                    class="
                                                                          justify-content-center
                                                                          btn btn-primary
                                                                          align-items-center
                                                                        "
                                    style="margin-left:90%">
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
      
  
@endsection
