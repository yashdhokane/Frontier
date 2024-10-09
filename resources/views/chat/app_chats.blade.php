  @if (Route::currentRouteName() != 'dash')
      @extends('home')

      @section('content')
      @endif
      <link rel="stylesheet" href="{{ url('public/admin/chat/style.css') }}">
      <!-- Page wrapper  -->
      <!-- -------------------------------------------------------------- -->
      <div class="" style="display: inline;">
          <div class="chat-application">
              <!-- -------------------------------------------------------------- -->
              <!-- Left Part  -->
              <!-- -------------------------------------------------------------- -->
              <div class=" left-part bg-white fixed-left-part user-chat-box mt-5" style="height: 100%; overflow-y: auto;">
                  <div class="position-relative border-start" style="height: 100%; ">
                      <div class="m-2 d-flex justify-content-between">
                          <label>
                              <input type="radio" name="role" value="employee" checked>
                              Employee
                          </label>
                          <label>
                              <input type="radio" name="role" value="customer">
                              Customer
                          </label>
                      </div>
                      <div class="p-2 border-bottom">
                          <form data-role="employee">
                              <div class="searchbar d-flex justify-content-between">
                                  <input class="form-control" type="text" name="text-search"
                                      placeholder="Search Or Add Employees" />
                              </div>
                          </form>
                          <form data-role="customer" class="add_custm_conversation" action="{{ route('search.customer') }}" method="post">
                            @csrf
                            <div class="searchbar d-flex justify-content-between">
                                <input class="form-control" type="text" id="search-customer" name="add_new_customer" placeholder="Search Or Add Customer" />
                            </div>
                        </form>
                        

                      </div>
                      @if (auth()->id() == 1)
                          <ul class="mailbox list-style-none app-chat">
                              @foreach ($employee as $item)
                                  <li class="chatlist cursor-pointer ps-2" data-id="{{ $item->id }}"
                                      data-role="employee">

                                      <a href="javascript:void(0)" class="chat-user message-item px-2">

                                          <div class="mail-contnet">
                                              <h6 class="message-title" data-username="2">
                                                  {{ $item->name }}
                                              </h6>
                                          </div>

                                      </a>
                                  </li>
                              @endforeach
                              @foreach ($customer as $item)
                                  <li class="chatlist cursor-pointer ps-2" data-id="{{ $item->id }}"
                                      data-role="customer">

                                      <a href="javascript:void(0)" class="chat-user message-item px-2">

                                          <div class="mail-contnet">
                                              <h6 class="message-title" data-username="2">
                                                  {{ $item->name }}
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

                      <div class="card chatting-box mb-0  support-message-box-show w-100 d-block" style="">

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
                                              <button id="sendButton" class="btn btn-primary" type="button">Send</button>
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
      <!-- -------------------------------------------------------------- -->
      <!-- End Page wrapper  -->
      @include('chat.script')
      @if (Route::currentRouteName() != 'dash')
      @endsection
  @endif
