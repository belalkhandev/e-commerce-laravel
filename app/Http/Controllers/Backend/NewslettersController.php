<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tp_option;
use App\Models\Subscriber;

class NewslettersController extends Controller
{
	
    //Subscribers page load
    public function getSubscribers(){
		
		$datalist = Subscriber::orderBy('id','desc')->paginate(20);

        return view('backend.subscribers', compact('datalist'));
    }
	
	//Get data for Subscriber Pagination
	public function getSubscriberTableData(Request $request){

		$search = $request->search;

		if($request->ajax()){

			if($search != ''){
				$datalist = Subscriber::where(function ($query) use ($search){
						$query->where('email_address', 'like', '%'.$search.'%')
							->orWhere('status', 'like', '%'.$search.'%');
					})
					->orderBy('id','desc')->paginate(20);
			}else{
				$datalist = Subscriber::orderBy('id','desc')->paginate(20);
			}

			return view('backend.partials.subscribers_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Subscriber
    public function saveSubscriberData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$email_address = $request->input('email_address');
		$status = $request->input('status');

		$validator_array = array(
			'email_address' => $request->input('email_address'),
			'status' => $request->input('status')
		);

		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'email_address' => 'required|email|max:191|unique:subscribers,email_address' . $rId,
			'status' => 'required'
		]);
		
		$errors = $validator->errors();

		if($errors->has('email_address')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('email_address');
			return response()->json($res);
		}
		
		if($errors->has('status')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('status');
			return response()->json($res);
		}
		
		$data = array(
			'email_address' => $email_address,
			'status' => $status
		);

		$gtext = gtext();
		if($gtext['is_mailchimp'] == 1){
			
			$HTTP_Status = self::MailChimpSubscriber($email_address, $status);

			if($id ==''){
				
				if($HTTP_Status == 200){
					$response = Subscriber::create($data);
					if($response){
						$res['msgType'] = 'success';
						$res['msg'] = __('You have successfully subscribed.');
					}else{
						$res['msgType'] = 'error';
						$res['msg'] = __('Some problem occurred, please try again.');
					}
				}elseif($HTTP_Status == 214){
					$res['msgType'] = 'error';
					$res['msg'] = __('You are already subscribed.');
				}elseif($HTTP_Status == 401){
					$res['msgType'] = 'error';
					$res['msg'] = __('MailChimp API Key Invalid.');
				}elseif($HTTP_Status == 404){
					$res['msgType'] = 'error';
					$res['msg'] = __('The requested resource could not be found.');
				}else{
					$res['msgType'] = 'error';
					$res['msg'] = __('Some problem occurred, please try again.');
				}

			}else{
				if($HTTP_Status == 200){
					$response = Subscriber::where('id', $id)->update($data);
					if($response){
						$res['msgType'] = 'success';
						$res['msg'] = __('Data Updated Successfully');
					}else{
						$res['msgType'] = 'error';
						$res['msg'] = __('Some problem occurred, please try again.');
					}
				}elseif($HTTP_Status == 214){
					$res['msgType'] = 'error';
					$res['msg'] = __('You are already subscribed.');
				}elseif($HTTP_Status == 401){
					$res['msgType'] = 'error';
					$res['msg'] = __('MailChimp API Key Invalid.');
				}elseif($HTTP_Status == 404){
					$res['msgType'] = 'error';
					$res['msg'] = __('The requested resource could not be found.');
				}else{
					$res['msgType'] = 'error';
					$res['msg'] = __('Some problem occurred, please try again.');
				}
			}
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Please enable mailchimp.');
		}
		
		return response()->json($res);
    }
	
	//Get data for Subscriber by id
    public function getSubscriberById(Request $request){

		$id = $request->id;
		
		$data = Subscriber::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Subscriber
	public function deleteSubscriber(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$gtext = gtext();
			if($gtext['is_mailchimp'] == 1){
				
				$data = Subscriber::where('id', $id)->first();
				$email_address = $data->email_address;
				$HTTP_Status = self::DeleteMailChimpSubscriber($email_address);
				
				if($HTTP_Status == 204){
					$response = Subscriber::where('id', $id)->delete();
					if($response){
						$res['msgType'] = 'success';
						$res['msg'] = __('Data Removed Successfully');
					}else{
						$res['msgType'] = 'error';
						$res['msg'] = __('Some problem occurred, please try again.');
					}
				}elseif($HTTP_Status == 401){
					$res['msgType'] = 'error';
					$res['msg'] = __('MailChimp API Key Invalid.');
				}elseif($HTTP_Status == 404){
					$res['msgType'] = 'error';
					$res['msg'] = __('The requested resource could not be found.');
				}else{
					$res['msgType'] = 'error';
					$res['msg'] = __('Some problem occurred, please try again.');
				}
			}else{
				$response = Subscriber::where('id', $id)->delete();
				if($response){
					$res['msgType'] = 'success';
					$res['msg'] = __('Data Removed Successfully');
				}else{
					$res['msgType'] = 'error';
					$res['msg'] = __('Some problem occurred, please try again.');
				}
			}
		}
		
		return response()->json($res);
	}
	
    //Subscribe Popup page load
    public function getSubscribeSettings(){
		
		$datalist = Tp_option::where('option_name', 'subscribe_popup')->get();
		$id = '';
		$option_value = '';
		foreach ($datalist as $row){
			$id = $row->id;
			$option_value = json_decode($row->option_value);
		}

		$data = array();
		if($id != ''){
			$data['subscribe_popup_desc'] = $option_value->subscribe_popup_desc;
			$data['is_subscribe_popup'] = $option_value->is_subscribe_popup;
			$data['is_subscribe_footer'] = $option_value->is_subscribe_footer;
		}else{
			$data['subscribe_popup_desc'] = '';
			$data['is_subscribe_popup'] = '';
			$data['is_subscribe_footer'] = '';
		}
		
		$datalist = $data;
		
		return view('backend.subscribe-settings', compact('datalist'));
    }

	//Save data for Subscribe Popup
    public function SubscribePopupUpdate(Request $request){
		$res = array();
		
		$subscribe_popup_desc = $request->input('subscribe_popup_desc');
		$is_subscribepopup = $request->input('is_subscribe_popup');
		$is_subscribefooter = $request->input('is_subscribe_footer');
		
		if ($is_subscribepopup == 'true' || $is_subscribepopup == 'on') {
			$is_subscribe_popup = 1;
		}else {
			$is_subscribe_popup = 0;
		}
		if ($is_subscribefooter == 'true' || $is_subscribefooter == 'on') {
			$is_subscribe_footer = 1;
		}else {
			$is_subscribe_footer = 0;
		}
		
		$validator_array = array(
			'subscribe_popup_desc' => $request->input('subscribe_popup_desc')
		);

		$validator = Validator::make($validator_array, [
			'subscribe_popup_desc' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('subscribe_popup_desc')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('subscribe_popup_desc');
			return response()->json($res);
		}
		
		$option_value = array(
			'subscribe_popup_desc' => $subscribe_popup_desc,
			'is_subscribe_popup' => $is_subscribe_popup,
			'is_subscribe_footer' => $is_subscribe_footer
		);
		
		$data = array(
			'option_name' => 'subscribe_popup',
			'option_value' => json_encode($option_value)
		);

		$gData = Tp_option::where('option_name', 'subscribe_popup')->get();
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
	
    //MailChimp Settings page load
    public function getMailChimpSettings(){
		
		$datalist = Tp_option::where('option_name', 'mailchimp')->get();
		$id = '';
		$option_value = '';
		foreach ($datalist as $row){
			$id = $row->id;
			$option_value = json_decode($row->option_value);
		}

		$data = array();
		if($id != ''){
			$data['mailchimp_api_key'] = $option_value->mailchimp_api_key;
			$data['audience_id'] = $option_value->audience_id;
			$data['is_mailchimp'] = $option_value->is_mailchimp;
		}else{
			$data['mailchimp_api_key'] = '';
			$data['audience_id'] = '';
			$data['is_mailchimp'] = '';
		}
		
		$datalist = $data;
		
		return view('backend.mailchimp-settings', compact('datalist'));
    }
	
	//Save data for MailChimp Settings
    public function MailChimpSettingsUpdate(Request $request){
		$res = array();
		
		$mailchimp_api_key = $request->input('mailchimp_api_key');
		$audience_id = $request->input('audience_id');
		$ismailchimp = $request->input('is_mailchimp');
		
		if ($ismailchimp == 'true' || $ismailchimp == 'on') {
			$is_mailchimp = 1;
		}else {
			$is_mailchimp = 0;
		}
		
		$validator_array = array(
			'mailchimp_api_key' => $request->input('mailchimp_api_key'),
			'audience_id' => $request->input('audience_id')
		);

		$validator = Validator::make($validator_array, [
			'mailchimp_api_key' => 'required',
			'audience_id' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('mailchimp_api_key')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('mailchimp_api_key');
			return response()->json($res);
		}
		if($errors->has('audience_id')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('audience_id');
			return response()->json($res);
		}

		$option_value = array(
			'mailchimp_api_key' => $mailchimp_api_key,
			'audience_id' => $audience_id,
			'is_mailchimp' => $is_mailchimp
		);
		
		$data = array(
			'option_name' => 'mailchimp',
			'option_value' => json_encode($option_value)
		);

		$gData = Tp_option::where('option_name', 'mailchimp')->get();
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
	
	//MailChimp Subscriber
    public function MailChimpSubscriber($email, $status){
		$gtext = gtext();

		$apiKey = $gtext['mailchimp_api_key'];
		$listId = $gtext['audience_id'];
		
        //Create mailchimp API url
        $memberId = md5(strtolower($email));
        $dataCenter = substr($apiKey, strpos($apiKey, '-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId; 
		
		$mailToArr = explode("@", $email);
		$name = ucfirst($mailToArr[0]);
		
        //Member info
        $data = array(
            'email_address' => $email,
            'status' => $status,
            'merge_fields'  => [
                'FNAME'     => $name,
                'LNAME'     => $name
            ]
        );

        $jsonString = json_encode($data);

        //Send a HTTP POST request with curl
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

	//Delete MailChimp Subscriber
    public function DeleteMailChimpSubscriber($email){
		$gtext = gtext();

		$apiKey = $gtext['mailchimp_api_key'];
		$listId = $gtext['audience_id'];

        //Create mailchimp API url
        $memberId = md5(strtolower($email));
        $dataCenter = substr($apiKey, strpos($apiKey, '-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId; 

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return $httpCode;
    }

	//Save data for subscribe Popup Off
    public function subscribePopupOff(Request $request){
		$PopupOff = $request->input('PopupOff');
		
		session(['subscribePopupOff' => $PopupOff]);
	}
}
