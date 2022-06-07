<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoresController extends Controller
{
	//Get data for stores
	public function getStoresData($seller_id, $title){
		$lan = glan();

		$seller_data = DB::table('users')
			->select('id', 'email', 'shop_name', 'phone', 'address', 'photo', 'created_at')
			->where('status_id', '=', 1)
			->where('id', '=', $seller_id)
			->first();
			
		$SellerReview = array('TotalReview' => 0, 'TotalRating' => 0, 'ReviewPercentage' => 0);
		$aReview = getReviewsBySeller($seller_id);
		$SellerReview['TotalReview'] = $aReview[0]->TotalReview;
		$SellerReview['TotalRating'] = $aReview[0]->TotalRating;
		$SellerReview['ReviewPercentage'] = number_format($aReview[0]->ReviewPercentage);
		
		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('users.status_id', '=', 1)
			->where('products.lan', '=', $lan)
			->where('products.user_id', '=', $seller_id)
			->orderBy('products.id', 'desc')
			->paginate(20);

		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}

		return view('frontend.stores', compact('seller_data', 'SellerReview', 'datalist'));
	}
}
