<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order_master;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    //Dashboard page load
    public function getDashboardData(){
		$seller_id = Auth::user()->id;
		$lan = glan();
		
		$total_order = Order_master::where('seller_id', '=', $seller_id)->count();
		$pending_order = Order_master::where('order_status_id', '=', 1)->where('seller_id', '=', $seller_id)->count();
		$processing_order = Order_master::where('order_status_id', '=', 2)->where('seller_id', '=', $seller_id)->count();
		$ready_for_pickup_order = Order_master::where('order_status_id', '=', 3)->where('seller_id', '=', $seller_id)->count();
		$completed_order = Order_master::where('order_status_id', '=', 4)->where('seller_id', '=', $seller_id)->count();
		$canceled_order = Order_master::where('order_status_id', '=', 5)->where('seller_id', '=', $seller_id)->count();
		
		$published_product = Product::where('is_publish', '=', 1)->where('user_id', '=', $seller_id)->where('lan', '=', $lan)->count();
		
		$sql = "SELECT COUNT(*) TotalReviews
		FROM reviews a
		INNER JOIN products b ON a.item_id = b.id
		WHERE b.user_id = '".$seller_id."';";
		$aRow = DB::select(DB::raw($sql));
		$review = $aRow[0]->TotalReviews;
		
		$selling_sql = "SELECT a.product_id, b.title, b.slug, SUM(IFNULL(quantity, 0)) TotalSelling
		FROM order_items a
		INNER JOIN products b ON a.product_id = b.id
		WHERE b.user_id = '".$seller_id."'
		GROUP BY a.product_id, b.title, b.slug
		ORDER BY TotalSelling DESC LIMIT 10;";
		$top_selling_products = DB::select(DB::raw($selling_sql));
		
		$review_sql = "SELECT a.item_id, b.title, b.slug, COUNT(a.id) TotalReview, SUM(rating) TotalRating
		FROM reviews a
		INNER JOIN products b ON a.item_id = b.id
		WHERE b.user_id = '".$seller_id."'
		GROUP BY a.item_id, b.title, b.slug
		ORDER BY TotalRating DESC LIMIT 10;";
		$top_rating_products = DB::select(DB::raw($review_sql));
		
        return view('seller.dashboard', compact('total_order', 'pending_order', 'processing_order', 'ready_for_pickup_order', 'completed_order', 'canceled_order', 'published_product', 'review', 'top_selling_products', 'top_rating_products'));
    }
}
