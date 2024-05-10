@foreach ($users as $user)
    <tr class="search-items">
        <td>
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <div class="user-meta-info">
                        <a href="{{ route('customersdata.show', $user->id) }}">
                            <h6 class="user-name mb-0" data-name="name"> {{ $user->name }}
                            </h6>
                        </a>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <span class="user-work">{{ $user->mobile }}</span><br />
            <span class="user-work">{{ $user->email }}</span>
        </td>
        <td>
            <div style="display:flex;">

                @if ($user)
                    @php
                        $userAddress = DB::table('user_address')
                            ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
                            ->where('user_id', $user->id)
                            ->first();
                        $userAddresscity = DB::table('user_address')
                            ->leftJoin('location_cities', 'user_address.city_id', '=', 'location_cities.city_id')
                            ->where('user_address.user_id', $user->id)
                            ->value('location_cities.city');

                    @endphp

                    @if ($userAddress)
                        <span class="user-work">{{ $userAddress->city }},
                            {{ $userAddress->state_code }}, {{ $userAddress->zipcode }}</span>
                    @else
                        <span class="user-work">N/A</span>
                    @endif
                @else
                    <span class="user-work">N/A</span>
                @endif
                <br />
            </div>
        </td>
        <td>
            <span>{{ $user->customerdatafetch->no_of_visits ?? '' }}</span>
        </td>
        <td>
            <span>{{ $user->customerdatafetch->job_completed ?? '' }}</span>
        </td>
        <td>{{ $user->customerdatafetch->issue_resolved ?? '' }}</td>
        <td class="action footable-last-visible" style="display: table-cell;">
            <div class="btn-group">
                <button type="button" class="btn btn-light-primary text-primary dropdown-toggle"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-settings-3-fill align-middle fs-5"></i>
                </button>
                @if ($user->customerdatafetch)
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('customersdata.show', $user->id) }}">
                            <i data-feather="eye" class="feather-sm me-2"></i> View
                        </a>
                        <!--   <a class="dropdown-item" href="{{ route('customersdata.show', $user->id) }}"><i
                        data-feather="edit-2" class="feather-sm me-2"></i> Edit</a> -->
                    </div>
                @else
                    <div class="dropdown-menu">

                        <form method="POST" action="{{ route('checkAndUpdateAndView') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="dropdown-item">
                                <i data-feather="edit-2" class="feather-sm me-2"></i> Insert
                                value
                            </button>
                        </form>
                    </div>
                @endif
            </div>

        </td>
    </tr>
    {{-- model staus active in active --}}
    <div class="modal fade" id="commentModal1" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Change Status</h5>
                </div>
                <!-- Comment form -->
                <form id="commentForm" action="{{ route('technicianstaus.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="user_id" id="userId">
                            <span id="confirmationMessage"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="confirmButton">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
    {{-- end model --}}


    {{-- open model is_update --}}
    <div class="modal fade" id="commentModal2" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Comment form -->
                <form id="commentForm" action="{{ route('customercomment') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5 class="modal-title" id="commentModalLabel">Update Work Details
                        </h5>

                        <div class="mb-3">
                            <input type="hidden" name="user_id" id="userIdModal2">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input contact-chkbox primary"
                                id="statusCheckboxModal2" name="is_updated" onchange="updateIsUpdated(this)"
                                {{ $user->is_updated == 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusCheckboxModal2">Mark as
                                Updated</label>
                        </div>
                        <input type="hidden" name="is_updated" id="isUpdatedHidden">




                        <div class="mb-3">
                            <label for="comment">Add Comment:</label>
                            <textarea class="form-control" id="comment" name="note" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="confirmButtonModal2">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end model --}}
@endforeach
