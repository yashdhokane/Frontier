@extends('home')
<!--<meta name="csrf-token" content="{{ csrf_token() }}">-->

@section('content')
<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-2 align-self-center">
            <h4 class="page-title">Lead Source</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lead Source </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-3 align-self-center">

            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#samedata-modal"
                data-bs-whatever="@mdo"><i class="ri-folder-add-line"></i> Add New</button>
            <div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="exampleModalLabel1">Add Lead Source</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ url('/store/leadsource') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="lead-source" class="control-label bold mb5">Lead Source:</label>
                                    <input type="text" class="form-control" name="source_name" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger text-danger font-medium"
                                    data-bs-dismiss="modal">Close</button>
                                <!-- <button type="submit" class="btn btn-success">Save</button> -->
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        @include('header-top-nav.settings-nav')
    </div>
</div>
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<!-- -------------------------------------------------------------- -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->
<!-- -------------------------------------------------------------- -->
<!-- Container fluid  -->
<!-- -------------------------------------------------------------- -->
<div class="container-fluid pt-2">

    <div class="row">
        <div class="col-9">
            <div class="card card-border shadow">
                <div class="card-body">
                    <table id="default_order" class="table table-striped table-bordered display text-nowrap"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>Lead Source</th>
                                <th>Added By</th>
                                <th>Date Added</th>
                                <th>Date Modified</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leadSources as $leadSource)
                            <tr>
                                <td>
                                    <p class="source_name">{{ $leadSource->source_name }}</p>
                                </td>
                                <td>{{ optional($leadSource->addedByUser)->name }}</td>
                                <td>{{ $leadSource->created_at->format('m-d-Y') }}</td>
                                <td>{{ $convertDateToTimezone($leadSource->updated_at) }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#samedata-modal2"
                                        data-lead-source-id="{{ $leadSource->source_id }}"
                                        data-lead-source-name="{{ $leadSource->source_name }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ url('delete/leadsource/' . $leadSource->source_id) }}"
                                        onclick="confirm('Are you sure you want to delete Online Booking?')"><button
                                            type="button" class="btn btn-danger btn-sm delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>Lead Source</th>
                            <th>Added By</th>
                            <th>Date Added</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>


        <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="exampleModalLabel1">Edit Lead Source
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('edit/leadsource') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="lead-source1" class="control-label bold mb5">Lead
                                    Source:</label>
                                <input type="text" class="form-control" id="lead-source1" name="source_name" value="" />
                                <input type="hidden" id="lead-source-id" name="source_id" value="" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-danger text-danger font-medium"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <div class="col-3">



            <div class="col-md-12 ">
                <div class="card card-body card-border shadow">
                    <h4 class="card-title">Managing lead sources</h4>
                    <p class="card-text pt-2">A lead source helps you track how customers found your business.
                        Google, Yelp, and Facebook are a few examples of common lead sources.</p>
                    <h5 class="card-title  mt-3">Why track lead sources?</h5>
                    <p class="card-text pt-2">Knowing where jobs come from is key for making smart marketing and
                        advertising decisions. Invest more on sources that bring in lots of good jobs.</p>
                    <h5 class="card-title  mt-3">How do I use lead sources?</h5>
                    <p class="card-text pt-2">You can track the lead source for any customer or job. Call tracking
                        will automatically add lead sources based on which phone number a customer calls.</p>
                    <a href="#" class="card-link">Need more help?</a>
                </div>
            </div>

        </div>
    </div>

    <!-- -------------------------------------------------------------- -->
    <!-- Recent comment and chats -->
    <!-- -------------------------------------------------------------- -->
</div>
@section('script')
<script>
    if ($.fn.DataTable.isDataTable('#default_order')) {
            $('#default_order').DataTable().destroy();
        }
        $('#default_order').DataTable({
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
        });
        document.addEventListener('DOMContentLoaded', function() {
            var dataTable = $('#default_order').DataTable();
            $('#saveLeadSourceBtn').on('click', function() {
                var leadSource = $('#lead-source').val();
                $.ajax({
                    url: '{{ route('lead-source-add') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        lead_source: leadSource
                    },
                    success: function(response) {
                        $('#samedata-modal').modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });

            let row;
            $(document).on('click', '.edit-btn', function() {
                var leadSourceId = $(this).data('lead-source-id');
                var leadSourceName = $(this).data('lead-source-name');
                row = $(this).closest('tr');
                $('#lead-source-id').val(leadSourceId);
                $('#lead-source1').val(leadSourceName);

                $('#samedata-modal2').modal('show');
            });


        });
</script>
@endsection
@endsection