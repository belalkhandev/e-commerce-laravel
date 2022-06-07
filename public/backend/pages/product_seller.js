var $ = jQuery.noConflict();

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
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });

	$("#product_name").on("blur", function () {
		onProductSlug();
	});
	
    $("#load_image").on('change', function() {
		upload_form();
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
		url: base_url + '/seller/updateProductsData',
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
			url: base_url + '/seller/hasProductSlug',
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
		url: base_url + '/seller/getCategoryList',
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
		url: base_url + '/seller/getBrandList',
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

//upload Image
function upload_form() {

	var data = new FormData();
		data.append('FileName', $('#load_image')[0].files[0]);
		data.append('media_type', media_type);
	var ReaderObj = new FileReader();
	var imgname  =  $('#load_image').val();
	var size  =  $('#load_image')[0].files[0].size;

	var ext =  imgname.substr((imgname.lastIndexOf('.') +1));
	
	if(ext=='jpg' || ext=='JPG' || ext=='jpeg' || ext=='JPEG' || ext=='png' || ext=='PNG' || ext=='gif' || ext=='ico' || ext=='ICO' || ext=='svg' || ext=='SVG'){
		
		$.ajax({
			url: base_url + '/seller/MediaUpload',
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
					
					$("#f_thumbnail_thumbnail").val(thumbnail);
					$("#view_thumbnail_image").html('<img src="'+public_path+'/media/'+thumbnail+'">');

					$("#remove_f_thumbnail").show();
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

