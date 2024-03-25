<div class="card text-white bg-light">
    <img class="card-img-top" src="holder.js/100px180/" alt="">
    <div class="card-body">





        <form action="{{ route('servicecategory.update') }}" method="post" class="form-horizontal form-material"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="category_id" value="{{ $ServiceCategory->id }}">
            <div class="form-group">
                <div class="col-md-12 mb-3"> <input type="text" value="{{ $ServiceCategory->category_name }}"
                        class="form-control" name="category_name" placeholder="Category Name"  required/>
                </div>


                <div class="col-md-12 mb-3">
                    <div class="
                                    fileupload
                                    btn btn-danger btn-rounded
                                    waves-effect waves-light
                                    btn-sm
                                  ">
                        <span><i class="ri-upload-line align-middle me-1 m-r-5"></i>Upload
                            Category Image</span>
                        <input type="file" value="{{ $ServiceCategory->category_image }}" name="category_image"
                            class="upload" />
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="submit" class="btn btn-info">Update</button>
                <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">
                    Cancel
                </button>

            </div>
        </form>
    </div>

</div>