@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="on">
	<form>
	@include('components.form.row', [
		'input'=>[
			['label'=>'Tanggal', 'type'=>'text', 'name'=>'tgl_1', 'value'=>request()->get('tgl_1'), 'col'=>'col-md-3', 'attributes'=>['autocomplete'=>'off']],
			['label'=>'S/D Tanggal', 'type'=>'text', 'name'=>'tgl_2', 'value'=>request()->get('tgl_2'), 'col'=>'col-md-3', 'attributes'=>['autocomplete'=>'off']],
			['label'=>'Nomor Uji', 'type'=>'text', 'name'=>'no_uji', 'value'=>request()->get('no_uji'), 'col'=>'col-md-3'],
			['label'=>'Nomor Kendaraan', 'type'=>'text', 'name'=>'no_kendaraan', 'value'=>request()->get('no_kendaraan'), 'col'=>'col-md-3'],
			['label'=>'Jenis Uji', 'type'=>'select', 'name'=>'jenis_pengujian', 'value'=>request()->get('jenis_pengujian'), 'options'=>$OptJenisUji, 'col'=>'col-md-3'],
			['label'=>'Pengajuan', 'type'=>'select', 'name'=>'status', 'value'=>request()->get('status'), 'options'=>$OptStsBooking, 'col'=>'col-md-2'],
			['label'=>'Pembayaran', 'type'=>'select', 'name'=>'flag', 'value'=>request()->get('flag'), 'options'=>$OptStsBayar, 'col'=>'col-md-2'],
		]
	])
	</form>
	</x-filter>

	<x-card.with_element>
		@section('header_left', 'Booking Pengujian')
		<x-table class="table table-striped table-booking">
			@section('thead')
				<th></th>
				<th style="width:50px">#</th>
				<th>Tanggal</th>
				<th>No Uji</th>
				<th>Jenis Uji</th>
				<th>Total Tagihan(Rp)</th>
				<th>Persyaratan</th>
				<th>Status</th>
				<th>Sts Bayar</th>

			@endsection
			
			@if(isset($Booking) && count($Booking) > 0)
				@foreach($Booking as $key=>$data)
					<tr data-toggle="collapse" data-target="#booking-{{ $data->booking_id }}" class="accordion-toggle">
						<td>{{ $key+1 }}</td>
						<td><a href="{{ route('read_booking_pengujian', $data->booking_id) }}" class="btn btn-default btn-xs btn-detail"><span class="fas fa-desktop"></span></a></td>
						<td>{{ $data->created_at }}</td>
						<td>{{ $data->no_uji }}</td>
						<td>{{ $data->jenis_pengujian }}</td>
						<td align="right">{{ number_format($data->total_tagihan_rp, 0, ',', '.') }}</td>
						<td>{!! statusPersyaratan($data->booking_id) !!}</td>
						<td>@statusBooking($data->status)</td>
						<td>@statusBayar($data->flag)</td>
					</tr>
					<tr>
						<td class="hiddenRow"></td>
						<td class="hiddenRow" colspan="8">
							<div id="booking-{{ $data->booking_id }}" class="accordian-body collapse">
								<div class="row p-0 m-0 mb-1">
									<div class="col-sm-3"><small class="text-muted">Tanggal Uji</small><br>{{ $data->tgl_uji }}</div>
									<div class="col-sm-3"><small class="text-muted">Lokasi Uji</small><br>{{ $data->lokasi_pengujian }}</div>
									<div class="col-sm-3"><small class="text-muted">Nama Pelanggan</small><br>{{ isset($data->member->nama) ? $data->member->nama : '' }}</div>	
									<div class="col-sm-3"><small class="text-muted">No Kendaraan</small><br>{{ $data->no_kendaraan }}</div>
								</div>
								<div class="row p-0 m-0 mb-1">
									<div class="col-sm-3"><small class="text-muted">ID Billing</small><br>{{ $data->billing_id }}</div>
									<div class="col-sm-3"><small class="text-muted">Retribusi</small><br>Rp{{ number_format($data->retribusi_rp, 0, ',', '.') }}</div>
									<div class="col-sm-3"><small class="text-muted">Denda</small><br>Rp{{ number_format($data->denda_rp, 0, ',', '.') }}</div>
									<div class="col-sm-3"><small class="text-muted">Kartu Hilang</small><br>Rp{{ number_format($data->kartu_hilang_rp, 0, ',', '.') }}</div>	
								</div>
								<div class="row p-0 m-0 mb-1">
									<div class="col-sm-3"><small class="text-muted">Nama Pemilik</small><br>{{ $data->nama }}</div>
									<div class="col-sm-9"><small class="text-muted">Alamat Pemilik</small><br>{{ $data->alamat }}</div>
								</div>
							</div>
						</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td class="text-center" colspan="9">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$Booking])
		
	</x-card.with_element>
@endsection
@push('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
	.hiddenRow {
		padding: 0 !important;
		background:#f7f7f7;
	}
</style>
@endpush
@push('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
	$(function() {
		$("input[name='tgl_1'],input[name='tgl_2']").datepicker({
			format: 'yyyy-mm-dd',
			//startDate: new Date(),
		});
	});
</script>
@endpush