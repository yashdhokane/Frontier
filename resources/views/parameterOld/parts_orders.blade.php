<!-- Add this spinner inside your row or wherever you want it to appear -->
<!-- Full-screen loader with blur effect -->
<div id="loader" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-light bg-opacity-75 text-center d-flex align-items-center justify-content-center z-index-999">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>


<style>
/* Add this CSS to apply the blur effect */
body.loading {
    filter: blur(1px);  /* Apply blur effect to the body */
    pointer-events: none;  /* Disable interactions */
}

/* Loader styling */
#loader {
   
    z-index: 9999;  /* Make sure the loader appears above everything else */
    backdrop-filter: blur(1px);  /* Optional: Blur the background of the loader itself */
}

#searchResults h3 a {
    font-size: 16px;
    margin-bottom: 0px;
    padding-bottom: 0px;
}
#searchResults p {
    font-size: 13px;
    margin-bottom: 10px;
    padding-bottom: 0px;
    line-height: 16px;
}
#searchResults h3 {
    margin-bottom: 5px;
}
/* Make the modal body scrollable */
.modal-body {
    max-height: 600px;  /* Adjust the height as needed */
    overflow-y: auto;   /* Enable vertical scrolling */
}

.modal-header {
    padding-top: 5px;
    margin-bottom: 0px !important;
    padding-bottom: 0px !important;}
</style>

<div class="row">
    <div class="col-sm-3 mb-2" style="display:none;">
        <label class="text-nowrap"><strong>Status</strong></label>
        <select id="status-filter" class="form-control">
            <option value="">All</option>
            <option value="Publish">Active</option>
            <option value="Draft">Inactive</option>
        </select>
    </div>

    <div class="col-sm-3 mb-2">
        <label class="text-nowrap"><strong>Search</strong></label>
        <input type="text" id="search-input" class="form-control" placeholder="Search parts...">
    </div>
     
     <div class="col-sm-3 mb-2 mt-3 ms-auto">
        <button id="reload-button" class="btn btn-secondary">Reload</button>
    </div>
</div>

<div id="product-container" class="row">
    @foreach ($products as $index => $item)
    <div class="col-md-4 mb-3 product-item" data-status="{{ $item->status }}" data-name="{{ $item->product_name }}">
        <div class="card shadow-sm h-100">
            <div class="card-body card-border card-shadow">
                <h5 class="card-title py-1 d-flex justify-content-between">
                    <strong>{{ $item->product_name }}</strong>
                    <!-- Icon to trigger the modal with dynamic product name -->

                    </button>

                </h5>

                <div class="d-flex align-items-center mb-3">
                    @if ($item->product_image)
                    <img src="{{ asset('public/product_image/' . $item->product_image) }}"
                        alt="{{ $item->product_name }}" class="rounded-circle" width="45" />
                    @else
                    <img src="{{ asset('public/images/default-part-image.png') }}"
                        alt="{{ $item->product_name }}" class="rounded-circle" width="45" />
                    @endif
                    <div class="ms-2">
                        <h6 class="user-name mb-0" data-name="name">{{ $item->product_name }}</h6>
                    </div>
                </div>

                <div>
                    <div class="mb-1"><strong>Category:</strong> {{ $item->categoryProduct->category_name ?? 'N/A' }}</div>
                    <div class="mb-1"><strong>Manufacturer:</strong> {{ $item->manufacturername->manufacturer_name ?? 'N/A' }}</div>
                    <div class="mb-1"><strong>Price:</strong> {{ $item->base_price ?? 'N/A' }}</div>



                </div>
                    <div class="mb-1">
                <button class="btn btn-light-primary pointer" data-bs-toggle="modal" data-bs-target="#searchProductModal" data-product-name="{{ $item->product_name }}">
                    <i class="ri-send-plane-line align-middle"></i> Search
                </button>
</div>

            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="searchProductModal" tabindex="-1" aria-labelledby="searchProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="product-name"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display Product Name dynamically here -->
          

  

                @include('parameterOld.search_engine_parts_render')
            </div>
        </div>
    </div>
</div>




