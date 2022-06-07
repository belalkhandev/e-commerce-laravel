<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderInvoiceController extends Controller
{
    //Order Invoice
    public function getOrderInvoice($id, $order_no) {
		$gtext = gtext();

		$mdata = DB::table('order_masters as a')
			->join('order_items as b', 'a.id', '=', 'b.order_master_id')
			->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
			->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
			->join('order_status as f', 'a.order_status_id', '=', 'f.id')			
			->select(
				'a.id', 
				'a.customer_id', 
				'a.payment_status_id', 
				'a.order_status_id', 
				'a.order_no', 
				'a.created_at', 
				'a.shipping_title', 
				'a.shipping_fee', 
				DB::raw("SUM(b.total_price) as total_amount"), 
				DB::raw("SUM(b.tax) as tax"), 
				DB::raw("SUM(b.discount) as discount"), 
				'a.email as customer_email', 
				'a.name as customer_name', 
				'a.phone as customer_phone', 
				'a.country', 
				'a.state', 
				'a.zip_code', 
				'a.city', 
				'a.address as customer_address',  
				'd.method_name', 
				'e.pstatus_name', 
				'f.ostatus_name')
			->where('a.id', $id)
			->groupBy(
				'a.customer_id', 
				'a.payment_status_id', 
				'a.order_status_id', 
				'a.created_at', 
				'f.ostatus_name', 
				'e.pstatus_name', 
				'd.method_name', 
				'a.shipping_title', 
				'a.name', 
				'a.phone', 
				'a.country', 
				'a.state', 
				'a.zip_code', 
				'a.city', 
				'a.email', 
				'a.address', 
				'a.shipping_fee',  
				'a.order_no', 
				'a.id')
			->first();
			
		$datalist = DB::table('order_items')
			->join('products', 'order_items.product_id', '=', 'products.id')
			->select('order_items.*', 'products.title')
			->where('order_items.order_master_id', $id)
			->get();

		$item_list = '';
		foreach($datalist as $row){
			
			if($gtext['currency_position'] == 'left'){
				$price = $gtext['currency_icon'].number_format($row->price);
				$total_price = $gtext['currency_icon'].number_format($row->total_price);
			}else{
				$price = number_format($row->price).$gtext['currency_icon'];
				$total_price = number_format($row->total_price).$gtext['currency_icon'];
			}

			if($row->variation_color == '0'){
				$color = '&nbsp;';
			}else{
				$color = 'Color: '.$row->variation_color.'&nbsp;';
			}

			if($row->variation_size == '0'){
				$size = '&nbsp;';
			}else{
				$size = 'Size: '.$row->variation_size;
			}
			
			$item_list .= '<tr>
					<td class="w-60" align="left">
						<p>'.$row->title.'</p>
						<p class="color_size">'.$color.$size.'</p>
					</td>
					<td class="w-20" align="center">'.$price.' x '.$row->quantity.'</td> 
					<td class="w-20" align="right">'.$total_price.'</td>  
				</tr>';
		}
		
		$total_amount_shipping_fee = $mdata->total_amount+$mdata->shipping_fee+$mdata->tax;

		if($gtext['currency_position'] == 'left'){
			$shipping_fee = $gtext['currency_icon'].number_format($mdata->shipping_fee, 2);
			$tax = $gtext['currency_icon'].number_format($mdata->tax, 2);
			$discount = $gtext['currency_icon'].number_format($mdata->discount, 2);
			$subtotal = $gtext['currency_icon'].number_format($mdata->total_amount, 2);
			$total_amount = $gtext['currency_icon'].number_format($total_amount_shipping_fee, 2);
		}else{
			$shipping_fee = number_format($mdata->shipping_fee, 2).$gtext['currency_icon'];
			$tax = number_format($mdata->tax, 2).$gtext['currency_icon'];
			$discount = number_format($mdata->discount, 2).$gtext['currency_icon'];
			$subtotal = number_format($mdata->total_amount, 2).$gtext['currency_icon'];
			$total_amount = number_format($total_amount_shipping_fee, 2).$gtext['currency_icon'];
		}

		$base_url = url('/');
		$logo = public_path('media/'.$gtext['front_logo']);
		
		//set font
		PDF::SetFont('dejavusans', '', 9);
		
		//set font size
		PDF::SetFontSize(9);
		
		//page title
		PDF::SetTitle($gtext['site_name']);

		//add a page
		PDF::AddPage();

		$html ='<style>
		.w-100 {width: 100%;}
		.w-75 {width: 75%;}
		.w-60 {width: 60%;}
		.w-50 {width: 50%;}
		.w-40 {width: 40%;}
		.w-25 {width: 25%;}
		.w-20 {width: 20%;}

		table td, table th {
			color: #686868;
			text-decoration: none;
		}
		a {
			color: #686868;
			text-decoration: none;
		}
		table.border td, table.border th {
			border: 1px solid #f0f0f0;
		}
		table.border-tb td, table.border-tb th {
			border-top: 1px solid #f0f0f0;
			border-bottom: 1px solid #f0f0f0;
		}
		table.border-header td {
			border-bottom: 1px solid #f0f0f0;
		}
		table.border-t td, table.border-t th {
			border-top: 1px solid #f0f0f0;
		}
		table.border-none td, table.border-none th {
			border: none;
		}
		.company-logo img{
			width: 100px;
			height: auto;
		}
		td.invoice-name {
			font-size: 25px;
			font-weight: 600;
			text-align: right;
		}
		p.com-address {
			line-height: 5px;
		}
		h3, h4 {
			line-height: 10px;
		}
		p {
			line-height: 5px;
		}
		h3 {
			font-size: 16px;
		}
		h4 {
			font-size: 12px;
			margin-bottom: 0px;
			font-weight: 400;
		}
		p.color_size {
			font-size: 8px;
		}
		.pstatus_1 {
			font-weight: bold;
			color: #26c56d;	
		}
		.pstatus_2 {
			font-weight: bold;
			color: #fe9e42;	
		}
		.pstatus_3,
		.pstatus_4 {
			font-weight: bold;
			color: #f25961;	
		}
		.ostatus_4 {
			font-weight: bold;
			color: #26c56d;	
		}
		.ostatus_1,
		.ostatus_2,
		.ostatus_3 {
			font-weight: bold;
			color: #fe9e42;	
		}
		.ostatus_5 {
			font-weight: bold;
			color: #f25961;	
		}
		</style>
		
		<!--html table -->
		<table class="border-header" width="100%" cellpadding="10" cellspacing="0">
			<tr>
				<td class="w-40"><span class="company-logo"><img src="'.$logo.'"/></span></td>  
				<td class="w-60 invoice-name">'.__('Invoice').'</td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="1" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td class="w-50" align="left">
					<h3>'.__('BILL TO').':</h3>
					<p><strong>'.$mdata->customer_name.'</strong></p>
					<p>'.$mdata->customer_address.'</p>
					<p>'.$mdata->city.', '.$mdata->state.', '.$mdata->zip_code.', '.$mdata->country.'</p>
					<p>'.$mdata->customer_email.'</p>
					<p>'.$mdata->customer_phone.'</p>
				</td>  
				<td class="w-50" align="right">
					<p><strong>'.__('Order#').'</strong>: '.$mdata->order_no.'</p>
					<p><strong>'.__('Order Date').'</strong>: '.date('d-m-Y', strtotime($mdata->created_at)).'</p>
					<p><strong>'.__('Payment Method').'</strong>: '.$mdata->method_name.'</p>
					<p><strong>'.__('Payment Status').'</strong>: <span class="pstatus_'.$mdata->payment_status_id.'">'.$mdata->pstatus_name.'</span></p>
					<p><strong>'.__('Order Status').'</strong>: <span class="ostatus_'.$mdata->order_status_id.'">'.$mdata->ostatus_name.'</span></p>
				</td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="5" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-100" align="left">
					<h3>'.__('BILL FROM').':</h3>
					<p><strong>'.$gtext['company'].'</strong></p>
					<p>'.$gtext['invoice_address'].'</p>
					<p>'.$gtext['invoice_email'].'</p>
					<p>'.$gtext['invoice_phone'].'</p>
				</td> 
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="10" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>

		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-60" align="left">
					<strong>'.__('Product').'</strong>
				</td>  
				<td class="w-20" align="center">
					<strong>'.__('Price').'</strong>
				</td>  
				<td class="w-20" align="right">
					<strong>'.__('Total').'</strong>
				</td>  
			</tr>
		</table>
		
		<table class="border-tb" width="100%" cellpadding="6" cellspacing="0">
			'.$item_list.'
		</table>
		<table class="border-none" width="100%" cellpadding="2" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-50" align="left">'.$mdata->shipping_title.'</td>  
				<td class="w-25" align="right"><strong>'.__('Shipping Fee').'</strong>: </td>  
				<td class="w-25" align="right"><strong>'.$shipping_fee.'</strong></td>  
			</tr>
			<tr>
				<td class="w-50" align="left"></td>  
				<td class="w-25" align="right"><strong>'.__('Tax').'</strong>: </td>  
				<td class="w-25" align="right"><strong>'.$tax.'</strong></td>  
			</tr>
			<tr>
				<td class="w-50" align="left"></td>  
				<td class="w-25" align="right"><strong>'.__('Subtotal').'</strong>: </td>  
				<td class="w-25" align="right"><strong>'.$subtotal.'</strong></td>  
			</tr>
			<tr>
				<td class="w-50" align="left"></td>  
				<td class="w-25" align="right"><strong>'.__('Total').'</strong>: </td>  
				<td class="w-25" align="right"><strong>'.$total_amount.'</strong></td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="70" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-t" width="100%" cellpadding="10" cellspacing="0">
			<tr>
				<td class="w-100" align="center">
					<p>'.__('Thank you for purchasing our products.').'</p>
					<p>'.__('If you have any questions about this invoice, please contact us').'</p>
					<p><a href="'.$base_url.'">'.$base_url.'</a></p>
				</td>
			</tr>
		</table>';

		//output the HTML content
		PDF::writeHTML($html, true, false, true, false, '');

		//Close and output PDF document
		PDF::Output('invoice-'.$mdata->order_no.'.pdf', 'D');
	}
}
