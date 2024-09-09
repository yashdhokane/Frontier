@extends('home')

@section('content')

@php
$header = 'off';
$sidebar = 'off';
@endphp

@php
$time_interval = Session::get('time_interval', 0);

@endphp


<style>
    /* .box {
        padding: 20px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
        cursor: pointer;
        background-color: #f8f9fa;
    }*/

    .box:focus {
        outline: none;
        box-shadow: 0 0 7px #007bff;
    }

    .card-border {
        border: 1px solid #ddd;
    }
    
</style>
<div class="container d-bg">
    <div id="boxContainer" class="row">
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Customers</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/customers-demo-iframe?header=off&sidebar=off"
                            style="width: 100%;  height: 400px; border: none;"  id="customerIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Technicians</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/technicians?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: scroll; overflow-y: scroll;"
                            id="technicianIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Jobs</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <iframe src="https://dispatchannel.com/portal/tickets?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: scroll; overflow-y: scroll;"
                            id="jobIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Schedule</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/schedule?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow: visible;" id="scheduleIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Open invoices</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->

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
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Message</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/inbox?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="messageIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Events</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/events?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="eventsIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Parts Management</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/parts?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="assetsIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Tools</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/tools?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="toolIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 d-flex align-items-stretch grid-item box" tabindex="0" data-index="0">
            <div class="card w-100">
                <div class="card-body card-border shadow">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-warning">Fleet</h4>
                            <button class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                        </div>
                        <!-- Add iframe or other content here -->
                        <iframe src="https://dispatchannel.com/portal/vehicles?header=off&sidebar=off"
                            style="width: 100%; height: 400px; border: none; overflow-x: auto; overflow-y: auto;"
                            id="fleetIframe">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- Repeat similar divs for the other boxes -->
    </div>
</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const boxContainer = document.getElementById('boxContainer');
        let originalOrder = Array.from(boxContainer.children);

        boxContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('expand-toggle')) {
                const button = e.target;
                const card = button.closest('.box');
                const index = card.getAttribute('data-index');

                if (button.textContent === 'Expand') {
                    // Expand the clicked card
                    card.classList.remove('col-md-4');
                    card.classList.add('col-md-12');
                    boxContainer.prepend(card);
                    button.textContent = 'Less';
                    card.focus();
                } else if (button.textContent === 'Less') {
                    // Collapse the card back to its original position
                    card.classList.remove('col-md-12');
                    card.classList.add('col-md-4');
                    boxContainer.innerHTML = '';
                    originalOrder.forEach(box => {
                        boxContainer.appendChild(box);
                    });
                    button.textContent = 'Expand';
                    card.focus();
                }
            }
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
                  .page-breadcrumb { padding: 0px 0px 0 0px !important; }
                  .page-wrapper>.container-fluid, .page-wrapper>.container-lg, .page-wrapper>.container-md, .page-wrapper>.container-sm, .page-wrapper>.container-xl, .page-wrapper>.container-xxl {
    padding: 0px;

}
.container-fluid{
    padding: 0px;
    }

    .table-custom #zero_config_info {
    float: none;}

.paginate_button{display:inline-block !important;}
 
  /* Custom styles for specific classes */
                .threedottest { display: block !important; }
                .withoutthreedottest { display: none !important; }


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
<script>
//   $(document).ready(function() {
//     function applyIframeStyles(iframeId) {
//         $(iframeId).on('load', function() {
//             var iframe = $(this)[0];
//             var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

//             // Create a link element for the external CSS file
//             var link = iframeDocument.createElement('link');
//             link.rel = 'stylesheet';
//             link.type = 'text/css';
//             link.href = '{{ url("public/admin/dashboard/style.css") }}'; // Replace with the correct path to your CSS file

//             // Append the link element to the iframe's head
//             iframeDocument.head.appendChild(link);
//         });
//     }

//     // Apply styles to all iframes
//     applyIframeStyles('#customerIframe');
//     applyIframeStyles('#scheduleIframe');
//     applyIframeStyles('#technicianIframe');
//     applyIframeStyles('#assetsIframe');
//     applyIframeStyles('#paymentsIframe');
//     applyIframeStyles('#eventsIframe');
//     applyIframeStyles('#jobIframe');
//     applyIframeStyles('#messageIframe');
//     applyIframeStyles('#toolIframe');
//     applyIframeStyles('#fleetIframe');
// });

</script>

@endsection