var $ = jQuery.noConflict();

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	
	
	$("#active-settings").addClass("active");

	$("#recaptcha-submit-form").on("click", function () {
        $("#GoogleRecaptcha_formId").submit();
    });
	
});

function showPerslyError() {
    $('.parsley-error-list').show();
}

jQuery('#GoogleRecaptcha_formId').parsley({
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
                onGoogleRecaptchaUpdate();
                return false;
            }
        }
    }
});

function onGoogleRecaptchaUpdate() {
	var recaptcha = $('#recaptcha').is(':checked');
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/GoogleRecaptchaUpdate',
		data: $('#GoogleRecaptcha_formId').serialize() + '&recaptcha='+recaptcha,
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
