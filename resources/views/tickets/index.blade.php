<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <!-- tickets/index.blade.php -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Calls / Tickets</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Calls / Tickets</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">
                    <div class="me-2">
                        <div class="lastmonth"></div>
                    </div>
                    <div class="">
                        <small>LAST MONTH</small>
                        <h4 class="text-info mb-0 font-medium">$58,256</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-4">
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-primary text-center">
                                        <h1 class="fw-light text-primary">{{ $totalCalls }}</h1>
                                        <h6 class="text-primary">Total Calls</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-warning text-center">
                                        <h1 class="fw-light text-warning">{{ $inProgress }}</h1>
                                        <h6 class="text-warning">In Progress</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-success text-center">
                                        <h1 class="fw-light text-success">{{ $opened }}</h1>
                                        <h6 class="text-success">Opened</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-danger text-center">
                                        <h1 class="fw-light text-danger">{{ $complete }}</h1>
                                        <h6 class="text-danger">Closed</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               {{--  <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create New Ticket</a> --}}
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" >
                            <table id="zero_config"
                                class="table table-bordered text-nowrap" data-paging="true"
                                data-paging-size="7" style="width:auto">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Title</th>
                                        <th>Ticket ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Technician</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    <!-- start row -->
                                    @foreach ($tickets as $ticket)
                                        <tr>

                                            <td>
                                                <span
                                                    class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
                                            </td>
                                            <td>{{ $ticket->priority }} </td>
                                            <td>{{ $ticket->job_title }} </td>

                                            <td>
                                                <a href="" class="fw-bold link"><span
                                                        class="mb-1 badge bg-primary">{{ $ticket->job_code }}</span></a>
                                            </td>

                                            <td>
                                                @if ($ticket->user)
                                                    {{ $ticket->user->name }}
                                                @else
                                                    Unassigned
                                                @endif
                                            </td>
                                        <td>{{ $ticket->created_at ? $ticket->created_at->format('d-m-y') : '-' }}</td>

                                            <td>
                                                @if ($ticket->technician)
                                                    {{ $ticket->technician->name }}
                                                @else
                                                    Unassigned
                                                @endif
                                            </td>

                                            <td>
                                                <span><a class="btn btn-success"
                                                        href="{{ route('tickets.show', $ticket->id) }}">View</a></span>
                                                <span  style="display:none;"><a class="btn btn-primary"
                                                        href="{{ route('tickets.edit', $ticket->id) }}">Edit</a></span>
                                                <span style="display:none;">
                                                    <form method="POST"
                                                        action="{{ route('tickets.destroy', $ticket->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('script')
  {{--   <script src="{{ asset('public/admin/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>--}}
 
    <script>
      $('#zero_config').DataTable();
    </script>
@endsection
@endsection
