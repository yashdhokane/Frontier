@extends('home')

@section('content')

<div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
		<div class="page-breadcrumb">
			<div class="row">
				<div class="col-5 align-self-center">
					<h3 class="page-title">Technician Report</h3>
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
	
		<div class="col-md-6">
			<div class="card shadow card-border">
				<div class="card-body">
					<h4 class="card-title">Jobs completed by technician</h4>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table customize-table mb-0 v-middle overflow-auto">
								<thead class="table-dark">
									<tr>
										<th class="border-bottom border-top">Technician Name</th>
										<th class="border-bottom border-top">Job Revenue</th>
										<th class="border-bottom border-top">Job Count</th>
										<th class="border-bottom border-top">Avg. Job Size</th>
									</tr>
									<tr class="table-success border-success">
										<th class="border-bottom border-top">Total</th>
										<th class="border-bottom border-top">$0</th>
										<th class="border-bottom border-top">0</th>
										<th class="border-bottom border-top">$0</th>
									</tr>
								</thead>
								<tbody>
        @foreach($technician as $user)
        <tr>
            <td>{{ $user->name ?? null }}</td>
            <td>$0</td>
            <td>0</td>
            <td>$0</td>
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
		
	
		 <div class="col-md-6">
			<div class="card shadow card-border">
				<div class="card-body">
					<h4 class="card-title">Time tracking (completed jobs)</h4>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table customize-table mb-0 v-middle overflow-auto">
								<thead class="table-dark">
									<tr>
										<th class="border-bottom border-top">Technician Name</th>
										<th class="border-bottom border-top">Total On Job Hrs</th>
										<th class="border-bottom border-top">Total Travel Hrs</th>
										<th class="border-bottom border-top">Total Hrs Per Job</th>
									</tr>
									<tr class="table-success border-success">
										<th class="border-bottom border-top">Total</th>
										<th class="border-bottom border-top">0</th>
										<th class="border-bottom border-top">0</th>
										<th class="border-bottom border-top">0</th>
									</tr>
								</thead>
								<tbody>
        @foreach($technician as $user)
        <tr>
            <td>{{ $user->name ?? null }}</td>
            <td>$0</td>
            <td>0</td>
            <td>$0</td>
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
  </div>

        @stop