<aside class="customizer">

      <a href="javascript:void(0)" class="service-panel-toggle"

        ><i data-feather="settings" class="feather-sm fa fa-spin"></i

      ></a>

      <div class="customizer-body">

        <ul class="nav customizer-tab" role="tablist">

          <li class="nav-item">

            <a

              class="nav-link active"

              id="pills-home-tab"

              data-bs-toggle="pill"

              href="#pills-home"

              role="tab"

              aria-controls="pills-home"

              aria-selected="true"

              ><i class="ri-tools-fill fs-6"></i

            ></a>

          </li>

          <li class="nav-item">

            <a

              class="nav-link"

              id="pills-profile-tab"

              data-bs-toggle="pill"

              href="#chat"

              role="tab"

              aria-controls="chat"

              aria-selected="false"

              ><i class="ri-message-3-line fs-6"></i

            ></a>

          </li>

          <li class="nav-item">

            <a

              class="nav-link"

              id="pills-contact-tab"

              data-bs-toggle="pill"

              href="#pills-contact"

              role="tab"

              aria-controls="pills-contact"

              aria-selected="false"

              ><i class="ri-timer-line fs-6"></i

            ></a>

          </li>

        </ul>

        <div class="tab-content" id="pills-tabContent">

          <!-- Tab 1 -->

          <div

            class="tab-pane fade show active"

            id="pills-home"

            role="tabpanel"

            aria-labelledby="pills-home-tab"

          >

            <div class="p-3 border-bottom">

              <!-- Sidebar -->

              <h5 class="font-medium mb-2 mt-2">Layout Settings</h5>

              <div class="form-check mt-2">

                <input type="checkbox" class="form-check-input" name="theme-view" id="theme-view" />

                <label class="form-check-label" for="theme-view">Dark Theme</label>

              </div>

              <div class="form-check mt-2">

                <input

                  type="checkbox"

                  class="form-check-input sidebartoggler"

                  name="collapssidebar"

                  id="collapssidebar"

                />

                <label class="form-check-label" for="collapssidebar">Collapse Sidebar</label>

              </div>

              <div class="form-check mt-2">

                <input

                  type="checkbox"

                  class="form-check-input"

                  name="sidebar-position"

                  id="sidebar-position"

                />

                <label class="form-check-label" for="sidebar-position">Fixed Sidebar</label>

              </div>

              <div class="form-check mt-2">

                <input

                  type="checkbox"

                  class="form-check-input"

                  name="header-position"

                  id="header-position"

                />

                <label class="form-check-label" for="header-position">Fixed Header</label>

              </div>

              <div class="form-check mt-2">

                <input

                  type="checkbox"

                  class="form-check-input"

                  name="boxed-layout"

                  id="boxed-layout"

                />

                <label class="form-check-label" for="boxed-layout">Boxed Layout</label>

              </div>

            </div>

            <div class="p-3 border-bottom">

              <!-- Logo BG -->

              <h5 class="font-medium mb-2 mt-2">Logo Backgrounds</h5>

              <ul class="theme-color">

                <li class="theme-item">

                  <a href="#" class="theme-link" data-logobg="skin1"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-logobg="skin2"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-logobg="skin3"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-logobg="skin4"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-logobg="skin5"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-logobg="skin6"></a>

                </li>

              </ul>

              <!-- Logo BG -->

            </div>

            <div class="p-3 border-bottom">

              <!-- Navbar BG -->

              <h5 class="font-medium mb-2 mt-2">Navbar Backgrounds</h5>

              <ul class="theme-color">

                <li class="theme-item">

                  <a href="#" class="theme-link" data-navbarbg="skin1"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-navbarbg="skin2"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-navbarbg="skin3"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-navbarbg="skin4"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-navbarbg="skin5"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-navbarbg="skin6"></a>

                </li>

              </ul>

              <!-- Navbar BG -->

            </div>

            <div class="p-3 border-bottom">

              <!-- Logo BG -->

              <h5 class="font-medium mb-2 mt-2">Sidebar Backgrounds</h5>

              <ul class="theme-color">

                <li class="theme-item">

                  <a href="#" class="theme-link" data-sidebarbg="skin1"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-sidebarbg="skin2"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-sidebarbg="skin3"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-sidebarbg="skin4"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-sidebarbg="skin5"></a>

                </li>

                <li class="theme-item">

                  <a href="#" class="theme-link" data-sidebarbg="skin6"></a>

                </li>

              </ul>

              <!-- Logo BG -->

            </div>

          </div>

          <!-- End Tab 1 -->

          <!-- Tab 2 -->

          <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="pills-profile-tab">

            <ul class="mailbox list-style-none mt-3">

              <li>

                <div class="message-center chat-scroll">

                  <a href="#" class="message-item" id="chat_user_1" data-user-id="1">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/1.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status online pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Pavan kumar</h5>

                      <span class="mail-desc">Just see the my admin!</span>

                      <span class="time">9:30 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_2" data-user-id="2">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/2.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status busy pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Sonu Nigam</h5>

                      <span class="mail-desc">I've sung a song! See you at</span>

                      <span class="time">9:10 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_3" data-user-id="3">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/3.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status away pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Arijit Sinh</h5>

                      <span class="mail-desc">I am a singer!</span>

                      <span class="time">9:08 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_4" data-user-id="4">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/4.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status offline pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Nirav Joshi</h5>

                      <span class="mail-desc">Just see the my admin!</span>

                      <span class="time">9:02 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_5" data-user-id="5">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/5.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status offline pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Sunil Joshi</h5>

                      <span class="mail-desc">Just see the my admin!</span>

                      <span class="time">9:02 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_6" data-user-id="6">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/6.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status offline pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Akshay Kumar</h5>

                      <span class="mail-desc">Just see the my admin!</span>

                      <span class="time">9:02 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_7" data-user-id="7">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/7.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status offline pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Pavan kumar</h5>

                      <span class="mail-desc">Just see the my admin!</span>

                      <span class="time">9:02 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                  <!-- Message -->

                  <a href="#" class="message-item" id="chat_user_8" data-user-id="8">

                    <span class="user-img">

                      <img

                        src="{{ asset('public/admin/assets/images/users/8.jpg') }}"

                        alt="user"

                        class="rounded-circle"

                      />

                      <span class="profile-status offline pull-right"></span>

                    </span>

                    <div class="mail-contnet">

                      <h5 class="message-title">Varun Dhavan</h5>

                      <span class="mail-desc">Just see the my admin!</span>

                      <span class="time">9:02 AM</span>

                    </div>

                  </a>

                  <!-- Message -->

                </div>

              </li>

            </ul>

          </div>

          <!-- End Tab 2 -->

          <!-- Tab 3 -->

          <div

            class="tab-pane fade p-3"

            id="pills-contact"

            role="tabpanel"

            aria-labelledby="pills-contact-tab"

          >

            <h6 class="mt-3 mb-3">Activity Timeline</h6>

            <div class="steamline">

              <div class="sl-item">

                <div class="sl-left bg-light-success text-success">

                  <i data-feather="user" class="feather-sm fill-white"></i>

                </div>

                <div class="sl-right">

                  <div class="font-medium">Meeting today <span class="sl-date"> 5pm</span></div>

                  <div class="desc">you can write anything</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left bg-light-info text-info">

                  <i data-feather="camera" class="feather-sm fill-white"></i>

                </div>

                <div class="sl-right">

                  <div class="font-medium">Send documents to Clark</div>

                  <div class="desc">Lorem Ipsum is simply</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left">

                  <img class="rounded-circle" alt="user" src="{{ asset('public/admin/assets/images/users/2.jpg') }}" />

                </div>

                <div class="sl-right">

                  <div class="font-medium">

                    Go to the Doctor <span class="sl-date">5 minutes ago</span>

                  </div>

                  <div class="desc">Contrary to popular belief</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left">

                  <img class="rounded-circle" alt="user" src="{{ asset('public/admin/assets/images/users/1.jpg') }}" />

                </div>

                <div class="sl-right">

                  <div>

                    <a href="#">Stephen</a>

                    <span class="sl-date">5 minutes ago</span>

                  </div>

                  <div class="desc">Approve meeting with tiger</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left bg-light-primary text-primary">

                  <i data-feather="user" class="feather-sm fill-white"></i>

                </div>

                <div class="sl-right">

                  <div class="font-medium">Meeting today <span class="sl-date"> 5pm</span></div>

                  <div class="desc">you can write anything</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left bg-light-info text-info">

                  <i data-feather="send" class="feather-sm fill-white"></i>

                </div>

                <div class="sl-right">

                  <div class="font-medium">Send documents to Clark</div>

                  <div class="desc">Lorem Ipsum is simply</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left">

                  <img class="rounded-circle" alt="user" src="{{ asset('public/admin/assets/images/users/4.jpg') }}" />

                </div>

                <div class="sl-right">

                  <div class="font-medium">

                    Go to the Doctor <span class="sl-date">5 minutes ago</span>

                  </div>

                  <div class="desc">Contrary to popular belief</div>

                </div>

              </div>

              <div class="sl-item">

                <div class="sl-left">

                  <img class="rounded-circle" alt="user" src="{{ asset('public/admin/assets/images/users/6.jpg') }}" />

                </div>

                <div class="sl-right">

                  <div>

                    <a href="#">Stephen</a>

                    <span class="sl-date">5 minutes ago</span>

                  </div>

                  <div class="desc">Approve meeting with tiger</div>

                </div>

              </div>

            </div>

          </div>

          <!-- End Tab 3 -->

        </div>

      </div>

    </aside>