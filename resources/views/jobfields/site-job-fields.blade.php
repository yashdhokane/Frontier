
@extends('home')
<!--<meta name="csrf-token" content="{{ csrf_token() }}">-->

@section('content')


        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-9 align-self-center">
              <h4 class="page-title">Job Fields</h4>
              <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="settings.html">Settings</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Job Fields </li>
                  </ol>
                </nav>
              </div>
            </div>
            <div class="col-3 align-self-center">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#samedata-modal" data-bs-whatever="@mdo"><i class="ri-file-add-line"></i> Add New</button>
 					<div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header d-flex align-items-center">
									<h4 class="modal-title" id="exampleModalLabel1">Add Job Field</h4>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form>
										<div class="mb-3">
										<label for="jobfields" class="control-label bold mb5">Job Field:</label>
										<input type="text" class="form-control" id="jobfields" name="jobfields" />
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
									<button type="button" class="btn btn-success" id="saveJobfieldsBtn">Save</button>
								</div>
							</div>
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
		
			<div class="row">
				<div class="col-9"> 
					<div class="card card-border shadow">
 						<div class="card-body">
							<div class="table-responsive">
								<table id="default_order" class="table table-striped table-bordered display text-nowrap" style="width: 100%" >
								<thead>
								<tr>
								<th>Job Field</th>
								<th>Added By</th>
								<!-- <th>Used</th> -->
								<th>Date Added</th>
								<th>Count</th>
								<th>Action</th>
								</tr>
								</thead>
								<tbody>
                                @foreach($JobfieldsList as $Jobfields)
                                    <tr>
                                        <td><p class="field_name">{{ $Jobfields->field_name }}</p></td>
										<td>{{ optional($Jobfields->addedByUser)->name }}</td>
                                        <!-- <td>{{ $Jobfields->field_id }}</td> -->
										<td>{{ $Jobfields->created_at->format('m-d-Y') }}</td>
										<td>{{ $Jobfields->count }}</td>
										<td>
										<button type="button" class="btn btn-primary btn-sm edit-btn" 
												data-bs-toggle="modal" 
												data-bs-target="#samedata-modal2" 
												data-job-fields-id="{{ $Jobfields->field_id }}" 
												data-job-fields-name="{{ $Jobfields->field_name }}">
											<i class="fas fa-edit"></i>
										</button>
										<button type="button" class="btn btn-danger btn-sm delete-btn" 
												data-job-fields-id="{{ $Jobfields->field_id }}" 
												data-job-fields-name="{{ $Jobfields->field_name }}">
											<i class="fas fa-trash"></i>
										</button>
										</td>
                                    </tr>
                                @endforeach 
								</tbody>
								<tfoot>
								<th>Job Field</th>
								<th>Added By</th>
								<!-- <th>Used</th> -->
								<th>Date Added</th>
								<th>Count</th>
								<th>Action</th>
								</tr>
								</tfoot>
								</table>
							</div>
 						</div>
					</div>
				</div>
				<!-- update model view code start -->
				<div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header d-flex align-items-center">
								<h4 class="modal-title" id="exampleModalLabel1">Edit Field</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="updateFieldForm">
									<div class="mb-3">
										<label for="field-name" class="control-label bold mb5">Field Name:</label>
										<input type="text" class="form-control" id="jobfields1" name="jobfields1">
									</div>
									<!-- <input type="hidden" id="field-id" name="field-id" value="{{ $Jobfields->field_id }}> -->
									<input type="hidden" id="job-fields-id" name="job-fields-id" value="{{ $Jobfields->field_id }}">

								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
								<button type="button" class="btn btn-success" id="updateFieldBtn">Update</button>
							</div>
						</div>
					</div>
				</div>

				<!-- update model view code end -->

				<div class="col-3"> 
					
					
 					<div class="col-md-12 ">
						<div class="card-body card card-border shadow">
						<h4 class="card-title">Why use job fields?</h4>
						<p class="card-text pt-2">Fields allow you to consistently capture and report on details about the work you're doing.</p>
 						<p class="card-text pt-2">These fields are visible on your jobs and estimates. You can customize the field values from this page so they make sense for your business needs.</p>
						<a href="#" class="card-link">Need more help?</a>
 						</div>
					</div>
					
			 
 				</div>
			</div>
          
		 
		 
	 
		 
				  
				  
				  
          <!-- -------------------------------------------------------------- -->
          <!-- Recent comment and chats -->
          <!-- -------------------------------------------------------------- -->
          <div class="row">
            <!-- column -->
            <div class="col-lg-6">
              <br/> 
            </div>
            <!-- column -->
            <div class="col-lg-6">
              <br/> 
            </div>
          </div>
          <!-- -------------------------------------------------------------- -->
          <!-- Recent comment and chats -->
          <!-- -------------------------------------------------------------- -->
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- footer -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- End footer -->
        <!-- -------------------------------------------------------------- -->
  
      <!-- -------------------------------------------------------------- -->
      <!-- End Page wrapper  -->
      <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->
    <!-- End Wrapper -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- customizer Panel -->
    <!-- -------------------------------------------------------------- -->
 

    <script>
			document.addEventListener('DOMContentLoaded', function () {
			document.getElementById('saveJobfieldsBtn').addEventListener('click', function () {
				var Jobfields = document.getElementById('jobfields').value;
// alert('1234');
				$.ajax({
					url: '{{ route("job-fields-add") }}',
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {
						jobfields: Jobfields
					},
					success: function (response) {
						// console.log('Success:', response);
						$('#samedata-modal').modal('hide');
						location.reload();
					},
					error: function (error) {
						console.error(error);
					}
				});
			});
		});


		// edit query in ajax
		let row;
		document.addEventListener('DOMContentLoaded', function () {
			$(document).on('click', '.edit-btn', function () {
				var jobFieldsId = $(this).data('job-fields-id');
				var jobFieldsName = $(this).data('job-fields-name');
				row = $(this).closest('tr');

				// console.log(row);
				$('#job-fields-id').val(jobFieldsId);
				$('#jobfields1').val(jobFieldsName);
			});
		});
	

		document.getElementById('updateFieldBtn').addEventListener('click', function () {
			var fieldId = document.getElementById('job-fields-id').value;
			var fieldName = document.getElementById('jobfields1').value;
// console.log(); return true;

			$.ajax({
				url: '{{ url("/update-site-field/") }}' + '/' + fieldId,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					'field-name': fieldName
				},
				success: function (response) {
					console.log('Success:', response);
					// $('#default_order').DataTable().ajax.reload();
					row.find('.field_name').text(fieldName)
					$('#samedata-modal2').modal('hide');
					// location.reload();
				},
				error: function (error) {
					console.error(error);
				}
			});
		});


		
	</script>	
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.delete-btn', function () {
			var jobFieldsId = $(this).data('job-fields-id');
			var jobFieldsName = $(this).data('job-fields-name');
			var tr = $(this).closest('tr');
			// console.log(tr);return true;
            if (confirm('Are you sure you want to delete ' + jobFieldsName + '?')) {
                $.ajax({
                    url: '{{ url("/delete-site-job-fields/") }}' + '/' + jobFieldsId,
                    type: 'DELETE', 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log('Success:', response);
						tr.remove();
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }
        });
    });
</script>

@endsection
