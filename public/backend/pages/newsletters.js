var $ = jQuery.noConflict();
var RecordId = '';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	

	$("#subscribe_popup_submit_form").on("click", function () {
        $("#subscribe_popup_formId").submit();
    });
	
	$("#mailchimp_submit_form").on("click", function () {
        $("#mailchimp_formId").submit();
    });
	
	$("#subscriber_submit_form").on("click", function () {
        $("#subscriber_formId").submit();
    });
	
	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
	$("#status").chosen();
	$("#status").trigger("chosen:updated");
	
}); 

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
	
	$("#status").trigger("chosen:updated");
}

function onListPanel() {
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("subscriber_formId");
	RecordId = '';
	$("#status").trigger("chosen:updated");
	
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();	
}

function showPerslyError() {
    $('.parsley-error-list').show();
}

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getSubscriberTableData?page="+page,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getSubscriberTableData",
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	var search = $("#search").val();
	$.ajax({
		url: base_url + "/backend/getSubscriberTableData?search="+search,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

jQuery('#subscribe_popup_formId').parsley({
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
                onSubscribePopupConfirmWhenAddEdit();
                return false;
            }
        }
    }
});

function onSubscribePopupConfirmWhenAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SubscribePopupUpdate',
		data: $('#subscribe_popup_formId').serialize(),
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

jQuery('#mailchimp_formId').parsley({
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
                onMailChimpConfirmWhenAddEdit();
                return false;
            }
        }
    }
});

function onMailChimpConfirmWhenAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/MailChimpSettingsUpdate',
		data: $('#mailchimp_formId').serialize(),
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

jQuery('#subscriber_formId').parsley({
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
                onSubscriberConfirmWhenAddEdit();
                return false;
            }
        }
    }
});

function onSubscriberConfirmWhenAddEdit() {
	
	var subscriber_btn = $('.subscriber_btn').html();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveSubscriberData',
		data: $('#subscriber_formId').serialize(),
		beforeSend: function() {
			$('.subscriber_btn').html('<span class="spinner-border spinner-border-sm"></span> Please Wait...');
		},
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
				resetForm("subscriber_formId");
				onRefreshData();
				onListPanel();
			} else {
				onErrorMsg(msg);
			}
			
			$('.subscriber_btn').html(subscriber_btn);
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
		url: base_url + '/backend/getSubscriberById',
		data: 'id=' + RecordId,
		success: function (response) {
			var datalist = response;
			
			$("#RecordId").val(datalist.id);
			$("#email_address").val(datalist.email_address);
			$("#status").val(datalist.status).trigger("chosen:updated");

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
	
	var please_wait = $('.please_wait').html();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteSubscriber',
		data: 'id='+RecordId,
		beforeSend: function() {
			$('.please_wait').html('<span class="spinner-border spinner-border-sm"></span> Please Wait...');
		},
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onRefreshData();
			}else{
				onErrorMsg(msg);
			}
			$('.please_wait').html(please_wait);
		}
    });
}
