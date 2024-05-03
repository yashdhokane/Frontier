@foreach ($users as $user)
<tr class="search-items">
    <td>
        <div class="d-flex align-items-center">
            @if ($user->user_image)
                <img src="{{ asset('public/images/Uploads/users/' . $user->id . '/' . $user->user_image) }}"
                    alt="avatar" class="rounded-circle" width="45" />
            @else
                <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                    alt="avatar" class="rounded-circle" width="45" />
            @endif

            <div class="ms-2">
                <div class="user-meta-info">
                    <a href="{{ route('users.show', $user->id) }}">
                        <h6 class="user-name mb-0" data-name="name"> {{ $user->name }}
                        </h6>
                    </a>
                </div>
            </div>
        </div>
    </td>
    <td>
        <span class="user-work text-muted">{{ $user->email }}</span><br />
        <span class="user-work text-muted">{{ $user->mobile }}</span><br />
    </td>
    <td>
        <div style="display:flex;">
            @if ($user)
                @php
                    $userAddress = DB::table('user_address')
                        ->leftJoin(
                            'location_states',
                            'user_address.state_id',
                            '=',
                            'location_states.state_id',
                        )
                        ->where('user_id', $user->id)
                        ->first();
                    $userAddresscity = DB::table('user_address')
                        ->leftJoin(
                            'location_cities',
                            'user_address.city_id',
                            '=',
                            'location_cities.city_id',
                        )
                        ->where('user_address.user_id', $user->id)
                        ->value('location_cities.city');

                @endphp

                @if ($userAddress)
                    <span class="user-work text-muted">{{ $userAddresscity ?? null }},
                        {{ $userAddress->state_name }},
                        {{ $userAddress->zipcode }}</span>
                @else
                    <span class="user-work text-muted">N/A</span>
                @endif
            @else
                <span class="user-work text-muted">N/A</span>
            @endif
            <br />




        </div>
    </td>
   
    <td>
        <span
            class="mb-1 badge @if ($user->status == 'deactive') { bg-danger } @else { bg-success } @endif">{{ $user->status }}</span>
    </td>
     <td>
											<div style="display:flex;" data-bs-toggle="modal" data-bs-target="#commentModal2" onclick="setUserIdModal2({{ $user->id }}, '{{ $user->is_updated }}')">
												@if ($user->is_updated == 'no')
 													<span class="status_icons status_icon2"><i class="ri-close-line"></i></span> <span class="px-2 mt-1 pointer">Not Updated</span>
												@elseif($user->is_updated == 'yes')
 													<span class="status_icons status_icon1"><i class="ri-check-fill"></i></span> 
													<span class="px-2 mt-1 pointer">Updated <span class="is_updated_time">{{ $isEnd($user->updated_at)}}</span></span>
  												@endif
											</div>
												  

										</td>
    <td class="action footable-last-visible" style="display: table-cell;">
        <div class="btn-group">
            <button type="button"
                class="btn btn-light-primary text-primary dropdown-toggle"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ri-settings-3-fill align-middle fs-5"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('users.show', $user->id) }}"><i
                        data-feather="eye" class="feather-sm me-2"></i> View</a>
                <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}"><i
                        data-feather="edit-2" class="feather-sm me-2"></i> Edit</a>
                <a class="dropdown-item activity" href="javascript:void(0)"
                    data-bs-toggle="modal" data-bs-target="#commentModal1"
                    onclick="setUserId({{ $user->id }})">
                    <i data-feather="activity" class="feather-sm me-2"></i> Status
                </a>

                <a class="dropdown-item add-comment" href="javascript:void(0)"
                    data-bs-toggle="modal" data-bs-target="#commentModal"
                    onclick="setUserIdForCommentModal('{{ $user->id }}')">
                    <i data-feather="message-circle" class="feather-sm me-2"></i>
                    Comments
                </a>
                <a class="dropdown-item" href="{{ route('permissionindex') }}">
                    <i data-feather="user-check" class="feather-sm me-2"></i>
                    Permission
                </a>

            </div>
        </div>

    </td>
</tr>
{{-- model staus active in active --}}
<div class="modal fade" id="commentModal1" tabindex="-1"
    aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Change Status</h5>
            </div>
            <!-- Comment form -->
            <form id="commentForm" action="{{ route('technicianstaus.update') }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="user_id" id="userId">
                        <span id="confirmationMessage"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"
                        id="confirmButton">Yes</button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>


</div>
{{-- end model --}}
@endforeach