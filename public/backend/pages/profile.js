var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	onLoadEditData();

	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });

	$('.toggle-password').on('click', function() {
		$(this).toggleClass('fa-eye-slash');
			let input = $($(this).attr('toggle'));
		if (input.attr('type') == 'password') {
			input.attr('type', 'text');
		}else {
			input.attr('type', 'password');
		}
	});
	
	$("#remove_photo_thumbnail").hide();
	
	$("#on_thumbnail").on("click", function () {
		onGlobalMediaModalView();
    });
	
	$("#media_select_file").on("click", function () {
		
		var thumbnail = $("#thumbnail").val();

		if(thumbnail !=''){
			$("#photo_thumbnail").val(thumbnail);
			$("#view_photo_thumbnail").html('<img src="'+public_path+'/media/'+thumbnail+'">');
		}

		$("#remove_photo_thumbnail").show();
		$('#global_media_modal_view').modal('hide');
    });
	
}); 

function onMediaImageRemove(type) {
	$('#photo_thumbnail').val('');
	$("#remove_photo_thumbnail").hide();
}

function showPerslyError() {
    $('.parsley-error-list').show();
}

jQuery('#DataEntry_formId').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            else {
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
		url: base_url + '/backend/profileUpdate',
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

function onLoadEditData() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getUserById',
		data: 'id='+userid,
		success: function (response) {
			
			var data = response;
			
			var passtype = $('#password').attr('type');
			if(passtype == 'text'){
				$(".toggle-password").removeClass("fa-eye-slash");
				$(".toggle-password").addClass("fa-eye");
				$('#password').attr('type', 'password');
			}
	
			$("#RecordId").val(data.id);
			$("#name").val(data.name);
			$("#email").val(data.email);
			$("#password").val(data.bactive);
			$("#phone").val(data.phone);
			$("#address").val(data.address);
			
 			if(data.photo != null){
				$("#photo_thumbnail").val(data.photo);
				$("#view_photo_thumbnail").html('<img src="'+public_path+'/media/'+data.photo+'">');
				$("#remove_photo_thumbnail").show();
			}else{
				$("#photo_thumbnail").val('');
				$("#view_photo_thumbnail").html('');
				$("#remove_photo_thumbnail").hide();
			}       
		}
    });
}
