  <title>{{ str_replace('_', ' ', config("app.name")) }} :: {{ $title }} - {{ $sub_title }}</title>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('assets/img/e-pkb-dishub-kota-bandung.ico') }}" type="image/png">

  <link href="https:/fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">  <!-- Icon fonts -->
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/fonts/ionicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/fonts/linearicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/fonts/open-iconic.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/fonts/pe-icon-7-stroke.css') }}">

  <!-- Core stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/bootstrap.css') }}" class="theme-settings-bootstrap-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/appwork.css') }}" class="theme-settings-appwork-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/theme-corporate.css') }}" class="theme-settings-theme-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/colors.css') }}" class="theme-settings-colors-css">
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/css/rtl/uikit.css') }}">
  
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/select2/select2.css') }}">
  
  <link rel="stylesheet" href="{{ asset('assets/theme/css/main.css') }}">

  <script src="{{ asset('assets/theme/vendor/js/material-ripple.js') }}"></script>
  <script src="{{ asset('assets/theme/vendor/js/layout-helpers.js') }}"></script>

  <!-- Theme settings -->
  <!-- This file MUST be included after core stylesheets and layout-helpers.js in the <head> section -->
  <script src="{{ asset('assets/theme/vendor/js/theme-settings.js') }}"></script>
  <script>
    window.themeSettings = new ThemeSettings({
      cssPath: "{{ asset('assets/theme/vendor/css/rtl') }}/",
      themesPath: "{{ asset('assets/theme/vendor/css/rtl') }}/"
    });
  </script>

  <!-- Core scripts -->
  <script src="{{ asset('assets/theme/vendor/js/pace.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.3_2_1.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('js/my.js') }}">

  <!-- Libs -->
  <link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/my.css') }}">

@yield('register_script_header')
@stack('header')