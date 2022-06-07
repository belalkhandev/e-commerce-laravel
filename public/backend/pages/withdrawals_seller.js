var $ = jQuery.noConflict();
var RecordId = '';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	resetForm("DataEntry_formId");
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });

	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
	onCurrentBalance();
	
});

function onPaginationDataLoad(page) {

	$.ajax({
		url:base_url + "/seller/getWithdrawalsTableData?page="+page
		+"&search="+$("#search").val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/seller/getWithdrawalsTableData?search="+$("#search").val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	$.ajax({
		url: base_url + "/seller/getWithdrawalsTableData?search="+$("#search").val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

function onListPanel() {
	$("#screenshot_id").hide();
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	RecordId = '';
	$("#screenshot_id").hide();
	$('#amount').prop('readonly', false);
	
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
	$("#screenshot_id").hide();
	$('#amount').prop('readonly', true);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();	
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
		url: base_url + '/seller/saveWithdrawalsData',
		data: $('#DataEntry_formId').serialize()+"&ubalance="+$("#ubalance").val(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				resetForm("DataEntry_formId");
				onCurrentBalance();
				onRefreshData();
				onListPanel();
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function onEdit(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to edit this record"];
	onCustomModal(msg, "onLoadEditData");	
}

function onLoadEditData() {
	
    $.ajax({
		type : 'POST',
		url: base_url + '/seller/getWithdrawalById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;
			$("#RecordId").val(data.id);
			$("#amount").val(data.amount);
			$("#fee_amount").val(data.fee_amount);
			$("#payment_method").val(data.payment_method);
			$("#transaction_id").val(data.transaction_id);
			$("#description").val(data.description);

			onEditPanel();
			getScreenshot();
		}
    });
}

function onCurrentBalance() {

    $.ajax({
		type : 'POST',
		url: base_url + '/seller/getCurrentBalanceBySellerId',
		success: function (response) {
			var data = response;
			$("#Current_Balance").text(data.CurrentBalance);
			$("#ubalance").val(data.ubalance);
		}
    });
}

function onMagnificPopupLoad() {
	$('.screenshot').magnificPopup({
		type: 'image',
		gallery: {
			enabled: true
		}
	});	
}

function getScreenshot() {

    $.ajax({
		type : 'POST',
		url: base_url + '/seller/getScreenshotById',
		data: 'withdrawal_id='+RecordId,
		success: function (response) {
			var datalist = response;

			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<li>'
					+'<a class="screenshot" href="'+public_path+'/media/'+obj.images+'">'
						+'<img src="'+public_path+'/media/'+obj.images+'" alt="" />'
					+'</a>'
				+'</li>';
			});
			
			$("#screenshot_list").html(html);
			onMagnificPopupLoad();
			$("#screenshot_id").show();
		}
    });
}
