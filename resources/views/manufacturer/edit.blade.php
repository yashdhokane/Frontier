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
                        <h4 class="page-title">{{ $manufacture->manufacturer_name }}</h4>
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
                        <div class="card card-body card-border shadow">
                             <form action="{{ route('manufacture.update', ['id' => $manufacture->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="control-label bold mb5 required-field">Name</label>
                                    <input type="text" name="manufacturer_name"
                                        value="{{ $manufacture->manufacturer_name }}" id="firstName" class="form-control"
                                        placeholder="">
                                    <small id="textHelp" class="form-text text-muted"></small>
                                </div>

                                <div class="mb-3 justify-content-between">
                                    <div class="col-md-12">
                                        <label class="control-label bold mb5 required-field">Description</label>
                                        <textarea id="text" name="manufacturer_description" class="form-control" style="height: 180px;">{{ $manufacture->manufacturer_description }}
							</textarea>
                                        <small id="textHelp" class="form-text text-muted"></small>
                                    </div>

                                </div>
								
                                <div class="mb-3 justify-content-between">
                                    <div class="col-md-12">
                                        <label class="control-label bold mb5 required-field">Image</label>
                                        <input type="file" class="form-control" value="{{ $manufacture->manufacturer_image }}"
                                            name="manufacturer_image" id="" >
                                        <small id="textHelp" class="form-text text-muted"></small>
                                    </div>
                                </div>
								<div class="mb-3 mt-3 pb-3">
									<button type="submit" class="justify-content-center btn btn-primary align-items-center">Update</button>
								</div>
							
                        </div>
                        <!-- Card -->



                        

                        </form>
                    </div>
                    <!-- Card -->
					
					<div class="col-lg-6 col-md-12">
						<div class="card card-body card-border shadow">
							<div class="row">
								<div class="col-lg-6 col-md-12">
									<div class="mb-3">
										@if ($manufacture->manufacturer_image)
											<img class="img_sq_manufacturer"
												src="{{ asset('public/images/' . $manufacture->manufacturer_image) }}"
												alt="Card image cap" />
										@else
											<img class="img_sq_manufacturer"
												src="{{ asset('public/images/1703141665_heating-air-conditioning.jpg') }}"
												alt="Default Image" />
										@endif
									</div>
								</div>
								<div class="col-lg-12 col-md-12">
									<div class="mb-3">
										<h5 class="card-title uppercase text-info">{{ $manufacture->manufacturer_name }}</h5>
										<p>{{ $manufacture->manufacturer_description }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>

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
