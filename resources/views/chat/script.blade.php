<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('public/admin/dist/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved') }}.js"></script>
<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min') }}.js"></script>

<script>
    $(document).ready(function() {

        $('input[name="role"]').on('change', function() {
            $('.chatlist').hide();
            var selectedRole = $('input[name="role"]:checked').val();
            $('.chatlist[data-role="' + selectedRole + '"]').show();
            $('form[data-role]').hide();
            $('form[data-role="' + selectedRole + '"]').show();
        });

        $('input[name="role"]:checked').trigger('change');

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
            $('.user-details-jobs').empty();
            $('.scheule-job-details').empty();
            const firstUserName = partician[1]?.user.name || '';
            const firstUser = partician[1]?.user || '';
            console.log(firstUser.schedule);
            const otherUsers = partician.filter((p, i) => i > 0).length;
            $('.chat-meta-user-top').html(
                `<div class="align-items-center mb-3"><i class="fa fa-user px-2"></i><strong>${firstUserName || 'Unknown'}</strong> and</div><div id="partCount" class="fw-bold ps-2">${otherUsers} Others</div>`
            );

            combineData.forEach(appendChatItem);
            const uniqueFiles = {};
            attach.forEach((attachs) => attachs.filename && appendAttachment(attachs, uniqueFiles));
            $('.chat-box').scrollTop($('.chat-box').prop("scrollHeight"));

            $('.user-details-jobs').html(
                `<h4 class="pt-2 ps-4">${firstUserName || 'Unknown'}</h4><div class="px-4 border-bottom">  ${firstUser.mobile} , ${firstUser.user_address.address_line1} , ${firstUser.user_address.city} , ${firstUser.user_address.state_name} , ${firstUser.user_address.zipcode}</div>`
            );

            if (Array.isArray(partician)) {
                partician.forEach(function(participant, index) {
                    const firstUser = participant.user || {};
                    
                    if (firstUser.schedule && typeof firstUser.schedule === 'object') {
                        // Convert object to array and loop through it
                        Object.values(firstUser.schedule).forEach(function(item) {
                            if (item && item.job_model) { // Check if item and item.job_model exist
                                let fieldNames = ''; // Ensure fieldNames is initialized

                                // Check if fieldids exist and are valid
                                if (Array.isArray(item.job_model.fieldids) && item.job_model.fieldids.length > 0) {
                                    // Join the field names into a single string
                                    fieldNames = item.job_model.fieldids.map(function(f) {
                                        return f.field_name;
                                    }).join(', ');
                                }

                                // Conditionally add the badge if fieldNames is not empty
                                const fieldNamesBadge = fieldNames ? `<span class="badge bg-primary">${fieldNames}</span>` : '';

                                $('.scheule-job-details').append(`
                                    <h5 class="card-title py-1">
                                        <strong class="text-uppercase">
                                            #${item.job_model ? item.job_model.id : ''} ${fieldNamesBadge}
                                            ${item.job_model.warranty_type === 'in_warranty' ? `<span class="badge bg-warning">In Warranty</span>` : ''}
                                            ${item.job_model.warranty_type === 'out_warranty' ? `<span class="badge bg-danger">Out of Warranty</span>` : ''}
                                        </strong>
                                    </h5>

                                    <!-- Job Title and Description -->
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

            // Check if the checkbox is checked and append the appropriate value
            const isSendChecked = $('#flexSwitchCheckChecked').is(':checked') ? 'yes' : 'no';
            formData.append('is_send', isSendChecked);

            $.ajax({
                url: '{{ route('store_reply') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#textarea1').val(''); // Clear input fields
                    updateChatUI(response); // Update the UI with the new message

                    // Uncheck the SMS checkbox after sending
                    $('#flexSwitchCheckChecked').prop('checked', false);
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

    });
</script>
