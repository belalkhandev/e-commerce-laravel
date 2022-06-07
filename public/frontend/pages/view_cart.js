var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	onViewCartData();
});

function onViewCartData() {

    $.ajax({
		type : 'GET',
		url: base_url + '/frontend/viewcart_data',
		dataType:"json",
		success: function (data) {

			$('#tp_viewcart_datalist').html(data.items);
			
			$(".viewcart_price_total").text(data.price_total);
			$(".viewcart_discount").text(data.discount);
			$(".viewcart_tax").text(data.tax);
			$(".viewcart_sub_total").text(data.sub_total);
			$(".viewcart_total").text(data.total);
		}
	});
}

function onRemoveToCart(id) {
	var rowid = $("#removetoviewcart_"+id).data('id');

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
			
			onViewCartData();
			onViewCart();
		}
	});
}

