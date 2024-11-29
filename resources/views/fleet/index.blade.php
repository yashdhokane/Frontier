<!-- resources/views/clients/index.blade.php -->
@if (Route::currentRouteName() != 'dash')
    @extends('home')

    @section('content')
    @endif

    <div class="page-breadcrumb">
        <div class="row withoutthreedottest">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Vehicles</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                            <li class="breadcrumb-item"><a href="#">Vehicles</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 text-end px-4">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a href="{{ route('product.index') }}"
                        class="btn {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : 'btn-light-info text-info' }}">Parts</a>
                    <a href="{{ route('tool.index') }}"
                        class="btn {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : 'btn-light-info text-info' }}">Tools</a>
                    <a href="{{ route('vehicles') }}"
                        class="btn {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : 'btn-light-info text-info' }}">Vehicles</a>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button"
                            class="btn {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Assign
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item {{ Route::currentRouteName() === 'assign_product' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('assign_product') }}">Parts</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('assign_tool') }}">Tools</a>
                        </div>
                    </div>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop2" type="button"
                            class="btn {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Add New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                            <a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('product.createproduct') }}">Parts</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('tool.createtool') }}">Tools</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'addvehicle' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('addvehicle') }}">Vehicles</a>
                        </div>
                    </div>
                    <a href="{{ route('partCategory') }}"
                        class="btn {{ Route::currentRouteName() === 'partCategory' ? 'btn-info' : 'btn-light-info text-info' }}">Categories</a>
                </div>
            </div>
        </div>
    </div>

    @if (Session::has('success'))
        <div class="alert_wrap">
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container-fluid pt-2">
        <div class="card threedottest" style="display:none;">
            <div class="row card-body">
                <!-- Search Input on the Left -->
                <div class="col-6 align-self-center">

                    <input type="text" class="form-control" id="searchInput" placeholder="Search Vehicles"
                        onkeyup="filterTable()" />
                </div>

                <!-- Three Dot Dropdown on the Right -->
                <div class="col-6 align-self-center">
                    <div class="d-flex justify-content-end">
                        <!-- Dropdown Menu for Filters -->
                        <div class="dropdown dropstart">
                            <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-more-vertical feather-sm">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="12" cy="5" r="1"></circle>
                                    <circle cx="12" cy="19" r="1"></circle>
                                </svg>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <!-- Filters Section -->



                                <li>
                                    <a href="{{ route('product.index') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : '' }}">Parts</a>
                                </li>
                                <li>
                                    <a href="{{ route('tool.index') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : '' }}">Tools</a>
                                </li>
                                <li>
                                    <a href="{{ route('vehicles') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : '' }}">Vehicles</a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : '' }}"
                                        href="#">Assign</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_product' ? 'btn-info' : '' }}"
                                                href="{{ route('assign_product') }}">Parts</a></li>
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool' ? 'btn-info' : '' }}"
                                                href="{{ route('assign_tool') }}">Tools</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : '' }}"
                                        href="#">Add New</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct' ? 'btn-info' : '' }}"
                                                href="{{ route('product.createproduct') }}">Parts</a></li>
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : '' }}"
                                                href="{{ route('tool.createtool') }}">Tools</a></li>
                                        <li><a class="dropdown-item {{ Route::currentRouteName() === 'addvehicle' ? 'btn-info' : '' }}"
                                                href="{{ route('addvehicle') }}">Vehicles</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route('partCategory') }}"
                                        class="dropdown-item {{ Route::currentRouteName() === 'partCategory' ? 'btn-info' : '' }}">Categories</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a href="#." id="filterButton" class="dropdown-item">
                                        <i class="ri-filter-line"></i> Filters
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="filterDiv" class="card card-body shadow" style="display: none;">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="technician_filter" class="form-label">Technician</label>
                    <select class="form-select" name="technician" id="technician_filter">
                        <option value="">All</option>
                        @foreach ($technician as $tech)
                            <option value="{{ $tech->name }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="status_filter" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status_filter">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                {{-- <div class="col-md-12">
                                        <button class="btn btn-primary w-100" onclick="applyFilters()">Apply
                                            Filters</button>
                                    </div> --}}
            </div>
        </div>


        <div class="card card-border shadow">
            <div class="col-12">
                <div class="card-body">
                    <table id="zero_config00" class="table table-hover table-striped text-nowrap">

                        <div class="row withoutthreedottest">
                            <div class="col-md-3 mb-3">
                                <label for="technician_filter" class="form-label">Technician</label>
                                <select class="form-select" name="technician" id="technician_filter1">
                                    <option value="">All</option>
                                    @foreach ($technician as $tech)
                                        <option value="{{ $tech->name }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status_filter" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status_filter1">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                        </div>
                        <thead>

                            <tr>
                                <th>ID</th>
                                <th>Vehicle Name</th>
                                <th>Vehicle No.</th>
                                <th>Insurance</th>
                                <th>Technician Assigned</th>
                                <th>Last Modified</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="vehicle_table_body">
                            @foreach ($vehicle as $item)
                                <tr data-technician="{{ $item->technician->name ?? '' }}"
                                    data-status="{{ $item->status }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($item->vehicle_image)
                                                <img src="{{ asset('public/vehicle_image/' . $item->vehicle_image) }}"
                                                    alt="{{ $item->vehicle_name }}" class="rounded-circle"
                                                    width="45" />
                                            @else
                                                <img src="{{ asset('public/images/vehicle.jfif') }}"
                                                    alt="{{ $item->vehicle_name }}" class="rounded-circle"
                                                    width="45" />
                                            @endif
                                            <div class="ms-2">
                                                <div class="user-meta-info"><a
                                                        href="{{ route('fleet.fleetedit', ['id' => $item->vehicle_id]) }}">
                                                        <h6 class="user-name mb-0" data-name="name">
                                                            {{ $item->vehicle_name ?? '' }}</h6>
                                                    </a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->vehicle_no ?? '' }}</td>
                                    <td>
                                        <div>
                                            @if ($item->vehicle && $item->vehicle->valid_upto)
                                                <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                                    data-bs-target="#samedata-modal-{{ $item->vehicle_id }}"
                                                    data-id="{{ $item->vehicle_id }}">
                                                    Yes
                                                </button>
                                                <div class="modal fade" id="samedata-modal-{{ $item->vehicle_id }}"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel1">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header d-flex align-items-center">
                                                                <h4 class="modal-title" id="exampleModalLabel1">
                                                                    Insurance Details</h4>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body"
                                                                id="modal-body-content-{{ $item->vehicle_id }}">
                                                                <!-- Vehicle details will be loaded here -->
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-light-danger text-danger font-medium"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-link">

                                                    No
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $item->technician->name ?? null }}</td>
                                    <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('m-d-Y h:i:a') : null }}
                                    </td>
                                    <td style="text-transform: capitalize;">{{ $item->status ?? null }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                            </button>
                                            <div class="dropdown-menu ">
                                                @if ($item->vehicle && $item->vehicle->valid_upto ?? '')
                                                    <button type="button" class="btn dropdown-item"
                                                        data-id="{{ $item->vehicle_id }}" data-bs-toggle="modal"
                                                        data-bs-target="#samedata-modal-{{ $item->vehicle_id }}">
                                                        <i data-feather="eye" class="feather-sm me-2"></i>
                                                        View</button>
                                                @else
                                                    <span class="dropdown-item"></span>
                                                @endif
                                                @if ($item->status == 'active')
                                                    <a class="dropdown-item"
                                                        href="{{ url('inactive/fleet/' . $item->vehicle_id) }}">
                                                        <i data-feather="edit-2" class="feather-sm me-2"></i>
                                                        Inactive</a>
                                                @else
                                                    <a class="dropdown-item"
                                                        href="{{ url('active/fleet/' . $item->vehicle_id) }}">
                                                        <i data-feather="edit-2" class="feather-sm me-2"></i>
                                                        Active</a>
                                                @endif
                                                @if ($item->vehicle_id)
                                                    <a href="{{ route('fleet.fleetedit', ['id' => $item->vehicle_id]) }}"
                                                        class="text-dark pe-2 dropdown-item">
                                                        <i class="fa fa-edit edit-sm fill-white pe-2"></i> Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            $(document).ready(function() {
                $('button[data-bs-toggle="modal"]').on('click', function(event) {
                    var button = $(event.currentTarget); // Button that triggered the modal
                    var vehicleId = button.data('id'); // Extract info from data-* attributes
                    var modalId = '#samedata-modal-' + vehicleId;
                    var modalBodyId = '#modal-body-content-' + vehicleId;

                    // Make an AJAX call to fetch vehicle details
                    $.ajax({
                        url: '{{ route('fleet.vehicle.details') }}', // Update with your route
                        type: 'GET',
                        data: {
                            id: vehicleId
                        },
                        success: function(response) {
                            if (response.error) {
                                $(modalBodyId).html('<p class="text-danger">' + response.error +
                                    '</p>');
                            } else {
                                // Use the document URL provided in the response directly
                                var documentLink = response.document ? '<a href="' + response
                                    .document +
                                    '" target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf-o me-2"></i> Download PDF</a>' :
                                    '<span>No insurance copy available</span>';

                                var content = '<p>Name: ' + response.name + '</p>' +
                                    '<p>Valid Up To: ' + response.valid_upto + '</p>' +
                                    '<p>Premium: ' + response.premium + '</p>' +
                                    '<p>Cover: ' + response.cover + '</p>' +
                                    documentLink;
                                $(modalBodyId).html(content);
                            }
                        },
                        error: function(xhr) {
                            $(modalBodyId).html('<p class="text-danger">Error fetching data</p>');
                        }
                    });
                });
            });
        </script>



        <script>
            $('#zero_config00').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 25,
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const technicianFilter = document.getElementById('technician_filter');
                const statusFilter = document.getElementById('status_filter');
                const tableBody = document.getElementById('vehicle_table_body');

                function filterTable() {
                    const technician = technicianFilter.value.toLowerCase();
                    const status = statusFilter.value.toLowerCase();

                    Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                        const rowTechnician = row.getAttribute('data-technician').toLowerCase();
                        const rowStatus = row.getAttribute('data-status').toLowerCase();

                        const matchesTechnician = technician === '' || rowTechnician === technician;
                        const matchesStatus = status === '' || rowStatus === status;

                        if (matchesTechnician && matchesStatus) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                technicianFilter.addEventListener('change', filterTable);
                statusFilter.addEventListener('change', filterTable);

                // Call filterTable on page load to apply initial filter if any
                filterTable();
            });
        </script>

        <script>
            function filterTable() {
                var input = document.getElementById("searchInput");
                var filter = input.value.toLowerCase();
                var table = document.getElementById("zero_config00");
                var rows = table.getElementsByTagName("tr");

                for (var i = 1; i < rows.length; i++) {
                    var row = rows[i];
                    var cells = row.getElementsByTagName("td");
                    var match = false;

                    for (var j = 0; j < cells.length; j++) {
                        var cell = cells[j];
                        if (cell) {
                            if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                                match = true;
                                break;
                            }
                        }
                    }

                    if (match) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            }

            function applyFilters() {
                var technicianFilter = document.getElementById("technician_filter").value.toLowerCase();
                var statusFilter = document.getElementById("status_filter").value.toLowerCase();
                var table = document.getElementById("zero_config00");
                var rows = table.getElementsByTagName("tr");

                for (var i = 1; i < rows.length; i++) {
                    var row = rows[i];
                    var technician = row.getAttribute("data-technician").toLowerCase();
                    var status = row.getAttribute("data-status").toLowerCase();
                    var showRow = true;

                    if (technicianFilter && technician !== technicianFilter) {
                        showRow = false;
                    }

                    if (statusFilter && status !== statusFilter) {
                        showRow = false;
                    }

                    if (showRow) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            }
        </script>

        <script>
            // Wait until the DOM is fully loaded
            document.addEventListener("DOMContentLoaded", function() {
                // Get the filter button and the filter div
                const filterButton = document.getElementById('filterButton');
                const filterDiv = document.getElementById('filterDiv');

                // Add a click event listener to the filter button
                filterButton.addEventListener('click', function() {
                    // Toggle the display of the filter div
                    if (filterDiv.style.display === 'none' || filterDiv.style.display === '') {
                        filterDiv.style.display = 'block'; // Show the filter section
                    } else {
                        filterDiv.style.display = 'none'; // Hide the filter section
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.dropdown-submenu .dropdown-toggle').on("click", function(e) {
                    var $subMenu = $(this).next('.dropdown-menu');
                    if (!$subMenu.hasClass('show')) {
                        $('.dropdown-menu .show').removeClass('show');
                        $subMenu.addClass('show');
                    } else {
                        $subMenu.removeClass('show');
                    }
                    e.stopPropagation();
                });

                // Ensure dropdown closes when clicking outside of it
                $(document).on("click", function() {
                    $('.dropdown-menu .show').removeClass('show');
                });
            });
        </script>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const technicianFilter = document.getElementById('technician_filter1');
            const statusFilter = document.getElementById('status_filter1');
            const tableBody = document.getElementById('vehicle_table_body');

            function filterTable() {
                const technician = technicianFilter.value.toLowerCase();
                const status = statusFilter.value.toLowerCase();

                Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                    const rowTechnician = row.getAttribute('data-technician').toLowerCase();
                    const rowStatus = row.getAttribute('data-status').toLowerCase();

                    const matchesTechnician = technician === '' || rowTechnician === technician;
                    const matchesStatus = status === '' || rowStatus === status;

                    if (matchesTechnician && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            technicianFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);

            // Call filterTable on page load to apply initial filter if any
            filterTable();
        });
    </script>
    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
