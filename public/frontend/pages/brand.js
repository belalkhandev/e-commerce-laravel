var $ = jQuery.noConflict();
var num = '';
var sortby = '';
var categories = [];
var brands = [];
var color = '';
var size = '';

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
	
	$("#num").on("change", function () {
		num = $('#num').val();
		onRefreshData();
	});
	
	$("#sortby").on("change", function () {
		sortby = $('#sortby').val();
		onRefreshData();
	});

	$('input:checkbox').prop('checked', false);
	
	$(document).on('click','.filter_by_category',()=> {
		var arr = [];
		$('.filter_by_category:checkbox:checked').each(function () {
			var val = parseInt($(this).val());
			arr.push(val);
		});
		
		categories = arr;
		onRefreshData();
	});

	$(document).on('click','.filter_by_brand',()=> {
		var brandArr = [];
		$('.filter_by_brand:checkbox:checked').each(function () {
			var val = parseInt($(this).val());
			brandArr.push(val);
		});
		
		brands = brandArr;
		onRefreshData();
	});
	
	brands.push(brand_id);
	$('#filter_by_brand_'+brand_id).prop('checked', true);
	
    $(".filter_by_color").on("click", function () {
		color = $(this)[0].id;
		var color_id = $(this).attr("data-color");
		$(".active_color").removeClass("active");
		$("#color_"+color_id).addClass("active");
		
		onRefreshData();
    });
	
    $(".filter_by_size").on("click", function () {
		size = $(this)[0].id;
		var size_id = $(this).attr("data-size");
		$(".active_size").removeClass("active");
		$("#size_"+size_id).addClass("active");
		
		onRefreshData();
    });
});

function onPaginationDataLoad(page) {
	if(brands.length == 0) {
		brands.push(brand_id);
		$('#filter_by_brand_'+brand_id).prop('checked', true);
	}
	
	$.ajax({
		url:base_url + "/frontend/getBrandGrid",
		data:{page:page,num:num,sortby:sortby,categories:categories,brands:brands,color:color,size:size},
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onRefreshData() {
	if(brands.length == 0) {
		brands.push(brand_id);
		$('#filter_by_brand_'+brand_id).prop('checked', true);
	}

 	$.ajax({
		url:base_url + "/frontend/getBrandGrid",
		data:{num:num,sortby:sortby,categories:categories,brands:brands,color:color,size:size},
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}


