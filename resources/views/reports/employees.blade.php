 @extends('home')

 @section('content')

     <div class="page-wrapper" style="display:inline">
         <!-- -------------------------------------------------------------- -->
         <!-- Bread crumb and right sidebar toggle -->
         <!-- -------------------------------------------------------------- -->
         <div class="page-breadcrumb">
             <div class="row">
                 <div class="col-5 align-self-center">
                     <h3 class="page-title">Employee Report</h3>
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
             <div class="row">

                 <div class="container">

                     <div class="row">

                         <div class="col-md-8" style="display: none">
                             <div class="row">
                                 <div class="col-lg-4">
                                     <div class="card card-border">
                                         <div class="card-body">
                                             <h5 class="card-title">Elvis Nolan</h5>
                                             <h6 class="card-subtitle mb-2 text-muted d-flex align-items-center">Dispatcher
                                             </h6>
                                             <p class="card-text pt-2">
                                                 0 Jobs<br>
                                                 LifetimeValue: $0</p>
                                             <a href="https://gaffis.in/frontier/website/users/show/244"
                                                 class="card-link">View Profile</a>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>



                         <div class="col-md-12">
                             <div class="card shadow card-border">
                                 <div class="card-body">
                                     <h4 class="card-title">Jobs created and dispatch technicians</h4>
                                     <div class="row">
                                         <div class="col-md-12">
                                             <div class="table-responsive">
                                                 <table class="table customize-table mb-0 v-middle w-100" id="zero_config666">
                                                     <thead class="table-dark">
                                                         <tr>
                                                             <th class="border-bottom border-top">Employee</th>
                                                             <th class="border-bottom border-top">Job Created</th>
                                                             <th class="border-bottom border-top">Job updated</th>
                                                             <th class="border-bottom border-top">Job Closed</th>
                                                             <th class="border-bottom border-top">Job Revenue</th>
                                                             <th class="border-bottom border-top">Average size Job</th>
                                                             <th class="border-bottom border-top">Activity</th>
                                                             <th class="border-bottom border-top">Messages</th>
                                                         </tr>
                                                         <tr class="table-success border-success">
                                                             <th class="border-bottom border-top">Total</th>
                                                             <th class="border-bottom border-top">{{ $job }}</th>
                                                             <th class="border-bottom border-top">{{ $job }}</th>
                                                             <th class="border-bottom border-top">{{ $job }}</th>
                                                             <th class="border-bottom border-top">${{ $alltotalGross }}</th>
                                                             @if ($job > 0)
                                                                 <th class="border-bottom border-top">
                                                                     ${{ intval($alltotalGross / $job) }}</th>
                                                             @else
                                                                 <td>N/A</td>
                                                             @endif
                                                             <th class="border-bottom border-top">{{ $totalActivity }}</th>
                                                             <th class="border-bottom border-top">{{ $totalChats }}</th>
                                                         </tr>
                                                     </thead>
                                                     <tbody>
                                                         @foreach ($employees as $employee)
                                                             <tr>
                                                                 <td>{{ $employee->name ?? null }}</td>
                                                                 <td>{{ $jobCountsByEmployee[$employee->id] }}</td>
                                                                 <td>{{ $jobCountsUpdatedBy[$employee->id] }}</td>
                                                                 <td>{{ $jobCountsClosedBy[$employee->id] }}</td>
                                                                 <td>${{ $grossTotalByEmployee[$employee->id] }}</td>
                                                                 @if ($jobCountsByEmployee[$employee->id] > 0)
                                                                     <td>${{ intval($grossTotalByEmployee[$employee->id] / $jobCountsByEmployee[$employee->id] )}}
                                                                     </td>
                                                                 @else
                                                                     <td>N/A</td>
                                                                 @endif
                                                                 <td>{{ $activity[$employee->id] }}</td>
                                                                 <td>{{ $chats[$employee->id] }}</td>
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



                     </div>



                 </div>

             </div>
         </div>



     </div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


     <script>
        $(document).ready(function() {
            $('#zero_config666').DataTable({
                "order": [[ 1, "desc" ]] // Sort by the second column (job count) in descending order
            });
        });
    </script>
 @stop
