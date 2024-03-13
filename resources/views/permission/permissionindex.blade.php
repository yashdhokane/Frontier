@extends('home')
@section('content')
<style>
    .required-field::after {
        content: " *";
        color: red;
    }
</style>   

    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    
   <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
 
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-5 align-self-center">
              <h4 class="page-title">Permissions</h4>
              <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                <div class="row ">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <h4 class="card-title">ADD PERMISSIONS</h4>
                            <form action="{{ route('permissions.store') }}" method="POST">
                                @csrf
                                <!-- Form fields go here -->
                                <div class="col-md-6">
                                    <label for="permission_name" class="form-label">Permission Name</label>
                                    <input type="text" class="form-control" id="permission_name" name="permission_name" required>
                                </div>
                                <!-- Add more form fields as needed -->
                                <button style="margin-top:10px;" type="submit" class="btn btn-primary">submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

             @if(session('success'))
    <div id="successMessage" style="margin-bottom:7px;" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

              <div class="card">
             
                <div class="card-body">
                   <div class="row mt-4">
                    <!-- Column -->
                  </div>
                  <div class="table-responsive mt-4">
               <table id="zero_config" class="table table-bordered text-nowrap">
    <thead>
        <tr>
            <th>Sr.NO</th>
            <th>Name</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $permission)
        <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $permission->name }}</td>
<td>{{ $convertDateToTimezone($permission->created_at) }}</td>
           
        <td>    <form action="{{ route('permissions.delete') }}" method="POST">
    @csrf
   <input type="hidden" name="id" value="{{ $permission->id }}">
    <button type="submit"  class="btn btn-danger delete-btn">Delete</button>
</form></td>

        </tr>
        @endforeach
    </tbody>
                      <tfoot>
                   
                        <!-- end row -->
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              <!-- ---------------------
                            end Tickets
                        ---------------- -->
            </div>
          </div>
        </div>
</div>

        <!-- row -->
      
        <!-- End row -->
    </div>
      </div>
        <!-- End page-wrapper -->

  <script>
        setTimeout(function() {
            document.getElementById('successMessage').style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
@section('script')
<script>
    $('#zero_config').DataTable();
</script>
@endsection
@endsection


