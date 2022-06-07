<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Media_option;
use App\Models\Withdrawal;
use App\Models\Bank_information;
use App\Models\User;
use App\Models\Withdrawal_image;

class WithdrawalController extends Controller
{
	//Withdrawals page load
    public function getWithdrawalsPageLoad() {
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
		$statuslist = DB::table('withdrawal_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('withdrawals')
			->join('withdrawal_status', 'withdrawals.status_id', '=', 'withdrawal_status.id')
			->join('users', 'withdrawals.seller_id', '=', 'users.id')
			->select('withdrawals.*', 'withdrawal_status.status', 'users.name', 'users.shop_name')
			->orderBy('withdrawals.id','desc')
			->paginate(20);

        return view('backend.withdrawals', compact('media_datalist', 'statuslist', 'datalist'));		
	}
	
	//Get data for withdrawals Pagination
	public function getWithdrawalsTableData(Request $request){

		$search = $request->search;
		$status_id = $request->status_id;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('withdrawals')
					->join('withdrawal_status', 'withdrawals.status_id', '=', 'withdrawal_status.id')
					->join('users', 'withdrawals.seller_id', '=', 'users.id')
					->select('withdrawals.*', 'withdrawal_status.status', 'users.name', 'users.shop_name')
					->where(function ($query) use ($search){
						$query->where('amount', 'like', '%'.$search.'%')
							->orWhere('fee_amount', 'like', '%'.$search.'%')
							->orWhere('payment_method', 'like', '%'.$search.'%')
							->orWhere('transaction_id', 'like', '%'.$search.'%')
							->orWhere('name', 'like', '%'.$search.'%')
							->orWhere('shop_name', 'like', '%'.$search.'%');
					})
					->where(function ($query) use ($status_id){
						$query->whereRaw("withdrawals.status_id = '".$status_id."' OR '".$status_id."' = '0'");
					})
					->orderBy('withdrawals.id','desc')
					->paginate(20);
			}else{
				
				$datalist = DB::table('withdrawals')
					->join('withdrawal_status', 'withdrawals.status_id', '=', 'withdrawal_status.id')
					->join('users', 'withdrawals.seller_id', '=', 'users.id')
					->select('withdrawals.*', 'withdrawal_status.status', 'users.name', 'users.shop_name')
					->where(function ($query) use ($status_id){
						$query->whereRaw("withdrawals.status_id = '".$status_id."' OR '".$status_id."' = '0'");
					})
					->orderBy('withdrawals.id','desc')
					->paginate(20);
			}

			return view('backend.partials.withdrawals_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Withdrawals
    public function saveWithdrawalsData(Request $request){
		$res = array();

		$id = $request->input('RecordId');
		$payment_method = $request->input('payment_method');
		$transaction_id = $request->input('transaction_id');
		$description = $request->input('description');
		$status_id = $request->input('status_id');
		
		$validator_array = array(
			'status' => $request->input('status_id')
		);
		
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'status' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('status')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('status');
			return response()->json($res);
		}
		
		$data = array(
			'transaction_id' => $transaction_id,
			'payment_method' => $payment_method,
			'description' => $description,
			'status_id' => $status_id
		);
		
		$response = Withdrawal::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['id'] = '';
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }
	
	//Delete data for Withdrawal
	public function deleteWithdrawal(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){

			Withdrawal_image::where('withdrawal_id', $id)->delete();
			$response = Withdrawal::where('id', $id)->delete();
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
	
	//Get data for Withdrawal by id
    public function getWithdrawalById(Request $request){
		$gtext = gtext();
		
		$seller_id = $request->seller_id;
		$id = $request->id;
		
		$data = array('dataList' => '', 'CurrentBalance' => 0, 'bank_info' => '', 'seller_info' => '');
		
		$data['dataList'] = Withdrawal::where('id', $id)->first();
		
		$sql = "SELECT (IFNULL(SUM(b.total_price), 0) + IFNULL(SUM(b.tax), 0)) AS OrderBalance
		FROM order_masters a
		INNER JOIN order_items b ON a.id = b.order_master_id
		WHERE a.payment_status_id = 1
		AND a.order_status_id = 4
		AND a.seller_id = '".$seller_id."';";
		$aRow = DB::select(DB::raw($sql));
		$OrderBalance = $aRow[0]->OrderBalance;
		
		$sql1 = "SELECT (IFNULL(SUM(amount), 0) + IFNULL(SUM(fee_amount), 0)) AS WithdrawalBalance
		FROM withdrawals 
		WHERE seller_id = '".$seller_id."'
		AND status_id = 3;";
		$aRow1 = DB::select(DB::raw($sql1));
		$WithdrawalBalance = $aRow1[0]->WithdrawalBalance;
		$OrderWithdrawalBalance = ($OrderBalance - $WithdrawalBalance);

		if($gtext['currency_position'] == 'left'){
			$data['CurrentBalance'] = $gtext['currency_icon'].number_format($OrderWithdrawalBalance, 2);
		}else{
			$data['CurrentBalance'] = number_format($OrderWithdrawalBalance, 2).$gtext['currency_icon'];
		}
		
		$bi_data = Bank_information::where('seller_id', $seller_id)->get();

		foreach($bi_data as $row){
			$data['bank_info'] = "<p><strong>". __('Bank Name') ."</strong>: ".$row->bank_name."</p>
					<p><strong>". __('Account Holder Name') ."</strong>: ".$row->account_holder."</p>
					<p><strong>". __('Account Number') ."</strong>: ".$row->account_number."</p>
					<p><strong>". __('Description') ."</strong>: ".$row->description."</p>";
		}
		
		$sellerInfo = User::where('id', $seller_id)->get();
		foreach($sellerInfo as $row){
			
			$created_at = date('d F, Y', strtotime($row->created_at));
			
			if($row->status_id == 1){
				$statusClass = "active";
				$status = __('Active');
			}else{
				$statusClass = "inactive";
				$status = __('Inactive');
			}
			
			$data['seller_info'] = '<p><strong>'. __('Joined At') .'</strong>: '.$created_at.'</p>
				<p><strong>'. __('Status') .'</strong>: <span class="'.$statusClass.'">'.$status.'</span></p>
				<p><strong>'. __('Name') .'</strong>: '.$row->name.'</p>
				<p><strong>'. __('Shop Name') .'</strong>: '.$row->shop_name.'</p>
				<p><strong>'. __('Email Address') .'</strong>: '.$row->email.'</p>
				<p><strong>'. __('Shop Phone') .'</strong>: '.$row->phone.'</p>
				<p><strong>'. __('Address') .'</strong>: '.$row->address.'</p>';
		}
		
		return response()->json($data);
	}

	//Save data for Screenshot
    public function saveScreenshot(Request $request){
		$res = array();

		$withdrawal_id = $request->input('withdrawal_id');
		$screenshot = $request->input('screenshot');
		
		$validator_array = array(
			'screenshot' => $request->input('screenshot')
		);
		
		$validator = Validator::make($validator_array, [
			'screenshot' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('screenshot')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('screenshot');
			return response()->json($res);
		}
		
		$data = array(
			'withdrawal_id' => $withdrawal_id,
			'images' => $screenshot
		);
		
		$response = Withdrawal_image::create($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('New Data Added Successfully');
		}else{
			$res['id'] = '';
			$res['msgType'] = 'error';
			$res['msg'] = __('Data insert failed');
		}

		return response()->json($res);
    }
	
	//Get data for Screenshot by id
    public function getScreenshotById(Request $request){
	
		$withdrawal_id = $request->withdrawal_id;

		$data = Withdrawal_image::where('withdrawal_id', $withdrawal_id)->orderBy('id','desc')->get();

		return response()->json($data);
	}
	
	//Delete data for Screenshot
	public function deleteScreenshotById(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){

			$response = Withdrawal_image::where('id', $id)->delete();
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
