<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderTrackingController extends Controller
{
    //get Order Tracking
    public function getOrderTracking(Request $request){
		
		$order_no = $request->input('order_no');
		$email = $request->input('email');
		
		if(($order_no != '') && ($email != '')){
				
			$masterData = DB::table('order_masters as a')
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
					'a.email as customer_email', 
					'a.name as customer_name', 
					'a.phone as customer_phone', 
					'a.country', 
					'a.state', 
					'a.city', 
					'a.address as customer_address',  
					'd.method_name', 
					'e.pstatus_name', 
					'f.ostatus_name')
				->where('a.order_no', $order_no)
				->where('a.email', $email)
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
					'a.city', 
					'a.email', 
					'a.address', 
					'a.shipping_fee',  
					'a.order_no', 
					'a.id')
				->get();

			$datalist = DB::table('order_items')
				->join('products', 'order_items.product_id', '=', 'products.id')
				->join('order_masters', 'order_items.order_master_id', '=', 'order_masters.id')
				->select('order_items.*', 'products.title', 'products.f_thumbnail', 'products.id')
				->where('order_masters.order_no', $order_no)
				->where('order_masters.email', $email)
				->get();
				
			$isfind = "block";
		}else{
			$masterData = array();
			$datalist = array();
			$isfind = "none";
		}
		
        return view('frontend.order-tracking', compact('masterData', 'datalist', 'isfind'));
    }	
}
