<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Cart;

class CartController extends Controller
{
	//Add to Cart
	public function AddToCart($id, $color, $size, $qty){

		$res = array();
		$datalist = Product::where('id', $id)->first();

		$data = array();
		$data['id'] = $datalist['id'];
		$data['name'] = $datalist['title'];
		$data['qty'] = $qty == 0 ? 1 : $qty;
		$data['price'] = $datalist['sale_price'];
		$data['weight'] = 0;
		$data['options'] = array();
		$data['options']['thumbnail'] = $datalist['f_thumbnail'];
		$data['options']['color'] = $color;
		$data['options']['size'] = $size;
		$data['options']['seller_id'] = $datalist['user_id'];

		$response = Cart::instance('shopping')->add($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Added product to cart successfully.');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Added product to cart failed.');
		}
		
		return response()->json($res);
	}
	
	//Add to Cart
	public function ViewCart(){
		$gtext = gtext();
		$gtax = getTax();
		$Path = asset('public/media');
		
		$data = Cart::instance('shopping')->content();
		
		$tax_rate = $gtax['percentage'];
		config(['cart.tax' => $tax_rate]);
		
		$items = '';
		foreach ($data as $key => $row) {
			
			$row->setTaxRate($tax_rate);
			Cart::instance('shopping')->update($row->rowId, $row->qty);

			if($gtext['currency_position'] == 'left'){
				$price = '<span id="product-quatity">'.$row->qty.'</span> x '.$gtext['currency_icon'].$row->price; 
			}else{
				$price = '<span id="product-quatity">'.$row->qty.'</span> x '.$row->price.$gtext['currency_icon']; 
			}
		
			$items .= '<div class="cart-item-group">
					<div class="cart-item-image">
						<img src="'.$Path.'/'.$row->options->thumbnail.'" />
					</div>
					<div class="cart-item-desc">
						<h4 class="item-title">'.$row->name.'</h4>
						<p class="item-quantity">'.$price.'</p>
						<a data-id="'.$row->rowId.'" id="removetocart_'.$row->id.'" onclick="onRemoveToCart('.$row->id.')" href="javascript:void(0);" class="btn-delete"><i class="bi bi-x-lg"></i></a>
					</div>
				</div>';
		}
		
		$count = Cart::instance('shopping')->count();
		$subtotal = Cart::instance('shopping')->subtotal();
		$tax = Cart::instance('shopping')->tax();
		$priceTotal = Cart::instance('shopping')->priceTotal();
		$total = Cart::instance('shopping')->total();
		
		$datalist = array();
		$datalist['items'] = $items;
		$datalist['total_qty'] = $count;
		if($gtext['currency_position'] == 'left'){
			$datalist['sub_total'] = $gtext['currency_icon'].$subtotal;
			$datalist['tax'] = $gtext['currency_icon'].$tax;
			$datalist['price_total'] = $gtext['currency_icon'].$priceTotal;
			$datalist['total'] = $gtext['currency_icon'].$total;
		}else{
			$datalist['sub_total'] = $subtotal.$gtext['currency_icon'];
			$datalist['tax'] = $tax.$gtext['currency_icon'];
			$datalist['price_total'] = $priceTotal.$gtext['currency_icon'];
			$datalist['total'] = $total.$gtext['currency_icon'];
		}

		return response()->json($datalist);
	}
	
	//Remove to Cart
	public function RemoveToCart($rowid){
		$res = array();

		$response = Cart::instance('shopping')->remove($rowid);

		if($response == ''){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Removed Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data remove failed');
		}
		
		return response()->json($res);
	}
	
    //get Cart
    public function getCart(){
        return view('frontend.cart');
    }
	
    //get Cart
    public function getViewCartData(){
		$gtext = gtext();
		$gtax = getTax();
		$Path = asset('public/media');
		
		$data = Cart::instance('shopping')->content();

		$tax_rate = $gtax['percentage'];
		config(['cart.tax' => $tax_rate]);
		
		$items = '';
		foreach ($data as $key => $row) {
			
			$row->setTaxRate($tax_rate);
			Cart::instance('shopping')->update($row->rowId, $row->qty);

			$pro_price = $row->price;
			$pro_qty = $row->qty;
			
			$total_Price = $row->price*$row->qty;
			
			if($gtext['currency_position'] == 'left'){ 
				$price = '<span class="pro-price">'.$gtext['currency_icon'].number_format($pro_price).'</span>'; 
			}else{
				$price = '<span class="pro-price">'.number_format($pro_price).$gtext['currency_icon'].'</span>';  
			}

			if($gtext['currency_position'] == 'left'){
				$totalPrice = $gtext['currency_icon'].number_format($total_Price);
			}else{
				$totalPrice = number_format($total_Price).$gtext['currency_icon'];
			}
			
			if($row->options->color == '0'){
				$color = '&nbsp;';
			}else{
				$color = '<strong>Color:</strong>'.$row->options->color;
			}
			
			if($row->options->size == '0'){
				$size = '&nbsp;';
			}else{
				$size = '<strong>Size:</strong>'.$row->options->size;
			}

			$items .= '<tr>
					<td class="pro-image-w">
						<div class="pro-image">
							<a href="'.route('frontend.product', [$row->id, str_slug($row->name)]).'"><img src="'.$Path.'/'.$row->options->thumbnail.'" /></a>
						</div>
					</td>
					<td class="pro-name-w" data-title="'.__('Product').':">
						<span class="pro-name"><a href="'.route('frontend.product', [$row->id, str_slug($row->name)]).'">'.$row->name.'</a></span>
					</td>
					<td class="pro-variation-w" data-title="'.__('Variation').':">
						<span class="pro-variation">'.$color.'  '.$size.'</span>
					</td>
					<td class="text-center pro-price-w" data-title="'.__('Price').':">
						<span class="pro-price">'.$price.'</span>
					</td>
					<td class="text-center pro-quantity-w" data-title="'.__('Quantity').':">
						<div class="pro-quantity">'.$row->qty.'</div>
					</td>
					<td class="text-center pro-total-price-w" data-title="'.__('Total').':">
						<span class="pro-total-price">'.$totalPrice.'</span>
					</td>
					<td class="text-center pro-remove-w" data-title="'.__('Remove').':">
						<a data-id="'.$row->rowId.'" id="removetoviewcart_'.$row->id.'" onclick="onRemoveToCart('.$row->id.')" href="javascript:void(0);" class="pro-remove"><i class="bi bi-x-lg"></i></a>
					</td>
				</tr>';	
				
		}
		
		$count = Cart::instance('shopping')->count();
		$subtotal = Cart::instance('shopping')->subtotal();
		$tax = Cart::instance('shopping')->tax();
		$priceTotal = Cart::instance('shopping')->priceTotal();
		$total = Cart::instance('shopping')->total();
		$discount = Cart::instance('shopping')->discount();
		
		$datalist = array();
		$datalist['items'] = $items;
		$datalist['total_qty'] = $count;
		if($gtext['currency_position'] == 'left'){
			$datalist['sub_total'] = $gtext['currency_icon'].$subtotal;
			$datalist['tax'] = $gtext['currency_icon'].$tax;
			$datalist['price_total'] = $gtext['currency_icon'].$priceTotal;
			$datalist['total'] = $gtext['currency_icon'].$total;
			$datalist['discount'] = $gtext['currency_icon'].$discount;
		}else{
			$datalist['sub_total'] = $subtotal.$gtext['currency_icon'];
			$datalist['tax'] = $tax.$gtext['currency_icon'];
			$datalist['price_total'] = $priceTotal.$gtext['currency_icon'];
			$datalist['total'] = $total.$gtext['currency_icon'];
			$datalist['discount'] = $discount.$gtext['currency_icon'];
		}

		return response()->json($datalist);
    }
	
	//Add to Wishlist
	public function addToWishlist($id){

		$res = array();
		$datalist = Product::where('id', $id)->first();

		$data = array();
		$data['id'] = $datalist['id'];
		$data['name'] = $datalist['title'];
		$data['qty'] = 1;
		$data['price'] = $datalist['sale_price'];
		$data['weight'] = 0;
		$data['options'] = array();
		$data['options']['thumbnail'] = $datalist['f_thumbnail'];

		$response = Cart::instance('wishlist')->add($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Added product to wishlist successfully.');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Added product to wishlist failed.');
		}
		
		return response()->json($res);
	}
	
    //get Wishlist
    public function getWishlist(){
		return view('frontend.wishlist');
	}
	
    public function getWishlistData(){
		$gtext = gtext();
		$Path = asset('public/media');

		$data = Cart::instance('wishlist')->content();

		$items = '';
		foreach ($data as $key => $row) {

			$pro_price = $row->price;

			if($gtext['currency_position'] == 'left'){ 
				$price = '<span class="pro-price">'.$gtext['currency_icon'].number_format($pro_price).'</span>'; 
			}else{
				$price = '<span class="pro-price">'.number_format($pro_price).$gtext['currency_icon'].'</span>';  
			}

			$items .= '<tr>
					<td class="text-center pro-remove-w" data-title="'.__('Remove').':">
						<a data-id="'.$row->rowId.'" id="removetowishlist_'.$row->id.'" onclick="onRemoveToWishlist('.$row->id.')" href="javascript:void(0);" class="pro-remove"><i class="bi bi-x-lg"></i></a>
					</td>
					<td class="pro-image-w">
						<div class="pro-image">
							<a href="'.route('frontend.product', [$row->id, str_slug($row->name)]).'">
							<img src="'.$Path.'/'.$row->options->thumbnail.'">
							</a>
						</div>
					</td>
					<td data-title="'.__('Product').':">
						<span class="pro-name"><a href="'.route('frontend.product', [$row->id, str_slug($row->name)]).'">'.$row->name.'</a></span>
					</td>
					<td class="text-center pro-price-w" data-title="'.__('Price').':">
						'.$price.'
					</td>
					<td class="text-center pro-addtocart-w" data-title="'.__('View').':">
						<div class="pro-addtocart">
							<a class="btn theme-btn cart" href="'.route('frontend.product', [$row->id, str_slug($row->name)]).'">'.__('View').'</a>
						</div>
					</td>
				</tr>';
		}
		
		return response()->json($items);
    }
	
	//Remove to Wishlist
	public function RemoveToWishlist($rowid){
		$res = array();

		$response = Cart::instance('wishlist')->remove($rowid);

		if($response == ''){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Removed Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data remove failed');
		}
		
		return response()->json($res);
	}
	
	//Count to Wishlist
	public function countWishlist(){

		$count = Cart::instance('wishlist')->content()->count();
		
		return response()->json($count);
	}
}
