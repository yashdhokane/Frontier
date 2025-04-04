@extends('home')
<!--<meta name="csrf-token" content="{{ csrf_token() }}">-->

@section('content')


    
     


        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-9 align-self-center">
              <h4 class="page-title">Tags</h4>
              <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#.">Settings</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Tags </li>
                  </ol>
                </nav>
              </div>
            </div>
            <div class="col-3 align-self-center">
			   <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#samedata-modal" data-bs-whatever="@mdo"><i class="fas fa-tag"></i> Add New</button>
 					<div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header d-flex align-items-center">
									<h4 class="modal-title" id="exampleModalLabel1">Add Tag</h4>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form>
										<div class="mb-3">
										<label for="tags" class="control-label">Tag:</label>
										<input type="text" class="form-control" id="tags" name="tags" />
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
									<button type="button" class="btn btn-success" id="saveTagsBtn">Save</button>
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
								<th>Tag</th>
								<th>Added By</th>
								<!-- <th>Used</th> -->
								<th>Date Added</th>
								<th>count</th>
								<th>Action</th>
								</tr>
								</thead>
								<tbody>
                                <!-- print_r($tagsList);
                                exit; -->
								@foreach($tagsList as $tag)
                                    <tr>
                                        <td><p class="tag_name">{{ $tag->tag_name }}</p></td>
                                        <!-- <td>{{ $tag->user_id }}</td> -->
										<td>{{ optional($tag->addedByUser)->name }}</td>
                                        <!-- <td>{{ optional($tag->updatedByUser)->name }}</td> -->

                                        <!-- <td>{{ $tag->created_by }}</td> -->
										<td>{{ $tag->created_at->format('m-d-Y') }}</td>
										<td>{{ $tag->count }}</td>

										<td>
										<button type="button" class="btn btn-primary btn-sm edit-btn" 
												data-bs-toggle="modal" 
												data-bs-target="#samedata-modal2" 
												data-tag-id="{{ $tag->tag_id }}" 
												data-tag-name="{{ $tag->tag_name }}">
											<i class="fas fa-edit"></i>
										</button>
										<button type="button" class="btn btn-danger btn-sm delete-btn" 
												data-tag-id="{{ $tag->tag_id }}" 
												data-tag-name="{{ $tag->tag_name }}">
											<i class="fas fa-trash"></i>
										</button>
										</td>
                                    </tr>
                                @endforeach
                        
								</tbody>
								<tfoot>
								<th>Tag</th>
								<th>Added By</th>
								<!-- <th>Used</th> -->
								<th>Date Added</th>
								<th>count</th>
								<th>Action</th>

								</tr>
								</tfoot>
								</table>
							</div>
 						</div>
					</div>
				</div>
				<!-- tags update model start -->
				<!-- <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header d-flex align-items-center">
								<h4 class="modal-title" id="exampleModalLabel1">Edit Tags</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="updateLeadSourceForm"> 
									<div class="mb-3">
										<label for="tag1" class="control-label">Tags:</label>
										<input type="text" class="form-control" id="tag1" name="tag1" value="tag1" />
									</div>
									<input type="hidden" id="" name="" value=""> 
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
								<h4 class="modal-title" id="exampleModalLabel1">Edit Tags</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="updateTagForm">
									<div class="mb-3">
										<label for="tag1" class="control-label">Tags:</label>
										<input type="text" class="form-control" id="tag1" name="tag1" value="tag1" />
									</div>
									<input type="hidden" id="tag-id" name="tag-id" value="{{ $tag->tag_id }}">
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
								<button type="button" class="btn btn-success" id="updateTagBtn">Update</button>
							</div>
						</div>
					</div>
				</div>
				<!-- tags update model end -->

				<div class="col-3"> 
					
					
					<div class="col-md-12 ">
						<div class="card-body card card-border shadow">
						<h4 class="card-title">Why use tags?</h4>
						<p class="card-text pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt minim veniam</p>
 						<p class="card-text pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
						<a href="#" class="card-link">Need more help?</a>
 						</div>
					</div>
					<hr/>
 					<div class="col-md-12 ">
					<div class="card text-white bg-primary">
					<div class="card-body">
					<span>
 						<i class="fas fa-tag fill-white"></i>
					</span>
					<h3 class="card-title mt-3 mb-0">213</h3>
					<p class="card-text text-white-50" style="color: white !important;">Total Tags</p>
					</div>
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
    <!-- </div> -->
    <!-- -------------------------------------------------------------- -->
    <!-- End Wrapper -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- customizer Panel -->
    <!-- -------------------------------------------------------------- -->
   
    <script>
		// for add tag query
			document.addEventListener('DOMContentLoaded', function () {
			document.getElementById('saveTagsBtn').addEventListener('click', function () {
				var Tags = document.getElementById('tags').value;
// alert('Tags');
				$.ajax({
					url: '{{ route("tags-add") }}',
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {
						tags: Tags
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
    
    // for edit model open and fetch
    let row;
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.edit-btn', function () {
            var tagId = $(this).data('tag-id');
            var tagName = $(this).data('tag-name');
			row = $(this).closest('tr');
			// alert(tagId);

            $('#tag-id').val(tagId);
            $('#tag1').val(tagName);
        });

        // for update query
        document.getElementById('updateTagBtn').addEventListener('click', function () {
            var tagId = document.getElementById('tag-id').value;
            var tagName = document.getElementById('tag1').value;
			// alert(tagId);
            $.ajax({
                url: '{{ url("/update-site-tag/") }}' + '/' + tagId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tag: tagName
                },
                success: function (response) {
                    console.log('Success:', response);
                    // $('#default_order').DataTable().ajax.reload();
					row.find('.tag_name').text(tagName)
                    $('#samedata-modal2').modal('hide');
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });

	//  // Delete functionality
	//  $(document).on('click', '.delete-btn', function () {
    //         var tagId = $(this).data('tag-id');
    //         var tagName = $(this).data('tag-name');

    //         if (confirm('Are you sure you want to delete ' + tagName + '?')) {
    //             $.ajax({
    //                 url: '{{ url("/delete-tag/") }}' + '/' + tagId,
    //                 type: 'DELETE',
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 },
    //                 success: function (response) {
    //                     console.log('Success:', response);
                       
    //                     location.reload(); 
    //                 },
    //                 error: function (error) {
    //                     console.error(error);
    //                 }
    //             });
    //         }
    //     });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.delete-btn', function () {
            var tagId = $(this).data('tag-id');
            var tagName = $(this).data('tag-name');
			var tr = $(this).closest('tr');
			// console.log(tr);return true;
            if (confirm('Are you sure you want to delete ' + tagName + '?')) {
                $.ajax({
                    url: '{{ url("/delete-site-tag/") }}' + '/' + tagId,
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

