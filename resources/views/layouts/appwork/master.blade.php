<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.appwork.header')
</head>
<body>
<div class="page-loader">
    <div class="bg-primary"></div>
  </div>

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-2">
    <div class="layout-inner">

      @include('layouts.appwork.sidenav')

      <!-- Layout container -->
      <div class="layout-container">
        
		@include('layouts.appwork.navbar')

        <!-- Layout content -->
        <div class="layout-content">

          <!-- Content -->
          <div class="container-fluid flex-grow-1 container-p-y">

            <div class="py-3 mb-0">
              <h4 class="font-weight-bold mb-2">
              <span class="text-muted font-weight-light"> {{ $title }} /</span> {{ $sub_title }}
              </h4>
              
              @if (trim($__env->yieldContent('page_description')))
                    <div class="text-muted text-big font-weight-light">
                      @yield('page_description')
                    </div>
              @endif
			  
			  
            </div>
			
			<!-- message content -->
			@if (count($errors) > 0 || Session::has('success'))
			<div class="alert {{(count($errors)>0?'alert-danger':'alert-success')}} text-big p-4 mb-4">
				@if(count($errors)>0)
					<strong>ERROR!</strong> Oops something went wrong, please check again.
				@else
					{!! Session::get('success') !!}
				@endif
			</div>
			@endif
      @if (Session::has('error'))
			<div class="alert alert-warning text-big p-4 mb-4">
      ERROR. {!! Session::get('error') !!}
			</div>
			@endif
			
			@yield('content')


          </div>
          <!-- / Content -->
		  
		@include('layouts.appwork.footer')
  </body>
</html>
