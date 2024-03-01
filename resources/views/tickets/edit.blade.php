<!-- resources/views/clients/edit.blade.php -->

@extends('home')

@section('content')


<div class="page-breadcrumb">

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="row">
        <div class="col-sm-12">
            <!-- ---------------------
                        start Default Form Elements
                    ---------------- -->
            <div class="card card-body">
            <h4 class="card-title">Edit Ticket</h4>
            <h5 class="card-subtitle mb-3 border-bottom pb-3">
                submit All Details
            </h5>

            <form class="form-horizontal" method="POST" action="{{ route('tickets.update', $ticket->id) }}">
            @csrf
            @method('PUT')
    <div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select col-12" id="inlineFormCustomSelect">
        <option value="open">Open</option>
        <option value="resolved">Resolved</option> <!-- Updated from "complete" to "resolved" -->
        <option value="pending">Pending</option>
        <option value="rejected">Rejected</option>
    </select>
</div>

    <div class="mb-3">
        <label>Priority</label>
        <select name="priority" class="form-select col-12" id="inlineFormCustomSelect">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Customer name</label>
        <select name="customer_id" class="form-select col-12" id="inlineFormCustomSelect">
            <option value="">Select User</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Technician</label>
        <select name="technician_id" class="form-select col-12" id="inlineFormCustomSelect">
            <option value="">Select User</option>
            @foreach ($technicians as $technician)
                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
            @endforeach
        </select>
    </div>
      <div class="mb-3">
        <label>Ticket Number</label>
        <input type="text" name="ticket_number" class="form-control" value="" />
    </div>
   
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="subject" class="form-control" value="" />
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea class="form-control" name="description" rows="5"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Update Ticket</button>
                <a type="submit" href="{{route('tickets.index')}}" class="btn btn-danger">Cancel</a>
            </form>
            </div>
            <!-- ---------------------
                        end Default Form Elements
                    ---------------- -->
        </div>
    </div>
</div>
          <!-- /.row -->

@endsection
