var RecordId = '';
var $ = jQuery.noConflict();
var media_type = 'Thumbnail';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#load_attachment").on('change', function() {
		upload_form();
    });
	
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
		url: base_url +"/backend/getMediaPaginationData?page="+page,
		success:function(data){
			$('#media_datalist').html(data);
		}
	});
}

function onMediaPaginationDataLoad() {
	$.ajax({
		url: base_url + "/backend/getMediaPaginationData",
		success:function(data){
			$('#media_datalist').html(data);
		}
	});
}

function onMediaSearch() {
	var search = $("#search").val();
	$.ajax({
		url: base_url + "/backend/getMediaPaginationData?search="+search,
		success:function(data){
			$('#media_datalist').html(data);
		}
	});
}

function onListPanel() {
    $('.btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    $('.btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
    $('.btn-form').hide();
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
		url: base_url + '/backend/mediaUpdate',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				$('#media_modal_view').modal('hide');
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

function onMediaModalView(id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMediaById',
		data: 'id='+id,
		success: function (response) {
			
			var data = response;

			$("#RecordId").val(data.id);
			$("#title").val(data.title);
			$("#alternative_text").val(data.alt_title);
			$("#thumbnail").val(public_path+'/media/'+data.thumbnail);
			$("#large_image").val(public_path+'/media/'+data.large_image);

			if(data.thumbnail != null){
				$("#media_preview_img").html('<img src="'+public_path+'/media/'+data.thumbnail+'">');
			}else{
				$("#media_preview_img").html('');
			}
			$('#media_modal_view').modal('show');
		}
    });
}

function onMediaDelete(id) {
	RecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmWhenDelete");	
}

function onConfirmWhenDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/onMediaDelete',
		data: 'id='+RecordId,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onMediaPaginationDataLoad();
			}else{
				onErrorMsg(msg);
			}
		}
    });
}

//upload attachment
function upload_form() {
	$("#upload-loader").show();
	
	var data = new FormData();
		data.append('FileName', $('#load_attachment')[0].files[0]);
		data.append('media_type', media_type);
	var ReaderObj = new FileReader();
	var imgname  =  $('#load_attachment').val();
	var size  =  $('#load_attachment')[0].files[0].size;

	var ext =  imgname.substr((imgname.lastIndexOf('.') +1));
	
	if(ext=='jpg' || ext=='JPG' || ext=='jpeg' || ext=='JPEG' || ext=='png' || ext=='PNG' || ext=='gif' || ext=='ico' || ext=='ICO' || ext=='svg' || ext=='SVG'){
		
		$.ajax({
			url: base_url + '/backend/MediaUpload',
			type: "POST",
			dataType : "json",
			data:  data,
			contentType: false,
			processData:false,
			enctype: 'multipart/form-data',
			mimeType:"multipart/form-data",
			success: function(response){

				var dataList = response;
				var msgType = dataList.msgType;
				var msg = dataList.msg;
				var thumbnail = dataList.thumbnail;
				var id = dataList.id;
				
				if (msgType == "success") {
					
					$("#upload-loader").hide();
					onSuccessMsg(msg);
					
					onMediaPaginationDataLoad();
				} else {
					onErrorMsg(msg);
				}
			},
			error: function(){
				return false;
			}				
		});
		
	}else{
		onErrorMsg(TEXT['Sorry only you can upload jpg, png and gif file type']);
	}
}
