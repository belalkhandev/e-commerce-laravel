<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Social_media;

class SocialMediasController extends Controller
{
    //Social Media page load
    public function getSocialMediaPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$datalist = DB::table('social_medias')
			->join('tp_status', 'social_medias.is_publish', '=', 'tp_status.id')
			->select('social_medias.id', 'social_medias.title', 'social_medias.url', 'social_medias.social_icon', 'social_medias.target', 'social_medias.is_publish', 'tp_status.status')
			->orderBy('social_medias.id','desc')
			->paginate(10);

        return view('backend.social-media', compact('statuslist', 'datalist'));
    }

	//Get data for Social Media Pagination
	public function getSocialMediaTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = DB::table('social_medias')
					->join('tp_status', 'social_medias.is_publish', '=', 'tp_status.id')
					->select('social_medias.id', 'social_medias.title', 'social_medias.url', 'social_medias.social_icon', 'social_medias.target', 'social_medias.is_publish', 'tp_status.status')
					->where(function ($query) use ($search){
						$query->where('title', 'like', '%'.$search.'%')
							->orWhere('url', 'like', '%'.$search.'%')
							->orWhere('social_icon', 'like', '%'.$search.'%');
					})
					->orderBy('social_medias.id','desc')
					->paginate(10);
			}else{
				
				$datalist = DB::table('social_medias')
					->join('tp_status', 'social_medias.is_publish', '=', 'tp_status.id')
					->select('social_medias.id', 'social_medias.title', 'social_medias.url', 'social_medias.social_icon', 'social_medias.target', 'social_medias.is_publish', 'tp_status.status')
					->orderBy('social_medias.id','desc')
					->paginate(10);
			}

			return view('backend.partials.social_media_table', compact('datalist'))->render();
		}
	}
	
	//Save data for Social Media
    public function saveSocialMediaData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$title = $request->input('title');
		$url = $request->input('url');
		$social_icon = $request->input('social_icon');
		$target = $request->input('target');
		$is_publish = $request->input('is_publish');
		
		$validator_array = array(
			'title' => $request->input('title'),
			'url' => $request->input('url'),
			'social_icon' => $request->input('social_icon'),
			'target' => $request->input('target'),
			'is_publish' => $request->input('is_publish')
		);
		
		$validator = Validator::make($validator_array, [
			'title' => 'required|max:191',
			'url' => 'required|max:191',
			'social_icon' => 'required|max:100',
			'target' => 'required|max:100',
			'is_publish' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('title');
			return response()->json($res);
		}
		
		if($errors->has('url')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('url');
			return response()->json($res);
		}
		
		if($errors->has('social_icon')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('social_icon');
			return response()->json($res);
		}

		if($errors->has('target')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('target');
			return response()->json($res);
		}
		if($errors->has('is_publish')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('is_publish');
			return response()->json($res);
		}

		$data = array(
			'title' => $title,
			'url' => $url,
			'social_icon' => $social_icon,
			'target' => $target,
			'is_publish' => $is_publish
		);

		if($id ==''){
			$response = Social_media::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Social_media::where('id', $id)->update($data);
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
	
	//Get data for Social Media by id
    public function getSocialMediaById(Request $request){

		$id = $request->id;
		
		$data = Social_media::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Social Media
	public function deleteSocialMedia(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Social_media::where('id', $id)->delete();
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
	
	//Bulk Action for Social Media
	public function bulkActionSocialMedia(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Social_media::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Social_media::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Social_media::whereIn('id', $idsArray)->delete();
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
