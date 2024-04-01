<div class="row justify-content-evenly">

    <div class="col-md-5 p-3 shadow ">
        <h5 class="d-flex justify-content-between">
            <span><i class="fa fa-calendar-alt"></i> Schedule</span> <i class="fa fa-edit"></i>
        </h5>
        <hr>
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <label for="inputEmail3" class="control-label col-form-label">Start Time</label>

            </div>
        </div>
        <div class="d-flex gap-5 my-2">
            <label for="fdate">From</label>
            <input type="date" name="start_date" id="start_date" class="form-control">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-clock feather-sm">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </span>
                <select class="form-control" name="start_time" id="start_time"
                aria-label="Username" aria-describedby="basic-addon1">
                <option >00:00</option>
                    <?php
                    // Generate options for end time
                    for ($hour = 7; $hour <= 19; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $time = sprintf('%02d:%02d', $hour, $minute);
                            echo "<option value=\"$time\">$time</option>";
                        }
                    }
                    ?>
                </select>
            </div>

        </div>
        <div class="d-flex gap-5 my-2">
            <label for="tdate">To</label>
            <input type="date" name="end_date" id="end_date" class="ms-3 form-control">
          
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-clock feather-sm">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </span>
                <select class="form-control" name="end_time" id="end_time"
                aria-label="Username" aria-describedby="basic-addon1">
                <option >00:00</option>
                    <?php
                    // Generate options for end time
                    for ($hour = 7; $hour <= 19; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $time = sprintf('%02d:%02d', $hour, $minute);
                            echo "<option value=\"$time\">$time</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-5 p-3 shadow ">
        <h5 class="d-flex justify-content-between">
            <span><i class="fa fa-calendar-alt"></i> Event Details</span>
        </h5>
        <hr>
        <div class="my-2">
            <input type="text" name="event_name" id="event_name" placeholder="Name"
                class="border-0 border-bottom form-control bg-light">
        </div>
        <div class="my-2">
            <textarea name="event_description" id="event_description" cols="3" rows="3" placeholder="Note"
                class="border-0 border-bottom form-control bg-light"></textarea>
        </div>
        <div class="my-2">
            <input type="text" name="event_location" id="event_location"
                placeholder="Location" class="border-0 border-bottom form-control bg-light">
        </div>
    </div>

</div>