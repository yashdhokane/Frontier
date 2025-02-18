

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Stock</b></label>
                    <select id="stock-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out Of Stock</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Category</b></label>
                    <select id="category-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                          @foreach ($product as $product)
                              <option value="{{ $product->id }}">
                                  {{ $product->category_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Manufacturer</b></label>
                    <select id="manufacturer-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                          @foreach ($manufacture as $manufacturer)
                              <option value="{{ $manufacturer->id }}">
                                  {{ $manufacturer->manufacturer_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Supppliers </b></label>
                    <select id="supppliers-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                         @foreach ($vendor as $item)
                           <option value="{{ $item->vendor_id }}">
                                  {{ $item->vendor_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Status</b></label>
                    <select id="status-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                          <option value="Publish">Active</option>
                          <option value="Draft">Inactive</option>
                    </select>
                </div>
            </div>
        
