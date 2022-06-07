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
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	
	$.ajax({
		url:base_url + "/backend/getTransactionsTableData?page="+page+"&start_date="+start_date+"&end_date="+end_date,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onSearch() {
	var search = $("#search").val();

	$.ajax({
		url: base_url + "/backend/getTransactionsTableData?search="+search,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onFilterAction() {
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();

	$.ajax({
		url: base_url + "/backend/getTransactionsTableData?start_date="+start_date+"&end_date="+end_date,
		success:function(data){
			$('#tp_datalist').html(data);
		}
	});
}

function onExcelExport() {
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	
	var FinalPath = base_url + "/backend/transactions-excel-export?start_date="+start_date+"&end_date="+end_date;

	$.ajax({
		url:FinalPath,
		success:function(data){
			var filePath = base_url+'/public/export/'+data;
			window.open(filePath);
		}
	});
}

function onCSVExport() {
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();
	
	var FinalPath = base_url + "/backend/transactions-csv-export?start_date="+start_date+"&end_date="+end_date;
	
	$.ajax({
		url:FinalPath,
		success:function(data){
			var filePath = base_url+'/public/export/'+data;
			window.open(filePath);
		}
	});
}
