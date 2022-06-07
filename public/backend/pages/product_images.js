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
	
	$(document).on('click', '.tp_pagination nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
	$("#media_select_file").on("click", function () {
		
		var thumbnail = $("#thumbnail").val();
		var large_image = $("#large_image").val();
		if(thumbnail !=''){
			$("#pro_thumbnail").val(thumbnail);
		}
		
		if(large_image !=''){
			$("#pro_large_image").val(large_image);
		}
		$('#global_media_modal_view').modal('hide');
    });
});

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getProductImagesTableData?page="+page+'&id='+product_id,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getProductImagesTableData?id="+product_id,
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
		url: base_url + '/backend/saveProductImagesData',
		data: $('#DataEntry_formId').serialize()+'&product_id='+product_id,
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			if (msgType == "success") {
				resetForm("DataEntry_formId");
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
		url: base_url + '/backend/deleteProductImages',
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
