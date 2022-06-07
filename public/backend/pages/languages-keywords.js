var $ = jQuery.noConflict();
var RecordId = '';

$(function () {
	"use strict";
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(".tabs-nav li a.active").removeClass("active");
	$("#languages-nav").addClass("active");	
	$("#tabId-2").addClass("active");	
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });
	
	$("#language_code").on("change", function () {
		onRefreshData();
	});
	
	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});	
	
	onRefreshData();
	
});

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getLanguageKeywordsTableData?page="+page+"&search="+$("#search").val()+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getLanguageKeywordsTableData?search="+$("#search").val()+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	$.ajax({
		url: base_url + "/backend/getLanguageKeywordsTableData?search="+$("#search").val()+"&language_code="+$('#language_code').val(),
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
	$('.filter').show();
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	RecordId = '';
	$('.filter').hide();
	$('#language_key').prop('readonly', false);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
	$('.filter').hide();
	$('#language_key').prop('readonly', true);
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
		url: base_url + '/backend/saveLanguageKeywordsData',
		data: $('#DataEntry_formId').serialize()+'&language_code='+$("#language_code").val(),
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
	onCustomModal(msg, "onLoadEditData");	
}

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getLanguageKeywordsById',
		data: 'RecordId=' + RecordId,
		success: function (response) {
			var datalist = response;
			
			$("#RecordId").val(datalist.id);
			$("#language_key").val(datalist.language_key);
			$("#language_value").val(datalist.language_value);

			onEditPanel();
        }
    });
}

function onDelete(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmDelete");	
}

//Language Delete
function onConfirmDelete() {
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteLanguageKeywords',
		data: 'RecordId='+RecordId+'&language_code='+$("#language_code").val(),
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

