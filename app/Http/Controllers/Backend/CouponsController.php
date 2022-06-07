<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;

class CouponsController extends Controller
{
    //Coupons page load
    public function getCouponsPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('coupons')
			->join('tp_status', 'coupons.is_publish', '=', 'tp_status.id')
			->select('coupons.id', 'coupons.code', 'coupons.expire_date', 'coupons.percentage', 'coupons.is_publish', 'tp_status.status')
			->orderBy('coupons.id','desc')
			->paginate(10);

        return view('backend.coupons', compact('statuslist', 'datalist'));
    }

	//Get data for Coupons Pagination
	public function getCouponsTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = DB::table('coupons')
					->join('tp_status', 'coupons.is_publish', '=', 'tp_status.id')
					->select('coupons.id', 'coupons.code', 'coupons.expire_date', 'coupons.percentage', 'coupons.is_publish', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('code', 'like', '%'.$search.'%')
							->orWhere('expire_date', 'like', '%'.$search.'%')
							->orWhere('percentage', 'like', '%'.$search.'%');
					})
					->orderBy('coupons.id','desc')
					->paginate(10);
			}else{
				
				$datalist = DB::table('coupons')
					->join('tp_status', 'coupons.is_publish', '=', 'tp_status.id')
					->select('coupons.id', 'coupons.code', 'coupons.expire_date', 'coupons.percentage', 'coupons.is_publish', 'tp_status.status')
					->orderBy('coupons.id','desc')
					->paginate(10);
			}

			return view('backend.partials.coupons_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Coupons
    public function saveCouponsData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$code = $request->input('code');
		$expire_date = $request->input('expire_date');
		$percentage = $request->input('percentage');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'code' => $request->input('code'),
			'expire_date' => $request->input('expire_date'),
			'percentage' => $request->input('percentage'),
			'is_publish' => $request->input('is_publish')
		);
		
		$validator = Validator::make($validator_array, [
			'code' => 'required|max:191',
			'expire_date' => 'required|max:191',
			'percentage' => 'required|max:100',
			'is_publish' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('code')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('code');
			return response()->json($res);
		}
		
		if($errors->has('expire_date')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('expire_date');
			return response()->json($res);
		}
		
		if($errors->has('percentage')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('percentage');
			return response()->json($res);
		}

		if($errors->has('is_publish')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('is_publish');
			return response()->json($res);
		}

		$data = array(
			'code' => $code,
			'expire_date' => $expire_date,
			'percentage' => $percentage,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = Coupon::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Coupon::where('id', $id)->update($data);
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
	
	//Get data for Coupon by id
    public function getCouponsById(Request $request){

		$id = $request->id;
		
		$data = Coupon::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Coupon
	public function deleteCoupons(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Coupon::where('id', $id)->delete();
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
	
	//Bulk Action for Coupons
	public function bulkActionCoupons(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Coupon::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Coupon::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Coupon::whereIn('id', $idsArray)->delete();
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
