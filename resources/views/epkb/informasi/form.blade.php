@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', 'Informasi dan Berita')
		
		<form enctype='multipart/form-data' method="post" action="{{ isset($Informasi->id) ? route('update_informasi', ['id'=>$Informasi->id]) : route('store_informasi') }}">
		@csrf
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Judul', 'type'=>'text', 'name'=>'subject', 'value'=>isset($Informasi->id)?$Informasi->subject:'', 'attributes'=>['required'=>'required']],
				['label'=>'Status', 'type'=>'select', 'name'=>'status', 'value'=>isset($Informasi->status)?$Informasi->status:'', 'options'=>config('flagging.status_global'), 'attributes'=>['required'=>'required']],
				['label'=>' ', 'type'=>'file', 'name'=>'profil', 'value'=>'', 'attributes'=>['accept'=>'image/*', 'data-img'=>(isset($Informasi->id)&&$Informasi->img_url?$Informasi->img_url:asset('assets/img/informasi-berita-epkb-default-image.png'))]],
				['label'=>'Konten', 'type'=>'textarea', 'name'=>'content', 'value'=>isset($Informasi->content)?$Informasi->content:'', 'attributes'=>['required'=>'required', 'id'=>'bs-markdown', 'rows'=>'10']],
			]
		])
		</form>	
	</x-card.with_element>
	
@endsection
@section('register_script_header')
	<!-- Libs -->
	<link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/bootstrap-markdown/bootstrap-markdown.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/quill/typography.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/theme/vendor/libs/quill/editor.css') }}">
@endsection  
@section('register_script_footer')
	<!-- Libs -->
	<script src="{{ asset('assets/theme/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  	<script src="{{ asset('assets/theme/vendor/libs/markdown/markdown.js') }}"></script>
  	<script src="{{ asset('assets/theme/vendor/libs/bootstrap-markdown/bootstrap-markdown.js') }}"></script>
	<script src="{{ asset('assets/theme/js/forms_editors.js') }}"></script>
	<script>
		// Shorthand for $( document ).ready()
		$(function() {
			var $imgSrc =$("[data-img]");
			if($imgSrc.length > 0){
				var $target = $imgSrc.closest("div");
				var sProfilImg = `<img src="`+$imgSrc.attr("data-img")+`" class="mb-2 rounded" style="width:50%" />`;
				$(sProfilImg).prependTo( $target );
				//$target.addClass("text-center");
				$('<small class="text-muted">Ukuran gambar terbaik {{ getImageWidth() }}x{{getImageHeight() }} px</small>').appendTo( $target );

				$imgSrc.on("change", function(){
					readFile(this, $(this).closest("div").find("img"));
				});
			}

			function readFile(_this, $img) {
				if (_this.files && _this.files[0]) {
					var FR= new FileReader();					
					FR.addEventListener("load", function(e) {
						//document.getElementById("img").src       = e.target.result;
						//document.getElementById("b64").innerHTML = e.target.result;
						console.log(e.target.result);
						$img.attr("src", e.target.result);
					}); 
					
					FR.readAsDataURL( _this.files[0] );
				}
			}
		});
	</script>
@endsection