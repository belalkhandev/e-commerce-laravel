<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Media_option;
use App\Models\Bank_information;
use App\Models\Withdrawal;
use App\Models\Withdrawal_image;
use App\Models\Product;
use App\Models\Pro_image;
use App\Models\Related_product;
use App\Models\Review;
use App\Models\Order_item;
use App\Models\Order_master;

class SellerController extends Controller
{
    public function LoadSellerRegister()
    {
        return view('frontend.seller-register');
    }
	
    public function SellerRegister(Request $request)
    {
		$gtext = gtext();

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['is_recaptcha'];
		if($recaptcha == 1){
			$request->validate([
				'g-recaptcha-response' => 'required',
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed|min:6',
				'shop_name' => 'required',
				'shop_url' => 'required',
				'shop_phone' => 'required',
			]);
			
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				return redirect("seller/register")->withFail(__('The recaptcha field is required'));
			}
		}else{
			$request->validate([
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed|min:6',
				'shop_name' => 'required',
				'shop_url' => 'required',
				'shop_phone' => 'required',
			]);
		}
		
		$SellerSettings = gSellerSettings();
		if($SellerSettings['seller_auto_active'] == 1){
			$status_id = 1;
		}else{
			$status_id = 2;
		}
		
		$data = array(
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => Hash::make($request->input('password')),
			'bactive' => base64_encode($request->input('password')),
			'shop_name' => $request->input('shop_name'),
			'shop_url' => $request->input('shop_url'),
			'phone' => $request->input('shop_phone'),
			'status_id' => $status_id,
			'role_id' => 3
		);
		
		$response = User::create($data);
		
		if($response){

			if($gtext['is_mailchimp'] == 1){
				$name = $request->input('name');
				$email_address = $request->input('email');

				$HTTP_Status = self::MailChimpSubscriber($name, $email_address);
				if($HTTP_Status == 200){
					$SubscriberCount = Subscriber::where('email_address', '=', $email_address)->count();
					if($SubscriberCount == 0){
						$data = array(
							'email_address' => $email_address,
							'first_name' => $name,
							'last_name' => $name,
							'status' => 'subscribed'
						);
						Subscriber::create($data);
					}
				}
			}
			
			if($status_id == 1){
				return redirect()->back()->withSuccess(__('Thanks! You have register successfully. Please login.'));
			}else{
				return redirect()->back()->withSuccess(__('Thanks! You have register successfully. Your account is pending for review.'));
			}

		}else{
			return redirect()->back()->withFail(__('Oops! You are failed registration. Please try again.'));
		}
    }
	
	//MailChimp Subscriber
    public function MailChimpSubscriber($name, $email){
		$gtext = gtext();

		$apiKey = $gtext['mailchimp_api_key'];
		$listId = $gtext['audience_id'];
		
        //Create mailchimp API url
        $memberId = md5(strtolower($email));
        $dataCenter = substr($apiKey, strpos($apiKey, '-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId; 

        //Member info
        $data = array(
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields'  => [
                'FNAME'     => $name,
                'LNAME'     => $name
            ]
        );

        $jsonString = json_encode($data);

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
		
		return $httpCode;
    }
	
	//has shop url Slug
    public function hasShopSlug(Request $request){
		$res = array();
		
		$slug = str_slug($request->shop_url);
        $count = User::where('shop_url', $slug) ->count();
		if($count == 0){
			$res['slug'] = $slug;
			$res['count'] = 0;
		}else{
			$res['slug'] = $slug;
			$res['count'] = 1;
		}
		
		return response()->json($res);
	}
	
	//Sellers page load
    public function getSellersPageLoad(){
		$statuslist = DB::table('user_status')->orderBy('id', 'asc')->get();
		$countrylist = DB::table('countries')->orderBy('country_name', 'asc')->get();
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
		$AllCount = User::where('role_id', '=', 3)->count();
		$ActiveCount = User::where('status_id', '=', 1)->where('role_id', '=', 3)->count();
		$InactiveCount = User::where('status_id', '=', 2)->where('role_id', '=', 3)->count();
		
		$datalist = DB::table('users')
			->join('user_roles', 'users.role_id', '=', 'user_roles.id')
			->join('user_status', 'users.status_id', '=', 'user_status.id')
			->select('users.*', 'user_roles.role', 'user_status.status')
			->where('users.role_id', 3)
			->orderBy('users.id','desc')
			->paginate(20);
			
        return view('backend.sellers', compact('AllCount', 'ActiveCount', 'InactiveCount', 'statuslist', 'countrylist', 'media_datalist', 'datalist'));
    }
	
	//Get data for Sellers Pagination
	public function getSellersTableData(Request $request){
		
		$status = $request->status;
		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
						
				$datalist = DB::table('users')
					->join('user_roles', 'users.role_id', '=', 'user_roles.id')
					->join('user_status', 'users.status_id', '=', 'user_status.id')
					->select('users.*', 'user_roles.role', 'user_status.status')
					->where(function ($query) use ($search){
						$query->where('name', 'like', '%'.$search.'%')
							->orWhere('email', 'like', '%'.$search.'%')
							->orWhere('phone', 'like', '%'.$search.'%')
							->orWhere('shop_name', 'like', '%'.$search.'%')
							->orWhere('shop_url', 'like', '%'.$search.'%')
							->orWhere('address', 'like', '%'.$search.'%')
							->orWhere('city', 'like', '%'.$search.'%')
							->orWhere('state', 'like', '%'.$search.'%');
					})
					->where(function ($query) use ($status){
						$query->whereRaw("users.status_id = '".$status."' OR '".$status."' = '0'");
					})
					->where(function ($query) use ($status){
						$query->whereRaw("users.role_id = 3");
					})
					->orderBy('users.id','desc')
					->paginate(20);
			}else{
				
			$datalist = DB::table('users')
				->join('user_roles', 'users.role_id', '=', 'user_roles.id')
				->join('user_status', 'users.status_id', '=', 'user_status.id')
				->select('users.*', 'user_roles.role', 'user_status.status')
				->where(function ($query) use ($status){
					$query->whereRaw("users.status_id = '".$status."' OR '".$status."' = '0'");
				})
				->where(function ($query) use ($status){
					$query->whereRaw("users.role_id = 3");
				})
				->orderBy('users.id','desc')
				->paginate(20);
			}

			return view('backend.partials.sellers_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Sellers
    public function saveSellersData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');
		$shop_name = $request->input('shop_name');
		$shop_url = str_slug($request->input('shop_url'));
		$phone = $request->input('phone');
		$address = $request->input('address');
		$city = $request->input('city');
		$state = $request->input('state');
		$zip_code = $request->input('zip_code');
		$country_id = $request->input('country_id');
		$status_id = $request->input('status_id');
		$photo = $request->input('photo');
		
		$validator_array = array(
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => $request->input('password'),
			'shop_name' => $request->input('shop_name'),
			'shop_url' => $request->input('shop_url'),
			'phone' => $request->input('phone'),
			'address' => $request->input('address'),
			'city' => $request->input('city'),
			'state' => $request->input('state'),
			'zip_code' => $request->input('zip_code'),
			'country_id' => $request->input('country_id'),
		);
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'name' => 'required|max:191',
			'email' => 'required|max:191|unique:users,email' . $rId,
			'password' => 'required|max:191',
			'shop_name' => 'required',
			'shop_url' => 'required',
			'phone' => 'required',
			'address' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zip_code' => 'required',
			'country_id' => 'required',
		]);

		$errors = $validator->errors();

		if($errors->has('name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('name');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('email')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('email');
			$res['id'] = '';
			return response()->json($res);
		}
		
		if($errors->has('password')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('password');
			$res['id'] = '';
			return response()->json($res);
		}
		
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
			'name' => $name,
			'email' => $email,
			'password' => Hash::make($password),
			'shop_name' => $shop_name,
			'shop_url' => $shop_url,
			'phone' => $phone,
			'address' => $address,
			'city' => $city,
			'state' => $state,
			'zip_code' => $zip_code,
			'country_id' => $country_id,
			'status_id' => $status_id,
			'photo' => $photo,
			'role_id' => 3,
			'bactive' => base64_encode($password)
		);

		if($id ==''){
			$response = User::create($data)->id;
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
	
	//Get data for Sellers by id
    public function getSellerById(Request $request){
		$gtext = gtext();
		$lan = glan();
		
		$datalist = array(
			'seller_data' => '', 
			'bank_information' => '', 
			'CurrentBalance' => 0,
			'OrderBalance' => 0,
			'WithdrawalBalance' => 0,
			'TotalProducts' => 0
		);
		
		$id = $request->id;
		
		$data = DB::table('users')->where('id', $id)->first();
		$data->bactive = base64_decode($data->bactive);
		$data->created_at = date('d F, Y', strtotime($data->created_at));
		
		$bankInfoData = DB::table('bank_informations')->where('seller_id', $id)->first();
		
		$datalist['seller_data'] = $data;
		$datalist['bank_information'] = $bankInfoData;
		
		$sql = "SELECT (IFNULL(SUM(b.total_price), 0) + IFNULL(SUM(b.tax), 0)) AS OrderBalance
		FROM order_masters a
		INNER JOIN order_items b ON a.id = b.order_master_id
		WHERE a.payment_status_id = 1
		AND a.order_status_id = 4
		AND a.seller_id = '".$id."';";
		$aRow = DB::select(DB::raw($sql));
		$OrderBalance = $aRow[0]->OrderBalance;
		
		$sql1 = "SELECT (IFNULL(SUM(amount), 0) + IFNULL(SUM(fee_amount), 0)) AS WithdrawalBalance
		FROM withdrawals 
		WHERE seller_id = '".$id."'
		AND status_id = 3;";
		$aRow1 = DB::select(DB::raw($sql1));
		$WithdrawalBalance = $aRow1[0]->WithdrawalBalance;
		$OrderWithdrawalBalance = ($OrderBalance - $WithdrawalBalance);

		if($gtext['currency_position'] == 'left'){
			$datalist['CurrentBalance'] = $gtext['currency_icon'].number_format($OrderWithdrawalBalance, 2);
			$datalist['OrderBalance'] = $gtext['currency_icon'].number_format($OrderBalance, 2);
			$datalist['WithdrawalBalance'] = $gtext['currency_icon'].number_format($WithdrawalBalance, 2);
		}else{
			$datalist['CurrentBalance'] = number_format($OrderWithdrawalBalance, 2).$gtext['currency_icon'];
			$datalist['OrderBalance'] = number_format($OrderBalance, 2).$gtext['currency_icon'];
			$datalist['WithdrawalBalance'] = number_format($WithdrawalBalance, 2).$gtext['currency_icon'];
		}
		
		$sql2 = "SELECT COUNT(id) AS TotalProducts
		FROM products 
		WHERE user_id = '".$id."'
		AND is_publish = 1
		AND lan = '".$lan."';";
		$aRow2 = DB::select(DB::raw($sql2));
		$datalist['TotalProducts'] = $aRow2[0]->TotalProducts;
		
		return response()->json($datalist);
	}
	
	//Delete data for Sellers
	public function deleteSeller(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			
			$aRows = Product::where('user_id', $id)->get();
			$idsArray = array();
			foreach($aRows as $key => $row){
				$idsArray[$key] = $row->id;
			}
			
			$withdrawalsRows = Withdrawal::where('seller_id', $id)->get();
			$withdrawalIdsArray = array();
			foreach($withdrawalsRows as $key => $row){
				$withdrawalIdsArray[$key] = $row->id;
			}
			
			Order_item::where('seller_id', $id)->delete();
			Order_master::where('seller_id', $id)->delete();
			
			Withdrawal_image::whereIn('withdrawal_id', $withdrawalIdsArray)->delete();
			Withdrawal::where('seller_id', $id)->delete();

			Review::whereIn('item_id', $idsArray)->delete();
			Related_product::whereIn('product_id', $idsArray)->delete();
			Pro_image::whereIn('product_id', $idsArray)->delete();
			Product::where('user_id', $id)->delete();
			
			Bank_information::where('seller_id', $id)->delete();
			$response = User::where('id', $id)->delete();
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
	
	//Bulk Action for Sellers
	public function bulkActionSellers(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'active'){
			$response = User::whereIn('id', $idsArray)->update(['status_id' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'inactive'){
			
			$response = User::whereIn('id', $idsArray)->update(['status_id' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			
			$aRows = Product::whereIn('user_id', $idsArray)->get();
			$itemIdsArray = array();
			foreach($aRows as $key => $row){
				$itemIdsArray[$key] = $row->id;
			}
			
			$withdrawalsRows = Withdrawal::whereIn('seller_id', $idsArray)->get();
			$withdrawalIdsArray = array();
			foreach($withdrawalsRows as $key => $row){
				$withdrawalIdsArray[$key] = $row->id;
			}
			
			Order_item::whereIn('seller_id', $idsArray)->delete();
			Order_master::whereIn('seller_id', $idsArray)->delete();
			
			Withdrawal_image::whereIn('withdrawal_id', $withdrawalIdsArray)->delete();
			Withdrawal::whereIn('seller_id', $idsArray)->delete();

			Review::whereIn('item_id', $itemIdsArray)->delete();
			Related_product::whereIn('product_id', $itemIdsArray)->delete();
			Pro_image::whereIn('product_id', $itemIdsArray)->delete();
			
			Product::whereIn('user_id', $idsArray)->delete();

			Bank_information::whereIn('seller_id', $idsArray)->delete();
			$response = User::whereIn('id', $idsArray)->delete();
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
