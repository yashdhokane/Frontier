  @if (Route::currentRouteName() != 'dash')
      @extends('home')

      @section('content')
      @endif
      <style>
          .right-part {
              height: calc(100vh - 70px);
          }

          .scheule-job-details {
              height: 240px;
              overflow-y: auto;
          }

          .mailbox.app-chat {
              padding-bottom: 100px;
          }

          @media (min-width: 768px) {
              #main-wrapper[data-layout=vertical][data-sidebartype=mini-sidebar] .page-wrapper {
                  margin-left: 0px !important;
              }
          }

          #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
              margin-left: 0px !important;
          }

          .chatlist a.chat-user.message-item {
              padding: 10px 5px;
              margin-top: 5px;
          }

          .chatlist h6.message-title {
              margin-bottom: 0px;
          }

          .chatlist .text-capitalize {
              font-size: 12px;
              font-style: italic;
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
              <div class=" left-part bg-white fixed-left-part user-chat-box mt-5" style="height: 100%; overflow-y: auto;">
                  <div class="position-relative border-start" style="height: 100%; ">

                      <div class="m-2 mt-4">
                          <form data-role="technician">
                              <div class="searchbar d-flex justify-content-between">
                                  <input class="form-control" type="text" name="text-search"
                                      placeholder="Search Or Add Technician" />
                              </div>
                          </form>
                          <form data-role="employee">
                              <div class="searchbar d-flex justify-content-between">
                                  <input class="form-control" type="text" name="text-search"
                                      placeholder="Search Or Add Employees" />
                              </div>
                          </form>
                          <form data-role="customer" class="add_custm_conversation" action="{{ route('search.customer') }}"
                              method="post">
                              @csrf
                              <div class="searchbar d-flex justify-content-between">
                                  <input class="form-control" type="text" id="search-customer" name="add_new_customer"
                                      placeholder="Search Or Add Customer" />
                              </div>
                          </form>


                      </div>
                      <div class="btn-group px-3 p-2 border-bottom" role="group" aria-label="Second group">
                          <button type="button" class="badge btn btn-info active-01"
                              data-role="technician">Technician</button>
                          <button type="button" class="badge btn btn-light-info text-info font-weight-medium"
                              data-role="customer">Customer</button>
                          <button type="button" class="badge btn btn-light-info text-info font-weight-medium"
                              data-role="employee">Employee</button>
                      </div>

                      {{-- <div class=" d-flex justify-content-between">
                          <label class="btn btn-cyan badge">
                              <input type="radio" name="role" value="technician" style="display: none;" checked>
                              Technician
                          </label>
                          <label class="btn btn-cyan badge">
                              <input type="radio" name="role" value="customer" style="display: none;">
                              Customer
                          </label>
                          <label class="btn btn-cyan badge">
                              <input type="radio" name="role" value="employee" style="display: none;">
                              Employee
                          </label>
                      </div> --}}
                      @if (auth()->id() == 1)
                          <ul class="mailbox list-style-none new-cust-chat border shadow">
                          </ul>
                          <ul class="mailbox list-style-none app-chat">
                              @foreach ($technician as $item)
                                  <li class="chatlist cursor-pointer ps-2" data-id="{{ $item->id }}"
                                      data-user-role="{{ $item->role }}" data-role="technician">

                                      <a href="javascript:void(0)" class="chat-user message-item border me-1">

                                          <div class="mail-contnet">
                                              <h6 class="message-title" data-username="{{ $item->name }}">
                                                  {{ $item->name }}
                                              </h6>
                                              <span class="text-capitalize"> {{ $item->role }}</span>
                                          </div>

                                      </a>
                                  </li>
                              @endforeach
                              @foreach ($employee as $item)
                                  <li class="chatlist cursor-pointer ps-2" data-id="{{ $item->id }}"
                                      data-user-role="{{ $item->role }}" data-role="employee">

                                      <a href="javascript:void(0)" class="chat-user message-item border me-1">

                                          <div class="mail-contnet">
                                              <h6 class="message-title" data-username="{{ $item->name }}">
                                                  {{ $item->name }}
                                              </h6>
                                              <span class="text-capitalize"> {{ $item->role }}</span>
                                          </div>

                                      </a>
                                  </li>
                              @endforeach
                              @foreach ($customer as $item)
                                  <li class="chatlist cursor-pointer ps-2" data-id="{{ $item->id }}"
                                      data-user-role="{{ $item->role }}" data-role="customer">

                                      <a href="javascript:void(0)" class="chat-user message-item border me-1">

                                          <div class="mail-contnet">
                                              <h6 class="message-title" data-username="{{ $item->name }}">
                                                  {{ $item->name }}
                                              </h6>
                                              <span class="text-capitalize"> {{ $item->role }}</span>
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
                                                  <button id="autosubmitwithoutmodel" type="submit"
                                                      class="btn btn-primary" id="add_user_to_conversation_button">
                                                      <i class="fas fa-plus"></i> <!-- "Add" icon -->
                                                  </button>
                                              </div>
                                          </div>
                                          <div id="autocomplete-results-users">

                                          </div>



                                      </div>
                                  </form>

                              </div>
                              <div class="d-flex">
                                  <div class="chat-box scrollable w-75" style="height: calc(100vh - 300px)">

                                      <ul class="chat-list chat">

                                      </ul>

                                  </div>
                                  <div class=" shadow w-25">
                                      <div class="user-details-jobs border">
                                      </div>
                                      <div class="scheule-job-details mt-2 px-3">
                                      </div>
                                  </div>

                              </div>
                          </div>


                          <div class="card-body border-top border-bottom chat-send-message-footer">
                              <div class="row">
                                  <div id="filePreview" class="col-md-12"></div>
                                  <div class="col-md-12 w-50 mb-1 d-flex align-items-center">
                                      <label for="file_input" class="btn btn-outline-primary m-1 py-1">
                                          Attachment
                                      </label>
                                      <input type="file" name="file[]" id="file_input" style="display: none;"
                                          accept="image/*, .png, .pdf, .doc, .docx, .xls, .xlsx, .txt, .zip" multiple />

                                      <select id="predefinedReplySelect" data-auth-user="{{ auth()->user()->name }}"
                                          class="form-control predefined-reply-select select2" style="width: 100%;">
                                          <option value="" disabled selected>Choose a Predefined Reply</option>
                                          @foreach ($predefinedReplies as $reply)
                                              <option value="{{ $reply->pt_content }}">{{ $reply->pt_title }}</option>
                                          @endforeach
                                      </select>
                                      <label for="subject" class="btn form-control m-1 py-1" data-bs-toggle="modal"
                                          data-bs-target="#subjectModal">
                                          Subject / Topic
                                      </label>

                                  </div>
                                  <div class="col-12">
                                      <div class="input-field mt-0 mb-0">
                                          <input type="hidden" name="auth_id" value="{{ auth()->user()->id }}">
                                          <input type="hidden" name="conversation_id" value=""
                                              id="name_support_message_id">

                                          <div class="d-flex">

                                              <input id="textarea1" placeholder="Type and hit enter" name="reply"
                                                  style="font-family: Arial, FontAwesome"
                                                  class="message-type-box form-control border-0 flex-grow-1 me-2"
                                                  type="text" />
                                              <div>
                                                  <span class="mx-4"> SMS </span>
                                                  <div class="form-check form-switch mx-4">
                                                      <input class="form-check-input" name="is_send" type="checkbox"
                                                          value="yes" id="flexSwitchCheckChecked">
                                                  </div>
                                              </div>

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
      <!-- Modal -->
      <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="subjectModalLabel">Enter Subject / Topic</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="subjectForm">
                          <div class="mb-3">
                              <label for="subjectInput" class="form-label">Subject / Topic</label>
                              <input type="text" class="form-control" id="subjectInput"
                                  placeholder="Enter your subject here" required>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" id="submitSubject" form="subjectForm" class="btn btn-primary">Submit</button>
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
