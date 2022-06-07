var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	//Menu active
	$('#select_product').parent().removeClass('active');
	$('#select_product').addClass('active');
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });
	
    $("#load_image").on('change', function() {
		upload_form();
    });
	
});

function showPerslyError() {
    $('.parsley-error-list').show();
}

jQuery('#DataEntry_formId').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            } else {
                showPerslyError();
                return false;
            }
        },
        onFormSubmit: function (isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEdit();
                return false;
            }
        }
    }
});

function onConfirmWhenAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/seller/saveProductSEOData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			if (msgType == "success") {
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

//upload Image
function upload_form() {

	var data = new FormData();
		data.append('FileName', $('#load_image')[0].files[0]);
		data.append('media_type', media_type);
	var ReaderObj = new FileReader();
	var imgname  =  $('#load_image').val();
	var size  =  $('#load_image')[0].files[0].size;

	var ext =  imgname.substr((imgname.lastIndexOf('.') +1));
	
	if(ext=='jpg' || ext=='JPG' || ext=='jpeg' || ext=='JPEG' || ext=='png' || ext=='PNG' || ext=='gif' || ext=='ico' || ext=='ICO' || ext=='svg' || ext=='SVG'){
		
		$.ajax({
			url: base_url + '/seller/MediaUpload',
			type: "POST",
			dataType : "json",
			data: data,
			contentType: false,
			processData:false,
			enctype: 'multipart/form-data',
			mimeType:"multipart/form-data",
			success: function(response){

				var dataList = response;
				var msgType = dataList.msgType;
				var msg = dataList.msg;
				var thumbnail = dataList.thumbnail;
				var id = dataList.id;
				
				if (msgType == "success") {
					if(thumbnail !=''){
						$("#og_image").val(thumbnail);
						$("#view_og_image").html('<img src="'+public_path+'/media/'+thumbnail+'">');
					}

					$("#remove_og_image").show();
				} else {
					onErrorMsg(msg);
				}
			},
			error: function(){
				return false;
			}				
		});
		
	}else{
		onErrorMsg(TEXT['Sorry only you can upload jpg, png and gif file type']);
	}
}
