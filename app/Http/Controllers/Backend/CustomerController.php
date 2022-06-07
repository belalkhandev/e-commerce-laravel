<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomerController extends Controller
{
	
	//Customers page load
    public function getCustomersPageLoad(){
		$statuslist = DB::table('user_status')->orderBy('id', 'asc')->get();

		$datalist = DB::table('users')
			->join('user_roles', 'users.role_id', '=', 'user_roles.id')
			->join('user_status', 'users.status_id', '=', 'user_status.id')
			->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.address', 'users.photo', 'user_roles.role', 'user_status.status', 'users.status_id')
			->where('users.role_id', 2)
			->orderBy('users.name','asc')
			->paginate(20);
			
        return view('backend.customers', compact('statuslist', 'datalist'));
    }
	
	//Get data for Customers Pagination
	public function getCustomersTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
						
				$datalist = DB::table('users')
					->join('user_roles', 'users.role_id', '=', 'user_roles.id')
					->join('user_status', 'users.status_id', '=', 'user_status.id')
					->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.address', 'users.photo', 'user_roles.role', 'user_status.status', 'users.status_id')
					->where(function ($query) use ($search){
						$query->where('name', 'like', '%'.$search.'%')
							->orWhere('email', 'like', '%'.$search.'%')
							->orWhere('phone', 'like', '%'.$search.'%');
					})
					->where('users.role_id', 2)
					->orderBy('users.name','asc')
					->paginate(20);
			}else{
				
			$datalist = DB::table('users')
				->join('user_roles', 'users.role_id', '=', 'user_roles.id')
				->join('user_status', 'users.status_id', '=', 'user_status.id')
				->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.address', 'users.photo', 'user_roles.role', 'user_status.status', 'users.status_id')
				->where('users.role_id', 2)
				->orderBy('users.name','asc')
				->paginate(20);
			}

			return view('backend.partials.customers_table', compact('datalist'))->render();
		}
	}

	//Save data for Customers
    public function saveCustomersData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');
		$phone = $request->input('phone');
		$address = $request->input('address');
		$status_id = $request->input('status_id');

		$validator_array = array(
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => $request->input('password')
		);
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'name' => 'required|max:191',
			'email' => 'required|max:191|unique:users,email' . $rId,
			'password' => 'required|max:191'
		]);

		$errors = $validator->errors();

		if($errors->has('name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('name');
			return response()->json($res);
		}
		
		if($errors->has('email')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('email');
			return response()->json($res);
		}
		
		if($errors->has('password')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('password');
			return response()->json($res);
		}

		$data = array(
			'name' => $name,
			'email' => $email,
			'password' => Hash::make($password),
			'phone' => $phone,
			'address' => $address,
			'status_id' => $status_id,
			'role_id' => 2,
			'bactive' => base64_encode($password)
		);

		if($id ==''){
			$response = User::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = User::where('id', $id)->update($data);
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
	
	//Get data for Customer by id
    public function getCustomerById(Request $request){

		$id = $request->id;
		
		$data = DB::table('users')->where('id', $id)->first();
					
		$data->bactive = base64_decode($data->bactive);

		return response()->json($data);
	}
	
	//Delete data for Customer
	public function deleteCustomer(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
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
	
	//Bulk Action for Customers
	public function bulkActionCustomers(Request $request){
		
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
