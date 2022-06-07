var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
    $("#new_account").on("click", function () {
		if($(this).is(":checked")){
			$("#new_account_pass").removeClass("hideclass");
			$("#password").attr("required", "");
			$("#password_confirmation").attr("required", "");
		}else if($(this).is(":not(:checked)")){
			$("#new_account_pass").addClass("hideclass");
			$("#password").removeAttr("required");
			$("#password_confirmation").removeAttr("required");
		}
    });
	
    $("#payment_method_stripe").on("click", function () {
		$("#pay_cod").addClass("hideclass");
		$("#pay_bank").addClass("hideclass");
		$("#pay_stripe").removeClass("hideclass");
    });
	
    $("#payment_method_cod").on("click", function () {
		$("#pay_stripe").addClass("hideclass");
		$("#pay_bank").addClass("hideclass");
		$("#pay_cod").removeClass("hideclass");
    });
	
    $("#payment_method_bank").on("click", function () {
		$("#pay_cod").addClass("hideclass");
		$("#pay_stripe").addClass("hideclass");
		$("#pay_bank").removeClass("hideclass");
    });
	
    $(".shipping_method").on("click", function () {
		var totalWithComma = $(this).data('total');
		var shippingfee = $(this).data('shippingfee');
		var total = totalWithComma.replace(/,/g, '');
		var TotalShippingfee = addCommas(parseFloat(total)+parseFloat(shippingfee));
		
		$(".shipping_fee").text(shippingfee);
		$(".total_amount").text(TotalShippingfee);
    });

	$("#checkout_submit_form").on("click", function () {
        $("#checkout_formid").submit();
    });
});

function addCommas(nStr){
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function showPerslyError() {
    $('.parsley-error-list').show();
}

jQuery('#checkout_formid').parsley({
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
                onConfirmMakeOrder();
                return false;
            }
        }
    }
});

function onConfirmMakeOrder() {

	var payment_method = $('input[name="payment_method"]:checked').val();

 	if(payment_method == 3){
		if(isenable_stripe == 1){
			if(validCardNumer == 0){
				$("span.payment_method_error").text(TEXT['Please type valid card number']);
				return;
			}
		}
	}else{
		$("span.payment_method_error").text('');
	}
	
	var checkout_btn = $('.checkout_btn').html();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/frontend/make_order',
		data: $('#checkout_formid').serialize(),
		beforeSend: function() {
			$('.checkout_btn').html('<span class="spinner-border spinner-border-sm"></span> Please Wait...');
		},
		success: function (response) {		
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				$("#checkout_formid").find('span.error-text').text('');
				
				if(payment_method == 3){
					if(isenable_stripe == 1){
						if(response.intent != ''){
							onConfirmPayment(response.intent, msg);
						}
					}
				}else{
					//onSuccessMsg(msg);
					window.location.href = base_url + '/thank';
				}

			} else {
				$.each(msg, function(prefix, val){
					if(prefix == 'oneError'){
						onErrorMsg(val[0]);
					}else{
						$('span.'+prefix+'_error').text(val[0]);
					}
				});
			}
			
			$('.checkout_btn').html(checkout_btn);
		}
	});
}