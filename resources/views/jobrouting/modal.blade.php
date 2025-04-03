<!-- Large Modal -->
<style>
.bt-switch input[type="checkbox"] {
	transform: scale(0.8);
	margin: 0;
}
.border-btm {
	border-bottom: 1px solid #D8D8D8;
}
</style>
<link href="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}"  rel="stylesheet" />

<div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header with Cross Icon -->
            <div class="modal-header title">
                <h4 class="modal-title uppercase">Routing Settings</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body modalbodyclass2 pt-1 pb-3">

                <form id="formstore" class="mb-0">

                    <div class="row">

                        <!-- Settings Panel with Scroll -->
                        <div class="col-md-5 settings-panel" style="max-height: 700px; overflow-y: auto;">
                            <div class="card card-border shadow p-3">
                                <div class="row mb-2 ">
                                    <label class="col-8 col-form-label bold">Auto Route Settings</label>
                                    <div class="col-4 bt-switch">
                                        <input type="checkbox" name="auto_route" data-toggle="switchbutton"
                                            data-on-color="success" data-off-color="default" id="switch_auto_route"
                                            onchange="toggleTimeInput(this, 'autoRouteTime')">
                                        <input type="hidden" name="auto_route_value" value="no">
                                    </div>
                                </div>
                                <div class="row mb-2 d-none" id="autoRouteTime">
                                    <div class="col-8 offset-4 mb-2">
                                        <input type="time" class="form-control" name="auto_route_time"
                                            value="08:00">
                                    </div>

                                    <div class="row mb-2">
                                        <label class="col-8 col-form-label bold">Time Constraints</label>
                                        <div class="col-4  bt-switch">
                                            <input type="checkbox" name="time_constraints" data-toggle="switchbutton"
                                                data-on-color="success" data-off-color="default"  id="switch_time_constraints"
                                                onchange="updateCheckboxValue(this)">
                                            <input type="hidden" name="time_constraints_value" value="no">
                                        </div>
                                    </div>
                                    <div class="row mb-2 border-btm">
                                        <label class="col-8 col-form-label bold">Priority Routing</label>
                                        <div class="col-4  bt-switch">
                                            <input type="checkbox" name="priority_routing" data-toggle="switchbutton"
                                                data-on-color="success" data-off-color="default"  id="switch_priority_routing"
                                                onchange="updateCheckboxValue(this)">
                                            <input type="hidden" name="priority_routing_value" value="no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2 border-btm">
                                    <label class="col-8 col-form-label bold">Automatic Re-Routing</label>
                                    <div class="col-4 bt-switch">
                                        <input type="checkbox" name="auto_rerouting" data-toggle="switchbutton"
                                            data-on-color="success" data-off-color="default"  id="switch_auto_rerouting"
                                            onchange="toggleTimeInput(this, 'autoReRouteTime')">
                                        <input type="hidden" name="auto_rerouting_value" value="no">
                                    </div>
                                    <div class="row mb-2 d-none border-btm" id="autoReRouteTime">
                                        <div class="col-8 offset-4 pb-2">
                                            <input type="time" class="form-control" name="auto_rerouting_time"
                                                value="08:00">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 border-btm">
                                    <label class="col-8 col-form-label bold">Auto Publishing</label>
                                    <div class="col-4 bt-switch">
                                        <input type="checkbox" name="auto_publishing" data-toggle="switchbutton"
                                            data-on-color="success" data-off-color="default"  id="switch_auto_publishing"
                                            onchange="toggleTimeInput(this, 'autoPublishingTime')">
                                        <input type="hidden" name="auto_publishing_value" value="no">
                                    </div>
                                    <div class="row mb-2 d-none border-btm" id="autoPublishingTime">
                                        <div class="col-8 offset-4 pb-2">
                                            <input type="time" class="form-control" name="auto_publishing_time"
                                                value="08:00">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2 border-btm">
                                    <label class="col-8 col-form-label bold">Publish Jobs</label>
                                    <div class="col-4 bt-switch">
                                        <input type="checkbox" name="publish_jobs" data-toggle="switchbutton"
                                            data-on-color="success" data-off-color="default"  id="publish_jobs">
                                    </div>
                                </div>
                                <div class="row mb-2 border-btm">
                                    <label class="col-8 col-form-label bold">Previous Open JObs</label>
                                    <div class="col-4 bt-switch">
                                        <input type="checkbox" name="p_open_job" data-toggle="switchbutton"
                                            data-on-color="success" data-off-color="default"  id="p_open_job">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label for="call-limit" class="col-6 col-form-label bold">Max Calls</label>
                                    <div class="col-6">
                                        <input type="number" id="call-limit" name="number_of_calls"
                                            class="form-control" placeholder="Set Limit" value="5">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tech List with Scroll -->
                        <div class="col-md-7  tech-list" style="max-height: 700px; overflow-y: auto;">
                            <div class="card card-border shadow p-3">

                                <div class="row">
                                    <!-- Loop through each technician in the $tech array -->
                                    @foreach ($tech as $technician)
                                        <div class="col-6">
                                            <div class="row  mt-1 mb-1 tech-item d-flex justify-content-between">
                                                <div class="col-1">
                                                    <input type="checkbox" class="form-check-input" id="tech{{ $technician->id }}" name="technicians[]"  value="{{ $technician->id }}" class="checkbox-technicians">
                                                </div>
                                                <div class="col-10">
                                                    <label for="tech{{ $technician->id }}" class="form-check-label bold">{{ $technician->name }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button id="selectAll" type="button" class="btn btn-info btn-rounded">Select All</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <button id="saveButton" type="button" class="btn btn-info btn-rounded mx-2 float-end">Set Routing</button>
                        </div>
                    </div>

                <form>
            </div>
        </div>
    </div>
</div>

<!-- Include the Bootstrap Switch JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateCheckboxValue(checkbox) {
        // Update the associated hidden input based on the checkbox state
        const hiddenField = checkbox.nextElementSibling;
        hiddenField.value = checkbox.checked ? 'yes' : 'no';
    }
</script>
<script>
    // Modal trigger code
    document.getElementById('setNewButton1').addEventListener('click', function() {
        var myModal = new bootstrap.Modal(document.getElementById('largeModal'), {});
        myModal.show();
    });

    // Toggle all checkboxes and update button text
    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.tech-item input[type="checkbox"]');
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        checkboxes.forEach(checkbox => checkbox.checked = !allChecked);

        // Update button text
        this.textContent = allChecked ? 'Select All' : 'Deselect All';
    });
</script>

<script>
    // Handle the save button click
    document.getElementById('saveButton').addEventListener('click', function() {
        
        $('#frontier_loader').show();
        // Collect the form data
        var formData = new FormData(document.getElementById('formstore'));
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Send the data using AJAX
        $.ajax({
            url: "{{ route('index.routing.Routesettingstore') }}", // Replace with your route
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Check if the response indicates success
                if (response.success) {
                     if(response.success == true){
                             // Reset the form fields (optional)
                            $('#formstore')[0].reset(); // Replace with the actual ID of your form

                            // Uncheck technician checkboxes
                            $('.checkbox-technicians').prop('checked', false);
                             setTimeout(function() {
                                $('#frontier_loader').hide();
                            }, 1000);
                            
                            // Hide the modal
                            $('#largeModal').modal('hide');

                            // $('#switch_auto_route').bootstrapSwitch('state', false, true);
                            // $('#switch_time_constraints').bootstrapSwitch('state', false, true);
                            // $('#switch_priority_routing').bootstrapSwitch('state', false, true);
                            // $('#switch_auto_rerouting').bootstrapSwitch('state', false, true);
                            // $('#switch_auto_publishing').bootstrapSwitch('state', false, true);
                            // Update Bootstrap Switch states
                            $('input[data-toggle="switchbutton"]').each(function() {
                                $(this).bootstrapSwitch('state', false, true);
                            });

                           
                        }

                   
                } else {
                    alert('There was an issue saving the settings.');
                     $('#frontier_loader').hide();
                }
            },
            error: function(xhr, status, error) {
                // Handle error
                alert('An error occurred: ' + error);
                 $('#frontier_loader').hide();
            }
        });

    });
</script>

