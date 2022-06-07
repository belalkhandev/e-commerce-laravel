<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\Order_master;
use App\Models\Product;
use App\Models\Pro_category;
use App\Models\Brand;
use App\Models\User;

class DashboardController extends Controller
{
    //Dashboard page load
    public function getDashboardData(){
		$lan = glan();
		
		$total_order = Order_master::count();
		$pending_order = Order_master::where('order_status_id', 1)->count();
		$processing_order = Order_master::where('order_status_id', 2)->count();
		$ready_for_pickup_order = Order_master::where('order_status_id', 3)->count();
		$completed_order = Order_master::where('order_status_id', 4)->count();
		$canceled_order = Order_master::where('order_status_id', 5)->count();
		$published_product = Product::where('is_publish', '=', 1)->where('lan', '=', $lan)->count();
		$published_category = Pro_category::where('is_publish', '=', 1)->where('lan', '=', $lan)->count();
		$published_brand = Brand::where('is_publish', '=', 1)->where('lan', '=', $lan)->count();
		$review = Review::count();
		$total_customer = User::where('role_id', '=', 2)->count();
		$out_of_stock_products = Product::where('stock_status_id', '=', 0)->where('lan', '=', $lan)->count();
		
		$selling_sql = "SELECT a.product_id, b.title, b.slug, SUM(IFNULL(quantity, 0)) TotalSelling
		FROM order_items a
		INNER JOIN products b ON a.product_id = b.id
		GROUP BY a.product_id, b.title, b.slug
		ORDER BY TotalSelling DESC LIMIT 10;";
		$top_selling_products = DB::select(DB::raw($selling_sql));
		
		$review_sql = "SELECT a.item_id, b.title, b.slug, COUNT(a.id) TotalReview, SUM(rating) TotalRating
		FROM reviews a
		INNER JOIN products b ON a.item_id = b.id
		GROUP BY a.item_id, b.title, b.slug
		ORDER BY TotalRating DESC LIMIT 10;";
		$top_rating_products = DB::select(DB::raw($review_sql));
		
        return view('backend.dashboard', compact('total_order', 'pending_order', 'processing_order', 'ready_for_pickup_order', 'completed_order', 'canceled_order', 'published_product', 'published_category', 'published_brand', 'review', 'total_customer', 'out_of_stock_products', 'top_selling_products', 'top_rating_products'));
    }
}
