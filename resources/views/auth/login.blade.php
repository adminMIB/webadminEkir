<!DOCTYPE html>

<html lang="en" class="default-style">

<head>
  <title>{{ str_replace('_', ' ', config('app.name')) }} - Login</title>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  
  <!--====== Favicon Icon ======-->
  <link rel="shortcut icon" href="{{ asset('assets/img/e-pkb-dishub-kota-bandung.ico') }}" type="image/png">

  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

  <!-- Core stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/bootstrap.css') }}" class="theme-settings-bootstrap-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/appwork.css') }}" class="theme-settings-appwork-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/theme-corporate.css') }}" class="theme-settings-theme-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/colors.css') }}" class="theme-settings-colors-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/uikit.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/theme/css/main.css') }}">

  <script src="{{ asset('assets/theme/vendor/js/material-ripple.js') }}"></script>
  <script src="{{ asset('assets/theme/vendor/js/layout-helpers.js') }}"></script>


  <!-- Core scripts -->
  <script src="{{ asset('assets/theme/vendor/js/pace.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.3_2_1.min.js') }}"></script>

  <!-- Libs -->
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <!-- Page -->
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/pages/authentication.css') }}">

  <style>
    .show-hide-wrapper {
      top:32px !important;
      right: 10px !important;
    }
  </style>
</head>

<body>
  <div class="page-loader">
    <div class="bg-primary"></div>
  </div>

  <!-- Content -->

  <div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4">
    <div class="ui-bg-overlay bg-dark opacity-25"></div>

    <div class="authentication-inner py-4">

      <div class="card" style="background-color:rgb(0,0,135);color:#fff">
        <div class="p-3 p-sm-4">
          <!-- Logo -->
          <img class="img-fluid p-3" src="{{ asset('assets/img/logo.png') }}" alt="{{ str_replace('_', ' ', config('app.name')) }}" />
          <!-- / Logo -->
          <br><br><br>

          @if(Session::has('error'))
          <div class="alert alert-warning">
            {!! Session::get('error') !!}
            {{ Session::forget('error') }}
          </div>
          @endif

          <!-- Form -->
          <form method="post" action="{{ route('login') }}">
			    @csrf
            <div class="form-group">
              <label class="form-label">Email</label>
              <input required autofocus name="username" type="email" class="form-control">
            </div>
            <div class="form-group" style="position:relative">
              <label class="form-label d-flex justify-content-between align-items-end">
                <div>Password</div>
              </label>
              <input required name="password" type="password" class="form-control">
            </div>
            <div class="d-flex justify-content-between align-items-center m-0 pt-3">
              <button type="submit" class="btn btn-primary form-control">Login</button>
            </div>
          </form>
          <!-- / Form -->

        </div>
        <div class="card-footer py-3 px-4 px-sm-5" style="background-color:rgb(1,0,84)">
          <div class="text-center text-muted">
            Copyright &copy;2021
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- / Content -->

  <!-- Core scripts -->
  <script src="{{ asset('assets/theme/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/theme/vendor/js/bootstrap.js') }}"></script>
  
  <!-- Demo -->
  <script src="{{ asset('assets/theme/js/main.js') }}"></script>
  <script src="{{ asset('js/my.js') }}"></script>

</body>

</html>