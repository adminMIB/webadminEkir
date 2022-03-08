@extends("layouts.".config('app.theme').".master")
@section('content')
<x-card.with_element>
	@section('header_left', $sub_title)

	<div class="row">
		<div class="col-sm-4">
			<small class="text-muted">No Uji</small> <span>@statusBooking($Data->status)</span>
			<h6 class="mb-2">{{ $Data->no_uji }}</h6>
			
			<small class="text-muted">Tanggal</small>
			<h6 class="mb-2">{{ $Data->created_at }}</h6>

			<small class="text-muted">Jenis Uji</small>
			<h6 class="mb-2">{{ $Data->jenis_pengujian }}</h6>

			<small class="text-muted">Tanggal Uji</small>
			<h6 class="mb-2">{{ $Data->tgl_uji }}</h6>

			<small class="text-muted">Lokasi Uji</small>
			<h6 class="mb-2">{{ $Data->lokasi_pengujian }}</h6>

			<small class="text-muted">Lokasi Daftar</small>
			<h6 class="mb-2">{{ isset($Regnew->data->LokDaftar) ?  $Regnew->data->LokDaftar : '' }}</h6>

		</div>

		<div class="col-sm-5">
			<small class="text-muted">Pelanggan</small>
			<h6 class="mb-2">{{ isset($data->member->nama) ? $data->member->nama : '-' }}</h6>

			<small class="text-muted">Nomor Kendaraan</small>
			<h6 class="mb-2">{{ ($Data->no_kendaraan) ? $Data->no_kendaraan : '-' }}</h6>

			<small class="text-muted">Nama Pemilik</small>
			<h6 class="mb-2">{{ $Data->nama }}</h6>

			<small class="text-muted">Alamat Pemilik</small>
			<h6 class="mb-2">{{ $Data->alamat }}</h6>

			@if($Data->status_keterangan)
			<small class="text-muted">Keterangan</small>
			<h6 class="mb-2 text-danger">{{ $Data->status_keterangan }}</h6>
			@endif

		</div>

		<div class="col-sm-3">
			<small class="text-muted">ID Billing</small> <span class="mb-2">@statusBayar($Data->flag)</span>
			<h6 class="mb-2">{{ $Data->billing_id }}</h6>
			
			<small class="text-muted">Keterlambatan</small>
			<h6 class="mb-2">{{ isset($Regnew->data->Keterlambatan) ? number_format($Regnew->data->Keterlambatan, 0, ',', '.') : 0 }} Bulan</h6>

			<small class="text-muted">Retribusi</small>
			<h6 class="mb-2 currency"><span>{{ isset($Regnew->data->Retribusi) ? number_format($Regnew->data->Retribusi, 0, ',', '.') : 0 }}</span></h6>

			<small class="text-muted">Kartu Hilang</small>
			<h6 class="mb-2 currency"><span>{{ isset($Regnew->data->KartuHilang) ? number_format($Regnew->data->KartuHilang, 0, ',', '.') : 0 }}</span></h6>

			<small class="text-muted">Sanksi</small>
			<h6 class="mb-2 currency"><span>{{ isset($Regnew->data->Sanksi) ? number_format($Regnew->data->Sanksi, 0, ',', '.') : 0 }}</span></h6>

			<small class="text-muted label-total">Total Tagihan</small>
			<h6 class="mb-1 currency total"><span>{{ isset($Regnew->data->JumlahTagihan) ? number_format($Regnew->data->JumlahTagihan, 0, ',', '.') : 0 }}</span></h6>

		</div>

		
	</div>
	
</x-card.with_element>

<div class="card mb-4 card-uji-lalu">
	<h6 class="card-header with-elements">
		<div class="card-header-title">Info Uji Lalu</div>
		<div class="card-header-elements ml-auto">
			<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				<i class="fa" aria-hidden="true"></i>
			</button>
		</div>
	</h6>
	
	<div id="collapseOne" class="collapse" aria-labelledby="headingOne">
	<div class="card-body">
		@if($UjiLalu->rc=='0000'&& isset($UjiLalu->data->TglDaftar))
		<div class="row">
			<div class="col-sm-4">
				<small class="text-muted">Tanggal Daftar</small>
				<h6 class="mb-2">@dateHumans($UjiLalu->data->TglDaftar)</h6>
				
				<small class="text-muted">Lokasi Daftar</small>
				<h6 class="mb-2">{{ $UjiLalu->data->LokDaftar }}</h6>

				<small class="text-muted">No Uji</small>
				<h6 class="mb-2">{{ $UjiLalu->data->NoUji }}</h6>

				<small class="text-muted">Jenis Uji</small>
				<h6 class="mb-2">{{ $UjiLalu->data->JenisPengujian }}</h6>

				<small class="text-muted">Habis Uji Lalu</small>
				<h6 class="mb-2">@dateHumans($UjiLalu->data->HabisUjiLalu)</h6>
			</div>

			<div class="col-sm-4">
				<small class="text-muted">No Kendaraann</small>
				<h6 class="mb-2">{{ $UjiLalu->data->NoKendaraan }}</h6>
				
				<small class="text-muted">Nama Pemilik</small>
				<h6 class="mb-2">{{ $UjiLalu->data->Pemilik }}</h6>

				<small class="text-muted">Alamat Pemilik</small>
				<h6 class="mb-2">{{ $UjiLalu->data->Alamat }}</h6>

				<small class="text-muted">Jenis Kendaraan</small>
				<h6 class="mb-2">{{ $UjiLalu->data->Jenis1 }} {{ $UjiLalu->data->Jenis }} ({{ $UjiLalu->data->Status }})</h6>

			</div>

			<div class="col-sm-4">
				<small class="text-muted">Retribusi</small>
				<h6 class="mb-2 currency"><span>{{ number_format($UjiLalu->data->Retribusi, 0, ',', '.') }}</h6>
				
				<small class="text-muted">Keterlambatan</small>
				<h6 class="mb-2 currency"><span>{{ number_format($UjiLalu->data->Keterlambatan, 0, ',', '.') }}</h6>

				<small class="text-muted">Kartu Hilang</small>
				<h6 class="mb-2 currency"><span>{{ number_format($UjiLalu->data->KartuHilang, 0, ',', '.') }}</h6>

				<small class="text-muted">Sanksi</small>
				<h6 class="mb-2 currency"><span>{{ number_format($UjiLalu->data->Sanksi, 0, ',', '.') }}</h6>
				

			</div>

		</div>

		@else
		<p class="alert alert-warning">{{ isset($UjiLalu->data->rcm) ? $UjiLalu->data->rcm : $UjiLalu->rc_message }}</p>
		@endif
	</div>
	</div>
	
</div>

<div class="card mb-4">
	<h6 class="card-header with-elements">
		<div class="card-header-title">Persyaratan - {{ $Data->jenis_pengujian }} {!! statusPersyaratan($Data->booking_id) !!}</div>
	</h6>
	<div class="card-body">
		@if(isset($Persyaratan) && count($Persyaratan) > 0)
		<ul class="p-0 m-0" style="list-style:none">
			@foreach($Persyaratan as $p)
			<li class="mb-2" data-toggle="collapse" data-target="#persyaratan-{{ $p->persyaratan_id }}" class="accordion-toggle">
				@if($p->image_status=="1")
				<i class="ion ion-md-checkmark text-success"></i>
				@else
				<i class="fas fa-exclamation-triangle text-warning"></i>
				@endif  
				<a href="javascript:void(0)">{{ $p->persyaratan_name }}</a>
			</li>
			<li id="persyaratan-{{ $p->persyaratan_id }}" class="accordian-body collapse">
				<div class="pt-2 pb-3">
					<img src="{{ $p->image_url }}" style="width:100%" />
				</div>
			</li>
			@endforeach
		</ul>
		@endif
	</div>
</div>

<div class="row card-action-wrapper">
	<div class="col">
		<a href="{{ route('booking_pengujian') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a> 
		@if($Data->status == "0" && Permissions::can('create-booking-pengujian') && isset($UjiLalu->data->TglDaftar))
		<button class="btn btn-success mb-3" data-toggle="modal" data-target="#modals-submit-pengajuan"><i class="fas fa-check"></i> Terima Pengajuan</button>
		<button class="btn btn-danger mb-3" data-toggle="modal" data-target="#modals-pesan-penolakan"><i class="fas fa-ban"></i> Tolak Pengajuan</button> 
		<button class="btn btn-info mb-3" data-toggle="modal" data-target="#modals-kirim-pesan"><i class="fas fa-comments"></i> Kirim Pesan</button>
		@endif
	</div>
</div>

@if($Data->status == "0" && Permissions::can('create-booking-pengujian') && isset($UjiLalu->data->TglDaftar))
<x-modal.epkb.submit-pengajuan :data="$Data" :kendaraan="$Kendaraan" />
<x-modal.epkb.pesan-penolakan :data="$Data" />
<x-modal.epkb.kirim-pesan :data="$Data" />
@endif

@endsection

@push('header')
<link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/growl/growl.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<style>
	.card-uji-lalu [data-toggle="collapse"] .fa:before {  
		content: "\f077";
	}

	.card-uji-lalu [data-toggle="collapse"].collapsed .fa:before {
		content: "\f078";
	}

	.card-action-wrapper {
		position: fixed;
		right: 30px;
		bottom: 0px;
	}
	
	.currency span{
		text-align:right;
		display:block;
		width:100%;
		margin-top:-16px;
	}
	.currency:before{
		content:"Rp"
	}
	.currency.total{
		font-size: 18px;
    	font-weight: bold;
    	color: rgb(2,188,119);
	}
	.label-total{
		border-bottom: solid 1pt #ccc;
		margin-bottom: 4px;
		display: block;
		margin-top: 10px;
	}
	.swal2-container {
		z-index:9999999 !important;
	}
</style>
@endpush
@push('footer')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/theme/vendor/libs/growl/growl.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
	$(function() {
		/*
		$(".modal-submit .datepicker").datepicker({
			format: 'dd-mm-yyyy',
			startDate: new Date(),
		}).datepicker("setDate",'now');
		*/
		
		$(".modal-submit .datepicker").datepicker({
			format: 'dd-mm-yyyy',
			startDate: new Date(),
		});

		//Submit Pengajuan
		$(".btn-submit-pengajuan").on("click", function(e){
			e.preventDefault();
			var _this =this;
			Swal.fire({
				title: 'Sumbit Pengujian?',
				text: "{{ \Options::getOption('adm.message.confirm_submit_pengujian') }}",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#02BC77',
				cancelButtonColor: '#8897AA',
				confirmButtonText: 'Ya, Submit Pengujian!',
				cancelButtonText:'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$(_this).closest("form").submit();
				}
			})
		});

		//Tolak Pengajuan
		$(".btn-tolak-pengajuan").on("click", function(e){
			e.preventDefault();
			var _this =this;
			var pesan =$(this).closest("form").find("textarea[name='pesan']").val().trim();
			if(!pesan){
				Swal.fire({
					icon: 'error',
					title: 'ERROR',
					text: 'Silahkan menuliskan pesan!'
				});
			}else{
				Swal.fire({
					title: 'Tolak Pengujian?',
					text: "{{ \Options::getOption('adm.message.confirm_tolak_pengajuan') }}",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#D9534F',
					cancelButtonColor: '#8897AA',
					confirmButtonText: 'Ya, Tolak Pengajuan!',
					cancelButtonText:'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						$(_this).closest("form").submit();
					}
				});
			}
		});

		//Kirim Pesan Notifikasi
		$(".btn-kirim-notifikasi").on("click", function(e){
			e.preventDefault();
			var $form = $(this).closest("form");
			var url = $form.attr("action");
			var booking_id = $form.find("input[name='booking_id']").val().trim();
			var pesan = $form.find("textarea[name='pesan']").val().trim();
			if(!pesan){
				Swal.fire({
					icon: 'error',
					title: 'ERROR',
					text: 'Silahkan menuliskan pesan!'
				});
			}else{
				App.request(url, {booking_id:booking_id, pesan: pesan}, true, function($resp){
					try{
						if($resp.rc == "0000"){
							$.growl.notice({
								message:  $resp.rc_message,
								location: 'tr'//isRtl ? 'tl' : 'tr'
							});
							$form.find(".close").trigger("click");
							$form.find("textarea[name='pesan']").val("");
						}else{
							$.growl.error({
								message:  $resp.rc_message,
								location: 'tr'//isRtl ? 'tl' : 'tr'
							});
						}
					}catch(err){
						alert(err);
					}					
				});
			}
		});
	});
</script>
@endpush