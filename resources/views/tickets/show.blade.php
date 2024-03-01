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
    <!-- ---------------------------------------------------
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
    </div> ----------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
                           <div style="display: flex; justify-content: space-between;">
    <div style="margin-Left:25px;">
       {{$technicians->job_title ?? null }}
         <a href="#" class="fw-bold link"><span class="mb-1 badge bg-primary">{{
                                                    $ticket->job_code }}</span></a>
    </div>
   <div style="margin-right: 20px;">
    <?php
    $payment = App\Models\Payment::where('job_id', $ticket->id)->first();
    ?>
    @if($payment)
    <a href="{{ route('invoicedetail', ['id' => $payment->id]) }}" class="btn waves-effect waves-light btn-rounded btn-warning">
        <i class="fa fa-paper-plane" aria-hidden="true"></i> View Invoice
    </a>
    @else
    <!-- Handle case when payment is not found -->
    <span>No payment found</span>
    @endif
</div>


</div>


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
                                        <img src="{{asset('public/admin/assets/images/users/default_image.jpg')}}"
                                            width="150" class="rounded-circle" alt="user" />
                                        @endisset
                                        <h4 class="mt-3 mb-0"> {{ $technicians->user->name ?? null }}</h4>
                                        <a href="mailto:{{ $technicians->user->email ?? '' }}">{{ $technicians->user->email
                                            ?? null }}</a><br><small class="text-muted">{{ $technicians->user->mobile ??
                                            null }}<br />{{$technicians->address?? null }},{{$technicians->city?? null }},{{$technicians->state?? null }},{{$technicians->zipcode?? null }}</small>

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
                                        <img src="{{asset('public/admin/assets/images/users/default_image.jpg')}}"
                                            width="150" class="rounded-circle" alt="user" />
                                        @endisset
                                        <h4 class="mt-3 mb-0">{{ $technicians->usertechnician->name ?? null }}</h4>
                                        <a href="mailto:{{ $technicians->usertechnician->email ?? '' }}">{{ $technicians->usertechnician->email

                                            ?? null }}</a><br><small class="text-muted">{{
                                            $technicians->usertechnician->mobile
                                            ?? null }}<br />{{ $technicians->usertechnician->Locationareaname->area_name
                                            ?? null }}</small>
                                                                                          <button type="button" class="btn waves-effect waves-light btn-rounded btn-warning"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Message</button>

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
                                        <img src="{{asset('public/admin/assets/images/users/default_image.jpg')}}"
                                            width="150" class="rounded-circle" alt="user" />
                                        @endisset
                                        <h4 class="mt-3 mb-0">{{ $technicians->userdispatcher->name ?? null }}</h4>
                                        <a href="mailto:{{ $technicians->userdispatcher->email ?? '' }}">{{ $technicians->userdispatcher->email

                                            ?? null }}</a>@if ($technicians->userdispatcher->mobile ?? null)
    <br><small class="text-muted">
    {{ $technicians->userdispatcher->mobile }}<br />Frontier Support Staff
    </small>
@endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Job Details &nbsp<span
                                class="mb-1 badge bg-primary" style="font-size: 15px;"></span> </h4> 
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
                                            <div class="mt-1">@if ($technicians->jobserviceinfo &&
                                                $technicians->jobserviceinfo->base_price !== null)
                                                ${{ $technicians->jobserviceinfo->base_price }}
                                                @endif</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">
                                                @if ($technicians->jobserviceinfo &&
                                                $technicians->jobserviceinfo->discount !== null)
                                                ${{ $technicians->jobserviceinfo->discount }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mt-1">
                                                @if ($technicians->jobserviceinfo && $technicians->jobserviceinfo->tax
                                                !== null)
                                                ${{ $technicians->jobserviceinfo->tax }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mt-1">
                                                @if ($technicians->jobserviceinfo &&
                                                $technicians->jobserviceinfo->sub_total !== null)
                                                ${{ $technicians->jobserviceinfo->sub_total }}
                                                @endif
                                            </div>
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
                                            <div class="mt-1">@if ($technicians->jobproductinfo &&
                                                $technicians->jobproductinfo->base_price !== null)
                                                ${{ $technicians->jobproductinfo->base_price }}
                                                @endif</div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-1">
                                                @if ($technicians->jobproductinfo &&
                                                $technicians->jobproductinfo->discount !== null)
                                                ${{ $technicians->jobproductinfo->discount }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mt-1">
                                                @if ($technicians->jobproductinfo && $technicians->jobproductinfo->tax
                                                !== null)
                                                ${{ $technicians->jobproductinfo->tax }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mt-1">
                                                @if ($technicians->jobproductinfo &&
                                                $technicians->jobproductinfo->sub_total !== null)
                                                ${{ $technicians->jobproductinfo->sub_total }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="border-top: 2px dotted #343434">
                                        <div class="col-md-4">&nbsp;</div>
                                        <div class="col-md-2">
                                            <div class="mt-2">&nbsp;</div>
                                        </div>
                                        <div class="col-md-2">
    <div class="mt-2">
        <h5>
@if ($technicians && $technicians->discount !== null)
    ${{ $technicians->discount }}
@endif
        </h5>
    </div>
</div>

<div class="col-md-2">
    <div class="mt-2">
        <h5>
        
             @if ($technicians &&
                                                $technicians->tax !== null)
                                                ${{ $technicians->tax }}
                                                @endif
        </h5>
    </div>
</div>

<div class="col-md-2">
    <div class="mt-2">
        <h5>
          
            
             @if ($technicians &&
           $technicians->gross_total !== null)
               ${{ $technicians->gross_total }}
                                                @endif
            
        </h5>
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
                        <h4 class="card-title"> Notes</h4>
                        @foreach ($techniciansnotes as $item)
                        <ul class="list-unstyled mt-1">
                            <li class="d-flex align-items-start">
                                @isset($item->user_image)
                                <img src="{{ asset('public/images/multiadmin/' . $item->user_image) ?? asset('public/images/default_user_image.jpg') }}"
                                    width="60" class="me-5 rounded-circle" alt="user" />
                                @else
                                <img src="{{asset('public/admin/assets/images/users/default_image.jpg')}}"
                                    width="60" class="me-5 rounded-circle" alt="user" />
                                @endisset

                                <div class="media-body">
                                    <h5 class="mt-0 mb-1">{{ $item->name ?? 'Unknown' }}</h5>
                                   {!! $item->note ?? null !!}

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
                        <h4 class="mb-3">Add a Note</h4>


                     <form class="row g-2" method="post" action="{{route('techniciannote')}}"
                            enctype="multipart/form-data">
                            @csrf
                      @if(session('success'))
               <div id="successMessage" class="alert alert-success" role="alert">
               {{ session('success') }}
               </div>
                @endif
                           <input type="hidden" name="id" value={{$technicians->id}} >
                            <input type="hidden" name="technician_id" value={{$technicians->technician_id}} >
                            <textarea id="mymce" name="note" ></textarea>

                       <div class="col-md-2">
    <button type="submit" id="submitBtn" class="mt-3 btn waves-effect waves-light btn-success">
        Send
    </button>
</div>

                            
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
    <span class="badge 
        @if($technicians->status == 'closed') bg-success 
        @elseif($technicians->status == 'pending') bg-warning 
        @elseif($technicians->status == 'rejected') bg-danger 
        @else bg-primary 
        @endif"
        style="font-size: 18px; font-weight: bold;">
        {{ $technicians->status ?? null }}
    </span>
</div>

                            <div class="col-6 my-2">&nbsp;</div>
                        </div>
                      <div class="row mt-3 mb-3">
    @if ($technicians->jobassignname->start_date_time)
        <?php $startDateTime = new DateTime($technicians->jobassignname->start_date_time); ?>
        <h4 class="card-title mb-0"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp; {{
            $startDateTime->format('jS M Y @ h:ia') }}</h4>
    @endif
</div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Duration:</strong> {{ $technicians->jobassignname->duration / 60 . ($technicians->jobassignname->duration ? ' hours' : '') ?? null }}


                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Start:</strong>  {{ $convertTimeToTimezone($ticket->JobAssign->start_date_time, 'H:i:a') }}


                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>End:</strong>  {{ $convertTimeToTimezone($ticket->JobAssign->end_date_time, 'H:i:a') }}


                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Priority:</strong> {{$technicians->priority?? null }} </div>
                            </div>
                            <div class="col-md-12">
   <div class="mb-2"><strong>Tags:</strong>
   @foreach($Sitetagnames as $item)
       {{ $item->tag_name }}
   @endforeach
</div>



</div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ADDRESS</h4>
                        <div class="">
                            <h5 class="todo-desc mb-0 fs-3 font-weight-medium">{{$technicians->address?? null }},{{$technicians->city?? null }},{{$technicians->state?? null }},{{$technicians->zipcode?? null }}
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
                             @foreach($ticket->jobactivity as $activities)
                                <li class="list-group-item border-0 mb-0 py-2 pe-3 ps-0">
                                    <h5 class="todo-desc mb-0 fs-3 font-weight-medium">{{$activities->activity ?? null}}</h5>
                                    <div class="todo-desc text-muted fw-normal fs-2">{{ $activities->created_at->format('jS M Y @ h:ia') ?? null }}

</div>
                                </li>
                                @endforeach
                                

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="mt-4">Technician Assigned</h5>
                        <span>{{ $technicians->jobassignname->technician->name ?? null }}</span>
                       <h5 class="pt-3">Ticket Creator</h5>
@if ($technicians->userdispatcher ?? null)
    <span>{{ $technicians->userdispatcher->name }} @Frontier Support Staff</span>
@endif

                        <h5 class="pt-3">Ticket created on </h5>
                       <span>
    @if ($technicians->jobassignname->created_at)
        {{ $technicians->jobassignname->created_at->format('jS M Y @ h:ia') }}
    @endif
</span>




                        <h5 class="pt-3">Ticket Last Modified on </h5>
                      <span>
    @if ($technicians->jobassignname->updated_at)
        {{ $technicians->jobassignname->updated_at->format('jS M Y @ h:ia') }}
    @endif
</span>

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

</script>

<script>
    $(function () {
        tinymce.init({
  selector: 'textarea#mymce'
});
$('#submitBtn').click(function() {
            // Check if the TinyMCE textarea is empty
            if (tinymce.activeEditor.getContent().trim() === '') {
                // If textarea is empty, prevent form submission
                alert('Please enter a Job note.');
                return false;
            }
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
<script>
    setTimeout(function() {
        $('#successMessage').fadeOut('fast');
    }, 5000); // 5000 milliseconds = 5 seconds
</script>

@endsection
@endsection