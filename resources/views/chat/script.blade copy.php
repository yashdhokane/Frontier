<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('public/admin/dist/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved') }}.js"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min') }}.js"></script>



<script>
    // open model
    $(document).ready(function() {



        // Function to handle search input
        $('input[name="text-search"]').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();

            // Loop through each list item
            $('.chatlist').each(function() {
                var $listItem = $(this);
                var itemName = $listItem.find('.message-title').text()
                    .toLowerCase();

                // Check if item name contains the search term
                if (itemName.includes(searchTerm)) {
                    // Show the list item if it matches the search term
                    $listItem.show();
                } else {
                    // Hide the list item if it doesn't match the search term
                    $listItem.hide();
                }
            });
        });



        var uniqueFiles = {}; // Initialize the uniqueFiles object

        $(document).on('click', '.chatlist', function() {
            $('.chat-not-selected').hide();
            $('.chatting-box').show();
            $('.chat').show();
            var id = $(this).data('id');
            console.log(id);

            $("#name_support_message_id").val(id);
            $("#add_user_to_conversation_hidden").val(id);
            $("#add_user_to_conversation_hidden-new").val(id);
            // Make an AJAX request to fetch data
            $.ajax({
                url: 'get-chat-messages',
                method: 'GET',
                data: {
                    id: id,
                },
                dataType: 'json',
                success: function(response) {
                    var combineData = response.combineData;
                    var partician = response.partician;
                    var attach = response.attachmentfileChatFile;
                    console.log(combineData);
                    console.log(attach);
                    $('.chat-list').empty();
                    $('.chat-meta-user-top')
                        .empty(); // Clear previous user data

                    var customerFound = false;
                    var otherUserCount = 0;
                    var firstUserName = '';

                    $.each(partician, function(index, participant) {
                        if (!customerFound && participant.user.role ===
                            'customer') {
                            var c_Name = participant.user.name;
                            $('.chat-meta-user-top').append(
                                '<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong>' +
                                c_Name +
                                ' and</strong>  </div> <div id="partCount" class="fw-bold ps-2"> </div>'
                            );
                            customerFound = true;
                        } else if (index === 0) {
                            firstUserName = participant.user.name;
                        } else {

                        }
                        otherUserCount++;
                    });

                    // If no customer was found, show the name of the first user and count the others
                    if (!customerFound) {
                        $('.chat-meta-user-top').append(
                            '<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong>' +
                            firstUserName +
                            '</strong> and</div> <div id="partCount" class="fw-bold ps-2"> </div>'
                        );
                    }

                    // Update the partCount element with the count of other users
                    $('#partCount').text(otherUserCount - 1 + ' Others');

                    // Append message data and file data to the chat list
                    $.each(combineData, function(index, data) {
                        var createdAtDate = new Date(data.time);
                        var currentTime = new Date();
                        var timeDifference = currentTime -
                            createdAtDate;
                        var formattedTimeDifference;

                        if (timeDifference < 60000) {
                            formattedTimeDifference = 'just now';
                        } else if (timeDifference < 3600000) {
                            formattedTimeDifference = Math.floor(
                                    timeDifference / 60000) +
                                ' minutes ago';
                        } else if (timeDifference < 86400000) {
                            formattedTimeDifference = Math.floor(
                                    timeDifference / 3600000) +
                                ' hours ago';
                        } else {
                            formattedTimeDifference = Math.floor(
                                    timeDifference / 86400000) +
                                ' days ago';
                        }

                        var userImageSrc = data.user?.user_image ?
                            'public/images/Uploads/users/' + data.user
                            .id + '/' + data.user.user_image :
                            '{{ asset('public/images/login_img_bydefault.png') }}';
                        var imageSrc = userImageSrc;

                        var userName = (data.user && data.user.name) ?
                            data.user.name : 'Unknown User';

                        var chatItem = '<li class="chat-item">' +
                            '<div class="chat-img"><img src="' +
                            imageSrc + '" alt="user" /></div>' +
                            '<div class="chat-content">' +
                            '<h6 class="font-medium">' + userName +
                            '</h6>';

                        // Check if the data contains a file path in the message field
                        // Check if it's a file
                        if (data.message && isFilePath(data.message)) {
                            var fileExtension = data.message.split('.')
                                .pop().toLowerCase();
                            // Check if it's an image file
                            if (['jpg', 'jpeg', 'png', 'gif', 'bmp']
                                .includes(fileExtension)) {
                                // It's an image file
                                chatItem += '<div class="file">' +
                                    '<a href="public/images/Uploads/chat/' +
                                    data.conversation_id + '/' + data
                                    .message + '" target="_blank">' +
                                    '<img src="public/images/Uploads/chat/' +
                                    data.conversation_id + '/' + data
                                    .message +
                                    '" width="100"height="100" />' +
                                    '</a>' + '</div>';
                            } else {
                                // It's another type of file
                                chatItem += '<div class="file">' +
                                    '<a href="public/images/Uploads/chat/' +
                                    data.conversation_id + '/' + data
                                    .message + '" target="_blank">' +
                                    data.message +
                                    // Display the file name
                                    '</a>' +
                                    '</div>';
                            }
                        } else {
                            // It's a message
                            chatItem += '<div class="box bg-light">' +
                                data.message + '</div>';
                        }

                        // Add formatted date and time
                        chatItem += '<div class="chat-time">' +
                            formattedTimeDifference + '</div>' +
                            '</li>';

                        $('.chat-list').prepend(chatItem);
                    });
                    $('.user_attachments').empty();
                    var uniqueFiles = {};
                    $.each(attach, function(index, attachs) {
                        // Check if a file attachment exists and belongs to the current conversation_id
                        if (attachs.filename && attachs.sender) {
                            var fileSrc =
                                'public/images/Uploads/chat/' + attachs
                                .conversation_id + '/' + attachs
                                .filename;

                            // Append the file link to the user_attachments container
                            if (!uniqueFiles[fileSrc]) {
                                // Append the file link to the user_attachments container
                                $('.user_attachments').append(
                                    '<li><a href="' + fileSrc +
                                    '" target="_blank">' + attachs
                                    .filename + '</a></li>');

                                // Record that this file source has been appended for this conversation_id
                                uniqueFiles[fileSrc] = true;
                            }
                        }
                    });

                    // Scroll to the bottom of the chat list
                    var chatBox = $('.chat-box');
                    chatBox.scrollTop(chatBox.prop("scrollHeight"));
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }


            });


            // Function to check if a string is a file path (ends with a file extension)
            function isFilePath(message) {
                var fileExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jfif',

                    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'mp3', 'mp4',
                    'avi', 'mov'
                ]; // Add more file extensions if needed
                var extension = message.split('.').pop()
                    .toLowerCase(); // Get the file extension from the message
                return fileExtensions.includes(
                    extension); // Check if the extension is in the list of file extensions
            }
        });

        $('#addNewChat').click(function() {
            $('.chat-not-selected').show();
            $('.chatting-box').hide();
            $('.chat').hide();
        });

        $("#autosubmitwithoutmodel").click(function(event) {
            var conversationId = $("#add_user_to_conversation_hidden-new").val();
            if (!conversationId) {
                event.preventDefault(); // Prevent form submission
                alert("Please select a conversation to add users to!"); // Display message
            }
        });
        $('#add_user_to_conversation').click(function() {
            // Get the value from add_user_to_conversation_hidden
            var conversationId = $('#add_user_to_conversation_hidden').val();

            // Set the value to conversation_id input in the modal
            $('#conversation_id').val(conversationId);

            // Show the modal
            $('#addUserModal').modal('show');
        });

        //new without model autosrch on input type
        var appendedUsersnew = [];

        function initializeAutocompletenew() {
            $("#users").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete.user') }}",
                        data: {
                            term: request.term
                        },
                        dataType: "json",
                        type: "GET",
                        success: function(data) {
                            var uniqueUsers = [];
                            data.forEach(function(item) {
                                if (!appendedUsersnew.includes(item
                                        .value)) {
                                    uniqueUsers.push(item);
                                    appendedUsersnew.push(item
                                        .value);
                                }
                            });
                            response(uniqueUsers);

                            // Set user_id in the modal to the selected user's id
                            $("#user_id").val(ui.item.value);
                        },
                        error: function(xhr, status, error) {
                            console.log("Error fetching user data:", error);
                        }
                    });
                },
                minLength: 3,
                select: function(event, ui) {
                    console.log(ui.item.value); // Log the selected user's id
                    $("#users").val(ui.item.label);
                    $("#user_id").val(ui.item
                        .value); // Set user_id to the selected user's id
                    $("#autocomplete-results-users").empty();
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>").addClass("col-md-8").text(item.label).appendTo(
                    "#autocomplete-results-users");
            };

            $("#autocomplete-results-users").on("click", "li", function() {
                var userId = $(this).data("user_id");
                var userName = $(this).text();
                $("#user_id").val(userId);
                $("#users").val(userName);
                $("#autocomplete-results-users").hide();
            });

            $("#users").click(function() {
                $("#autocomplete-results-users").show();
            });

            $("#users").on("input", function() {
                var inputText = $(this).val().trim();
                if (inputText === "") {
                    $("#autocomplete-results-users").empty();
                    appendedUsersnew = []; // Clear appended users when input is cleared
                }
            });
        }

        // Call the function to initialize autocomplete
        initializeAutocompletenew();

        // Listen for input field clearing
        $("#users").on("input", function() {
            var inputText = $(this).val().trim();
            if (inputText === "") {
                // If input field is cleared, re-initialize autocomplete
                initializeAutocompletenew();
            }
        });


        var appendedUsersParticipant = [];

        function initializeAutocompleteParticipant() {
            $("#participants").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete.participants') }}",
                        data: {
                            term: request.term
                        },
                        dataType: "json",
                        type: "GET",
                        success: function(data) {
                            var uniqueUsers = [];
                            data.forEach(function(item) {
                                if (!appendedUsersParticipant
                                    .includes(item
                                        .value)) {
                                    uniqueUsers.push(item);
                                    appendedUsersParticipant.push(
                                        item
                                        .value);
                                }
                            });
                            response(uniqueUsers);

                            // Set user_id in the modal to the selected user's id
                            // $("#user_id").val(ui.item.value);
                        },
                        error: function(xhr, status, error) {
                            console.log("Error fetching user data:", error);
                        }
                    });
                },
                minLength: 3,
                select: function(event, ui) {
                    console.log(ui.item.value); // Log the selected user's id
                    $("#participants").val(ui.item.label);
                    $("#user_id").val(ui.item
                        .value); // Set user_id to the selected user's id
                    $("#autocomplete-results-users-part").empty();
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>").addClass("col-md-8").text(item.label).attr("data-user-id", item
                    .value).appendTo(
                    "#autocomplete-results-users-part");
            };

            $("#autocomplete-results-users-part").on("click", "li", function() {
                var userId = $(this).data("user-id");
                var userName = $(this).text();
                $("#selected_user_id").val(userId);
                $("#participants").val(userName);
                $("#autocomplete-results-users-part").hide();
            });

            $("#participants").click(function() {
                $("#autocomplete-results-users-part").show();
            });

            $("#participants").on("input", function() {
                var inputText = $(this).val().trim();
                if (inputText === "") {
                    $("#autocomplete-results-users-part").empty();
                    appendedUsersParticipant
                        = []; // Clear appended users when input is cleared
                }
            });
        }


        // Call the function to initialize autocomplete
        initializeAutocompleteParticipant();

        // Listen for input field clearing
        $("#participants").on("input", function() {
            var inputText = $(this).val().trim();
            if (inputText === "") {
                // If input field is cleared, re-initialize autocomplete
                initializeAutocompleteParticipant();
            }
        });

        //old Retrieve conversation_id when opening the modal
        var appendedUsers = [];

        function initializeAutocomplete() {
            $("#user").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete.user') }}",
                        data: {
                            term: request.term
                        },
                        dataType: "json",
                        type: "GET",
                        success: function(data) {
                            var uniqueUsers = [];
                            data.forEach(function(item) {
                                if (!appendedUsers.includes(item.value)) {
                                    uniqueUsers.push(item);
                                    appendedUsers.push(item.value);
                                }
                            });
                            response(uniqueUsers);

                            // Set user_id in the modal to the selected user's id
                            $("#user_id").val(ui.item.value);
                        },
                        error: function(xhr, status, error) {
                            console.log("Error fetching user data:", error);
                        }
                    });
                },
                minLength: 3,
                select: function(event, ui) {
                    console.log(ui.item.value); // Log the selected user's id
                    $("#user").val(ui.item.label);
                    $("#user_id").val(ui.item.value); // Set user_id to the selected user's id
                    $("#autocomplete-results").empty();
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>").addClass("col-md-12").text(item.label).appendTo("#autocomplete-results");
            };

            $("#autocomplete-results").on("click", "li", function() {
                var userId = $(this).data("user_id");
                var userName = $(this).text();
                $("#user_id").val(userId);
                $("#user").val(userName);
                $("#autocomplete-results").hide();
            });

            $("#user").click(function() {
                $("#autocomplete-results").show();
            });

            $("#user").on("input", function() {
                var inputText = $(this).val().trim();
                if (inputText === "") {
                    $("#autocomplete-results").empty();
                    appendedUsers = []; // Clear appended users when input is cleared
                }
            });
        }

        // Call the function to initialize autocomplete
        initializeAutocomplete();

        // Listen for input field clearing
        $("#user").on("input", function() {
            var inputText = $(this).val().trim();
            if (inputText === "") {
                // If input field is cleared, re-initialize autocomplete
                initializeAutocomplete();
            }
        });

        //open model

        // Send AJAX request to refresh the chat list
        function refreshChatList(id) {
            // Make an AJAX request to fetch data

            $.ajax({
                url: 'get-chat-messages', // Replace 'your-api-endpoint' with your actual API endpoint
                method: 'GET',
                data: {
                    id: id,
                },
                dataType: 'json', // Specify the expected data type
                success: function(response) {
                    var chat = response.chat;
                    var combineData = response.combineData;
                    var uniqueFiles = {};


                    var attach = response.attachmentfileChatFile;

                    $('.chat-list').empty();
                    $('.user_attachments').empty();
                    var uniqueFiles = {};
                    $.each(attach, function(index, attachs) {
                        // Check if a file attachment exists and belongs to the current conversation_id
                        if (attachs.filename && attachs.sender) {
                            var fileSrc = 'public/images/Uploads/chat/' + attachs
                                .conversation_id +
                                '/' + attachs.filename;

                            // Append the file link to the user_attachments container
                            if (!uniqueFiles[fileSrc]) {
                                // Append the file link to the user_attachments container
                                $('.user_attachments').append('<li><a href="' + fileSrc +
                                    '" target="_blank">' + attachs.filename +
                                    '</a></li>');

                                // Record that this file source has been appended for this conversation_id
                                uniqueFiles[fileSrc] = true;
                            }
                        }
                    });



                    // Example: Append user data to a container
                    $.each(chat, function(index, chats) {
                        $('.user-container').empty();
                        $('.user-container').append(
                            '<img src="public/images/Uploads/users/' + chats.user
                            .user_image +
                            '" alt="dynamic-image" class="rounded-circle" width="45" /><span class="name fw-bold ms-2">' +
                            chats.user.name + '</span>'
                        );
                    });

                    // Example: Append message data to a container
                    $.each(combineData, function(index, data) {
                        // Parse the string representation of the date into a Date object
                        var createdAtDate = new Date(data.time);
                        var currentTime = new Date();
                        var timeDifference = currentTime - createdAtDate;
                        var formattedTimeDifference;

                        if (timeDifference < 60000) {
                            formattedTimeDifference = 'just now';
                        } else if (timeDifference < 3600000) {
                            formattedTimeDifference = Math.floor(timeDifference / 60000) +
                                ' minutes ago';
                        } else if (timeDifference < 86400000) {
                            formattedTimeDifference = Math.floor(timeDifference / 3600000) +
                                ' hours ago';
                        } else {
                            formattedTimeDifference = Math.floor(timeDifference /
                                86400000) +
                                ' days ago';
                        }
                        var userImageSrc = data.user?.user_image ?
                            'public/images/Uploads/users/' + data
                            .user.id + '/' + data.user.user_image :
                            '{{ asset('public/images/login_img_bydefault.png') }}';
                        var imageSrc = userImageSrc;

                        var userName = (data.user && data.user.name) ? data.user.name :
                            'Unknown User';

                        var chatItem = '<li class="chat-item">' +
                            '<div class="chat-img"><img src="' + imageSrc +
                            '" alt="user" /></div>' +
                            '<div class="chat-content">' +
                            '<h6 class="font-medium">' + userName + '</h6>';

                        // Check if the data contains a file path in the message field
                        if (data.message && isFilePath(data.message)) {
                            var fileExtension = data.message.split('.').pop().toLowerCase();
                            // Check if it's an image file
                            if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(
                                fileExtension)) {
                                // It's an image file
                                chatItem += '<div class="file">' +
                                    '<a href="public/images/Uploads/chat/' + data
                                    .conversation_id +
                                    '/' + data.message + '" target="_blank">' +
                                    '<img src="public/images/Uploads/chat/' + data
                                    .conversation_id +
                                    '/' + data.message + '" width="100"height="100" />' +
                                    '</a>' +
                                    '</div>';
                            } else {
                                // It's another type of file
                                chatItem += '<div class="file">' +
                                    '<a href="public/images/Uploads/chat/' + data
                                    .conversation_id +
                                    '/' + data.message + '" target="_blank">' +
                                    data.message + // Display the file name
                                    '</a>' +
                                    '</div>';
                            }
                        } else {
                            // It's a message
                            chatItem += '<div class="box bg-light">' + data.message +
                                '</div>';
                        }

                        // Add formatted date and time
                        chatItem += '<div class="chat-time">' + formattedTimeDifference +
                            '</div>' +
                            '</li>';

                        // Prepend the chat item to the chat list
                        $('.chat-list').prepend(chatItem);
                    });

                    // Scroll to the bottom of the chat list
                    var chatBox = $('.chat-box');
                    chatBox.scrollTop(chatBox.prop("scrollHeight"));

                    // Focus on the newly appended content
                    var chatItems = $('.chat-list .chat-item');
                    var lastChatItem = chatItems
                .first(); // Changed to first() to focus on the newly added item
                    lastChatItem.focus();
                    // Scroll to the bottom of the chat list
                    // $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
        // Function to check if a string is a file path (ends with a file extension)
        function isFilePath(message) {
            var fileExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif',
                'bmp', 'mp3',
                'mp4',
                'avi', 'mov'
            ]; // Add more file extensions if needed
            var extension = message.split('.').pop().toLowerCase(); // Get the file extension from the message
            return fileExtensions.includes(
            extension); // Check if the extension is in the list of file extensions
        }
        // Function to handle sending the message with or without file attachment
        function sendMessage() {
            var formData = new FormData();
            formData.append('reply', $('#textarea1').val());
            formData.append('_token', '{{ csrf_token() }}'); // CSRF token
            formData.append('auth_id', $('input[name="auth_id"]').val());
            formData.append('support_message_id', $('input[name="conversation_id"]').val());

            // Check if a file is selected
            // var fileInput = $('#fileInput')[0];
            // if (fileInput.files.length > 0) {
            //     formData.append('file', fileInput.files[0]);
            // }

            // Send AJAX request to upload file and send message
            $.ajax({
                url: '{{ route('store_reply') }}', // Use the named route
                method: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    $('#textarea1').val('');
                    $('#fileInput').val(''); // Clear file input field
                    refreshChatList(formData.get('support_message_id'));
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Bind the AJAX request to both textarea keypress and button click events
        $('#textarea1').on('keypress', function(event) {
            // Check if Enter key is pressed (key code 13)
            if (event.which === 13) {
                // Prevent the default form submission behavior
                event.preventDefault();
                sendMessage(); // Call the sendMessage function
            }
        });

        $('#sendButton').on('click', function() {
            sendMessage(); // Call the sendMessage function
        });
        // Extract query parameters from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('message_id');
        const user_one = urlParams.get('user_one');
        const user_two = urlParams.get('user_two');

        // Check if all parameters are set
        if (id && user_one && user_two) {
            // Hide/show elements and set value of #name_support_message_id
            $('.chat-not-selected').hide();
            $('.chatting-box').show();
            $('.chat').show();
            $('#name_support_message_id').val(id);
            history.replaceState({}, document.title, window.location.pathname);
        }


        // done part start

        // Trigger AJAX function to load chat messages based on the extracted parameters

        $.ajax({
            url: 'get-chat-messages', // Replace 'your-api-endpoint' with your actual API endpoint
            method: 'GET',
            data: {
                id: id,
            },
            dataType: 'json', // Specify the expected data type
            success: function(response) {
                // Access user data and messages data from the response
                var chat = response.chat;

                $('.chat-list').empty();

                // Example: Append user data to a container
                $.each(chat, function(index, chats) {
                    $('.user-container').empty();
                    $('.user-container').append(
                        '<img src="public/images/Uploads/users/' + chats.user
                        .user_image +
                        '" alt="dynamic-image"class="rounded-circle" width="45" /><span class="name fw-bold ms-2">' +
                        chats.user.name + '</span>'
                    );
                });

                // Example: Append message data to a container
                $.each(chat, function(index, chats) {
                    var createdAtDate = new Date(chats.time);
                    var formattedDate = createdAtDate.toLocaleDateString();
                    var formattedTime = createdAtDate.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    // Check if the user is a technician
                    var userImageSrc = chats.user.role === 'technician' ?
                        'public/images/Uploads/users/' : 'public/images/users/';

                    // Check if the user image is available
                    var userImage = chats.user.user_image ? chats.user
                        .user_image : '1707736455_avatar-8.png';

                    // Construct the image source
                    var imageSrc = userImageSrc + userImage;

                    $('.chat-list').append(
                        '<li class="chat-item"><div class="chat-img"><img src="' +
                        imageSrc + '" alt="user" /></div>' +
                        '<div class="chat-content"><h6 class="font-medium">' +
                        chats.user.name + '</h6>' +
                        '<div class="box bg-light">' + chats.message +
                        '</div></div>' +
                        '<div class="chat-time">' + formattedDate + ' ' +
                        formattedTime + '</div></li>'
                    );
                });


                // Scroll to the bottom of the chat list
                $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);

            },

            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });


        $('.chatlist:first').trigger('click');

        if ($('.chatlist').length > 0) {
            $('.chatlist:first').trigger('click');
        }

    });


    // done part start
</script>

<script>
    // open model
    // $(document).ready(function() {


    //     $(document).on('click', '.chatlist', function() {
    //         var id = $(this).data('id');
    //         $('.chat').show();
    //         console.log(id);
    //         $.ajax({
    //             url: '{{ route('add_employee_cnvrsn') }}',
    //             method: 'GET',
    //             data: {
    //                 id: id,
    //             },
    //             dataType: 'json',
    //             success: function(response) {
    //                 var combineData = response.combineData;
    //                 var partician = response.partician;
    //                 var attach = response.attachmentfileChatFile;
    //                 var id = response.conversation_id;
    //                 console.log(combineData);

    //                 $("#name_support_message_id").val(id);
    //                 $("#add_user_to_conversation_hidden").val(id);
    //                 $("#add_user_to_conversation_hidden-new").val(id);

    //                 $('.chat-list').empty();
    //                 $('.chat-meta-user-top').empty(); // Clear previous user data

    //                 var customerFound = false;
    //                 var otherUserCount = 0;
    //                 var firstUserName = '';

    //                 $.each(partician, function(index, participant) {
    //                     if (!customerFound && participant.user.role !=
    //                         'superadmin') {
    //                         var c_Name = participant.user.name;
    //                         $('.chat-meta-user-top').append(
    //                             '<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong>' +
    //                             c_Name +
    //                             ' and</strong>  </div> <div id="partCount" class="fw-bold ps-2"> </div>'
    //                         );
    //                         customerFound = true;
    //                     } else if (index === 0) {
    //                         firstUserName = participant.user.name;
    //                     } else {

    //                     }
    //                     otherUserCount++;
    //                 });

    //                 // If no customer was found, show the name of the first user and count the others
    //                 if (!customerFound) {
    //                     $('.chat-meta-user-top').append(
    //                         '<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong>' +
    //                         firstUserName +
    //                         '</strong> and</div> <div id="partCount" class="fw-bold ps-2"> </div>'
    //                     );
    //                 }

    //                 // Update the partCount element with the count of other users
    //                 $('#partCount').text(otherUserCount - 1 + ' Others');

    //                 // Append message data and file data to the chat list
    //                 $.each(combineData, function(index, data) {
    //                     var createdAtDate = new Date(data.time);
    //                     var currentTime = new Date();
    //                     var timeDifference = currentTime - createdAtDate;
    //                     var formattedTimeDifference;

    //                     if (timeDifference < 60000) {
    //                         formattedTimeDifference = 'just now';
    //                     } else if (timeDifference < 3600000) {
    //                         formattedTimeDifference = Math.floor(
    //                                 timeDifference / 60000) +
    //                             ' minutes ago';
    //                     } else if (timeDifference < 86400000) {
    //                         formattedTimeDifference = Math.floor(
    //                                 timeDifference / 3600000) +
    //                             ' hours ago';
    //                     } else {
    //                         formattedTimeDifference = Math.floor(
    //                                 timeDifference / 86400000) +
    //                             ' days ago';
    //                     }

    //                     var userImageSrc = data.user?.user_image ?
    //                         'public/images/Uploads/users/' + data.user.id + '/' +
    //                         data.user.user_image :
    //                         '{{ asset('public/images/login_img_bydefault.png') }}';
    //                     var imageSrc = userImageSrc;

    //                     var userName = (data.user && data.user.name) ? data.user
    //                         .name : 'Unknown User';

    //                     var chatItem = '<li class="chat-item ps-2">' +
    //                         '<div class="chat-img"><img src="' +
    //                         imageSrc + '" alt="user" /></div>' +
    //                         '<div class="chat-content">' +
    //                         '<div class="d-flex"><span class="font-medium">' +
    //                         userName +
    //                         ', </span><span class="chat-time m-1">' +
    //                         formattedTimeDifference + '</span></div>';

    //                     // Check if the data contains a file path in the message field
    //                     // Check if it's a file
    //                     if (data.message && isFilePath(data.message)) {
    //                         var fileExtension = data.message.split('.').pop()
    //                             .toLowerCase();
    //                         // Check if it's an image file
    //                         if (['jpg', 'jpeg', 'png', 'gif', 'bmp']
    //                             .includes(fileExtension)) {
    //                             // It's an image file
    //                             chatItem += '<div class="file">' +
    //                                 '<a href="public/images/Uploads/chat/' +
    //                                 data.conversation_id + '/' + data
    //                                 .message + '" target="_blank">' +
    //                                 '<img src="public/images/Uploads/chat/' +
    //                                 data.conversation_id + '/' + data
    //                                 .message +
    //                                 '" width="100"height="100" />' +
    //                                 '</a>' + '</div>';
    //                         } else {
    //                             // It's another type of file
    //                             chatItem += '<div class="file">' +
    //                                 '<a href="public/images/Uploads/chat/' +
    //                                 data.conversation_id + '/' + data
    //                                 .message + '" target="_blank">' +
    //                                 data.message +
    //                                 // Display the file name
    //                                 '</a>' +
    //                                 '</div>';
    //                         }
    //                     } else {
    //                         // It's a message
    //                         chatItem += '<div class="box bg-light">' +
    //                             data.message + '</div>';
    //                     }

    //                     // Add formatted date and time
    //                     chatItem += '</li>';

    //                     $('.chat-list').prepend(chatItem);
    //                 });
    //                 $('.user_attachments').empty();
    //                 var uniqueFiles = {};
    //                 $.each(attach, function(index, attachs) {
    //                     // Check if a file attachment exists and belongs to the current conversation_id
    //                     if (attachs.filename && attachs.sender) {
    //                         var fileSrc =
    //                             'public/images/Uploads/chat/' + attachs
    //                             .conversation_id + '/' + attachs
    //                             .filename;

    //                         // Append the file link to the user_attachments container
    //                         if (!uniqueFiles[fileSrc]) {
    //                             // Append the file link to the user_attachments container
    //                             $('.user_attachments').append(
    //                                 '<li><a href="' + fileSrc +
    //                                 '" target="_blank">' + attachs
    //                                 .filename + '</a></li>');

    //                             // Record that this file source has been appended for this conversation_id
    //                             uniqueFiles[fileSrc] = true;
    //                         }
    //                     }
    //                 });

    //                 // Scroll to the bottom of the chat list
    //                 var chatBox = $('.chat-box');
    //                 chatBox.scrollTop(chatBox.prop("scrollHeight"));
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Error:', error);
    //             }
    //         });

    //         // Function to check if a string is a file path (ends with a file extension)
    //         function isFilePath(message) {
    //             var fileExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jfif',
    //                 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'mp3', 'mp4',
    //                 'avi', 'mov'
    //             ]; // Add more file extensions if needed
    //             var extension = message.split('.').pop()
    //                 .toLowerCase(); // Get the file extension from the message
    //             return fileExtensions.includes(
    //                 extension); // Check if the extension is in the list of file extensions
    //         }
    //     });


    //     function sendMessage() {
    //         var formData = new FormData();
    //         formData.append('reply', $('#textarea1').val());
    //         formData.append('_token', '{{ csrf_token() }}'); // CSRF token
    //         formData.append('auth_id', $('input[name="auth_id"]').val());
    //         formData.append('support_message_id', $('input[name="conversation_id"]').val());



    //         // Send AJAX request to upload file and send message
    //         $.ajax({
    //             url: '{{ route('store_reply') }}', // Use the named route
    //             method: 'POST',
    //             data: formData,
    //             dataType: 'json',
    //             contentType: false,
    //             processData: false,
    //             success: function(response) {
    //                 var combineData = response.combineData;
    //                 var partician = response.partician;
    //                 var attach = response.attachmentfileChatFile;
    //                 var id = response.conversation_id;
    //                 console.log(response);
    //                 $('#textarea1').val('');
    //                 $('#fileInput').val('');
    //                 $('.chat-list').empty();
    //                 // Append message data and file data to the chat list
    //                 $.each(combineData, function(index, data) {
    //                     var createdAtDate = new Date(data.time);
    //                     var currentTime = new Date();
    //                     var timeDifference = currentTime - createdAtDate;
    //                     var formattedTimeDifference;

    //                     if (timeDifference < 60000) {
    //                         formattedTimeDifference = 'just now';
    //                     } else if (timeDifference < 3600000) {
    //                         formattedTimeDifference = Math.floor(
    //                                 timeDifference / 60000) +
    //                             ' minutes ago';
    //                     } else if (timeDifference < 86400000) {
    //                         formattedTimeDifference = Math.floor(
    //                                 timeDifference / 3600000) +
    //                             ' hours ago';
    //                     } else {
    //                         formattedTimeDifference = Math.floor(
    //                                 timeDifference / 86400000) +
    //                             ' days ago';
    //                     }

    //                     var userImageSrc = data.user?.user_image ?
    //                         'public/images/Uploads/users/' + data.user.id + '/' +
    //                         data.user.user_image :
    //                         '{{ asset('public/images/login_img_bydefault.png') }}';
    //                     var imageSrc = userImageSrc;

    //                     var userName = (data.user && data.user.name) ? data.user
    //                         .name : 'Unknown User';

    //                     var chatItem = '<li class="chat-item ps-2">' +
    //                         '<div class="chat-img"><img src="' +
    //                         imageSrc + '" alt="user" /></div>' +
    //                         '<div class="chat-content">' +
    //                         '<div class="d-flex"><span class="font-medium">' +
    //                         userName +
    //                         ', </span><span class="chat-time m-1">' +
    //                         formattedTimeDifference + '</span></div>';

    //                     // Check if the data contains a file path in the message field
    //                     // Check if it's a file
    //                     if (data.message && isFilePath(data.message)) {
    //                         var fileExtension = data.message.split('.').pop()
    //                             .toLowerCase();
    //                         // Check if it's an image file
    //                         if (['jpg', 'jpeg', 'png', 'gif', 'bmp']
    //                             .includes(fileExtension)) {
    //                             // It's an image file
    //                             chatItem += '<div class="file">' +
    //                                 '<a href="public/images/Uploads/chat/' +
    //                                 data.conversation_id + '/' + data
    //                                 .message + '" target="_blank">' +
    //                                 '<img src="public/images/Uploads/chat/' +
    //                                 data.conversation_id + '/' + data
    //                                 .message +
    //                                 '" width="100"height="100" />' +
    //                                 '</a>' + '</div>';
    //                         } else {
    //                             // It's another type of file
    //                             chatItem += '<div class="file">' +
    //                                 '<a href="public/images/Uploads/chat/' +
    //                                 data.conversation_id + '/' + data
    //                                 .message + '" target="_blank">' +
    //                                 data.message +
    //                                 // Display the file name
    //                                 '</a>' +
    //                                 '</div>';
    //                         }
    //                     } else {
    //                         // It's a message
    //                         chatItem += '<div class="box bg-light">' +
    //                             data.message + '</div>';
    //                     }

    //                     // Add formatted date and time
    //                     chatItem += '</li>';

    //                     $('.chat-list').prepend(chatItem);
    //                 });
    //                 $('.user_attachments').empty();
    //                 var uniqueFiles = {};
    //                 $.each(attach, function(index, attachs) {
    //                     // Check if a file attachment exists and belongs to the current conversation_id
    //                     if (attachs.filename && attachs.sender) {
    //                         var fileSrc =
    //                             'public/images/Uploads/chat/' + attachs
    //                             .conversation_id + '/' + attachs
    //                             .filename;

    //                         // Append the file link to the user_attachments container
    //                         if (!uniqueFiles[fileSrc]) {
    //                             // Append the file link to the user_attachments container
    //                             $('.user_attachments').append(
    //                                 '<li><a href="' + fileSrc +
    //                                 '" target="_blank">' + attachs
    //                                 .filename + '</a></li>');

    //                             // Record that this file source has been appended for this conversation_id
    //                             uniqueFiles[fileSrc] = true;
    //                         }
    //                     }
    //                 });

    //                 // Scroll to the bottom of the chat list
    //                 var chatBox = $('.chat-box');
    //                 chatBox.scrollTop(chatBox.prop("scrollHeight"));

    //             },
    //             error: function(xhr, status, error) {
    //                 console.error(xhr.responseText);
    //             }
    //         });
    //         // Function to check if a string is a file path (ends with a file extension)
    //         function isFilePath(message) {
    //             var fileExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jfif',
    //                 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'mp3', 'mp4',
    //                 'avi', 'mov'
    //             ]; // Add more file extensions if needed
    //             var extension = message.split('.').pop()
    //                 .toLowerCase(); // Get the file extension from the message
    //             return fileExtensions.includes(
    //                 extension); // Check if the extension is in the list of file extensions
    //         }
    //     }
    //     // Bind the AJAX request to both textarea keypress and button click events
    //     $('#textarea1').on('keypress', function(event) {
    //         // Check if Enter key is pressed (key code 13)
    //         if (event.which === 13) {
    //             // Prevent the default form submission behavior
    //             event.preventDefault();
    //             sendMessage(); // Call the sendMessage function
    //         }
    //     });
    //     $('#sendButton').on('click', function() {
    //         sendMessage(); // Call the sendMessage function
    //     });


    //     // Function to handle search input
    //     $('input[name="text-search"]').on('input', function() {
    //         var searchTerm = $(this).val().toLowerCase();

    //         // Loop through each list item
    //         $('.chatlist').each(function() {
    //             var $listItem = $(this);
    //             var itemName = $listItem.find('.message-title').text()
    //                 .toLowerCase();

    //             // Check if item name contains the search term
    //             if (itemName.includes(searchTerm)) {
    //                 // Show the list item if it matches the search term
    //                 $listItem.show();
    //             } else {
    //                 // Hide the list item if it doesn't match the search term
    //                 $listItem.hide();
    //             }
    //         });
    //     });

    //     $('.chatlist:first').trigger('click');

    // });
</script>