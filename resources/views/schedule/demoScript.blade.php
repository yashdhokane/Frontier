@section('script')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&loading=async&callback=initMap&libraries=marker"
        async></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.JobOpenModalButton', function(event) {
                event.preventDefault(); // Prevent the default anchor click behavior
                var tech_id = $(this).data('tech-id');
                var tech_name = $(this).data('tech-name');
                var date = $(this).data('date');
                $.ajax({
                    url: '{{ route('schedule.getALlJobDetails') }}',
                    method: 'GET',
                    data: {
                        tech_id: tech_id,
                        date: date
                    },
                    success: function(response) {
                        var jobs = response;
                        var ticketShowRoute =
                        "{{ route('tickets.show', ':id') }}"; // Placeholder for job ID
                        $('#allJobsTechnicianLabel46').empty();
                        $('#allJobsTechnicianLabel46').append(tech_name +
                            ' - Dispatch Schedule');

                        $('.openJobTechDetails').empty();


                        // Check if there are jobs in the response
                        if (jobs.length === 0) {
                            // If no jobs are available, show a message
                            $('.openJobTechDetails').append(
                                '<div class="col-12"><p>There is no job available.</p></div>'
                            );
                        } else {
                            // If jobs are available, iterate over each job and append its details
                            jobs.forEach(function(job) {
                                // Create the HTML structure for each job
                                var jobHtml = `
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                            <!-- Job ID and Badge -->
                                            <h5 class="card-title">
                                                <i class="fas fa-id-badge px-2"></i>
                                                <strong>Job #${job.job_model ? job.job_model.id : 'N/A'}</strong>
                                            </h5>

                                            <!-- Job Time Range -->
                                            <p class="text-muted">
                                                ${job.start_date_time && job.end_date_time ? formatDateRange(job.start_date_time, job.end_date_time, job.interval) : ''}
                                            </p>

                                            <!-- Job Title -->
                                            <div class="mb-2">
                                                <i class="fas fa-ticket-alt px-2"></i>
                                                <strong>${job.job_model ? job.job_model.job_title : 'N/A'}</strong>
                                            </div>

                                            <!-- User Info -->
                                            <div class="mb-2">
                                                <i class="fas fa-user px-2"></i>
                                                <strong>${job.job_model && job.job_model.user ? job.job_model.user.name : 'N/A'}</strong>
                                                <p class="text-muted ps-4 m-0 ms-2">
                                                    ${job.job_model && job.job_model.addresscustomer ? job.job_model.addresscustomer.address_line1 : 'N/A'},
                                                    ${job.job_model && job.job_model.addresscustomer ? job.job_model.addresscustomer.zipcode : ''}
                                                </p>
                                                <p class="text-muted ps-4 m-0 ms-2">
                                                    ${job.job_model && job.job_model.user ? job.job_model.user.mobile : 'N/A'}
                                                </p>
                                            </div>

                                            <!-- Technician Info -->
                                            <div class="mb-2">
                                                <i class="fas fa-user-secret px-2"></i>
                                                <strong>${job.technician && job.technician.name ? job.technician.name : 'N/A'}</strong>
                                            </div>

                                            <!-- Job Status -->
                                            <div class="mb-3">
                                                <i class="fas fa-tag px-2"></i>
                                                <span class="badge bg-primary">${job.job_model ? job.job_model.status : 'N/A'}</span>
                                            </div>

                                            <!-- Edit Button -->
                                            <div class="d-flex justify-content-between">
                                                <a href="${ticketShowRoute.replace(':id', job.job_model ? job.job_model.id : '#')}?mode=edit#editdetails" target="_blank">
                                                <button class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="${ticketShowRoute.replace(':id', job.job_model ? job.job_model.id : '#')}">
                                                <button class="btn btn-outline-primary btn-sm">
                                                    View
                                                </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                                // Append the jobHtml into the container
                                $('.openJobTechDetails').append(jobHtml);

                            });



                        }
                        $('#allJobsTechnician').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:',
                            error);
                    }
                });
            });
            // Helper function to format the start and end time with the interval adjustment
            function formatDateRange(startDate, endDate, interval) {
                var startDateTime = moment(
                    startDate); // Assuming moment.js is available
                var endDateTime = moment(endDate);

                // Add the interval if provided
                if (interval) {
                    startDateTime.add(interval, 'hours');
                    endDateTime.add(interval, 'hours');
                }

                // Format the dates
                return startDateTime.format('MMM D YYYY h:mm A') + ' - ' +
                    endDateTime.format('h:mm A');
            }
        });



        $(document).ready(function() {

            // $(document).on("click", ".tech_extend_width", function(event) {
            //     event.stopPropagation(); // Prevents the event from bubbling up

            //     var className = $(this).data("class-name");
            //     var jobClass = $(this).data("jobclass-name");
            //     var maxWidth = $(this).data("max-width");


            //     $("." + className).each(function() {
            //         var $this = $(this);
            //         var currentWidth = $this.css("width");

            //         var $show_address = $this.find("." + 'show_address');
            //         var $hide_address = $this.find("." + 'hide_address');

            //         if (currentWidth === "400px") {
            //             $this.attr("style", "width: 100px !important;");
            //             if (!$this.hasClass("tech-header")) {
            //                 $this.attr("style", "width: 100px !important; display: flex;");
            //             }

            //             $hide_address.attr("style", "display: block");
            //             $show_address.attr("style", "display: none");
            //         } else {
            //             $this.attr("style", "width: 400px !important; display: flex;");
            //             $hide_address.attr("style", "display: none");
            //             $show_address.attr("style", "display: block");
            //         }

            //         // New functionality: Adjust width based on child count
            //         var $children = $this.find("." + jobClass);
            //         var $maxWidth = $this.find("." + maxWidth);
            //         var childCount = $children.length;


            //         if (childCount > 0) {
            //              if (currentWidth === "400px") {
            //                 var newWidth = 100 / childCount;
            //             } else {
            //                 var newWidth = 400 / childCount;
            //             }
            //         }

            //         $children.each(function() {
            //             // Get the current inline styles
            //             var currentStyle = $(this).attr("style") || "";

            //             // Update or add the width with !important
            //             var updatedStyle = currentStyle.replace(/width:\s*\d+px\s*!important;/, '') + " width: " + newWidth + "px !important;";

            //             // Apply the updated styles
            //             $(this).attr("style", updatedStyle);
            //         });
            //         $maxWidth.each(function() {
            //             // Get the current inline styles
            //             var currentStyle = $(this).attr("style") || "";

            //             // Update or add the width with !important
            //             var updatedStyle = currentStyle.replace(/max-width:\s*\d+px\s*!important;/, '') + " max-width: " + newWidth + "px !important;";

            //             // Apply the updated styles
            //             $(this).attr("style", updatedStyle);
            //         });


            //     });
            // });



            $(document).on("click", ".tech_profile", function(event) {
                // Check if the clicked element is an image
                if ($(event.target).is("img")) {
                    return; // Exit if the clicked element is an image
                }
                event.preventDefault();

                var profileLink = $(this).closest(".tech_profile");
                var popupContainer = profileLink.next(".popupContainer");

                $(".popupContainer").not(popupContainer).fadeOut();

                var topPosition = profileLink.offset().top + profileLink.outerHeight();
                var leftPosition = profileLink.offset().left;

                popupContainer.css({
                    top: topPosition + "px",
                });

                popupContainer.fadeIn();
            });

            // Click event handler for message-popup links
            $(document).on("click", ".message-popup", function(event) {
                event.preventDefault(); // Prevent default link behavior

                var messagePopup = $(this);
                var smscontainer = messagePopup.closest(".tech-header").find(".smscontainer");

                // Hide all other open smscontainers and settingcontainers
                $(".smscontainer").not(smscontainer).fadeOut();
                $(".settingcontainer").fadeOut();

                // Set position of the smscontainer to the right of popupContainer
                smscontainer.css({
                    top: messagePopup.offset().top - 10 + "px",
                    left: messagePopup.offset().left + $(".popupContainer").outerWidth() - 60 +
                        "px", // Adjust 10 pixels for spacing
                });

                smscontainer.fadeToggle();
            });

            // Click event handler for setting-popup links
            $(document).on("click", ".setting-popup", function(event) {
                event.preventDefault(); // Prevent default link behavior

                var settingPopup = $(this);
                var settingcontainer = settingPopup.closest(".tech-header").find(".settingcontainer");

                // Hide all other open settingcontainers and smscontainers
                $(".settingcontainer").not(settingcontainer).fadeOut();
                $(".smscontainer").fadeOut();

                // Set position of the settingcontainer to the right of popupContainer (next to the message container)
                settingcontainer.css({
                    top: settingPopup.offset().top - 50 + "px",
                    left: settingPopup.offset().left + $(".popupContainer").outerWidth() + $(
                        ".smscontainer").outerWidth() - 315 + "px", // Adjust for both containers
                });

                settingcontainer.fadeToggle();
            });

            // Click event listener for the document to close popups when clicking outside
            $(document).click(function(event) {
                var target = $(event.target);

                if (
                    !target.closest(".popupContainer").length &&
                    !target.closest(".tech_profile").length &&
                    !target.closest(".smscontainer").length &&
                    !target.closest(".settingcontainer").length
                ) {
                    $(".popupContainer, .smscontainer, .settingcontainer").fadeOut();
                }
            });
        });

        $(function() {
            $(".day").sortable({
                connectWith: ".day",
                cursor: "move",
                helper: "clone",
                items: "> .dragDiv",
                stop: function(event, ui) {
                    var $item = ui.item;
                    var eventLabel = $item.text();
                    var newDay = $item.parent().attr("id");


                    // Here's where am ajax call will go

                }
            }).disableSelection();
        });

        $(document).ready(function() {

            var isDragging = false;
            var isResizing = false;

            // Prevent tooltip from showing while dragging
            $(document).on('mouseenter', '.stretchJob', function() {
                if (!isDragging) {
                    var template = $(this).find('.template');
                    if (!this._tippy) {
                        tippy(this, {
                            content: template.html(),
                            allowHTML: true,
                        });
                    }
                }
            });

            $(document).on('click', '.clickPoint1', function(e) {
                if (!isResizing) {
                    e.stopPropagation();
                    var popupDiv = $(this).find('.popupDiv1');

                    // Hide any previously displayed popupDiv elements
                    $('.popupDiv1').not(popupDiv).hide();

                    // Calculate the position of the popupDiv based on the clicked point
                    var mouseX = e.pageX - 180;
                    var mouseY = e.pageY - 100;

                    // Get the dimensions of the popupDiv and the window
                    var popupWidth = popupDiv.outerWidth();
                    var popupHeight = popupDiv.outerHeight();
                    var windowWidth = $(window).width();
                    var windowHeight = $(window).height();

                    // Calculate the position for the popupDiv, ensuring it stays within the window
                    var topPosition = mouseY;
                    var leftPosition = mouseX;

                    // Adjust the position if the popupDiv overflows the window
                    if (topPosition + popupHeight > windowHeight) {
                        topPosition = windowHeight - popupHeight - 10; // Add a margin of 10px
                    }
                    if (leftPosition + popupWidth > windowWidth) {
                        leftPosition = windowWidth - popupWidth - 10; // Add a margin of 10px
                    }

                    // Set the position and show the popupDiv
                    popupDiv.css({
                        position: 'absolute',
                        top: topPosition + 'px',
                        left: leftPosition + 'px',
                        zIndex: 1000 // Ensure the popupDiv is above other elements
                    }).toggle();

                    // Add keydown event listener to hide popupDiv when Esc is pressed
                    $(document).on('keydown', function(e) {
                        if (e.key === "Escape") { // Check if the pressed key is "Esc"
                            popupDiv.hide();
                        }
                    });
                    // Hide the popup div when clicking outside of it
                    $(document).on('click', function(e) {
                        popupDiv.hide();
                    });

                }
            });

            $('.eventSchedule').on('click', function() {
                var id = $(this).attr('data-id');
                $('#event_technician_id').val(id);
            });

            $('.event_start_time').hide();
            $('.event_end_time').hide();
            $('.f_start').hide();
            $('.s_to').hide();

            $(document).on('change', '.event_type', function() {
                var event_type = $(this).val();
                if (event_type == 'full') {
                    $('.event_start_date').show();
                    $('.event_end_date').show();
                    $('.event_start_time').hide();
                    $('.event_end_time').hide();
                    $('.f_start').hide();
                    $('.s_to').hide();
                } else {
                    $('.event_start_date').show();
                    $('.event_start_time').show();
                    $('.event_end_date').hide();
                    $('.event_end_time').show();
                    $('.f_start').show();
                    $('.s_to').show();
                }
            });

            $('#addEvent').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this); // 'this' refers to the form DOM element

                // Make an AJAX request to submit the form data
                $.ajax({
                    url: $(this).attr('action'), // Get the form action attribute
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        // Handle success response here

                        if (data.success === true) {
                            // If success is true, close the current modal
                            $('#event').modal('hide');
                            // Display a success message using SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Event added successfully.'
                            }).then(function() {
                                // Reset form fields
                                $('#addEvent')[0].reset();
                                location.reload();

                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('Error submitting form data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Operation failed. Please try again.' + error
                        });
                    }
                });
            });

            $(document).on('change', '.technician_check', function() {
                var isChecked = $(this).prop('checked');
                var id = $(this).data('id'); // Retrieve the value of the data-id attribute

                if (isChecked) {
                    // Show elements with class tech-header and day that match the id
                    $('.tech-header[data-tech-id="' + id + '"]').show();
                    $('.clickPoint1[data-technician-id="' + id + '"]').show();
                } else {
                    // Hide elements with class tech-header and day that match the id
                    $('.tech-header[data-tech-id="' + id + '"]').hide();
                    $('.clickPoint1[data-technician-id="' + id + '"]').hide();
                }
            });

            // Function to initialize draggable elements
            function initializeDraggable() {
                $('.day .dragDiv').draggable({
                    helper: 'clone',
                    cursor: 'move',
                    start: function(event, ui) {
                        if (isResizing) {
                            return false; // Prevent dragging if resizing
                        }
                        isDragging = true;
                    },
                    stop: function(event, ui) {
                        isDragging = false;
                    }
                });
            }

            // Function to revert drag operation
            function revertDrag(ui) {
                ui.helper.animate(ui.originalPosition, "slow");
            }

            // Function to initialize droppable elements
            function initializeDroppable() {
                $('.day').droppable({
                    tolerance: 'pointer',
                    drop: function(event, ui) {
                        var jobId = ui.draggable.attr('id');
                        var duration = ui.draggable.attr('data-duration');
                        var newTechnicianId = $(this).data('technician-id');
                        var techName = ui.draggable.attr('data-technician-name');
                        var timezone = ui.draggable.attr('data-timezone-name');
                        var date = $(this).data('date');
                        var time = $(this).data('slot-time');
                        let name;
                        let zoneName;

                        var height_slot = duration ? (duration / 30) * 40 : 0;

                        // Temporarily move the job to the new position
                        var originalContainer = ui.draggable.parent();
                        var newContainer = $(event.target);
                        var originalJobCount = originalContainer.children('.dts').length;
                        var newJobCount = newContainer.children('.dts').length + 1;
                        var newJobWidth = 100 / newJobCount;
                        var originalJobWidth = 100 / (originalJobCount - 1);

                        // Remove the draggable element from its original container
                        ui.draggable.remove();

                        // Set the width of existing jobs in the new container
                        newContainer.children('.dts').each(function() {
                            $(this).css('width', newJobWidth + 'px');
                        });

                        // Append the new job with the calculated width
                        var newJobElement = $('<div>', {
                            id: jobId,
                            class: 'dts dragDiv stretchJob border width_job_' + newTechnicianId,
                            css: {
                                height: height_slot + 'px',
                                position: 'relative',
                                width: newJobWidth + 'px'
                            },
                            'data-duration': duration,
                            'data-technician-name': techName,
                            'data-timezone-name': timezone,
                            html: ui.draggable.html()
                        });

                        newContainer.append(newJobElement);

                        // Update the width of the original container if any jobs remain
                        if (originalJobCount > 1) {
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });
                        }

                        // Make the new job element draggable
                        newJobElement.draggable({
                            helper: 'clone',
                            cursor: 'move',
                            start: function(event, ui) {
                                if (isResizing) {
                                    return false;
                                }
                                isDragging = true;
                            },
                            stop: function(event, ui) {
                                isDragging = false;
                            }
                        });

                        // Ask for confirmation to move the job
                        $.ajax({
                            url: "{{ route('get.techName') }}",
                            type: 'GET',
                            data: {
                                techId: newTechnicianId,
                            },
                            success: function(response) {
                                name = response.name;
                                zoneName = response.time_zone.timezone_name;

                                Swal.fire({
                                    title: `Do you want to move job from ${techName} to ${name}?`,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        if (timezone == zoneName) {
                                            updateJobTechnician(jobId, duration,
                                                date, time, newTechnicianId, ui,
                                                name, zoneName, newJobElement,
                                                originalContainer,
                                                originalJobCount);
                                        } else {
                                            Swal.fire({
                                                title: `Do you want to change the Job from ${timezone} to ${zoneName}?`,
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'No',
                                                reverseButtons: true
                                            }).then((innerResult) => {
                                                if (innerResult
                                                    .isConfirmed) {
                                                    updateJobTechnician(
                                                        jobId, duration,
                                                        date, time,
                                                        newTechnicianId,
                                                        ui, name,
                                                        zoneName,
                                                        newJobElement,
                                                        originalContainer,
                                                        originalJobCount
                                                    );
                                                } else {
                                                    revertTempMove(
                                                        newJobElement,
                                                        originalContainer,
                                                        originalJobCount
                                                    );
                                                }
                                            });
                                        }
                                    } else {
                                        revertTempMove(newJobElement,
                                            originalContainer, originalJobCount);
                                    }
                                });
                            },
                            error: function(error) {
                                revertTempMove(newJobElement, originalContainer,
                                    originalJobCount);
                                console.error(error);
                            }
                        });

                        function updateJobTechnician(jobId, duration, date, time, newTechnicianId, ui,
                            name, zoneName, newJobElement, originalContainer, originalJobCount) {
                            $.ajax({
                                url: '{{ route('updateJobTechnician') }}',
                                method: 'POST',
                                data: {
                                    job_id: jobId,
                                    duration: duration,
                                    date: date,
                                    time: time,
                                    technician_id: newTechnicianId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    console.log('Job updated successfully:', response);
                                    if (response.success) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'Job moved successfully',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        revertTempMove(newJobElement, originalContainer,
                                            originalJobCount);
                                        console.error('Error:', response.error);
                                    }
                                },
                                error: function(error) {
                                    revertTempMove(newJobElement, originalContainer,
                                        originalJobCount);
                                    console.error(error);
                                }
                            });
                        }

                        function revertTempMove(newJobElement, originalContainer, originalJobCount) {
                            // Remove the temporary new job element from the new container
                            newJobElement.remove();

                            // Append the draggable job back to the original container
                            originalContainer.append(ui.draggable);

                            // Reinitialize draggable functionality for the element
                            ui.draggable.draggable({
                                helper: 'clone',
                                cursor: 'move',
                                start: function(event, ui) {
                                    if (isResizing) {
                                        return false;
                                    }
                                    isDragging = true;
                                },
                                stop: function(event, ui) {
                                    isDragging = false;
                                }
                            });

                            // Calculate the correct number of jobs in the original container
                            var currentJobCount = originalContainer.children('.dts').length;

                            // Update the width of all jobs in the original container
                            var originalJobWidth = 100 / currentJobCount;
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });

                            // Update the width of all jobs in the new container
                            var newJobWidth = 100 / newContainer.children('.dts').length;
                            newContainer.children('.dts').each(function() {
                                $(this).css('width', newJobWidth + 'px');
                            });
                        }

                    }
                });
            }

            // Function to initialize resizable elements
            function initializeResizable() {
                interact('.stretchJob').resizable({
                        edges: {
                            left: false,
                            right: false,
                            bottom: true,
                            top: false
                        }
                    })
                    .on('resizestart', function(event) {
                        // Disable dragging while resizing
                        isResizing = true;

                        // Set original height if not already set
                        if (!event.target.dataset.originalHeight) {
                            event.target.dataset.originalHeight = event.target.style.height;
                        }
                    })
                    .on('resizemove', function(event) {
                        let target = event.target;
                        let originalHeight = parseFloat(target.dataset.originalHeight) || parseFloat(target
                            .style.height) || 0;
                        let heightChange = event.rect.height - originalHeight;

                        // Update the height directly with the cursor movement
                        let newHeight = originalHeight + heightChange;

                        // Set a minimum height to prevent collapsing too much
                        let minHeight = 40; // Equivalent to 30 minutes
                        if (newHeight < minHeight) {
                            newHeight = minHeight;
                        }

                        // Update the element's height
                        target.style.height = newHeight + 'px';

                        // Calculate and update the new duration
                        let heightPer30Min = 40; // height for 30 minutes
                        let newDuration = Math.round(newHeight / heightPer30Min) * 30;
                        target.dataset.duration = newDuration;
                    })
                    .on('resizeend', function(event) {
                        // Re-enable dragging after resizing
                        isResizing = false;

                        // Get the updated duration
                        let newDuration = parseInt(event.target.dataset.duration);

                        // Get the job ID
                        let jobId = event.target.id; // Assuming the element's ID is the job ID

                        // AJAX request to update duration in database
                        updateDurationInDatabase(jobId, newDuration, event.target);
                    });
            }

            function updateDurationInDatabase(jobId, newDuration, target) {
                Swal.fire({
                    title: 'Do you want to change time?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX POST request to your Laravel endpoint
                        $.ajax({
                            url: "{{ route('schedule.update_job_duration') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if using Laravel CSRF protection
                            },
                            data: {
                                duration: newDuration,
                                jobId: jobId
                            },
                            success: function(response) {
                                // Handle success if needed
                                Swal.fire('Success', 'Duration updated successfully', 'success')
                                    .then(() => {});
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating duration:', error);
                                // Handle error if needed
                                Swal.fire('Error',
                                    'Failed to update duration. Please try again.', 'error');
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Reset the height to the original height
                        let originalHeight = parseFloat(target.dataset.originalHeight);
                        target.style.height = originalHeight + 'px';
                    }
                });
            }

            // Function to initialize all necessary components
            function initializeComponents() {
                initializeDraggable();
                initializeDroppable();
                initializeResizable();
            }

            // Call the initialization function once at the start
            initializeComponents();

            // Fetch new schedule data and reinitialize components

            $(document).on('click', '#preDate1, #tomDate1', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                var date = $(this).data('previous-date') || $(this).data('tomorrow-date');
                fetchSchedule(date);
            });

            // Event listener for showing the map
            $('#mapSection1').hide();

            $(document).on('click', 'a[href="#navMap1"]', function(e) {
                e.preventDefault();
                $('#scheduleSection1').hide();
                $('.cbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.mbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#mapSection1').show();
                initMap('mapScreen1', '#scheduleSection1');
            });

            // Event listener for hiding the map
            $(document).on('click', 'a[href="#navCalendar1"]', function(e) {
                e.preventDefault();
                $('#mapSection1').hide();
                $('.mbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.cbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#scheduleSection1').show();
            });

            function fetchSchedule(date) {
                $.ajax({
                    url: "{{ route('schedule.demoScheduleupdate') }}",
                    method: "GET",
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#newdemodata').empty().html(response.tbody);
                        initializeComponents();
                        initializeDatepicker('#selectDates1', fetchSchedule);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }


            var openInfoWindowpop = null;
            var maps = {};

            function initMap(mapElementId, scheduleSectionId) {

                if (maps[mapElementId]) {
                    destroyMap(mapElementId); // Destroy the existing map instance
                }

                maps[mapElementId] = new google.maps.Map(document.getElementById(mapElementId), {
                    zoom: 5,
                    center: {
                        lat: 39.8283,
                        lng: -98.5795
                    }
                });

                var selectedDate = $(scheduleSectionId).data('map-date');
                if (!selectedDate) {
                    selectedDate = new Date().toISOString().split('T')[0];
                    $(scheduleSectionId).data('map-date', selectedDate);
                    console.log("No date provided. Using current date:", selectedDate);
                }

                fetchJobData(mapElementId, selectedDate);
            }


            function fetchJobData(mapElementId, date) {
                $.ajax({
                    url: '{{ route('schedule.getJobsByDate') }}',
                    method: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        if (response.data) {
                            setMarkers(mapElementId, response.data);
                        } else {
                            console.error('Error: No job data returned.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:', error);
                    }
                });
            }

            function setMarkers(mapElementId, markersData) {
                clearMarkers(mapElementId);

                const markers = markersData.filter(marker => marker.latitude && marker.longitude);
                var bounds = new google.maps.LatLngBounds();

                markers.forEach(marker => {
                    var markerInstance = new google.maps.Marker({
                        position: {
                            lat: parseFloat(marker.latitude),
                            lng: parseFloat(marker.longitude)
                        },
                        map: maps[mapElementId],
                        title: marker.name
                    });

                    markerInstance.addListener('click', function() {
                        fetchMarkerDetails(markerInstance, marker.job_id);
                    });

                    bounds.extend(markerInstance.position);
                });

                if (markers.length > 0) {
                    maps[mapElementId].fitBounds(bounds);
                } else {
                    console.log("No markers to set on map.");
                }
            }

            function fetchMarkerDetails(markerInstance, jobId) {
                $.ajax({
                    url: '{{ route('schedule.getMarkerDetails') }}',
                    method: 'GET',
                    data: {
                        id: jobId
                    },
                    success: function(response) {
                        if (response.content) {
                            if (openInfoWindowpop) {
                                openInfoWindowpop.close();
                            }
                            openInfoWindowpop = openInfoWindow(markerInstance, response.content);
                        } else {
                            console.error('Error: No content returned.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:', error);
                    }
                });
            }

            function openInfoWindow(marker, content) {
                var infoWindow = new google.maps.InfoWindow({
                    content: content
                });
                infoWindow.open(maps[marker.map.getDiv().id], marker);
                return infoWindow;
            }

            function clearMarkers(mapElementId) {
                if (maps[mapElementId].markers) {
                    maps[mapElementId].markers.forEach(marker => marker.setMap(null));
                }
                maps[mapElementId].markers = [];
            }

            window.onload = function() {
                initMap('mapScreen1', '#scheduleSection1');
            };

            function destroyMap(mapElementId) {
                if (maps[mapElementId]) {
                    // Clear any existing markers or overlays if applicable
                    clearMarkers(mapElementId);

                    // Clear event listeners associated with the map
                    google.maps.event.clearInstanceListeners(maps[mapElementId]);

                    // Set the map instance to null, effectively "destroying" it
                    maps[mapElementId] = null;
                }
            }

            function initializeDatepicker(selector, fetchFunction) {
                $(selector).datepicker({
                    format: 'yyyy-mm-dd', // Specify the format
                    autoclose: true, // Close the datepicker when a date is selected
                    todayHighlight: true // Highlight today's date
                }).on('changeDate', function(selected) {
                    var selectedDate = new Date(selected.date);
                    var date = selectedDate.getFullYear() + '-' +
                        (selectedDate.getMonth() + 1).toString().padStart(2, '0') + '-' +
                        selectedDate.getDate().toString().padStart(2, '0');
                    fetchFunction(date);
                });
            }
            initializeDatepicker('#selectDates1', fetchSchedule);


        });
    </script>
@endsection
