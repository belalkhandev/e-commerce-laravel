<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Menu_parent;
use App\Models\Mega_menu;
use App\Models\Menu_child;

class MenuController extends Controller
{
    //Menu page load
    public function getMenuPageLoad(){
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$languagelist  = Language::where('status', 1)->orderBy('language_name', 'asc')->get();
		
		$datalist = DB::table('menus')
			->join('tp_status', 'menus.status_id', '=', 'tp_status.id')
			->select('menus.id', 'menus.menu_name', 'menus.menu_position', 'menus.lan', 'menus.status_id', 'tp_status.status')
			->orderBy('menus.id','desc')
			->paginate(10);

        return view('backend.menu', compact('languagelist', 'statuslist', 'datalist'));
    }
	
	//Get data for Menu Pagination
	public function getMenuTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = DB::table('menus')
					->join('tp_status', 'menus.status_id', '=', 'tp_status.id')
					->select('menus.id', 'menus.menu_name', 'menus.menu_position', 'menus.lan', 'menus.status_id', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('menu_name', 'like', '%'.$search.'%')
							->orWhere('menu_position', 'like', '%'.$search.'%');
					})
					->orderBy('menus.id','desc')
					->paginate(10);
			}else{
				$datalist = DB::table('menus')
					->join('tp_status', 'menus.status_id', '=', 'tp_status.id')
					->select('menus.id', 'menus.menu_name', 'menus.menu_position', 'menus.lan', 'menus.status_id', 'tp_status.status')
					->orderBy('menus.id','desc')
					->paginate(10);
			}

			return view('backend.partials.menu_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Menu
    public function saveMenuData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$menu_name = $request->input('menu_name');
		$menu_position = $request->input('menu_position');
		$lan = $request->input('lan');
		$status_id = $request->input('status_id');
		
		$validator_array = array(
			'menu_name' => $request->input('menu_name'),
			'menu_position' => $request->input('menu_position'),
			'lan' => $request->input('lan'),
			'status_id' => $request->input('status_id')
		);
		
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'menu_name' => 'required|max:191|unique:menus,menu_name' . $rId,
			'menu_position' => 'required|max:191',
			'lan' => 'required|max:100',
			'status_id' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('menu_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('menu_name');
			return response()->json($res);
		}
		
		if($errors->has('menu_position')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('menu_position');
			return response()->json($res);
		}
		
		if($errors->has('lan')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('lan');
			return response()->json($res);
		}
		
		if($errors->has('status_id')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('status_id');
			return response()->json($res);
		}

		$data = array(
			'menu_name' => $menu_name,
			'menu_position' => $menu_position,
			'lan' => $lan,
			'status_id' => $status_id
		);

		if($id ==''){
			$response = Menu::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Menu::where('id', $id)->update($data);
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
	
	//Get data for Menu by id
    public function getMenuById(Request $request){

		$id = $request->id;
		
		$data = Menu::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Menu
	public function deleteMenu(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){

			Menu_child::where('menu_id', $id)->delete();
			Mega_menu::where('menu_id', $id)->delete();
			Menu_parent::where('menu_id', $id)->delete();
			
			$response = Menu::where('id', $id)->delete();
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
	
	//Bulk Action for Menu
	public function bulkActionMenu(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Menu::whereIn('id', $idsArray)->update(['status_id' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Menu::whereIn('id', $idsArray)->update(['status_id' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			
			Menu_child::whereIn('menu_id', $idsArray)->delete();
			Mega_menu::whereIn('menu_id', $idsArray)->delete();
			Menu_parent::whereIn('menu_id', $idsArray)->delete();
			
			$response = Menu::whereIn('id', $idsArray)->delete();
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
