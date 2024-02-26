
@extends('home')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')




   
<div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-5 align-self-center">
              <h4 class="page-title">Lead Source</h4>
              <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="settings.html">Settings</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Lead Source </li>
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
		
			<div class="row">
				<div class="col-9"> 
					<div class="card">
 						<div class="card-body">
							<div class="table-responsive">
								<table id="default_order" class="table table-striped table-bordered display text-nowrap" style="width: 100%" >
								<thead>
								<tr>
								<th>Lead Source</th>
								<th>Added By</th>
								<th>Used</th>
								<th>Date Added</th>
								<th>Action</th>
								</tr>
								</thead>
								<tbody>
								@foreach($leadSources as $leadSource)
									<tr>
										<td><p class="source_name">{{ $leadSource->source_name }}</p></td>
										<td>{{ optional($leadSource->addedByUser)->name }}</td>
										<td>{{ optional($leadSource->lastUpdatedByUser)->name }}</td>
										<td>{{ $leadSource->created_at->format('m-d-Y') }}</td>
										<td>
										<button type="button" class="btn btn-primary btn-sm edit-btn" 
												data-bs-toggle="modal" 
												data-bs-target="#samedata-modal2" 
												data-lead-source-id="{{ $leadSource->id }}" 
												data-lead-source-name="{{ $leadSource->source_name }}">
											<i class="fas fa-edit"></i>
										</button>
										<button type="button" class="btn btn-danger btn-sm delete-btn" 
												data-lead-source-id="{{ $leadSource->id }}" 
												data-lead-source-name="{{ $leadSource->source_name }}">
											<i class="fas fa-trash"></i>
										</button>
										</td>
									</tr>
								@endforeach  
								</tbody>
								<tfoot>
								<th>Lead Source</th>
								<th>Added By</th>
								<th>Used</th>
								<th>Date Added</th>
								<th>Action</th>
								</tr>
								</tfoot>
								</table>
							</div>
 						</div>
					</div>
				</div>
				
				<!-- <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header d-flex align-items-center">
									<h4 class="modal-title" id="exampleModalLabel1">Edit Lead Source</h4>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form>
										<div class="mb-3">
										<label for="recipient-name" class="control-label">Lead Source:</label>
										<input type="text" class="form-control" id="lead-source1" name="lead-source1" value="chat" />
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
									<button type="button" class="btn btn-success" id="updateLeadSourceBtn">Update</button>
								</div>
							</div>
						</div>
					</div> -->

					<div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header d-flex align-items-center">
								<h4 class="modal-title" id="exampleModalLabel1">Edit Lead Source</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="updateLeadSourceForm"> 
									<div class="mb-3">
										<label for="lead-source1" class="control-label">Lead Source:</label>
										<input type="text" class="form-control" id="lead-source1" name="lead-source1" value="lead-source1" />
									</div>
									<input type="hidden" id="lead-source-id" name="lead-source-id" value="{{ $leadSource->id }}"> 
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
								<button type="button" class="btn btn-success" id="updateLeadSourceBtn">Update</button>
							</div>
						</div>
					</div>
				</div>

				 
				<div class="col-3"> 
					
 					<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#samedata-modal" data-bs-whatever="@mdo"><i class="fas fa-tag"></i> Add New</button>
 					<div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header d-flex align-items-center">
									<h4 class="modal-title" id="exampleModalLabel1">Add Lead Source</h4>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form id="leadSourceForm">
									@csrf
										<div class="mb-3">
										<label for="lead-source" class="control-label">Lead Source:</label>
										<input type="text" class="form-control" id="lead-source" name="lead_source" />
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
									<!-- <button type="submit" class="btn btn-success">Save</button> -->
									<button type="button" class="btn btn-success" id="saveLeadSourceBtn">Save</button>
								</div>
							</div>
						</div>
					</div>
				  
					<hr/>
 					<div class="col-md-12 ">
						<div class="card-body">
						<h4 class="card-title">Managing lead sources</h4>
						<p class="card-text pt-2">A lead source helps you track how customers found your business. Google, Yelp, and Facebook are a few examples of common lead sources.</p>
						<h5 class="card-title  mt-3">Why track lead sources?</h5>
						<p class="card-text pt-2">Knowing where jobs come from is key for making smart marketing and advertising decisions. Invest more on sources that bring in lots of good jobs.</p>
						<h5 class="card-title  mt-3">How do I use lead sources?</h5>
						<p class="card-text pt-2">You can track the lead source for any customer or job. Call tracking will automatically add lead sources based on which phone number a customer calls.</p>
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
      </div>
      <!-- -------------------------------------------------------------- -->
      <!-- End Page wrapper  -->
      <!-- -------------------------------------------------------------- -->
    <!-- </div> -->
    <!-- -------------------------------------------------------------- -->
    <!-- End Wrapper -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- customizer Panel -->
    <!-- -------------------------------------------------------------- -->
<script>
		document.addEventListener('DOMContentLoaded', function () {
			var dataTable = $('#default_order').DataTable();
			$('#saveLeadSourceBtn').on('click', function () {
				var leadSource = $('#lead-source').val();
				$.ajax({
					url: '{{ route("lead-source-add") }}',
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {
						lead_source: leadSource
					},
					success: function (response) {
						$('#samedata-modal').modal('hide');
						location.reload();
					},
					error: function (error) {
						console.error(error);
					}
				});
			});

			let row;
			$(document).on('click', '.edit-btn', function () {
				var leadSourceId = $(this).data('lead-source-id');
				var leadSourceName = $(this).data('lead-source-name');
				row = $(this).closest('tr');
				$('#lead-source-id').val(leadSourceId);
				$('#lead-source1').val(leadSourceName);

				$('#samedata-modal2').modal('show');
			});

			$('#updateLeadSourceBtn').on('click', function () {
				var leadSourceId = $('#lead-source-id').val();
				var leadSourceName = $('#lead-source1').val();

				$.ajax({
					url: '{{ url("/update-lead-source/") }}' + '/' + leadSourceId,
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {
						lead_source: leadSourceName
					},
					success: function (response) {
						console.log('Success:', response);
						row.find('.source_name').text(leadSourceName)
						$('#samedata-modal2').modal('hide');

						// dataTable.ajax.reload();
					},
					error: function (error) {
						console.error(error);
					}
				});
			});
		});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.delete-btn', function () {
            var leadSourceId = $(this).data('lead-source-id');
            var leadSourceName = $(this).data('lead-source-name');
			var tr = $(this).closest('tr');
			// console.log(tr);return true;
            if (confirm('Are you sure you want to delete ' + leadSourceName + '?')) {
                $.ajax({
                    url: '{{ url("/delete-lead-source/") }}' + '/' + leadSourceId,
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
