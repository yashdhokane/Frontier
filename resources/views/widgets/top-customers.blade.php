<!--<div class="card card-hover"> -->

    <div class="p-3">
        <div class="d-flex justify-content-between mb-1">
            <h4 class="">Top Customers</h4>
            @if ($layout->added_by == auth()->user()->id)
                <button class="btn btn-light mx-2 clearSection"
                    data-element-id="{{ $cardPosition->element_id }}">X</button>
            @endif
        </div>

        <div class="row">
            @foreach ($customeruser as $user)
                <div class="col-lg-4">
                    <div class="card card-border shadow">
                        <div class="card-body">
                            <h5 class="card-title ft13 uppercase text-primary">{{ $user->name ?? null }}</h5>
                            @foreach ($user->user_addresses as $address)
                                <h6 class="ft11 mb-2 d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>
                                    {{ $address->address_line1 ?? null }}, {{ $address->city ?? null }},
                                    {{ $address->state_name ?? null }}, {{ $address->zipcode ?? null }}
                                </h6>
                            @endforeach
                            <p class="card-text pt-2 ft12">
                                {{ count($user->jobs) }} Jobs<br />
                                LifetimeValue: ${{ $user->gross_total ?? 0 }}</p>
                            <a href="{{ route('users.show', ['id' => $user->id]) }}" class="card-link">View
                                Profile</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
<!--</div> -->
