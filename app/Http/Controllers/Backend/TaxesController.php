<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tax;

class TaxesController extends Controller
{
    //tax page load
    public function getTaxPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tax::offset(0)->limit(1)->get();

		$datalist = array('id' => '', 'title' => '', 'percentage' => '', 'is_publish' => '');
		foreach ($results as $row){
			$datalist['id'] = $row->id;
			$datalist['title'] = $row->title;
			$datalist['percentage'] = $row->percentage;
			$datalist['is_publish'] = $row->is_publish;
		}
		
        return view('backend.tax', compact('statuslist', 'datalist'));
    }
	
	//Save data for Tax
    public function saveTaxData(Request $request){
		$res = array();

		$title = $request->input('title');
		$percentage = $request->input('percentage');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'title' => $request->input('title'),
			'percentage' => $request->input('percentage'),
			'is_publish' => $request->input('is_publish')
		);
		
		$validator = Validator::make($validator_array, [
			'title' => 'required|max:191',
			'percentage' => 'required|max:100',
			'is_publish' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('title');
			return response()->json($res);
		}
		
		if($errors->has('percentage')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('percentage');
			return response()->json($res);
		}

		if($errors->has('is_publish')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('is_publish');
			return response()->json($res);
		}

		$data = array(
			'title' => $title,
			'percentage' => $percentage,
			'is_publish' => $is_publish
		);
		
		$results = Tax::offset(0)->limit(1)->get();
		$id = '';
		foreach ($results as $row){
			$id = $row->id;
		}
		if($id ==''){
			$response = Tax::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tax::where('id', $id)->update($data);
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
}
