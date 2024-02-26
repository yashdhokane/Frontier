@extends('home')
@section('content')
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display: inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Frontier Services Inc</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">Service Area </li>
                            <li class="breadcrumb-item active" aria-current="page">Add Service Area </li>
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

        <!-- -------------------------------------------------------------- -->

        <!-- Start Page Content -->

        <!-- -------------------------------------------------------------- -->

        <!-- 1. card with img -->

        <!-- popup model -->





        <!-- Row -->
        <form action="{{ route('servicearea.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mt-4 mb-3">

               {{--  <div class="col-md-6 mb-3">
                    <div class="mparea">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d774440.6850054913!2d-75.16222184954196!3d40.69249737782041!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1704723153396!5m2!1sen!2sin"
                            width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div> --}}

                <div class="col-md-6 mb-3">


                    <div class="form-group mb-3">
                        <input type="text" name="area_name" class="form-control" id="nametext" aria-describedby="name"
                            placeholder="Name" required>

                    </div>
                    <div class="form-group mb-3">
                        <textarea class="form-control" name="area_description" id="name1"
                            placeholder="Description" required></textarea>

                    </div>
                   <div class="form-group mb-3">
    <select class="form-select" name="area_radius" id="name2" aria-describedby="name" required>
        <option value="1">1KM</option>
        <option value="2">2KM</option>
        <option value="3">3KM</option>
        <option value="4">4KM</option>
        <option value="5">5KM</option>
        <option value="10">10KM</option>
        <option value="20">20KM</option>
        <option value="50">50KM</option>
        <option value="100">100KM</option>
    </select>
</div>

                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="area_latitude" id="name3" aria-describedby="name"
                            placeholder="Latitude" required>

                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="area_longitude" id="name4" aria-describedby="name"
                            placeholder="Longitude"required>

                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info waves-effect" data-bs-dismiss="modal">
                            Save
                        </button>
                      <a href="{{route('servicearea.index')}}">  <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">
                            Cancel
                        </button></a>
                    </div>


                </div>


            </div>
        </form>
    </div>


</div>
@endsection