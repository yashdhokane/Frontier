@extends('home')

@section('content')
    <style>
        .message-type-box {
            border: 1px solid #2962ff !important;
            height: 60px;
        }
    </style>
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="">
        <div class="chat-application">
            <!-- -------------------------------------------------------------- -->
            <!-- Left Part  -->
            <!-- -------------------------------------------------------------- -->
            <div class="left-part bg-white fixed-left-part user-chat-box">
                <!-- Mobile toggle button -->
                <a class="ri-menu-fill ri-close-fill btn btn-success show-left-part d-block d-md-none"
                    href="javascript:void(0)"></a>
                <!-- Mobile toggle button -->
                <div class="p-3">
                    <h4>Chat Sidebar</h4>
                </div>
                <div class="scrollable position-relative" style="height: 100%">
                    <div class="p-3 border-bottom">
                        <form>
                            <div class="searchbar">
                                <input class="form-control" type="text" placeholder="Search Messages" />
                            </div>
                        </form>
                    </div>
                    @if (auth()->id() == 1)
                        <ul class="mailbox list-style-none app-chat">
                            @foreach ($chats as $item)
                                <li class="chatlist cursor-pointer">
                                    <input type="hidden" value="{{ $item->id }}" class="support_message_id">
                                    <input type="hidden" value="{{ $item->user_one }}" class="user_one">
                                    <input type="hidden" value="{{ $item->user_two }}" class="user_two">
                                    <div class="message-center  chat-users">
                                        <span class="chat-user message-item">
                                            <span class="user-img">
                                                @if ($item->user->user_image && file_exists(public_path('images/technician/' . $item->user->user_image)))
                                                    <img src="{{ asset('public/images/technician/' . $item->user->user_image) }}"
                                                        alt="user" class="rounded-circle" />
                                                @else
                                                    <img src="{{ asset('public/images/technician/1707736455_avatar-8.png') }}"
                                                        alt="user" class="rounded-circle" />
                                                @endif

                                                <span class="profile-status online pull-right"></span>
                                            </span>
                                            <div class="mail-contnet">
                                                @if ($item->user)
                                                    <h5 class="message-title cursor-pointer">{{ $item->user->name }}</h5>
                                                @else
                                                    <p class="m-00">Technician not available</p>
                                                @endif
                                                <span class="mail-desc">Just see the my admin!</span>
                                                <span class="time">{{ $item->created_at->format('g:i A') }}</span>
                                            </div>
                                        </span>
                                    </div>
                                </li>
                            @endforeach


                        </ul>
                    @endif
                </div>
            </div>
            <!-- -------------------------------------------------------------- -->
            <!-- End Left Part  -->
            <!-- -------------------------------------------------------------- -->
            <!-- -------------------------------------------------------------- -->
            <!-- Right Part  Mail Compose -->
            <!-- -------------------------------------------------------------- -->
            <div class="right-part chat-container">
                <div class="chat-box-inner-part">
                    <div class="chat-not-selected">
                        <div class="text-center">
                            <span class="display-5 text-info"><i data-feather="message-square"></i></span>
                            <h5>Open chat from the list</h5>
                        </div>
                    </div>
                    <div class="card chatting-box mb-0">
                        <div class="card-body">
                            <div class="chat-meta-user pb-3 border-bottom">
                                <div class="current-chat-user-name">
                                    <span class="user-container">
                                        <img src="../../assets/images/users/1.jpg" alt="dynamic-image"
                                            class="rounded-circle" width="45" />
                                        <span class="name fw-bold ms-2"></span>
                                    </span>
                                </div>
                            </div>
                            <!-- <h4 class="card-title">Chat Messages</h4> -->
                            <div class="chat-box scrollable" style="height: calc(100vh - 300px)">

                                <ul class="chat-list chat">
                                    <li class="chat-item">
                                        <div class="chat-img">
                                            <img src="../../assets/images/users/" alt="user" />
                                        </div>
                                        <div class="chat-content">
                                            <h6 class="font-medium"></h6>
                                            <div class="box bg-light"></div>
                                        </div>
                                        <div class="chat-time"></div>
                                    </li>
                                </ul>

                            </div>
                        </div>


                        <div class="card-body border-top border-bottom chat-send-message-footer">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-field mt-0 mb-0">
                                        <input type="hidden" name="auth_id" value="{{ auth()->id() }}">
                                        <input type="hidden" name="support_message_id" value=""
                                            id="name_support_message_id">

                                        <input id="textarea1" placeholder="Type and hit enter" name="reply"
                                            style="font-family: Arial, FontAwesome"
                                            class="message-type-box form-control border-0" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Page wrapper  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Event listener for the button click
            $('.chatlist').click(function() {

                $('.chat-not-selected').hide();
                $('.chatting-box').show();
                $('.chat').show();


                let user_one = $(this).find('.user_one').val();

                let user_two = $(this).find('.user_two').val();

                let id = $(this).find('.support_message_id').val();

                $('#name_support_message_id').val(id);

                // Make an AJAX request to fetch data
                $.ajax({
                    url: 'get-chat-messages', // Replace 'your-api-endpoint' with your actual API endpoint
                    method: 'GET',
                    data: {
                        id: id,
                        user_one: user_one,
                        user_two: user_two,
                    },
                    dataType: 'json', // Specify the expected data type
                    success: function(response) {
                        // Access user data and messages data from the response
                        var users = response.users;
                        var messages = response.messages;

                        $('.chat-list').empty();

                        // Example: Append user data to a container
                        $.each(users, function(index, user) {
                            $('.user-container').empty();
                            $('.user-container').append(
                                '<img src="public/images/technician/' + user
                                .user_image +
                                '" alt="dynamic-image"class="rounded-circle" width="45" /><span class="name fw-bold ms-2">' +
                                user.name + '</span>'
                            );
                        });

                        // Example: Append message data to a container
                        $.each(messages, function(index, message) {
                            var createdAtDate = new Date(message.created_at);
                            var formattedDate = createdAtDate.toLocaleDateString();
                            var formattedTime = createdAtDate.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            // Check if the user is a technician
                            var userImageSrc = message.user.role === 'technician' ?
                                'public/images/technician/' : 'public/images/users/';

                            // Check if the user image is available
                            var userImage = message.user.user_image ? message.user
                                .user_image : '1707736455_avatar-8.png';

                            // Construct the image source
                            var imageSrc = userImageSrc + userImage;

                            $('.chat-list').append(
                                '<li class="chat-item"><div class="chat-img"><img src="' +
                                imageSrc + '" alt="user" /></div>' +
                                '<div class="chat-content"><h6 class="font-medium">' +
                                message.user.name + '</h6>' +
                                '<div class="box bg-light">' + message.reply +
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

            });


            // Send AJAX request to refresh the chat list
            function refreshChatList() {
                // Make an AJAX request to fetch data
                $.ajax({
                    url: 'get-chat-messages', // Replace 'your-api-endpoint' with your actual API endpoint
                    method: 'GET',
                    data: {
                        id: $('#name_support_message_id').val(),
                        user_one: $('.user_one').val(),
                        user_two: $('.user_two').val(),
                    },
                    dataType: 'json', // Specify the expected data type
                    success: function(response) {
                        // Clear existing chat list
                        $('.chat-list').empty();

                        // Append new chat messages
                        $.each(response.messages, function(index, message) {
                            var createdAtDate = new Date(message.created_at);

                            // Format date and time
                            var formattedDate = createdAtDate
                                .toLocaleDateString(); // Change the date format if needed
                            var formattedTime = createdAtDate.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            // Check if the user is a technician
                            var userImageSrc = message.user.role == 'technician' ?
                                'public/images/technician/' : 'public/images/users/';

                            // Check if the user image is available
                            var userImage = message.user.user_image ? message.user
                                .user_image : '1707736455_avatar-8.png';

                            // Construct the image source
                            var imageSrc = userImageSrc + userImage;

                            $('.chat-list').append(
                                '<li class="chat-item"><div class="chat-img"><img src="' +
                                imageSrc +
                                '" alt="user" /></div><div class="chat-content"><h6 class="font-medium">' +
                                message.user.name +
                                '</h6><div class="box bg-light">' + message.reply +

                                '</div></div><div class="chat-time">' +
                                formattedDate + ' ' + formattedTime +
                                '</div></li>'
                            );
                        });
                        // Scroll to the bottom of the chat list
                        $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Listen for keypress event on input field
            $('#textarea1').on('keypress', function(event) {
                // Check if Enter key is pressed (key code 13)
                if (event.which === 13) {
                    // Prevent the default form submission behavior
                    event.preventDefault();

                    // Gather form data
                    var formData = {
                        _token: '{{ csrf_token() }}', // CSRF token
                        auth_id: $('input[name="auth_id"]').val(),
                        support_message_id: $('input[name="support_message_id"]').val(),
                        reply: $(this).val()
                    };

                    // Send AJAX request
                    $.ajax({
                        url: 'store_reply', // URL to submit the form data
                        method: 'POST', // Method to submit the form data
                        data: formData, // Form data
                        dataType: 'json', // Expected data type
                        success: function(response) {
                            // Handle success response here
                            console.log(response);
                            $('#textarea1').val('');
                            // Refresh the chat list
                            refreshChatList();

                        },
                        error: function(xhr, status, error) {
                            // Handle error response here
                            console.error(xhr.responseText);
                        }
                    });
                }
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

            // Trigger AJAX function to load chat messages based on the extracted parameters

            $.ajax({
                url: 'get-chat-messages', // Replace 'your-api-endpoint' with your actual API endpoint
                method: 'GET',
                data: {
                    id: id,
                    user_one: user_one,
                    user_two: user_two,
                },
                dataType: 'json', // Specify the expected data type
                success: function(response) {
                    // Access user data and messages data from the response
                    var users = response.users;
                    var messages = response.messages;

                    $('.chat-list').empty();

                    // Example: Append user data to a container
                    $.each(users, function(index, user) {
                        $('.user-container').empty();
                        var imageUrl = user.user_image ? ('public/images/technician/' + user
                                .user_image) :
                            'public/images/technician/1707736455_avatar-8.png';
                        $('.user-container').append(
                            '<img src="' + imageUrl +
                            '" alt="dynamic-image" class="rounded-circle" width="45" /><span class="name fw-bold ms-2">' +
                            user.name + '</span>'
                        );
                    });


                    // Example: Append message data to a container
                    $.each(messages, function(index, message) {
                        var createdAtDate = new Date(message.created_at);
                        var formattedDate = createdAtDate.toLocaleDateString();
                        var formattedTime = createdAtDate.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        // Check if the user is a technician
                        var userImageSrc = message.user.role === 'technician' ?
                            'public/images/technician/' : 'public/images/users/';

                        // Check if the user image is available
                        var userImage = message.user.user_image ? message.user.user_image :
                            '1707736455_avatar-8.png';

                        // Construct the image source
                        var imageSrc = userImageSrc + userImage;

                        // Constructing the HTML for each message and appending it to the chat-list
                        $('.chat-list').append(
                            '<li class="chat-item"><div class="chat-img"><img src="' +
                            imageSrc + '" alt="user" /></div>' +
                            '<div class="chat-content"><h6 class="font-medium">' +
                            message.user.name + '</h6>' +
                            '<div class="box bg-light">' + message.reply +
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
        });
    </script>
@endsection
