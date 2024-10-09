<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('public/admin/dist/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved') }}.js"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min') }}.js"></script>

<script>
    $(document).ready(function() {

        const isFilePath = (message) => {
            const fileExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jfif', 'jpg', 'jpeg',
                'png', 'gif', 'bmp', 'mp3', 'mp4', 'avi', 'mov'
            ];
            return fileExtensions.includes(message.split('.').pop().toLowerCase());
        };

        const formatTime = (time) => {
            const diff = new Date() - new Date(time);
            return diff < 60000 ? 'just now' :
                diff < 3600000 ? `${Math.floor(diff / 60000)} minutes ago` :
                diff < 86400000 ? `${Math.floor(diff / 3600000)} hours ago` :
                `${Math.floor(diff / 86400000)} days ago`;
        };

        const appendChatItem = (data) => {
            const imgSrc = data.user?.user_image ?
                `public/images/Uploads/users/${data.user.id}/${data.user.user_image}` :
                '{{ asset('public/images/login_img_bydefault.png') }}';
            const chatItem = `<li class="chat-item ps-2">
            <div class="chat-img"><img src="${imgSrc}" alt="user"></div>
            <div class="chat-content">
                <div class="d-flex"><span class="font-medium">${data.user?.name || 'Unknown User'}, </span>
                <span class="chat-time m-1">${formatTime(data.time)}</span></div>
                ${data.message && isFilePath(data.message) ? `
                    ${['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(data.message.split('.').pop().toLowerCase()) ?
                    `<div class="file"><a href="public/images/Uploads/chat/${data.conversation_id}/${data.message}" target="_blank">
                    <img src="public/images/Uploads/chat/${data.conversation_id}/${data.message}" width="100" height="100" /></a></div>` :
                    `<div class="file"><a href="public/images/Uploads/chat/${data.conversation_id}/${data.message}" target="_blank">${data.message}</a></div>`
                }` : `<div class="box bg-light">${data.message}</div>`}
            </div></li>`;
            $('.chat-list').prepend(chatItem);
        };

        const appendAttachment = (attachs, uniqueFiles) => {
            const fileSrc = `public/images/Uploads/chat/${attachs.conversation_id}/${attachs.filename}`;
            if (!uniqueFiles[fileSrc]) {
                $('.user_attachments').append(
                    `<li><a href="${fileSrc}" target="_blank">${attachs.filename}</a></li>`);
                uniqueFiles[fileSrc] = true;
            }
        };

        const updateChatUI = (response) => {
            const {
                combineData,
                partician,
                attachmentfileChatFile: attach,
                conversation_id: id
            } = response;
            $("#name_support_message_id, #add_user_to_conversation_hidden, #add_user_to_conversation_hidden-new")
                .val(id);
            $('.chat-list, .chat-meta-user-top').empty();
            const firstUserName = partician[1]?.user.name || '';
            const otherUsers = partician.filter((p, i) => i > 0).length;
            $('.chat-meta-user-top').html(
                `<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong>${firstUserName || 'Unknown'}</strong> and</div><div id="partCount" class="fw-bold ps-2">${otherUsers} Others</div>`
            );

            combineData.forEach(appendChatItem);
            const uniqueFiles = {};
            attach.forEach((attachs) => attachs.filename && appendAttachment(attachs, uniqueFiles));
            $('.chat-box').scrollTop($('.chat-box').prop("scrollHeight"));
        };

        $(document).on('click', '.chatlist', function() {
            const id = $(this).data('id');
            $('.chat').show();
            $.get('{{ route('add_employee_cnvrsn') }}', {
                id
            }, updateChatUI);
        });

        const sendMessage = () => {
            const formData = new FormData();
            formData.append('reply', $('#textarea1').val());
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('auth_id', $('input[name="auth_id"]').val());
            formData.append('support_message_id', $('input[name="conversation_id"]').val());

            $.ajax({
                url: '{{ route('store_reply') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#textarea1, #fileInput').val('');
                    updateChatUI(response);
                }
            });
        };

        $('#textarea1').on('keypress', function(event) {
            // on key enter 
            if (event.which === 13) {

                event.preventDefault();
                sendMessage();
            }
        });
        $('#sendButton').on('click', function() {
            sendMessage();
        });


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

        $('.chatlist:first').trigger('click');

    });
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
