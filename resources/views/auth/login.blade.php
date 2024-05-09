<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="Technician Management, System Management, {{$siteSettings->business_name ?? null}}" />
    <meta name="description" content="{{$siteSettings->business_name ?? null}} - Technician management and system management."    />
    <meta name="robots" content="noindex,nofollow" />
    <title>{{$siteSettings->business_name ?? null}} - Web Application to manage technicians</title>
    <link rel="canonical" href="{{ route('home') }}" />
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
      
      <!-- Login box.scss -->
      <!-- -------------------------------------------------------------- -->
      <div
        class="auth-wrapper d-flex no-block justify-content-center align-items-center"
        style="background: url(public/admin/assets/images/big/auth-bg.jpg) no-repeat center center"
      >
        <div class="auth-box">
          <div id="loginform">
            <div class="logo">
              <span class="db"><img src="{{asset('public/admin/assets/images/dispatch-logo.png')}}" alt="logo" /></span>
              <h5 class="font-medium mb-2 mt-4">SIGN IN TO DISPATCHANNEL PORTAL</h5>
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
                            <a id="to-recover" href="javascript:void(0)" class="text-dark ms-auto d-flex align-items-center"  href="{{ route('password.request') }}"><i data-feather="lock" class="feather-sm me-1"></i>Forgot Password?</a>
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
                   <div class="mb-3 mb-0 mt-2">
                    <div class="col-sm-12 text-center">
                      Don't have an account?
                      <a href="mailto: {{$siteSettings->email ?? null}}" class="text-info ms-1"
                        ><b>Contact Support</b></a
                      >
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div id="recoverform">
            <div class="logo">
              <span class="db"><img src="{{asset('public/admin/assets/images/dispatch-logo.png')}}" alt="logo" /></span>
              <h5 class="font-medium mt-4 mb-3 uppercase card-title">Recover Password</h5>
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
                    <button class="btn d-block w-100 btn-lg btn-light-danger text-danger" type="submit" >Send</button>
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
