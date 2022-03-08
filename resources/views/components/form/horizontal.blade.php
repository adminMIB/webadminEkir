@foreach($input as $inp)
	<div class="form-group row">
		<label class="col-form-label col-sm-2 text-sm-right text-muted">{{ $inp['label'] }}</label>
		<div class="col-sm-10">
		@switch($inp['type'])
			@case ('select')
				@include('components.form.inputs.select', $inp)
			@break
			@case ('textarea')
				@include('components.form.inputs.textarea', $inp)
			@break
			@case ('label')
				@include('components.form.inputs.label', $inp)
			@break
			@default
			
				@include('components.form.inputs.text', $inp)
			@break
		@endswitch
		</div>
	</div>
@endforeach
<br />
<div class="form-group row">
	<div class="col-sm-10 ml-sm-auto">
		<a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-warning"><i class="ion ion-md-arrow-round-back"></i> Kembali</a>&nbsp;
		<button type="submit" class="btn btn-success"><i class="ion ion-ios-save"></i> Simpan</button>
	</div>
</div>