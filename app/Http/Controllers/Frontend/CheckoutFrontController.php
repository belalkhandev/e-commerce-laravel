<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order_master;
use App\Models\Order_item;
use App\Models\Country;
use App\Models\Shipping;
use Cart;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class CheckoutFrontController extends Controller
{
    public function LoadCheckout()
    {
		$country_list = Country::where('is_publish', '=', 1)->orderBy('country_name', 'ASC')->get();
		$shipping_list = Shipping::where('is_publish', '=', 1)->get();
				
        return view('frontend.checkout', compact('country_list', 'shipping_list'));
    }
	
    public function LoadThank()
    {	
        return view('frontend.thank');
    }
	
    public function LoadMakeOrder(Request $request)
    {
		$res = array();
		$gtext = gtext();
		$gtax = getTax();

		$total_qty = Cart::instance('shopping')->count();
		
		if($total_qty == 0){
			$res['msgType'] = 'error';
			$res['msg'] = array('oneError' => array(__('Oops! Your order is failed. Please product add to cart.')));
			return response()->json($res);
		}
		
		$customer_id = '';
		
		$newaccount = $request->input('new_account');
		
		if ($newaccount == 'true' || $newaccount == 'on') {
			$new_account = 1;
		}else {
			$new_account = 0;
		}

		$payment_method_id = $request->input('payment_method');
		$shipping_method_id = $request->input('shipping_method');

		if($new_account == 1){
			
			$validator = Validator::make($request->all(),[
				'name' => 'required',
				'phone' => 'required',
				'country' => 'required',
				'state' => 'required',
				'zip_code' => 'required',
				'city' => 'required',
				'address' => 'required',
				'payment_method' => 'required',
				'shipping_method' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed',
			]);

			if(!$validator->passes()){
				$res['msgType'] = 'error';
				$res['msg'] = $validator->errors()->toArray();
				return response()->json($res);
			}

			$userData = array(
				'name' => $request->input('name'),
				'email' => $request->input('email'),
				'phone' => $request->input('phone'),
				'address' => $request->input('address'),
				'state' => $request->input('state'),
				'zip_code' => $request->input('zip_code'),
				'city' => $request->input('city'),
				'password' => Hash::make($request->input('password')),
				'bactive' => base64_encode($request->input('password')),
				'status_id' => 1,
				'role_id' => 2
			);
			
			$customer_id = User::create($userData)->id;
			
		}else{
			
			$validator = Validator::make($request->all(),[
				'name' => 'required',
				'email' => 'required',
				'phone' => 'required',
				'country' => 'required',
				'state' => 'required',
				'zip_code' => 'required',
				'city' => 'required',
				'address' => 'required',
				'payment_method' => 'required',
				'shipping_method' => 'required'
			]);
			
			if(!$validator->passes()){
				$res['msgType'] = 'error';
				$res['msg'] = $validator->errors()->toArray();
				return response()->json($res);
			}

			$customer_id = $request->input('customer_id');
		}

		$shipping_list = Shipping::where('id', '=', $shipping_method_id)->where('is_publish', '=', 1)->get();
		$shipping_title = NULL;
		$shipping_fee = NULL;
		foreach ($shipping_list as $row){
			$shipping_title = $row->title;
			$shipping_fee = comma_remove($row->shipping_fee);
		}

		$total_amount = Cart::instance('shopping')->total();

		$CartDataList = Cart::instance('shopping')->content();
		$UniqueDataArray = array();
		$key = 0;
		foreach($CartDataList as $row){
			
			$UniqueDataArray[$key] = $row->options->seller_id;
			
			$key++;
		}
		
		$UniqueDataList = array_unique($UniqueDataArray);
		
		$MasterData = array();
		$i = 1;
		foreach($UniqueDataList as $row){
			
			$random_code = random_int(100000, 999999);
			
			$order_no = 'ORD-'.$random_code.$i;
			$seller_id = $row;
			$data = array(
				'order_no' => $order_no,
				'customer_id' => $customer_id,
				'seller_id' => $seller_id,
				'payment_method_id' => $payment_method_id,
				'payment_status_id' => 2,
				'order_status_id' => 1,
				'shipping_title' => $shipping_title,
				'shipping_fee' => $shipping_fee,
				'name' => $request->input('name'),
				'email' => $request->input('email'),
				'phone' => $request->input('phone'),
				'country' => $request->input('country'),
				'state' => $request->input('state'),
				'zip_code' => $request->input('zip_code'),
				'city' => $request->input('city'),
				'address' => $request->input('address'),
				'comments' => $request->input('comments')
			);
			
			$order_master_id = Order_master::create($data)->id;
			
			$i++;
			
			$MasterData[$seller_id] = $order_master_id;
		}

		$tax_rate = $gtax['percentage'];
		
		$index = 0;
		$CartDataList = Cart::instance('shopping')->content();
		foreach($CartDataList as $row){

			$seller_id = $row->options->seller_id;
			$order_master_id = $MasterData[$seller_id];
			
			$total_price = $row->price*$row->qty;
			
			$tax = (($total_price*$tax_rate)/100);
			
			$OrderItemData = array(
				'order_master_id' => $order_master_id,
				'customer_id' => $customer_id,
				'seller_id' => $seller_id,
				'product_id' => $row->id,
				'variation_size' => $row->options->size,
				'variation_color' => $row->options->color,
				'quantity' => comma_remove($row->qty),
				'price' => comma_remove($row->price),
				'total_price' => comma_remove($total_price),
				'tax' => comma_remove($tax)
			);
			
			Order_item::create($OrderItemData);
			
			$index++;
		}
		
		if($index>0){
			$intent = '';
			//Stripe
			if($payment_method_id == 3){
				if($gtext['stripe_isenable'] == 1){
					$stripe_secret = $gtext['stripe_secret'];
					$totalAmount = comma_remove($total_amount);
					
					// Enter Your Stripe Secret
					\Stripe\Stripe::setApiKey($stripe_secret);
							
					$amount = $totalAmount;
					$amount *= 100;
					$amount = (int) $amount;
					if($gtext['stripe_currency'] !=''){
						$currency = $gtext['stripe_currency'];
					}else{
						$currency = 'usd';
					}
					
					$description = 'Total Quantity:'.comma_remove($total_qty);
					
					$payment_intent = \Stripe\PaymentIntent::create([
						'amount' => $amount,
						'currency' => $currency,
						'description' => $description,
						'payment_method_types' => ['card']
					]);
					$intent = $payment_intent->client_secret;
				}
			}else{
				$intent = '';
			}

			Cart::instance('shopping')->destroy();
			
			 if($gtext['ismail'] == 1){
				foreach($MasterData as $row){
					$orderMaster_id = $row;
					self::orderNotify($orderMaster_id);
				}
			}
			
			$res['msgType'] = 'success';
			$res['msg'] = __('Your order is successfully.');
			$res['intent'] = $intent;
			return response()->json($res);
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Oops! Your order is failed. Please try again.');
			return response()->json($res);
		}
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
				'a.zip_code', 
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
				$mail->Subject = $mdata->order_no.' - '. __('Your order is successfully.');
				
				$mail->Body = '<table style="background-color:#edf2f7;color:#111111;padding:40px 0px;line-height:24px;font-size:14px;" border="0" cellpadding="0" cellspacing="0" width="100%">	
								<tr>
									<td>
										<table style="background-color:#fff;max-width:1000px;margin:0 auto;padding:30px;" border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr><td style="font-size:40px;border-bottom:1px solid #ddd;padding-bottom:25px;font-weight:bold;text-align:center;">'.$gtext['company'].'</td></tr>
											<tr><td style="font-size:25px;font-weight:bold;padding:30px 0px 5px 0px;">'.__('Hi').' '.$mdata->customer_name.'</td></tr>
											<tr><td>'.__('We have received your order and will contact you as soon as your package is shipped. You can find your purchase information below.').'</td></tr>
											<tr>
												<td style="padding-top:30px;padding-bottom:20px;">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td style="vertical-align: top;">
																<table border="0" cellpadding="3" cellspacing="0" width="100%">
																	<tr><td style="font-size:16px;font-weight:bold;">'.__('BILL TO').':</td></tr>
																	<tr><td><strong>'.$mdata->customer_name.'</strong></td></tr>
																	<tr><td>'.$mdata->customer_address.'</td></tr>
																	<tr><td>'.$mdata->city.', '.$mdata->state.', '.$mdata->zip_code.', '.$mdata->country.'</td></tr>
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
}
