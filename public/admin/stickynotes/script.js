document.addEventListener('DOMContentLoaded', function() {
    
    const allCheckbox = document.getElementById('allcheckbox');
    if (allCheckbox) {  // Check if the element exists
        allCheckbox.addEventListener('change', function() {
            
            let jobIds = document.querySelectorAll('.jobIds');
            jobIds.forEach(function(jobId) {
                jobId.checked = allCheckbox.checked;
            });
        });

        // This event listener checks if any checkbox with the class "jobIds" is clicked
        document.querySelectorAll('.jobIds').forEach(function(jobId) {
            jobId.addEventListener('change', function() {
                
                let allChecked = document.querySelectorAll('.jobIds:checked').length === document.querySelectorAll('.jobIds').length;
                allCheckbox.checked = allChecked;
            });
        });
    }
});
$(document).ready(function () {
   
    $(document).on("click", "#showStickyNote", function () {
        $(".stickyMainSection").show();
    });
    $(document).on("click", "#showJobList", function () {
        $(".jobMainSection").show();
    });

    $(document).on("click", "#close-job-detail", function () {
        $(".jobMainSection").hide();
    });

    $(document).on("click", "#close-task-detail", function () {
        $(".stickyMainSection").hide();
        $(".stickyNotesList").show();
        $(".addStickyNote").hide();
    });

    $(document).on("click", ".addStickyNoteBtn", function () {
        $(".stickyNotesList").hide();
        $(".addStickyNote").show();
    });

    $(document).on("click", ".closeStickyAdd", function () {
        $(".stickyNotesList").show();
        $(".addStickyNote").hide();
        $(".editStickyNote").hide();
    });

    $("#colorNoteForm").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: storeColorNoteUrl, // Use the URL passed from Blade
            headers: {
                "X-CSRF-TOKEN": csrfToken, // Include the CSRF token in the headers
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $(".addStickyNote").hide();
                $("#colorNoteForm")[0].reset(); // Reset the form
                $(".sticknoteslist").empty();

                // Append each sticky note to the list
                response.forEach(function (item) {
                    var formattedDate = moment(item.updated_at).format(
                        "YYYY-MM-DD hh:mm A"
                    );

                    var newNote = `
                        <div class="col-sm-4 col-md-4 my-3">
                            <div class="card border rounded p-3 h-100 justify-content-between">
                            <div class="row d-flex justify-content-between">
                            <div class="col-9">${item.note} </div>
                            <div class="col-2 btn-group ms-2">
                                <div class="text-primary fw-bold fs-7 actionBtnNote" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   ...
                                </div>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item editStckyNoteBtn" data-note-id="${item.note_id}"><i
                                        data-feather="edit" class="feather-sm me-2"></i> Edit</a>
                                        <input type="hidden" class="edit_note_id" value="${item.note_id}">
                                <a class="dropdown-item deleteStckyNoteBtn"
                                     data-note-id="${item.note_id}"><i
                                        data-feather="trash" class="feather-sm me-2"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div> ${formattedDate} </div>
                                    <div> <i class="fa fa-circle" style="color:${item.color_code} ;"></i> </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Append the new sticky note card to the list
                    $(".sticknoteslist").append(newNote);
                });
                $(".stickyNotesList").show();
            },
            error: function (response) {
                // Handle error - display error messages
                alert("An error occurred. Please try again.");
            },
        });
    });

    $(document).on("click", ".editStckyNoteBtn", function () {
        // Get the note_id from the data attribute
        var id = $(this).attr("data-note-id");

        // Use AJAX to fetch the note details from the server
        $.ajax({
            url: storeEditNoteUrl, // Endpoint to get the note details
            type: "get",
            data: {
                id: id,
            },
            success: function (response) {
              
                $("#edit_note_id").empty();
                $("#edit_note_id2").empty();
                $("#edit_color_code").empty();
                $("#edit_note").empty();

                // Populate the form fields with the note details
                $("#edit_note_id").val(response.note_id);
                $("#edit_note_id2").val(response.note_id);
                $("#edit_note").val(response.note);

                $.each(response.color, function (index, value) {
                    var selected =
                        value.color_code === response.color_code
                            ? "selected"
                            : "";
                    $("#edit_color_code").append(
                        `<option value="${value.color_code}" style="background: ${value.color_code};" ${selected}>${value.color_code}</option>`
                    );
                });

                $(".editStickyNote").show();
                $(".stickyNotesList").hide();
                $(".addStickyNote").hide();
            },
        });
    });

    $("#editNoteForm").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: updateColorNoteUrl, // Use the URL passed from Blade
            headers: {
                "X-CSRF-TOKEN": csrfToken, // Include the CSRF token in the headers
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $(".editStickyNote").hide();
                $("#editNoteForm")[0].reset(); // Reset the form
                $(".sticknoteslist").empty();

                var formattedDate = moment(response.updated_at).format(
                    "YYYY-MM-DD hh:mm A"
                );
                // Append each sticky note to the list
                response.forEach(function (item) {
                    var newNote = `
                        <div class="col-sm-4 col-md-4 my-3">
                            <div class="card border rounded p-3 h-100 justify-content-between">
                            <div class="row d-flex justify-content-between">
                            <div class="col-9">${item.note} </div>
                            <div class="col-2 btn-group ms-2">
                                <div class="text-primary fw-bold fs-7 actionBtnNote" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   ...
                                </div>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item editStckyNoteBtn" data-note-id="${item.note_id}"><i
                                        data-feather="edit" class="feather-sm me-2"></i> Edit</a>
                                        <input type="hidden" class="edit_note_id" value="${item.note_id}">
                                <a class="dropdown-item deleteStckyNoteBtn"
                                     data-note-id="${item.note_id}"><i
                                        data-feather="trash" class="feather-sm me-2"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div> ${formattedDate} </div>
                                    <div> <i class="fa fa-circle" style="color:${item.color_code} ;"></i> </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Append the new sticky note card to the list
                    $(".sticknoteslist").append(newNote);
                });
                $(".stickyNotesList").show();
            },
            error: function (response) {
                // Handle error - display error messages
                alert("An error occurred. Please try again.");
            },
        });
    });

    $(document).on("click", ".deleteStckyNoteBtn", function () {
        // Get the note_id from the data attribute
        var id = $(this).attr("data-note-id");

        // Use AJAX to fetch the note details from the server
        $.ajax({
            url: deleteNoteUrl, // Endpoint to get the note details
            type: "get",
            data: {
                id: id,
            },
            success: function (response) {
                $(".editStickyNote").hide();
                $(".stickyNotesList").show();
                $(".addStickyNote").hide();
                $(".sticknoteslist").empty();
                var formattedDate = moment(response.updated_at).format(
                    "YYYY-MM-DD hh:mm A"
                );
                // Append each sticky note to the list
                response.forEach(function (item) {
                    var newNote = `
                        <div class="col-sm-4 col-md-4 my-3">
                            <div class="card border rounded p-3 h-100 justify-content-between">
                            <div class="row d-flex justify-content-between">
                            <div class="col-9">${item.note} </div>
                            <div class="col-2 btn-group ms-2">
                                <div class="text-primary fw-bold fs-7 actionBtnNote" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   ...
                                </div>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item editStckyNoteBtn" data-note-id="${item.note_id}"><i
                                        data-feather="edit" class="feather-sm me-2"></i> Edit</a>
                                        <input type="hidden" class="edit_note_id" value="${item.note_id}">
                                <a class="dropdown-item deleteStckyNoteBtn"
                                     data-note-id="${item.note_id}"><i
                                        data-feather="trash" class="feather-sm me-2"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div> ${formattedDate} </div>
                                    <div> <i class="fa fa-circle" style="color:${item.color_code} ;"></i> </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Append the new sticky note card to the list
                    $(".sticknoteslist").append(newNote);
                });
                $(".stickyNotesList").show();
            },
        });
    });

    $("#sticky_job_list").DataTable({
        order: [[0, "desc"]],
        pageLength: 25,
        columnDefs: [
            {
                targets: 4, // Index of the priority column (zero-based)
                visible: false, // Hide the column from view
            },
            {
                targets: 5, // Index of the is confirmed column (zero-based)
                visible: false, // Hide the column from view
            },
        ],
    });

    $(
        ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
    ).addClass("btn btn-cyan text-white me-1");

    // Initialize DataTable
    var table = $("#sticky_job_list").DataTable();

    // Technician filter
    $("#technician-filter").on("change", function () {
        var technician = $(this).val();
        table
            .column(3)
            .search(technician || "", true, false)
            .draw();
    });

    // Customer filter
    $("#customer-filter").on("change", function () {
        var customer = $(this).val();
        table
            .column(2)
            .search(customer || "", true, false)
            .draw();
    });

    // Priority filter
    $("#priority-filter").on("change", function () {
        var priority = $(this).val();
        table
            .column(4)
            .search(priority || "", true, false)
            .draw();
    });

    // Title filtering
    $("#title-filter").on("change", function () {
        var title = $(this).val();
        table
            .column(1)
            .search(title || "", true, false)
            .draw();
    });

    // isConfirmed filtering
    $("#isConfirmed-filter").on("change", function () {
        var isConfirmed = $(this).val();
        table
            .column(5)
            .search(isConfirmed || "", true, false)
            .draw();
    });

       $('#showStickyRouting').hide();
       $('#colrouting').hide();
        $(document).on('click', '#stickyRouting', function(){
            $('#showStickyRouting').toggle();
            $('#colrouting').toggle();
        });
});
