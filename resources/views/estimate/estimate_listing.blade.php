@extends('home')
@section('content')

<!-- Page wrapper  -->
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">{{ \App\Models\EstimateTemplateCategory::find($category_id)->category_name ??
                    null }}
                </h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Price Book</a></li>
                            <li class="breadcrumb-item"><a href="">estimates & Materials </a>
                            </li>
                            @if($category_id)
                            <li class="breadcrumb-item">
                                {{ \App\Models\EstimateTemplateCategory::find($category_id)->category_name ?? null }}
                            </li>
                            @else
                            <li class="breadcrumb-item"><a href="">Category</a></li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">

                    <a href="{{ route('estimate.createestimate') }}" id="btn-add-contact" class="btn btn-info">
                        <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                        Add New Estimate</a>

                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12">
                <!-- ---------------------
                            start estimate Orders
                        ---------------- -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table estimate-overview" id="zero_config">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Template Name</th>
                                        <th>Total Value</th>

                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estimate as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>{{ $item->template_name }} </td>
                                        <td>{{ $item->estimate_total }}</td>


                                        <td>
                                            <div style="display:flex;">
                                                <div>

                                                    <a href="{{ route('estimateservices.edit', ['template_id' => $item->template_id]) }}"
                                                        class="text-dark pe-2">
                                                        <i data-feather="edit-2" class="feather-sm fill-white"></i>
                                                    </a>

                                                </div>
                                                <div>
                                                    <form method="post"
                                                        action="{{ route('estimate.destroy', ['template_id' => $item->template_id]) }}">
                                                        @csrf
                                                        @method('DELETE')


                                                        <button type="submit" class="text-dark"
                                                            style="background: none; border: none; cursor: pointer;">
                                                            <i data-feather="trash-2" class="feather-sm fill-white"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <!-- ---------------------
                            end estimate Orders
                        ---------------- -->
            </div>
            <!-- Column -->
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>
</div>
</div>
@stop