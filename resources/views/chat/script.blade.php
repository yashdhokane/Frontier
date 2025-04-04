<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('public/admin/dist/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved') }}.js"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min') }}.js"></script>

<script>
    $(document).ready(function() {

        const $subjectSelect = $('#subjectSelect');
        const $chatList = $('.chat-list');

        // Set the default selected option to the last one and trigger the change event
        const lastSubjectValue = $subjectSelect.find('option:last').val();
        $subjectSelect.val(lastSubjectValue).trigger('change');

        // Function to scroll to the selected subject in the chat list
        function scrollToSelectedSubject() {
            const selectedSubjectId = $subjectSelect.val();

            // Locate the chat item that matches the selected subject
            const $targetChatItem = $chatList.find(`.subject-msg[data-subject-id="${selectedSubjectId}"]`);

            if ($targetChatItem.length) {
                // Calculate the offset position to scroll to, relative to the container
                const scrollTo = $targetChatItem.offset().top - $chatList.offset().top + $chatList.scrollTop();

                // Animate the scroll to the target chat item
                $chatList.animate({
                    scrollTop: scrollTo
                }, 500);
            }
        }

        // Listen for changes on the select element and call the scroll function
        $subjectSelect.on('change', scrollToSelectedSubject);







        $('#file_input').on('change', function() {
            const files = this.files;
            const filePreview = $('#filePreview');
            filePreview.empty();
            Array.from(files).forEach((file, index) => {
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(2) + ' KB';
                filePreview.append(`
            <div class="alert alert-info d-flex align-items-center py-1 m-0" data-file-index="${index}">
                <span class="me-2"><i class="fa fa-paperclip"></i></span>
                <span class="file-name">${fileName} (${fileSize})</span>
                <button type="button" class="btn-close ms-auto" aria-label="Close" data-remove-file="${index}"></button>
            </div>
        `);
            });
        });

        $(document).on('click', '[data-remove-file]', function() {
            const indexToRemove = $(this).data('remove-file');
            const fileInput = $('#file_input')[0];
            const dataTransfer = new DataTransfer();
            Array.from(fileInput.files).forEach((file, index) => {
                if (index !== indexToRemove) dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
            $(this).closest(`div[data-file-index="${indexToRemove}"]`).remove();
        });

        $(document).on('change', '#predefinedReplySelect', function() {
            const predefinedReply = $(this).val();
            if (predefinedReply) {
                const userName = $('.chat-user-name').text().trim();
                const yourName = $(this).data('auth-user').trim();

                let replyWithUserNames = predefinedReply
                    .replace(/{name}/gi, userName)
                    .replace(/{your name}/gi, yourName);

                $('#textarea1').val(replyWithUserNames);
            }
        });


        $('.btn-group button').on('click', function() {
            $('.btn-group button').removeClass('active-01 btn-info').addClass(
                'btn-secondary text-white font-weight-medium');
            $(this).removeClass('btn-secondary text-white font-weight-medium').addClass(
                'active-01 btn-info');
            $('.chatlist').hide();
            var selectedRole = $(this).data('role');
            $('.chatlist[data-role="' + selectedRole + '"]').show();
            $('form[data-role]').hide();
            $('form[data-role="' + selectedRole + '"]').show();
        });

        $('.btn-group button.active-01').trigger('click');


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

            $('.chat-list').empty();
            const groupedMessages = [];

            data.forEach((msg) => {
                const groupKey = `${msg.sender}-${msg.time}`;
                let group = groupedMessages.find(g => g.key === groupKey);

                if (!group) {
                    group = {
                        key: groupKey,
                        sender: msg.sender,
                        time: msg.time,
                        messages: [msg]
                    };
                    groupedMessages.push(group);
                } else {
                    if (!group.messages.some(m => m.message === msg.message)) {
                        group.messages.push(msg);
                    }
                }
            });

            groupedMessages.sort((a, b) => new Date(a.time) - new Date(b.time));

            groupedMessages.forEach(group => {
                const imgSrc = group.messages[0].user?.user_image ?
                    `public/images/Uploads/users/${group.messages[0].user.id}/${group.messages[0].user.user_image}` :
                    '{{ asset('public/images/login_img_bydefault.png') }}';

                const isSubjectType = group.messages.some(msg => msg.type === 'subject');

                let chatItem;
                if (isSubjectType) {
                    chatItem = `<li class="chat-item ps-2 mb-3">
                                    <div class="chat-content subject-content">
                                        <div class="subject-message text-center">
                                            ${group.messages.map(msg => `<div class="subject-msg" data-msg-id="${msg.id}" data-msg-subject="${msg.message}">${msg.message} <i class="far fa-edit fs-1 align-top edit-subject" style="cursor: pointer;" data-id="${msg.id}"  data-msg-subject="${msg.message}"></i></div>`).join('')}
                                        </div>
                                    </div>
                                </li>`;
                } else {
                    const chatItemsHTML = group.messages.map(data => {
                        const isYoutubeLink = (message) => {
                            const youtubeRegex =
                                /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/(watch\?v=|embed\/|v\/|.+\?v=)?([^&\n]{11})/;
                            return youtubeRegex.test(message);
                        };

                        return data.message ?
                            isYoutubeLink(data.message) ?
                            `<div class="file"><iframe width="320" height="240" src="${data.message.replace("watch?v=", "embed/")}" frameborder="0" allowfullscreen></iframe></div>` :
                            isFilePath(data.message) && ['jpg', 'jpeg', 'png', 'gif', 'bmp']
                            .includes(data.message.split('.').pop().toLowerCase()) ?
                            `<div class="file"><a href="public/images/Uploads/chat/${data.conversation_id}/${data.message}" target="_blank">
                        <img src="public/images/Uploads/chat/${data.conversation_id}/${data.message}" width="100" height="100" /></a></div>` :
                            isFilePath(data.message) ?
                            `<div class="file my-1"><a href="public/images/Uploads/chat/${data.conversation_id}/${data.message}" target="_blank">${data.message}</a></div>` :
                            `<div class="box bg-light">${data.message}</div>` :
                            `<div class="box bg-light">No message</div>`;
                    }).join('');

                    chatItem = `<li class="chat-item ps-2">
                                    <div class="chat-img"><img src="${imgSrc}" alt="user"></div>
                                    <div class="chat-content">
                                        <div class="d-flex">
                                            <span class="font-medium">${group.messages[0].user?.name || 'Unknown User'}, </span>
                                            <span class="chat-time m-1">${formatTime(group.time)}</span>
                                        </div>
                                        ${chatItemsHTML}
                                    </div>
                                </li>`;
                }

                $('.chat-list').append(chatItem);
            });
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
            $('.user-details-jobs').empty();
            $('.scheule-job-details').empty();

            if (partician.length > 2) {
                // Show the user at index 1 and count the rest
                const secondUserName = partician[1]?.user.name || 'Unknown';
                const otherUsersCount = partician.length - 2;
                $('.chat-meta-user-top').html(
                    `<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong class="chat-user-name">${secondUserName}</strong> and</div><div id="partCount" class="fw-bold ps-2">${otherUsersCount} Others</div>`
                );
            } else if (partician.length === 2) {
                // Just show the user at index 1 if there are only 2 participants
                const secondUserName = partician[1]?.user.name || 'Unknown';
                $('.chat-meta-user-top').html(
                    `<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong class="chat-user-name">${secondUserName}</strong></div>`
                );
            }
            const datacombineformsg = [combineData];
            // Append chat data
            datacombineformsg.forEach(appendChatItem);

            // Append attachment files
            const uniqueFiles = {};
            attach.forEach((attachs) => attachs.filename && appendAttachment(attachs, uniqueFiles));
            $('.chat-box').scrollTop($('.chat-box').prop("scrollHeight"));

            // Show user details
            const firstUserName = partician[1]?.user.name || 'Unknown';
            const firstUser = partician[1]?.user || {};
            $('.user-details-jobs').html(
                `<h4 class="pt-2 ps-4">${firstUserName}</h4>
                <div class="px-4 border-bottom ft11"><i class="fas fa-mobile-alt"></i>
                    ${firstUser.mobile || ''}, <i class="ri-mail-send-line"></i> ${firstUser.email || ''}, <i class="ri-map-pin-line"></i>${firstUser.user_address?.address_line1 || ''}, 
                    ${firstUser.user_address?.city || ''}, ${firstUser.user_address?.state_name || ''}, 
                    ${firstUser.user_address?.zipcode || ''}
                </div>`
            );
            let hasSchedules = false;

            if (Array.isArray(partician)) {
                partician.forEach(function(participant) {
                    if (participant.schedules && Object.keys(participant.schedules).length > 0) {
                        hasSchedules = true;
                    }
                });
                if (hasSchedules) {
                    $('.scheule-job-details').append(`<h4 class="py-2"> Today's Jobs</h4>`);
                }
            }

            // Append job details if available
            if (Array.isArray(partician)) {
                partician.forEach(function(participant) {
                    const schedules = participant.schedules || {};
                    if (schedules && typeof schedules === 'object') {
                        Object.values(schedules).forEach(function(item) {
                            if (item?.job_model) {
                                const fieldNames = Array.isArray(item.job_model.fieldids) ?
                                    item.job_model.fieldids.map(f => f.field_name).join(
                                        ', ') : '';

                                const fieldNamesBadge = fieldNames ?
                                    `<span class="badge bg-primary">${fieldNames}</span>` :
                                    '';

                                $('.scheule-job-details').append(`
                                    <h5 class="card-title py-1">
                                        <strong class="text-uppercase">
                                            #${item.job_model.id} ${fieldNamesBadge}
                                            ${item.job_model.warranty_type === 'in_warranty' ? `<span class="badge bg-warning">In Warranty</span>` : ''}
                                            ${item.job_model.warranty_type === 'out_warranty' ? `<span class="badge bg-danger">Out of Warranty</span>` : ''}
                                        </strong>
                                    </h5>
                                    <div class="pp_job_info pp_job_info_box">
                                        <h6 class="text-uppercase">${item.job_model.job_title.length > 20 ? item.job_model.job_title.substring(0, 20) + '...' : item.job_model.job_title}</h6>
                                        <div class="description_info">${item.job_model.description || ''}</div>
                                        <div class="pp_job_date text-primary">
                                            ${item.start_date_time && item.end_date_time ? formatDateRange(item.start_date_time, item.end_date_time, item.interval) : ''}
                                        </div>
                                    </div>
                                `);
                            }
                        });
                    }
                });
            }
        };

        const quickId = '{{ $quickId }}';
        const quickUserRole = '{{ $quickUserRole }}';

        let id = null;
        let userRole = null;
        let isSet = false;

        $(document).on('click', '.chatlist', function() {
            if (!isSet) {
                if (quickId && quickUserRole) {
                    id = quickId;
                    userRole = quickUserRole;
                } else {
                    id = $(this).data('id');
                    userRole = $(this).data('user-role');
                }
                isSet = true;
            } else {
                id = $(this).data('id') || id;
                userRole = $(this).data('user-role') || userRole;
            }

            $('.chat').show();
            $('#textarea1').val('');
            $('#predefinedReplySelect').val('').trigger('change');

            $.get('{{ route('add_employee_cnvrsn') }}', {
                id: id,
                user_role: userRole
            }, updateChatUI);
        });

        const sendMessage = () => {
            const formData = new FormData();
            const messageText = $('#textarea1').val().trim();
            const fileInput = $('#file_input')[0].files;

            if (!messageText && fileInput.length === 0) {
                alert('Please enter a message or select a file to send.');
                return;
            }

            formData.append('reply', messageText);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('auth_id', $('input[name="auth_id"]').val());
            formData.append('support_message_id', $('input[name="conversation_id"]').val());

            for (let i = 0; i < fileInput.length; i++) {
                formData.append('file[]', fileInput[i]);
            }

            const isSendChecked = $('#flexSwitchCheckChecked').is(':checked') ? 'yes' : 'no';
            formData.append('is_send', isSendChecked);



            $.ajax({
                url: '{{ route('store_reply') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#textarea1').val('');
                    $('#file_input').val('');
                    $('#filePreview').empty();
                    updateChatUI(response);
                    $('#flexSwitchCheckChecked').prop('checked', false);
                },
                error: function(xhr, status, error) {
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    alert('Error sending message. Please try again.');
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

        $('#search-customer').on('input', function() {
            var query = $(this).val().trim();
            if (query.length < 2) {
                $('.chatlist[data-role="new_customer"]').hide();
                $('.chatlist[data-role="customer"]').show();
                return;
            }


            $.ajax({
                url: '{{ route('search.customer') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    query: query
                },
                success: function(data) {
                    $('.chatlist[data-role="new_customer"]').remove();

                    // Loop through the returned customers and append them to the list
                    $.each(data, function(index, customer) {
                        var customerItem = `
                        <li class="chatlist cursor-pointer ps-2" data-id="${customer.id}" data-role="new_customer">
                            <a href="javascript:void(0)" class="chat-user message-item px-2">
                                <div class="mail-contnet">
                                    <h6 class="message-title" data-username="2">${customer.name}</h6>
                                </div>
                            </a>
                        </li>
                    `;

                        // Append the new customer item to the customer list
                        $('.new-cust-chat').append(customerItem);
                    });
                    if (data.length > 0) {
                        $('.chatlist[data-role="customer"]').show();
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }

            });
            $('.chatlist[data-role="customer"]').show();
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

        $('#submitSubject').on('click', function() {
            const formData = new FormData();

            // Add the subject and other form data
            formData.append('subject', $('#subjectInput').val());
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('auth_id', $('input[name="auth_id"]').val());
            formData.append('support_message_id', $('input[name="conversation_id"]').val());

            // Send AJAX request
            $.ajax({
                url: '{{ route('store_subject') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    alert('Error sending message. Please try again.');
                }
            });
        });

        $(document).on('click', '.edit-subject', function() {
            const msgId = $(this).data('id');
            const currentSubject = $(this).data('msg-subject');
            $('#subjectInput-msg').val(currentSubject);
            $('#editSubjectModal').data('msg-id', msgId);
            $('#editSubjectModal').modal('show');
        });

        $('#saveSubjectBtn').click(function() {
            const msgId = $('#editSubjectModal').data('msg-id');
            const newSubject = $('#subjectInput-msg').val();

            $.ajax({
                url: '{{ route('update_subject') }}',
                method: 'POST',
                data: {
                    id: msgId,
                    subject: newSubject,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $(`.subject-msg[data-msg-id="${msgId}"]`)
                        .attr('data-msg-subject', newSubject)
                        .html(
                            `${newSubject} <i class="far fa-edit fs-1 align-top edit-subject" style="cursor: pointer;" data-id="${msgId}" data-msg-subject="${newSubject}"></i>`
                        );
                    $('#editSubjectModal').modal('hide');
                },

                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });



    });
</script>
