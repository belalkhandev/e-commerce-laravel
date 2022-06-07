var $ = jQuery.noConflict();
var logo_type = '';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });

	$("#on_favicon").on("click", function () {
		logo_type = 'favicon';
		onGlobalMediaModalView();
    });
	
	$("#on_front_logo").on("click", function () {
		logo_type = 'front_logo';
		onGlobalMediaModalView();
    });
	
	$("#on_back_logo").on("click", function () {
		logo_type = 'back_logo';
		onGlobalMediaModalView();
    });
	
	$("#media_select_file").on("click", function () {
		
		var thumbnail = $("#thumbnail").val();

		if(logo_type == 'favicon'){
			
			if(thumbnail !=''){
				$("#favicon").val(thumbnail);
				$("#view_favicon").html('<img src="'+public_path+'/media/'+thumbnail+'">');
			}
			
			$("#remove_favicon").show();
			
		} else if (logo_type == 'front_logo') {
			if(thumbnail !=''){
				$("#front_logo").val(thumbnail);
				$("#view_front_logo").html('<img src="'+public_path+'/media/'+thumbnail+'">');
			}
			
			$("#remove_front_logo").show();
			
		} else if (logo_type == 'back_logo') {
			
			if(thumbnail !=''){
				$("#back_logo").val(thumbnail);
				$("#view_back_logo").html('<img src="'+public_path+'/media/'+thumbnail+'">');
			}
			
			$("#remove_back_logo").show();
		}
		
		$('#global_media_modal_view').modal('hide');
    });
});

function onMediaImageRemove(type) {
	if(type == 'favicon'){
		
		$('#favicon').val('');
		$("#remove_favicon").hide();
		
	}else if(type == 'front_logo'){
		
		$('#front_logo').val('');
		$("#remove_front_logo").hide();
		
	}else if(type == 'back_logo'){
		$('#back_logo').val('');
		$("#remove_back_logo").hide();
	}
}

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
		url: base_url + '/backend/saveThemeLogo',
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

