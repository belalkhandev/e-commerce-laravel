var $ = jQuery.noConflict();

$(function () {
	"use strict";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		onPaginationDataLoad(page);
	});
	
});

function onPaginationDataLoad(page) {

	$.ajax({
		url:base_url + "/frontend/getProductReviewsGrid",
		data:{page:page,item_id:item_id},
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}



