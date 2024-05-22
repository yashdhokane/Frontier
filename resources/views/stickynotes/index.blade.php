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
                                            <option value="{{ $value->color_code }}"
                                                style="background: {{ $value->color_code }};">
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
        <div class="row m-2">

            @foreach ($note as $item)
                <div class="col-sm-3 col-md-3 my-3">
                    <div class="card border rounded p-3 h-100 justify-content-between">
                        <div class="d-flex justify-content-between">
                            <div> {{ $item->note }} </div>
                            <div class="btn-group ms-2">
                                <button type="button" class="btn btn-light-primary text-primary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('sticky-notes-edit/' . $item->note_id) }}"><i
                                            data-feather="edit" class="feather-sm me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ url('note/delete/' . $item->note_id) }}"><i
                                            data-feather="trash" class="feather-sm me-2"></i> Delete</a>

                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div> {{ $item->updated_at }} </div>
                            <div> <i class="fa fa-circle" style="color:{{ $item->color_code }} ;"></i> </div>

                        </div>
                    </div>
                </div>
            @endforeach


        </div>


    </div>

@stop
