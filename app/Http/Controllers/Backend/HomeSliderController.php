<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Media_option;
use App\Models\Slider;

class HomeSliderController extends Controller
{
    //Slider page load
    public function getSliderPageLoad() {
		
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('sliders')
			->join('tp_status', 'sliders.is_publish', '=', 'tp_status.id')
			->select('sliders.id', 'sliders.slider_type', 'sliders.url', 'sliders.image', 'sliders.desc', 'sliders.is_publish', 'tp_status.status')
			->orderBy('sliders.id','desc')
			->paginate(10);

        return view('backend.slider', compact('media_datalist', 'statuslist', 'datalist'));
    }

	//Get data for Slider Pagination
	public function getSliderTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = DB::table('sliders')
					->join('tp_status', 'sliders.is_publish', '=', 'tp_status.id')
					->select('sliders.id', 'sliders.slider_type', 'sliders.url', 'sliders.image', 'sliders.desc', 'sliders.is_publish', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('slider_type', 'like', '%'.$search.'%')
							->orWhere('desc', 'like', '%'.$search.'%')
							->orWhere('url', 'like', '%'.$search.'%');
					})
					->orderBy('sliders.id','desc')
					->paginate(10);
			}else{
				
				$datalist = DB::table('sliders')
					->join('tp_status', 'sliders.is_publish', '=', 'tp_status.id')
					->select('sliders.id', 'sliders.slider_type', 'sliders.url', 'sliders.image', 'sliders.desc', 'sliders.is_publish', 'tp_status.status')
					->orderBy('sliders.id','desc')
					->paginate(10);
			}

			return view('backend.partials.slider_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Slider
    public function saveSliderData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$desc = $request->input('desc');
		$image = $request->input('image');
		$url = $request->input('image_url');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'image' => $request->input('image'),
			'is_publish' => $request->input('is_publish')
		);
		
		$validator = Validator::make($validator_array, [
			'image' => 'required',
			'is_publish' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('image')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('image');
			return response()->json($res);
		}
		
		if($errors->has('is_publish')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('is_publish');
			return response()->json($res);
		}

		$data = array(
			'slider_type' => 'home_slider',
			'image' => $image,
			'url' => $url,
			'desc' => $desc,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = Slider::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Slider::where('id', $id)->update($data);
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
	
	//Get data for Slider by id
    public function getSliderById(Request $request){

		$id = $request->id;
		
		$data = Slider::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Slider
	public function deleteSlider(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Slider::where('id', $id)->delete();
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
	
	//Bulk Action for Slider
	public function bulkActionSlider(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Slider::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Slider::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Slider::whereIn('id', $idsArray)->delete();
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
