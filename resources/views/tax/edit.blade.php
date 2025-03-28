<form action="{{ route('tax.update') }}" method="post">
    @csrf

    <div class="mb-3">
        <input type="hidden" class="form-control" id="state_id" name="state_id" value="{{ $state->state_id }}" />
        <label for="state_name" class="control-label bold mb5">State: {{ $state->state_name }}</label>
        <input type="hidden" class="form-control" id="state_name" name="state_name" value="{{ $state->state_name }}"
            required />
    </div>
    <div class="mb-3">
        <label for="state_tax" class="control-label bold mb5">Tax Rate:</label>
        <input type="text" class="form-control" id="state_tax" name="state_tax" value="{{ $state->state_tax }}"
            required />
    </div>
	<div class="modal-footer">
        <button type="button" class="btn btn-secondary font-medium"
            data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary">Update</button>
    </div>
</form>
