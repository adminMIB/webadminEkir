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
		</div>

		<div class="col-sm-5">
			<small class="text-muted">Pelanggan</small>
			<h6 class="mb-2">{{ isset($Data->member->nama) ? $Data->member->nama : '-' }}</h6>

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
			
			<small class="text-muted">Retribusi</small>
			<h6 class="mb-2 currency"><span>{{ number_format($Data->retribusi_rp, 0, ',', '.') }}</span></h6>

			<small class="text-muted">Kartu Hilang</small>
			<h6 class="mb-2 currency"><span>{{ number_format($Data->kartu_hilang_rp, 0, ',', '.') }}</span></h6>

			<small class="text-muted">Sanksi</small>
			<h6 class="mb-2 currency"><span>{{ number_format($Data->denda_rp, 0, ',', '.') }}</span></h6>

			<small class="text-muted label-total">Total Tagihan</small>
			<h6 class="mb-1 currency total"><span>{{ number_format($Data->total_tagihan_rp, 0, ',', '.') }}</span></h6>

		</div>

		
	</div>
	
</x-card.with_element>

<div class="row card-action-wrapper">
	<div class="col">
		<a href="{{ route('booking_pengujian') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a> 
		<button class="btn btn-info mb-3" data-toggle="modal" data-target="#modals-kirim-pesan"><i class="fas fa-comments"></i> Kirim Pesan</button>
	</div>
</div>

<x-modal.epkb.kirim-pesan :data="$Data" />

@endsection

@push('header')
<link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/growl/growl.css') }}">

<style>
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
<script src="{{ asset('assets/theme/vendor/libs/growl/growl.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
	$(function() {
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