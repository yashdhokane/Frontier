<a class="nav-link dropdown-toggle waves-effect waves-dark" title="Sticky Note" href="#" id="showStickyNote" data-bs-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-sticky-note ft20"></i>
</a>
<div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up stickyDropdown" data-bs-auto-close="outside" aria-labelledby="showStickyNote">
    <span class="with-arrow"><span class="bg-site"></span></span>
    <ul class="list-style-none">
        <li>
            <div class="drop-title text-white bg-site">
                <h5 class="mb-0 mt-1 uppercase">Sticky Notes</h5>
            </div>
        </li>
        <li>
            <div class="stickyMainSection">

                    <div class="row m-2 stickyNotesList">
                        <div class="col-sm-12 col-md-12"><button type="button"
                                class="btn btn-info ms-3 addStickyNoteBtn">
                                <i class="fa fa-plus"></i> Add Note</button></div>

                        @php
                            $stickyNote = \App\Models\StickyNotes::all();
                        @endphp

                        <div class="col-sm-12 col-md-12">
                            <div class="row sticknoteslist">
                                @foreach ($stickyNote as $item)
                                    <div class="col-sm-4 col-md-4 my-3">
                                        <div class="card-border shadow rounded p-3 h-100">
                                            <div class="row">
                                                <div class="col-9"> <div> {{ $item->note }} </div>
                                                 <small>{{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}</small>

                                                </div>
                                                <div class="col-2 btn-group ms-2">
                                                    <div class="fw-bold fs-7 actionBtnNote"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        ...
                                                    </div>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item editStckyNoteBtn pointer ps-1"
                                                            data-note-id="{{ $item->note_id }}"><i data-feather="edit"
                                                                class="feather-sm me-2"></i> Edit</a>
                                                        <input type="hidden" class="edit_note_id"
                                                            value="{{ $item->note_id }}">
                                                        <a class="dropdown-item deleteStckyNoteBtn pointer ps-1"
                                                            data-note-id="{{ $item->note_id }}"><i data-feather="trash"
                                                                class="feather-sm me-2"></i> Delete</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <div class="row m-2 addStickyNote" style="display: none;">
                        <div class="col-sm-12 col-md-12">
                            <h3 class="p-1 mt-2">Add Notes </h3>
                            <hr>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <h4>Note Details</h4>
                        </div>

                        @php
                            $color = \App\Models\ColorCode::all();
                        @endphp

                        <div class="col-sm-12 col-md-12">
                            <form id="colorNoteForm" method="post" class="form-horizontal form-material"
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
                                <div class="">
                                    <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                        Save
                                    </button>
                                    <button type="button" class="btn btn-info waves-effect closeStickyAdd"
                                        data-bs-dismiss="modal">
                                        Cancel
                                    </button>

                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="row m-2 editStickyNote" style="display: none;">
                        <div class="col-sm-12 col-md-12">
                            <h3 class="p-1 mt-2">Edit Note </h3>
                            <hr>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <h4>Note Details</h4>
                        </div>


                        <div class="col-sm-12 col-md-12">
                            <form id="editNoteForm" method="post" class="form-horizontal form-material"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="note_id" id="edit_note_id2" value="">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="col-md-12 mb-4">
                                            <label for="" class="my-2"> Color Code </label>
                                            <select name="color_code" class="form-control" id="edit_color_code">
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
                                                <textarea name="note" class="form-control" id="edit_note"></textarea>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="">
                                    <button type="submit" class="btn btn-info ">
                                        Update
                                    </button>
                                    <button type="button" class="btn btn-info waves-effect closeStickyAdd">
                                        Cancel
                                    </button>

                                </div>
                            </form>
                        </div>

                    </div>

            </div>
        </li>
    </ul>
    

</div>