<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Attribute;

class AttributesController extends Controller
{
    //Attributes page load
    public function getAttributesPageLoad() {

		$datalist = Attribute::orderBy('att_type', 'ASC')->paginate(10);

        return view('backend.attributes', compact('datalist'));
    }
	
	//Get data for Attributes Pagination
	public function getAttributesTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = Attribute::where(function ($query) use ($search){
						$query->where('att_type', 'like', '%'.$search.'%')
							->orWhere('name', 'like', '%'.$search.'%')
							->orWhere('color', 'like', '%'.$search.'%');
					})
					->orderBy('att_type', 'ASC')->paginate(10);
			}else{
				$datalist = Attribute::orderBy('att_type', 'ASC')->paginate(10);
			}

			return view('backend.partials.attributes_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Attributes
    public function saveAttributesData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$att_type = $request->input('att_type');
		$name = $request->input('name');
		$color = $request->input('color');
		
		$validator_array = array(
			'att_type' => $request->input('att_type'),
			'name' => $request->input('name')
		);
		
		$validator = Validator::make($validator_array, [
			'att_type' => 'required|max:100',
			'name' => 'required|max:100'
		]);

		$errors = $validator->errors();
		
		if($errors->has('att_type')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('att_type');
			return response()->json($res);
		}
		
		if($errors->has('name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('name');
			return response()->json($res);
		}
		
		if($att_type == 'Size'){
			$data = array(
				'att_type' => $att_type,
				'name' => $name,
				'color' => NULL
			);
		}else{
			$data = array(
				'att_type' => $att_type,
				'name' => $name,
				'color' => $color
			);
		}
		
		if($id ==''){
			$response = Attribute::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Attribute::where('id', $id)->update($data);
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
	
	//Get data for Attribute by id
    public function getAttributesById(Request $request){

		$id = $request->id;
		
		$data = Attribute::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Attributes
	public function deleteAttributes(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Attribute::where('id', $id)->delete();
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
	
	//Bulk Action for Attributes
	public function bulkActionAttributes(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'delete'){
			$response = Attribute::whereIn('id', $idsArray)->delete();
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
