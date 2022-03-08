          <!-- Layout footer -->
          <nav class="layout-footer footer bg-footer-theme">
            <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
              <div class="pt-3">
                <span class="footer-text font-weight-bolder">Copyright</span> &copy;2021
              </div>

            </div>
          </nav>
          <!-- / Layout footer -->

        </div>
        <!-- Layout content -->

      </div>
      <!-- / Layout container -->

    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-sidenav-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!--loading spinner-->
  <div class="page-loader-wrapper" style="display:none">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>mohon menunggu...</p>
    </div>
</div>

  <!-- Core scripts -->
  <script src="{{  asset('assets/theme/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{  asset('assets/theme/vendor/js/bootstrap.js') }}"></script>
  <script src="{{  asset('assets/theme/vendor/js/sidenav.js') }}"></script>

  <!-- Libs -->
  <script src="{{  asset('assets/theme/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/theme/vendor/libs/select2/select2.js') }}"></script>

  <!-- Demo -->
  <script src="{{  asset('assets/theme/js/main.js') }}"></script>
  @yield('register_script_footer')
  @stack('footer')
  <script src="{{  asset('js/my.js') }}"></script>