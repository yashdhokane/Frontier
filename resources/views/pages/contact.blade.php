@extends('home')
@section('content')

        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Contact Us</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
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
            <!-- -------------------------------------------------------------- -->
            <!-- Start Page Content -->
            <!-- -------------------------------------------------------------- -->
            <div class="row">
                <div class="col-lg-9">
                    <!-- ----------------------------------------- -->
                    <!-- 1. Basic Form -->
                    <!-- ----------------------------------------- -->
                    <!-- ---------------------
                                    start Basic Form
                                ---------------- -->
                    <div class="card">
                        <div class="card-body card-border shadow">												    <h5 class="card-title uppercase mb-3">Contact Us</h5>													    <div class="row">								<div class="col-md-4 text-center">									<i class="ri-phone-line display-1"></i>									<h3>Phone</h3>									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>								</div>								<div class="col-md-4 text-center">									<i class="ri-mail-line display-1"></i>									<h3>Email</h3>									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>								</div>								<div class="col-md-4 text-center">									<i class="ri-map-pin-line display-1"></i>									<h3>Address</h3>									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>								</div>															</div>														<h5 class="card-title uppercase mb-3 mt-4">Frequently Asked Questions</h5>													    <div class="accordion" id="accordionExample">								<div class="accordion-item">								  <h2 class="accordion-header" id="headingOne">									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">									  Accordion Item #1									</button>								  </h2>								  <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">									<div class="accordion-body">									  <strong>This is the first item's accordion body.</strong>									  It is hidden by default, until the collapse plugin									  adds the appropriate classes that we use to style each									  element. These classes control the overall appearance,									  as well as the showing and hiding via CSS transitions.									  You can modify any of this with custom CSS or									  overriding our default variables. It's also worth									  noting that just about any HTML can go within the									  <code>.accordion-body</code>, though the transition									  does limit overflow.									</div>								  </div>								</div>								<div class="accordion-item">								  <h2 class="accordion-header" id="headingTwo">									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">									  Accordion Item #2									</button>								  </h2>								  <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">									<div class="accordion-body">									  <strong>This is the second item's accordion body.</strong>									  It is hidden by default, until the collapse plugin									  adds the appropriate classes that we use to style each									  element. These classes control the overall appearance,									  as well as the showing and hiding via CSS transitions.									  You can modify any of this with custom CSS or									  overriding our default variables. It's also worth									  noting that just about any HTML can go within the									  <code>.accordion-body</code>, though the transition									  does limit overflow.									</div>								  </div>								</div>								<div class="accordion-item">								  <h2 class="accordion-header" id="headingThree">									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">									  Accordion Item #3									</button>								  </h2>								  <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">									<div class="accordion-body">									  <strong>This is the third item's accordion body.</strong>									  It is hidden by default, until the collapse plugin									  adds the appropriate classes that we use to style each									  element. These classes control the overall appearance,									  as well as the showing and hiding via CSS transitions.									  You can modify any of this with custom CSS or									  overriding our default variables. It's also worth									  noting that just about any HTML can go within the									  <code>.accordion-body</code>, though the transition									  does limit overflow.									</div>								  </div>								</div>							 </div>							 							<h5 class="card-title uppercase mb-3 mt-5">About Dispatch Channel</h5>													    <div class="row">								<div class="col-lg-6 col-md-6 col-xl-2">								  <!-- Card -->								  <div class="card">									<img class="card-img-top img-responsive" src="https://dispatchannel.com/portal/public/images/users/1/image-11.png" alt="Card image cap">									<div class="card-body">									  <p class="card-text">										Some quick example text to build on the card title and make										up the bulk of the card's content.									  </p>									</div>								  </div>								  <!-- Card -->								</div>																<div class="col-lg-6 col-md-6 col-xl-2">								  <!-- Card -->								  <div class="card">									<img class="card-img-top img-responsive" src="https://dispatchannel.com/portal/public/images/users/1/image-11.png" alt="Card image cap">									<div class="card-body">									  <p class="card-text">										Some quick example text to build on the card title and make										up the bulk of the card's content.									  </p>									</div>								  </div>								  <!-- Card -->								</div>															</div>
                            <h5 class="card-title uppercase mb-3">Contact Support</h5>							
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="tb-fname"
                                                placeholder="Subject" />
                                            <label for="tb-fname">Subject</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="tb-message"
                                                placeholder="name@example.com" />
                                            <label for="tb-message">Message</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-md-flex align-items-center mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault" />
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Remember me
                                                </label>
                                            </div>
                                            <div class="ms-auto mt-3 mt-md-0">
                                                <button type="submit" class="btn btn-info font-medium rounded-pill px-4">
                                                    <div class="d-flex align-items-center">
                                                        <i data-feather="send" class="feather-sm fill-white me-2"></i>
                                                        Submit
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>								<div class="col-lg-3">				    <div class="card card-border shadow">						<div class="card-body">							<h5 class="card-title uppercase">Help &amp; Support</h5>							<ul class="list-group list-group-flush">								<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Contact Support </a></li>								<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">View Website </a></li>								<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Download App </a></li>								<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Privacy Policy </a></li>								<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Documentation </a></li>							</ul>						</div>					</div>				</div>
                <!-- -------------------------------------------------------------- -->
                <!-- End Page wrapper  -->
                <!-- -------------------------------------------------------------- -->
            </div>
        </div>
   




@section('script')
@endsection
@endsection
