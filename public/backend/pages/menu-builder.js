var $ = jQuery.noConflict();
var submitformid = '';

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$("#submit-custom-link-form").on("click", function () {
        $("#CustomLinkDataFormId").submit();
    });
	
	$("#submit_MegaMenuTitle_form").on("click", function () {
        $("#MegaMenuTitle_FormId").submit();
    });
	
	$(document).on("click", "input.child_menu_type", function(event) {
		var child_menu_type = $(this).val();
		var childMenuTypeId = $(this)[0].id;

		var CMTIdArr = childMenuTypeId.split("-");
		var child_menu_type_id = CMTIdArr[1];

		if(child_menu_type == 'mega_menu'){
			$("#mega_menu_settings_hide_show"+child_menu_type_id).show();
		}else{
			$("#mega_menu_settings_hide_show"+child_menu_type_id).hide();
		}
	});
	
	$(document).on("click", "input.width_type", function(event) {
		var width_type = $(this).val();
		var widthTypeId = $(this)[0].id;
		
		var widthTypeArr = widthTypeId.split("-");
		var width_type_id = widthTypeArr[1];
		
		if(width_type == 'fixed_width'){
			$("#width_type_hide_show"+width_type_id).show();
		}else{
			$("#width_type_hide_show"+width_type_id).hide();
		}
	});
	
	//Menu Settings Submit Form
	$(document).on("click", ".MenuSettingsSubmitForm", function(event) {
		event.preventDefault(); 
		
		var submitformid = $(this).data("submitformid");
		var res = submitformid.split("_");
		var menu_next = res[0];
		var menu_child_id = res[1];
		var menu_parent_id = res[2];
		var nextChild_id = menu_next+'_'+menu_child_id;
		var menu_type = $("#menu_type_"+nextChild_id).val();
		
		if(menu_type == 'custom_link'){
			var custom_url_str = $("#custom_url_"+nextChild_id).val();
			var custom_url = custom_url_str.trim();
			if(custom_url == ''){
				$("#custom_url_"+nextChild_id).addClass("red_border");
				onErrorMsg(TEXT['Please fill out required field']);
				return;
			}else{
				$("#custom_url_"+nextChild_id).removeClass("red_border");
			}
			
			var item_label_str = $("#item_label_"+nextChild_id).val();
			var item_label = item_label_str.trim();
			if(item_label == ''){
				$("#item_label_"+nextChild_id).addClass("red_border");
				onErrorMsg(TEXT['Please fill out required field']);
				return;
			}else{
				$("#item_label_"+nextChild_id).removeClass("red_border");
			}
		}else{
			var item_label_str = $("#item_label_"+nextChild_id).val();
			var item_label = item_label_str.trim();
			if(item_label == ''){
				$("#item_label_"+nextChild_id).addClass("red_border");
				onErrorMsg(TEXT['Please fill out required field']);
				return;
			}else{
				$("#item_label_"+nextChild_id).removeClass("red_border");
			}
		}

		$.ajax({
			type : 'POST',
			url: base_url + '/backend/UpdateMenuSettings',
			data: $("#MenuSettingsSubmitForm_id_"+submitformid).serialize()
			+'&menu_next='+menu_next
			+'&id='+menu_child_id
			+'&menu_parent_id='+menu_parent_id,
			success: function (response) {
				var msgType = response.msgType;
				var msg = response.msg;
				if (msgType == "success") {
					onSuccessMsg(msg);
					onAjaxMakeMenuList();
				} else {
					onErrorMsg(msg);
				}
			}
		});
	});
	
	//Delete Parent Child Menu
	$(document).on("click", ".DeleteParentChildMenu", function(event) {
		event.preventDefault(); 
		
		var parentchildid = $(this).data("parentchildid");
		var res = parentchildid.split("_");

		var menu_next = res[0];
		var main_menu_id = res[1];
		var menu_parent_id = res[2];
		var menu_child_id = res[3];

		$.ajax({
			type : 'POST',
			url: base_url + '/backend/deleteParentChildMenu',
			data: "lan="+main_menu_lan
				+"&menu_next="+menu_next
				+"&main_menu_id="+main_menu_id
				+"&menu_parent_id="+menu_parent_id
				+"&menu_child_id="+menu_child_id,
			success:function(response){
				var msgType = response.msgType;
				var msg = response.msg;
				if (msgType == "success") {
					onSuccessMsg(msg);
					onAjaxMakeMenuList();
				} else {
					onErrorMsg(msg);
				}
			}
		});
	});
	
	//Page
	$(document).on('click', '.PageMenuBuilder nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPageMenuBuilderPagination(page);
	});
	
	//Brand
	$(document).on('click', '.BrandMenuBuilder nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onBrandMenuBuilderPagination(page);
	});
	
	//Product
	$(document).on('click', '.ProductMenuBuilder nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onProductMenuBuilderPagination(page);
	});
	
	//Product Category
	$(document).on('click', '.ProductCategoryMenuBuilder nav ul.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onProductCategoryMenuBuilderPagination(page);
	});
	
	$('input:checkbox').prop('checked', false);
	
	//Page
    $(".pageCheckAll").on("click", function () {
        $("input.page-menu-item").not(this).prop("checked", this.checked);
    });

	//Brand
    $(".brandCheckAll").on("click", function () {
        $("input.brand-menu-item").not(this).prop("checked", this.checked);
    });
	
	//Product
    $(".productCheckAll").on("click", function () {
        $("input.product-menu-item").not(this).prop("checked", this.checked);
    });
	
	//Product Category
    $(".productCategoryCheckAll").on("click", function () {
        $("input.product-category-menu-item").not(this).prop("checked", this.checked);
    });
	
	$("#is_image").on("click", function () {
		var is_image = $("#is_image:checked").val();
		if(is_image == 'on'){
			$("#mega_menu_image_hide_show").show();
		}else{
			$("#mega_menu_image_hide_show").hide();
		}
    });
	
	$("#remove_mega_menu_image").hide();
	$("#media_select_file").on("click", function () {
		var large_image = $("#large_image").val();
		if(large_image !=''){
			$("#mega_menu_image").val(large_image);
			$("#view_mega_menu_image").html('<img src="'+public_path+'/media/'+large_image+'">');
		}
		$("#remove_mega_menu_image").show();
		$('#global_media_modal_view').modal('hide');
    });
	
	//Menu Builder for parent menu sortable
	$("ul.menu-edit").sortable({
		connectWith: "ul",
		placeholder: 'ui-state-highlight',
		start: function(e,ui){
			ui.placeholder.height(ui.item.height());
		},
		receive: function (event, ui) {
			$(ui.item).removeClass("child_menu_item");
			$(ui.item).addClass("parent_menu_item");
			var str = ui.item.attr("data-mainparentmegatypeitemlan");
			var res = str.split("-");
			var menu_id = res[0];
			var menu_type = res[3];
			var item_id = res[4];
			var lan = res[5];
			
			//menu_parent_child_id, menu_parent_child
			var menuItemStr = ui.item.attr("id");
			var pcRes = menuItemStr.split("_");
			var menu_parent_child = pcRes[2];
			var menu_parent_child_id = pcRes[3];
			
			var MainTypeItemLan = menu_id+'-'+menu_type+'-'+item_id+'-'+lan+'-0-'+menu_parent_child_id+'-'+menu_parent_child;
			var TypeItemLan = menu_type+'-'+item_id+'-'+lan;
			
			//menu_id, menu_type, item_id, lan, Primary id = 0 (If custom_link item_id = id), menu_parent_child_id, menu_parent_child
			$(ui.item).attr("data-maintypeitemlan", MainTypeItemLan);
			
			//menu_type, item_id, lan(If custom_link item_id = id)
			$(ui.item).attr("data-typeitemlan", TypeItemLan);
		}
	});
	
	//Menu Builder for child menu sortable
    $("ul.child-menu-list").sortable({
		connectWith: "ul",
		placeholder: "ui-state-highlight",
		start: function(e,ui){
			ui.placeholder.height(ui.item.height());
		},
		receive: function (event, ui) {
			$(ui.item).removeClass("parent_menu_item");
			$(ui.item).addClass("child_menu_item");
			
			//menu_id, menu_parent_id, mega_menu_id
			var MainParentMegaMenuId = event.target.attributes['data-mainparentmega-id'].value;
			
			//menu_parent_child_id, menu_parent_child
			var menuItemStr = ui.item.attr("id");
			var Res = menuItemStr.split("_");
			var menu_parent_child = Res[2];
			var menu_parent_child_id = Res[3];

			//menu_type, item_id, lan(If custom_link item_id = id)
			var TypeItemLan = ui.item.attr("data-typeitemlan");
			
			var mainparentmegatypeitemlan = MainParentMegaMenuId+'-'+TypeItemLan+'-0-'+menu_parent_child_id+'-'+menu_parent_child;
			
			//menu_id, menu_parent_id, mega_menu_id, menu_type, item_id, lan, Primary id = 0 (If custom_link item_id = id), menu_parent_child_id, menu_parent_child
			$(ui.item).attr("data-mainparentmegatypeitemlan", mainparentmegatypeitemlan);
		}
    });
});

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

//Sortable Item List
function onSortableItemList() {

	var ParentMenuListObject = {};
	var ChildMenuListObject = {};
	var listItems = $("#menulist_id li.menu-item");
	var i =1;
	listItems.each(function(idx, li) {
		var hasParentMenuItem = $(li).hasClass("parent_menu_item");
		if(hasParentMenuItem){
			//menu_id, menu_type, item_id, lan, Primary = id (If custom_link item_id = id)
			ParentMenuListObject[i] = $(li).attr("data-maintypeitemlan");
		}
		
		var hasChildMenuItem = $(li).hasClass("child_menu_item");
		if(hasChildMenuItem){
			//menu_id, menu_parent_id, mega_menu_id, menu_type, item_id, lan, Primary = id(If custom_link item_id = id)
			ChildMenuListObject[i] = $(li).attr("data-mainparentmegatypeitemlan");
		}
		
		i++;
	});
	
	if(jQuery.isEmptyObject(ParentMenuListObject)){
		return;
	}
	
	//menu_id, menu_type, item_id, lan(If custom_link item_id = id)
	var ParentMenuList = JSON.stringify(ParentMenuListObject);

	//menu_id, menu_parent_id, mega_menu_id, menu_type, item_id, lan(If custom_link item_id = id)
	var ChildMenuList = JSON.stringify(ChildMenuListObject);

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/UpdateSortableMenuList',
		data: 'menu_id='+main_menu_id
		+'&lan='+main_menu_lan
		+'&ParentMenuList='+ParentMenuList
		+'&ChildMenuList='+ChildMenuList,
		success: function (response) {		
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

//Refresh Sortable
function onRefreshSortable() {

	//Menu Builder for parent menu sortable
	$("ul.menu-edit").sortable({
		connectWith: "ul",
		placeholder: 'ui-state-highlight',
		start: function(e,ui){
			ui.placeholder.height(ui.item.height());
		},
		receive: function (event, ui) {
			$(ui.item).removeClass("child_menu_item");
			$(ui.item).addClass("parent_menu_item");
			var str = ui.item.attr("data-mainparentmegatypeitemlan");

			var res = str.split("-");
			var menu_id = res[0];
			var menu_type = res[3];
			var item_id = res[4];
			var lan = res[5];
			
			//menu_parent_child_id, menu_parent_child
			var menuItemStr = ui.item.attr("id");
			var pcRes = menuItemStr.split("_");
			var menu_parent_child = pcRes[2];
			var menu_parent_child_id = pcRes[3];
			
			var MainTypeItemLan = menu_id+'-'+menu_type+'-'+item_id+'-'+lan+'-0-'+menu_parent_child_id+'-'+menu_parent_child;
			var TypeItemLan = menu_type+'-'+item_id+'-'+lan;
			
			//menu_id, menu_type, item_id, lan, Primary id = 0 (If custom_link item_id = id), menu_parent_child_id, menu_parent_child
			$(ui.item).attr("data-maintypeitemlan", MainTypeItemLan);
			
			//menu_type, item_id, lan(If custom_link item_id = id)
			$(ui.item).attr("data-typeitemlan", TypeItemLan);
		}
	});
	
	//Menu Builder for child menu sortable
    $("ul.child-menu-list").sortable({
		connectWith: "ul",
		placeholder: "ui-state-highlight",
		start: function(e,ui){
			ui.placeholder.height(ui.item.height());
		},
		receive: function (event, ui) {
			$(ui.item).removeClass("parent_menu_item");
			$(ui.item).addClass("child_menu_item");
			
			//menu_parent_child_id, menu_parent_child
			var menuItemStr = ui.item.attr("id");
			var Res = menuItemStr.split("_");
			var menu_parent_child = Res[2];
			var menu_parent_child_id = Res[3];
			
			//menu_id, menu_parent_id, mega_menu_id
			var MainParentMegaMenuId = event.target.attributes['data-mainparentmega-id'].value;
			
			//menu_type, item_id, lan(If custom_link item_id = id)
			var TypeItemLan = ui.item.attr("data-typeitemlan");
			
			var mainparentmegatypeitemlan = MainParentMegaMenuId+'-'+TypeItemLan+'-0-'+menu_parent_child_id+'-'+menu_parent_child;
			
			//menu_id, menu_parent_id, mega_menu_id, menu_type, item_id, lan, Primary id = 0 (If custom_link item_id = id), menu_parent_child_id, menu_parent_child
			$(ui.item).attr("data-mainparentmegatypeitemlan", mainparentmegatypeitemlan);
		}
    });
}

//Page
function onPageCheckAll() {
	
	$('input:checkbox').prop('checked',false);
	
    $(".pageCheckAll").on("click", function () {
        $("input.page-menu-item").not(this).prop("checked", this.checked);
    });
}

function onPageMenuBuilderPagination(page) {
	var search_page_menu = $("#search_page_menu").val();
	$.ajax({
		url: base_url + "/backend/getPageMenuBuilderData?page="+page+"&lan="+main_menu_lan+"&id="+main_menu_id+"&search="+search_page_menu,
		success:function(data){
			$('#page_menu_builder_id').html(data);
			onPageCheckAll();
		}
	});
}

function onPageMenuBuilderSearch() {
	var search_page_menu = $("#search_page_menu").val();
	$.ajax({
		url: base_url + "/backend/getPageMenuBuilderData?search="+search_page_menu+"&lan="+main_menu_lan+"&id="+main_menu_id,
		success:function(data){
			$('#page_menu_builder_id').html(data);
			onPageCheckAll();
		}
	});
}

function submitPageMenu() {
	var ids = [];
	$('.page-menu-item:checked').each(function(){
		ids.push($(this).val());
	});
	
	if(ids.length == 0){
		var msg = TEXT["Please select record"];
		onErrorMsg(msg);
		return;
	}
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveParentMenu',
		data: 'ids='+ids+'&menu_type=page'+'&menu_id='+main_menu_id+'&lan='+main_menu_lan,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
				ids = [];
			}else{
				onErrorMsg(msg);
			}
			
			onPageCheckAll();
		}
    });
}
//End of Page

function submitPostMenu() {
	var ids = [];
	$('.post-menu-item:checked').each(function(){
		ids.push($(this).val());
	});
	
	if(ids.length == 0){
		var msg = TEXT["Please select record"];
		onErrorMsg(msg);
		return;
	}
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveParentMenu',
		data: 'ids='+ids+'&menu_type=post'+'&menu_id='+main_menu_id+'&lan='+main_menu_lan,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
				ids = [];
			}else{
				onErrorMsg(msg);
			}
			
			onPostCheckAll();
		}
    });
}
//End of Post

//Brand
function onBrandCheckAll() {
	
	$('input:checkbox').prop('checked',false);
	
    $(".brandCheckAll").on("click", function () {
        $("input.brand-menu-item").not(this).prop("checked", this.checked);
    });
}

function onBrandMenuBuilderPagination(page) {
	var search_brand_menu = $("#search_brand_menu").val();
	$.ajax({
		url: base_url + "/backend/getBrandMenuBuilderData?page="+page+"&lan="+main_menu_lan+"&id="+main_menu_id+"&search="+search_brand_menu,
		success:function(data){
			$('#category_menu_builder_id').html(data);
			onBrandCheckAll();
		}
	});
}

function onBrandMenuBuilderSearch() {
	var search_brand_menu = $("#search_brand_menu").val();
	$.ajax({
		url: base_url + "/backend/getBrandMenuBuilderData?search="+search_brand_menu+"&lan="+main_menu_lan+"&id="+main_menu_id,
		success:function(data){
			$('#category_menu_builder_id').html(data);
			onBrandCheckAll();
		}
	});
}

function submitBrandMenu() {
	var ids = [];
	$('.brand-menu-item:checked').each(function(){
		ids.push($(this).val());
	});
	
	if(ids.length == 0){
		var msg = TEXT["Please select record"];
		onErrorMsg(msg);
		return;
	}
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveParentMenu',
		data: 'ids='+ids+'&menu_type=brand'+'&menu_id='+main_menu_id+'&lan='+main_menu_lan,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
				ids = [];
			}else{
				onErrorMsg(msg);
			}
			
			onBrandCheckAll();
		}
    });
}
//End of Category

//Custom Links
function showPerslyError() {
    $('.parsley-error-list').show();
}

jQuery('#CustomLinkDataFormId').parsley({
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
                onAddCustomLink();
                return false;
            }
        }
    }
});

function onAddCustomLink() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveParentMenu',
		data: $('#CustomLinkDataFormId').serialize()+'&menu_type=custom_link'+'&menu_id='+main_menu_id+'&lan='+main_menu_lan,
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				resetForm("CustomLinkDataFormId");
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
			} else {
				onErrorMsg(msg);
			}
		}
	});
}
//End of Custom Links

//Product
function onProductCheckAll() {
	
	$('input:checkbox').prop('checked',false);
	
    $(".productCheckAll").on("click", function () {
        $("input.product-menu-item").not(this).prop("checked", this.checked);
    });
}

function onProductMenuBuilderPagination(page) {
	var search = $("#search_product_menu").val();
	$.ajax({
		url: base_url + "/backend/getProductMenuBuilderData?page="+page+"&lan="+main_menu_lan+"&id="+main_menu_id+"&search="+search,
		success:function(data){
			$('#product_menu_builder_id').html(data);
			onProductCheckAll();
		}
	});
}

function onProductMenuBuilderSearch() {
	var search = $("#search_product_menu").val();
	$.ajax({
		url: base_url + "/backend/getProductMenuBuilderData?search="+search+"&lan="+main_menu_lan+"&id="+main_menu_id,
		success:function(data){
			$('#product_menu_builder_id').html(data);
			onProductCheckAll();
		}
	});
}

function submitProductMenu() {
	var ids = [];
	$('.product-menu-item:checked').each(function(){
		ids.push($(this).val());
	});
	
	if(ids.length == 0){
		var msg = TEXT["Please select record"];
		onErrorMsg(msg);
		return;
	}
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveParentMenu',
		data: 'ids='+ids+'&menu_type=product'+'&menu_id='+main_menu_id+'&lan='+main_menu_lan,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
				ids = [];
			}else{
				onErrorMsg(msg);
			}
			
			onProductCheckAll();
		}
    });
}
//End of Product

//Product Category
function onProductCategoryCheckAll() {
	
	$('input:checkbox').prop('checked',false);
	
    $(".productCategoryCheckAll").on("click", function () {
        $("input.product-category-menu-item").not(this).prop("checked", this.checked);
    });
}

function onProductCategoryMenuBuilderPagination(page) {
	var search = $("#search_product_category_menu").val();
	$.ajax({
		url: base_url + "/backend/getProductCategoryMenuBuilderData?page="+page+"&lan="+main_menu_lan+"&id="+main_menu_id+"&search="+search,
		success:function(data){
			$('#product_category_menu_builder_id').html(data);
			onProductCategoryCheckAll();
		}
	});
}

function onProductCategoryMenuBuilderSearch() {
	var search = $("#search_product_category_menu").val();
	$.ajax({
		url: base_url + "/backend/getProductCategoryMenuBuilderData?search="+search+"&lan="+main_menu_lan+"&id="+main_menu_id,
		success:function(data){
			$('#product_category_menu_builder_id').html(data);
			onProductCategoryCheckAll();
		}
	});
}

function submitProductCategoryMenu() {
	var ids = [];
	$('.product-category-menu-item:checked').each(function(){
		ids.push($(this).val());
	});
	
	if(ids.length == 0){
		var msg = TEXT["Please select record"];
		onErrorMsg(msg);
		return;
	}
	
    $.ajax({
		type : 'POST',
		url: base_url + '/backend/SaveParentMenu',
		data: 'ids='+ids+'&menu_type=product_category'+'&menu_id='+main_menu_id+'&lan='+main_menu_lan,
		success: function (response) {
			var msgType = response.msgType;
			var msg = response.msg;

			if(msgType == "success"){
				onSuccessMsg(msg);
				onAjaxMakeMenuList();
				ids = [];
			}else{
				onErrorMsg(msg);
			}
			
			onProductCategoryCheckAll();
		}
    });
}
//End of Product Category

function onAjaxMakeMenuList() {

	$.ajax({
		url: base_url + "/backend/ajaxMakeMenuList?lan="+main_menu_lan+"&id="+main_menu_id,
		success:function(data){
			$('#menulist_id').html(data);
			onRefreshSortable();
		}
	});
}

//Mega Menu Title
function onMediaImageRemove(type) {
    $('#mega_menu_image').val('');
	$("#remove_mega_menu_image").hide();
}

function onEditMegaMenuTitle(id) {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/getMegaMenuTitleById',
		data: 'id='+id,
		success: function (response) {
			
			var data = response;

			$("#mega_menu_RecordId").val(data.id);
			$("#mega_menu_title").val(data.mega_menu_title);
			
			if(data.is_title == 1){
				$('#is_title').prop('checked', true);
			}else{
				$('#is_title').prop('checked', false);
			}
			
			if(data.is_image == 1){
				$('#is_image').prop('checked', true);
				$("#mega_menu_image_hide_show").show();
			}else{
				$('#is_image').prop('checked', false);
				$("#mega_menu_image_hide_show").hide();
			}
			
			if(data.image != null){
				$("#mega_menu_image").val(data.image);
				$("#view_mega_menu_image").html('<img src="'+public_path+'/media/'+data.image+'">');
				$("#remove_mega_menu_image").show();
			}else{
				$("#mega_menu_image").val('');
				$("#view_mega_menu_image").html('');
				$("#remove_mega_menu_image").hide();
			}
			
			$('#megamenu_modal_view').modal('show');
		}
    });
}

jQuery('#MegaMenuTitle_FormId').parsley({
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
                onUpdateMegaMenuTitle();
                return false;
            }
        }
    }
});

function onUpdateMegaMenuTitle() {

    $.ajax({
		type : 'POST',
		url: base_url + '/backend/UpdateMegaMenuTitle',
		data: $('#MegaMenuTitle_FormId').serialize(),
		success: function (response) {			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
				$('#megamenu_modal_view').modal('hide');
				onAjaxMakeMenuList();
			} else {
				onErrorMsg(msg);
			}
		}
	});
}

