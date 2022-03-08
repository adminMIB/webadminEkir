<form>
	<div class="card mb-4">
		<h6 class="card-header with-elements">
			<div class="card-header-title">Filter</div>
			<div class="card-header-elements ml-auto">
			<button type="button" class="btn btn-info btn-sm md-btn-flat btn-reset-filter">Reset</button>
			</div>
		</h6>
		<div class="card-body">
			{{ $slot }}
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary"><i class="ion ion-md-sync"></i> Filter</button>

			@if(isset($attributes['btn-export-xls']) && strtolower($attributes['btn-export-xls']) === "off")
			@else
			<button type="button" class="btn btn-success btn-export-xls"><i class="far fa-file-excel"></i> Export Excel</button>
			@endif
		</div>
	</div>
</form>