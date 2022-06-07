<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Media_option;

class MediaController extends Controller
{
    //Media page load
    public function getMediaPageLoad(Request $request){

		$search = $request->search;

        if($search != ''){
            $media_datalist = Media_option::where(function ($query) use ($search){
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('alt_title', 'like', '%'.$search.'%');
            })
			->orderBy('id','desc')
			->paginate(28);
			
            $media_datalist->appends(['search' => $search]);
			
        }else{
            $media_datalist = Media_option::orderBy('id','desc')->paginate(28);
        }
		
		return view('backend.media', compact('media_datalist'));
    }
	
	//Get data for Media Pagination
	public function getMediaPaginationData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){
			
			if($search != ''){
				$media_datalist = Media_option::where(function ($query) use ($search){
					$query->where('title', 'like', '%'.$search.'%')
						->orWhere('alt_title', 'like', '%'.$search.'%');
				})
				->orderBy('id', 'desc')
				->paginate(28);
				
				$media_datalist->appends(['search' => $search]);
				
			}else{
				$media_datalist = Media_option::orderBy('id', 'desc')->paginate(28);
			}
			
			return view('backend.partials.media_pagination_data', compact('media_datalist'))->render();
		}
	}
	
	//Get data for media by id
    public function getMediaById(Request $request){

		$id = $request->id;
		
		$data = Media_option::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Save data for media
    public function mediaUpdate(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$title = $request->input('title');
		$alt_title = $request->input('alternative_text');

		$data = array(
			'title' => $title,
			'alt_title' => $alt_title
		);

		$response = Media_option::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }
	
	//Delete data for Media
	public function onMediaDelete(Request $request){
		
		$res = array();

		$id = $request->id;
		
		if($id != ''){
			
			$datalist = Media_option::where('id', $id)->first();
			$thumbnail = $datalist['thumbnail'];
			$large_image = $datalist['large_image'];

			if (file_exists(public_path('media/'.$thumbnail))) {
				unlink(public_path('media/'.$thumbnail));
			}
			
			if (file_exists(public_path('media/'.$large_image))) {
				unlink(public_path('media/'.$large_image));
			}
		
			$response = Media_option::where('id', $id)->delete();
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
	
	//Get data for Global Media
	public function getGlobalMediaData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){
			
			if($search != ''){
				$media_datalist = Media_option::where(function ($query) use ($search){
					$query->where('title', 'like', '%'.$search.'%')
						->orWhere('alt_title', 'like', '%'.$search.'%');
				})
				->orderBy('id', 'desc')
				->paginate(28);
				
				$media_datalist->appends(['search' => $search]);
				
			}else{
				$media_datalist = Media_option::orderBy('id', 'desc')->paginate(28);
			}

			return view('backend.partials.global_media_pagination_data', compact('media_datalist'))->render();
		}
	}
}
