<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bank_information;

class SellerSettingsController extends Controller
{
	//Sellers page load
    public function getSellerSettingsPageLoad(){
		$id = Auth::user()->id;

		$countrylist = DB::table('countries')->orderBy('country_name', 'asc')->get();

		$sellerData = DB::table('users')->where('id', $id)->get();
		$seller_data = array(
			'id' => '',
			'shop_name' => '',
			'shop_url' => '',
			'phone' => '',
			'address' => '',
			'city' => '',
			'state' => '',
			'zip_code' => '',
			'country_id' => '',
			'photo' => ''
		);
		
		foreach ($sellerData as $row){
			$seller_data['id'] = $row->id;
			$seller_data['shop_name'] = $row->shop_name;
			$seller_data['shop_url'] = $row->shop_url;
			$seller_data['phone'] = $row->phone;
			$seller_data['address'] = $row->address;
			$seller_data['city'] = $row->city;
			$seller_data['state'] = $row->state;
			$seller_data['zip_code'] = $row->zip_code;
			$seller_data['country_id'] = $row->country_id;
			$seller_data['photo'] = $row->photo;
		}
		
		$bankInformation = DB::table('bank_informations')->where('seller_id', $id)->get();
		$bank_information = array(
			'id' => '',
			'bank_name' => '',
			'bank_code' => '',
			'account_number' => '',
			'account_holder' => '',
			'paypal_id' => '',
			'description' => ''
		);
		
		foreach ($bankInformation as $row){
			$bank_information['id'] = $row->id;
			$bank_information['bank_name'] = $row->bank_name;
			$bank_information['bank_code'] = $row->bank_code;
			$bank_information['account_number'] = $row->account_number;
			$bank_information['account_holder'] = $row->account_holder;
			$bank_information['paypal_id'] = $row->paypal_id;
			$bank_information['description'] = $row->description;
		}

        return view('seller.settings', compact('countrylist', 'seller_data', 'bank_information'));
    }
	
	//Save data for Sellers
    public function saveSellersData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$shop_name = $request->input('shop_name');
		$shop_url = str_slug($request->input('shop_url'));
		$phone = $request->input('phone');
		$address = $request->input('address');
		$city = $request->input('city');
		$state = $request->input('state');
		$zip_code = $request->input('zip_code');
		$country_id = $request->input('country_id');
		$photo = $request->input('photo');
		
		$validator_array = array(
			'shop_name' => $request->input('shop_name'),
			'shop_url' => $request->input('shop_url'),
			'phone' => $request->input('phone'),
			'address' => $request->input('address'),
			'city' => $request->input('city'),
			'state' => $request->input('state'),
			'zip_code' => $request->input('zip_code'),
			'country_id' => $request->input('country_id')
		);
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'shop_name' => 'required',
			'shop_url' => 'required',
			'phone' => 'required',
			'address' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zip_code' => 'required',
			'country_id' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('shop_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('shop_name');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('shop_url')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('shop_url');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('phone')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('phone');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('address')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('address');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('city')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('city');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('state')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('state');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('zip_code')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('zip_code');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('country_id')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('country_id');
			$res['id'] = '';
			return response()->json($res);
		}

		$data = array(
			'shop_name' => $shop_name,
			'shop_url' => $shop_url,
			'phone' => $phone,
			'address' => $address,
			'city' => $city,
			'state' => $state,
			'zip_code' => $zip_code,
			'country_id' => $country_id,
			'photo' => $photo
		);

		$response = User::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
			$res['id'] = $id;
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
			$res['id'] = '';
		}
		
		return response()->json($res);
    }
	
	//Save data for Bank Information
    public function saveBankInformationData(Request $request){
		$res = array();
		
		$id = $request->input('bank_information_id');
		$seller_id = $request->input('seller_id');
		$bank_name = $request->input('bank_name');
		$bank_code = $request->input('bank_code');
		$account_number = $request->input('account_number');
		$account_holder = $request->input('account_holder');
		$paypal_id = $request->input('paypal_id');
		$description = $request->input('description');
		
		$data = array(
			'seller_id' => $seller_id,
			'bank_name' => $bank_name,
			'bank_code' => $bank_code,
			'account_number' => $account_number,
			'account_holder' => $account_holder,
			'paypal_id' => $paypal_id,
			'description' => $description
		);

		if($id ==''){
			$response = Bank_information::create($data)->id;
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
				$res['id'] = $response;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
				$res['id'] = '';
			}
		}else{
			$response = Bank_information::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				$res['id'] = $id;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
				$res['id'] = '';
			}
		}
		
		return response()->json($res);
    }	
		
	//has shop url Slug
    public function hasShopSlug(Request $request){
		$res = array();
		
		$slug = str_slug($request->shop_url);

		$res['slug'] = $slug;

		return response()->json($res);
	}
}
