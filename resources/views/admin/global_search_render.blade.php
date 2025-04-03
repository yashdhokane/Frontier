@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
@endif
<!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ3Q1f+7pH22o6d7b1rUl1qQU1F6hsR00sHV8k5azQ9o1Nq0zFbZV+E8v5T1" crossorigin="anonymous">

 <style>
//     .paginate_laravel svg {
//         width: 15px;
//     }

//     .paginate_laravel nav > div:first-child {
//         display: none;
//     }

//     .paginate_laravel nav > div:nth-child(2) {
//         display: flex;
//         justify-content: space-between;
//     }
 </style>

<style>
    .fixed-height-card {
        /* height: 600px;  */
        overflow-y: auto;
    }
    .col-md-4{
         margin-top: 10px;
    }
</style>

<div class="container-fluid">  
{{-- {{ $totalResultsCount }} result(s) --}}
    <div class="row">
     <h4 class="card-title">Search Results For "{{ $query }}" </h4>
        <h6 class="card-subtitle"> <h6 class="card-subtitle"> </h6>
</h6>


<div id="masonry-grid" class=" row d-flex flex-wrap">

    {{--    @if($jobs->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Jobs</h4>
                @foreach($jobs as $row)
                    <a href="{{ url('tickets/'.$row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                           
                            <p  class="font-medium link" style="margin-bottom:2px!important;">#{{ $row->id }} - {{ $row->result }}</p>
                             <div style="font-size:12px;">@if ($row->user)
                                                        {{ $row->user->name }}
                                                    @else
                                                        Unassigned
                                                        <pre>{{ json_encode($row->user1) }}</pre>
                                                    @endif   </div>
                             <div style="font-size:12px;">
                                                        @if ($row->JobAppliances && $row->JobAppliances->Appliances)
                                                            {{ $row->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                                        @endif
                                                        @if ($row->JobAppliances && $row->JobAppliances->Appliances)
                                                            {{ $row->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
                                                        @endif
                                                        @if ($row->JobAppliances && $row->JobAppliances->Appliances->model_number)
                                                            {{ $row->JobAppliances->Appliances->model_number ?? null }}/
                                                        @endif
                                                        @if ($row->JobAppliances && $row->JobAppliances->Appliances->serial_number)
                                                            {{ $row->JobAppliances->Appliances->serial_number ?? null }}
                                                        @endif
                                                    </div>
                             <div style="font-size:12px;">
                              @if ($row)
                                    @php
                                    $userAddress = DB::table('user_address')
                                    ->leftJoin(
                                    'location_states',
                                    'user_address.state_id',
                                    '=',
                                    'location_states.state_id',
                                    )
                                    ->where('user_id', $row->customer_id)
                                    ->first();
                                    $userAddresscity = DB::table('user_address')
                                    ->leftJoin(
                                    'location_cities',
                                    'user_address.city_id',
                                    '=',
                                    'location_cities.city_id',
                                    )
                                    ->where('user_address.user_id', $row->customer_id)
                                    ->value('location_cities.city');

                                    @endphp


                                    @if ($userAddress)
                                    <span class="user-work">Address: {{ $userAddress->city }},
                                        {{ $userAddress->state_code }}, {{ $userAddress->zipcode }}</span>
                                    @else
                                    <span class="user-work">N/A</span>
                                    @endif
                                    @else
                                    <span class="user-work">N/A</span>
                                    @endif
                                    <br />
                             </div>

                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif  

--}}

@if($jobs->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Jobs</h4>
                @foreach($jobs as $index => $row)
                    <a href="{{ url('tickets/'.$row->id) }}" class="d-block mb-2 text-decoration-none job-item @if($index >= 10) d-none @endif">
                        <div class="p-2 border rounded shadow-sm">
                            <p class="font-medium link" style="margin-bottom:2px!important;">#{{ $row->id }} - {{ $row->result }}</p>
                            <div style="font-size:12px;">
                                @if ($row->user)
                                    {{ $row->user->name }}
                                @else
                                    Unassigned
                                @endif
                            </div>
                            <div style="font-size:12px;">
                                @if ($row->JobAppliances && $row->JobAppliances->Appliances)
                                    {{ $row->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                    {{ $row->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
                                    {{ $row->JobAppliances->Appliances->model_number ?? null }}/
                                    {{ $row->JobAppliances->Appliances->serial_number ?? null }}
                                @endif
                            </div>
                            <div style="font-size:12px;">
                                @php
                                    $userAddress = DB::table('user_address')
                                        ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
                                        ->where('user_id', $row->customer_id)
                                        ->first();
                                    
                                    $userAddresscity = DB::table('user_address')
                                        ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
                                        ->where('user_address.user_id', $row->customer_id)
                                        ->value('location_cities.city');
                                @endphp

                                @if ($userAddress)
                                    <span class="user-work">Address: {{ $userAddress->city }},
                                        {{ $userAddress->state_code }}, {{ $userAddress->zipcode }}</span>
                                @else
                                    <span class="user-work">N/A</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach

                @if($jobs->count() > 10)
                    <button id="readMoreBtn" class="btn btn-primary mt-2 w-100">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif


{{--
@if($customers->isNotEmpty())   
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Customers</h4>
                @foreach($customers as $row)
                    <a href="{{ url('customers/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 ">{{ $row->short_description }}</p>

                              @if ($row)
                                    @php
                                    $userAddress = DB::table('user_address')
                                    ->leftJoin(
                                    'location_states',
                                    'user_address.state_id',
                                    '=',
                                    'location_states.state_id',
                                    )
                                    ->where('user_id', $row->id)
                                    ->first();
                                    $userAddresscity = DB::table('user_address')
                                    ->leftJoin(
                                    'location_cities',
                                    'user_address.city_id',
                                    '=',
                                    'location_cities.city_id',
                                    )
                                    ->where('user_address.user_id', $row->id)
                                    ->value('location_cities.city');

                                    @endphp

                                    @if ($userAddress)
                                    <span class="user-work">Address: {{ $userAddress->city }},
                                        {{ $userAddress->state_code }}, {{ $userAddress->zipcode }}</span>
                                    @else
                                    <span class="user-work">N/A</span>
                                    @endif
                                    @else
                                    <span class="user-work">N/A</span>
                                    @endif
                                    <br />
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif --}}
@if($customers->isNotEmpty())   
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Customers</h4>
                @foreach($customers as $index => $row)
                    <a href="{{ url('customers/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none customer-item @if($index >= 10) d-none @endif">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 ">{{ $row->short_description }}</p>

                            @php
                                $userAddress = DB::table('user_address')
                                    ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
                                    ->where('user_id', $row->id)
                                    ->first();

                                $userAddresscity = DB::table('user_address')
                                    ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
                                    ->where('user_address.user_id', $row->id)
                                    ->value('location_cities.city');
                            @endphp

                            @if ($userAddress)
                                <span class="user-work">Address: {{ $userAddress->city }},
                                    {{ $userAddress->state_code }}, {{ $userAddress->zipcode }}</span>
                            @else
                                <span class="user-work">N/A</span>
                            @endif
                        </div>
                    </a>
                @endforeach

                @if($customers->count() > 10)
                    <button id="readMoreCustomersBtn" class="btn btn-primary mt-2 w-100">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif

@if($users->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Dispatchers</h4>
                @foreach($users as $index => $row)
                    <a href="{{ url('dispatchers/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none dispatcher-item @if($index >= 10) d-none @endif">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
                @if($users->count() > 10)
                    <button id="readMoreDispatchersBtn" class="btn btn-primary mt-2 w-100">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif

@if($technicians->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Technicians</h4>
                @foreach($technicians as $index => $row)
                    <a href="{{ url('technicians/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none technician-item @if($index >= 10) d-none @endif">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                            @if(!empty($row->service_area))
                                <p class="mb-0 text-muted">
                                    <strong>Service Area:</strong> {{ $row->service_area }}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
                @if($technicians->count() > 10)
                    <button id="readMoreTechniciansBtn" class="btn btn-primary mt-2 w-100">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif

@if($admins->isNotEmpty()) 
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Admins</h4>
                @foreach($admins as $index => $row)
                    <a href="{{ url('multiadmin/show/'.$row->id) }}" class="d-block mb-2 text-decoration-none admin-item {{ $index >= 5 ? 'd-none' : '' }}">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
                @if($admins->count() > 5)
                    <button id="readMoreAdminsBtn" class="btn btn-link">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif

@if($services->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Services</h4>
                @foreach($services as $index => $row)
                    <a href="{{ url('book-list/services/'.$row->id.'/edit') }}" class="d-block mb-2 text-decoration-none service-item {{ $index >= 5 ? 'd-none' : '' }}">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
                @if($services->count() > 5)
                    <button id="readMoreServicesBtn" class="btn btn-link">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif

@if($products->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Parts</h4>
                @foreach($products as $index => $row)
                    <a href="{{ url('book-list/parts/'.$row->id.'/edit') }}" class="d-block mb-2 text-decoration-none part-item {{ $index >= 1 ? 'd-none' : '' }}">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
                @if($products->count() > 1)
                    <button id="readMorePartsBtn" class="btn btn-link">Read More</button>
                @endif
            </div>
        </div>
    </div>
@endif

@if($payments->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Payments</h4>
                @foreach($payments as $row)
                    <a href="{{ url('invoice-detail', $row->id) }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($manufacturers->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Manufacturers</h4>
                @foreach($manufacturers as $row)
                    <a href="{{ url('manufacturer-edit/'.$row->id.'/edit') }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif


{{--
@if($estimateTemplates->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Estimate Templates</h4>
                @foreach($estimateTemplates as $row)
                    <a href="{{ url('book-list/estimate/'.$row->template_id.'/edit') }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif  --}}

@if($siteWorkingHours->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Site Working Hours</h4>
                @foreach($siteWorkingHours as $row)
                    <a href="{{ url('setting/buisness-profile') }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($locationServiceAreas->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Location Service Areas</h4>
                @foreach($locationServiceAreas as $row)
                    <a href="{{ url('setting/businessHours/business-hours') }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($locationStates->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Location States</h4>
                @foreach($locationStates as $row)
                    <a href="{{ url('setting/tax') }}" class="d-block mb-2 text-decoration-none">
                        <div class="p-2 border rounded shadow-sm">
                            <strong>{{ $row->result }}</strong>
                            <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
@if($siteSettings1->isNotEmpty())
   <div class="col-md-4 masonry-item">
        <div class="card">
            <div class="card-body card-border shadow fixed-height-card">
                <h4 class="card-title">Site Settings</h4>
                @php $row = $siteSettings1->first(); @endphp
                <a href="{{ url('setting/buisness-profile') }}" class="d-block mb-2 text-decoration-none">
                    <div class="p-2 border rounded shadow-sm">
                        <strong>{{ $row->result }}</strong>
                        <p class="mb-0 text-muted">{{ $row->short_description }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endif


    </div>
</div>

</div>



@section('script')


<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<!-- Include imagesLoaded.js -->
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
      <script>
document.addEventListener("DOMContentLoaded", function () {
    var elem = document.querySelector("#masonry-grid");

    imagesLoaded(elem, function () {
        // Ensure masonry initializes properly
        var msnry = new Masonry(elem, {
            itemSelector: ".masonry-item",
            columnWidth: ".masonry-item",
            percentPosition: true
        });

        // Force three-column layout
        document.querySelectorAll(".masonry-item").forEach((item) => {
            item.style.width = "32%"; // Set width for three columns
            item.style.marginBottom = "15px"; // Ensure spacing between items
        });

        // Re-layout Masonry after applying styles
        msnry.layout();
    });
});


      </script>
<script>
 $(document).ready(function () {
    let table = new DataTable('#globalSearchTable', {
        paging: true,          // Enables pagination
        searching: true,       // Enables search
        ordering: true,        // Enables sorting
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Option to show all records
        pageLength: 10,        // Default records per page
        dom: 'Bfrtip',         // Enable buttons
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Download Excel',
                title: 'Global Search Data',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: 'Download PDF',
                title: 'Global Search Data',
                className: 'btn btn-danger'
            }
        ]
    });
});

    </script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const readMoreBtn = document.getElementById("readMoreBtn");
    const readMoreCustomersBtn = document.getElementById("readMoreCustomersBtn");
      const readMoreTechniciansBtn = document.getElementById("readMoreTechniciansBtn");
            const readMoreDispatchersBtn = document.getElementById("readMoreDispatchersBtn");

             const readMoreAdminsBtn = document.getElementById("readMoreAdminsBtn");
    const readMoreServicesBtn = document.getElementById("readMoreServicesBtn");
    const readMorePartsBtn = document.getElementById("readMorePartsBtn");


    let masonryGrid = document.querySelector("#masonry-grid");

    // Initialize Masonry
    let msnry = new Masonry(masonryGrid, {
        itemSelector: ".masonry-item",
        columnWidth: ".masonry-item",
        percentPosition: true
    });

    // Function to reveal items and update Masonry
    function revealItems(button, selector) {
        if (button) {
            button.addEventListener("click", function () {
                let hiddenItems = document.querySelectorAll(selector + ".d-none");

                hiddenItems.forEach(function (item) {
                    item.classList.remove("d-none");
                });

                // **Trigger Masonry layout update if applicable**
                msnry.reloadItems();
                msnry.layout();

                // Scroll smoothly to the last revealed item
                if (hiddenItems.length > 0) {
                    hiddenItems[hiddenItems.length - 1].scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }

                // Hide the button after showing all records
                button.style.display = "none";
            });
        }
    }

    // Apply function for both buttons
    revealItems(readMoreBtn, ".job-item");
    revealItems(readMoreCustomersBtn, ".customer-item");

    revealItems("readMoreDispatchersBtn", "dispatcher-item");
    revealItems("readMoreTechniciansBtn", "technician-item");

    revealItems(readMoreAdminsBtn, ".admin-item");
    revealItems(readMoreServicesBtn, ".service-item");
    revealItems(readMorePartsBtn, ".part-item");
});


</script>


@endsection

@if (Route::currentRouteName() != 'dash')
    @endsection
@endif