  <div class="row">

                                <div class="col-lg-3 col-xlg-9">

                                    <div class="row text-left justify-content-md-left">

                                        <div class="col-12">
                                            <center class="mt-1">
                                                @if ($commonUser->user_image)
                                                <img src="{{ asset('public/images/Uploads/users/' . $commonUser->id . '/' . $commonUser->user_image) }}"
                                                    class="rounded-circle" width="150" />
                                                @else
                                                <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                    alt="avatar" class="rounded-circle" width="150" />
                                                @endif
                                                <h5 class="card-title uppercase mt-1">{{ $commonUser->name }}</h5>
                                                <h6 class="card-subtitle">Technician</h6>
                                            </center>
                                        </div>


                                        <div class="col-12">
                                            <h5 class="card-title uppercase mt-4">Tags</h5>
                                            <div class="mt-0">
                                                @if ($commonUser->tags->isNotEmpty())
                                                @foreach ($commonUser->tags as $tag)
                                                <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                                @endforeach
                                                @else
                                                <span class="badge bg-dark">No tags available</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h5 class="card-title uppercase mt-4">Files & Attachments</h5>
                                            <div class="mt-0">
                                                @foreach ($customerimage as $image)
                                                @if ($image->filename)
                                                <a href="{{ asset('storage/app/' . $image->filename) }}" download>
                                                    <p><i class="fas fa-file-alt"></i></p>
                                                    <img src="{{ asset('storage/app/' . $image->filename) }}"
                                                        alt="Customer Image" style="width: 50px; height: 50px;">
                                                </a>
                                                @else
                                                <!-- Default image if no image available -->
                                                <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
                                                    alt="Default Image" style="width: 50px; height: 50px;">
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <div class="col-lg-9 col-xlg-9">
                                    <div class="row">

                                        <div class="col-md-3 col-xs-6 b-r">
                                            <div class="col-12">
                                                <h5 class="card-title uppercase mt-4">Contact info</h5>
                                                <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                                    {{ $commonUser->mobile }}</h6>
                                                <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                    {{ $commonUser->email }}</h6>

                                                <h5 class="card-title uppercase mt-5">Address</h5>
                                                <h6 style="font-weight: normal;"><i class="ri-map-pin-line"></i>
                                                    {{ $address ?? ''}}</h6>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="card-title uppercase mt-4">Summary</h5>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Jobs Completed</small>
                                                        <h6>0</h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Jobs Open</small>
                                                        <h6>0</h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Revenue Earned</small>
                                                        <h6>$0.00</h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Profile Created</small>
                                                        <h6>{{ $commonUser->created_at ?
                                                            \Carbon\Carbon::parse($commonUser->created_at)->format('m-d-Y')
                                                            : null }}
                                                        </h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Last service</small>
                                                        <h6>
                                                            {{ $jobasigndate && $jobasigndate->start_date_time
                                                            ?
                                                            \Carbon\Carbon::parse($jobasigndate->start_date_time)->format('m-d-Y')
                                                            : null }}
                                                        </h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted pt-1 db">Status</small>
                                                        <h6 class="ucfirst">{{ $commonUser->status ?? null }}</h6>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-1 col-xs-6 b-r">&nbsp;</div>
                                        <div class="col-md-8 col-xs-6 b-r">
                                            <div class="mt-4">
                                                <iframe id="map{{ $location->address_id }}" width="100%" height="300"
                                                    frameborder="0" style="border: 0" allowfullscreen></iframe>
                                                <div style="display:flex;">
                                                    <h6>{{ $address?? '' }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>



                                    </div>

                                </div>
                            </div>