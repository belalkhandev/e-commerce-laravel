<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Order_master;
use App\Models\Order_item;
use Illuminate\Support\Facades\Auth;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class OrdersSellerController extends Controller
{
	
	//Orders page load
    public function getOrdersPageLoad() {
		
		$seller_id = Auth::user()->id;
		
		$order_status_list = DB::table('order_status')->get();
		
		$datalist = DB::table('order_masters as a')
			->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
			->join('users as c', 'a.seller_id', '=', 'c.id')
			->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
			->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
			->join('order_status as f', 'a.order_status_id', '=', 'f.id')
			->join('order_items as g', 'a.id', '=', 'g.order_master_id')
			->select('a.id', 'a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
			->where('a.seller_id', $seller_id)
			->groupBy('a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
			->orderBy('a.created_at','desc')
			->paginate(20);
		
        return view('seller.orders', compact('order_status_list', 'datalist'));		
	}
	
	//Get data for Orders Pagination
	public function getOrdersTableData(Request $request){
		$seller_id = Auth::user()->id;
		
		$search = $request->search;
		$status = $request->status;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		if($request->ajax()){

			if($search != ''){

				$datalist = DB::table('order_masters as a')
					->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
					->join('users as c', 'a.seller_id', '=', 'c.id')
					->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
					->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
					->join('order_status as f', 'a.order_status_id', '=', 'f.id')
					->join('order_items as g', 'a.id', '=', 'g.order_master_id')
					->select('a.id', 'a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
					->where(function ($query) use ($search){
						$query->where('a.order_no', 'like', '%'.$search.'%')
							->orWhere('a.created_at', 'like', '%'.$search.'%')
							->orWhere('b.name', 'like', '%'.$search.'%')
							->orWhere('c.shop_name', 'like', '%'.$search.'%')
							->orWhere('d.method_name', 'like', '%'.$search.'%')
							->orWhere('e.pstatus_name', 'like', '%'.$search.'%')
							->orWhere('f.ostatus_name', 'like', '%'.$search.'%')
							->orWhere('b.email', 'like', '%'.$search.'%');
					})
					->where('a.seller_id', $seller_id)
					->groupBy('a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
					->orderBy('a.created_at','desc')
					->paginate(20);
			}else{
				if(($start_date != '') && ($end_date != '')){

					$datalist = DB::table('order_masters as a')
						->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
						->join('users as c', 'a.seller_id', '=', 'c.id')
						->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
						->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
						->join('order_status as f', 'a.order_status_id', '=', 'f.id')
						->join('order_items as g', 'a.id', '=', 'g.order_master_id')
						->select('a.id', 'a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
						->where('a.seller_id', $seller_id)
						->whereBetween('a.created_at', [$start_date, $end_date])
						->groupBy('a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
						->orderBy('a.created_at','desc')
						->paginate(20);
				}else{
					if($status == 0){

						$datalist = DB::table('order_masters as a')
							->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
							->join('users as c', 'a.seller_id', '=', 'c.id')
							->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
							->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
							->join('order_status as f', 'a.order_status_id', '=', 'f.id')
							->join('order_items as g', 'a.id', '=', 'g.order_master_id')
							->select('a.id', 'a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
							->where('a.seller_id', $seller_id)
							->groupBy('a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
							->orderBy('a.created_at','desc')
							->paginate(20);
					}else{

						$datalist = DB::table('order_masters as a')
							->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
							->join('users as c', 'a.seller_id', '=', 'c.id')
							->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
							->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
							->join('order_status as f', 'a.order_status_id', '=', 'f.id')
							->join('order_items as g', 'a.id', '=', 'g.order_master_id')
							->select('a.id', 'a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
							->where('a.order_status_id', '=', $status)
							->where('a.seller_id', $seller_id)
							->groupBy('a.customer_id', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
							->orderBy('a.created_at','desc')
							->paginate(20);
					}
				}
			}

			return view('seller.partials.orders_table', compact('datalist'))->render();
		}
	}
	
    //Order page load
    public function getOrderData($id) {
		$seller_id = Auth::user()->id;
		
		$order_status_list = DB::table('order_status')->get();

		$mdata = DB::table('order_masters as a')
			->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
			->join('users as c', 'a.seller_id', '=', 'c.id')
			->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
			->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
			->join('order_status as f', 'a.order_status_id', '=', 'f.id')
			->join('order_items as g', 'a.id', '=', 'g.order_master_id')
			->select(
				'a.id', 
				'a.customer_id', 
				'a.payment_status_id', 
				'a.order_status_id', 
				'a.order_no', 
				'a.created_at', 
				'a.shipping_title', 
				'a.shipping_fee', 
				DB::raw("SUM(g.total_price) as total_amount"), 
				DB::raw("SUM(g.tax) as tax"), 
				DB::raw("SUM(g.discount) as discount"), 
				'b.name', 
				'a.email as customer_email', 
				'a.name as customer_name', 
				'a.phone as customer_phone', 
				'a.country', 
				'a.state',
				'a.zip_code',
				'a.city', 
				'a.address as customer_address', 
				'c.shop_name', 
				'd.method_name', 
				'e.pstatus_name', 
				'f.ostatus_name')
			->where('a.id', $id)
			->where('a.seller_id', $seller_id)
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
				'b.name', 
				'c.shop_name', 
				'a.order_no', 
				'a.id')
			->first();
			
		$datalist = DB::table('order_items')
			->join('products', 'order_items.product_id', '=', 'products.id')
			->select('order_items.*', 'products.title')
			->where('order_items.order_master_id', $id)
			->where('order_items.seller_id', $seller_id)
			->get();

        return view('seller.order', compact('order_status_list', 'mdata', 'datalist'));		
	}

	//update Order Status
	public function updateOrderStatus(Request $request){
		$gtext = gtext();
		$res = array();

		$id = $request->input('order_master_id');
		$order_status_id = $request->input('order_status_id');
		$is_notify = $request->input('isnotify');
		
		if ($is_notify == 'true' || $is_notify == 'on') {
			$isnotify = 1;
		}else {
			$isnotify = 0;
		}
		
		$data = array(
			'order_status_id' => $order_status_id
		);
		
		$response = Order_master::where('id', $id)->update($data);
		if($response){
			if($isnotify == 1){
				if($gtext['ismail'] == 1){
					self::orderNotify($id);
				}
			}
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
	}

    //Payment Order Status
    public function getPaymentOrderStatusData(Request $request) {
		
		$id = $request->order_master_id;
		
		$data = DB::table('order_masters')
			->join('payment_status', 'order_masters.payment_status_id', '=', 'payment_status.id')
			->join('order_status', 'order_masters.order_status_id', '=', 'order_status.id')
			->select('order_masters.payment_status_id', 'payment_status.pstatus_name', 'order_masters.order_status_id', 'order_status.ostatus_name')
			->where('order_masters.id', $id)
			->first();

        return response()->json($data);
	}
	
    //Order Notify
    public function orderNotify($id) {
		$gtext = gtext();
	
		$mdata = DB::table('order_masters as a')
			->join('order_items as b', 'a.id', '=', 'b.order_master_id')
			->join('users as c', 'a.seller_id', '=', 'c.id')
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
				'a.city', 
				'a.address as customer_address',
				'c.shop_name',  
				'c.email as seller_email', 				
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
				'c.shop_name', 
				'c.email', 
				'a.shipping_title', 
				'a.name', 
				'a.phone', 
				'a.country', 
				'a.state', 
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
							<td style="width:70%;text-align:left;border:1px solid #ddd;">'.$row->title.'<br>'.$color.$size.'</td>
							<td style="width:15%;text-align:center;border:1px solid #ddd;">'.$price.' x '.$row->quantity.'</td>
							<td style="width:15%;text-align:right;border:1px solid #ddd;">'.$total_price.'</td>
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
		
		if($mdata->payment_status_id == 1){
			$pstatus = '#26c56d'; //Completed = 1
		}elseif($mdata->payment_status_id == 2){
			$pstatus = '#fe9e42'; //Pending = 2
		}elseif($mdata->payment_status_id == 3){
			$pstatus = '#f25961'; //Canceled = 3
		}elseif($mdata->payment_status_id == 4){
			$pstatus = '#f25961'; //Incompleted 4
		}
		
		if($mdata->order_status_id == 1){
			$ostatus = '#fe9e42'; //Awaiting processing = 1
		}elseif($mdata->order_status_id == 2){
			$ostatus = '#fe9e42'; //Processing = 2
		}elseif($mdata->order_status_id == 3){
			$ostatus = '#fe9e42'; //Ready for pickup = 3
		}elseif($mdata->order_status_id == 4){
			$ostatus = '#26c56d'; //Completed 4
		}elseif($mdata->order_status_id == 5){
			$ostatus = '#f25961'; //Canceled 5
		}

		$base_url = url('/');

		if($gtext['ismail'] == 1){
			try {

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);
				$mail->CharSet = "UTF-8";

				if($gtext['mailer'] == 'smtp'){
					$mail->SMTPDebug = 0; //0 = off (for production use), 1 = client messages, 2 = client and server messages
					$mail->isSMTP();
					$mail->Host       = $gtext['smtp_host'];
					$mail->SMTPAuth   = true;
					$mail->Username   = $gtext['smtp_username'];
					$mail->Password   = $gtext['smtp_password'];
					$mail->SMTPSecure = $gtext['smtp_security'];
					$mail->Port       = $gtext['smtp_port'];
				}

				//Get mail
				$mail->setFrom($gtext['from_mail'], $gtext['from_name']);
				$mail->addAddress($mdata->customer_email, $mdata->customer_name);
				$mail->addAddress($mdata->seller_email, $mdata->shop_name);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = __('Your order status').' - '.$mdata->ostatus_name;
				
				$mail->Body = '<table style="background-color:#edf2f7;color:#111111;padding:40px 0px;line-height:24px;font-size:14px;" border="0" cellpadding="0" cellspacing="0" width="100%">	
								<tr>
									<td>
										<table style="background-color:#fff;max-width:1000px;margin:0 auto;padding:30px;" border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr><td style="font-size:40px;border-bottom:1px solid #ddd;padding-bottom:25px;font-weight:bold;text-align:center;">'.$gtext['company'].'</td></tr>
											<tr><td style="font-size:25px;font-weight:bold;padding:30px 0px 5px 0px;">'.__('Hi').' '.$mdata->customer_name.'</td></tr>
											<tr><td>'.__('Thanks for your order. You can find your purchase information below.').'</td></tr>
											<tr>
												<td style="padding-top:30px;padding-bottom:20px;">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td style="vertical-align: top;">
																<table border="0" cellpadding="3" cellspacing="0" width="100%">
																	<tr><td style="font-size:16px;font-weight:bold;">'.__('BILL TO').':</td></tr>
																	<tr><td><strong>'.$mdata->customer_name.'</strong></td></tr>
																	<tr><td>'.$mdata->customer_address.'</td></tr>
																	<tr><td>'.$mdata->city.', '.$mdata->state.', '.$mdata->country.'</td></tr>
																	<tr><td>'.$mdata->customer_email.'</td></tr>
																	<tr><td>'.$mdata->customer_phone.'</td></tr>
																</table>
																<table style="padding:30px 0px;" border="0" cellpadding="3" cellspacing="0" width="100%">
																	<tr><td style="font-size:16px;font-weight:bold;">'.__('BILL FROM').':</td></tr>
																	<tr><td><strong>'.$gtext['company'].'</strong></td></tr>
																	<tr><td>'.$gtext['invoice_address'].'</td></tr>
																	<tr><td>'.$gtext['invoice_email'].'</td></tr>
																	<tr><td>'.$gtext['invoice_phone'].'</td></tr>
																</table>
															</td>
															<td style="vertical-align: top;">
																<table style="text-align:right;" border="0" cellpadding="3" cellspacing="0" width="100%">
																	<tr><td><strong>'.__('Order#').'</strong>: '.$mdata->order_no.'</td></tr>
																	<tr><td><strong>'.__('Order Date').'</strong>: '.date('d-m-Y', strtotime($mdata->created_at)).'</td></tr>
																	<tr><td><strong>'.__('Payment Method').'</strong>: '.$mdata->method_name.'</td></tr>
																	<tr><td><strong>'.__('Payment Status').'</strong>: <span style="color:'.$pstatus.'">'.$mdata->pstatus_name.'</span></td></tr>
																	<tr><td><strong>'.__('Order Status').'</strong>: <span style="color:'.$ostatus.'">'.$mdata->ostatus_name.'</span></td></tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td>
													<table style="border-collapse:collapse;" border="0" cellpadding="5" cellspacing="0" width="100%">
														<tr>
															<th style="width:70%;text-align:left;border:1px solid #ddd;">'.__('Product').'</th>
															<th style="width:15%;text-align:center;border:1px solid #ddd;">'.__('Price').'</th>
															<th style="width:15%;text-align:right;border:1px solid #ddd;">'.__('Total').'</th>
														</tr>
														'.$item_list.'
													</table>
												</td>
											</tr>
											<tr>
												<td style="padding-top:5px;padding-bottom:20px;">
													<table style="font-weight:bold;" border="0" cellpadding="5" cellspacing="0" width="100%">
														<tr>
															<td style="width:85%;text-align:right;">'.$mdata->shipping_title.' - '.__('Shipping Fee').':</td>
															<td style="width:15%;text-align:right;">'.$shipping_fee.'</td>
														</tr>
														<tr>
															<td style="width:85%;text-align:right;">'.__('Tax').':</td>
															<td style="width:15%;text-align:right;">'.$tax.'</td>
														</tr>
														<tr>
															<td style="width:85%;text-align:right;">'.__('Subtotal').':</td>
															<td style="width:15%;text-align:right;">'.$subtotal.'</td>
														</tr>
														<tr>
															<td style="width:85%;text-align:right;">'.__('Total').':</td>
															<td style="width:15%;text-align:right;">'.$total_amount.'</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr><td style="padding-top:30px;padding-bottom:50px;"><a href="'.route('frontend.order-invoice', [$mdata->id, $mdata->order_no]).'" style="background:'.$gtext['theme_color'].';display:block;text-align:center;padding:10px 30px;border-radius:3px;text-decoration:none;color:#fff;float:left;">'.__('Invoice Download').'</a></td></tr>
											<tr><td style="padding-top:10px;border-top:1px solid #ddd;text-align:center;">'.__('Thank you for purchasing our products.').'</td></tr>
											<tr><td style="padding-top:5px;text-align:center;">'.__('If you have any questions about this invoice, please contact us').'</td></tr>
											<tr><td style="padding-top:5px;text-align:center;"><a href="'.$base_url.'">'.$base_url.'</a></td></tr>
										</table>
									</td>
								</tr>
							</table>';

				$mail->send();
				
				return 1;
			} catch (Exception $e) {
				return 0;
			}
		}
	}
	
	//Delete data for Order
	public function deleteOrder(Request $request){
		$res = array();

		$id = $request->id;

		if($id != ''){
			Order_item::where('order_master_id', $id)->delete();
			$response = Order_master::where('id', $id)->delete();
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}	
}
