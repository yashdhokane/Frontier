@extends('home')

@section('content')
    <style>
        .newLayout {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .draggable-items {
            padding: 10px;
            text-align: center;
            width: fit-content;
            /* Set a fixed width for masonry items */
            box-sizing: border-box;
        }

        .gu-mirror {
            opacity: 0.6;
            position: fixed;
            z-index: 9999;
            pointer-events: none;
        }
    </style>
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <div class="col-12">
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

                        <!-- Edit Layout Name Modal -->
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
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
                                                    name="layout_name" value="{{ $layout->layout_name ?? null }}"
                                                    required>
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
                    <div class="newLayout draggable-cards" id="draggable-area">

                        @foreach ($cardPositions as $cardPosition)
                            {{-- first layout --}}
                            @if ($cardPosition->module_id == 1)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.upcoming-jobs')
                                </div>
                            @elseif($cardPosition->module_id == 2)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.open-invoices')
                                </div>
                            @elseif($cardPosition->module_id == 3)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.paid-invoices')
                                </div>
                            @elseif($cardPosition->module_id == 4)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.stats')
                                </div>
                            @elseif($cardPosition->module_id == 5)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.jobs-manufacturer')
                                </div>
                            @elseif($cardPosition->module_id == 6)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.jobs-service-type')
                                </div>
                            @elseif($cardPosition->module_id == 7)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.quick-links')
                                </div>
                            @elseif($cardPosition->module_id == 8)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.my-activity')
                                </div>
                            @elseif($cardPosition->module_id == 9)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.my-notifications')
                                </div>
                            @elseif($cardPosition->module_id == 10)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.active-technicians')
                                </div>
                            @elseif($cardPosition->module_id == 11)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    @include('widgets.top-customers')
                                </div>

                                {{-- end first layout --}}
                            @elseif($cardPosition->module_id == 12)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 8</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-module-id="{{ $cardPosition->module_id }}">X</button>
                                            @endif
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
                            @elseif($cardPosition->module_id == 13)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 9</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-module-id="{{ $cardPosition->module_id }}">X</button>
                                            @endif
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
                            @elseif($cardPosition->module_id == 14)
                                <div class="col-md-6 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 10</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-module-id="{{ $cardPosition->module_id }}">X</button>
                                            @endif
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
                            @elseif($cardPosition->module_id == 15)
                                <div class="col-md-8 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 8</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-module-id="{{ $cardPosition->module_id }}">X</button>
                                            @endif
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
                            @elseif($cardPosition->module_id == 16)
                                <div class="col-md-12 col-sm-12 draggable-items"
                                    data-id="{{ $cardPosition->module_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 9</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-module-id="{{ $cardPosition->module_id }}">X</button>
                                            @endif
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
                            @elseif($cardPosition->module_id == 17)
                                <div class="col-md-4 col-sm-12 draggable-items" data-id="{{ $cardPosition->module_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 10</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-module-id="{{ $cardPosition->module_id }}">X</button>
                                            @endif
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
                        <button type="submit" class="btn btn-primary mt-3" id="savePosition">Save Positions</button>
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
                })
                .on('drop', function() {
                    $('#savePosition').show();
                });


            $('#positionForm').on('submit', function(event) {
                var positions = [];
                $('#draggable-area .draggable-items').each(function(index, element) {
                    positions.push({
                        module_id: $(element).data('id'),
                        position: index
                    });
                });
                $('#positions').val(JSON.stringify(positions));
            });
        });
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
                var elementId = $(this).closest('.draggable-items').data('id');

                function getUrlParameter(name) {
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(window.location.search);
                    return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }

                // Get the 'id' parameter from the URL or fallback to the value from the DOM element
                var layoutId = getUrlParameter('id') || $('#layout_id_val').val();
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
@endsection

@endsection
