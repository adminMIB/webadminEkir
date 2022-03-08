@extends("layouts.".config('app.theme').".master")
@section('content')

<form class="card card-body">
<div class="row">
	<div class="col-md-3">
		<input type="text" name="tgl_1" value="{{ request()->get('tgl_1') }}" autocomplete="off" class="form-control" placeholder="Tanggal" />
	</div>
	<div class="col-md-3">
		<input type="text" name="tgl_2" value="{{ request()->get('tgl_2') }}" autocomplete="off" class="form-control" placeholder="S/D Tanggal" />
	</div>
	<div class="col-md-6 text-md-right">
	<button class="btn btn-warning btn-reset-filter">Reset</button> <button class="btn btn-info" type="submit">Filter</button>
	</div>
</div>
</form>

<div class="row mt-4">
	@foreach($SummaryCount as $summary)
	<div class="col-sm-6 col-xl-3">
		<div class="card mb-4">
			<div class="card-body">
			<div class="d-flex align-items-center">
				<div class="{{ $summary->icon }} display-4 {{ $summary->text }}"></div>
				<div class="ml-3">
				<div class="text-muted small">{{ $summary->name }}</div>
				<div class="text-large">
					<span data-count="{{ $summary->counter }}" class="animated-count">0</span>
				</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	@endforeach
	
</div><!-- end .row-->

<div class="row mt-3">  
	<div class="col-xl-9">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fas fa-chart-area mr-1"></i>
				Booking Pengujian Harian Bulan {{ toIndonesianMonth( intval(date("m", time())) ) }} {{ date("Y", time()) }}
			</div>
			<div class="card-body"><canvas id="bookingHarian" width="100%" height="30"></canvas></div>
		</div>
	</div>

	<div class="col-xl-3 col-md-4 col-sm-4">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fas fa-chart-area mr-1"></i>
				Pengujian Tahun {{ date("Y", time()) }}
			</div>
			<div class="card-body"><canvas id="jenisUji" width="100%" height="115"></canvas></div>
		</div>
	</div>
	
</div>

<div class="row mt-3">  
	<div class="col-xl-9">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fas fa-chart-area mr-1"></i>
				Booking Pengujian Bulanan Tahun {{ date("Y", time()) }}
			</div>
			<div class="card-body"><canvas id="bookingBulanan" width="100%" height="30"></canvas></div>
		</div>
	</div>

	<div class="col-xl-3 col-md-4 col-sm-4">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fas fa-chart-area mr-1"></i>
				Status Pengajuan {{ date("Y", time()) }}
			</div>
			<div class="card-body"><canvas id="statusPengajuan" width="100%" height="115"></canvas></div>
		</div>
	</div>

</div>
	


@endsection
@push('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush
@push('footer')
<script src="{{ asset('vendor/jquery/jquery.number.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
	$(function() {

		$("input[name='tgl_1'],input[name='tgl_2']").datepicker({
			format: 'yyyy-mm-dd',
			//startDate: new Date(),
		});
	
		animatedCounter();
		/*
		* Animated Counter
		*/
		function animatedCounter(){
			//var $this = $("span.animated-count");
			//$("span.animated-count").stop().fadeOut("slow").fadeIn("slow");
			$("span.animated-count").stop().fadeIn("slow");
			
			$('span.animated-count').each(function() {
				var $this = $(this),
					countTo = $this.attr('data-count');
				$({
					countNum: $this.text()
				}).animate({
						countNum: countTo
					},
					{
					duration: 800,
					easing: 'swing',
					step: function() {
						$this.text(Math.floor(this.countNum));
					},
					complete: function() {
						$this.text($.number( this.countNum, 0 ).replaceAll(",", "."));
						//alert('finished');
					}
				});
			});
		}
	});
</script>
{!! $sGrafikHarian !!}
{!! $sGrafikBulanan !!}
{!! $sGrafikJenisUji !!}
{!! $sGrafikStatusPengajuan !!}
@endpush