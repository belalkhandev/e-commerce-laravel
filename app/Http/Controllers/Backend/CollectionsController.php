<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Collection;

class CollectionsController extends Controller
{
    //Collections page load
    public function getCollectionsPageLoad() {

		$datalist = Collection::orderBy('id','desc')->paginate(10);

        return view('backend.collections', compact('datalist'));
    }
	
	//Get data for Collections Pagination
	public function getCollectionsTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = Collection::where(function ($query) use ($search){
						$query->where('name', 'like', '%'.$search.'%');
					})
					->orderBy('id','desc')->paginate(10);
			}else{
				$datalist = Collection::orderBy('id','desc')->paginate(10);
			}

			return view('backend.partials.collections_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Collections
    public function saveCollectionsData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$name = $request->input('name');

		$validator_array = array(
			'name' => $request->input('name')
		);
		
		$validator = Validator::make($validator_array, [
			'name' => 'required|max:191'
		]);

		$errors = $validator->errors();

		if($errors->has('name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('name');
			return response()->json($res);
		}
		$data = array(
			'name' => $name
		);

		if($id ==''){
			$response = Collection::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Collection::where('id', $id)->update($data);
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
	
	//Get data for Collection by id
    public function getCollectionsById(Request $request){

		$id = $request->id;
		
		$data = Collection::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Collections
	public function deleteCollections(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Collection::where('id', $id)->delete();
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
	
	//Bulk Action for Collections
	public function bulkActionCollections(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'delete'){
			$response = Collection::whereIn('id', $idsArray)->delete();
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
