<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
	
	//Get data for search
	public function getSearchData(Request $request){
		$lan = glan();
		$search = $request->search;
		
		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('users.status_id', '=', 1)
			->where('products.lan', '=', $lan)
			->where(function ($query) use ($search){
				$query->where('products.title', 'like', '%'.$search.'%')
					->orWhere('products.slug', 'like', '%'.$search.'%')
					->orWhere('brands.name', 'like', '%'.$search.'%')
					->orWhere('products.sale_price', 'like', '%'.$search.'%')
					->orWhere('products.sku', 'like', '%'.$search.'%')
					->orWhere('labels.title', 'like', '%'.$search.'%')
					->orWhere('users.shop_name', 'like', '%'.$search.'%');
			})
			->orderBy('products.id', 'desc')
			->paginate(20);

		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		return view('frontend.search', compact('datalist'));
	}
}
