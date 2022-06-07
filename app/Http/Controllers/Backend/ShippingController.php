<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Shipping;

class ShippingController extends Controller
{
    //Shipping page load
    public function getShippingPageLoad() {

		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('shipping')
			->join('tp_status', 'shipping.is_publish', '=', 'tp_status.id')
			->select('shipping.id', 'shipping.title', 'shipping.shipping_fee', 'shipping.desc', 'shipping.is_publish', 'tp_status.status')
			->orderBy('shipping.id','desc')
			->paginate(10);

        return view('backend.shipping', compact('statuslist', 'datalist'));
    }
	
	//Get data for Shipping Pagination
	public function getShippingTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('shipping')
					->join('tp_status', 'shipping.is_publish', '=', 'tp_status.id')
					->select('shipping.id', 'shipping.title', 'shipping.shipping_fee', 'shipping.desc', 'shipping.is_publish', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('title', 'like', '%'.$search.'%')
							->orWhere('shipping_fee', 'like', '%'.$search.'%')
							->orWhere('desc', 'like', '%'.$search.'%');
					})
					->orderBy('shipping.id','desc')
					->paginate(10);
			}else{
				
				$datalist = DB::table('shipping')
					->join('tp_status', 'shipping.is_publish', '=', 'tp_status.id')
					->select('shipping.id', 'shipping.title', 'shipping.shipping_fee', 'shipping.desc', 'shipping.is_publish', 'tp_status.status')
					->orderBy('shipping.id','desc')
					->paginate(10);
			}

			return view('backend.partials.shipping_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Shipping
    public function saveShippingData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$title = $request->input('title');
		$shipping_fee = $request->input('shipping_fee');
		$desc = $request->input('desc');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'title' => $request->input('title'),
			'shipping_fee' => $request->input('shipping_fee'),
			'is_publish' => $request->input('is_publish')
		);
		
		$validator = Validator::make($validator_array, [
			'title' => 'required|max:191',
			'shipping_fee' => 'required',
			'is_publish' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('title');
			return response()->json($res);
		}
		
		if($errors->has('shipping_fee')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('shipping_fee');
			return response()->json($res);
		}

		if($errors->has('is_publish')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('is_publish');
			return response()->json($res);
		}

		$data = array(
			'title' => $title,
			'shipping_fee' => $shipping_fee,
			'desc' => $desc,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = Shipping::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Shipping::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Get data for Shipping by id
    public function getShippingById(Request $request){

		$id = $request->id;
		
		$data = Shipping::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Shipping
	public function deleteShipping(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Shipping::where('id', $id)->delete();
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
	
	//Bulk Action for Shipping
	public function bulkActionShipping(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Shipping::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Shipping::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Shipping::whereIn('id', $idsArray)->delete();
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
