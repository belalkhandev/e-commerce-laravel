var $ = jQuery.noConflict();
var RecordId = '';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	//Menu active
	$('#select_product').parent().removeClass('active');
	$('#select_product').addClass('active');
	
	$(document).on('click', '.tp_pagination_modal nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationModalDataLoad(page);
	});
	
	$(document).on('click', '.tp_pagination nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
});

function onPaginationModalDataLoad(page) {
	$.ajax({
		url:base_url + "/seller/getProductListForRelatedTableData?page="+page+'&product_id='+product_id,
		success:function(data){
			$('#tp_datalist_modal').html(data);
		}
	});
}

function onSearchModal() {
	var search = $("#search_modal").val();
	$.ajax({
		url: base_url + "/seller/getProductListForRelatedTableData?search="+search+'&product_id='+product_id,
		success:function(data){
			$('#tp_datalist_modal').html(data);
		}
	});
}

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/seller/getRelatedProductTableData?page="+page+'&product_id='+product_id,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	var search = $("#search").val();
	$.ajax({
		url: base_url + "/seller/getRelatedProductTableData?search="+search+'&product_id='+product_id,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/seller/getRelatedProductTableData?product_id="+product_id,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onAddRelatedProductsModalView() {
	$('#global_media_modal_view').modal('show');
}

function onRelatedProduct(related_item_id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/seller/saveRelatedProductsData',
		data: 'product_id='+product_id+'&related_item_id='+related_item_id,
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			if (msgType == "success") {
				onSuccessMsg(msg);
				onRefreshData();
			} else {
				onErrorMsg(msg);
			}
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
		url: base_url + '/seller/deleteRelatedProduct',
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