var color = 0;
var size = 0;
var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	onViewCart();
	onWishlist();
	
    $(".selectcolor").on("click", function () {
		color = $(this).data('id');
		$(".tp_color").removeClass("active");
		$("#color_"+color).addClass("active");
    });
	
    $(".selectsize").on("click", function () {
		size = $(this).data('id');
		$(".tp_size").removeClass("active");
		$("#size_"+size).addClass("active");
    });
	
	$("#variation_required").hide();
	$("#quantity_required").hide();
	$("#stockqty_required").hide();
	$("#stockout_required").hide();
	
	$(document).on("click", ".product_addtocart", function(event) {
		event.preventDefault();
		
		$("#variation_required").hide();
		$("#quantity_required").hide();
		$("#stockqty_required").hide();
		$("#stockout_required").hide();
	
		var id = $(this).data('id');
		var qty = $("#quantity").val();

		if(is_color == 1){
			if(color == 0){
				$("#variation_required").show();
				return;
			}
		}
		if(is_size == 1){
			if(size == 0){
				$("#variation_required").show();
				return;
			}
		}
		if((qty == undefined) || (qty == '') || (qty <= 0)){
			$("#quantity_required").show();
			return;
		}
		if(is_stock == 1){
			var stockqty = $(this).data('stockqty');
			if(is_stock_status == 1){
				if(qty > stockqty){
					$("#stockqty_required").show();
					return;
				}
			}else{
				$("#stockout_required").show();
				return;
			}
		}
		
		$.ajax({
			type : 'GET',
			url: base_url + '/frontend/add_to_cart/'+id+'/'+color+'/'+size+'/'+qty,
			dataType:"json",
			success: function (response) {
				var msgType = response.msgType;
				var msg = response.msg;
				
				$("#variation_required").hide();
				$("#quantity_required").hide();
				$("#stockqty_required").hide();
				$("#stockout_required").hide();
				
				if (msgType == "success") {
					onSuccessMsg(msg);
				} else {
					onErrorMsg(msg);
				}
				onViewCart();
			}
		});
    });
	
	$(document).on("click", ".product_buy_now", function(event) {
		event.preventDefault();
		
		$("#variation_required").hide();
		$("#quantity_required").hide();
		$("#stockqty_required").hide();
		$("#stockout_required").hide();
	
		var id = $(this).data('id');
		var qty = $("#quantity").val();

		if(is_color == 1){
			if(color == 0){
				$("#variation_required").show();
				return;
			}
		}
		if(is_size == 1){
			if(size == 0){
				$("#variation_required").show();
				return;
			}
		}
		if((qty == undefined) || (qty == '') || (qty <= 0)){
			$("#quantity_required").show();
			return;
		}
		if(is_stock == 1){
			var stockqty = $(this).data('stockqty');
			if(is_stock_status == 1){
				if(qty > stockqty){
					$("#stockqty_required").show();
					return;
				}
			}else{
				$("#stockout_required").show();
				return;
			}
		}
		
		$.ajax({
			type : 'GET',
			url: base_url + '/frontend/add_to_cart/'+id+'/'+color+'/'+size+'/'+qty,
			dataType:"json",
			success: function (response) {
				var msgType = response.msgType;
				var msg = response.msg;
				
				$("#variation_required").hide();
				$("#quantity_required").hide();
				$("#stockqty_required").hide();
				$("#stockout_required").hide();
				
				if (msgType == "success") {
					// onSuccessMsg(msg);
					window.location.href = base_url + '/checkout';
				} else {
					onErrorMsg(msg);
				}
				onViewCart();
			}
		});
    });
	
	$(document).on("click", ".addtocart", function(event) {
		event.preventDefault();
		
		var id = $(this).data('id');
		var color = 0;
		var size = 0;
		var qty = 0;
		$.ajax({
			type : 'GET',
			url: base_url + '/frontend/add_to_cart/'+id+'/'+color+'/'+size+'/'+qty,
			dataType:"json",
			success: function (response) {
				var msgType = response.msgType;
				var msg = response.msg;

				if (msgType == "success") {
					onSuccessMsg(msg);
				} else {
					onErrorMsg(msg);
				}
				onViewCart();
			}
		});
    });	
	
	$(document).on("click", ".addtowishlist", function(event) {
		event.preventDefault();
		
		var id = $(this).data('id');

		$.ajax({
			type : 'GET',
			url: base_url + '/frontend/add_to_wishlist/'+id,
			dataType:"json",
			success: function (response) {
				var msgType = response.msgType;
				var msg = response.msg;

				if (msgType == "success") {
					onSuccessMsg(msg);
				} else {
					onErrorMsg(msg);
				}
				onWishlist();
			}
		});
    });	
});

function onViewCart() {

    $.ajax({
		type : 'GET',
		url: base_url + '/frontend/view_cart',
		dataType:"json",
		success: function (data) {

			$('#tp_cart_data').html(data.items);
			
			$(".total_qty").text(data.total_qty);
			$(".sub_total").text(data.sub_total);
			$(".tax").text(data.tax);
			$(".tp_total").text(data.total);
		}
	});
}

function onRemoveToCart(id) {
	var rowid = $("#removetocart_"+id).data('id');

	$.ajax({
		type : 'GET',
		url: base_url + '/frontend/remove_to_cart/'+rowid,
		dataType:"json",
		success: function (response) {
			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
			
			onViewCart();
		}
	});
}

function onWishlist() {

    $.ajax({
		type : 'GET',
		url: base_url + '/frontend/count_wishlist',
		dataType:"json",
		success: function (data) {
			$(".count_wishlist").text(data);
		}
	});
}
