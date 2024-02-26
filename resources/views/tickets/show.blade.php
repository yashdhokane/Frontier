@extends('home')
@section('content')


<!-- -------------------------------------------------------------- -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- -------------------------------------------------------------- -->
<!-- -------------------------------------------------------------- -->
<!-- Page wrapper  -->
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Calls / Ticket Details</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Calls / Ticket</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Information</li>
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
        <!-- basic table -->
        <div class="row">
            <div class="col-lg-8">

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="card-title">Customer</h4>
                                    <div class="profile-pic mb-3 mt-3">
                                        @isset($technicians->user->user_image)
                                        <img src="{{ asset('public/images/customer/' . $technicians->user->user_image) ?? null }}"
                                            width="150" class="rounded-circle" alt="user" />
                                        @else
                                        <img src="https://gaffis.in/frontier/website/public/admin/assets/images/users/5.jpg"
                                            width="150" class="rounded-circle" alt="user" />
                                        @endisset
                                        <h4 class="mt-3 mb-0"> {{ $technicians->user->name ?? null }}</h4>
                                        <a href="mailto:danielkristeen@gmail.com">{{ $technicians->user->email
                                            ?? null }}</a><br><small class="text-muted">{{ $technicians->user->mobile ??
                                            null }}<br />{{
                                            $technicians->addresscustomer->address_line1 ?? null }}</small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="card-title">Technician</h4>
                                    <div class="profile-pic mb-3 mt-3">
                                        @isset($technicians->usertechnician->user_image)
                                        <img src="{{ asset('public/images/technician/' . $technicians->usertechnician->user_image) ?? null }}"
                                            width="150" class="rounded-circle" alt="user" />
                                        @else
                                        <img src="https://gaffis.in/frontier/website/public/admin/assets/images/users/5.jpg"
                                            width="150" class="rounded-circle" alt="user" />
                                        @endisset
                                        <h4 class="mt-3 mb-0">{{ $technicians->usertechnician->name ?? null }}</h4>
                                        <a href="mailto:danielkristeen@gmail.com">{{ $technicians->usertechnician->email

                                            ?? null }}</a><br><small class="text-muted">{{
                                            $technicians->usertechnician->mobile
                                            ?? null }}<br />{{ $technicians->usertechnician->Locationareaname->area_name
                                            ?? null }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-2">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="card-title">Dispatcher</h4>
                                    <div class="profile-pic mb-3 mt-3">
                                        @isset($technicians->userdispatcher->user_image)
                                        <img src="{{ asset('public/images/dispatcher/' . $technicians->userdispatcher->user_image) ?? null }}"
                                            width="150" class="rounded-circle" alt="user" />
                                        @else
                                        <img src="https://gaffis.in/frontier/website/public/admin/assets/images/users/5.jpg"
                                            width="150" class="rounded-circle" alt="user" />
                                        @endisset
                                        <h4 class="mt-3 mb-0">{{ $technicians->userdispatcher->name ?? null }}</h4>
                                        <a href="mailto:danielkristeen@gmail.com">{{ $technicians->userdispatcher->email

                                            ?? null }}</a><br><small class="text-muted">{{
                                            $technicians->userdispatcher->mobile
                                            ?? null }}<br />Frontier Support Staff</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{$technicians->job_title ?? null }} &nbsp<span
                                class="mb-1 badge bg-primary" style="font-size: 15px;">{{$technicians->job_code
                                ?? null }}</span> </h4>
                        <p>{{$technicians->description ?? null }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2"><strong>Appliances:</strong>
                                    {{$technicians->jobdetailsinfo->apliencename->appliance_name
                                    ?? null }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2"><strong>Manufacturer:</strong>

                                    {{$technicians->jobdetailsinfo->manufacturername->manufacturer_name
                                    ?? null }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2"><strong>Model Number
                                        :</strong>{{$technicians->jobdetailsinfo->model_number?? null }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2"><strong>Serial Number :</strong>
                                    {{$technicians->jobdetailsinfo->serial_number?? null }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">SERVICES & PARTS</h4>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="confirm_job_box">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="mt-0">&nbsp;</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0"><label>Unit Price</label></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0"><label>Discount</label></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0"><label>Tax</label></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0"><label>Total</label></div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="mt-1">
                                                <h6 class="font-weight-medium mb-0">{{
                                                    $technicians->job_code ?? null }} <small class="text-muted">{{
                                                        $technicians->jobserviceinfo->service_name
                                                        ?? null }}</small></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobserviceinfo->base_price
                                                ?? null }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobserviceinfo->discount
                                                ?? null }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobserviceinfo->tax
                                                ?? null }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobserviceinfo->sub_total
                                                ?? null }}</div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="mt-1">
                                                <h6 class="font-weight-medium mb-0">{{
                                                    $technicians->job_code ?? null }} <small class="text-muted">{{
                                                        $technicians->jobproductinfo->product_name
                                                        ?? null }}</small></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobproductinfo->base_price
                                                ?? null }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobproductinfo->discount
                                                ?? null }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobproductinfo->tax
                                                ?? null }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">${{
                                                $technicians->jobproductinfo->sub_total
                                                ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="row" style="border-top: 2px dotted #343434">
                                        <div class="col-md-4">&nbsp;</div>
                                        <div class="col-md-2">
                                            <div class="mt-2">&nbsp;</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-2">
                                                <h5>${{
                                                    $technicians->discount
                                                    ?? null }} </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-2">
                                                <h5>${{
                                                    $technicians->tax
                                                    ?? null }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-2">
                                                <h5>${{
                                                    $technicians->gross_total
                                                    ?? null }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>





                <!-- ---------------------
                            end technicians
                        ---------------- -->
                <!-- ---------------------
                            start Ticket Replies
                        ---------------- -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">TECHNICIAN NOTES</h4>
                        @foreach ($techniciansnotes as $item)
                        <ul class="list-unstyled mt-5">
                            <li class="d-flex align-items-start">
                                @isset($item->user_image)
                                <img src="{{ asset('public/images/technician/' . $item->user_image) ?? asset('public/images/default_user_image.jpg') }}"
                                    width="60" class="me-3 rounded" alt="user" />
                                @else
                                <img src="https://gaffis.in/frontier/website/public/admin/assets/images/users/5.jpg"
                                    width="60" class="me-3 rounded" alt="user" />
                                @endisset

                                <div class="media-body">
                                    <h5 class="mt-0 mb-1">{{ $item->name ?? 'Unknown' }}</h5>
                                    {{ $item->note ?? 'No note available' }}
                                </div>
                            </li>
                            <hr />
                        </ul>
                        @endforeach

                    </div>
                </div>
                <!-- ---------------------
                            end Ticket Replies
                        ---------------- -->
                <!-- ---------------------
                            start Write a reply
                        ---------------- -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Write a reply</h4>
                        <form method="post">
                            <textarea id="mymce" name="area"></textarea>

                            <button type="button" class="mt-3 btn waves-effect waves-light btn-success">
                                Reply
                            </button>
                            <button type="button" class="mt-3 btn waves-effect waves-light btn-info">
                                Reply & close
                            </button>
                        </form>
                    </div>
                </div>
                <!-- ---------------------
                            end Write a reply
                        ---------------- -->
            </div>

            <div class="col-lg-4">

                <div class="card">
                    <div class="card-body bg-light">
                        <div class="row text-center">
                            <div class="col-6 my-2 text-start">
                                <span class="badge bg-warning"
                                    style="font-size: 18px;font-weight: bold;">{{$technicians->status?? null }}</span>
                            </div>
                            <div class="col-6 my-2">&nbsp;</div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <h4 class="card-title mb-0"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp; {{
                                $technicians->created_at ? $technicians->created_at->format('F d,
                                Y H:i') : '-' ?? null }}</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Duration:</strong> {{ $technicians->jobassignname->duration ??
                                    null }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Priority:</strong> {{$technicians->priority?? null }} </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Tags:</strong> {{$technicians->tag_ids?? null }} </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ADDRESS</h4>
                        <div class="">
                            <h5 class="todo-desc mb-0 fs-3 font-weight-medium">{{$technicians->address?? null }}
                            </h5>
                            <iframe id="map" width="100%" height="150" frameborder="0" style="border: 0"
                                allowfullscreen></iframe>
                            <h5 class="todo-desc mb-0 fs-3 font-weight-medium">Service Area: {{
                                $technicians->serviceareaname->area_name
                                ?? null }}</small> </h5>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ACTIVITY</h4>
                        <div class="todo-widget">
                            <ul class="list-group mb-0">
                                <li class="list-group-item border-0 mb-0 py-2 pe-3 ps-0">
                                    <h5 class="todo-desc mb-0 fs-3 font-weight-medium">New Job created and assigned to
                                        technician</h5>
                                    <div class="todo-desc text-muted fw-normal fs-2"></div>
                                </li>
                                <li class="list-group-item border-0 mb-0 py-2 pe-3 ps-0">
                                    <h5 class="todo-desc mb-0 fs-3 font-weight-medium">Technician accepted the job</h5>
                                    <div class="todo-desc text-muted fw-normal fs-2"></div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="mt-4">Technician Assigned</h5>
                        <span>{{ $technicians->jobassignname->technician->name ?? null }}</span>
                        <h5 class="pt-3">Ticket Creator</h5>
                        <span>{{ $technicians->userdispatcher->name ?? null }} @Frontier Support Staff</span>
                        <h5 class="pt-3">Ticket created on </h5>
                        <span>02-20-2024</span>
                        <h5 class="pt-3">Ticket Last Modified on </h5>
                       <span>02-20-2024</span>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>
</div>

@section('script')


<!-- This page JavaScript -->
<!-- --------------------------------------------------------------- -->
<script src="https://gaffis.in/frontier/website/public/admin/dist/libs/tinymce/tinymce.min.js"></script>
<!--c3 charts -->
<script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/d3-3.5.6.js"></script>


<script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/c3-0.4.9.min.js"></script>
<script>
    $(function () {
        tinymce.init({
  selector: 'textarea#mymce'
});
        // ==============================================================
        // Our Visitor
        // ==============================================================

        var chart = c3.generate({
          bindto: '#visitor',
          data: {
            columns: [
              ['Open', 4],
              ['Closed', 2],
              ['In progress', 2],
              ['Other', 0],
            ],

            type: 'donut',
            tooltip: {
              show: true,
            },
          },
          donut: {
            label: {
              show: false,
            },
            title: 'Tickets',
            width: 35,
          },

          legend: {
            hide: true,
            //or hide: 'data1'
            //or hide: ['data1', 'data2']
          },
          color: {
            pattern: ['#40c4ff', '#2961ff', '#ff821c', '#7e74fb'],
          },
        });
      });
</script>
<script>
    // Get latitude and longitude values from your data or variables
        var latitude ={{$technicians->latitude?? null }}; // Example latitude
        var longitude ={{$technicians->longitude?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' + latitude + ',' + longitude + '&zoom=13';

        document.getElementById('map').src = mapUrl;
</script>
@endsection
@endsection