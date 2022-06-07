var $ = jQuery.noConflict();
var RecordId = '';

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	
	
	$("#active-settings").addClass("active");
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });
	
	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
});

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getMediaSettingsTableData?page="+page,
		success:function(data){
			$('#media_settings_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getMediaSettingsTableData",
		success:function(data){
			$('#media_settings_datalist').html(data);
		}
	});
}

function onSearch() {
	var search = $("#search").val();
	$.ajax({
		url: base_url + "/backend/getMediaSettingsTableData?search="+search,
		success:function(data){
			$('#media_settings_datalist').html(data);
		}
	});
}

function onListPanel() {
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
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
		url: base_url + '/backend/MediaSettingsUpdate',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
				onRefreshData();
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
	onCustomModal(msg, "onMediaSettingsEditData");	
}

function onMediaSettingsEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMediaSettingsById',
		data: 'id=' + RecordId,
		success: function (response) {
			var datalist = response;
			
			$("#RecordId").val(datalist.id);
			$("#media_type").text(datalist.media_type+' Image sizes');
			$("#media_width").val(datalist.media_width);
			$("#media_height").val(datalist.media_height);
			
			onEditPanel();
        }
    });
}

