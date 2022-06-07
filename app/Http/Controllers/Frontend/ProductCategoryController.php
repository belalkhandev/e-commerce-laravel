<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Pro_category;

class ProductCategoryController extends Controller
{
	
    //get Product Category Page
    public function getProductCategoryPage($id, $title){
		
		$params = array('category_id' => $id);
		
		$mdata = Pro_category::where('id', '=', $id)->where('is_publish', '=', 1)->first();
		if($mdata !=''){
			$metadata = $mdata;
		}else{
			$metadata = array(
				'id' => '',
				'name' => '',
				'slug' => '',
				'thumbnail' => '',
				'subheader_image' => '',
				'description' => '',
				'lan' => '',
				'parent_id' => '',
				'is_subheader' => '',
				'is_publish' => '',
				'og_title' => '',
				'og_image' => '',
				'og_description' => '',
				'og_keywords' => ''
			);
		}
		
		$datalist = DB::table('products')
			->leftJoin('labels', 'products.label_id', '=', 'labels.id')
			->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
			->join('users', 'products.user_id', '=', 'users.id')
			->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
			->where('products.is_publish', '=', 1)
			->where('users.status_id', '=', 1)
			->where('products.cat_id', '=', $id)
			->orderBy('products.id','desc')
			->paginate(30);
			
		for($i=0; $i<count($datalist); $i++){
			$Reviews = getReviews($datalist[$i]->id);
			$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
			$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
			$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
		}

        return view('frontend.product-category', compact('params', 'metadata', 'datalist'));
    }
	
	//Get data for Product Category Pagination
	public function getProductCategoryGrid(Request $request){

		$cat_ids = $request->categories;
		$brand_ids = isset($request->brands) ? $request->brands : '';
		
		$color = isset($request->color) ? $request->color : '';
		$size = isset($request->size) ? $request->size : '';

		
		if($request->num !=''){
			$num = $request->num;
		}else{
			$num = 12;
		}
		
		$field_name = 'id';
		$order_name = 'desc';
		if($request->sortby !=''){
			if($request->sortby == 'date_asc'){
				$field_name = 'created_at';
				$order_name = 'asc';
			}elseif($request->sortby == 'date_desc'){
				$field_name = 'created_at';
				$order_name = 'desc';
			}elseif($request->sortby == 'name_asc'){
				$field_name = 'title';
				$order_name = 'asc';
			}elseif($request->sortby == 'name_desc'){
				$field_name = 'title';
				$order_name = 'desc';
			}
		}else{
			$field_name = 'id';
			$order_name = 'desc';
		}
		
		if($request->ajax()){

			if($brand_ids !=''){
				
				$datalist = DB::table('products')
					->leftJoin('labels', 'products.label_id', '=', 'labels.id')
					->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
					->join('users', 'products.user_id', '=', 'users.id')
					->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
					->where('products.is_publish', '=', 1)
					->where('users.status_id', '=', 1)
					->whereIn('products.cat_id', $cat_ids)
					->whereIn('products.brand_id', $brand_ids)
					->where(function ($query) use ($color){
						if($color !=''){
							$query->where('variation_color', 'like', '%'.$color.'%');
						}
					})
					->where(function ($query) use ($size){
						if($size !=''){
							$query->where('variation_size', 'like', '%'.$size.'%');
						}
					})
					->orderBy('products.'.$field_name, $order_name)
					->paginate($num);
			}else{
				$datalist = DB::table('products')
					->leftJoin('labels', 'products.label_id', '=', 'labels.id')
					->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
					->join('users', 'products.user_id', '=', 'users.id')
					->select('products.id', 'products.brand_id', 'products.title', 'products.slug', 'products.f_thumbnail', 'products.sale_price', 'products.old_price', 'products.start_date', 'products.end_date', 'products.variation_color', 'products.variation_size', 'labels.title as labelname', 'labels.color as labelcolor', 'brands.name as brandname', 'users.shop_name', 'users.id as seller_id', 'users.shop_url')
					->where('products.is_publish', '=', 1)
					->where('users.status_id', '=', 1)
					->whereIn('products.cat_id', $cat_ids)
					->where(function ($query) use ($color){
						if($color !=''){
							$query->where('variation_color', 'like', '%'.$color.'%');
						}
					})
					->where(function ($query) use ($size){
						if($size !=''){
							$query->where('variation_size', 'like', '%'.$size.'%');
						}
					})
					->orderBy('products.'.$field_name, $order_name)
					->paginate($num);
			}

 			for($i=0; $i<count($datalist); $i++){
				$Reviews = getReviews($datalist[$i]->id);
				$datalist[$i]->TotalReview = $Reviews[0]->TotalReview;
				$datalist[$i]->TotalRating = $Reviews[0]->TotalRating;
				$datalist[$i]->ReviewPercentage = number_format($Reviews[0]->ReviewPercentage);
			}
			
			return view('frontend.partials.product-category-grid', compact('datalist'))->render();
		}
	}
}
