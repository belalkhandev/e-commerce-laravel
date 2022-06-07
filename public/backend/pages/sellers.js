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
	resetForm("bankInformation_formId");
	
	$("#submit-form").on("click", function () {
        $("#DataEntry_formId").submit();
    });
	
	$("#bank_information_submit_form").on("click", function () {
        $("#bankInformation_formId").submit();
    });
	
	$(document).on('click', '.users_pagination nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
		
	$('input:checkbox').prop('checked',false);
	
    $(".checkAll").on("click", function () {
        $("input:checkbox").not(this).prop("checked", this.checked);
    });

	$("#status_id").chosen();
	$("#status_id").trigger("chosen:updated");
	
	$('.toggle-password').on('click', function() {
		$(this).toggleClass('fa-eye-slash');
			let input = $($(this).attr('toggle'));
		if (input.attr('type') == 'password') {
			input.attr('type', 'text');
		}else {
			input.attr('type', 'password');
		}
	});
	
	$("#on_thumbnail").on("click", function () {
		onGlobalMediaModalView();
    });
	
	$("#media_select_file").on("click", function () {	
		var thumbnail = $("#thumbnail").val();

		if(thumbnail !=''){
			$("#photo_thumbnail").val(thumbnail);
			$("#view_photo_thumbnail").html('<img src="'+public_path+'/media/'+thumbnail+'">');
		}

		$("#remove_photo_thumbnail").show();
		$('#global_media_modal_view').modal('hide');
    });
	
	$("#view_by_status").val(0);
	
});

function onCheckAll() {
    $(".checkAll").on("click", function () {
        $("input:checkbox").not(this).prop("checked", this.checked);
    });
}

function onPaginationDataLoad(page) {
	$.ajax({
		url:base_url + "/backend/getSellersTableData?page="+page+"&search="+$("#search").val()+"&status="+$("#view_by_status").val(),
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onRefreshData() {
	$.ajax({
		url:base_url + "/backend/getSellersTableData?search="+$("#search").val()+"&status="+$("#view_by_status").val(),
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onSearch() {

	$.ajax({
		url: base_url + "/backend/getSellersTableData?search="+$("#search").val()+"&status="+$("#view_by_status").val(),
		success:function(data){
			$('#tp_datalist').html(data);
			onCheckAll();
		}
	});
}

function onDataViewByStatus(status) {

	$("#view_by_status").val(status);
	
	$(".orderstatus").removeClass('active')
	$("#orderstatus_"+status).addClass('active');
	
	$.ajax({
		url: base_url + "/backend/getSellersTableData?status="+$("#view_by_status").val()+"&search="+$("#search").val(),
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
	
	$("#status_id").trigger("chosen:updated");
}

function onListPanel() {
	$('.parsley-error-list').hide();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
	var passtype = $('#password').attr('type');
	if(passtype == 'text'){
		$(".toggle-password").removeClass("fa-eye-slash");
		$(".toggle-password").addClass("fa-eye");
		$('#password').attr('type', 'password');
	}
	
    resetForm("DataEntry_formId");
    resetForm("bankInformation_formId");
	RecordId = '';
	
	$("#status_id").trigger("chosen:updated");
	
	$("#remove_photo_thumbnail").hide();
	$("#photo_thumbnail").html('');
	
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
	
	onDetailsBankInfo(1);
	$("#details_bank_info_2").hide();
	
	$(".error_available").html('');
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
	
	$("#details_bank_info_2").show();
	$(".error_available").html('');
}

function onMediaImageRemove(type) {
	$('#photo_thumbnail').val('');
	$("#remove_photo_thumbnail").hide();
}

function onDetailsBankInfo(id) {
	if(id == 1){
		$("#bank_information").hide();
		$('#details').show();
		$(".details_bank_info").removeClass("active");
		$("#details_bank_info_1").addClass("active");
	}else{
		$('#details').hide();
		$("#bank_information").show();
		$(".details_bank_info").removeClass("active");
		$("#details_bank_info_2").addClass("active");
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
		url: base_url + '/backend/saveSellersData',
		data: $('#DataEntry_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			var id = response.id;
			$("#RecordId").val(id);
			$("#seller_id").val(id);
			if (msgType == "success") {
				
				if(RecordId == ''){
					RecordId = id;
					$('#details').hide();
					$("#bank_information").show();
					$("#details_bank_info_2").show();
					$(".details_bank_info").removeClass("active");
					$("#details_bank_info_2").addClass("active");
				}

				onRefreshData();
				onSuccessMsg(msg);

			} else {
				onErrorMsg(msg);
			}
			
			onCheckAll();
		}
	});
}

jQuery('#bankInformation_formId').parsley({
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
                onBankInformationAddEdit();
                return false;
            }
        }
    }
});

function onBankInformationAddEdit() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/saveBankInformationData',
		data: $('#bankInformation_formId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;
			var id = response.id;
			$("#bank_information_id").val(id);
			if (msgType == "success") {
				onRefreshData();
				onSuccessMsg(msg);
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
		url: base_url + '/backend/getSellerById',
		data: 'id='+RecordId,
		success: function (response) {

			var seller_data = response.seller_data;
			var bank_info_data = response.bank_information;

			var passtype = $('#password').attr('type');
			if(passtype == 'text'){
				$(".toggle-password").removeClass("fa-eye-slash");
				$(".toggle-password").addClass("fa-eye");
				$('#password').attr('type', 'password');
			}
			$("#seller_id").val(seller_data.id);
			$("#RecordId").val(seller_data.id);
			$("#name").val(seller_data.name);
			$("#email").val(seller_data.email);
			$("#password").val(seller_data.bactive);
			$("#phone").val(seller_data.phone);
			$("#shop_name").val(seller_data.shop_name);
			$("#shop_url").val(seller_data.shop_url);
			$("#shopurl").text(seller_data.shop_url);
			$("#shop_url_id").text(seller_data.id);
			
			$("#address").val(seller_data.address);
			$("#city").val(seller_data.city);
			$("#state").val(seller_data.state);
			$("#zip_code").val(seller_data.zip_code);
			$("#country_id").val(seller_data.country_id).trigger("chosen:updated");
			$("#status_id").val(seller_data.status_id).trigger("chosen:updated");
 			
			if(seller_data.photo != null){
				$("#photo_thumbnail").val(seller_data.photo);
				$("#view_photo_thumbnail").html('<img src="'+public_path+'/media/'+seller_data.photo+'">');
				$("#remove_photo_thumbnail").show();
			}else{
				$("#photo_thumbnail").val('');
				$("#view_photo_thumbnail").html('');
				$("#remove_photo_thumbnail").hide();
			}
			
			if(seller_data.status_id == 1){
				$("#seller_status").removeClass("inactive").addClass("active");
				$("#seller_status").text(TEXT['Active']);
			}else{
				$("#seller_status").removeClass("active").addClass("inactive");
				$("#seller_status").text(TEXT['Inactive']);
			}
			
			if(bank_info_data != null){
				$("#bank_name").val(bank_info_data.bank_name);
				$("#bank_code").val(bank_info_data.bank_code);
				$("#account_number").val(bank_info_data.account_number);
				$("#account_holder").val(bank_info_data.account_holder);
				$("#paypal_id").val(bank_info_data.paypal_id);
				$("#description").val(bank_info_data.description);
				$("#bank_information_id").val(bank_info_data.id);
			}else{
				$("#bank_name").val('');
				$("#bank_code").val('');
				$("#account_number").val('');
				$("#account_holder").val('');
				$("#paypal_id").val('');
				$("#description").val('');
				$("#bank_information_id").val('');
			}
			
			$("#created_at").text(seller_data.created_at);
			$("#Current_Balance").text(response.CurrentBalance);
			$("#OrderBalance").text(response.OrderBalance);
			$("#WithdrawalBalance").text(response.WithdrawalBalance);
			$("#TotalProducts").text(response.TotalProducts);
			
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
		url: base_url + '/backend/deleteSeller',
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
	
	if(BulkAction == 'active'){
		var msg = TEXT["Do you really want to active this records"];
	}else if(BulkAction == 'inactive'){
		var msg = TEXT["Do you really want to inactive this records"];
	}else if(BulkAction == 'delete'){
		var msg = TEXT["Do you really want to delete this records"];
	}
	
	onCustomModal(msg, "onConfirmBulkAction");	
}

function onConfirmBulkAction() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/bulkActionSellers',
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

$("#shop_url").on("blur", function () {
	var shop_url = $("#shop_url").val();
	var str_name = shop_url.trim();
	var strLength = str_name.length;
	if(strLength>0){
		$.ajax({
			type : 'POST',
			url: base_url + '/frontend/hasShopSlug',
			data: 'shop_url='+shop_url,
			success: function (response) {
				var slug = response.slug;
				$("#shop_url").val(slug);
			}
		});
	}
});