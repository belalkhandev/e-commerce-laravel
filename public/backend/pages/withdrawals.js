var $ = jQuery.noConflict();
var RecordId = '';
var seller_id = '';

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
	
	$("#status_id").chosen();
	$("#status_id").trigger("chosen:updated");
	
	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
	$("#on_thumbnail").on("click", function () {
		onGlobalMediaModalView();
    });
	
	$("#media_select_file").on("click", function () {	
		var large_image = $("#large_image").val();
		$("#screenshot").val(large_image);
		$('#global_media_modal_view').modal('hide');
		onScreenshot();
    });
	
	$("#f_status_id").val(0).trigger("chosen:updated");
	$("#f_status_id").on("change", function () {
		onRefreshData();
	});
});

function onPaginationDataLoad(page) {

	$.ajax({
		url:base_url + "/backend/getWithdrawalsTableData?page="+page
		+"&search="+$("#search").val()
		+"&status_id="+$('#f_status_id').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getWithdrawalsTableData?search="+$("#search").val()
		+"&status_id="+$('#f_status_id').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	$.ajax({
		url: base_url + "/backend/getWithdrawalsTableData?search="+$("#search").val()
		+"&status_id="+$('#f_status_id').val(),
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
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	RecordId = '';
	seller_id = '';
	
	$('#amount').prop('readonly', false);
	
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
	$('#amount').prop('readonly', true);
	$('#fee_amount').prop('readonly', true);
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
		url: base_url + '/backend/saveWithdrawalsData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				resetForm("DataEntry_formId");
				onRefreshData();
				onListPanel();
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function onEdit(id, sellerid) {
	RecordId = id;
	seller_id = sellerid;
	var msg = TEXT["Do you really want to edit this record"];
	onCustomModal(msg, "onLoadEditData");	
}

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getWithdrawalById',
		data: 'id='+RecordId + "&seller_id="+seller_id,
		success: function (response) {
			var data = response.dataList;

			$("#RecordId").val(data.id);
			$("#amount").val(data.amount);
			$("#fee_amount").val(data.fee_amount);
			$("#payment_method").val(data.payment_method);
			$("#transaction_id").val(data.transaction_id);
			$("#description").val(data.description);
			
			$("#status_id").val(data.status_id).trigger("chosen:updated");
			
			$("#Current_Balance").text(response.CurrentBalance);
			$("#bank_info").html(response.bank_info);
			$("#seller_info").html(response.seller_info);
			getScreenshot();
			onEditPanel();
		}
    });
}

function onDelete(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmDelete");	
}

function onConfirmDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteWithdrawal',
		data: 'id='+RecordId,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onRefreshData();
			}else{
				onErrorMsg(msg);
			}
		}
    });
}

function onScreenshot() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveScreenshot',
		data: 'withdrawal_id='+RecordId + "&screenshot="+$("#screenshot").val(),
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;
			
			if (msgType == "success") {
				onSuccessMsg(msg);
				getScreenshot();
			} else {
				onErrorMsg(msg);
			}
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
		url: base_url + '/backend/getScreenshotById',
		data: 'withdrawal_id='+RecordId,
		success: function (response) {
			var datalist = response;

			var html = '';
			$.each(datalist, function (key, obj) {
				html += '<li>'
					+'<a class="screenshot" href="'+public_path+'/media/'+obj.images+'">'
						+'<img src="'+public_path+'/media/'+obj.images+'" alt="" />'
					+'</a>'
					+'<a onClick="onDeleteScreenshot(' + obj.id + ')" href="javascript:void(0);" class="delete_icon"><i class="fa fa-remove"></i></a>'
				+'</li>';
			});
			
			$("#screenshot_list").html(html);
			onMagnificPopupLoad();
		}
    });
}

function onDeleteScreenshot(id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteScreenshotById',
		data: 'id='+id,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;
			
			if (msgType == "success") {
				onSuccessMsg(msg);
				getScreenshot();
			} else {
				onErrorMsg(msg);
			}
		}
    });
}

