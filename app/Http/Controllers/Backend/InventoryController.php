<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class InventoryController extends Controller
{
    //Manage Stock page load
    public function getManageStockPageLoad() {
		
		$AllCount = Product::count();
		$InStockCount = Product::where('stock_status_id', '=', 1)->count();
		$OutOfStockCount = Product::where('stock_status_id', '=', 0)->count();
		
		$languageslist = DB::table('languages')->where('status', 1)->orderBy('language_name', 'asc')->get();

		$datalist = Product::orderBy('id','desc')->paginate(20);
			
        return view('backend.manage-stock', compact('AllCount', 'InStockCount', 'OutOfStockCount', 'languageslist', 'datalist'));		
	}
	
	//Get data for Manage Stock Pagination
	public function getManageStockTableData(Request $request){

		$search = $request->search;
		$status = $request->status;
		
		$language_code = $request->language_code;

		if($request->ajax()){

			if($search != ''){

				$datalist = Product::where(function ($query) use ($search){
						$query->where('title', 'like', '%'.$search.'%')
							->orWhere('sku', 'like', '%'.$search.'%')
							->orWhere('stock_qty', 'like', '%'.$search.'%');
					})
					->where(function ($query) use ($language_code){
						$query->whereRaw("lan = '".$language_code."' OR '".$language_code."' = '0'");
					})
					->where(function ($query) use ($status){
						$query->whereRaw("stock_status_id = '".$status."' OR '".$status."' = '2'");
					})
					->orderBy('id','desc')
					->paginate(20);
			}else{
				
				$datalist = Product::where(function ($query) use ($language_code){
						$query->whereRaw("lan = '".$language_code."' OR '".$language_code."' = '0'");
					})
					->where(function ($query) use ($status){
						$query->whereRaw("stock_status_id = '".$status."' OR '".$status."' = '2'");
					})
					->orderBy('id','desc')
					->paginate(20);
			}

			return view('backend.partials.manage_stock_table', compact('datalist'))->render();
		}
	}
	
	//Get data for Product by id
    public function getProductById(Request $request){

		$id = $request->id;
		
		$data = Product::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Save data for Inventory
    public function saveManageStockData(Request $request){
		$res = array();

		$id = $request->input('RecordId');
		$is_stock = $request->input('is_stock');
		$stock_status_id = $request->input('stock_status_id');
		$sku = $request->input('sku');
		$stock_qty = $request->input('stock_qty');

		$data = array(
			'is_stock' => $is_stock,
			'stock_status_id' => $stock_status_id,
			'sku' => $sku,
			'stock_qty' => $stock_qty
		);
		
		$response = Product::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }	
}
