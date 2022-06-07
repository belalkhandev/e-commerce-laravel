<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;

class ReviewsController extends Controller
{
    //Review & Ratings page load
    public function getReviewRatingsPageLoad() {

		$datalist = DB::table('reviews')
			->join('users', 'reviews.user_id', '=', 'users.id')
			->join('products', 'reviews.item_id', '=', 'products.id')
			->select('reviews.*', 'users.name', 'products.title', 'products.slug', 'products.id as product_id')
			->orderBy('reviews.id','desc')
			->paginate(20);

        return view('backend.review', compact('datalist'));
    }
	
	//Get data for Review Ratings Pagination
	public function getReviewRatingsTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				
				$datalist = DB::table('reviews')
					->join('users', 'reviews.user_id', '=', 'users.id')
					->join('products', 'reviews.item_id', '=', 'products.id')
					->select('reviews.*', 'users.name', 'products.title', 'products.slug', 'products.id as product_id')
					->where(function ($query) use ($search){
						$query->where('users.name', 'like', '%'.$search.'%')
							->orWhere('products.title', 'like', '%'.$search.'%')
							->orWhere('reviews.rating', 'like', '%'.$search.'%')
							->orWhere('reviews.created_at', 'like', '%'.$search.'%');
					})
					->orderBy('reviews.id','desc')
					->paginate(20);
			}else{
				
				$datalist = DB::table('reviews')
					->join('users', 'reviews.user_id', '=', 'users.id')
					->join('products', 'reviews.item_id', '=', 'products.id')
					->select('reviews.*', 'users.name', 'products.title', 'products.slug', 'products.id as product_id')
					->orderBy('reviews.id','desc')
					->paginate(20);
			}

			return view('backend.partials.review_ratings_table', compact('datalist'))->render();
		}
	}
	
	//Delete data for Review Ratings
	public function deleteReviewRatings(Request $request){
		
		$res = array();

		$id = $request->id;

		if($id != ''){
			$response = Review::where('id', $id)->delete();
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
	
	//Bulk Action for Review Ratings
	public function bulkActionReviewRatings(Request $request){
		
		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);
		
		$BulkAction = $request->BulkAction;

		$response = Review::whereIn('id', $idsArray)->delete();
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Removed Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data remove failed');
		}
		
		return response()->json($res);
	}	
}
