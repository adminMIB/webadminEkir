<!-- Layout sidenav -->
      <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-dark">

        <!-- Brand demo (see assets/css/demo/demo.css) -->
        <div class="app-brand demo">
          <a href="{{ url('/') }}" class="app-brand-text demo sidenav-text font-weight-normal ml-2">
          E-PKB DISHUB<br><small class="text-muted">Kota Bandung</small>
          </a>
          <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
          </a>
        </div>

        <div class="sidenav-divider mt-0"></div>

        <!-- Links -->
        <ul class="sidenav-inner py-1">
			  {{ \App\Models\Platform\Sidebar::render() }}
        </ul>
      </div>
      <!-- / Layout sidenav -->