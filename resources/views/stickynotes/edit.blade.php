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
                    <h4 class="page-title"> Notes</h4>
                </div>
                <div class="col-7 align-self-center">
                    <div class="d-flex no-block justify-content-end align-items-center">
                        <div class="me-2">
                            <a href="{{route('sticky_notes')}}" id="btn-add-contact" class="btn btn-info"> Back</a>
                        </div>
                    </div>
                </div>  
            </div>
        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}

            </div>
        @endif
        <div class="row justify-center">
            <div class="col-6">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-custom">
                            <form action="{{ url('note/update/' . $note->note_id) }}" method="post"
                                class="form-horizontal form-material" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="col-md-12 mb-4">
                                            <label for="" class="my-2"> Color Code
                                            </label>
                                            <select name="color_code" class="form-control" id="">
                                                @foreach ($color as $value)
                                                    <option value="{{ $value->color_code }}"  style="background: {{ $value->color_code }};"
                                                        {{ $value->color_code == $note->color_code ? 'selected' : '' }}>
                                                        {{ $value->color_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="">
                                                <label for="" class="my-2">Note </label>
                                                <textarea name="note" class="form-control" value="{{ $note->note }}">{{ $note->note }}</textarea>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                        Update
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>

@stop
