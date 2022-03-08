@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', $title)
		
		<form method="post" action="{{ isset($Pelayanan->id) ? route('update_pelayanan', ['id'=>$Pelayanan->id]) : route('store_pelayanan') }}">
			@csrf		
			<div class="form-group row">
				<label class="col-form-label col-sm-2 text-sm-right text-muted">Pelayanan</label>
				<div class="col-sm-10">						
					<input type="text" value="{{ isset($Pelayanan->id) ? $Pelayanan->subject : '' }}" name="subject" class="form-control " placeholder="Pelayanan" required="required">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-sm-2 text-sm-right text-muted">Status</label>
				<div class="col-sm-10">						
					<select class="form-control" name="status" required>
						<option value="">--Pilih--</option>
						@foreach(config('flagging.status_pelayanan') as $key=>$value)
						<option {{ isset($Pelayanan->id) && $Pelayanan->status==$key ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-sm-2 text-sm-right text-muted">Urutan Menu</label>
				<div class="col-sm-10">						
					<input type="number" value="{{ isset($Pelayanan->id) ? $Pelayanan->menu_order : '1' }}" name="menu_order" class="form-control " required="required">
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-form-label col-sm-2 text-sm-right text-muted">Persyaratan</label>
				<div class="col-sm-10 col-target">
					@if(isset($Persyaratan) && count($Persyaratan) > 0)
					@foreach($Persyaratan as $s)
					<div class="row mb-2">
						<div class="col-10">					
							<input type="text" value="{{ $s->name }}" placeholder="Persyaratan" name="persyaratan[]" class="form-control">
							<input type="hidden" value="{{ $s->id }}" name="persyaratan_id[]">
						</div>
						<div class="col-2">					
							<button class="btn btn-outline-warning btn-remove borderless"><i class="fas fa-trash"></i></button>
						</div>
					</div>
					@endforeach
					@endif
					<div class="row mb-2">
						<div class="col-10">					
							<input type="text" value="" placeholder="Persyaratan" name="persyaratan[]" class="form-control">
							<input type="hidden" value="" name="persyaratan_id[]">
						</div>
						<div class="col-2">					
							<button class="btn btn-outline-primary btn-add borderless"><i class="fas fa-plus-circle"></i></button>
						</div>
					</div>
				</div>
			</div>

			<br />
			<div class="form-group row">
				<div class="col-sm-10 ml-sm-auto">
					<a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-warning"><i class="ion ion-md-arrow-round-back"></i> Kembali</a>&nbsp;
					<button type="submit" class="btn btn-success"><i class="ion ion-ios-save"></i> Simpan</button>
				</div>
			</div>
			

		</form>	
	</x-card.with_element>
	
@endsection
@section('register_script_header')
	<!-- Libs -->

@endsection  
@section('register_script_footer')
	<script>
		// Shorthand for $( document ).ready()
		$(function() {
			const sHTML = `<div class="row">
						<div class="col-10">					
							<input type="text" value="" placeholder="Persyaratan" name="persyaratan[]" class="form-control">
							<input type="hidden" value="" name="persyaratan_id[]">
						</div>
						<div class="col-2">					
							<button class="btn btn-outline-warning btn-remove borderless"><i class="fas fa-trash"></i></button>
						</div>
					</div>`;
			$(".btn.btn-add").on("click", function(e){
				e.preventDefault();
				$(sHTML).appendTo( $(".col-target") );

				removeHanlder();
			});
			
			removeHanlder();
			function removeHanlder(){
				$(".btn-remove").unbind("click").bind("click", function(e){
					e.preventDefault();
					var val =$(this).closest(".row").find("input[type='text']").val().trim();
					if(!val){
						$(this).closest(".row").remove();
					}else if(confirm("Anda yakin akan menghapus?")){
						$(this).closest(".row").remove();
					}
				});
			}
		});
	</script>
@endsection