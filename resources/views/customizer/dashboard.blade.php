@extends('home')

@section('content')

    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between pb-2">
                    <h4 class="mb-3 page-title text-info fw-bold">{{ $layout->layout_name ?? null }}
                        <a href="#" class="edit-layout" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fa fa-edit align-top fs-1 text-danger"></i>
                        </a>
                    </h4>
                    <form id="urlForm" class="d-flex" action="{{ route('dash') }}" method="GET">
                        <select id="urlSelect" name="id" class="form-select">
                            <option value="">--Select a Layout--</option>
                            @foreach ($layoutList as $value)
                                <option value="{{ $value->id }}">{{ $value->layout_name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" id="showButton" class="btn btn-info ms-2">Show</button>
                    </form>

                    <!-- Edit Layout Name Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editForm" action="{{ route('updateLayoutName', ['id' => $layout->id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Layout Name</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="editLayoutId" name="id" value="">
                                        <div class="mb-3">
                                            <label for="editLayoutName" class="form-label">Layout Name</label>
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

                    <form action="{{ route('update.status') }}" method="POST" class="d-flex">
                        @csrf
                        @php
                            $titles = [
                                1 => 'Upcoming Job',
                                2 => 'Open invoices',
                                3 => 'Paid invoices',
                                4 => 'Stats',
                                5 => 'Title 5',
                                6 => 'Title 6',
                                7 => 'Title 7',
                                8 => 'Title 8',
                                9 => 'Title 9',
                                10 => 'Title 10',
                            ];
                        @endphp
                        <select name="status" class="form-control select2" required>
                            @if ($variable->isEmpty())
                                <option value="">All section already exists</option>
                            @else
                                <option value="">Select to add section</option>
                                @foreach ($variable as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $titles[$value->element_id] ?? 'All section already exists' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="submit" class="btn btn-success mx-2">Add</button>
                    </form>
                </div>
                <form id="positionForm" method="POST" action="{{ route('savePositions') }}">
                    @csrf
                    <input type="hidden" name="positions" id="positions">
                    <div class="row draggable-cards" id="draggable-area">

                        @foreach ($cardPositions as $cardPosition)
                            @if ($cardPosition->layout_id == 1 && $cardPosition->element_id == 1)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Upcoming Job </h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
                                                <table id="" class="table table-bordered text-nowrap">
                                                    <thead class="uppercase">
                                                        <tr>
                                                            <th>Jobs Details</th>
                                                            <th>Customer</th>
                                                            <th>Technician</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($job as $item)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ url('tickets/' . $item->JobModel->id) }}"
                                                                        class="font-medium link">{{ $item->JobModel->job_title ?? null }}</a><br />
                                                                    {{ $item->JobModel->description ?? null }}
                                                                </td>
                                                                <td>{{ $item->JobModel->user->name ?? null }}</td>
                                                                <td>{{ $item->technician->name ?? null }}</td>
                                                                <td>
                                                                    @if ($item && $item->start_date_time)
                                                                        <div class="font-medium link ft12">
                                                                            {{ $convertDateToTimezone($item->start_date_time ?? null) }}
                                                                        </div>
                                                                    @else
                                                                        <div></div>
                                                                    @endif
                                                                    <div class="ft12">
                                                                        {{ $convertTimeToTimezone($item->start_date_time ?? null, 'H:i:a') }}
                                                                        to
                                                                        {{ $convertTimeToTimezone($item->end_date_time ?? null, 'H:i:a') }}
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
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 2)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Open invoices</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
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
                                                                <td><a
                                                                        href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                                                </td>

                                                                <td>{{ $item->user->name ?? null }}</td>
                                                                <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                                                <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}
                                                                </td>
                                                                <td>{{ $item->due_date ?? null }}</td>
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
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 3)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-success d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Paid invoices</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
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
                                                        @foreach ($paymentopen as $index => $item)
                                                            <tr>
                                                                <td><a
                                                                        href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                                                </td>

                                                                <td>{{ $item->user->name ?? null }}</td>
                                                                <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                                                <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}
                                                                </td>
                                                                <td>{{ $item->due_date ?? null }}</td>
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
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 4)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Stats</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <div class="text-white bg-primary rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-group-line"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $customerCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Customers</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="text-white bg-warning rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-contacts-line"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $technicianCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Technicians
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="text-white bg-success rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-admin-line"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $dispatcherCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Dispatchers
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-white bg-danger rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-admin-fill"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $adminCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Admin</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 5)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-primary d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 5</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 5</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 5</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 6)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 6</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 6</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 6</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 7)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 7</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 7</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 7</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 2 && $cardPosition->element_id == 8)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 8</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 8</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 8</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 2 && $cardPosition->element_id == 9)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 9</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 9</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 9</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 2 && $cardPosition->element_id == 10)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 10</h4>
                                            <button class="btn btn-light mx-2 clearSection"
                                                data-element-id="{{ $cardPosition->element_id }}">X</button>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 10</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 10</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    @if ($layout->added_by == auth()->user()->id)
                        <button type="submit" class="btn btn-primary mt-3">Save Positions</button>
                    @endif
                </form>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>

    <div class="chat-windows"></div>

@section('script')
    <style>
        .gu-mirror {
            opacity: 0.6;
            position: fixed;
            z-index: 9999;
            pointer-events: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('urlForm');
            const select = document.getElementById('urlSelect');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // prevent the form from submitting normally

                // Get the selected value from the dropdown
                const selectedValue = select.value;

                if (selectedValue) {
                    // Construct the URL and redirect
                    const url = `{{ route('dash') }}?id=${selectedValue}`;
                    window.location.href = url;
                }
            });
        });
    </script>
    </script>
    <script>
        $(function() {
            dragula([document.getElementById('draggable-area')])
                .on('drag', function(e) {
                    e.className = e.className.replace('card-moved', '');
                })
                .on('over', function(e, t) {
                    t.className += ' card-over';
                })
                .on('out', function(e, t) {
                    t.className = t.className.replace('card-over', '');
                });


            $('#positionForm').on('submit', function(event) {
                var positions = [];
                $('#draggable-area .col-md-6').each(function(index, element) {
                    positions.push({
                        element_id: $(element).data('id'),
                        position: index
                    });
                });
                $('#positions').val(JSON.stringify(positions));
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.clearSection').on('click', function() {
                var elementId = $(this).data('element-id');

                $.ajax({
                    url: '{{ route('changeStatus') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        element_id: elementId
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
@endsection

@endsection
