var $ = jQuery.noConflict();

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
	
	$("#media_select_file").on("click", function () {
		
		var large_image = $("#large_image").val();
		if(large_image !=''){
			$("#payment_gateway_icon").val(large_image);
			$("#view_payment_gateway_icon").html('<img src="'+public_path+'/media/'+large_image+'">');
		}

		$("#remove_payment_gateway_icon").show();
		$('#global_media_modal_view').modal('hide');
    });
	
});

function onMediaImageRemove(type) {
    $('#payment_gateway_icon').val('');
	$("#remove_payment_gateway_icon").hide();
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
		url: base_url + '/backend/saveThemeOptionsFooter',
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

