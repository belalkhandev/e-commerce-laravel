<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdrawal;
use App\Models\Bank_information;
use App\Models\Withdrawal_image;

class WithdrawalController extends Controller
{
	//Withdrawals page load
    public function getWithdrawalsPageLoad() {
		
		$user_id = Auth::user()->id;

		$datalist = DB::table('withdrawals')
			->join('withdrawal_status', 'withdrawals.status_id', '=', 'withdrawal_status.id')
			->select('withdrawals.*', 'withdrawal_status.status')
			->where('withdrawals.seller_id', $user_id)
			->orderBy('withdrawals.id','desc')
			->paginate(20);

			$bi_data = Bank_information::where('seller_id', $user_id)->get();
			
			$biData = array(
				'bank_name' => '', 
				'bank_code' => '', 
				'account_number' => '', 
				'account_holder' => '',
				'paypal_id' => '',
				'description' => ''
			);
			foreach($bi_data as $row){
				$biData['bank_name'] = $row->bank_name;
				$biData['bank_code'] = $row->bank_code;
				$biData['account_number'] = $row->account_number;
				$biData['account_holder'] = $row->account_holder;
				$biData['paypal_id'] = $row->paypal_id;
				$biData['description'] = $row->description;
			}

        return view('seller.withdrawals', compact('datalist', 'biData'));		
	}
	
	//Get data for withdrawals Pagination
	public function getWithdrawalsTableData(Request $request){
		$user_id = Auth::user()->id;
		
		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('withdrawals')
					->join('withdrawal_status', 'withdrawals.status_id', '=', 'withdrawal_status.id')
					->select('withdrawals.*', 'withdrawal_status.status')
					->where(function ($query) use ($search){
						$query->where('amount', 'like', '%'.$search.'%')
							->orWhere('fee_amount', 'like', '%'.$search.'%')
							->orWhere('payment_method', 'like', '%'.$search.'%')
							->orWhere('transaction_id', 'like', '%'.$search.'%');
					})
					->where('withdrawals.seller_id', $user_id)
					->orderBy('withdrawals.id','desc')
					->paginate(20);
			}else{
				
				$datalist = DB::table('withdrawals')
					->join('withdrawal_status', 'withdrawals.status_id', '=', 'withdrawal_status.id')
					->select('withdrawals.*', 'withdrawal_status.status')
					->where('withdrawals.seller_id', $user_id)
					->orderBy('withdrawals.id','desc')
					->paginate(20);
			}

			return view('seller.partials.withdrawals_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Withdrawals
    public function saveWithdrawalsData(Request $request){
		$res = array();
		$gsellersettings = gSellerSettings();
		
		$id = $request->input('RecordId');
		$amount = $request->input('amount');
		$fee_amount = $request->input('fee_amount');
		$description = $request->input('description');
		$ubalance = $request->input('ubalance');

		$validator_array = array(
			'amount' => $request->input('amount')
		);
		
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'amount' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('amount')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('amount');
			return response()->json($res);
		}
		
		$seller_id = Auth::user()->id;

		if($id ==''){
			
			$fee_withdrawal = $gsellersettings['fee_withdrawal'] == '' ? 0 : $gsellersettings['fee_withdrawal'];
			$balance = $ubalance - $fee_withdrawal;
			
			if($amount > $balance){
				$res['msgType'] = 'error';
				$res['msg'] = __('The balance is not enough for withdrawal.');
				return response()->json($res);
			}
			
			$data = array(
				'seller_id' => $seller_id,
				'amount' => $amount,
				'fee_amount' => $fee_amount,
				'description' => $description,
				'status_id' => 1
			);
			
			$response = Withdrawal::create($data)->id;
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['id'] = '';
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$data = array(
				'seller_id' => $seller_id,
				'description' => $description
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
		}
		
		return response()->json($res);
    }
	
	//Get data for Withdrawal by id
    public function getWithdrawalById(Request $request){

		$id = $request->id;
		
		$data = Withdrawal::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Get data for Current Balance by seller id
    public function getCurrentBalanceBySellerId(Request $request){
		$gtext = gtext();
		
		$seller_id = Auth::user()->id;

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
		WHERE seller_id = '".$seller_id."';";
		$aRow1 = DB::select(DB::raw($sql1));
		$WithdrawalBalance = $aRow1[0]->WithdrawalBalance;
		$OrderWithdrawalBalance = ($OrderBalance - $WithdrawalBalance);
		
		$data = array('CurrentBalance' => 0, 'ubalance' => 0);
		if($gtext['currency_position'] == 'left'){
			$data['CurrentBalance'] = $gtext['currency_icon'].number_format($OrderWithdrawalBalance, 2);
			$data['ubalance'] = $OrderWithdrawalBalance;
		}else{
			$data['CurrentBalance'] = number_format($OrderWithdrawalBalance, 2).$gtext['currency_icon'];
			$data['ubalance'] = $OrderWithdrawalBalance;
		}
		return response()->json($data);
	}
	
	//Get data for Screenshot by id
    public function getScreenshotById(Request $request){
	
		$withdrawal_id = $request->withdrawal_id;

		$data = Withdrawal_image::where('withdrawal_id', $withdrawal_id)->orderBy('id','desc')->get();

		return response()->json($data);
	}	
}
