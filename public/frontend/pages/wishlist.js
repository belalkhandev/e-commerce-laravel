var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	onViewWishlistData();
});

function onViewWishlistData() {

    $.ajax({
		type : 'GET',
		url: base_url + '/frontend/wishlist_data',
		dataType:"json",
		success: function (data) {
			$('#tp_wishlist_datalist').html(data);
		}
	});
}

function onRemoveToWishlist(id) {
	var rowid = $("#removetowishlist_"+id).data('id');

	$.ajax({
		type : 'GET',
		url: base_url + '/frontend/remove_to_wishlist/'+rowid,
		dataType:"json",
		success: function (response) {
			
			var msgType = response.msgType;
			var msg = response.msg;

			if (msgType == "success") {
				onSuccessMsg(msg);
			} else {
				onErrorMsg(msg);
			}
			
			onViewWishlistData();
			onWishlist();
		}
	});
}

