var $ = jQuery.noConflict();
var MediaRecordId = '';

$(function () {
	"use strict";

    $("#load_attachment").on('change', function() {
		upload_form();
    });

	$(document).on('click', '.global_media_pagination nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onGlobalMediaPaginationDataLoad(page);
	});

});
 
function onUploadListPanel() {
    $('.up-btn-form').show();
    $('#upload-form-panel, .up-btn-list').hide();
}

function onUploadFormPanel() {
    $('.up-btn-form').hide();
    $('#upload-form-panel, .up-btn-list').show();
}

function onGlobalMediaModalView() {
	onUploadListPanel();
	$("#tp_media_right").hide();
	$("#media_select_file").prop("disabled", true);
	$("ul.media-view li.active").removeClass("active");
	$('#global_media_modal_view').modal('show');
}

function onGlobalMediaModalDataLoad() {
	$.ajax({
		url:base_url + "/backend/getGlobalMediaData",
		success:function(data){
			$('#global_datalist').html(data);
		}
	});
}

function onGlobalMediaPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getGlobalMediaData?page="+page,
		success:function(data){
			$('#global_datalist').html(data);
		}
	});
}

function onGlobalMediaSearch() {
	var search = $("#global_media_search").val();
	$.ajax({
		url:base_url + "/backend/getGlobalMediaData?search="+search,
		success:function(data){
			$('#global_datalist').html(data);
		}
	});
}

function onMediaDelete(id) {
	MediaRecordId = id;
	var msg = TEXT["Do you really want to delete this record"];
	onCustomModal(msg, "onConfirmMediaDelete");	
}

function onConfirmMediaDelete() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/onMediaDelete',
		data: 'id='+MediaRecordId,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onGlobalMediaModalDataLoad();
			}else{
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

			$("#title").val(data.title);
			$("#alternative_text").val(data.alt_title);
			$("#thumbnail").val(data.thumbnail);
			$("#large_image").val(data.large_image);

			if(data.thumbnail != null){
				$("#media_preview_img").html('<img src="'+public_path+'/media/'+data.thumbnail+'">');
			}else{
				$("#media_preview_img").html('');
			}

			$("ul.media-view li.active").removeClass("active");
			$("#media_item_"+id).addClass("active");
			
			$("#tp_media_right").show();
			$("#media_select_file").prop("disabled", false);
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
			data: data,
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
					onGlobalMediaModalDataLoad();
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
