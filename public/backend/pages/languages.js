var $ = jQuery.noConflict();
var RecordId = '';
var language_code = '';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});	

	$(".tabs-nav li a.active").removeClass("active");
	$("#languages-nav").addClass("active");	
	$("#tabId-1").addClass("active");	
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });
	
	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
}); 

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
	$('#language_code').prop('readonly', false);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
	$('#language_code').prop('readonly', true);
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();	
}

function showPerslyError() {
    $('.parsley-error-list').show();
}

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getLanguagesTableData?page="+page,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getLanguagesTableData",
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	var search = $("#search").val();
	$.ajax({
		url: base_url + "/backend/getLanguagesTableData?search="+search,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
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
		url: base_url + '/backend/saveLanguagesData',
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
	onCustomModal(msg, "onLoadEditData");	
}

function onLoadEditData() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getLanguageById',
		data: 'RecordId=' + RecordId,
		success: function (response) {
			var datalist = response;
			
			$("#RecordId").val(datalist.id);
			$("#language_code").val(datalist.language_code);
			$("#old_language_code").val(datalist.language_code);
			$("#language_name").val(datalist.language_name);

            if (datalist.language_default == 1) {
                document.getElementById("language_default").checked = true;
            } else {
                document.getElementById("language_default").checked = false;
            }
			
            if (datalist.is_rtl == 1) {
                document.getElementById("is_rtl").checked = true;
            } else {
                document.getElementById("is_rtl").checked = false;
            }
			
            if (datalist.status == 1) {
                document.getElementById("status").checked = true;
            } else {
                document.getElementById("status").checked = false;
            }
			
			onEditPanel();
        }
    });
}

function onDelete(id, lang_code) {
	
	RecordId = id;
	language_code = lang_code;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmDelete");	
}

//Language Delete
function onConfirmDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteLanguage',
		data: 'RecordId='+RecordId+'&language_code='+language_code,
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

