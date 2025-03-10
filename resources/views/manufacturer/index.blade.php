@if (Route::currentRouteName() != 'dash')
@extends('home')
@section('content')
@endif

<!-- -------------------------------------------------------------- -->

<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->



<div class="page-breadcrumb">
    <div class="row">
        <div class="col-2 align-self-center">
            <h4 class="page-title">Manufactures</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manufactures</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-3 align-self-center">
            <a href="{{ route('manufacturer.create') }}" class="btn btn-info">
                <i class="ri-device-line"> </i>
                Add New Manufacturer</a>
        </div>
        @include('settings.mini-nav')
    </div>
    @if (Session::has('success'))
    <div class="alert_wrap">
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
            {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    </div>
    @endif
    <!-- ------------------------------------------------------------ -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid pt-2">




        <!-- Row -->
        <style>
            .srdrop {
                float: right;
            }

            .card {
                min-height: 100%;
            }

            .card-img-top {
                aspect-ratio: 5/2;
                object-fit: contain;
                padding-block: 8px;
            }

            .img_sq_manufacturer {
                max-height: 200px;
            }
        </style>

        <div class="row">

            <!-- column -->
            @foreach ($manufacture as $item)
            <div class="col-lg-3 col-md-6 col-xl-2 mb-3 ">
                <!-- Card -->
                <div class="card card-border shadow">


                    <a href="{{ route('manufacturer.edit', ['id' => $item->id]) }}" class="card-link">
                        @if ($item->manufacturer_image)
                        <img class="img_sq_manufacturer" src="{{ asset('public/images/' . $item->manufacturer_image) }}"
                            alt="Card image cap" />
                        @else
                        <img class="img_sq_manufacturer"
                            src="{{ asset('public/images/1703141665_heating-air-conditioning.jpg') }}"
                            alt="Default Image" />
                        @endif
                    </a>



                    <div class="card-body">
                        <h5 class="card-title uppercase text-info">{{ $item->manufacturer_name }}<div
                                class="dropdown dropstart srdrop ">

                                <a href="" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                    <i data-feather="more-vertical" class="feather-sm"></i>

                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                    <li><a class="dropdown-item viewinfo"
                                            href="{{ route('manufacturer.edit', ['id' => $item->id]) }}">Edit</a>
                                    </li>
                                    @if ($item->is_active == 'no')
                                    <li><a class="dropdown-item viewinfo"
                                            href="{{ url('/setting/manufacturer-enable', ['id' => $item->id]) }}">Enable</a>
                                    </li>
                                    @else
                                    <li><a class="dropdown-item viewinfo"
                                            href="{{ url('/setting/manufacturer-disable', ['id' => $item->id]) }}">Disable</a>
                                    </li>
                                    @endif




                                </ul>

                            </div>
                        </h5>

                    </div>


                </div>

            </div>
            @endforeach



            <!-- column -->
            <!-- column -->

        </div>
        <!-- Row -->








        <!-- -------------------------------------------------------------- -->
        <!-- Recent comment and chats -->
        <!-- -------------------------------------------------------------- -->

    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- Recent comment and chats -->
    <!-- -------------------------------------------------------------- -->

    @if (Route::currentRouteName() != 'dash')
    @endsection
    @endif