@extends('home')
@section('content')
<style>
    table.dataTable td.dt-empty {
        text-align: start !important;
    }


    /* Highlight effect */
    /* Smooth highlight animation */
    @keyframes glow {
        0% {
            box-shadow: 0 0 10px rgba(41, 98, 255, 0.7);
        }

        50% {
            box-shadow: 0 0 20px rgba(41, 98, 255, 0.7);
        }

        100% {
            box-shadow: 0 0 10px rgba(41, 98, 255, 0.7);
        }
    }

    .highlight-field {
        border: 2px solid #2962ff !important;
        /* Blue border */
        animation: glow 1.5s infinite alternate;
        /* Blue glow effect */
    }

    /* input:focus, select:focus, .select2-selection--single:focus, .select2-search__field:focus {
    border: 2px solid #2962ff !important;
    box-shadow: 0 0 10px rgba(41, 98, 255, 0.7) !important;
    outline: none !important;
} */

    input[type="date"]:focus,
    input[type="text"]:focus {
        border: 2px solid #2962ff !important;
        box-shadow: 0 0 10px rgba(41, 98, 255, 0.7) !important;
        outline: none !important;
    }

    .select2-container--default .select2-selection--single {
        //  border: 1px solid #2962ff; /* Border for select2 */
    }

    .select2-container--default .select2-selection--single:focus {
        box-shadow: 0 0 10px rgba(41, 98, 255, 0.7);
        border-color: #2962ff;
    }

    select {
        border: 1px solid #2962ff;
        padding: 0.5rem;
        font-size: 1rem;
    }

    input,
    select {
        transition: border 0.3s, box-shadow 0.3s;
        /* Smooth transition */
    }
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-4 align-self-center">
            <h4 class="page-title">Parameters</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Parameters</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-8 text-end px-4">
            <div class="btn-group select-type-values mx-2" role="group" aria-label="Button group with nested dropdown float-end">
                <a href="#showJobs" data-value="jobs" class="btn btn-dark btn-show-jobs">Jobs</a>
                <a href="#showParts" data-value="products" class="btn btn-secondary btn-show-parts">Parts</a>
            </div>
          @include('header-top-nav.settings-nav') 
        </div>
    </div>
</div>
<div class="container-fluid pt-2">
    <div class="bg-white my-2 px-4 card-border shadow">
        <div class="row px-2 py-2">


            <div class="col-md-6 p-0">
                <div class="flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Command</b></label>
                    <input type="text" id="command-text" class="form-control mb-1">
                </div>
            </div>
            <div class="col-md-1 ps-1 align-self-center mt-3">
                <button class="btn btn-secondary badge" id="command-search"> <i class="fas fa-search"></i></button>
            </div>
            <div class="col-md-5">
                <div class="flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Previous Parameters</b></label>
                    <select id="saved-filters-dropdown" class="form-control select2">
                    </select>
                </div>
            </div>

        </div>
    </div>

    <div class="card card-border shadow">
        <div class="card-body">
            <div class="row border p-2" id="job-filter-rows">
                @include('parameter.job-filter-rows')
            </div>

            <div class="row border p-2" id="product-filter-rows">
                @include('parameter.product-filter-rows')
            </div>

            <div class="mt-2 float-end">
                <button type="button" class="btn btn-secondary me-5" id="save-filters-modal">Save</button>
            </div>

        </div>
    </div>

    @php
    $time_interval = Session::get('time_interval', 0);
    @endphp
    <div class="card card-border shadow">
        <div class="card-body">
            <div class="table-responsive table-custom">

                <table id="jobs-table" class="table table-hover table-striped text-nowrap">
                    @include('parameter.jobs-table')
                </table>

                <table id="product-table" class="table table-hover table-striped product-overview">
                    @include('parameter.product-table')
                </table>

                <table id="user-table" class="table table-hover table-striped user-overview">
                    @include('parameter.user-table')
                </table>

            </div>
        </div>
    </div>


    <!-- Bootstrap Modal -->
    <div class="modal fade" id="filterNameModal" tabindex="-1" role="dialog" aria-labelledby="filterNameModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterNameModalLabel">Enter Name</h5>

                </div>
                <div class="modal-body">
                    <input type="hidden" id="filter-id-update" name="filter_id_update">
                    <input type="text" id="filter-name" class="form-control" placeholder="Enter name">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close-showmodel"
                        data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submit-filters">Save </button>
                </div>
            </div>
        </div>
    </div>

</div>
@section('script')
@include('parameter.script')
@endsection
@endsection