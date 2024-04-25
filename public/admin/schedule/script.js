$(document).ready(function () {
    // Click event handler for tech_profile links
    $(".tech_profile").click(function (event) {
        event.preventDefault(); // Prevent default link behavior

        var profileLink = $(this);
        var popupContainer = profileLink.next(".popupContainer");

        // Hide all other open popups
        $(".popupContainer").not(popupContainer).fadeOut();

        // Calculate position of the popup relative to the clicked element
        var position = profileLink.offset();
        var topPosition = position.top + profileLink.outerHeight() + 10; // Adjust 10 pixels for spacing
        var leftPosition = position.left;

        // Set position of the popup
        popupContainer.css({
            top: 56 + "px",
            left: 16 + "px",
        });

        // Show the popup
        popupContainer.fadeIn();
    });

    // Click event handler for profile link images
    $(".tech_profile img").click(function (event) {
        event.preventDefault(); 
        
    });

    // Click event listener for the document
    $(document).click(function (event) {
        var target = $(event.target);

        // Check if the clicked element is not within any popup container or profile link
        if (
            !target.closest(".popupContainer").length &&
            !target.is(".tech_profile")
        ) {
            // Hide all popup containers if any are currently visible
            $(".popupContainer").fadeOut();
        }
    });

  

    // Show job details on hover
    $(".show_job_details").hover(
        function () {
            // Show the corresponding popup
            $(this).next(".open_job_details").fadeIn();
        },
        function () {
            // Hide the popup when mouse leaves
            $(this).next(".open_job_details").fadeOut();
        }
    );

    // Close the popup when mouse leaves
    $(".open_job_details").mouseleave(function () {
        $(this).fadeOut();
    });

    function openPopup(popup) {
        if (popup) {
            popup.style.display = "block";
        } else {
            console.error("Popup element is null");
        }
    }

    function closePopup(popup) {
        if (popup) {
            popup.style.display = "none";
        } else {
            console.error("Popup element is null");
        }
    }
});
$(document).ready(function() {
    // Click event handler for both message option in popup and document
    $(document).on('click', '.message-popup', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Toggle visibility of the smscontainer associated with the clicked "Message" option
        var smscontainer = $(this).closest('th').find('.smscontainer');
        var messagePopup = $(this);
        
        // Calculate the position of the smscontainer relative to the message popup option
        var position = messagePopup.offset();
        var topPosition = position.top;
        var leftPosition = position.left + messagePopup.outerWidth(); // Position to the right of the message popup

        smscontainer.css({
            'top': 63 + 'px',
            'left': 127 + 'px'
        }).fadeToggle();

        // Hide all other open smscontainers
        $('.settingcontainer').fadeOut();
        $('.smscontainer').not(smscontainer).fadeOut();
    });

    $(document).on('click', function(event) {
        var target = $(event.target);

        // Check if the clicked element is not within any popup container, message popup option, or smscontainer
        if (!target.closest('.popupContainer').length && !target.is('.message-popup') && !target.closest('.smscontainer').length) {
            // Hide all smscontainers if clicked outside of them, the message popup option, or smscontainer
            $('.smscontainer').fadeOut();
        }
    });
});
$(document).ready(function() {
    // Click event handler for both message option in popup and document
    $(document).on('click', '.setting-popup', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Toggle visibility of the smscontainer associated with the clicked "Message" option
        var smscontainer = $(this).closest('th').find('.settingcontainer');
        var messagePopup = $(this);
        
        // Calculate the position of the smscontainer relative to the message popup option
        var position = messagePopup.offset();
        var topPosition = position.top;
        var leftPosition = position.left + messagePopup.outerWidth(); // Position to the right of the message popup

        smscontainer.css({
            'top': 125 + 'px',
            'left': 127 + 'px'
        }).fadeToggle();

        // Hide all other open smscontainers
        $('.smscontainer').fadeOut();
        $('.settingcontainer').not(smscontainer).fadeOut();
    });

    $(document).on('click', function(event) {
        var target = $(event.target);

        // Check if the clicked element is not within any popup container, message popup option, or smscontainer
        if (!target.closest('.popupContainer').length && !target.is('.setting-popup') && !target.closest('.settingcontainer').length) {
            // Hide all smscontainers if clicked outside of them, the message popup option, or smscontainer
            $('.settingcontainer').fadeOut();
        }
    });
});

