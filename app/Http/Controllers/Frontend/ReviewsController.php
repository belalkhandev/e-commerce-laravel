<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Review;

class ReviewsController extends Controller
{
	
    public function saveReviews(Request $request)
    {
		$user_id = 0;
		if(isset(Auth::user()->id)){
			$user_id = Auth::user()->id;
		}else{
			return redirect()->back()->withFail(__('Oops! You are unauthorized. Please login.'));
		}

		$request->validate([
			'rating' => 'required',
			'comments' => 'required',
		]);
		
		$item_id = $request->input('item_id');
		
		$ReviewCount = Review::where('item_id', '=', $item_id)->where('user_id', '=', $user_id)->count();
		
		if($ReviewCount>0){
			return redirect()->back()->withFail(__('You have reviewed this product already!'));
		}
		
		$data = array(
			'item_id' => $item_id,
			'user_id' => $user_id,
			'rating' => $request->input('rating'),
			'comments' => $request->input('comments')
		);
		
		$response = Review::create($data);
		
		if($response){
			return redirect()->back()->withSuccess(__('Thanks for your review'));
		}else{
			return redirect()->back()->withFail(__('Oops! You are failed review. Please try again.'));
		}
    }
}
