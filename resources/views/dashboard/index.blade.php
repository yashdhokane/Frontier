@extends('home')

@section('content')


    <link rel="stylesheet" href="{{ url('public/admin/dashboard/style.css') }}">



    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        /* CSS for smooth transitions */
        .box {
            transition: all 0.3s ease;
        }

        .draggable-items1 {
            cursor: move;
            transition: cursor 0.3s ease-in-out;
        }

        .draggable-items1:active {
            cursor: move;
            transition: cursor 0.3s ease-in-out;
        }

        /* Optionally, adding a transform on hover for more smoothness */
        .draggable-items1:hover {
            //transform: scale(1.02); /* Slightly enlarge when hovering */
            // transition: transform 0.3s ease; /* Smooth transition for the hover effect */
        }


        .expanded {
            order: -1 !important;
            /* Move to the top */
        }

        .original {
            order: 0;
        }

        .box:focus-within {
            /* border: 2px solid #007bff; */
            /* Add blue border when focused */
            /* box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); */
            /* Add shadow effect */
            /* outline: none; */
            /* Remove default outline */
        }
    </style>
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->

        <div class="d-flex justify-content-between pb-2">
            {{-- <h4 class="mb-3 page-title fw-bold">
                {{ $layout->layout_name ?? null }}
                @if ($layout->added_by == auth()->user()->id && $layout->is_editable == 'yes')
                    <a href="#" class="edit-layout" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="fa fa-edit align-top fs-1 text-danger"></i>
                    </a>
                @endif
            </h4>  --}}
            @include('widgets.edit_model')
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
                    {{--   <select id="urlSelect" name="id" class="form-select">
                        <option value="">--Select Dashboard--</option>
                        @foreach ($layoutList as $value)
                            <option value="{{ $value->id }}">{{ $value->layout_name }}</option>
                        @endforeach
                    </select> --}}
                    <select id="urlSelect" name="id" class="form-select">
                        <option value="">--Select Dashboard--</option>
                        @foreach ($layoutList as $index => $value)
                            <option value="{{ $value->id }}"
                                {{ request('id') == $value->id || (!request('id') && $index == 0) ? 'selected' : '' }}>
                                {{ $value->layout_name }}
                            </option>
                        @endforeach

                    </select>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const urlParams = new URLSearchParams(window.location.search);
                            const selectedId = urlParams.get('id'); // Get the 'id' from URL query params

                            if (selectedId) {
                                const selectElement = document.getElementById('urlSelect');
                                selectElement.value = selectedId; // Set the value in the select box
                            }
                        });
                    </script>

                </form>

                @if ($layout->added_by == auth()->user()->id)
                    <!-- Your Blade template form -->
                    <form action="{{ route('update.status') }}" method="POST" id="ajaxForm" class="d-flex pe-5">
                        @csrf
                        <input type="hidden" name="layout_id" id="layout_id_val" value="{{ $layout->id }}">
                        <select name="module_id" class="form-select" required>
                            @if ($variable->isEmpty() && $List->isEmpty())
                                <option value="">All sections already exist</option>
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
                        <button type="button" class="btn btn-info mx-2" id="submitButton">Add</button>
                    </form>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            function setupExpandCollapse() {
                                const boxes = document.querySelectorAll('.box');

                                // Iterate over each box
                                boxes.forEach(box => {
                                    const expandBtn = box.querySelector('.expand-btn');

                                    // Store the original index (position) of the box when the page loads
                                    const originalIndex = Array.from(box.parentNode.children).indexOf(box);

                                    expandBtn.addEventListener('click', function() {
                                        const isExpanded = box.classList.contains('expanded');

                                        // Reset all other boxes to their original state
                                        boxes.forEach(b => {
                                            if (b !== box) {
                                                b.classList.remove('expanded');
                                                b.classList.add('original');
                                                b.classList.remove('col-md-12');
                                                b.classList.add('col-md-4');
                                                const btn = b.querySelector('.expand-btn');
                                                btn.textContent = 'Expand';
                                            }
                                        });

                                        if (!isExpanded) {
                                            // Expand the clicked box
                                            box.classList.add('expanded');
                                            box.classList.remove('original');
                                            box.classList.remove('col-md-4');
                                            box.classList.add('col-md-12');
                                            expandBtn.textContent = 'Less';

                                            // Scroll into view and focus on the expanded section
                                            box.scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'start'
                                            });

                                            // Optionally focus on the expand button for accessibility
                                            expandBtn.focus();
                                        } else {
                                            // Collapse the box back to original size and position
                                            box.classList.remove('expanded');
                                            box.classList.add('original');
                                            box.classList.remove('col-md-12');
                                            box.classList.add('col-md-4');
                                            expandBtn.textContent = 'Expand';

                                            // Scroll the collapsed box back into view
                                            box.scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'start'
                                            });

                                            // Return the box to its original position in the container
                                            const container = box.parentNode;
                                            container.insertBefore(box, container.children[originalIndex]);

                                            // Focus on the collapsed section
                                            expandBtn.focus();
                                        }
                                    });
                                });
                            }

                            // Initial setup for expand/collapse functionality
                            setupExpandCollapse();

                            // Rebind the expand/collapse functionality after dynamically adding sections
                            $(document).on('click', '#submitButton', function() {
                                const selectedModuleId = $('select[name="module_id"]').val();

                                // Count the number of boxes inside the #box-container
                                const boxCount = $('#box-container .box').length;

                                // Check if the selected module_id is 18 and box count is less than 9
                                if (selectedModuleId == 18 && boxCount < 9) {
                                    // Show an alert if the condition is met
                                    alert('Please add at least 9 modules before proceeding.');
                                    return; // Stop the form submission
                                }

                                // Assuming that new HTML is appended to the #box-container after the form submission
                                $.ajax({
                                    url: $('#ajaxForm').attr('action'),
                                    type: 'POST',
                                    data: $('#ajaxForm').serialize(),
                                    success: function(response) {
                                        if (response.success) {
                                            // Append the new HTML content
                                            $('#box-container').append(response.html);

                                            // Alert message
                                            alert(response.message);

                                            // Reinitialize the expand/collapse script for newly added content
                                            setupExpandCollapse();

                                            // Focus on the expand button of the newly added module
                                            const newModule = $('#box-container .box')
                                                .last(); // Get the newly added box
                                            const newExpandBtn = newModule.find(
                                                '.expand-btn'); // Find the expand button inside the new box
                                            newExpandBtn.focus(); // Focus on the expand button

                                            // Scroll to the newly added box
                                            newModule[0].scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'start'
                                            });
                                          // **Dynamically update dropdown**
            let dropdown = $('select[name="module_id"]');
            dropdown.empty(); // Clear existing options
console.log(response.variable);
console.log(response.List);
            // Check if variable and List are empty
           if (response.variable.length === 0 && response.List.length === 0) {
                dropdown.append('<option value="">All sections already exist</option>');
            } else {
                dropdown.append('<option value="">Select to add section</option>');

                // Append new options from `variable`
                $.each(response.variable, function(index, value) {
                    dropdown.append('<option value="' + value.module_id + '">' + 
                                    (value.module_list ? value.module_list.module_name : 'Unknown') + 
                                    '</option>');
                });

                // Append new options from `List`
                $.each(response.List, function(index, item) {
                    dropdown.append('<option value="' + item.module_id + '">' + 
                                    (item.module_name ? item.module_name : 'Unknown') + 
                                    '</option>');
                });
            }
        } else {
            alert(response.message);
        }
                                    },
                                    error: function(xhr) {
                                        alert('An error occurred. Please try again.');
                                    }
                                });
                            });
                        });
                    </script>









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
                                data-original-index="{{ $index }}" tabindex="0"
                                data-id="{{ $cardPosition->module_id }}">
                                <div class="card card-border card-shadow" style="">
                                    <!-- Flex container for module name and button -->
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $cardPosition->ModuleList->module_name }}</strong>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-link expand-btn">Expand</button>
                                            <div style="float: right;">
                                                @if ($layout->added_by == auth()->user()->id)
                                                    <button class="btn btn-light mx-2 clearSection"
                                                        data-element-id="{{ $cardPosition->element_id }}"
                                                        data-id="{{ $cardPosition->module_id }}">X</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body clearelement " data-id="{{ $cardPosition->module_id }}">
                                        @include('widgets.' . $cardPosition->ModuleList->module_code)
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4 mb-3 box draggable-items1" id="box-{{ $index }}"
                                data-original-index="{{ $index }}" tabindex="0"
                                data-id="{{ $cardPosition->module_id }}">
                                <div class="card card-border card-shadow">
                                    <!-- Flex container for module name and button -->
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $cardPosition->ModuleList->module_name }}</strong>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-link expand-btn">Expand</button>
                                            <div style="float: right;">
                                                @if ($layout->added_by == auth()->user()->id)
                                                    <button class="btn btn-light mx-2 clearSection"
                                                        data-element-id="{{ $cardPosition->element_id }}"
                                                        data-id="{{ $cardPosition->module_id }}">X</button>
                                                @endif
                                            </div>
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
            // const boxes = document.querySelectorAll('.box');

            // boxes.forEach(box => {
            //     const expandBtn = box.querySelector('.expand-btn');

            //     expandBtn.addEventListener('click', function() {
            //         const isExpanded = box.classList.contains('expanded');

            //         // Reset all boxes to original state
            //         boxes.forEach(b => {
            //             b.classList.remove('expanded');
            //             b.classList.add('original');
            //             b.classList.remove('col-md-12');
            //             b.classList.add('col-md-4');
            //             const btn = b.querySelector('.expand-btn');
            //             btn.textContent = 'Expand';
            //         });

            //         if (!isExpanded) {
            //             // Expand this box
            //             box.classList.add('expanded');
            //             box.classList.remove('original');
            //             box.classList.remove('col-md-4');
            //             box.classList.add('col-md-12');
            //             expandBtn.textContent = 'Less';

            //             // Scroll into view and focus on expanded section
            //             box.scrollIntoView({
            //                 behavior: 'smooth',
            //                 block: 'start'
            //             });

            //             // Optionally focus on the expand button for accessibility
            //             expandBtn.focus();
            //         } else {
            //             // Focus on the collapsed section after restoring original state
            //             box.scrollIntoView({
            //                 behavior: 'smooth',
            //                 block: 'start'
            //             });
            //             expandBtn.focus();
            //         }
            //     });
            // });

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
                var elementId = $(this).data('id');
                // console.log("first".elementId);

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
            /* Hide header and aside elements*/
            header, aside, footer { display: none !important; }
             /* card-body & rapper padding for cardfor 4 md remove for 12
            .page-wrapper {padding:0px !important; }
            .card-body {padding:1px !important; } */
            /* remove for col 12 this for 4 customer card*/

            .search-breadcrumb{padding:0px !important; }
             #usersTable {
             padding: 0;
             margin: 0;
             font-weight: normal;
             }

        #usersTable thead,
        #usersTable tbody,
        #usersTable th,
        #usersTable td {
             padding: 0;
             margin: 0;
             font-weight: normal;
        }
         #usersTable thead th {
          font-weight: 700;
         }

         /*JOB & TECHNICIAN IFRAME */

        #zero_config thead,
        #zero_config tbody,
        #zero_config th,
        #zero_config td {
             padding: 1px;
             margin: 1px;
             font-weight: normal;
        }
         #zero_config thead th {
          font-weight: 700;
         }
         .table-responsive .dataTables_wrapper .dataTables_length select {
             display:none !important;
             }
         #zero_config_length {
        display: none;
     }
     #zero_config_filter
     {
        display: none;
     }








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
            #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper { margin-left: 0px; }
            #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper { padding-top: 0px; }
             #main-wrapper[data-layout="vertical"][data-sidebartype="mini-sidebar"] .page-wrapper {
             margin-left: 0px !important;
             }

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

    //
    <script>
        //     $(document).ready(function() {
        //         function applyIframeStyles(iframeId, cssUrl) {
        //             $(iframeId).on('load', function() {
        //                 var iframe = $(this)[0];
        //                 var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

        //                 // Create a link element for external CSS specifically for the iframe
        //                 var link = iframeDocument.createElement('link');
        //                 link.rel = 'stylesheet';
        //                 link.type = 'text/css';
        //                 link.href = cssUrl; // Pass the iframe-specific CSS URL

        //                 // Append the link element to the iframe's head
        //                 iframeDocument.head.appendChild(link);
        //             });
        //         }

        //         // Define the external CSS URL for iframe styling only
        //         var iframeCssUrl = "{{ url('public/admin/dashboard/iframe-style.css') }}";

        //         // Apply the external iframe-specific CSS to all iframes
        //         applyIframeStyles('#customerIframe', iframeCssUrl);
        //         applyIframeStyles('#scheduleIframe', iframeCssUrl);
        //         applyIframeStyles('#technicianIframe', iframeCssUrl);
        //         applyIframeStyles('#assetsIframe', iframeCssUrl);
        //         applyIframeStyles('#paymentsIframe', iframeCssUrl);
        //         applyIframeStyles('#eventsIframe', iframeCssUrl);
        //         applyIframeStyles('#jobIframe', iframeCssUrl);
        //         applyIframeStyles('#messageIframe', iframeCssUrl);
        //         applyIframeStyles('#toolIframe', iframeCssUrl);
        //         applyIframeStyles('#fleetIframe', iframeCssUrl);
        //     });
        //
    </script>
@endsection
@endsection
