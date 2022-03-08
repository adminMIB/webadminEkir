$(function() {
	BtnDelete.init();
	BtnAll.init();
	
    Croppie.init();
	ChangeIcon.init();
	App.init();
});

/*
* App
*/
var App = {
	init : function(){
		//Selected Side Menu
		this.initSideMenu();

		//Indonesian Phone Format
		this.initPhoneFormat("input.phone-format");

		//Link To Whatsapp Web
		this.linkToWhatsapp();

		//Show Hide Password
		this.showHidePassword();

		//Select 2
		$('.select2').each(function() {
			var placeholder = $(this).attr("data-placeholder") ? $(this).attr("data-placeholder"): "--Pilih--";
			$(this)
			.wrap('<div class="position-relative"></div>')
			.select2({
				placeholder: placeholder,
				dropdownParent: $(this).parent()
			});
		});

	},

	showHidePassword: function(){
		var sHTML ='<div class="show-hide-wrapper" style="display:none;position:absolute;right:20px;top:8px"><a href="javascript:void(0)" class="show">Tampilkan</a><a href="javascript:void(0)" style="display:none" class="hide">Sembunyikan</a></div>';
		var $password =$("input[type='password']");
		if ($password.length > 0){
			var $target =$password.closest("div");
			$(sHTML).appendTo($target);

			var $showHide = $target.find("a.show, a.hide");
			
			$showHide.on("click", function(){
				if($(this).hasClass("show")){
					$("a.show").hide();$("a.hide").show();
					$password.attr("type", "text");
				}else{
					$("a.hide").hide();$("a.show").show();
					$password.attr("type", "password");
				}
			});
			$password.on("keyup", function(){
				if($(this).val().trim()){
					$("div.show-hide-wrapper").stop().fadeIn(300);
				}else{
					$("div.show-hide-wrapper").stop().fadeOut(300);
				}
			});

			$password.closest("form").on("submit", function(){
				var password = $("input[name='password']").val();
				var repassword = $("input[name='repassword']").val();
				if(password && repassword){
					if(password != repassword) {
						alert("Password dan Konfirmasi password tidak sama!");
						return false;
					}
				}
			});
		}
	},

	linkToWhatsapp: function(){
		$(".to-whatsapp").on("click", function(){
			var number = $(this).attr("data-number");
			var message = $(this).attr("data-message");
			if(number.length > 2 && message){
				message = message.split(' ').join('%20');
				window.open('https://api.whatsapp.com/send?phone='+number+'&text=%20'+message, '_blank');
			}
		})
	},

	initSideMenu : function(){
		var arr=window.location.pathname.split('/');
		//console.log(arr[2]);
		if(typeof arr[2] !== "undefined" && arr[2].trim()){
			var slug = arr[2];
			var $liActive =$("ul.sidenav-inner").find("li."+slug);
			if($liActive.length > 0){
				$liActive.closest("ul").closest("li.sidenav-item").addClass("open");
			}
		}
	},

	initPhoneFormat : function (element){
		var $phoneFormat =$((typeof element === "undefined" ? "input.phone-format" : element));
		if($phoneFormat.length > 0){
			App.set2PhoneFormat($phoneFormat);
		}
	},

	/*
    * Set Phone Number with Mask
    */
   	set2PhoneFormat : function ($obj){
        var _this =this;
        $obj.on("keydown", function(e){
            if(App.isNumberKey(e)){
                var charCode = (e.which) ? e.which : e.keyCode;
                if(charCode == 8 || charCode == 39 || charCode == 37){
                    return true;
                }else{
                    _this.phoneFormat($obj);
                }
            }else{
                return false;
            }
        });
        $obj.on("blur", function(){
            _this.phoneFormat($obj)
        });
	},
	
	phoneFormat : function($obj){
        var value = $obj.val().replaceAll(" - ", "");
        var svalue = value.match(/.{1,4}/g);
        $obj.val( svalue );
        var fvalue = $obj.val().replaceAll(",", " - ");
        var arr = fvalue.split("-").map(Function.prototype.call, String.prototype.trim);
		$obj.val( arr.join(" - ") );
	},
	
	/*
   * is number
   */
   isNumberKey : function(evt) {
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)  && charCode!=13  && charCode!=9  && charCode!=37  && charCode!=39) {
			if(charCode < 96 && charCode > 105){ //num lock            
			}else{
				return false;
			}
		}
		return true;
	},

	//Add Commas
	addCommas : function(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	},

	//Rupiah Format
	rupiahFormat : function(nStr){
		nStr += '';
		x = nStr.split(',');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return "Rp"+x1 + x2;
	},

	
   /*
   * Ajax Request
   */
    request: function (url, data, isShowLoader, onMessage, type) {
        var _this =this;
        var response =null;
        _this.spinner(false);
        if (isShowLoader === true) {
            _this.spinner(true);
		}
		if (typeof data._token === "undefined"){
        data._token = $("input[name='_token']").val();
			if(!data._token){
				data._token = $('meta[name="_token"]').attr("content");
			}
		}

        var reqData = {
            url: url,
            type: typeof type === "undefined" ? "post" : type,
            data: data,
            dataType: "json",
            timeout: 0,
            success: function (response, status, xhr) {
                _this.spinner(false);
                if(typeof onMessage === "function"){
                    onMessage(response, status, xhr);
                }
            },
            beforeSend: function (xhr, opt) {
                //xhr.id = opt.id
            },
            error: function (jqXHR, exception) {
                _this.spinner(false);
                if(typeof onMessage === "function"){
                    onMessage(response, jqXHR, exception);
                }
            }
        }
        $.ajax(reqData)
    },
	/*
	* Ajax Request
	*/
	/*
	 request: function (url, data, isShowLoader, onMessage, type) {
		 var _this =this;
		 var response =null;
		 _this.spinner(false);
		 if (isShowLoader === true) {
			 _this.spinner(true);
		 }
		 data._token = $("input[name='_token']").val();
		 if(!data._token){
			 data._token = $('meta[name="_token"]').attr("content");
		 }
 
		 var reqData = {
			 url: url,
			 type: typeof type === "undefined" ? "post" : type,
			 data: data,
			 dataType: "json",
			 timeout: 0,
			 success: function (response, status, xhr) {
				 _this.spinner(false);
				 if(typeof onMessage === "function"){
					 onMessage(response, status, xhr);
				 }
			 },
			 beforeSend: function (xhr, opt) {
				 //xhr.id = opt.id
			 },
			 error: function (jqXHR, exception) {
				 _this.spinner(false);
				 if(typeof onMessage === "function"){
					 onMessage(response, jqXHR, exception);
				 }
			 }
		 }
		 $.ajax(reqData)
	 },
	 */
	 /*
    * Loading Spinner
    */
   spinner: function(isShow){
        if(isShow){
            $(".page-loader-wrapper").show();
        }else{
            $(".page-loader-wrapper").hide();
        }
   }
}
/*
* Parse Number Format
*/
String.prototype.parseNumberFormat = function(){
	//100.000,3434
	var s =this;	
	do {
    	//s = s.replace(".", "titik");
		s = s.replace(".", "");
   	} while (s.indexOf(".") !== -1);
	do {
    	s = s.replace(",", ".");
   	} while (s.indexOf(",") !== -1);
	
	s =parseFloat(s);
	if(isNaN(s)) s =0;
	
	return s;
};
/*
* Repalce All
*/
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

/*
* Button Delete Confirmation
*/
var BtnDelete ={
	init: function(){
		var $btn =$(".btn-delete");
		if($btn.length > 0){
			$btn.on("click", function(){
				var conf =confirm("Anda yakin akan menghapus?");
				return conf;
			});
		}
	}
};

/*
* Button All
*/
var BtnAll = {
	init : function(){
		var _this =this;
		
		var $btnFilter =$(".btn-reset-filter");
		if($btnFilter.length > 0){
			$btnFilter.on("click", function(e){
				e.preventDefault();
				var $form =$btnFilter.closest("form");
				
				//Reset input
				$form.find("input[type='text']").val('');
				
				$form.find("select option").removeAttr("selected");
				$form.find("select option[value='0']").prop("selected", true);
				$form.find("select option[value='']").prop("selected", true);
				
				//Select 2
				if($('.select2').length > 0){
					$('.select2').each(function() {
						$(this).val("0").trigger("change");
					});
				}
			});
		}
		
		//Export Xls
		var $btnXls =$('.btn-export-xls');
		if($btnXls.length > 0){
			$btnXls.on("click", function(e){
				e.preventDefault();
				var $form =$btnXls.closest("form");
				
				//$form.submit();
				location.href = '?export-xls=1&'+$form.serialize()
			});
		}
	}
};


/*
| CROPPIE
*/
var Croppie ={
	$croppie : null,
	width : 150,
	height: 150,
	init : function(){
		this.$croppie = $("img.croppie");
		if(this.$croppie.length > 0){
			var crWidth =this.$croppie.attr("coppie-w");
			var	crHeight =this.$croppie.attr("coppie-h");

			if(crWidth){ this.width = crWidth; }
			if(crHeight){ this.height = crHeight; }

			this.createElement();
			this.addEventListener();
		}
		
	},
	
	createElement: function(){
		this.$croppie.wrap('<label for="browse-croppie" class="croppie-wrapper"></label>');
		$('<i class="icon-camera ion ion-md-camera fade"></i><input type="file" class="item-img" id="browse-croppie" name="croppie-img" accept="image/*" style="display: none">').appendTo(this.$croppie.closest('label'));
		
		this.$croppie.hover(
			function(){
				//$(".croppie-wrapper").find(".icon-camera").stop().fadeIn("fast");
				//console.log($(".croppie-wrapper").find(".icon-camera").length);
				$(".croppie-wrapper").find(".icon-camera").css("opacity", 1);
			},
			function(){
				$(".croppie-wrapper").find(".icon-camera").css("opacity", 0);
			}
		);
		
		if($("#cropImagePop").length == 0){
			$(this.elModal()).appendTo('body');
		}
		
	},
	
	elModal: function(){
		return '<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
			'<div class="modal-dialog">'+
			'	<div class="modal-content">'+
			'		<div class="modal-header">'+
			'			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
			'			<h4 class="modal-title" id="myModalLabel">Upload</h4>'+
			'		</div>'+
			'		<div class="modal-body">'+
			'			<div id="upload-demo" class="center-block"></div>'+
			'		</div>'+
			'		<div class="modal-footer">'+
			'			<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>'+
			'			<button type="button" id="cropImageBtn" class="btn btn-primary">Simpan</button>'+
			'		</div>'+
			'	</div>'+
			'</div>'+
		'</div>';
	},
	
	addEventListener : function(){
		var _this =this;

		$uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: _this.width,
				height: _this.height,
				//type  : typeof(CRP_TYPE) === "undefined" ? "square" : CRP_TYPE,
				//type: 'circle'
				type: 'square'
			},
			boundary: {
				width: parseInt(_this.width)+100,
				height: parseInt(_this.height)+100
			},
			enableExif: true
		});
		
		$('#cropImagePop').on('shown.bs.modal', function(){
			$uploadCrop.croppie('bind', {
				url: rawImg
			}).then(function(){
				//console.log('jQuery bind complete');
			});
		});
	
		$(".croppie-wrapper .item-img").on('change', function () {
			imageId = $(this).data('id'); 
			tempFilename = $(this).val();
			$('#cancelCropBtn').data('id', imageId);
			_this.readFile(this); 
		});
		
		$('#cropImageBtn').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'base64',
            format: 'jpeg',
            size: {width: _this.width, height: _this.height}
        }).then(function (resp) {
			_this.$croppie.attr('src', resp);
            if(typeof onCroppie === "function"){
                onCroppie(resp);
            }
            $('#cropImagePop').modal('hide');
        });
    });
	},
	
	readFile : function(input){
		if (input.files && input.files[0]) {
          var reader = new FileReader();
            reader.onload = function (e) {
                $('.upload-demo').addClass('ready');
                $('#cropImagePop').modal('show');
                rawImg = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
        else {
            alert("Sorry - you're browser doesn't support the FileReader API");
        }
	}
};

/*
* Change Icon
*/
var ChangeIcon={
	$icon : null,
	icon : null,
	init: function(){
		this.$icon = $("a.change-icon");
		if(this.$icon.length > 0){
			this.renderModal();
		}
	},
	
	renderModal: function(){
		var _this =this;
		var el =$("#modal-icons");
		if($(el).length == 0){
			$(this.modalHTML()).appendTo('body');
			this.$icon.attr({'data-toggle' : 'modal', 'data-target' : '#modal-icons'});
			this.fontAwesome5Init();
			this.iconIonsInit();
			this.linearIconInit();
			this.openIconicInit();
			this.strokeIcons7Init();
			
			//Click Icon
			$("#modal-icons .card.icon-example").on("click", function(){
				var $activeIcon = $("#modal-icons").find(".card.icon-example.active");
				if($activeIcon.length > 0){
					$activeIcon.removeClass("active");
				}
				$(this).addClass("active");
				_this.icon = $(this).find("i").attr("class");
				
			});
			//Change Icon
			$("#modal-icons").find("button.btn-change-icon").on("click", function(){
				if(typeof onChangeIcon === "function"){
					var $input = _this.$icon.closest("form").find("input[name='icon']");
					if($input.length == 0){
						$('<input type="hidden" name="icon" />').appendTo(_this.$icon.closest("form"));
						$input = _this.$icon.closest("form").find("input[name='icon']");
					}
					$input.val(_this.icon);
					
					onChangeIcon(_this.icon);
					$("#modal-icons").modal('hide');
				}
			});
		}
	},
	
	modalHTML : function(){
		return '<!-- The Modal -->'+
			'<div class="modal fade" id="modal-icons">'+
			'  <div class="modal-dialog modal-lg">'+
			'	<div class="modal-content">'+
			'	  <!-- Modal Header -->'+
			'	  <div class="modal-header">'+
			'		<h4 class="modal-title">Icons</h4>'+
			'		<button type="button" class="close" data-dismiss="modal">&times;</button>'+
			'	  </div>'+
			'	  <!-- Modal body -->'+
			'	  <div class="modal-body">'+
			
			'<div class="nav-tabs-top mb-4">'+
            '      <ul class="nav nav-tabs">'+
            '        <li class="nav-item">'+
            '          <a class="nav-link active show" data-toggle="tab" href="#navs-font-awesome">Font Awesome 5</a>'+
            '        </li>'+
            '        <li class="nav-item">'+
            '          <a class="nav-link" data-toggle="tab" href="#navs-ionicons">Ionicons</a>'+
            '        </li>'+
            '        <li class="nav-item">'+
            '          <a class="nav-link" data-toggle="tab" href="#navs-liner-icons">Linear Icons</a>'+
            '        </li>'+
			'        <li class="nav-item">'+
            '          <a class="nav-link" data-toggle="tab" href="#navs-open-iconic">Open Iconic</a>'+
            '        </li>'+
			'        <li class="nav-item">'+
            '          <a class="nav-link" data-toggle="tab" href="#navs-stroke-icons-7">Stroke Icons 7</a>'+
            '        </li>'+
            '      </ul>'+
            '      <div class="tab-content">'+
            '        <div class="tab-pane fade active show" id="navs-font-awesome">'+
                      
			'			<div class="py-2 my-4 mx-auto" style="max-width:300px">'+
			'			  <div class="input-group">'+
			'				<div class="input-group-prepend">'+
			'				  <span class="input-group-text"><i class="ion ion-ios-search"></i></span>'+
			'				</div>'+
			'				<input type="text" class="form-control" id="ion-fa-icons-search" placeholder="Search...">'+
			'			  </div>'+
			'			</div>'+
			'			<div id="font-awesome-5-icons-container" class="text-center"></div>'+
					  
            '       </div>'+
            '        <div class="tab-pane fade" id="navs-ionicons">'+
            '          <div class="card-body">'+
                        
			'			<div class="py-2 my-4 mx-auto" style="max-width:300px">'+
			'			  <div class="input-group">'+
			'				<div class="input-group-prepend">'+
			'				  <span class="input-group-text"><i class="ion ion-ios-search"></i></span>'+
			'				</div>'+
			'				<input type="text" class="form-control" id="ion-ios-icons-search" placeholder="Search...">'+
			'			  </div>'+
			'			</div>'+
			'			<div id="ionicons-icons-container" class="text-center"></div>'+
						
            '         </div>'+
            '        </div>'+
            '        <div class="tab-pane fade" id="navs-liner-icons">'+
            '          <div class="card-body">'+
            
			'			<div class="py-2 my-4 mx-auto" style="max-width:300px">'+
			'			  <div class="input-group">'+
			'				<div class="input-group-prepend">'+
			'				  <span class="input-group-text"><i class="ion ion-ios-search"></i></span>'+
			'				</div>'+
			'				<input type="text" class="form-control" id="linear-icons-search" placeholder="Search...">'+
			'			  </div>'+
			'			</div>'+
			'			<div id="linearicon-icons-container" class="text-center"></div>'+
			
            '          </div>'+
            '        </div>'+
			'        <div class="tab-pane fade" id="navs-open-iconic">'+
            '          <div class="card-body">'+
            
			'			<div class="py-2 my-4 mx-auto" style="max-width:300px">'+
			'			  <div class="input-group">'+
			'				<div class="input-group-prepend">'+
			'				  <span class="input-group-text"><i class="ion ion-ios-search"></i></span>'+
			'				</div>'+
			'				<input type="text" class="form-control" id="open-iconic-icons-search" placeholder="Search...">'+
			'			  </div>'+
			'			</div>'+
			'			<div id="open-iconic-icons-container" class="text-center"></div>'+
			
            '          </div>'+
            '        </div>'+
			'        <div class="tab-pane fade" id="navs-stroke-icons-7">'+
            '          <div class="card-body">'+
            
			'			<div class="py-2 my-4 mx-auto" style="max-width:300px">'+
			'			  <div class="input-group">'+
			'				<div class="input-group-prepend">'+
			'				  <span class="input-group-text"><i class="ion ion-ios-search"></i></span>'+
			'				</div>'+
			'				<input type="text" class="form-control" id="icon-stroke7-icons-search" placeholder="Search...">'+
			'			  </div>'+
			'			</div>'+
			'			<div id="stroke-icons-7-icons-container" class="text-center"></div>'+
			
            '          </div>'+
            '        </div>'+
            '      </div>'+
            '    </div>'+
			
			'	  </div>'+
			'	  <!-- Modal footer -->'+
			'	  <div class="modal-footer">'+
			'		<button type="button" class="btn btn-warning btn-change-icon">Change Icon</button>&nbsp;'+
			'		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'+
			'	  </div>'+
			'	</div>'+
			' </div>'+
			'</div>';
	},
	
	fontAwesome5Init : function(){
		var icons = [['fab', '500px'], ['fab', 'accessible-icon'], ['fab', 'accusoft'], ['fas', 'address-book'], ['far', 'address-book'], ['fas', 'address-card'], ['far', 'address-card'], ['fas', 'adjust'], ['fab', 'adn'], ['fab', 'adversal'], ['fab', 'affiliatetheme'], ['fab', 'algolia'], ['fas', 'align-center'], ['fas', 'align-justify'], ['fas', 'align-left'], ['fas', 'align-right'], ['fas', 'allergies'], ['fab', 'amazon'], ['fab', 'amazon-pay'], ['fas', 'ambulance'], ['fas', 'american-sign-language-interpreting'], ['fab', 'amilia'], ['fas', 'anchor'], ['fab', 'android'], ['fab', 'angellist'], ['fas', 'angle-double-down'], ['fas', 'angle-double-left'], ['fas', 'angle-double-right'], ['fas', 'angle-double-up'], ['fas', 'angle-down'], ['fas', 'angle-left'], ['fas', 'angle-right'], ['fas', 'angle-up'], ['fab', 'angrycreative'], ['fab', 'angular'], ['fab', 'app-store'], ['fab', 'app-store-ios'], ['fab', 'apper'], ['fab', 'apple'], ['fab', 'apple-pay'], ['fas', 'archive'], ['fas', 'arrow-alt-circle-down'], ['far', 'arrow-alt-circle-down'], ['fas', 'arrow-alt-circle-left'], ['far', 'arrow-alt-circle-left'], ['fas', 'arrow-alt-circle-right'], ['far', 'arrow-alt-circle-right'], ['fas', 'arrow-alt-circle-up'], ['far', 'arrow-alt-circle-up'], ['fas', 'arrow-circle-down'], ['fas', 'arrow-circle-left'], ['fas', 'arrow-circle-right'], ['fas', 'arrow-circle-up'], ['fas', 'arrow-down'], ['fas', 'arrow-left'], ['fas', 'arrow-right'], ['fas', 'arrow-up'], ['fas', 'arrows-alt'], ['fas', 'arrows-alt-h'], ['fas', 'arrows-alt-v'], ['fas', 'assistive-listening-systems'], ['fas', 'asterisk'], ['fab', 'asymmetrik'], ['fas', 'at'], ['fab', 'audible'], ['fas', 'audio-description'], ['fab', 'autoprefixer'], ['fab', 'avianex'], ['fab', 'aviato'], ['fab', 'aws'], ['fas', 'backward'], ['fas', 'balance-scale'], ['fas', 'ban'], ['fas', 'band-aid'], ['fab', 'bandcamp'], ['fas', 'barcode'], ['fas', 'bars'], ['fas', 'baseball-ball'], ['fas', 'basketball-ball'], ['fas', 'bath'], ['fas', 'battery-empty'], ['fas', 'battery-full'], ['fas', 'battery-half'], ['fas', 'battery-quarter'], ['fas', 'battery-three-quarters'], ['fas', 'bed'], ['fas', 'beer'], ['fab', 'behance'], ['fab', 'behance-square'], ['fas', 'bell'], ['far', 'bell'], ['fas', 'bell-slash'], ['far', 'bell-slash'], ['fas', 'bicycle'], ['fab', 'bimobject'], ['fas', 'binoculars'], ['fas', 'birthday-cake'], ['fab', 'bitbucket'], ['fab', 'bitcoin'], ['fab', 'bity'], ['fab', 'black-tie'], ['fab', 'blackberry'], ['fas', 'blender'], ['fas', 'blind'], ['fab', 'blogger'], ['fab', 'blogger-b'], ['fab', 'bluetooth'], ['fab', 'bluetooth-b'], ['fas', 'bold'], ['fas', 'bolt'], ['fas', 'bomb'], ['fas', 'book'], ['fas', 'book-open'], ['fas', 'bookmark'], ['far', 'bookmark'], ['fas', 'bowling-ball'], ['fas', 'box'], ['fas', 'box-open'], ['fas', 'boxes'], ['fas', 'braille'], ['fas', 'briefcase'], ['fas', 'briefcase-medical'], ['fas', 'broadcast-tower'], ['fas', 'broom'], ['fab', 'btc'], ['fas', 'bug'], ['fas', 'building'], ['far', 'building'], ['fas', 'bullhorn'], ['fas', 'bullseye'], ['fas', 'burn'], ['fab', 'buromobelexperte'], ['fas', 'bus'], ['fab', 'buysellads'], ['fas', 'calculator'], ['fas', 'calendar'], ['far', 'calendar'], ['fas', 'calendar-alt'], ['far', 'calendar-alt'], ['fas', 'calendar-check'], ['far', 'calendar-check'], ['fas', 'calendar-minus'], ['far', 'calendar-minus'], ['fas', 'calendar-plus'], ['far', 'calendar-plus'], ['fas', 'calendar-times'], ['far', 'calendar-times'], ['fas', 'camera'], ['fas', 'camera-retro'], ['fas', 'capsules'], ['fas', 'car'], ['fas', 'caret-down'], ['fas', 'caret-left'], ['fas', 'caret-right'], ['fas', 'caret-square-down'], ['far', 'caret-square-down'], ['fas', 'caret-square-left'], ['far', 'caret-square-left'], ['fas', 'caret-square-right'], ['far', 'caret-square-right'], ['fas', 'caret-square-up'], ['far', 'caret-square-up'], ['fas', 'caret-up'], ['fas', 'cart-arrow-down'], ['fas', 'cart-plus'], ['fab', 'cc-amazon-pay'], ['fab', 'cc-amex'], ['fab', 'cc-apple-pay'], ['fab', 'cc-diners-club'], ['fab', 'cc-discover'], ['fab', 'cc-jcb'], ['fab', 'cc-mastercard'], ['fab', 'cc-paypal'], ['fab', 'cc-stripe'], ['fab', 'cc-visa'], ['fab', 'centercode'], ['fas', 'certificate'], ['fas', 'chalkboard'], ['fas', 'chalkboard-teacher'], ['fas', 'chart-area'], ['fas', 'chart-bar'], ['far', 'chart-bar'], ['fas', 'chart-line'], ['fas', 'chart-pie'], ['fas', 'check'], ['fas', 'check-circle'], ['far', 'check-circle'], ['fas', 'check-square'], ['far', 'check-square'], ['fas', 'chess'], ['fas', 'chess-bishop'], ['fas', 'chess-board'], ['fas', 'chess-king'], ['fas', 'chess-knight'], ['fas', 'chess-pawn'], ['fas', 'chess-queen'], ['fas', 'chess-rook'], ['fas', 'chevron-circle-down'], ['fas', 'chevron-circle-left'], ['fas', 'chevron-circle-right'], ['fas', 'chevron-circle-up'], ['fas', 'chevron-down'], ['fas', 'chevron-left'], ['fas', 'chevron-right'], ['fas', 'chevron-up'], ['fas', 'child'], ['fab', 'chrome'], ['fas', 'church'], ['fas', 'circle'], ['far', 'circle'], ['fas', 'circle-notch'], ['fas', 'clipboard'], ['far', 'clipboard'], ['fas', 'clipboard-check'], ['fas', 'clipboard-list'], ['fas', 'clock'], ['far', 'clock'], ['fas', 'clone'], ['far', 'clone'], ['fas', 'closed-captioning'], ['far', 'closed-captioning'], ['fas', 'cloud'], ['fas', 'cloud-download-alt'], ['fas', 'cloud-upload-alt'], ['fab', 'cloudscale'], ['fab', 'cloudsmith'], ['fab', 'cloudversify'], ['fas', 'code'], ['fas', 'code-branch'], ['fab', 'codepen'], ['fab', 'codiepie'], ['fas', 'coffee'], ['fas', 'cog'], ['fas', 'cogs'], ['fas', 'coins'], ['fas', 'columns'], ['fas', 'comment'], ['far', 'comment'], ['fas', 'comment-alt'], ['far', 'comment-alt'], ['fas', 'comment-dots'], ['far', 'comment-dots'], ['fas', 'comment-slash'], ['fas', 'comments'], ['far', 'comments'], ['fas', 'compact-disc'], ['fas', 'compass'], ['far', 'compass'], ['fas', 'compress'], ['fab', 'connectdevelop'], ['fab', 'contao'], ['fas', 'copy'], ['far', 'copy'], ['fas', 'copyright'], ['far', 'copyright'], ['fas', 'couch'], ['fab', 'cpanel'], ['fab', 'creative-commons'], ['fab', 'creative-commons-by'], ['fab', 'creative-commons-nc'], ['fab', 'creative-commons-nc-eu'], ['fab', 'creative-commons-nc-jp'], ['fab', 'creative-commons-nd'], ['fab', 'creative-commons-pd'], ['fab', 'creative-commons-pd-alt'], ['fab', 'creative-commons-remix'], ['fab', 'creative-commons-sa'], ['fab', 'creative-commons-sampling'], ['fab', 'creative-commons-sampling-plus'], ['fab', 'creative-commons-share'], ['fas', 'credit-card'], ['far', 'credit-card'], ['fas', 'crop'], ['fas', 'crosshairs'], ['fas', 'crow'], ['fas', 'crown'], ['fab', 'css3'], ['fab', 'css3-alt'], ['fas', 'cube'], ['fas', 'cubes'], ['fas', 'cut'], ['fab', 'cuttlefish'], ['fab', 'd-and-d'], ['fab', 'dashcube'], ['fas', 'database'], ['fas', 'deaf'], ['fab', 'delicious'], ['fab', 'deploydog'], ['fab', 'deskpro'], ['fas', 'desktop'], ['fab', 'deviantart'], ['fas', 'diagnoses'], ['fas', 'dice'], ['fas', 'dice-five'], ['fas', 'dice-four'], ['fas', 'dice-one'], ['fas', 'dice-six'], ['fas', 'dice-three'], ['fas', 'dice-two'], ['fab', 'digg'], ['fab', 'digital-ocean'], ['fab', 'discord'], ['fab', 'discourse'], ['fas', 'divide'], ['fas', 'dna'], ['fab', 'dochub'], ['fab', 'docker'], ['fas', 'dollar-sign'], ['fas', 'dolly'], ['fas', 'dolly-flatbed'], ['fas', 'donate'], ['fas', 'door-closed'], ['fas', 'door-open'], ['fas', 'dot-circle'], ['far', 'dot-circle'], ['fas', 'dove'], ['fas', 'download'], ['fab', 'draft2digital'], ['fab', 'dribbble'], ['fab', 'dribbble-square'], ['fab', 'dropbox'], ['fab', 'drupal'], ['fas', 'dumbbell'], ['fab', 'dyalog'], ['fab', 'earlybirds'], ['fab', 'ebay'], ['fab', 'edge'], ['fas', 'edit'], ['far', 'edit'], ['fas', 'eject'], ['fab', 'elementor'], ['fas', 'ellipsis-h'], ['fas', 'ellipsis-v'], ['fab', 'ember'], ['fab', 'empire'], ['fas', 'envelope'], ['far', 'envelope'], ['fas', 'envelope-open'], ['far', 'envelope-open'], ['fas', 'envelope-square'], ['fab', 'envira'], ['fas', 'equals'], ['fas', 'eraser'], ['fab', 'erlang'], ['fab', 'ethereum'], ['fab', 'etsy'], ['fas', 'euro-sign'], ['fas', 'exchange-alt'], ['fas', 'exclamation'], ['fas', 'exclamation-circle'], ['fas', 'exclamation-triangle'], ['fas', 'expand'], ['fas', 'expand-arrows-alt'], ['fab', 'expeditedssl'], ['fas', 'external-link-alt'], ['fas', 'external-link-square-alt'], ['fas', 'eye'], ['far', 'eye'], ['fas', 'eye-dropper'], ['fas', 'eye-slash'], ['far', 'eye-slash'], ['fab', 'facebook'], ['fab', 'facebook-f'], ['fab', 'facebook-messenger'], ['fab', 'facebook-square'], ['fas', 'fast-backward'], ['fas', 'fast-forward'], ['fas', 'fax'], ['fas', 'feather'], ['fas', 'female'], ['fas', 'fighter-jet'], ['fas', 'file'], ['far', 'file'], ['fas', 'file-alt'], ['far', 'file-alt'], ['fas', 'file-archive'], ['far', 'file-archive'], ['fas', 'file-audio'], ['far', 'file-audio'], ['fas', 'file-code'], ['far', 'file-code'], ['fas', 'file-excel'], ['far', 'file-excel'], ['fas', 'file-image'], ['far', 'file-image'], ['fas', 'file-medical'], ['fas', 'file-medical-alt'], ['fas', 'file-pdf'], ['far', 'file-pdf'], ['fas', 'file-powerpoint'], ['far', 'file-powerpoint'], ['fas', 'file-video'], ['far', 'file-video'], ['fas', 'file-word'], ['far', 'file-word'], ['fas', 'film'], ['fas', 'filter'], ['fas', 'fire'], ['fas', 'fire-extinguisher'], ['fab', 'firefox'], ['fas', 'first-aid'], ['fab', 'first-order'], ['fab', 'first-order-alt'], ['fab', 'firstdraft'], ['fas', 'flag'], ['far', 'flag'], ['fas', 'flag-checkered'], ['fas', 'flask'], ['fab', 'flickr'], ['fab', 'flipboard'], ['fab', 'fly'], ['fas', 'folder'], ['far', 'folder'], ['fas', 'folder-open'], ['far', 'folder-open'], ['fas', 'font'], ['fab', 'font-awesome'], ['fab', 'font-awesome-alt'], ['fab', 'font-awesome-flag'], ['fas', 'font-awesome-logo-full'], ['far', 'font-awesome-logo-full'], ['fab', 'font-awesome-logo-full'], ['fab', 'fonticons'], ['fab', 'fonticons-fi'], ['fas', 'football-ball'], ['fab', 'fort-awesome'], ['fab', 'fort-awesome-alt'], ['fab', 'forumbee'], ['fas', 'forward'], ['fab', 'foursquare'], ['fab', 'free-code-camp'], ['fab', 'freebsd'], ['fas', 'frog'], ['fas', 'frown'], ['far', 'frown'], ['fab', 'fulcrum'], ['fas', 'futbol'], ['far', 'futbol'], ['fab', 'galactic-republic'], ['fab', 'galactic-senate'], ['fas', 'gamepad'], ['fas', 'gas-pump'], ['fas', 'gavel'], ['fas', 'gem'], ['far', 'gem'], ['fas', 'genderless'], ['fab', 'get-pocket'], ['fab', 'gg'], ['fab', 'gg-circle'], ['fas', 'gift'], ['fab', 'git'], ['fab', 'git-square'], ['fab', 'github'], ['fab', 'github-alt'], ['fab', 'github-square'], ['fab', 'gitkraken'], ['fab', 'gitlab'], ['fab', 'gitter'], ['fas', 'glass-martini'], ['fas', 'glasses'], ['fab', 'glide'], ['fab', 'glide-g'], ['fas', 'globe'], ['fab', 'gofore'], ['fas', 'golf-ball'], ['fab', 'goodreads'], ['fab', 'goodreads-g'], ['fab', 'google'], ['fab', 'google-drive'], ['fab', 'google-play'], ['fab', 'google-plus'], ['fab', 'google-plus-g'], ['fab', 'google-plus-square'], ['fab', 'google-wallet'], ['fas', 'graduation-cap'], ['fab', 'gratipay'], ['fab', 'grav'], ['fas', 'greater-than'], ['fas', 'greater-than-equal'], ['fab', 'gripfire'], ['fab', 'grunt'], ['fab', 'gulp'], ['fas', 'h-square'], ['fab', 'hacker-news'], ['fab', 'hacker-news-square'], ['fas', 'hand-holding'], ['fas', 'hand-holding-heart'], ['fas', 'hand-holding-usd'], ['fas', 'hand-lizard'], ['far', 'hand-lizard'], ['fas', 'hand-paper'], ['far', 'hand-paper'], ['fas', 'hand-peace'], ['far', 'hand-peace'], ['fas', 'hand-point-down'], ['far', 'hand-point-down'], ['fas', 'hand-point-left'], ['far', 'hand-point-left'], ['fas', 'hand-point-right'], ['far', 'hand-point-right'], ['fas', 'hand-point-up'], ['far', 'hand-point-up'], ['fas', 'hand-pointer'], ['far', 'hand-pointer'], ['fas', 'hand-rock'], ['far', 'hand-rock'], ['fas', 'hand-scissors'], ['far', 'hand-scissors'], ['fas', 'hand-spock'], ['far', 'hand-spock'], ['fas', 'hands'], ['fas', 'hands-helping'], ['fas', 'handshake'], ['far', 'handshake'], ['fas', 'hashtag'], ['fas', 'hdd'], ['far', 'hdd'], ['fas', 'heading'], ['fas', 'headphones'], ['fas', 'heart'], ['far', 'heart'], ['fas', 'heartbeat'], ['fas', 'helicopter'], ['fab', 'hips'], ['fab', 'hire-a-helper'], ['fas', 'history'], ['fas', 'hockey-puck'], ['fas', 'home'], ['fab', 'hooli'], ['fas', 'hospital'], ['far', 'hospital'], ['fas', 'hospital-alt'], ['fas', 'hospital-symbol'], ['fab', 'hotjar'], ['fas', 'hourglass'], ['far', 'hourglass'], ['fas', 'hourglass-end'], ['fas', 'hourglass-half'], ['fas', 'hourglass-start'], ['fab', 'houzz'], ['fab', 'html5'], ['fab', 'hubspot'], ['fas', 'i-cursor'], ['fas', 'id-badge'], ['far', 'id-badge'], ['fas', 'id-card'], ['far', 'id-card'], ['fas', 'id-card-alt'], ['fas', 'image'], ['far', 'image'], ['fas', 'images'], ['far', 'images'], ['fab', 'imdb'], ['fas', 'inbox'], ['fas', 'indent'], ['fas', 'industry'], ['fas', 'infinity'], ['fas', 'info'], ['fas', 'info-circle'], ['fab', 'instagram'], ['fab', 'internet-explorer'], ['fab', 'ioxhost'], ['fas', 'italic'], ['fab', 'itunes'], ['fab', 'itunes-note'], ['fab', 'java'], ['fab', 'jedi-order'], ['fab', 'jenkins'], ['fab', 'joget'], ['fab', 'joomla'], ['fab', 'js'], ['fab', 'js-square'], ['fab', 'jsfiddle'], ['fas', 'key'], ['fab', 'keybase'], ['fas', 'keyboard'], ['far', 'keyboard'], ['fab', 'keycdn'], ['fab', 'kickstarter'], ['fab', 'kickstarter-k'], ['fas', 'kiwi-bird'], ['fab', 'korvue'], ['fas', 'language'], ['fas', 'laptop'], ['fab', 'laravel'], ['fab', 'lastfm'], ['fab', 'lastfm-square'], ['fas', 'leaf'], ['fab', 'leanpub'], ['fas', 'lemon'], ['far', 'lemon'], ['fab', 'less'], ['fas', 'less-than'], ['fas', 'less-than-equal'], ['fas', 'level-down-alt'], ['fas', 'level-up-alt'], ['fas', 'life-ring'], ['far', 'life-ring'], ['fas', 'lightbulb'], ['far', 'lightbulb'], ['fab', 'line'], ['fas', 'link'], ['fab', 'linkedin'], ['fab', 'linkedin-in'], ['fab', 'linode'], ['fab', 'linux'], ['fas', 'lira-sign'], ['fas', 'list'], ['fas', 'list-alt'], ['far', 'list-alt'], ['fas', 'list-ol'], ['fas', 'list-ul'], ['fas', 'location-arrow'], ['fas', 'lock'], ['fas', 'lock-open'], ['fas', 'long-arrow-alt-down'], ['fas', 'long-arrow-alt-left'], ['fas', 'long-arrow-alt-right'], ['fas', 'long-arrow-alt-up'], ['fas', 'low-vision'], ['fab', 'lyft'], ['fab', 'magento'], ['fas', 'magic'], ['fas', 'magnet'], ['fas', 'male'], ['fab', 'mandalorian'], ['fas', 'map'], ['far', 'map'], ['fas', 'map-marker'], ['fas', 'map-marker-alt'], ['fas', 'map-pin'], ['fas', 'map-signs'], ['fas', 'mars'], ['fas', 'mars-double'], ['fas', 'mars-stroke'], ['fas', 'mars-stroke-h'], ['fas', 'mars-stroke-v'], ['fab', 'mastodon'], ['fab', 'maxcdn'], ['fab', 'medapps'], ['fab', 'medium'], ['fab', 'medium-m'], ['fas', 'medkit'], ['fab', 'medrt'], ['fab', 'meetup'], ['fas', 'meh'], ['far', 'meh'], ['fas', 'memory'], ['fas', 'mercury'], ['fas', 'microchip'], ['fas', 'microphone'], ['fas', 'microphone-alt'], ['fas', 'microphone-alt-slash'], ['fas', 'microphone-slash'], ['fab', 'microsoft'], ['fas', 'minus'], ['fas', 'minus-circle'], ['fas', 'minus-square'], ['far', 'minus-square'], ['fab', 'mix'], ['fab', 'mixcloud'], ['fab', 'mizuni'], ['fas', 'mobile'], ['fas', 'mobile-alt'], ['fab', 'modx'], ['fab', 'monero'], ['fas', 'money-bill'], ['fas', 'money-bill-alt'], ['far', 'money-bill-alt'], ['fas', 'money-bill-wave'], ['fas', 'money-bill-wave-alt'], ['fas', 'money-check'], ['fas', 'money-check-alt'], ['fas', 'moon'], ['far', 'moon'], ['fas', 'motorcycle'], ['fas', 'mouse-pointer'], ['fas', 'music'], ['fab', 'napster'], ['fas', 'neuter'], ['fas', 'newspaper'], ['far', 'newspaper'], ['fab', 'nintendo-switch'], ['fab', 'node'], ['fab', 'node-js'], ['fas', 'not-equal'], ['fas', 'notes-medical'], ['fab', 'npm'], ['fab', 'ns8'], ['fab', 'nutritionix'], ['fas', 'object-group'], ['far', 'object-group'], ['fas', 'object-ungroup'], ['far', 'object-ungroup'], ['fab', 'odnoklassniki'], ['fab', 'odnoklassniki-square'], ['fab', 'old-republic'], ['fab', 'opencart'], ['fab', 'openid'], ['fab', 'opera'], ['fab', 'optin-monster'], ['fab', 'osi'], ['fas', 'outdent'], ['fab', 'page4'], ['fab', 'pagelines'], ['fas', 'paint-brush'], ['fas', 'palette'], ['fab', 'palfed'], ['fas', 'pallet'], ['fas', 'paper-plane'], ['far', 'paper-plane'], ['fas', 'paperclip'], ['fas', 'parachute-box'], ['fas', 'paragraph'], ['fas', 'parking'], ['fas', 'paste'], ['fab', 'patreon'], ['fas', 'pause'], ['fas', 'pause-circle'], ['far', 'pause-circle'], ['fas', 'paw'], ['fab', 'paypal'], ['fas', 'pen-square'], ['fas', 'pencil-alt'], ['fas', 'people-carry'], ['fas', 'percent'], ['fas', 'percentage'], ['fab', 'periscope'], ['fab', 'phabricator'], ['fab', 'phoenix-framework'], ['fab', 'phoenix-squadron'], ['fas', 'phone'], ['fas', 'phone-slash'], ['fas', 'phone-square'], ['fas', 'phone-volume'], ['fab', 'php'], ['fab', 'pied-piper'], ['fab', 'pied-piper-alt'], ['fab', 'pied-piper-hat'], ['fab', 'pied-piper-pp'], ['fas', 'piggy-bank'], ['fas', 'pills'], ['fab', 'pinterest'], ['fab', 'pinterest-p'], ['fab', 'pinterest-square'], ['fas', 'plane'], ['fas', 'play'], ['fas', 'play-circle'], ['far', 'play-circle'], ['fab', 'playstation'], ['fas', 'plug'], ['fas', 'plus'], ['fas', 'plus-circle'], ['fas', 'plus-square'], ['far', 'plus-square'], ['fas', 'podcast'], ['fas', 'poo'], ['fas', 'portrait'], ['fas', 'pound-sign'], ['fas', 'power-off'], ['fas', 'prescription-bottle'], ['fas', 'prescription-bottle-alt'], ['fas', 'print'], ['fas', 'procedures'], ['fab', 'product-hunt'], ['fas', 'project-diagram'], ['fab', 'pushed'], ['fas', 'puzzle-piece'], ['fab', 'python'], ['fab', 'qq'], ['fas', 'qrcode'], ['fas', 'question'], ['fas', 'question-circle'], ['far', 'question-circle'], ['fas', 'quidditch'], ['fab', 'quinscape'], ['fab', 'quora'], ['fas', 'quote-left'], ['fas', 'quote-right'], ['fab', 'r-project'], ['fas', 'random'], ['fab', 'ravelry'], ['fab', 'react'], ['fab', 'readme'], ['fab', 'rebel'], ['fas', 'receipt'], ['fas', 'recycle'], ['fab', 'red-river'], ['fab', 'reddit'], ['fab', 'reddit-alien'], ['fab', 'reddit-square'], ['fas', 'redo'], ['fas', 'redo-alt'], ['fas', 'registered'], ['far', 'registered'], ['fab', 'rendact'], ['fab', 'renren'], ['fas', 'reply'], ['fas', 'reply-all'], ['fab', 'replyd'], ['fab', 'researchgate'], ['fab', 'resolving'], ['fas', 'retweet'], ['fas', 'ribbon'], ['fas', 'road'], ['fas', 'robot'], ['fas', 'rocket'], ['fab', 'rocketchat'], ['fab', 'rockrms'], ['fas', 'rss'], ['fas', 'rss-square'], ['fas', 'ruble-sign'], ['fas', 'ruler'], ['fas', 'ruler-combined'], ['fas', 'ruler-horizontal'], ['fas', 'ruler-vertical'], ['fas', 'rupee-sign'], ['fab', 'safari'], ['fab', 'sass'], ['fas', 'save'], ['far', 'save'], ['fab', 'schlix'], ['fas', 'school'], ['fas', 'screwdriver'], ['fab', 'scribd'], ['fas', 'search'], ['fas', 'search-minus'], ['fas', 'search-plus'], ['fab', 'searchengin'], ['fas', 'seedling'], ['fab', 'sellcast'], ['fab', 'sellsy'], ['fas', 'server'], ['fab', 'servicestack'], ['fas', 'share'], ['fas', 'share-alt'], ['fas', 'share-alt-square'], ['fas', 'share-square'], ['far', 'share-square'], ['fas', 'shekel-sign'], ['fas', 'shield-alt'], ['fas', 'ship'], ['fas', 'shipping-fast'], ['fab', 'shirtsinbulk'], ['fas', 'shoe-prints'], ['fas', 'shopping-bag'], ['fas', 'shopping-basket'], ['fas', 'shopping-cart'], ['fas', 'shower'], ['fas', 'sign'], ['fas', 'sign-in-alt'], ['fas', 'sign-language'], ['fas', 'sign-out-alt'], ['fas', 'signal'], ['fab', 'simplybuilt'], ['fab', 'sistrix'], ['fas', 'sitemap'], ['fab', 'sith'], ['fas', 'skull'], ['fab', 'skyatlas'], ['fab', 'skype'], ['fab', 'slack'], ['fab', 'slack-hash'], ['fas', 'sliders-h'], ['fab', 'slideshare'], ['fas', 'smile'], ['far', 'smile'], ['fas', 'smoking'], ['fas', 'smoking-ban'], ['fab', 'snapchat'], ['fab', 'snapchat-ghost'], ['fab', 'snapchat-square'], ['fas', 'snowflake'], ['far', 'snowflake'], ['fas', 'sort'], ['fas', 'sort-alpha-down'], ['fas', 'sort-alpha-up'], ['fas', 'sort-amount-down'], ['fas', 'sort-amount-up'], ['fas', 'sort-down'], ['fas', 'sort-numeric-down'], ['fas', 'sort-numeric-up'], ['fas', 'sort-up'], ['fab', 'soundcloud'], ['fas', 'space-shuttle'], ['fab', 'speakap'], ['fas', 'spinner'], ['fab', 'spotify'], ['fas', 'square'], ['far', 'square'], ['fas', 'square-full'], ['fab', 'stack-exchange'], ['fab', 'stack-overflow'], ['fas', 'star'], ['far', 'star'], ['fas', 'star-half'], ['far', 'star-half'], ['fab', 'staylinked'], ['fab', 'steam'], ['fab', 'steam-square'], ['fab', 'steam-symbol'], ['fas', 'step-backward'], ['fas', 'step-forward'], ['fas', 'stethoscope'], ['fab', 'sticker-mule'], ['fas', 'sticky-note'], ['far', 'sticky-note'], ['fas', 'stop'], ['fas', 'stop-circle'], ['far', 'stop-circle'], ['fas', 'stopwatch'], ['fas', 'store'], ['fas', 'store-alt'], ['fab', 'strava'], ['fas', 'stream'], ['fas', 'street-view'], ['fas', 'strikethrough'], ['fab', 'stripe'], ['fab', 'stripe-s'], ['fas', 'stroopwafel'], ['fab', 'studiovinari'], ['fab', 'stumbleupon'], ['fab', 'stumbleupon-circle'], ['fas', 'subscript'], ['fas', 'subway'], ['fas', 'suitcase'], ['fas', 'sun'], ['far', 'sun'], ['fab', 'superpowers'], ['fas', 'superscript'], ['fab', 'supple'], ['fas', 'sync'], ['fas', 'sync-alt'], ['fas', 'syringe'], ['fas', 'table'], ['fas', 'table-tennis'], ['fas', 'tablet'], ['fas', 'tablet-alt'], ['fas', 'tablets'], ['fas', 'tachometer-alt'], ['fas', 'tag'], ['fas', 'tags'], ['fas', 'tape'], ['fas', 'tasks'], ['fas', 'taxi'], ['fab', 'teamspeak'], ['fab', 'telegram'], ['fab', 'telegram-plane'], ['fab', 'tencent-weibo'], ['fas', 'terminal'], ['fas', 'text-height'], ['fas', 'text-width'], ['fas', 'th'], ['fas', 'th-large'], ['fas', 'th-list'], ['fab', 'themeisle'], ['fas', 'thermometer'], ['fas', 'thermometer-empty'], ['fas', 'thermometer-full'], ['fas', 'thermometer-half'], ['fas', 'thermometer-quarter'], ['fas', 'thermometer-three-quarters'], ['fas', 'thumbs-down'], ['far', 'thumbs-down'], ['fas', 'thumbs-up'], ['far', 'thumbs-up'], ['fas', 'thumbtack'], ['fas', 'ticket-alt'], ['fas', 'times'], ['fas', 'times-circle'], ['far', 'times-circle'], ['fas', 'tint'], ['fas', 'toggle-off'], ['fas', 'toggle-on'], ['fas', 'toolbox'], ['fab', 'trade-federation'], ['fas', 'trademark'], ['fas', 'train'], ['fas', 'transgender'], ['fas', 'transgender-alt'], ['fas', 'trash'], ['fas', 'trash-alt'], ['far', 'trash-alt'], ['fas', 'tree'], ['fab', 'trello'], ['fab', 'tripadvisor'], ['fas', 'trophy'], ['fas', 'truck'], ['fas', 'truck-loading'], ['fas', 'truck-moving'], ['fas', 'tshirt'], ['fas', 'tty'], ['fab', 'tumblr'], ['fab', 'tumblr-square'], ['fas', 'tv'], ['fab', 'twitch'], ['fab', 'twitter'], ['fab', 'twitter-square'], ['fab', 'typo3'], ['fab', 'uber'], ['fab', 'uikit'], ['fas', 'umbrella'], ['fas', 'underline'], ['fas', 'undo'], ['fas', 'undo-alt'], ['fab', 'uniregistry'], ['fas', 'universal-access'], ['fas', 'university'], ['fas', 'unlink'], ['fas', 'unlock'], ['fas', 'unlock-alt'], ['fab', 'untappd'], ['fas', 'upload'], ['fab', 'usb'], ['fas', 'user'], ['far', 'user'], ['fas', 'user-alt'], ['fas', 'user-alt-slash'], ['fas', 'user-astronaut'], ['fas', 'user-check'], ['fas', 'user-circle'], ['far', 'user-circle'], ['fas', 'user-clock'], ['fas', 'user-cog'], ['fas', 'user-edit'], ['fas', 'user-friends'], ['fas', 'user-graduate'], ['fas', 'user-lock'], ['fas', 'user-md'], ['fas', 'user-minus'], ['fas', 'user-ninja'], ['fas', 'user-plus'], ['fas', 'user-secret'], ['fas', 'user-shield'], ['fas', 'user-slash'], ['fas', 'user-tag'], ['fas', 'user-tie'], ['fas', 'user-times'], ['fas', 'users'], ['fas', 'users-cog'], ['fab', 'ussunnah'], ['fas', 'utensil-spoon'], ['fas', 'utensils'], ['fab', 'vaadin'], ['fas', 'venus'], ['fas', 'venus-double'], ['fas', 'venus-mars'], ['fab', 'viacoin'], ['fab', 'viadeo'], ['fab', 'viadeo-square'], ['fas', 'vial'], ['fas', 'vials'], ['fab', 'viber'], ['fas', 'video'], ['fas', 'video-slash'], ['fab', 'vimeo'], ['fab', 'vimeo-square'], ['fab', 'vimeo-v'], ['fab', 'vine'], ['fab', 'vk'], ['fab', 'vnv'], ['fas', 'volleyball-ball'], ['fas', 'volume-down'], ['fas', 'volume-off'], ['fas', 'volume-up'], ['fab', 'vuejs'], ['fas', 'walking'], ['fas', 'wallet'], ['fas', 'warehouse'], ['fab', 'weibo'], ['fas', 'weight'], ['fab', 'weixin'], ['fab', 'whatsapp'], ['fab', 'whatsapp-square'], ['fas', 'wheelchair'], ['fab', 'whmcs'], ['fas', 'wifi'], ['fab', 'wikipedia-w'], ['fas', 'window-close'], ['far', 'window-close'], ['fas', 'window-maximize'], ['far', 'window-maximize'], ['fas', 'window-minimize'], ['far', 'window-minimize'], ['fas', 'window-restore'], ['far', 'window-restore'], ['fab', 'windows'], ['fas', 'wine-glass'], ['fab', 'wolf-pack-battalion'], ['fas', 'won-sign'], ['fab', 'wordpress'], ['fab', 'wordpress-simple'], ['fab', 'wpbeginner'], ['fab', 'wpexplorer'], ['fab', 'wpforms'], ['fas', 'wrench'], ['fas', 'x-ray'], ['fab', 'xbox'], ['fab', 'xing'], ['fab', 'xing-square'], ['fab', 'y-combinator'], ['fab', 'yahoo'], ['fab', 'yandex'], ['fab', 'yandex-international'], ['fab', 'yelp'], ['fas', 'yen-sign'], ['fab', 'yoast'], ['fab', 'youtube'], ['fab', 'youtube-square']];
		var template = '<div id="icon-%icon-id%" data-title="%icon-class%" class="card icon-example d-inline-flex justify-content-center align-items-center my-2 mx-2"><i class="%icon% d-block"></i></div>'
		var $container = $('#font-awesome-5-icons-container');

		for (var i = 0, l = icons.length; i < l; i++) {
		  $container.append($(template
			.replace(/%icon\-id%/g, icons[i].join('-'))
			.replace(/%icon%/g, icons[i][0] + ' fa-' + icons[i][1])
			.replace(/%icon\-class%/g, '.' + icons[i][0] + '.fa-' + icons[i][1])
		  ));
		}
		this.searchFontAwesomeIcons($("#ion-fa-icons-search"), icons, $container);
	},	
	iconIonsInit : function(){
		var icons = ['ios-add', 'md-add', 'ios-add-circle', 'md-add-circle', 'ios-add-circle-outline', 'md-add-circle-outline', 'ios-airplane', 'md-airplane', 'ios-alarm', 'md-alarm', 'ios-albums', 'md-albums', 'ios-alert', 'md-alert', 'ios-american-football', 'md-american-football', 'ios-analytics', 'md-analytics', 'ios-aperture', 'md-aperture', 'ios-apps', 'md-apps', 'ios-appstore', 'md-appstore', 'ios-archive', 'md-archive', 'ios-arrow-back', 'md-arrow-back', 'ios-arrow-down', 'md-arrow-down', 'ios-arrow-dropdown', 'md-arrow-dropdown', 'ios-arrow-dropdown-circle', 'md-arrow-dropdown-circle', 'ios-arrow-dropleft', 'md-arrow-dropleft', 'ios-arrow-dropleft-circle', 'md-arrow-dropleft-circle', 'ios-arrow-dropright', 'md-arrow-dropright', 'ios-arrow-dropright-circle', 'md-arrow-dropright-circle', 'ios-arrow-dropup', 'md-arrow-dropup', 'ios-arrow-dropup-circle', 'md-arrow-dropup-circle', 'ios-arrow-forward', 'md-arrow-forward', 'ios-arrow-round-back', 'md-arrow-round-back', 'ios-arrow-round-down', 'md-arrow-round-down', 'ios-arrow-round-forward', 'md-arrow-round-forward', 'ios-arrow-round-up', 'md-arrow-round-up', 'ios-arrow-up', 'md-arrow-up', 'ios-at', 'md-at', 'ios-attach', 'md-attach', 'ios-backspace', 'md-backspace', 'ios-barcode', 'md-barcode', 'ios-baseball', 'md-baseball', 'ios-basket', 'md-basket', 'ios-basketball', 'md-basketball', 'ios-battery-charging', 'md-battery-charging', 'ios-battery-dead', 'md-battery-dead', 'ios-battery-full', 'md-battery-full', 'ios-beaker', 'md-beaker', 'ios-bed', 'md-bed', 'ios-beer', 'md-beer', 'ios-bicycle', 'md-bicycle', 'ios-bluetooth', 'md-bluetooth', 'ios-boat', 'md-boat', 'ios-body', 'md-body', 'ios-bonfire', 'md-bonfire', 'ios-book', 'md-book', 'ios-bookmark', 'md-bookmark', 'ios-bookmarks', 'md-bookmarks', 'ios-bowtie', 'md-bowtie', 'ios-briefcase', 'md-briefcase', 'ios-browsers', 'md-browsers', 'ios-brush', 'md-brush', 'ios-bug', 'md-bug', 'ios-build', 'md-build', 'ios-bulb', 'md-bulb', 'ios-bus', 'md-bus', 'ios-business', 'md-business', 'ios-cafe', 'md-cafe', 'ios-calculator', 'md-calculator', 'ios-calendar', 'md-calendar', 'ios-call', 'md-call', 'ios-camera', 'md-camera', 'ios-car', 'md-car', 'ios-card', 'md-card', 'ios-cart', 'md-cart', 'ios-cash', 'md-cash', 'ios-cellular', 'md-cellular', 'ios-chatboxes', 'md-chatboxes', 'ios-chatbubbles', 'md-chatbubbles', 'ios-checkbox', 'md-checkbox', 'ios-checkbox-outline', 'md-checkbox-outline', 'ios-checkmark', 'md-checkmark', 'ios-checkmark-circle', 'md-checkmark-circle', 'ios-checkmark-circle-outline', 'md-checkmark-circle-outline', 'ios-clipboard', 'md-clipboard', 'ios-clock', 'md-clock', 'ios-close', 'md-close', 'ios-close-circle', 'md-close-circle', 'ios-close-circle-outline', 'md-close-circle-outline', 'ios-cloud', 'md-cloud', 'ios-cloud-circle', 'md-cloud-circle', 'ios-cloud-done', 'md-cloud-done', 'ios-cloud-download', 'md-cloud-download', 'ios-cloud-outline', 'md-cloud-outline', 'ios-cloud-upload', 'md-cloud-upload', 'ios-cloudy', 'md-cloudy', 'ios-cloudy-night', 'md-cloudy-night', 'ios-code', 'md-code', 'ios-code-download', 'md-code-download', 'ios-code-working', 'md-code-working', 'ios-cog', 'md-cog', 'ios-color-fill', 'md-color-fill', 'ios-color-filter', 'md-color-filter', 'ios-color-palette', 'md-color-palette', 'ios-color-wand', 'md-color-wand', 'ios-compass', 'md-compass', 'ios-construct', 'md-construct', 'ios-contact', 'md-contact', 'ios-contacts', 'md-contacts', 'ios-contract', 'md-contract', 'ios-contrast', 'md-contrast', 'ios-copy', 'md-copy', 'ios-create', 'md-create', 'ios-crop', 'md-crop', 'ios-cube', 'md-cube', 'ios-cut', 'md-cut', 'ios-desktop', 'md-desktop', 'ios-disc', 'md-disc', 'ios-document', 'md-document', 'ios-done-all', 'md-done-all', 'ios-download', 'md-download', 'ios-easel', 'md-easel', 'ios-egg', 'md-egg', 'ios-exit', 'md-exit', 'ios-expand', 'md-expand', 'ios-eye', 'md-eye', 'ios-eye-off', 'md-eye-off', 'ios-fastforward', 'md-fastforward', 'ios-female', 'md-female', 'ios-filing', 'md-filing', 'ios-film', 'md-film', 'ios-finger-print', 'md-finger-print', 'ios-fitness', 'md-fitness', 'ios-flag', 'md-flag', 'ios-flame', 'md-flame', 'ios-flash', 'md-flash', 'ios-flash-off', 'md-flash-off', 'ios-flashlight', 'md-flashlight', 'ios-flask', 'md-flask', 'ios-flower', 'md-flower', 'ios-folder', 'md-folder', 'ios-folder-open', 'md-folder-open', 'ios-football', 'md-football', 'ios-funnel', 'md-funnel', 'ios-gift', 'md-gift', 'ios-git-branch', 'md-git-branch', 'ios-git-commit', 'md-git-commit', 'ios-git-compare', 'md-git-compare', 'ios-git-merge', 'md-git-merge', 'ios-git-network', 'md-git-network', 'ios-git-pull-request', 'md-git-pull-request', 'ios-glasses', 'md-glasses', 'ios-globe', 'md-globe', 'ios-grid', 'md-grid', 'ios-hammer', 'md-hammer', 'ios-hand', 'md-hand', 'ios-happy', 'md-happy', 'ios-headset', 'md-headset', 'ios-heart', 'md-heart', 'ios-heart-dislike', 'md-heart-dislike', 'ios-heart-empty', 'md-heart-empty', 'ios-heart-half', 'md-heart-half', 'ios-help', 'md-help', 'ios-help-buoy', 'md-help-buoy', 'ios-help-circle', 'md-help-circle', 'ios-help-circle-outline', 'md-help-circle-outline', 'ios-home', 'md-home', 'ios-hourglass', 'md-hourglass', 'ios-ice-cream', 'md-ice-cream', 'ios-image', 'md-image', 'ios-images', 'md-images', 'ios-infinite', 'md-infinite', 'ios-information', 'md-information', 'ios-information-circle', 'md-information-circle', 'ios-information-circle-outline', 'md-information-circle-outline', 'ios-jet', 'md-jet', 'ios-journal', 'md-journal', 'ios-key', 'md-key', 'ios-keypad', 'md-keypad', 'ios-laptop', 'md-laptop', 'ios-leaf', 'md-leaf', 'ios-link', 'md-link', 'ios-list', 'md-list', 'ios-list-box', 'md-list-box', 'ios-locate', 'md-locate', 'ios-lock', 'md-lock', 'ios-log-in', 'md-log-in', 'ios-log-out', 'md-log-out', 'ios-magnet', 'md-magnet', 'ios-mail', 'md-mail', 'ios-mail-open', 'md-mail-open', 'ios-mail-unread', 'md-mail-unread', 'ios-male', 'md-male', 'ios-man', 'md-man', 'ios-map', 'md-map', 'ios-medal', 'md-medal', 'ios-medical', 'md-medical', 'ios-medkit', 'md-medkit', 'ios-megaphone', 'md-megaphone', 'ios-menu', 'md-menu', 'ios-mic', 'md-mic', 'ios-mic-off', 'md-mic-off', 'ios-microphone', 'md-microphone', 'ios-moon', 'md-moon', 'ios-more', 'md-more', 'ios-move', 'md-move', 'ios-musical-note', 'md-musical-note', 'ios-musical-notes', 'md-musical-notes', 'ios-navigate', 'md-navigate', 'ios-notifications', 'md-notifications', 'ios-notifications-off', 'md-notifications-off', 'ios-notifications-outline', 'md-notifications-outline', 'ios-nuclear', 'md-nuclear', 'ios-nutrition', 'md-nutrition', 'ios-open', 'md-open', 'ios-options', 'md-options', 'ios-outlet', 'md-outlet', 'ios-paper', 'md-paper', 'ios-paper-plane', 'md-paper-plane', 'ios-partly-sunny', 'md-partly-sunny', 'ios-pause', 'md-pause', 'ios-paw', 'md-paw', 'ios-people', 'md-people', 'ios-person', 'md-person', 'ios-person-add', 'md-person-add', 'ios-phone-landscape', 'md-phone-landscape', 'ios-phone-portrait', 'md-phone-portrait', 'ios-photos', 'md-photos', 'ios-pie', 'md-pie', 'ios-pin', 'md-pin', 'ios-pint', 'md-pint', 'ios-pizza', 'md-pizza', 'ios-planet', 'md-planet', 'ios-play', 'md-play', 'ios-play-circle', 'md-play-circle', 'ios-podium', 'md-podium', 'ios-power', 'md-power', 'ios-pricetag', 'md-pricetag', 'ios-pricetags', 'md-pricetags', 'ios-print', 'md-print', 'ios-pulse', 'md-pulse', 'ios-qr-scanner', 'md-qr-scanner', 'ios-quote', 'md-quote', 'ios-radio', 'md-radio', 'ios-radio-button-off', 'md-radio-button-off', 'ios-radio-button-on', 'md-radio-button-on', 'ios-rainy', 'md-rainy', 'ios-recording', 'md-recording', 'ios-redo', 'md-redo', 'ios-refresh', 'md-refresh', 'ios-refresh-circle', 'md-refresh-circle', 'ios-remove', 'md-remove', 'ios-remove-circle', 'md-remove-circle', 'ios-remove-circle-outline', 'md-remove-circle-outline', 'ios-reorder', 'md-reorder', 'ios-repeat', 'md-repeat', 'ios-resize', 'md-resize', 'ios-restaurant', 'md-restaurant', 'ios-return-left', 'md-return-left', 'ios-return-right', 'md-return-right', 'ios-reverse-camera', 'md-reverse-camera', 'ios-rewind', 'md-rewind', 'ios-ribbon', 'md-ribbon', 'ios-rocket', 'md-rocket', 'ios-rose', 'md-rose', 'ios-sad', 'md-sad', 'ios-save', 'md-save', 'ios-school', 'md-school', 'ios-search', 'md-search', 'ios-send', 'md-send', 'ios-settings', 'md-settings', 'ios-share', 'md-share', 'ios-share-alt', 'md-share-alt', 'ios-shirt', 'md-shirt', 'ios-shuffle', 'md-shuffle', 'ios-skip-backward', 'md-skip-backward', 'ios-skip-forward', 'md-skip-forward', 'ios-snow', 'md-snow', 'ios-speedometer', 'md-speedometer', 'ios-square', 'md-square', 'ios-square-outline', 'md-square-outline', 'ios-star', 'md-star', 'ios-star-half', 'md-star-half', 'ios-star-outline', 'md-star-outline', 'ios-stats', 'md-stats', 'ios-stopwatch', 'md-stopwatch', 'ios-subway', 'md-subway', 'ios-sunny', 'md-sunny', 'ios-swap', 'md-swap', 'ios-switch', 'md-switch', 'ios-sync', 'md-sync', 'ios-tablet-landscape', 'md-tablet-landscape', 'ios-tablet-portrait', 'md-tablet-portrait', 'ios-tennisball', 'md-tennisball', 'ios-text', 'md-text', 'ios-thermometer', 'md-thermometer', 'ios-thumbs-down', 'md-thumbs-down', 'ios-thumbs-up', 'md-thumbs-up', 'ios-thunderstorm', 'md-thunderstorm', 'ios-time', 'md-time', 'ios-timer', 'md-timer', 'ios-today', 'md-today', 'ios-train', 'md-train', 'ios-transgender', 'md-transgender', 'ios-trash', 'md-trash', 'ios-trending-down', 'md-trending-down', 'ios-trending-up', 'md-trending-up', 'ios-trophy', 'md-trophy', 'ios-tv', 'md-tv', 'ios-umbrella', 'md-umbrella', 'ios-undo', 'md-undo', 'ios-unlock', 'md-unlock', 'ios-videocam', 'md-videocam', 'ios-volume-high', 'md-volume-high', 'ios-volume-low', 'md-volume-low', 'ios-volume-mute', 'md-volume-mute', 'ios-volume-off', 'md-volume-off', 'ios-walk', 'md-walk', 'ios-wallet', 'md-wallet', 'ios-warning', 'md-warning', 'ios-watch', 'md-watch', 'ios-water', 'md-water', 'ios-wifi', 'md-wifi', 'ios-wine', 'md-wine', 'ios-woman', 'md-woman', 'logo-android', 'logo-angular', 'logo-apple', 'logo-bitbucket', 'logo-bitcoin', 'logo-buffer', 'logo-chrome', 'logo-closed-captioning', 'logo-codepen', 'logo-css3', 'logo-designernews', 'logo-dribbble', 'logo-dropbox', 'logo-euro', 'logo-facebook', 'logo-flickr', 'logo-foursquare', 'logo-freebsd-devil', 'logo-game-controller-a', 'logo-game-controller-b', 'logo-github', 'logo-google', 'logo-googleplus', 'logo-hackernews', 'logo-html5', 'logo-instagram', 'logo-ionic', 'logo-ionitron', 'logo-javascript', 'logo-linkedin', 'logo-markdown', 'logo-model-s', 'logo-no-smoking', 'logo-nodejs', 'logo-npm', 'logo-octocat', 'logo-pinterest', 'logo-playstation', 'logo-polymer', 'logo-python', 'logo-reddit', 'logo-rss', 'logo-sass', 'logo-skype', 'logo-slack', 'logo-snapchat', 'logo-steam', 'logo-tumblr', 'logo-tux', 'logo-twitch', 'logo-twitter', 'logo-usd', 'logo-vimeo', 'logo-vk', 'logo-whatsapp', 'logo-windows', 'logo-wordpress', 'logo-xbox', 'logo-xing', 'logo-yahoo', 'logo-yen', 'logo-youtube'];
		var template = '<div id="icon-%icon-id%" data-title="%icon-class%" class="card icon-example d-inline-flex justify-content-center align-items-center my-2 mx-2"><i class="ion ion-%icon% d-block"></i></div>'
		var $container = $('#ionicons-icons-container');

		for (var i = 0, l = icons.length; i < l; i++) {
		  $container.append($(template
			.replace(/%icon\-id%/g, icons[i])
			.replace(/%icon%/g, icons[i])
			.replace(/%icon\-class%/g, '.ion.ion-' + icons[i])
		  ));
		}
		this.searchIcons($("#ion-ios-icons-search"), icons, $container);
	},	
	linearIconInit: function(){
		var icons = ['home', 'apartment', 'pencil', 'magic-wand', 'drop', 'lighter', 'poop', 'sun', 'moon', 'cloud', 'cloud-upload', 'cloud-download', 'cloud-sync', 'cloud-check', 'database', 'lock', 'cog', 'trash', 'dice', 'heart', 'star', 'star-half', 'star-empty', 'flag', 'envelope', 'paperclip', 'inbox', 'eye', 'printer', 'file-empty', 'file-add', 'enter', 'exit', 'graduation-hat', 'license', 'music-note', 'film-play', 'camera-video', 'camera', 'picture', 'book', 'bookmark', 'user', 'users', 'shirt', 'store', 'cart', 'tag', 'phone-handset', 'phone', 'pushpin', 'map-marker', 'map', 'location', 'calendar-full', 'keyboard', 'spell-check', 'screen', 'smartphone', 'tablet', 'laptop', 'laptop-phone', 'power-switch', 'bubble', 'heart-pulse', 'construction', 'pie-chart', 'chart-bars', 'gift', 'diamond', 'linearicons', 'dinner', 'coffee-cup', 'leaf', 'paw', 'rocket', 'briefcase', 'bus', 'car', 'train', 'bicycle', 'wheelchair', 'select', 'earth', 'smile', 'sad', 'neutral', 'mustache', 'alarm', 'bullhorn', 'volume-high', 'volume-medium', 'volume-low', 'volume', 'mic', 'hourglass', 'undo', 'redo', 'sync', 'history', 'clock', 'download', 'upload', 'enter-down', 'exit-up', 'bug', 'code', 'link', 'unlink', 'thumbs-up', 'thumbs-down', 'magnifier', 'cross', 'menu', 'list', 'chevron-up', 'chevron-down', 'chevron-left', 'chevron-right', 'arrow-up', 'arrow-down', 'arrow-left', 'arrow-right', 'move', 'warning', 'question-circle', 'menu-circle', 'checkmark-circle', 'cross-circle', 'plus-circle', 'circle-minus', 'arrow-up-circle', 'arrow-down-circle', 'arrow-left-circle', 'arrow-right-circle', 'chevron-up-circle', 'chevron-down-circle', 'chevron-left-circle', 'chevron-right-circle', 'crop', 'frame-expand', 'frame-contract', 'layers', 'funnel', 'text-format', 'text-format-remove', 'text-size', 'bold', 'italic', 'underline', 'strikethrough', 'highlight', 'text-align-left', 'text-align-center', 'text-align-right', 'text-align-justify', 'line-spacing', 'indent-increase', 'indent-decrease', 'pilcrow', 'direction-ltr', 'direction-rtl', 'page-break', 'sort-alpha-asc', 'sort-amount-asc', 'hand', 'pointer-up', 'pointer-right', 'pointer-down', 'pointer-left'];
		var template = '<div id="icon-%icon-id%" data-title="%icon-class%" class="card icon-example d-inline-flex justify-content-center align-items-center my-2 mx-2"><i class="lnr lnr-%icon% d-block"></i></div>'
		var $container = $('#linearicon-icons-container');

		for (var i = 0, l = icons.length; i < l; i++) {
		  $container.append($(template
			.replace(/%icon\-id%/g, icons[i])
			.replace(/%icon%/g, icons[i])
			.replace(/%icon\-class%/g, '.lnr.lnr-' + icons[i])
		  ));
		}
		this.searchIcons($("#linear-icons-search"), icons, $container);
	},
	
	openIconicInit : function(){
		var icons = ['account-login', 'account-logout', 'action-redo', 'action-undo', 'align-center', 'align-left', 'align-right', 'aperture', 'arrow-bottom', 'arrow-circle-bottom', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-top', 'arrow-left', 'arrow-right', 'arrow-thick-bottom', 'arrow-thick-left', 'arrow-thick-right', 'arrow-thick-top', 'arrow-top', 'audio-spectrum', 'audio', 'badge', 'ban', 'bar-chart', 'basket', 'battery-empty', 'battery-full', 'beaker', 'bell', 'bluetooth', 'bold', 'bolt', 'book', 'bookmark', 'box', 'briefcase', 'british-pound', 'browser', 'brush', 'bug', 'bullhorn', 'calculator', 'calendar', 'camera-slr', 'caret-bottom', 'caret-left', 'caret-right', 'caret-top', 'cart', 'chat', 'check', 'chevron-bottom', 'chevron-left', 'chevron-right', 'chevron-top', 'circle-check', 'circle-x', 'clipboard', 'clock', 'cloud-download', 'cloud-upload', 'cloud', 'cloudy', 'code', 'cog', 'collapse-down', 'collapse-left', 'collapse-right', 'collapse-up', 'command', 'comment-square', 'compass', 'contrast', 'copywriting', 'credit-card', 'crop', 'dashboard', 'data-transfer-download', 'data-transfer-upload', 'delete', 'dial', 'document', 'dollar', 'double-quote-sans-left', 'double-quote-sans-right', 'double-quote-serif-left', 'double-quote-serif-right', 'droplet', 'eject', 'elevator', 'ellipses', 'envelope-closed', 'envelope-open', 'euro', 'excerpt', 'expand-down', 'expand-left', 'expand-right', 'expand-up', 'external-link', 'eye', 'eyedropper', 'file', 'fire', 'flag', 'flash', 'folder', 'fork', 'fullscreen-enter', 'fullscreen-exit', 'globe', 'graph', 'grid-four-up', 'grid-three-up', 'grid-two-up', 'hard-drive', 'header', 'headphones', 'heart', 'home', 'image', 'inbox', 'infinity', 'info', 'italic', 'justify-center', 'justify-left', 'justify-right', 'key', 'laptop', 'layers', 'lightbulb', 'link-broken', 'link-intact', 'list-rich', 'list', 'location', 'lock-locked', 'lock-unlocked', 'loop-circular', 'loop-square', 'loop', 'magnifying-glass', 'map-marker', 'map', 'media-pause', 'media-play', 'media-record', 'media-skip-backward', 'media-skip-forward', 'media-step-backward', 'media-step-forward', 'media-stop', 'medical-cross', 'menu', 'microphone', 'minus', 'monitor', 'moon', 'move', 'musical-note', 'paperclip', 'pencil', 'people', 'person', 'phone', 'pie-chart', 'pin', 'play-circle', 'plus', 'power-standby', 'print', 'project', 'pulse', 'puzzle-piece', 'question-mark', 'rain', 'random', 'reload', 'resize-both', 'resize-height', 'resize-width', 'rss-alt', 'rss', 'script', 'share-boxed', 'share', 'shield', 'signal', 'signpost', 'sort-ascending', 'sort-descending', 'spreadsheet', 'star', 'sun', 'tablet', 'tag', 'tags', 'target', 'task', 'terminal', 'text', 'thumb-down', 'thumb-up', 'timer', 'transfer', 'trash', 'underline', 'vertical-align-bottom', 'vertical-align-center', 'vertical-align-top', 'video', 'volume-high', 'volume-low', 'volume-off', 'warning', 'wifi', 'wrench', 'x', 'yen', 'zoom-in', 'zoom-out'];
		var template = '<div id="icon-%icon-id%" data-title="%icon-class%" class="card icon-example d-inline-flex justify-content-center align-items-center my-2 mx-2"><i class="oi oi-%icon% d-block"></i></div>'
		var $container = $('#open-iconic-icons-container');

		for (var i = 0, l = icons.length; i < l; i++) {
		  $container.append($(template
			.replace(/%icon\-id%/g, icons[i])
			.replace(/%icon%/g, icons[i])
			.replace(/%icon\-class%/g, '.oi.oi-' + icons[i])
		  ));
		}
		this.searchIcons($("#open-iconic-icons-search"), icons, $container);
	},
	
	strokeIcons7Init: function(){
		var icons = ['album', 'arc', 'back-2', 'bandaid', 'car', 'diamond', 'door-lock', 'eyedropper', 'female', 'gym', 'hammer', 'headphones', 'helm', 'hourglass', 'leaf', 'magic-wand', 'male', 'map-2', 'next-2', 'paint-bucket', 'pendrive', 'photo', 'piggy', 'plugin', 'refresh-2', 'rocket', 'settings', 'shield', 'smile', 'usb', 'vector', 'wine', 'cloud-upload', 'cash', 'close', 'bluetooth', 'cloud-download', 'way', 'close-circle', 'id', 'angle-up', 'wristwatch', 'angle-up-circle', 'world', 'angle-right', 'volume', 'angle-right-circle', 'users', 'angle-left', 'user-female', 'angle-left-circle', 'up-arrow', 'angle-down', 'switch', 'angle-down-circle', 'scissors', 'wallet', 'safe', 'volume2', 'volume1', 'voicemail', 'video', 'user', 'upload', 'unlock', 'umbrella', 'trash', 'tools', 'timer', 'ticket', 'target', 'sun', 'study', 'stopwatch', 'star', 'speaker', 'signal', 'shuffle', 'shopbag', 'share', 'server', 'search', 'film', 'science', 'disk', 'ribbon', 'repeat', 'refresh', 'add-user', 'refresh-cloud', 'paperclip', 'radio', 'note2', 'print', 'network', 'prev', 'mute', 'power', 'medal', 'portfolio', 'like2', 'plus', 'left-arrow', 'play', 'key', 'plane', 'joy', 'photo-gallery', 'pin', 'phone', 'plug', 'pen', 'right-arrow', 'paper-plane', 'delete-user', 'paint', 'bottom-arrow', 'notebook', 'note', 'next', 'news-paper', 'musiclist', 'music', 'mouse', 'more', 'moon', 'monitor', 'micro', 'menu', 'map', 'map-marker', 'mail', 'mail-open', 'mail-open-file', 'magnet', 'loop', 'look', 'lock', 'lintern', 'link', 'like', 'light', 'less', 'keypad', 'junk', 'info', 'home', 'help2', 'help1', 'graph3', 'graph2', 'graph1', 'graph', 'global', 'gleam', 'glasses', 'gift', 'folder', 'flag', 'filter', 'file', 'expand1', 'exapnd2', 'edit', 'drop', 'drawer', 'download', 'display2', 'display1', 'diskette', 'date', 'cup', 'culture', 'crop', 'credit', 'copy-file', 'config', 'compass', 'comment', 'coffee', 'cloud', 'clock', 'check', 'chat', 'cart', 'camera', 'call', 'calculator', 'browser', 'box2', 'box1', 'bookmarks', 'bicycle', 'bell', 'battery', 'ball', 'back', 'attention', 'anchor', 'albums', 'alarm', 'airplay'];
		var template = '<div id="icon-%icon-id%" data-title="%icon-class%" class="card icon-example d-inline-flex justify-content-center align-items-center my-2 mx-2"><i class="pe-7s-%icon% d-block"></i></div>'
		var $container = $('#stroke-icons-7-icons-container');

		for (var i = 0, l = icons.length; i < l; i++) {
		  $container.append($(template
			.replace(/%icon\-id%/g, icons[i])
			.replace(/%icon%/g, icons[i])
			.replace(/%icon\-class%/g, '.pe-7s-' + icons[i])
		  ));
		}
		this.searchIcons($("#icon-stroke7-icons-search"), icons, $container);
	},
	
	searchFontAwesomeIcons : function($s, icons, $container){
		$s.on('input', function() {
		  var val = String(this.value).replace(/^\s+|\s+$/g, '');

		  if (!val) return $container.find('> *').removeClass('d-none').addClass('d-inline-flex');
		  $container.find('> *').removeClass('d-inline-flex').addClass('d-none');
		  for (var j = 0, k = icons.length; j < k; j++) {
			if (icons[j][1].indexOf(val) !== -1) {
			  $('#icon-' + icons[j].join('-')).removeClass('d-none').addClass('d-inline-flex');
			}
		  }
		});
	},
	searchIcons : function($s, icons, $container){
		$s.on('input', function() {
		  var val = String(this.value).replace(/^\s+|\s+$/g, '');

		  if (!val) return $container.find('> *').removeClass('d-none').addClass('d-inline-flex');
		  $container.find('> *').removeClass('d-inline-flex').addClass('d-none');
		  for (var j = 0, k = icons.length; j < k; j++) {
			if (icons[j].indexOf(val) !== -1) {
			  $('#icon-' + icons[j]).removeClass('d-none').addClass('d-inline-flex');
			}
		  }
		});
	}
};

/*
* ===============================
* COMMONS
* ===============================
*/
var Commons ={
	
	slugify : function(string) {
	  const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
	  const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
	  const p = new RegExp(a.split('').join('|'), 'g')

	  return string.toString().toLowerCase()
		.replace(/\s+/g, '-') // Replace spaces with -
		.replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
		.replace(/&/g, '-and-') // Replace & with 'and'
		.replace(/[^\w\-]+/g, '') // Remove all non-word characters
		.replace(/\-\-+/g, '-') // Replace multiple - with single -
		.replace(/^-+/, '') // Trim - from start of text
		.replace(/-+$/, '') // Trim - from end of text
	}
}

