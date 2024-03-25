<div class="row">
    <div class="col-3 bg-light py-2 px-3">
        <div class="form-group">
            <input type="text" class="form-control" value="{{ $getData->name }}" readonly name="rescheduleData[{{ $count }}][customer_name]">
            <input type="hidden" name="rescheduleData[{{ $count }}][job_id]" value="{{ $getData->job_id }}">
        </div>
    </div>
    <div class="col-3 bg-light py-2 px-3">
        <div class="form-group">
            <input type="datetime-local" class="form-control start_date_time" min="{{ now()->format('Y-m-d\TH:i') }}"
                value="{{ $getData->start_date_time }}" name="rescheduleData[{{ $count }}][start_date_time]">
        </div>
    </div>
    <div class="col-3 bg-light py-2 px-3">
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
    <div class="col-3 bg-light py-2 px-3">
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