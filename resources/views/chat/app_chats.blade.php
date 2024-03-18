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
            <div class=" left-part bg-white fixed-left-part user-chat-box" style="height: 100%; overflow-y: auto;">
                <!-- Mobile toggle button -->
                <a class="ri-menu-fill ri-close-fill btn btn-success show-left-part d-block d-md-none"
                    href="javascript:void(0)"></a>
                <!-- Mobile toggle button -->
                <div class="p-3">
                    <h4>Chat Sidebar</h4>
                </div>
                <div class=" position-relative" style="height: 100%; ">
                    <div class="p-3 border-bottom">
                        <form>
                            <div class="searchbar">
                                <input class="form-control" type="text" placeholder="Search Messages" />
                            </div>
                        </form>
                    </div>
                    @if (auth()->id() == 1)
                        <ul class="mailbox list-style-none app-chat">
                            @foreach ($chatConversion as $item)
                            <li class="chatlist cursor-pointer" data-id="{{$item->id}}">
                                
                                <a href="javascript:void(0)" class="chat-user message-item px-2">
                                    <div class="d-flex align-items-center user-img1">
                                        @foreach ($item->Participant as $value)
                                            @if ($value->user)
                                                <img src="{{ asset('public/images/technician/' . $value->user->user_image) }}"
                                                    class="rounded-circle me-n2 card-hover border border-2 border-white user-img" width="35">
                                            @endif
                                        @endforeach
                                    </div>
                        
                                    <div class="mail-contnet">
                                        <h6 class="message-title" data-username="2">
                                            @foreach ($item->Participant as $value)
                                                @if ($value->user)
                                                    {{ $value->user->name }},
                                                @endif
                                            @endforeach
                                        </h6>
                                    </div>
                        
                                    <div>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <div class="time">{{$item->last_activity}}</div>
                                    </div>
                                </a>
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
   <script src="{{ asset('public/admin/dist/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.js')}}"></script>
  <script src="{{ asset('public/admin/dist/libs/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved')}}.js"></script>
     <script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min')}}.js"></script> 
    <script>
        $(document).ready(function() {
            // done part start 
            // Event listener for the button click
            $('.chatlist').click(function() {
                

                $('.chat-not-selected').hide();
                $('.chatting-box').show();
                $('.chat').show();
                var id = $(this).data('id');

                // Make an AJAX request to fetch data
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
                                '<img src="public/images/technician/' + chats.user
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
                                'public/images/technician/' : 'public/images/users/';

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

            });


            // Send AJAX request to refresh the chat list
            function refreshChatList() {
                // Make an AJAX request to fetch data
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
                                '<img src="public/images/technician/' + chats.user
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
                                'public/images/technician/' : 'public/images/users/';

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
            }

            
            // done part start end

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
                                '<img src="public/images/technician/' + chats.user
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
                                'public/images/technician/' : 'public/images/users/';

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

            
            // done part start 
        });
    </script>
@endsection

