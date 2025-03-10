@extends('home')
<!--<meta name="csrf-token" content="{{ csrf_token() }}">-->

@section('content')

<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-2 align-self-center">
            <h4 class="page-title">Job Fields</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Job Fields </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-3 align-self-center">

            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#samedata-modal"
                data-bs-whatever="@mdo"><i class="ri-file-add-line"></i> Add New</button>
            <div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="exampleModalLabel1">Add Job Field</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ url('/store/jobfield') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="jobfields" class="control-label bold mb5">Job
                                        Field:</label>
                                    <input type="text" class="form-control" id="jobfields" name="field_name" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger text-danger font-medium"
                                    data-bs-dismiss="modal">Close</button>
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
                    <table id="default_order" class="table table-hover table-striped table-bordered display text-nowrap"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>Job Field</th>
                                <th>Added By</th>
                                <th>Date Added</th>
                                <th>Date Modified</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($JobfieldsList as $Jobfields)
                            <tr>
                                <td>
                                    <p class="field_name">{{ $Jobfields->field_name }}</p>
                                </td>
                                <td>{{ optional($Jobfields->addedByUser)->name }}</td>
                                <td>{{ $Jobfields->created_at->format('m-d-Y') }}</td>
                                <td>{{ $convertDateToTimezone($Jobfields->updated_at) }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#samedata-modal2"
                                        data-job-fields-id="{{ $Jobfields->field_id }}"
                                        data-job-fields-name="{{ $Jobfields->field_name }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ url('/delete/jobfield/' . $Jobfields->field_id) }}"
                                        onclick="confirm('Are you sure you want to delete job field?')"><button
                                            type="button" class="btn btn-danger btn-sm delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>Job Field</th>
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

        <!-- update model view code start -->
        <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="exampleModalLabel1">Edit Field</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ url('/edit/jobfield') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="field-name" class="control-label bold mb5">Field Name:</label>
                                <input type="text" class="form-control" id="jobfields1" name="field_name">
                            </div>
                            <input type="hidden" id="job-fields-id" name="field_id" value="">

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

        <!-- update model view code end -->

        <div class="col-3">




            <div class="col-md-12 ">
                <div class="card-body card card-border shadow">
                    <h4 class="card-title">Why use job fields?</h4>
                    <p class="card-text pt-2">Fields allow you to consistently capture and
                        report on details about the work you're doing.</p>
                    <p class="card-text pt-2">These fields are visible on your jobs and
                        estimates. You can customize the field values from this page so they
                        make sense for your business needs.</p>
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
        // edit query in ajax
        let row;
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.edit-btn', function() {
                var jobFieldsId = $(this).data('job-fields-id');
                var jobFieldsName = $(this).data('job-fields-name');
                row = $(this).closest('tr');

                // console.log(row);
                $('#job-fields-id').val(jobFieldsId);
                $('#jobfields1').val(jobFieldsName);
            });
        });
</script>
@endsection
@endsection