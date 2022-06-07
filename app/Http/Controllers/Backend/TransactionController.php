<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    //Transactions page load
    public function getTransactionsPageLoad() {
		
		$order_status_list = DB::table('order_status')->get();
		
		$datalist = DB::table('order_masters as a')
			->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
			->join('users as c', 'a.seller_id', '=', 'c.id')
			->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
			->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
			->join('order_status as f', 'a.order_status_id', '=', 'f.id')
			->join('order_items as g', 'a.id', '=', 'g.order_master_id')
			->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
			->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
			->orderBy('a.created_at','desc')
			->paginate(25);
			
        return view('backend.transactions', compact('order_status_list', 'datalist'));		
	}
	
	//Get data for Transactions Pagination
	public function getTransactionsTableData(Request $request){

		$search = $request->search;
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
					->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
					->where(function ($query) use ($search){
						$query->where('a.order_no', 'like', '%'.$search.'%')
							->orWhere('a.transaction_no', 'like', '%'.$search.'%')
							->orWhere('a.created_at', 'like', '%'.$search.'%')
							->orWhere('b.name', 'like', '%'.$search.'%')
							->orWhere('c.shop_name', 'like', '%'.$search.'%')
							->orWhere('d.method_name', 'like', '%'.$search.'%')
							->orWhere('e.pstatus_name', 'like', '%'.$search.'%')
							->orWhere('f.ostatus_name', 'like', '%'.$search.'%')
							->orWhere('b.email', 'like', '%'.$search.'%');
					})
					->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
					->orderBy('a.created_at','desc')
					->paginate(25);
					
			}else{
				if(($start_date != '') && ($end_date != '')){
					
					$datalist = DB::table('order_masters as a')
						->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
						->join('users as c', 'a.seller_id', '=', 'c.id')
						->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
						->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
						->join('order_status as f', 'a.order_status_id', '=', 'f.id')
						->join('order_items as g', 'a.id', '=', 'g.order_master_id')
						->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
						->whereBetween('a.created_at', [$start_date, $end_date])
						->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
						->orderBy('a.created_at','desc')
						->paginate(25);
						
				}else{
					
					$datalist = DB::table('order_masters as a')
						->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
						->join('users as c', 'a.seller_id', '=', 'c.id')
						->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
						->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
						->join('order_status as f', 'a.order_status_id', '=', 'f.id')
						->join('order_items as g', 'a.id', '=', 'g.order_master_id')
						->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
						->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
						->orderBy('a.created_at','desc')
						->paginate(25);
				}
			}

			return view('backend.partials.transactions_table', compact('datalist'))->render();
		}
	}
}
