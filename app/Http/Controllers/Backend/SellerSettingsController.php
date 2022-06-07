<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tp_option;

class SellerSettingsController extends Controller
{
    //Seller Settings page load
    public function getSellerSettingsPageLoad() {
		
		$datalist = Tp_option::where('option_name', 'seller_settings')->get();
		$id = '';
		$option_value = '';
		foreach ($datalist as $row){
			$id = $row->id;
			$option_value = json_decode($row->option_value);
		}

		$data = array();
		if($id != ''){
			$data['fee_withdrawal'] = $option_value->fee_withdrawal;
			$data['product_auto_publish'] = $option_value->product_auto_publish;
			$data['seller_auto_active'] = $option_value->seller_auto_active;
		}else{
			$data['fee_withdrawal'] = '';
			$data['product_auto_publish'] = 0;
			$data['seller_auto_active'] = 0;
		}

		$datalist = $data;
		
        return view('backend.seller-settings', compact('datalist'));
    }
	
	//Save data for seller setting
    public function SellerSettingsSave(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$fee_withdrawal = $request->input('fee_withdrawal');
		$productAutoPublish = $request->input('product_auto_publish');
		$sellerAutoActive = $request->input('seller_auto_active');
		
		if($productAutoPublish == 'true' || $productAutoPublish == 'on'){
			$product_auto_publish = 1;
		}else{
			$product_auto_publish = 0;
		}
		
		if($sellerAutoActive == 'true' || $sellerAutoActive == 'on'){
			$seller_auto_active = 1;
		}else{
			$seller_auto_active = 0;
		}

		$validator_array = array(
			'fee_withdrawal' => $request->input('fee_withdrawal')
		);

		$validator = Validator::make($validator_array, [
			'fee_withdrawal' => 'required'
		]);

		$errors = $validator->errors();
		
		if($errors->has('fee_withdrawal')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('fee_withdrawal');
			return response()->json($res);
		}
		
		$option_value = array(
			'fee_withdrawal' => $fee_withdrawal,
			'product_auto_publish' => $product_auto_publish,
			'seller_auto_active' => $seller_auto_active
		);

		$data = array(
			'option_name' => 'seller_settings',
			'option_value' => json_encode($option_value)
		);
		
		$gData = Tp_option::where('option_name', 'seller_settings')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
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
}
