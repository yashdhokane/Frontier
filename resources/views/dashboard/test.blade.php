@extends('home')

@section('content')
@php
$time_interval = Session::get('time_interval', 0);
@endphp

<div class="container-fluid">
    <div class="row grid" id="sectionRow">
        <!-- Customers Section -->


        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="customerSectionDemo">
            <div class="card w-100">
                <div class="card-border card-body shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="customerHeader" class="text-success">Customers </h4>
                            <button class="btn btn-link text-success" id="customerToggleDemo"> Expand </button>
                        </div>

                        <!-- Embed the customer section in an iframe -->
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/customers-demo-iframe"
                            style="width: 100%; height: 400px; border: none;" id="customerIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/customers-demo-iframe?header=off&sidebar=off"
                            style="width: 100%;  height: 400px; border: none;" id="customerIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="technicianSectionDemo">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="technicianHeader" class="text-warning">Technicians </h4>
                            <button class="btn btn-link text-warning" id="technicianToggleDemo"> Expand
                            </button>
                        </div>
                        <!-- Add iframe here -->
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/technicians"
                            style="width: 100%; height: 400px; border: none; overflow-x: scroll; overflow-y: scroll;"
                            id="technicianIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/technicians?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: scroll; overflow-y: scroll;"
                            id="technicianIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="jobSection">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="jobHeader" class="text-warning">Jobs </h4>
                            <button class="btn btn-link text-warning" id="jobToggle"> Expand
                            </button>
                        </div>
                        <!-- Add iframe here -->
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/tickets"
                            style="width: 100%; height: 400px; border: none; overflow-x: scroll; overflow-y: scroll;"
                            id="jobIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/tickets?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: scroll; overflow-y: scroll;"
                            id="jobIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>


        <!-- schedule section in -->
        <div class="col-lg-12 col-sm-12 d-flex align-items-stretch grid-item" id="scheduleSectiondemo">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <h4 id="scheduleHeader" class="text-dark">Schedule </h4>
                            <button class="btn btn-link text-dark" id="scheduleToggleDemo"> Expand</button>

                        </div>
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/schedule"
                            style="width: 100%; height: 400px; border: none; overflow: visible;" id="scheduleIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/schedule?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow: visible;" id="scheduleIframe">
                        </iframe> 

                    </div>
                </div>
            </div>
        </div>
        <!-- open invoices -->
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="OpeninvoicesSection">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <h4 id="OpeninvoicesHeader" class="text-dark">Open invoices</h4>
                            <button class="btn btn-link text-dark" id="OpeninvoicesToggle"> Expand</button>

                        </div>
                        <div class="table-responsive mt-1">
                            <table id="" class="table table-bordered text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>ID</th>

                                        <th>Customer</th>
                                        <th>Technician</th>
                                        <th>Inv. Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>

                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    <!-- start row -->
                                    @foreach ($paymentclose as $index => $item)
                                    <tr>
                                        <td><a href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number
                                                ?? null }}</a>
                                        </td>

                                        <td>{{ $item->user->name ?? null }}</td>
                                        <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                        <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}</td>
                                        <td>{{ $convertDateToTimezone($item->due_date ?? null) }}</td>
                                        <td>${{ $item->total ?? null }}</td>


                                    </tr>
                                    <!-- Modal for adding comment -->
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Messages -->
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="messageSection">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <h4 id="messageHeader" class="text-primary">Message</h4>
                            <button class="btn btn-link text-primary" id="messageToggle"> Expand</button>

                        </div>
                 
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/inbox"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="messageIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/inbox?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="messageIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>
        <!-- event -->
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="eventsSectionDemo">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="eventsHeader" class="text-danger">Events </h4>
                            <button class="btn btn-link text-danger" id="eventsToggleDemo"> Expand</button>
                        </div>
                        <!-- Add iframe here -->
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/events"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="eventsIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/events?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="eventsIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>

        <!-- Parts Section -->

        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="assetsSectionDemo">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="assetsHeader" class="text-primary">Parts Management </h4>
                            <button class="btn btn-link text-primary" id="assetsToggleDemo"> Expand</button>
                        </div>
                        <!-- Add iframe here -->
                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/parts"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="assetsIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/parts?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="assetsIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="toolSection">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <h4 id="toolHeader" class="text-danger">Tools</h4>
                            <button class="btn btn-link text-danger" id="toolToggle"> Expand</button>

                        </div>
                     

                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/tools"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="toolIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/tools?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="toolIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item" id="fleetSection">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <h4 id="fleetHeader" class="text-danger">Fleet</h4>
                            <button class="btn btn-link text-danger" id="fleetToggle"> Expand</button>

                        </div>


                        <!-- <iframe src="http://localhost/laravelgaffiss/dispatcheralportal/vehicles"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="fleetIframe">
                        </iframe> -->
                         <iframe src="https://dispatchannel.com/portal/vehicles?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="fleetIframe">
                        </iframe> 
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script>
    $(document).ready(function () {
         $('.row').css('--bs-gutter-x', '10px');
         $('.row').css('--bs-gutter-y', '0px');

        // // Function to change the gutter dynamically
        // function changeGutter(gutterValue) {
        //     $('.row').css('--bs-gutter-x', gutterValue);
        // }

        // Initialize Masonry
        var $grid = $('.grid').masonry({
            itemSelector: '.grid-item',
            percentPosition: true,
            columnWidth: '.grid-sizer'
        });

        // Layout Masonry after each image loads
        $grid.imagesLoaded().progress(function () {
            $grid.masonry('layout');
        });
});
</script>
<script>
 $(document).ready(function () {
    function Section(sectionId) {
        // Get the section to expand
        let section = $('#' + sectionId).closest('.col-md-4');
        let container = $('#sectionRow');

        // Remove all other sections and save them
        let otherSections = container.children().not(section);

        // Collapse all other sections
        otherSections.each(function () {
            $(this).removeClass('col-md-12 expanded-section').addClass('col-md-4 collapsed-section');
            $(this).find('ul').css('display', 'block');
            $(this).find('li').css({'width': '100%', 'float': 'none'});

            // Reapply grid-item class to other sections
            $(this).addClass('grid-item');

            // Apply styles only to collapsed sections
           
        });

        // Add the class to the expanded section
        section.removeClass('col-md-4 collapsed-section').addClass('col-md-12 expanded-section');
        container.prepend(section);

        // Apply the expanded styles
        section.find('ul').css('display', 'block');
        section.find('li').css({'width': '33.33%', 'float': 'left', 'border': '1px solid #ddd'});

        // Remove the grid-item class from the expanded section
        section.removeClass('grid-item');

        // Scroll to the top of the expanded section
        $('html, body').animate({
            scrollTop: section.offset().top
        }, 500);

        // Remove styles from previously expanded sections if necessary
        $('.expanded-section').each(function () {
            $(this).find('style').remove(); // Remove style tags from expanded sections
        });
    }

    // Add click handlers to the buttons to expand sections
    $('#customerToggle').on('click', function () {
        Section('customerSection');
    });
    $('#customerToggleDemo').on('click', function () {
        Section('customerSectionDemo');
    });   

    $('#technicianToggle').on('click', function () {
        Section('technicianSection');
    });
    $('#technicianToggleDemo').on('click', function () {
        Section('technicianSectionDemo');
    });

    $('#assetsToggle').on('click', function () {
        Section('assetsSection');
    });
    $('#assetsToggleDemo').on('click', function () {
        Section('assetsSectionDemo');
    });

    $('#eventsToggle').on('click', function () {
        Section('eventsSection');
    });
    $('#eventsToggleDemo').on('click', function () {
        Section('eventsSectionDemo');
    });

    $('#paymentsToggle').on('click', function () {
        Section('paymentsSection');
    });
    $('#paymentsToggleDemo').on('click', function () {
        Section('paymentsSectionDemo');
    });

    $('#scheduleToggle').on('click', function () {
        Section('scheduleSection');
    });
    $('#scheduleToggleDemo').on('click', function () {
        Section('scheduleSectiondemo');
    });
    $('#OpeninvoicesToggle').on('click', function () {
        Section('OpeninvoicesSection');
    });
    $('#UpcomingjobsToggle').on('click', function () {
        Section('UpcomingjobsSection');
    });

    // New section
    $('#jobToggle').on('click', function () {
        Section('jobSection');
    });
      $('#fleetToggle').on('click', function () {
        Section('fleetSection');
    });
    $('#toolToggle').on('click', function () {
        Section('toolSection');
    });

     $('#messageToggle').on('click', function () {
        Section('messageSection');
    });
});


    $(document).ready(function () {
        // Search functionality for Schedules
        $('#scheduleSearch').on('input', function () {
            let query = $(this).val();
            let list = $('#scheduleList');

            if (query === '') {
                // If input is cleared, show default list
                list.empty();
                @foreach($schedules as $schedule)
                list.append('<li class="list-group-item"><i class="ri-timer-line text-info me-2"></i> #{{ $schedule->job_id ?? '' }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.schedule.search') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    list.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, schedule) {
                            list.append('<li class="list-group-item"><i class="ri-timer-line text-info me-2"></i> #' + schedule.job_id + '</li>');
                        });
                    } else {
                        list.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });

        // Search functionality for Customers
        $('#customerSearch').on('input', function () {
            let query = $(this).val();
            let list = $('#customerList');

            if (query === '') {
                // If input is cleared, show default list
                list.empty();
                @foreach($recentcustomer as $assignment)
                list.append('<li class="list-group-item">{{ $assignment->customer->name ?? 'Unknown Customer' }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.search.customers') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    list.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, customer) {
                            list.append('<li class="list-group-item">' + customer.name + '</li>');
                        });
                    } else {
                        list.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });

        // Search functionality for Events
        $('#eventSearch').on('input', function () {
            let query = $(this).val();
            let list = $('#eventList');

            if (query === '') {
                // If input is cleared, show default list
                list.empty();
                @foreach($events as $event)
                list.append('<li class="list-group-item"><i class="ri-calendar-event-line text-info me-2"></i> {{ $event->event_name ?? '' }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.events.search') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    list.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, event) {
                            list.append('<li class="list-group-item"><i class="ri-calendar-event-line text-info me-2"></i> ' + event.event_name + '</li>');
                        });
                    } else {
                        list.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });

        // Search functionality for Technicians
        $('#technicianSearch').on('input', function () {
            let query = $(this).val();
            let list = $('#technicianList');

            if (query === '') {
                // If input is cleared, show default list
                list.empty();
                @foreach($recentcustomer as $technician)
                list.append('<li class="list-group-item">{{ $technician->technician->name ?? 'Unknown Technician' }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.search.technicians') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    list.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, technician) {
                            list.append('<li class="list-group-item">' + technician.name + '</li>');
                        });
                    } else {
                        list.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });

        // Search functionality for Parts
        $('#partsSearch').on('input', function () {
            let query = $(this).val();
            let partsList = $('#partsList');

            if (query === '') {
                // If input is cleared, show default list
                partsList.empty();
                @foreach($product as $item)
                partsList.append('<li class="list-group-item">{{ $item }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.search.parts') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    partsList.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, item) {
                            partsList.append('<li class="list-group-item">' + item + '</li>');
                        });
                    } else {
                        partsList.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });

        // Search functionality for Tools
        $('#toolsSearch').on('input', function () {
            let query = $(this).val();
            let list = $('#toolsList');

            if (query === '') {
                // If input is cleared, show default list
                list.empty();
                @foreach($tool as $item)
                list.append('<li class="list-group-item">{{ $item->product_name ?? '' }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.search.tools') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    list.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, tool) {
                            list.append('<li class="list-group-item">' + tool.product_name + '</li>');
                        });
                    } else {
                        list.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });

        // Search functionality for Payments
        $('#paymentsSearch').on('input', function () {
            let query = $(this).val();
            let list = $('#paymentsList');

            if (query === '') {
                // If input is cleared, show default list
                list.empty();
                @foreach($payments as $payment)
                list.append('<li class="list-group-item"><i class="ri-currency-line text-info me-2"></i> {{ $payment->invoice_number ?? '' }}</li>');
                @endforeach
                return;
            }

            $.ajax({
                url: "{{ route('dashboard.search.payments') }}",
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    list.empty();
                    if (data.length > 0) {
                        $.each(data, function (index, payment) {
                            list.append('<li class="list-group-item"><i class="ri-currency-line text-info me-2"></i> ' + payment.invoice_number + '</li>');
                        });
                    } else {
                        list.append('<li class="list-group-item">No results found</li>');
                    }
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
             function applyIframeStyles(iframeId) {
            $(iframeId).on('load', function() {
            var iframe = $(this)[0];
            var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

            // Inject custom CSS into the iframe
            var style = document.createElement('style');
            style.type = 'text/css';
            style.innerHTML = `

                /* Hide header and aside elements */
                header { display: none !important; }
                aside { display: none !important; }
                footer { display: none !important; }
                  .page-title { display: none !important; }


                /* Adjust layout and overflow */
                #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar {
                    display: none;
                }
                #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar {
                    display: none;
                }
                #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
                    margin-left: 10px;
                }
                #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
                    padding-top: 10px;
                }

                /* Make iframe content scrollable */
                html, body {
                    overflow: auto !important; /* Allow scrolling */
                    margin: 0; /* Remove default margins */
                    padding: 0; /* Remove default padding */
                    
                }
                #scheduleSection1{
                    overflow:auto !important;
                    }
            `;

            iframeDocument.head.appendChild(style);

            // Optionally adjust iframe height based on content
            // $(iframe).height($(iframeDocument).find('body').height());
        });
    }

    // Apply styles to both iframes
     applyIframeStyles('#customerIframe');
     applyIframeStyles('#scheduleIframe');
     applyIframeStyles('#technicianIframe');
     applyIframeStyles('#assetsIframe');
     applyIframeStyles('#paymentsIframe');
     applyIframeStyles('#eventsIframe');
     applyIframeStyles('#jobIframe');
applyIframeStyles('#messageIframe');
applyIframeStyles('#toolIframe');
applyIframeStyles('#fleetIframe');
});
</script>
   
@endsection