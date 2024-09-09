@extends('home')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        .expanded {
            order: -1 !important;
            /* Move to the top */
        }

        .original {
            order: 0;
        }

        .box:focus-within {
            border: 2px solid #007bff;
            /* Add blue border when focused */
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            /* Add shadow effect */
            outline: none;
            /* Remove default outline */
        }
    </style>
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->

        <div class="d-flex justify-content-between pb-2">
            <h4 class="mb-3 page-title text-info fw-bold">
                {{ $layout->layout_name ?? null }}
                @if ($layout->added_by == auth()->user()->id && $layout->is_editable == 'yes')
                    <a href="#" class="edit-layout" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="fa fa-edit align-top fs-1 text-danger"></i>
                    </a>
                @endif
            </h4>
            <button type="button" class="btn btn-info" id="customAdd">Custom Dashboard </button>

        </div>
        <div id="customShow">
            <div class="d-flex justify-content-end pb-2">

                <a href="#" class="create-layout" data-bs-toggle="modal" data-bs-target="#createModal">
                    <button type="button" class="btn btn-info">Add New Dashboard</button>
                </a>
                <a href="#" class="create-layout mx-2" data-bs-toggle="modal" data-bs-target="#saveAsModal">
                    <button type="button" class="btn btn-danger ">Save As Current</button>
                </a>
                <form id="urlForm" class="d-flex mx-2" action="{{ route('dash') }}" method="GET">
                    <select id="urlSelect" name="id" class="form-select">
                        <option value="">--Select Dashboard--</option>
                        @foreach ($layoutList as $value)
                            <option value="{{ $value->id }}">{{ $value->layout_name }}</option>
                        @endforeach
                    </select>
                </form>

                @if ($layout->added_by == auth()->user()->id)
                    <form action="{{ route('update.status') }}" method="POST" class="d-flex pe-5">
                        @csrf
                        <input type="hidden" name="layout_id" id="layout_id_val" value="{{ $layout->id }}">
                        <select name="module_id" class="form-select" required>
                            @if ($variable->isEmpty() && $List->isEmpty())
                                <option value="">All section already exists</option>
                            @else
                                <option value="">Select to add section</option>
                                @foreach ($variable as $value)
                                    <option value="{{ $value->module_id }}">
                                        {{ $value->ModuleList->module_name ?? null }}
                                    </option>
                                @endforeach
                                @foreach ($List as $item)
                                    <option value="{{ $item->module_id }}">
                                        {{ $item->module_name ?? null }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="submit" class="btn btn-info mx-2">Add</button>
                    </form>

                @endif

                <!-- Create Layout Name Modal -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editForm" action="{{ route('createLayout') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Add New Dashboard</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editLayoutId" name="id" value="">
                                    <div class="mb-3">
                                        <label for="editLayoutName" class="form-label">Dashboard Name</label>
                                        <input type="text" class="form-control" id="editLayoutName" name="layout_name"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Layout Name Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editForm" action="{{ route('updateLayoutName', ['id' => $layout->id]) }}"
                                method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Dashboard Name</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editLayoutId" name="id" value="">
                                    <div class="mb-3">
                                        <label for="editLayoutName" class="form-label">Dashboard Name</label>
                                        <input type="text" class="form-control" id="editLayoutName"
                                            name="layout_name" value="{{ $layout->layout_name ?? null }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Save as current Layout Name Modal -->
                <div class="modal fade" id="saveAsModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editForm" action="{{ route('createNewLayout') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Save as current Dashboard</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editLayoutId" name="id" value="">
                                    <div class="mb-3">
                                        <label for="editLayoutName" class="form-label">Dashboard Name</label>
                                        <input type="text" class="form-control" id="editLayoutName"
                                            name="layout_name" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <form id="positionForm" method="POST" action="{{ route('savePositions') }}">
            @csrf
            <input type="hidden" name="positions" id="positions">
            <input type="hidden" name="layout_id" value="{{ $layout->id }}">
            <div class="" id="draggable-area">

                    <div class="row" id="box-container">
                    @foreach ($cardPositions as $index => $cardPosition)
                        @if ($cardPosition->ModuleList->module_code == 'schedule')
                            <!-- Correct the spelling here -->
                            <div class="col-md-12 col-lg-12  mb-3 box draggable-items1" id="box-{{ $index }}"
                                data-original-index="{{ $index }}" tabindex="0"  data-id="{{ $cardPosition->module_id }}">
                                <div class="card">
                                    <!-- Flex container for module name and button -->
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $cardPosition->ModuleList->module_name }}</strong>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-link expand-btn">Expand</button>
                                        </div>
                                    </div>

                                    <div class="card-body clearelement" data-id="{{ $cardPosition->module_id }}">
                                        @include('widgets.' . $cardPosition->ModuleList->module_code)
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4 mb-3 box draggable-items1" id="box-{{ $index }}"
                                data-original-index="{{ $index }}" tabindex="0"  data-id="{{ $cardPosition->module_id }}">
                                <div class="card">
                                    <!-- Flex container for module name and button -->
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $cardPosition->ModuleList->module_name }}</strong>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-link expand-btn">Expand</button>
                                        </div>
                                    </div>

                                    <div class="card-body clearelement" data-id="{{ $cardPosition->module_id }}">
                                        @include('widgets.' . $cardPosition->ModuleList->module_code)
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

            </div>
            @if ($layout->added_by == auth()->user()->id)
                <button type="submit" class="btn btn-primary mt-3" id="savePosition">Save Positions</button>
            @endif
        </form>


        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>

    <div class="chat-windows"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = document.querySelectorAll('.box');

            boxes.forEach(box => {
                const expandBtn = box.querySelector('.expand-btn');

                expandBtn.addEventListener('click', function() {
                    const isExpanded = box.classList.contains('expanded');

                    // Reset all boxes to original state
                    boxes.forEach(b => {
                        b.classList.remove('expanded');
                        b.classList.add('original');
                        b.classList.remove('col-md-12');
                        b.classList.add('col-md-4');
                        const btn = b.querySelector('.expand-btn');
                        btn.textContent = 'Expand';
                    });

                    if (!isExpanded) {
                        // Expand this box
                        box.classList.add('expanded');
                        box.classList.remove('original');
                        box.classList.remove('col-md-4');
                        box.classList.add('col-md-12');
                        expandBtn.textContent = 'Less';

                        // Scroll into view and focus on expanded section
                        box.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // Optionally focus on the expand button for accessibility
                        expandBtn.focus();
                    } else {
                        // Focus on the collapsed section after restoring original state
                        box.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        expandBtn.focus();
                    }
                });
            });

            // Initialize Sortable
            new Sortable(document.getElementById('box-container'), {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    console.log('Moved box from index', evt.oldIndex, 'to', evt.newIndex);

                    // Show the "Save Positions" button on drop
                    document.getElementById('savePosition').style.display = 'block';
                }
            });

            // Handle form submission
            $('#positionForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var positions = [];
                $('#box-container .draggable-items1').each(function(index, element) {
                    // Push module_id and new position into the array
                    positions.push({
                        module_id: $(element).data(
                            'id'), // Assuming data-id holds the module's id
                        position: index // New position after sorting
                    });
                });

                // Set the hidden input's value to the serialized positions
                $('#positions').val(JSON.stringify(positions));

                // Submit the form
                this.submit();
            });
        });
    </script>
     
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('urlForm');
            const select = document.getElementById('urlSelect');

            select.addEventListener('change', function() {
                const selectedValue = select.value;

                if (selectedValue) {
                    // Construct the URL and redirect
                    const url = `{{ route('dash') }}?id=${selectedValue}`;
                    window.location.href = url;
                }
            });
        });

        // $(function() {
        //     dragula([document.getElementById('draggable-area')])
        //         .on('drag', function(e) {
        //             e.className = e.className.replace('card-moved', '');
        //         })
        //         .on('over', function(e, t) {
        //             t.className += ' card-over';
        //         })
        //         .on('out', function(e, t) {
        //             t.className = t.className.replace('card-over', '');
        //         })
        //         .on('drop', function() {
        //             $('#savePosition').show();
        //         });


        //     $('#positionForm').on('submit', function(event) {
        //         var positions = [];
        //         $('#draggable-area .draggable-items').each(function(index, element) {
        //             positions.push({
        //                 module_id: $(element).data('id'),
        //                 position: index
        //             });
        //         });
        //         $('#positions').val(JSON.stringify(positions));
        //     });
        // });
    </script>
    <script>
        $(document).ready(function() {

            $('#savePosition').hide();
            $('#customShow').hide();

            $(document).on('click', '#customAdd', function() {
                console.log('hey');
                $('#customShow').toggle();
            });


            $(document).on('click', '.clearSection', function() {
                var elementId = $(this).closest('.clearelement').data('id');
                console.log("first".elementId);

                function getUrlParameter(name) {
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(window.location.search);
                    return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }

                // Get the 'id' parameter from the URL or fallback to the value from the DOM element
                var layoutId = getUrlParameter('id') || $('#layout_id_val').val();
                console.log("yas".layoutId);
                $.ajax({
                    url: '{{ route('changeStatus') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        module_id: elementId,
                        layout_id: layoutId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
  <!--   <script>
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
    </script> -->
    <script>
    $(document).ready(function() {
        function applyIframeStyles(iframeId, styles) {
            $(iframeId).on('load', function() {
                var iframe = $(this)[0];
                var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

                // Inject custom CSS into the iframe
                var style = iframeDocument.createElement('style');
                style.type = 'text/css';
                style.innerHTML = styles; // Pass the styles as an argument

                // Append the style element to the iframe's head
                iframeDocument.head.appendChild(style);
            });
        }

        // Define the CSS styles as a variable
        var iframeStyles = `
            /* Hide header and aside elements */
            header, aside, footer { display: none !important; }
            
            .page-title { display: none !important; }
            .page-breadcrumb { padding: 0px 0px 0 0px !important; }
            
            .page-wrapper>.container-fluid, 
            .page-wrapper>.container-lg, 
            .page-wrapper>.container-md, 
            .page-wrapper>.container-sm, 
            .page-wrapper>.container-xl, 
            .page-wrapper>.container-xxl {
                padding: 0px;
            }
            
            .container-fluid {
                padding: 0px;
            }

            .table-custom #zero_config_info { float: none; }

            .paginate_button { display: inline-block !important; }

            /* Custom styles for specific classes */
            .threedottest { display: block !important; }
            .withoutthreedottest { display: none !important; }

            /* Adjust layout and overflow */
            #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar { display: none; }
            #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar { display: none; }
            #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper { margin-left: 10px; }
            #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper { padding-top: 10px; }

            /* Make iframe content scrollable */
            html, body {
                overflow: auto !important; /* Allow scrolling */
                margin: 0; /* Remove default margins */
                padding: 0; /* Remove default padding */
            }

            #scheduleSection1 { overflow: auto !important; }
        `;

        // Apply styles to all iframes
        applyIframeStyles('#customerIframe', iframeStyles);
        applyIframeStyles('#scheduleIframe', iframeStyles);
        applyIframeStyles('#technicianIframe', iframeStyles);
        applyIframeStyles('#assetsIframe', iframeStyles);
        applyIframeStyles('#paymentsIframe', iframeStyles);
        applyIframeStyles('#eventsIframe', iframeStyles);
        applyIframeStyles('#jobIframe', iframeStyles);
        applyIframeStyles('#messageIframe', iframeStyles);
        applyIframeStyles('#toolIframe', iframeStyles);
        applyIframeStyles('#fleetIframe', iframeStyles);
    });
</script>

@endsection
@endsection
