var $ = jQuery.noConflict();

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	
	
	$("#active-settings").addClass("active");

	$("#submit-form").on("click", function () {
        $("#submit_formId").submit();
    });
	
	$("#mailer").on("change", function () {
		var mailer = $("#mailer").val();
		if(mailer == 'smtp'){
			$("#is_smtp").show();
		}else{
			$("#is_smtp").hide();
		}
	});

	$("#mailer").chosen();
	$("#mailer").trigger("chosen:updated");	
	
	$("#smtp_security").chosen();
	$("#smtp_security").trigger("chosen:updated");	
	
});

function showPerslyError() {
    $('.parsley-error-list').show();
}

jQuery('#submit_formId').parsley({
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
	var ismail = $('#ismail').is(':checked');
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveMailSettings',
		data: $('#submit_formId').serialize() + '&ismail='+ismail,
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
