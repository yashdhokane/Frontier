@extends('home')
@section('content')

    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->


        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Sticky Notes</h4>
                </div>
                <div class="col-7 align-self-center">
                    <div class="d-flex no-block justify-content-end align-items-center">
                        <div class="me-2">
                            <a href="javascript:void(0)" id="btn-add-contact" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#add-contact"><i class=" fas fa-user-plus "></i> Add Sticky Notes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Popup Model  1 latest -->
        <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">Add Sticky Notes</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            fdprocessedid="k7jfjv"></button>
                    </div>
                    <form action="{{ route('stickynotes.store') }}" method="post" class="form-horizontal form-material"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="col-md-12 mb-4">
                                    <label for="" class="my-2"> Color Code </label>
                                    <select name="color_code" class="form-control" id="">
                                        @foreach ($color as $value)
                                            <option value="{{ $value->color_code }}" style="background: {{ $value->color_code }};">
                                                {{ $value->color_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="">
                                        <label for="" class="my-2">Note </label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                Save
                            </button>
                            <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">
                                Cancel
                            </button>

                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- End Popup Model 1 latest-->

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}

            </div>
        @endif
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-custom">
                            <table id="zero_config" class="table table-hover table-striped text-nowrap" data-paging="true"
                                data-paging-size="7">

                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Note</th>
                                        <th>Color Code</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($note as $item)
                                        <tr>
                                            <td>{{ $item->user->name ?? null }} </td>
                                            <td>{{ $item->note ?? null }} </td>
                                            <td class="ucfirst">{{ $item->color_code ?? null }} </td>
                                            <td>
                                                {{ $item->created_at ?? null }}
                                            </td>
                                            <td class="action footable-last-visible" style="display: table-cell;">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-light-primary text-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ url('sticky-notes-edit/' . $item->note_id) }}"><i data-feather="edit"
                                                                class="feather-sm me-2"></i> Edit</a>
                                                        <a class="dropdown-item"
                                                            href="{{ url('note/delete/' . $item->note_id) }}"><i
                                                                data-feather="trash" class="feather-sm me-2"></i> Delete</a>

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
        </div>


    </div>

@stop
