var $ = jQuery.noConflict();
var RecordId = '';
var ids = [];

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	//Menu active
	$('#select_orders').parent().removeClass('active');
	$('#select_orders').addClass('active');
	
	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
	$('input:checkbox').prop('checked',false);
	
    $(".checkAll").on("click", function () {
        $("input:checkbox").not(this).prop("checked", this.checked);
    });

	$("#view_by_status").val(0);
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });
	
});

function onCheckAll() {
    $(".checkAll").on("click", function () {
        $("input:checkbox").not(this).prop("checked", this.checked);
    });
}

function onPaginationDataLoad(page) {
	var status = $("#view_by_status").val();
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	
	$.ajax({
		url:base_url + "/backend/getOrdersTableData?page="+page+"&status="+status+"&start_date="+start_date+"&end_date="+end_date,
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onRefreshData() {
	var status = $("#view_by_status").val();
	
	$.ajax({
		url:base_url + "/backend/getOrdersTableData?status="+status,
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onSearch() {
	var search = $("#search").val();
	var status = $("#view_by_status").val();
	
	$.ajax({
		url: base_url + "/backend/getOrdersTableData?search="+search+"&status="+status,
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onFilterAction() {
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	
	$.ajax({
		url: base_url + "/backend/getOrdersTableData?start_date="+start_date+"&end_date="+end_date,
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onDataViewByStatus(status) {

	$("#view_by_status").val(status);
	
	$(".orderstatus").removeClass('active')
	$("#orderstatus_"+status).addClass('active');
	
	$.ajax({
		url: base_url + "/backend/getOrdersTableData?status="+status,
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onBulkAction() {
	
	ids = [];
	$('.selected_item:checked').each(function(){
		ids.push($(this).val());
	});

	if(ids.length == 0){
		var msg = TEXT["Please select record"];
		onErrorMsg(msg);
		return;
	}
	
	var order_status_id = $("#order_status_id").val();
	if(order_status_id == ''){
		var msg = TEXT["Please select action"];
		onErrorMsg(msg);
		return;
	}
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/bulkActionOrders',
		data: 'ids='+ids+'&order_status_id='+order_status_id,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onRefreshData();
				ids = [];
			}else{
				onErrorMsg(msg);
			}
			
			onCheckAll();
		}
    });
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
                onConfirmOrderStatus();
                return false;
            }
        }
    }
});

function onConfirmOrderStatus() {

	var update_btn = $('.update_btn').html();
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/updateOrderStatus',
		data: $('#DataEntry_formId').serialize(),
		beforeSend: function() {
			$('.update_btn').html('<span class="spinner-border spinner-border-sm"></span> Please Wait...');
		},
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onPaymentOrderStatusData();
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
			
			$('.update_btn').html(update_btn);
		}
	});
}
	
function onPaymentOrderStatusData() {
	var order_master_id = $("#order_master_id").val();
	
	$.ajax({
		url:base_url + "/backend/getPaymentOrderStatusData?order_master_id="+order_master_id,
		success:function(data){
			$("#payment_status_class").removeClass().addClass("pstatus_"+data.payment_status_id);
			$("#order_status_class").removeClass().addClass("ostatus_"+data.order_status_id);
			$("#pstatus_name").text(data.pstatus_name);
			$("#ostatus_name").text(data.ostatus_name);
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
		url: base_url + '/backend/deleteOrder',
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

function onExcelExport() {
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	var status = $("#view_by_status").val();
	
	var FinalPath = base_url + "/backend/orders-excel-export?status="+status+"&start_date="+start_date+"&end_date="+end_date;

	$.ajax({
		url:FinalPath,
		success:function(data){
			var filePath = base_url+'/public/export/'+data;
			window.open(filePath);
		}
	});
}

function onCSVExport() {
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	var status = $("#view_by_status").val();
	
	var FinalPath = base_url + "/backend/orders-csv-export?status="+status+"&start_date="+start_date+"&end_date="+end_date;
	
	$.ajax({
		url:FinalPath,
		success:function(data){
			var filePath = base_url+'/public/export/'+data;
			window.open(filePath);
		}
	});
}


