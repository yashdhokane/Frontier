<div class="container-fluid">
    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->
    <!-- basic table -->
    <div class="row">
        <div class="col-md-6">
            <h5 class="card-title uppercase">Parts assigned to technician</h5>

<form id="assignProductForm" action="{{ url('store/assign-product') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4" style="display: none">
            <input type="hidden" name="technician_id[]" value="{{ $commonUser->id }}">
        </div>

        <div class="col-md-2">
            <label for="parts" class="control-label bold mb5">Parts</label>
            <select class="select2-with-menu-bg form-control" name="product_id[]" id="menu-bg-multiple"
                multiple="multiple" data-bgcolor="light" data-bgcolor-variation="accent-3"
                data-text-color="blue" style="width: 100%" required>
                @foreach ($product as $item)
                <option value="{{ $item->product_id }}">{{ $item->product_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="parts" class="control-label bold mb5">Quantity</label>
            <input type="number" class="form-control" name="quantity" id="quantity-input" required>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn waves-effect waves-light btn-primary"
                style="margin-top: 25px; margin-left: 20px; width: 140px;"
                id="assignButton">Assign</button>
        </div>
    </div>
</form>

<script>
    document.getElementById('assignButton').addEventListener('click', function () {
        const form = document.getElementById('assignProductForm');
        const productSelect = document.getElementById('menu-bg-multiple');
        const quantityInput = document.getElementById('quantity-input');

        // Validate that at least one product is selected
        if (productSelect.selectedOptions.length === 0) {
            alert('Please select at least one part.');
            return; // Stop submission
        }

        // Validate the quantity input
        if (!quantityInput.value || quantityInput.value <= 0) {
            alert('Please enter a valid quantity.');
            return; // Stop submission
        }

        // If all validations pass, submit the form
        form.submit();
    });
</script>




            <div class="card">
                <div class="card-body card-border shadow text-left">
                    <h4 class="card-title mb-2">{{ $technicianpart->name }}</h4>
                    <h6 class="mb-2">Assigned to </h6>
                    <ul class="list-group list-group-horizontal-xl">
                        @php
                        $assignedProducts = [];
                        @endphp

                        @foreach ($assign->where('technician_id', $technicianpart->id) as $assignment)
                        @if (!isset($assignedProducts[$assignment->product_id]))
                        @php
                        $assignedProducts[$assignment->product_id] = $assignment->quantity;
                        @endphp
                        @else
                        @php
                        $assignedProducts[$assignment->product_id] += $assignment->quantity;
                        @endphp
                        @endif
                        @endforeach

                        @if (empty($assignedProducts))
                        <p>No Part Assigned</p>
                        @else
                        @foreach ($assignedProducts as $productId => $quantity)
                        @php
                        $productItem = $product->find($productId);
                        @endphp
                        @if ($productItem)
                        <li class="list-group-item d-flex align-items-center">
                            <i class="text-info fas fa-user mx-2"></i>
                            {{ $productItem->product_name }} ({{ $quantity }})
                        </li>
                        @else
                        <li class="list-group-item d-flex align-items-center">
                            <i class="text-danger fas fa-exclamation-triangle mx-2"></i>
                            Product not found ({{ $quantity }})
                        </li>
                        @endif
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>


        </div>
        <div class="col-md-6">
            <h5 class="card-title uppercase">Tools assigned to technician</h5>

<form id="assignProductForm1" action="{{ url('store/assign-tool') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4" style="display: none">
            <input type="hidden" name="technician_id[]" value="{{ $commonUser->id }}">
        </div>

        <div class="col-md-2">
            <label for="parts" class="control-label bold mb5">Tools</label>
            <select class="select2-with-menu-bg form-control" name="product_id[]"
                id="menu-bg-multiple-parts" multiple="multiple" data-bgcolor="light"
                data-bgcolor-variation="accent-3" data-text-color="blue" style="width: 100%" required>
                @foreach ($tool as $item)
                <option value="{{ $item->product_id }}">{{ $item->product_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="parts" class="control-label bold mb5">Quantity</label>
            <input type="number" class="form-control" name="quantity" id="quantity-input-tools" required>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn waves-effect waves-light btn-primary"
                style="margin-top: 25px; margin-left: 20px; width: 140px;"
                id="assignToolButton">Assign</button>
        </div>
    </div>
</form>

<script>
    document.getElementById('assignToolButton').addEventListener('click', function () {
        const form = document.getElementById('assignProductForm1');
        const toolSelect = document.getElementById('menu-bg-multiple-parts');
        const quantityInput = document.getElementById('quantity-input-tools');

        // Validate that at least one tool is selected
        if (toolSelect.selectedOptions.length === 0) {
            alert('Please select at least one tool.');
            return; // Stop submission
        }

        // Validate the quantity input
        if (!quantityInput.value || quantityInput.value <= 0) {
            alert('Please enter a valid quantity.');
            return; // Stop submission
        }

        // If all validations pass, submit the form
        form.submit();
    });
</script>





            <div class="card">
                <div class="card-body card-border shadow text-left">
                    <h4 class="card-title mb-2">{{ $technicianpart->name }}</h4>
                    <h6 class="mb-2">Assigned to </h6>
                    <ul class="list-group list-group-horizontal-xl">
                        @php
                        $assignedProducts = [];
                        @endphp

                        @foreach ($toolassign->where('technician_id', $technicianpart->id) as $assignment)
                        @if (!isset($assignedProducts[$assignment->product_id]))
                        @php
                        $assignedProducts[$assignment->product_id] = $assignment->quantity;
                        @endphp
                        @else
                        @php
                        $assignedProducts[$assignment->product_id] += $assignment->quantity;
                        @endphp
                        @endif
                        @endforeach

                        @if (empty($assignedProducts))
                        <p>No Tool Assigned</p>
                        @else
                        @foreach ($assignedProducts as $productId => $quantity)
                        @php
                        $productItem = $tool->find($productId);
                        @endphp
                        @if ($productItem)
                        <li class="list-group-item d-flex align-items-center">
                            <i class="text-info fas fa-user mx-2"></i>
                            {{ $productItem->product_name }} ({{ $quantity }})
                        </li>
                        @else
                        <li class="list-group-item d-flex align-items-center">
                            <i class="text-danger fas fa-exclamation-triangle mx-2"></i>
                            Tool not found ({{ $quantity }})
                        </li>
                        @endif
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>


        </div>





    </div>
</div>