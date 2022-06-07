<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Page;

class PageController extends Controller
{
    //Get All Page Data
    public function getAllPageData(){
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		$languageslist = DB::table('languages')->where('status', 1)->orderBy('language_name', 'asc')->get();
		
		$AllCount = Page::count();
		$PublishedCount = Page::where('is_publish', '=', 1)->count();
		$DraftCount = Page::where('is_publish', '=', 2)->count();

		$datalist = DB::table('pages')
			->join('tp_status', 'pages.is_publish', '=', 'tp_status.id')
			->join('languages', 'pages.lan', '=', 'languages.language_code')
			->select('pages.*', 'tp_status.status', 'languages.language_name')
			->orderBy('pages.id','desc')
			->paginate(20);
		
        return view('backend.page', compact('AllCount', 'PublishedCount', 'DraftCount', 'datalist', 'statuslist', 'languageslist'));
    }
	
	//Get data for Page Pagination
	public function getPagePaginationData(Request $request){

		$search = $request->search;
		$post_status = $request->post_status;
		$language_code = $request->language_code;
		
		if($request->ajax()){
			if($search != ''){
				$datalist = DB::table('pages')
					->join('tp_status', 'pages.is_publish', '=', 'tp_status.id')
					->join('languages', 'pages.lan', '=', 'languages.language_code')
					->select('pages.*', 'tp_status.status', 'languages.language_name')
					->where(function ($query) use ($search){
						$query->where('title', 'like', '%'.$search.'%')
							->orWhere('slug', 'like', '%'.$search.'%');
					})
					->where(function ($query) use ($language_code){
						$query->whereRaw("pages.lan = '".$language_code."' OR '".$language_code."' = '0'");
					})
					->orderBy('pages.id','desc')
					->paginate(20);
			}else{
				$datalist = DB::table('pages')
					->join('tp_status', 'pages.is_publish', '=', 'tp_status.id')
					->join('languages', 'pages.lan', '=', 'languages.language_code')
					->select('pages.*', 'tp_status.status', 'languages.language_name')
					->where(function ($query) use ($post_status){
						$query->whereRaw("pages.is_publish = '".$post_status."' OR '".$post_status."' = '0'");
					})
					->where(function ($query) use ($language_code){
						$query->whereRaw("pages.lan = '".$language_code."' OR '".$language_code."' = '0'");
					})
					->orderBy('pages.id','desc')
					->paginate(20);
			}

			return view('backend.partials.page_table', compact('datalist'))->render();
		}
	}
	 
	//Save data for Page
    public function savePageData(Request $request){
		$res = array();

		$id = $request->input('RecordId');
		$title = esc($request->input('title'));
		$slug = esc(str_slug($request->input('slug')));
		$content = $request->input('content');
		$is_publish = $request->input('is_publish');
		$lan = $request->input('lan');
		
 		$validator_array = array(
			'title' => $request->input('title'),
			'content' => $request->input('content'),
			'slug' => $slug,
			'language' => $request->input('lan')
		);
		
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'title' => 'required',
			'content' => 'required',
			'slug' => 'required|max:191|unique:pages,slug' . $rId,
			'language' => 'required'
		]);

		$errors = $validator->errors();	

		if($errors->has('title')){
			$res['id'] = '';
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('title');
			return response()->json($res);
		}
		
		if($errors->has('slug')){
			$res['id'] = '';
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('slug');
			return response()->json($res);
		}
		
		if($errors->has('content')){
			$res['id'] = '';
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('content');
			return response()->json($res);
		}
		
		if($errors->has('language')){
			$res['id'] = '';
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language');
			return response()->json($res);
		}

		$data = array(
			'title' => $title,
			'slug' => $slug,
			'content' => $content,
			'is_publish' => $is_publish,
			'lan' => $lan
		);
		
		if($id ==''){
			$response = Page::create($data)->id;
			if($response){
				$res['id'] = $response;
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['id'] = '';
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Page::where('id', $id)->update($data);
			if($response){
				$res['id'] = $id;
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['id'] = '';
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//has Page Title Slug
    public function hasPageTitleSlug(Request $request){
		$res = array();
		
		$slug = str_slug($request->slug);
        $count = Page::where('slug', 'like', '%'.$slug.'%') ->count();
		if($count == 0){
			$res['slug'] = $slug;
		}else{
			$incr = $count+1;
			$res['slug'] = $slug.'-'.$incr;
		}
		
		return response()->json($res);
	}
	
	//Get data for Page by id
    public function getPageById(Request $request){

		$id = $request->id;
		
		$data = Page::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Page
	public function deletePage(Request $request){
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Page::where('id', $id)->delete();
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
	
	//Bulk Action for Page
	public function bulkActionPage(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		if($BulkAction == 'publish'){
			$response = Page::whereIn('id', $idsArray)->update(['is_publish' => 1]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'draft'){
			
			$response = Page::whereIn('id', $idsArray)->update(['is_publish' => 2]);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
			
		}elseif($BulkAction == 'delete'){
			$response = Page::whereIn('id', $idsArray)->delete();
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
