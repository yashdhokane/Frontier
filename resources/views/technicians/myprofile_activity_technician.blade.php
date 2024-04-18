<div class="row col-md-12 " style="display:flex; justify-content: space-between;">
    <div class="row col-md-9 ">

        <div class="card" style="border: 1px solid #D8D8D8;">
            <div class="card-body">
                <h4 class="card-title">Activity</h4>  
                <div class="table-responsive">
                    <table class="table customize-table mb-0 v-middle">
                        <tbody>
                            @foreach($activity as $record)
                            <tr>
                                <td style="width:20%">
                                    <div class="d-flex align-items-center">
                                        @if($record->user->user_image)
                                        <img src="{{ asset('public/images/Uploads/users/' . $record->user->id . '/' . $record->user->user_image) }}"
                                            alt="avatar" class="rounded-circle" width="40" />
                                        {{-- <img src="{{ asset('public/images/superadmin/' . $record->user_image) }}"
                                            alt="avatar" class="rounded-circle" width="40" /> --}}

                                        @else
                                        <img src="{{asset('public/images/login_img_bydefault.png')}}" alt="avatar"
                                            class="rounded-circle" width="40" />


                                        @endif



                                        <span class="ms-2 fw-normal">{{ $record->user->name ?? null }}</span>
                                    </div>
                                </td>
                                <td style="width:60%">{{ $record->activity ?? null}}</td>
                                <td style="width:20%">
                                    {{ \Carbon\Carbon::parse($record->created_at)->format('D n/j/y g:ia') ?? 'null' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>


    </div>
    <div class="col-md-3 row">
     </div>


</div>

</div>
