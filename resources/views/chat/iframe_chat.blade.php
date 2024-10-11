  @if (Route::currentRouteName() != 'dash')
      @extends('home')

      @section('content')
      @endif

      <style>
          #main-wrapper[data-layout="vertical"][data-sidebartype="mini-sidebar"] .page-wrapper {
              margin-left: 0px !important;
          }

          .container-fluid {
              padding: 0px !important;
          }

          #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar {
              display: none !important;
          }

          #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar {
              display: none !important;
          }

          #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
              margin-left: 0px !important;
          }

          #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
              padding-top: 0px !important;
          }

          .page-wrapper {
              padding: 0px !important;
          }


          html,
          body {
              overflow: auto !important;

              margin: 0;
              padding: 0;
          }
      </style>
      <link rel="stylesheet" href="{{ url('public/admin/chat/style.css') }}">
      <!-- Page wrapper  -->
      <!-- -------------------------------------------------------------- -->
      <div class="" style="display: inline;">
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
                  <div class="position-relative" style="height: 100%; ">
                      <div class="p-3 border-bottom">
                          <form>
                              <div class="searchbar d-flex justify-content-between">
                                  <input class="form-control" type="text" name="text-search"
                                      placeholder="Search Users" />
                                  <i class="fa fa-comment-dots fs-7 ms-1 pt-1" id="addNewChat"></i>
                              </div>
                          </form>
                      </div>
                      @if (auth()->id() == 1)
                          <ul class="mailbox list-style-none app-chat">
                              @foreach ($chatConversion as $item)
                                  <li class="chatlist cursor-pointer" data-id="{{ $item->id }}">

                                      <a href="javascript:void(0)" class="chat-user message-item px-2">

                                          <div class="mail-contnet">
                                              <h6 class="message-title" data-username="2">
                                                  @foreach ($item->Participant as $value)
                                                      @if ($value->user)
                                                          {{ $value->user->name }},
                                                      @endif
                                                  @endforeach
                                              </h6>
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

                      <div class="chat-not-selected w-100">
                          <div class="text-center">
                              <span class="display-5 text-info"><i data-feather="message-square"></i></span>
                              <h5>Open chat from the list</h5>
                          </div>
                          <form class="conversation_form" action="{{ route('addUserToParticipant') }}" method="post">
                              @csrf
                              <div class="searchbar">
                                  <div style="display:flex;" class="justify-content-center">
                                      <div class="col-md-9">
                                          <input class="form-control" type="text"
                                              placeholder="Search dispatcher or customer" class="form-control"
                                              id="participants" name="users" required />
                                          <input class="form-control" type="hidden" id="selected_user_id" name="user_id">
                                      </div>
                                      <div class="col-md-1" style="margin-left: 5px;">
                                          <button type="submit" class="btn btn-primary" id="add_participant">
                                              <i class="fas fa-plus"></i> <!-- "Add" icon -->
                                          </button>
                                      </div>
                                  </div>
                                  <div id="autocomplete-results-users-part">

                                  </div>



                              </div>
                          </form>
                      </div>


                      <div class="card chatting-box mb-0  support-message-box-show w-100" style="">

                          <div class="">
                              <div class="d-flex justify-content-between shadow py-2 px-4">
                                  <div class="chat-meta-user-top d-flex">

                                  </div>

                                  <form class="conversation_form" action="{{ route('addUserToConversation') }}"
                                      method="post">
                                      @csrf
                                      <div class="searchbar">
                                          <div style="display:flex;">
                                              <div class="col-md-9">
                                                  <input class="form-control" type="text" placeholder="Add Users"
                                                      class="form-control" id="users" name="users" required />
                                                  <input class="form-control" type="hidden"
                                                      id="add_user_to_conversation_hidden-new" name="conversation_id">
                                              </div>
                                              <div class="col-md-1" style="margin-left: 5px;">
                                                  <button id="autosubmitwithoutmodel" type="submit" class="btn btn-primary"
                                                      id="add_user_to_conversation_button">
                                                      <i class="fas fa-plus"></i> <!-- "Add" icon -->
                                                  </button>
                                              </div>
                                          </div>
                                          <div id="autocomplete-results-users">

                                          </div>



                                      </div>
                                  </form>

                              </div>

                              <div class="chat-box scrollable" style="height: calc(100vh - 300px)">

                                  <ul class="chat-list chat">
                                      <li class="chat-item">
                                          <div class="chat-img">

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
                                          <input type="hidden" name="conversation_id" value=""
                                              id="name_support_message_id">
                                          {{-- <input id="fileInput" type="hidden" name="file" style="display: none;" /> --}}
                                          {{-- <label for="fileInput" class="btn btn-secondary me-2">
                                            <i class="fa fa-paperclip"></i> Attach File
                                        </label> --}}
                                          <div class="d-flex">
                                              <input id="textarea1" placeholder="Type and hit enter" name="reply"
                                                  style="font-family: Arial, FontAwesome"
                                                  class="message-type-box form-control border-0 flex-grow-1 me-2"
                                                  type="text" />
                                              <button id="sendButton" class="btn btn-primary"
                                                  type="button">Send</button>
                                              <input type="hidden" class="form-control" id="user" name="users"
                                                  required />

                                              <input type="hidden" class="form-control" id="conversation_id"
                                                  name="conversation_id" required />
                                          </div>
                                      </div>
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
      @include('chat.script')
      @if (Route::currentRouteName() != 'dash')
      @endsection
  @endif
