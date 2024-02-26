<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="keywords"
      content="Technician Management, System Management, Frontier Tech Services"
    />
    <meta
      name="description"
      content="Frontier Tech Services - Technician management and system management."
    />
    <meta name="robots" content="noindex,nofollow" />
    <title>Frontier Tech Services - Web Application to manage technicians</title>
    <link rel="canonical" href="https://gaffis.in/frontier/webapp/design/html/ltr/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('public/admin/assets/images/favicon.png')}}" />
    <!-- Custom CSS -->
    <link href="{{asset('public/admin/dist/css/style.min.css')}}" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="main-wrapper">
      <!-- -------------------------------------------------------------- -->
      <!-- Preloader - style you can find in spinners.css -->
      <!-- -------------------------------------------------------------- -->
      <div class="preloader">
        <svg
          class="tea lds-ripple"
          width="37"
          height="48"
          viewbox="0 0 37 48"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z"
            stroke="#2962FF"
            stroke-width="2"
          ></path>
          <path
            d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34"
            stroke="#2962FF"
            stroke-width="2"
          ></path>
          <path
            id="teabag"
            fill="#2962FF"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z"
          ></path>
          <path
            id="steamL"
            d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke="#2962FF"
          ></path>
          <path
            id="steamR"
            d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13"
            stroke="#2962FF"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></path>
        </svg>
      </div>
      <!-- -------------------------------------------------------------- -->
      <!-- Preloader - style you can find in spinners.css -->
      <!-- -------------------------------------------------------------- -->
      <!-- -------------------------------------------------------------- -->
      <!-- Login box.scss -->
      <!-- -------------------------------------------------------------- -->
      <div
        class="auth-wrapper d-flex no-block justify-content-center align-items-center"
        style="background: url(public/admin/assets/images/big/auth-bg.jpg) no-repeat center center"
      >
        <div class="auth-box">
          <div id="loginform">
            <div class="logo">
              <span class="db"><img src="{{asset('public/admin/assets/images/logo-icon.png')}}" alt="logo" /></span>
              <h5 class="font-medium mb-3">Sign In to Admin</h5>
              @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

            </div>
            <!-- Form -->
            <div class="row">
              <div class="col-12">
                <form class="form-horizontal mt-3" id="loginform" method="POST" action="{{ route('login') }}">
                    @csrf
                  <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                      <i data-feather="user" class="feather-sm"></i>
                    </span>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                      class="form-control form-control-lg"
                      aria-describedby="basic-addon1"
                    />
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon2">
                      <i data-feather="edit-2" class="feather-sm"></i>
                    </span>
                    <input  id="password"  type="password" name="password" required autocomplete="current-password" class="form-control form-control-lg"
                      aria-describedby="basic-addon1"
                    />
                  </div>
                  <div class="mb-3 row">
                    <div class="col-md-12">
                      <div class="form-check d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="customCheck1" name="remember" />
                        <label class="form-check-label ms-2 mt-1" for="customCheck1"
                          >Remember me</label
                        >
                     @if (Route::has('password.request'))
                            <a id="to-recover" href="javascript:void(0)" class="text-dark ms-auto d-flex align-items-center"  href="{{ route('password.request') }}"><i data-feather="lock" class="feather-sm me-1"></i>Forgot pwd?</a>
                        @endif 
                      </div>
                    </div>
                  </div>
                  <div class="mb-3 text-center">
                    <div class="col-xs-12 pb-3">
                      <button class="btn d-block w-100 btn-lg btn-info font-medium" type="submit">
                        Log In
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2 text-center">
                      <div class="social">
                        <a
                          href="javascript:void(0)"
                          class="btn btn-facebook"
                          data-bs-toggle="tooltip"
                          title=""
                          data-original-title="Login with Facebook"
                        >
                          <i aria-hidden="true" class="ri-facebook-box-fill fs-4"></i>
                        </a>
                        <a
                          href="javascript:void(0)"
                          class="btn btn-googleplus"
                          data-bs-toggle="tooltip"
                          title=""
                          data-original-title="Login with Google"
                        >
                          <i aria-hidden="true" class="ri-google-fill fs-4"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3 mb-0 mt-2">
                    <div class="col-sm-12 text-center">
                      Don't have an account?
                      <a href="{{route('register')}}" class="text-info ms-1"
                        ><b>Sign Up</b></a
                      >
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div id="recoverform">
            <div class="logo">
              <span class="db"><img src="{{asset('public/admin/assets/images/logo-icon.png')}}" alt="logo" /></span>
              <h5 class="font-medium mb-3">Recover Password</h5>
              <span>Enter your Email and instructions will be sent to you!</span>
            </div>
            <div class="row mt-3">
              <!-- Form -->
<form class="col-12" method="post" action="{{ route('resetPassword') }}">
               @csrf <!-- email -->
                <div class="mb-3 row">
                  <div class="col-12">
                    <input
                      class="form-control form-control-lg"
                      type="email"
                      name="email"
                      required=""
                      placeholder="Username"
                    />
                  </div>
                </div>
                <!-- pwd -->
                <div class="row mt-3">
                  <div class="col-12">
                    <button
                      class="btn d-block w-100 btn-lg btn-light-danger text-danger"
                      type="submit"
                     
                    >
                     Sent
                    </button>
                  </div>
                </div>
            
            </div>
              </form>
          </div>
        </div>
      </div>
      <!-- -------------------------------------------------------------- -->
      <!-- Login box.scss -->
      <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- All Required js -->
    <!-- -------------------------------------------------------------- -->
    <script src="{{asset('public/admin/dist/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('public/admin/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <!-- -------------------------------------------------------------- -->
    <!-- This page plugin js -->
    <!-- -------------------------------------------------------------- -->
    <!--Custom JavaScript -->
    <script src="{{asset('public/admin/dist/js/feather.min.js')}}"></script>
    <script src="{{asset('public/admin/dist/js/custom.min.js')}}"></script>
    <script>
      // ==============================================================
      // Login and Recover Password
      // ==============================================================
      $('#to-recover').on('click', function () {
        $('#loginform').slideUp();
        $('#recoverform').fadeIn();
      });
    </script>
  </body>
</html>
