@extends('home')
@section('content')
    <style>
        .scroll-container {
            height: 750px;
            overflow-y: auto;
        }

        .error {
            color: rgb(218, 45, 45);
        }
        .gm-style .gm-style-iw-d {
            margin-top: -30px;
        }
    </style>
    <div class="page-wrapper" style="display:inline;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <div class="container-fluid" style="padding-top: 0px;">
            <div class="row">
                <div class="container">
                    <div class="row mb-3">

                        <div class="col-12 reschedulejob" style="display: none">
                            <form class="rescheduleForm" method="post">
                                <div class="row">
                                    <div class="col-12 rescheduleList"> </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12  py-2 px-3">
                                    <button type="button" class="btn waves-effect waves-light btn-info reschedulebutton"><i
                                            class="ri-calendar-check-line"></i> Reschedule Jobs
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col bg-light py-2 px-3 card-border">
                            <ul class="list-group scroll-container reschedule_user_list">
                                <li class="list-group-item">
                                    <div class="form-group mb-4">
                                        <label class="me-sm-2 py-2 bold" for="inlineFormCustomSelect">Select
                                            Territory</label>
                                        <select class="form-select me-sm-2 territory" id="territory"
                                            onchange="reloadPage()">
                                            <option value="">-- Select Territory --</option>
                                            @php
                                                $selectedAreaId =
                                                    request()->input('area_id') ??
                                                    ($locationServiceSouthWest->area_id ?? null);
                                            @endphp

                                            @foreach ($locationServiceArea as $value)
                                                <option data-lat="{{ $value->area_latitude }}" value="{{ $value->area_id }}"
                                                    @if ($selectedAreaId == $value->area_id) selected @endif
                                                    data-lag="{{ $value->area_longitude }}"
                                                    data-radius="{{ $value->area_radius }}">
                                                    {{ $value->area_name }}
                                                </option>
                                            @endforeach




                                        </select>
                                        <span class="error territory_error"></span>
                                    </div>
                                </li>
                                @if (isset($data) && !empty($data->count()))
                                    @foreach ($data as $key => $value)
                                        <li class="list-group-item" id="event_click{{ $value->assign_id }}"
                                            style="cursor: pointer;">
                                            <h6 class="uppercase mb-0">{{ $value->subject }}</h6>
                                            <div class="ft14"><i class="ri-user-location-fill"></i>
                                                {{ $value->name }}<br />
                                                {{ $value->address . ', ' . $value->city . ', ' . $value->state }}
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item mb-2" id="event_click" style="cursor: pointer;">
                                        <span style="font-size: 15px;font-weight: 700;letter-spacing: 1px;">
                                            No Data Found</span><br />
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-9 bg-light py-2 px-3 card-border">
                            <div id="map" style="height: 750px !important; width: 100% !important;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('maps.scriptIndex')
@endsection
