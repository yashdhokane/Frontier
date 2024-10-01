<h4 class="mb-3 page-title text-info fw-bold">
                {{ $layout->layout_name ?? null }}
                @if ($layout->added_by == auth()->user()->id && $layout->is_editable == 'yes')
                    <a href="#" class="edit-layout" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="fa fa-edit align-top fs-1 text-danger"></i>
                    </a>
                @endif
            </h4> 


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