<div class="card-body card-border shadow">
                            <div class="row">

                                <div class="col-lg-4 col-xlg-9">
                                    <div class="row text-left justify-content-md-left">
                                        <div class="col-12 mb-3 mt-2">
                                            <div class="card-border" style="height: 200px;">

                                                <iframe id="map238" width="100%" height="150" frameborder="0"
                                                    style="border: 0" allowfullscreen=""></iframe>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="card card-border">
                                                <div class="card-body">
                                                    <div class="row open_items">
                                                        <div class="col-md-1">
                                                            <i class="fas fa-paperclip"></i>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <h5 class="card-title uppercase">Files</h5>
                                                        </div>
                                                        <div class="col-md-1 addAttachment" style="cursor: pointer;">
                                                            <i class="fas fa-plus "></i>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <form action="{{ route('customer_file_store') }}" method="POST"
                                                            enctype="multipart/form-data" class="showAttachment"
                                                            style="display: none;">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$commonUser->id}}"
                                                                class="form-control">
                                                            <input type="file" name="attachment" id=""
                                                                class="form-control">
                                                            <div class="mb-3 text-end">
                                                                <button type="submit"
                                                                    class="btn btn-primary rounded mt-2">Add</button>
                                                            </div>

                                                        </form>
                                                        <div>
                                                            @foreach ($customerimage as $item)
                                                            <a href="{{url('public/images/users/'.$item->user_id.'/'.$item->filename)}}"
                                                                target="_blank"><img
                                                                    src="{{url('public/images/users/'.$item->user_id.'/'.$item->filename)}}"
                                                                    alt="file" width="100px"
                                                                    onerror="this.onerror=null; this.src='{{$defaultImage}}';"></a>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-12 mb-3">
                                            <div class="mb-4">
                                                <div class="card card-border">
                                                    <div class="card-body">
                                                        <div class="row open_items">
                                                            <div class="col-md-1">
                                                                <i class="fas fas fa-tag" style="margin-top: 7px;"></i>
                                                                <!-- Adjusted margin -->
                                                            </div>
                                                            <div class="col-md-9">
                                                                <!-- Adjusted column size -->
                                                                <h5 class="card-title uppercase">Customer Tags</h5>
                                                                <!-- Adjusted margins -->
                                                            </div>
                                                            <div class="col-md-1 addCustomerTags"
                                                                style="cursor: pointer;">
                                                                <i class="fas fa-plus"></i>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-2">
                                                                    {{-- @foreach ($Sitetagnames as $item)
                                                                    {{ $item->tag_name }} ,
                                                                    @endforeach --}}
                                                                    @if($commonUser->tags->isNotEmpty())
                                                                    @foreach($commonUser->tags as $tag)
                                                                    <span class="badge bg-dark">{{ $tag->tag_name
                                                                        }}</span>
                                                                    @endforeach
                                                                    @else
                                                                    <span class="badge bg-dark">No tags available</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 showCustomerTags"
                                                                style="display:none; ">
                                                                <form action="{{ route('customer_tags_store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input value="{{$commonUser->id}}" name="id"
                                                                        type="hidden" />
                                                                    <div class="mb-3">
                                                                        <select
                                                                            class="select2-with-menu-bg form-control  me-sm-2"
                                                                            name="customer_tags[]"
                                                                            id="menu-bg-multiple1" multiple="multiple"
                                                                            data-bgcolor="light"
                                                                            data-bgcolor-variation="accent-3"
                                                                            data-text-color="blue" style="width: 100%"
                                                                            required>
                                                                            @foreach ($customer_tag as $item)
                                                                            <option value="{{ $item->tag_id }}">
                                                                                {{ $item->tag_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3 text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary rounded">Add</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <div class="card card-border">
                                                <div class="card-body">
                                                    <div class="row open_items">
                                                        <div class="col-md-1">
                                                            <i class="fas fa-bullseye "></i>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <h5 class="card-title uppercase">Lead Source</h5>
                                                        </div>
                                                        <div class="col-md-1 addSource" style="cursor: pointer;">
                                                            <i class="fas fa-plus "></i>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            @foreach($leadsourcename as $leadsourcename)
                                                            <div class="mb-2">
                                                                <span
                                                                    class="mb-1 badge bg-primary">{{$leadsourcename->source_name
                                                                    }}</span>
                                                            </div>
                                                            @endforeach

                                                        </div>
                                                        <div class="col-md-12 showSource" style="display:none; ">
                                                            <form action="{{ route('customer_leadsource_store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input name="id" value="{{$commonUser->id}}"
                                                                    type="hidden" />
                                                                <div class="mb-3">
                                                                    <select
                                                                        class="select2-with-menu-bg form-control  me-sm-2"
                                                                        name="lead_source[]" id="menu-bg-multiple2"
                                                                        multiple="multiple" data-bgcolor="light"
                                                                        data-bgcolor-variation="accent-3"
                                                                        data-text-color="blue" style="width: 100%"
                                                                        required>
                                                                        @foreach ($leadsource as $item)
                                                                        <option value="{{ $item->source_name }}">
                                                                            {{ $item->source_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3 text-end">
                                                                    <button type="submit"
                                                                        class="btn btn-primary rounded">Add</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <div class="card card-border">
                                                <div class="card-body">
                                                    <div class="row open_items">
                                                        <div class="col-md-1">
                                                            <i class="far fa-flag"></i> 
                                                        </div>
                                                        <div class="col-md-9">
                                                            <h5 class="card-title uppercase">Flag Customer</h5>
                                                        </div>
                                                        <div class="col-md-1 addFlag" style="cursor: pointer;">
                                                            <i class="fas fa-plus "></i>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 showFlag" style="display:none; ">
                                                            <form id="flagCustomerForm" method="POST">
                                                                @csrf
                                                                <div class="card-body card-border shadow">
                                                                    <div class="row">
                                                                        <div class="col-12 pt-2">
                                                                           
                                                                            <label for="flag" class="pt-2">Flag</label>
                                                                            <select name="flag_id" id="flag" class="form-control select2" required>
                                                                                <option value="">-- Select Flag --</option>
                                                                                @foreach ($flag as $value)
                                                                                    <option value="{{ $value->flag_id }}"  style="color: {{ $value->flag_color }};">
                                                                                        {{ $value->flag_desc }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-12 pt-2">
                                                                           
                                                                            <label for="job_id" class="pt-2"> Job</label>
                                                                            <select name="job_id" id="job_id" class="form-control select2" required>
                                                                                <option value="">-- Select Job --</option>
                                                                                @foreach ($flagJob as $value)
                                                                                    <option value="{{ $value->id }}">#{{ $value->id }}-{{ $value->job_title }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-12 pt-2 ">
                                                                            <label for="flag_reason">Add Reason</label>
                                                                            <textarea class="form-control" name="flag_reason" id="flag_reason" cols="10" rows="3" required></textarea>
                                                                        </div>
                                                                        <div class="col-12 pt-2">
                                                                            <button type="submit" class="btn btn-success">
                                                                                Submit
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-lg-8 col-xlg-9">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6 b-r">
                                            <div class="col-12 mx-3">
                                                <h5 class="card-title uppercase mt-2">Contact info</h5>
                                                <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                                    {{$commonUser->mobile}}</h6>
                                                @if($UsersDetails->work_phone)
                                                <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i> {{
                                                    $UsersDetails->work_phone }} (Work)</h6>
                                                @endif

                                                @if($UsersDetails->home_phone)
                                                <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i> {{
                                                    $UsersDetails->home_phone }} (Home)</h6>
                                                @endif

                                                @if($commonUser->email)
                                                <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                    {{$commonUser->email}}</h6>
                                                @endif

                                                @if($UsersDetails->additional_email)
                                                <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i> {{
                                                    $UsersDetails->additional_email }} (Additional)</h6>
                                                @endif



                                                <h5 class="card-title uppercase mt-5">Address</h5>
                                                <h6 style="font-weight: normal;"><i class="ri-map-pin-line"></i>
                                                    {{ $address ?? ''}}

                                                </h6>



                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="card-title uppercase mt-4">Summary</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Last service</small>
                                                        <h6>
                                                            {{ $jobasigndate && $jobasigndate->start_date_time ?
                                                            \Carbon\Carbon::parse($jobasigndate->start_date_time)->format('m-d-Y')
                                                            :
                                                            null }}
                                                        </h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Profile Created</small>
                                                        <h6>{{ $commonUser->created_at ?
                                                            \Carbon\Carbon::parse($commonUser->created_at)->format('m-d-Y')
                                                            :
                                                            null }}</h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Lifetime value</small>
                                                        <h6>$0.00</h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Outstanding
                                                            balance</small>
                                                        <h6>$0.00</h6>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-7 col-xs-6 b-r">

                                            <div class="mt-4">
                                                <iframe id="map{{ $location->address_id }}" width="100%" height="300"
                                                    frameborder="0" style="border: 0" allowfullscreen></iframe>
                                                <div style="display:flex;">
                                                    <h6>
                                                        {{ $address ?? ''}}

                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div>





                                    </div>


                                </div>


                            </div>
                        </div>