var $ = jQuery.noConflict();

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

	$("#product_name").on("blur", function () {
		onProductSlug();
	});

	$("#media_select_file").on("click", function () {
		
		var thumbnail = $("#thumbnail").val();
		if(thumbnail !=''){
			$("#f_thumbnail_thumbnail").val(thumbnail);
			$("#view_thumbnail_image").html('<img src="'+public_path+'/media/'+thumbnail+'">');
		}

		$("#remove_f_thumbnail").show();
		$('#global_media_modal_view').modal('hide');
    });
	
	$("#brand_id").chosen();
	$("#brand_id").trigger("chosen:updated");
	
	$("#cat_id").chosen();
	$("#cat_id").trigger("chosen:updated");
	
	$("#collection_id").chosen();
	$("#collection_id").trigger("chosen:updated");
	
	$("#label_id").chosen();
	$("#label_id").trigger("chosen:updated");
	
	$("#tax_id").chosen();
	$("#tax_id").trigger("chosen:updated");
	
	$("#is_featured").chosen();
	$("#is_featured").trigger("chosen:updated");
	
	$("#lan").chosen();
	$("#lan").trigger("chosen:updated");
	
	$("#is_publish").chosen();
	$("#is_publish").trigger("chosen:updated");
	
	$("#lan").on("change", function () {
		onCategoryList();
		onBrandList();
	});
	
	//Summernote
	$('#description').summernote({
		tabDisable: false,
		height: 300,
		toolbar: [
		  ['style', ['style']],
		  ['font', ['bold', 'italic', 'underline', 'clear']],
		  ['para', ['ul', 'ol', 'paragraph']],
		  ['table', ['table']],
		  ['insert', ['link', 'unlink']],
		  ['misc', ['undo', 'redo']],
		  ['view', ['codeview', 'help']]
		]
	});	
});

function onMediaImageRemove(type) {
    $('#f_thumbnail_thumbnail').val('');
	$("#remove_f_thumbnail").hide();
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
		url: base_url + '/backend/updateProductsData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			if (msgType == "success") {
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

//Product Slug
function onProductSlug() {
	var StrName = $("#product_name").val();
	var str_name = StrName.trim();
	var strLength = str_name.length;
	if(strLength>0){
		$.ajax({
			type : 'POST',
			url: base_url + '/backend/hasProductSlug',
			data: 'slug='+StrName,
			success: function (response) {
				var slug = response.slug;
				$("#slug").val(slug);
			}
		});
	}
}

function onCategoryList() {
	
	$.ajax({
		type : 'POST',
		url: base_url + '/backend/getCategoryList',
		data: 'lan='+$('#lan').val(),
		success: function (data) {
			var html = '<option value="" selected="selected">'+TEXT['Select Category']+'</option>';
			$.each(data, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.name + '</option>';
			});
			
			$("#cat_id").html(html);
			$("#cat_id").chosen();
			$("#cat_id").trigger("chosen:updated");
		}
	});
}

function onBrandList() {
	
	$.ajax({
		type : 'POST',
		url: base_url + '/backend/getBrandList',
		data: 'lan='+$('#lan').val(),
		success: function (data) {
			var html = '<option value="0" selected="selected">No Brand</option>';
			$.each(data, function (key, obj) {
				html += '<option value="' + obj.id + '">' + obj.name + '</option>';
			});
			
			$("#brand_id").html(html);
			$("#brand_id").chosen();
			$("#brand_id").trigger("chosen:updated");
		}
	});
}