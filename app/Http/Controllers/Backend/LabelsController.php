<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Label;

class LabelsController extends Controller
{
    //Labels page load
    public function getLabelsPageLoad() {

		$datalist = Label::orderBy('id','desc')->paginate(10);

        return view('backend.labels', compact('datalist'));
    }
	
	//Get data for Labels Pagination
	public function getLabelsTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = Label::where(function ($query) use ($search){
						$query->where('title', 'like', '%'.$search.'%')
							->orWhere('color', 'like', '%'.$search.'%');
					})
					->orderBy('id','desc')->paginate(10);
			}else{
				$datalist = Label::orderBy('id','desc')->paginate(10);
			}

			return view('backend.partials.labels_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Labels
    public function saveLabelsData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$title = $request->input('title');
		$color = $request->input('color');
		
		$validator_array = array(
			'title' => $request->input('title'),
			'color' => $request->input('color')
		);
		
		$validator = Validator::make($validator_array, [
			'title' => 'required|max:191',
			'color' => 'required|max:191'
		]);

		$errors = $validator->errors();

		if($errors->has('title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('title');
			return response()->json($res);
		}
		
		if($errors->has('color')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('color');
			return response()->json($res);
		}

		$data = array(
			'title' => $title,
			'color' => $color
		);

		if($id ==''){
			$response = Label::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Label::where('id', $id)->update($data);
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
	
	//Get data for Label by id
    public function getLabelsById(Request $request){

		$id = $request->id;
		
		$data = Label::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Labels
	public function deleteLabels(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Label::where('id', $id)->delete();
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
	
	//Bulk Action for Labels
	public function bulkActionLabels(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'delete'){
			$response = Label::whereIn('id', $idsArray)->delete();
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
