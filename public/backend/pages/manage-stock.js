var $ = jQuery.noConflict();
var RecordId = '';

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

	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});

	$("#view_by_status").val(2);
	
	$("#language_code").val(0).trigger("chosen:updated");
	$("#language_code").on("change", function () {
		onRefreshData();
	});
});

function onPaginationDataLoad(page) {

	$.ajax({
		url:base_url + "/backend/getManageStockTableData?page="+page
		+"&status="+$("#view_by_status").val()
		+"&search="+$("#search").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {

	$.ajax({
		url:base_url + "/backend/getManageStockTableData?status="+$("#view_by_status").val()
		+"&search="+$("#search").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {

	$.ajax({
		url: base_url + "/backend/getManageStockTableData?search="+$("#search").val()
		+"&status="+$("#view_by_status").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onDataViewByStatus(status) {

	$("#view_by_status").val(status);
	
	$(".viewstatus").removeClass('active')
	$("#viewstatus_"+status).addClass('active');
	
	$.ajax({
		url: base_url + "/backend/getManageStockTableData?status="+$("#view_by_status").val()
		+"&search="+$("#search").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onListPanel() {
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
	RecordId = '';

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
		url: base_url + '/backend/saveManageStockData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onRefreshData();
				onSuccessMsg(msg);
				onListPanel();
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
		url: base_url + '/backend/getProductById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;
			$("#RecordId").val(data.id);
			$("#is_stock").val(data.is_stock).trigger("chosen:updated");
			$("#stock_status_id").val(data.stock_status_id).trigger("chosen:updated");
			$("#sku").val(data.sku);
			$("#stock_qty").val(data.stock_qty);

			onEditPanel();
		}
    });
}
