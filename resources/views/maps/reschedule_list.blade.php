<div class="row card-border shadow bg-light py-2 mb-3 ">
    <div class="col-3 bg-light px-3">
        <div class="maplocationpopup">
			<div class="ft12 bold">#{{ $getData->job_id }} {{ $getData->job_title }}</div>
 			<div class="mt-0 ft11 fw-medium text-info">{{ $getData->name }}</div>
            <div class="mt-0 ft11">{{ $getData->address }}, {{ $getData->city }}, {{ $getData->state }}, {{ $getData->zipcode }}</div>
            <input type="hidden" value="{{ $getData->name }}" name="rescheduleData[{{ $count }}][customer_name]">
            <input type="hidden" name="rescheduleData[{{ $count }}][job_id]" value="{{ $getData->job_id }}">
            <input type="hidden" name="rescheduleData[{{ $count }}][customer_id]" value="{{ $getData->customer_id }}">
            <input type="hidden" name="rescheduleData[{{ $count }}][assign_title]" value="{{ $getData->job_title }}">
            <input type="hidden" name="rescheduleData[{{ $count }}][assign_description]" value="{{ $getData->description }}">
            <input type="hidden" name="rescheduleData[{{ $count }}][technician_note_id]" value="{{ $getData->technician_note_id }}">
		</div>
    </div>
    <div class="col-3 bg-light px-3">
        <div class="form-group">
			<div class="bg-light"><label class="bold ft11">Technician</label></div>
			<div class="form-group mb-2">
				<select class="form-select me-sm-2 technician" id="inlineFormCustomSelect">
					<option value="" selected>-- Select Technician --</option>
					@if (isset($technician) && !empty($technician->count()))
						@foreach ($technician as $value)
							<option value="{{ $value->id }}">{{ $value->name }}</option>
						@endforeach
					@endif
				</select>
				<span class="error technicians_error"></span>
			</div>
        </div>
    </div>
    <div class="col-2 bg-light px-3">
        <div class="bg-light"><label class="bold ft11">Start Time</label></div>
        <div class="form-group">
            <input type="datetime-local" class="form-control start_date_time" min="{{ now()->format('Y-m-d\TH:i') }}"
                value="{{ $getData->start_date_time }}" name="rescheduleData[{{ $count }}][start_date_time]">
        </div>
    </div>
    <div class="col-2 bg-light px-3">
        <div class="bg-light"><label class="bold ft11">Duration</label></div>
        <div class="form-group">
            <select class="form-select me-sm-2" id="inlineFormCustomSelect" name="rescheduleData[{{ $count }}][duration]">
                <option value="60" @if ($getData->duration == 60) selected @endif>1 Hours</option>
                <option value="120" @if ($getData->duration == 120) selected @endif>2 Hours</option>
                <option value="180" @if ($getData->duration == 180) selected @endif>3 Hours</option>
                <option value="240" @if ($getData->duration == 240) selected @endif>4 Hours</option>
                <option value="300" @if ($getData->duration == 300) selected @endif>5 Hours</option>
            </select>
        </div>
    </div>
    <div class="col-2 bg-light px-3">
		<div class="bg-light"><label class="bold ft11">Travel Time</label></div>
        <div class="form-group">
            <select class="form-select me-sm-2" id="inlineFormCustomSelect" name="rescheduleData[{{ $count }}][driving_hours]">
                <option value="60" @if ($getData->driving_hours == 60) selected @endif>1 Hours</option>
                <option value="120" @if ($getData->driving_hours == 120) selected @endif>2 Hours</option>
                <option value="180" @if ($getData->driving_hours == 180) selected @endif>3 Hours</option>
                <option value="240" @if ($getData->driving_hours == 240) selected @endif>4 Hours</option>
                <option value="300" @if ($getData->driving_hours == 300) selected @endif>5 Hours</option>
            </select>
        </div>
    </div>
</div>