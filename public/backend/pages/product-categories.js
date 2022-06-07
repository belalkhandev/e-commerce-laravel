var $ = jQuery.noConflict();
var RecordId = '';
var BulkAction = '';
var ids = [];

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

	$(document).on('click', '.ProductCategories nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
	$('input:checkbox').prop('checked',false);
	
    $(".checkAll").on("click", function () {
        $("input:checkbox").not(this).prop("checked", this.checked);
    });

	$("#is_featured").chosen();
	$("#is_featured").trigger("chosen:updated");
	
	$("#is_publish").chosen();
	$("#is_publish").trigger("chosen:updated");
	
	$("#on_thumbnail").on("click", function () {
		media_type = 'Product_Thumbnail';
		onGlobalMediaModalView();
    });
	
	$("#on_subheader_image").on("click", function () {
		media_type = 'Subheader';
		onGlobalMediaModalView();
    });
	
	$("#on_og_image").on("click", function () {
		media_type = 'SEO_Image';
		onGlobalMediaModalView();
    });
	
	$("#media_select_file").on("click", function () {
		
		var thumbnail = $("#thumbnail").val();

		if(media_type == 'Product_Thumbnail'){
			
			if(thumbnail !=''){
				$("#category_thumbnail").val(thumbnail);
				$("#view_category_thumbnail").html('<img src="'+public_path+'/media/'+thumbnail+'">');
			}
			
			$("#remove_category_thumbnail").show();
			
		} else if (media_type == 'Subheader') {
			if(thumbnail !=''){
				$("#subheader_image").val(thumbnail);
				$("#view_subheader_image").html('<img src="'+public_path+'/media/'+thumbnail+'">');
			}
			
			$("#remove_subheader_image").show();
			
		} else if (media_type == 'SEO_Image') {
			
			if(thumbnail !=''){
				$("#og_image").val(thumbnail);
				$("#view_og_image").html('<img src="'+public_path+'/media/'+thumbnail+'">');
			}
			
			$("#remove_og_image").show();
		}
		
		$('#global_media_modal_view').modal('hide');
    });
	
	$("#name").on("blur", function () {
		if(RecordId ==''){
			onCategorySlug();
		}
	});
	
	$("#language_code").val(0).trigger("chosen:updated");
	
	$("#language_code").on("change", function () {
		onRefreshData();
	});

});

function onCheckAll() {
    $(".checkAll").on("click", function () {
        $("input:checkbox").not(this).prop("checked", this.checked);
    });
}

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getProductCategoriesTableData?page="+page
		+"&search="+$("#search").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getProductCategoriesTableData?search="+$("#search").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onSearch() {
	$.ajax({
		url: base_url + "/backend/getProductCategoriesTableData?search="+$("#search").val()
		+"&language_code="+$('#language_code').val(),
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
	
	$("#lan").trigger("chosen:updated");
	$("#is_publish").trigger("chosen:updated");
	$("#is_subheader").trigger("chosen:updated");
}

function onListPanel() {
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("DataEntry_formId");
	RecordId = '';
	
	$("#remove_category_thumbnail").hide();
	$("#category_thumbnail").html('');
	
	$("#remove_subheader_image").hide();
	$("#subheader_image").html('');
	
	$("#remove_og_image").hide();
	$("#og_image").html('');
	
	$("#lan").trigger("chosen:updated");
	$("#is_publish").trigger("chosen:updated");
	$("#is_subheader").trigger("chosen:updated");
			
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();	
}

function onMediaImageRemove(type) {
	if(type == 'category_thumbnail'){
		
		$('#category_thumbnail').val('');
		$("#remove_category_thumbnail").hide();
		
	}else if(type == 'subheader_image'){
		
		$('#subheader_image').val('');
		$("#remove_subheader_image").hide();
		
	}else if(type == 'og_image'){
		$('#og_image').val('');
		$("#remove_og_image").hide();
	}
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
		url: base_url + '/backend/saveProductCategoriesData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				resetForm("DataEntry_formId");
				onRefreshData();
				onSuccessMsg(msg);
				onListPanel();
			} else {
				onErrorMsg(msg);
			}
			
			onCheckAll();
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
		url: base_url + '/backend/getProductCategoriesById',
		data: 'id='+RecordId,
		success: function (response) {
			var data = response;
			$("#RecordId").val(data.id);
			$("#name").val(data.name);
			$("#slug").val(data.slug);
				
			$("#lan").val(data.lan).trigger("chosen:updated");
			$("#is_publish").val(data.is_publish).trigger("chosen:updated");

			if(data.description != null){
				$("#description").val(data.description);
			}else{
				$("#description").val('');
			}

 			if(data.thumbnail != null){
				$("#category_thumbnail").val(data.thumbnail);
				$("#view_category_thumbnail").html('<img src="'+public_path+'/media/'+data.thumbnail+'">');
				$("#remove_category_thumbnail").show();
			}else{
				$("#category_thumbnail").val('');
				$("#view_category_thumbnail").html('');
				$("#remove_category_thumbnail").hide();
			}
			
			$("#is_subheader").val(data.is_subheader).trigger("chosen:updated");
 			
			if(data.subheader_image != null){
				$("#subheader_image").val(data.subheader_image);
				$("#view_subheader_image").html('<img src="'+public_path+'/media/'+data.subheader_image+'">');
				$("#remove_subheader_image").show();
			}else{
				$("#subheader_image").val('');
				$("#view_subheader_image").html('');
				$("#remove_subheader_image").hide();
			}
			
			if(data.og_title != null){
				$("#og_title").val(data.og_title);
			}else{
				$("#og_title").val('');
			}
			
			if(data.og_keywords != null){
				$("#og_keywords").val(data.og_keywords);
			}else{
				$("#og_keywords").val('');
			}
			
			if(data.og_description != null){
				$("#og_description").val(data.og_description);
			}else{
				$("#og_description").val('');
			}
			
			if(data.og_image != null){
				$("#og_image").val(data.og_image);
				$("#view_og_image").html('<img src="'+public_path+'/media/'+data.og_image+'">');
				$("#remove_og_image").show();
			}else{
				$("#og_image").val('');
				$("#view_og_image").html('');
				$("#remove_og_image").hide();
			}

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

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/deleteProductCategories',
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
	
	BulkAction = $("#bulk-action").val();
	if(BulkAction == ''){
		var msg = TEXT["Please select action"];
		onErrorMsg(msg);
		return;
	}
	
	if(BulkAction == 'publish'){
		var msg = TEXT["Do you really want to publish this records"];
	}else if(BulkAction == 'draft'){
		var msg = TEXT["Do you really want to draft this records"];
	}else if(BulkAction == 'delete'){
		var msg = TEXT["Do you really want to delete this records"];
	}
	
	onCustomModal(msg, "onConfirmBulkAction");	
}

function onConfirmBulkAction() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/bulkActionProductCategories',
		data: 'ids='+ids+'&BulkAction='+BulkAction,
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

//Category Slug
function onCategorySlug() {
	var StrName = $("#name").val();
	var str_name = StrName.trim();
	var strLength = str_name.length;
	if(strLength>0){
		$.ajax({
			type : 'POST',
			url: base_url + '/backend/hasProductCategorySlug',
			data: 'slug='+StrName,
			success: function (response) {
				var slug = response.slug;
				$("#slug").val(slug);
			}
		});
	}
}

