<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Media_option;

class UsersController extends Controller
{
    //Users page load
    public function getUsersPageLoad(){
		$statuslist = DB::table('user_status')->orderBy('id', 'asc')->get();
		$roleslist = DB::table('user_roles')->whereNotIn('id', [2, 3])->orderBy('id', 'asc')->get();
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
		$datalist = DB::table('users')
			->join('user_roles', 'users.role_id', '=', 'user_roles.id')
			->join('user_status', 'users.status_id', '=', 'user_status.id')
			->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.address', 'users.photo', 'user_roles.role', 'user_status.status', 'users.status_id')
			->where('users.role_id', 1)
			->orderBy('users.name','asc')
			->paginate(20);
			
        return view('backend.users', compact('statuslist', 'roleslist', 'media_datalist', 'datalist'));
    }
	
	//Get data for Users Pagination
	public function getUsersTableData(Request $request){

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
					->whereNotIn('users.role_id', 1)
					->orderBy('users.name','asc')
					->paginate(20);
			}else{
				
			$datalist = DB::table('users')
				->join('user_roles', 'users.role_id', '=', 'user_roles.id')
				->join('user_status', 'users.status_id', '=', 'user_status.id')
				->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.address', 'users.photo', 'user_roles.role', 'user_status.status', 'users.status_id')
				->whereNotIn('users.role_id', 1)
				->orderBy('users.name','asc')
				->paginate(20);
			}

			return view('backend.partials.users_table', compact('datalist'))->render();
		}
	}

	//Save data for Users
    public function saveUsersData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');
		$phone = $request->input('phone');
		$address = $request->input('address');
		$status_id = $request->input('status_id');
		$role_id = $request->input('role_id');
		$photo = $request->input('photo');
		
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
			'role_id' => $role_id,
			'photo' => $photo,
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
	
	//Get data for User by id
    public function getUserById(Request $request){

		$id = $request->id;
		
		$data = DB::table('users')->where('id', $id)->first();
					
		$data->bactive = base64_decode($data->bactive);

		return response()->json($data);
	}
	
	//Delete data for User
	public function deleteUser(Request $request){
		
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
	
	//Bulk Action for Users
	public function bulkActionUsers(Request $request){
		
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
	
    //Profile page load
    public function getProfilePageLoad(){
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);

        return view('backend.profile', compact('media_datalist'));
    }
	
	//Save data for User Profile
    public function profileUpdate(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');
		$phone = $request->input('phone');
		$address = $request->input('address');
		$photo = $request->input('photo');
		
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
			'photo' => $photo,
			'bactive' => base64_encode($password)
		);

		$response = User::where('id', $id)->update($data);
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
