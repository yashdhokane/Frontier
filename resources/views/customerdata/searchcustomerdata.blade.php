@foreach ($users as $user)
<tr class="search-items">
    <td>
        <div class="d-flex align-items-center">
            <div class="ms-2">
                <div class="user-meta-info">
                    <a href="{{ route('customersdata.show', $user->userdata1->id) }}">
                        <h6 class="user-name mb-0" data-name="name">{{
                            $user->userdata1->name }}
                        </h6>
                    </a>
                </div>
            </div>
        </div>
    </td>
    <td>
        <span class="user-work">{{ $user->userdata1->mobile }}</span><br />
        <span class="user-work">{{ $user->userdata1->email }}</span>
    </td>
    <td>
        <div style="display:flex;">
            @php
            $userAddress = DB::table('user_address')
            ->leftJoin('location_states', 'user_address.state_id', '=',
            'location_states.state_id')
            ->where('user_id', $user->userdata1->id)
            ->first();
            $userAddresscity = DB::table('user_address')
            ->leftJoin('location_cities', 'user_address.city_id', '=',
            'location_cities.city_id')
            ->where('user_address.user_id', $user->userdata1->id)
            ->value('location_cities.city');
            @endphp

            @if (isset($userAddress) && $userAddress)
            <span class="user-work">
                @if(isset($userAddress->city) && $userAddress->city !== '')
                {{ $userAddress->city }},
                @endif

                @if(isset($userAddress->state_code) && $userAddress->state_code !==
                '')
                {{ $userAddress->state_code }},
                @endif

                @if(isset($userAddress->zipcode) && $userAddress->zipcode !== '')
                {{ $userAddress->zipcode }}
                @endif
            </span>
            @else
            <span class="user-work">N/A</span>
            @endif
            <br />
        </div>
    </td>
    <td>
        <span>{{ $user->no_of_visits ?? '' }}</span>
    </td>
    <td>
        <span>{{ $user->job_completed ?? '' }}</span>
    </td>
    <td>{{ $user->issue_resolved ?? '' }}</td>
    <td class="action footable-last-visible" style="display: table-cell;">
        <div class="btn-group">
            <button type="button" class="btn btn-light-primary text-primary dropdown-toggle" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="ri-settings-3-fill align-middle fs-5"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('customersdata.show', $user->userdata1->id) }}"><i
                        data-feather="eye" class="feather-sm me-2"></i> View</a>
                <!--   <a class="dropdown-item" href="{{ route('customersdata.show', $user->userdata1->id) }}"><i
                                                                            data-feather="edit-2" class="feather-sm me-2"></i> Edit</a> -->
            </div>
        </div>
    </td>
</tr>
{{-- model status active in active --}}
@endforeach
