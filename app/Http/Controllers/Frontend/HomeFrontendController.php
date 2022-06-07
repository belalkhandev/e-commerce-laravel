<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Slider;
use App\Models\Offer_ad;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Tp_option;

class HomeFrontendController extends Controller
{
	//Get Frontend Data
    public function homePageLoad(Request $request)
	{
		$lan = glan();
		
		//Slider
		$slider = Slider::where('is_publish', '=', 1)->orderBy('id', 'desc')->get();
		
		$posi_1 = Offer_ad::where('offer_ad_type', '=', 'position_1')->first();
		$desc_posi_1 = json_decode($posi_1['desc']);
		$position_1 = array();
		$position_1['url'] = $posi_1['url'];
		$position_1['image'] = $posi_1['image'];
		$position_1['is_publish'] = $posi_1['is_publish'];
		$position_1['text_1'] = $desc_posi_1->text_1;
		$position_1['text_2'] = $desc_posi_1->text_2;

		$posi_2 = Offer_ad::where('offer_ad_type', '=', 'position_2')->first();
		$desc_posi_2 = json_decode($posi_2['desc']);
		$position_2 = array();
		$position_2['url'] = $posi_2['url'];
		$position_2['image'] = $posi_2['image'];
		$position_2['is_publish'] = $posi_2['is_publish'];
		$position_2['text_1'] = $desc_posi_2->text_1;
		$position_2['text_2'] = $desc_posi_2->text_2;

		$posi_3 = Offer_ad::where('offer_ad_type', '=', 'position_3')->first();
		$desc_posi_3 = json_decode($posi_3['desc']);
		$position_3 = array();
		$position_3['url'] = $posi_3['url'];
		$position_3['image'] = $posi_3['image'];
		$position_3['is_publish'] = $posi_3['is_publish'];
		$position_3['text_1'] = $desc_posi_3->text_1;
		$position_3['text_2'] = $desc_posi_3->text_2;

		$posi_4 = Offer_ad::where('offer_ad_type', '=', 'position_4')->first();
		$desc_posi_4 = json_decode($posi_4['desc']);
		$position_4 = array();
		$position_4['url'] = $posi_4['url'];
		$position_4['image'] = $posi_4['image'];
		$position_4['is_publish'] = $posi_4['is_publish'];
		$position_4['text_1'] = $desc_posi_4->text_1;
		$position_4['text_2'] = $desc_posi_4->text_2;
		
		$posi_5 = Offer_ad::where('offer_ad_type', '=', 'position_5')->first();
		$desc_posi_5 = json_decode($posi_5['desc']);
		$position_5 = array();
		$position_5['url'] = $posi_5['url'];
		$position_5['image'] = $posi_5['image'];
		$position_5['is_publish'] = $posi_5['is_publish'];
		$position_5['text_1'] = $desc_posi_5->text_1;
		$position_5['text_2'] = $desc_posi_5->text_2;
		
		//Brand
		$brand = Brand::where('is_publish', '=', 1)->where('is_featured', '=', 1)->where('lan', '=', $lan)->orderBy('id', 'desc')->get();
		
		//4 = New Arrivals
		$na_sql = "SELECT a.id, a.brand_id, a.title, a.slug, a.f_thumbnail, a.sale_price, a.old_price, a.start_date, a.end_date, a.variation_color, a.variation_size, b.title labelname, b.color labelcolor, c.name brandname, d.shop_name, d.id seller_id, d.shop_url
			FROM products a
			LEFT JOIN labels b ON a.label_id = b.id
			LEFT JOIN brands c ON a.brand_id = c.id
			INNER JOIN users d ON a.user_id = d.id AND d.status_id = 1
			WHERE a.collection_id = 4 AND a.lan = '".$lan."' AND a.is_publish = 1 AND a.is_featured = 1 ORDER BY a.id DESC LIMIT 20;";
		$new_arrivals = DB::select(DB::raw($na_sql));
		
		//3 = Trending Products
		$tp_sql = "SELECT a.id, a.brand_id, a.title, a.slug, a.f_thumbnail, a.sale_price, a.old_price, a.start_date, a.end_date, a.variation_color, a.variation_size, b.title labelname, b.color labelcolor, c.name brandname, d.shop_name, d.id seller_id, d.shop_url
			FROM products a
			LEFT JOIN labels b ON a.label_id = b.id
			LEFT JOIN brands c ON a.brand_id = c.id
			INNER JOIN users d ON a.user_id = d.id AND d.status_id = 1
			WHERE a.collection_id = 3 AND a.lan = '".$lan."' AND a.is_publish = 1 AND a.is_featured = 1 ORDER BY a.id DESC LIMIT 20;";
		$trending_products = DB::select(DB::raw($tp_sql));
		
		for($i=0; $i<count($trending_products); $i++){
			$Reviews = getReviews($trending_products[$i]->id);
			$trending_products[$i]->TotalReview = $Reviews[0]->TotalReview;
			$trending_products[$i]->TotalRating = $Reviews[0]->TotalRating;
			$trending_products[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		//2 = Best Sellers
		$bs_sql = "SELECT a.id, a.brand_id, a.title, a.slug, a.f_thumbnail, a.sale_price, a.old_price, a.start_date, a.end_date, a.variation_color, a.variation_size, b.title labelname, b.color labelcolor, c.name brandname, d.shop_name, d.id seller_id, d.shop_url
			FROM products a
			LEFT JOIN labels b ON a.label_id = b.id
			LEFT JOIN brands c ON a.brand_id = c.id
			INNER JOIN users d ON a.user_id = d.id AND d.status_id = 1
			WHERE a.collection_id = 2 AND a.lan = '".$lan."' AND a.is_publish = 1 AND a.is_featured = 1 ORDER BY a.id DESC LIMIT 20;";
		$best_sellers = DB::select(DB::raw($bs_sql));
		
		for($i=0; $i<count($best_sellers); $i++){
			$Reviews = getReviews($best_sellers[$i]->id);
			$best_sellers[$i]->TotalReview = $Reviews[0]->TotalReview;
			$best_sellers[$i]->TotalRating = $Reviews[0]->TotalRating;
			$best_sellers[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		//1 = Available Offer
		$ao_sql = "SELECT a.id, a.brand_id, a.title, a.slug, a.f_thumbnail, a.sale_price, a.old_price, a.start_date, a.end_date, a.variation_color, a.variation_size, b.title labelname, b.color labelcolor, c.name brandname, d.shop_name, d.id seller_id, d.shop_url
			FROM products a
			LEFT JOIN labels b ON a.label_id = b.id
			LEFT JOIN brands c ON a.brand_id = c.id
			INNER JOIN users d ON a.user_id = d.id AND d.status_id = 1
			WHERE a.collection_id = 1 AND a.lan = '".$lan."' AND a.is_publish = 1 AND a.is_featured = 1 ORDER BY a.id DESC LIMIT 20;";
		$available_offer = DB::select(DB::raw($ao_sql));
		
		for($i=0; $i<count($available_offer); $i++){
			$Reviews = getReviews($available_offer[$i]->id);
			$available_offer[$i]->TotalReview = $Reviews[0]->TotalReview;
			$available_offer[$i]->TotalRating = $Reviews[0]->TotalRating;
			$available_offer[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}

		$tdata = Tp_option::where('option_name', 'trending')->get();

		$id = '';
		foreach ($tdata as $row){
			$id = $row->id;
		}

		$trending_data = array();
		if($id != ''){
			$sData = json_decode($tdata);
			$dataObj = json_decode($sData[0]->option_value);
			
			$trending_data['title'] = $dataObj->title;
			$trending_data['short_desc'] = $dataObj->short_desc;
			$trending_data['url'] = $dataObj->url;
			$trending_data['image'] = $dataObj->image;
			$trending_data['is_publish'] = $dataObj->is_publish;
		}else{
			$trending_data['title'] = '';
			$trending_data['short_desc'] = '';
			$trending_data['url'] = '';
			$trending_data['image'] = '';
			$trending_data['is_publish'] = '2';
		}
		
        return view('frontend.home', compact('slider', 'position_1', 'position_2', 'position_3', 'position_4', 'position_5', 'brand', 'new_arrivals', 'trending_products', 'best_sellers', 'available_offer', 'trending_data'));
    }
	
	//Get data for New Arrivals
	public function getNewArrivalsData(){
		$lan = glan();

		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('products.collection_id', '=', 4)
			->where('users.status_id', '=', 1)
			->where('products.lan', '=', $lan)
			->orderBy('products.id', 'desc')
			->paginate(20);

		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		return view('frontend.new-arrivals', compact('datalist'));
	}
	
	//Get data for Trending Products
	public function getTrendingProductsData(){
		$lan = glan();

		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('products.collection_id', '=', 3)
			->where('users.status_id', '=', 1)
			->where('products.lan', '=', $lan)
			->orderBy('products.id', 'desc')
			->paginate(20);

		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		return view('frontend.trending-products', compact('datalist'));
	}
	
	//Get data for Best Sellers
	public function getBestSellersData(){
		$lan = glan();

		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('products.collection_id', '=', 2)
			->where('users.status_id', '=', 1)
			->where('products.lan', '=', $lan)
			->orderBy('products.id', 'desc')
			->paginate(20);

		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		return view('frontend.best-sellers', compact('datalist'));
	}
	
	//Get data for Available Offer
	public function getAvailableOfferData(){
		$lan = glan();

		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('products.collection_id', '=', 1)
			->where('users.status_id', '=', 1)
			->where('products.lan', '=', $lan)
			->orderBy('products.id', 'desc')
			->paginate(20);

		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}
		
		return view('frontend.available-offer', compact('datalist'));
	}	
}
