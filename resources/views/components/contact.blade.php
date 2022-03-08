<div class="card mb-4">
    <div class="card-body">
    
    @if(isset($canUpdate) && $canUpdate)
    <div class="contacts-dropdown btn-group">
        <button type="button" class="btn btn-sm btn-default icon-btn borderless btn-round md-btn-flat dropdown-toggle hide-arrow" data-toggle="dropdown">
        <i class="ion ion-ios-more"></i>
        </button>
        <div class="contacts-dropdown-menu dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{ $routeUbahData }}"><i class="fas fa-pencil-alt"></i> Ubah Data</a>
        <a class="dropdown-item" href="{{ $routeUbahEmail }}"><i class="ion ion-ios-mail"></i> Ganti Email</a>
        <a class="dropdown-item" href="{{ $routeUbahNoTelpon }}"><i class="fas fa-phone"></i> Ganti No. Telpon</a>
        <a class="dropdown-item" href="{{ $routeResetPassword }}"><i class="ion ion-ios-key"></i> Reset Password</a>
        </div>
    </div>
    @endif

    <div class="contact-content">
        @if($data->user_role == 4)
        <img src="{{ config('app.image_url') }}{{ $data->user_url ?: 'avatars/default-mitra.jpg' }}" class="contact-content-img rounded-circle" alt="">
        @elseif($data->user_role == 5)
        <img src="{{ config('app.image_url') }}{{ $data->user_url ?: 'avatars/default-canvasser.jpg' }}" class="contact-content-img rounded-circle" alt="">
        @else
        <img src="{{ config('app.image_url') }}{{ $data->user_url ?: 'avatars/no-image-profile-200-200.jpg' }}" class="contact-content-img rounded-circle" alt="">
        @endif
        <div class="contact-content-about">
        <h5 class="contact-content-name mb-1 mt-2">{{ $data->display_name }}</h5>
        <div class="contact-content-user text-muted small mb-2">{{ $data->user_code }}</div>
        <div class="contact-content-user text-muted mb-2">@status($data->user_status)</div>
        <div class="text text-success">
            <i class="fas fa-store-alt"></i> {{ isset($data->TokoKoperasi->toko_name)?$data->TokoKoperasi->toko_name: "(belum ada toko)" }}
        </div>        
        </div>
    </div>

    </div>
</div>