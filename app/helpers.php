<?php

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Tp_option;
use App\Models\Media_setting;
use App\Models\Menu;
use App\Models\Menu_parent;
use App\Models\Mega_menu;
use App\Models\Menu_child;
use App\Models\Pro_category;
use App\Models\Attribute;
use App\Models\Tax;
use App\Models\Order_master;
use App\Models\Social_media;
use Illuminate\Support\Facades\Auth;

//Get data for Language locale
function glan(){
	$lan = app()->getLocale();
	
	return $lan;
}

//Category List
function CategoryMenuList(){
	$lan = glan();
	
	$datalist = Pro_category::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('name','ASC')->get();
	$li_List = '';
	foreach($datalist as $row){
		$id = $row->id;
		$slug = $row->slug;

		$li_List .= '<li><a href="'.route('frontend.product-category', [$id, $slug]).'">'.$row->name.'</a></li>';
	}
	
	return $li_List;
}

//Menu List
function HeaderMenuList($MenuType){

	$lan = glan();

	$sql = "SELECT b.id, b.menu_id, b.menu_type, b.child_menu_type, b.item_id, b.item_label, b.custom_url, 
	b.target_window, b.css_class, b.`column`, b.width_type, b.width, b.lan, b.sort_order
	FROM menus a
	INNER JOIN menu_parents b ON a.id = b.menu_id
	WHERE a.menu_position = 'header'
	AND a.lan = '".$lan."'
	AND a.status_id  = 1
	ORDER BY sort_order ASC;";
	$datalist = DB::select(DB::raw($sql));
	$MenuList = '';
	$MegaDropdownMenuList = '';
	$full_width = '';
	$hasChildrenMenu = '';
	foreach($datalist as $row){

		$menu_id = $row->menu_id;
		$menu_parent_id = $row->id;
		
		$item_id = $row->item_id;
		$custom_url = $row->custom_url;
		
		//Menu list for Desktop
		if($MenuType == 'HeaderMenuListForDesktop'){
			
			if($row->child_menu_type == 'mega_menu'){
				$MegaDropdownMenuList = makeMegaMenu($menu_id, $menu_parent_id, $row->width_type, $row->width, $MenuType);
			}elseif($row->child_menu_type == 'dropdown'){
				$MegaDropdownMenuList = makeDropdownMenu($menu_id, $menu_parent_id, $MenuType);
			}else{
				$MegaDropdownMenuList = '';
			}
			
			if($row->width_type == 'full_width'){
				$full_width = 'class="tp-static"';
			}else{
				$full_width = '';
			}
			
			if($row->menu_type == 'page'){
				$MenuList .= '<li '.$full_width.'><a target="'.$row->target_window.'" href="'.route('frontend.page', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';

			}elseif($row->menu_type == 'brand'){
				$MenuList .= '<li '.$full_width.'><a target="'.$row->target_window.'" href="'.route('frontend.brand', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';
		
			}elseif($row->menu_type == 'custom_link'){
				$MenuList .= '<li '.$full_width.'><a target="'.$row->target_window.'" href="'.$custom_url.'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';

			}elseif($row->menu_type == 'product'){
				$MenuList .= '<li '.$full_width.'><a target="'.$row->target_window.'" href="'.route('frontend.product', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';
		
			}elseif($row->menu_type == 'product_category'){
				$MenuList .= '<li '.$full_width.'><a target="'.$row->target_window.'" href="'.route('frontend.product-category', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';
			}
			
		//Menu list for Mobile
		}else{
			
			if($row->child_menu_type == 'mega_menu'){
				$MegaDropdownMenuList = makeMegaMenu($menu_id, $menu_parent_id, $row->width_type, $row->width, $MenuType);
				$hasChildrenMenu = 'class="has-children-menu"';
			}elseif($row->child_menu_type == 'dropdown'){
				$MegaDropdownMenuList = makeDropdownMenu($menu_id, $menu_parent_id, $MenuType);
				$hasChildrenMenu = 'class="has-children-menu"';
			}else{
				$MegaDropdownMenuList = '';
				$hasChildrenMenu = '';
			}
			
			if($row->menu_type == 'page'){
				$MenuList .= '<li '.$hasChildrenMenu.'><a target="'.$row->target_window.'" href="'.route('frontend.page', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';

			}elseif($row->menu_type == 'brand'){
				$MenuList .= '<li '.$hasChildrenMenu.'><a target="'.$row->target_window.'" href="'.route('frontend.brand', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';
		
			}elseif($row->menu_type == 'custom_link'){
				$MenuList .= '<li '.$hasChildrenMenu.'><a target="'.$row->target_window.'" href="'.$row->custom_url.'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';

			}elseif($row->menu_type == 'product'){
				$MenuList .= '<li '.$hasChildrenMenu.'><a target="'.$row->target_window.'" href="'.route('frontend.product', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';
		
			}elseif($row->menu_type == 'product_category'){
				$MenuList .= '<li '.$hasChildrenMenu.'><a target="'.$row->target_window.'" href="'.route('frontend.product-category', [$item_id, $custom_url]).'">'.$row->item_label.'</a>'.$MegaDropdownMenuList.'</li>';
			}
		}
	}

	return $MenuList;
}

function makeMegaMenu($menu_id, $menu_parent_id, $width_type, $width, $MenuType){
	
	$sql = "SELECT a.id, a.mega_menu_title, a.is_title, a.is_image, a.image, a.sort_order, b.column, b.width_type, b.width, b.css_class
	FROM mega_menus a 
	INNER JOIN menu_parents b ON a.menu_parent_id = b.id
	WHERE a.menu_id = '".$menu_id."'
	AND a.menu_parent_id = '".$menu_parent_id."'
	ORDER BY a.sort_order ASC;";
	$datalist = DB::select(DB::raw($sql));
	
	$ul_List = '';
	$title = '';
	$imageOrMegaLiList = '';
	foreach($datalist as $row){
		$mega_menu_id = $row->id;
		
		//Menu list for Desktop
		if($MenuType == 'HeaderMenuListForDesktop'){
		
			if($row->is_title == 1){
				$title = '<li class="mega-title">'.$row->mega_menu_title.'</li>';
			}else{
				$title = '';
			}
			
			if($row->is_image == 1){
				if($row->image != ''){
					$Path = asset('public/media');
					$imageOrMegaLiList = '<img src="'.$Path.'/'.$row->image.'" />';
				}else{
					$imageOrMegaLiList = '';
				}
			}else{
				$imageOrMegaLiList = mega_liList($menu_id, $menu_parent_id, $mega_menu_id, $MenuType);
			}
			
			if($row->width_type == 'full_width'){
				$ul_List .= '<ul class="mega-col-'.$row->column.' '.$row->css_class.'">
							'.$title.$imageOrMegaLiList.'
						</ul>';
			}else{
				$ul_List .= '<ul class="megafixed-col-'.$row->column.' '.$row->css_class.'">
							'.$title.$imageOrMegaLiList.'
						</ul>';
			}
		
		//Menu list for Mobile
		}else{
			if($row->is_image == 1){
				$imageOrMegaLiList = '';
			}else{
				$imageOrMegaLiList = mega_liList($menu_id, $menu_parent_id, $mega_menu_id, $MenuType);
			}
			
			$ul_List .= '<li class="has-children-menu"><a href="#">'.$row->mega_menu_title.'</a>
						<ul class="dropdown">'.$imageOrMegaLiList.'</ul>
					</li>';
		}
	}
	
	//Menu list for Desktop
	if($MenuType == 'HeaderMenuListForDesktop'){
		if($width_type == 'full_width'){
			$MenuList = '<div class="mega-menu mega-full">'.$ul_List.'</div>';
		}else{
			$MenuList = '<div class="mega-menu" style="width:'.$width.'px;">'.$ul_List.'</div>';
		}
	
	//Menu list for Mobile
	}else{
		$MenuList = '<ul class="dropdown">'.$ul_List.'</ul>';
	}
	
	return $MenuList;	
}

function mega_liList($menu_id, $menu_parent_id, $mega_menu_id, $MenuType){
	
	$datalist = Menu_child::where('menu_id', '=', $menu_id)
			->where('menu_parent_id', '=', $menu_parent_id)
			->where('mega_menu_id', '=', $mega_menu_id)
			->orderBy('sort_order','ASC')->get();

	$li_List = '';
	foreach($datalist as $row){
		
		$item_id = $row->item_id;
		$custom_url = $row->custom_url;
		
		if($row->menu_type == 'page'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.page', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';

		}elseif($row->menu_type == 'brand'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.brand', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
			
		}elseif($row->menu_type == 'custom_link'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.$custom_url.'">'.$row->item_label.'</a></li>';

		}elseif($row->menu_type == 'product'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.product', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
			
		}elseif($row->menu_type == 'product_category'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.product-category', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
		}
	}

	return $li_List;
}

function makeDropdownMenu($menu_id, $menu_parent_id, $MenuType){

	$datalist = Menu_child::where('menu_id', '=', $menu_id)
			->where('menu_parent_id', '=', $menu_parent_id)
			->orderBy('sort_order','ASC')->get();

	$li_List = '';
	foreach($datalist as $row){
		
		$item_id = $row->item_id;
		$custom_url = $row->custom_url;
		
		if($row->menu_type == 'page'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.page', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';

		}elseif($row->menu_type == 'brand'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.brand', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
			
		}elseif($row->menu_type == 'custom_link'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.$custom_url.'">'.$row->item_label.'</a></li>';

		}elseif($row->menu_type == 'product'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.product', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
			
		}elseif($row->menu_type == 'product_category'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.product-category', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
		}
	}
	
	//Menu list for Desktop
	if($MenuType == 'HeaderMenuListForDesktop'){
		$MenuList = '<ul class="submenu">'.$li_List.'</ul>';
	
	//Menu list for Mobile
	}else{
		$MenuList = '<ul class="dropdown">'.$li_List.'</ul>';
	}
	
	return $MenuList;
}

//Footer Menu List
function FooterMenuList($MenuType){

	$lan = glan();

	$sql = "SELECT b.id, b.menu_id, b.menu_type, b.item_id, b.item_label, b.custom_url, b.target_window, b.sort_order
	FROM menus a
	INNER JOIN menu_parents b ON a.id = b.menu_id
	WHERE a.menu_position = '".$MenuType."'
	AND a.lan = '".$lan."'
	AND a.status_id  = 1
	ORDER BY sort_order ASC;";
	$datalist = DB::select(DB::raw($sql));
	$li_List = '';
	foreach($datalist as $row){
		$item_id = $row->item_id;
		$custom_url = $row->custom_url;
		
		if($row->menu_type == 'page'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.page', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';

		}elseif($row->menu_type == 'brand'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.brand', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
			
		}elseif($row->menu_type == 'custom_link'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.$custom_url.'">'.$row->item_label.'</a></li>';

		}elseif($row->menu_type == 'product'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.product', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
			
		}elseif($row->menu_type == 'product_category'){
			$li_List .= '<li><a target="'.$row->target_window.'" href="'.route('frontend.product-category', [$item_id, $custom_url]).'">'.$row->item_label.'</a></li>';
		}
	}

	return $li_List;
}

function gtext(){

	$data = array();
	
	//general_settings
	$general_settings = Tp_option::where('option_name', 'general_settings')->get();
	
	$general_settings_id = '';
	foreach ($general_settings as $row){
		$general_settings_id = $row->id;
	}

	if($general_settings_id != ''){
		$general_settingsData = json_decode($general_settings);
		$general_settingsDataObj = json_decode($general_settingsData[0]->option_value);
		$data['site_name'] = $general_settingsDataObj->site_name;
		$data['site_title'] = $general_settingsDataObj->site_title;
		$data['company'] = $general_settingsDataObj->company;
		$data['invoice_email'] = $general_settingsDataObj->email;
		$data['invoice_phone'] = $general_settingsDataObj->phone;
		$data['invoice_address'] = $general_settingsDataObj->address;
		$data['timezone'] = $general_settingsDataObj->timezone;
	}else{
		$data['site_name'] = 'bShop';
		$data['site_title'] = 'Laravel eCommerce Shopping Platform';
		$data['company'] = '';
		$data['invoice_email'] = '';
		$data['invoice_phone'] = '';
		$data['invoice_address'] = '';
		$data['timezone'] = '';
	}
	
	//theme_logo
	$theme_logo = Tp_option::where('option_name', 'theme_logo')->get();
	
	$theme_logo_id = '';
	foreach ($theme_logo as $row){
		$theme_logo_id = $row->id;
	}

	if($theme_logo_id != ''){
		$theme_logoData = json_decode($theme_logo);
		$theme_logoObj = json_decode($theme_logoData[0]->option_value);
		$data['favicon'] = $theme_logoObj->favicon;
		$data['front_logo'] = $theme_logoObj->front_logo;
		$data['back_logo'] = $theme_logoObj->back_logo;
	}else{
		$data['favicon'] = '';
		$data['front_logo'] = '';
		$data['back_logo'] = '';
	}
	
	//currency
 	$currency = Tp_option::where('option_name', 'currency')->get();
	
	$currency_id = '';
	foreach ($currency as $row){
		$currency_id = $row->id;
	}

	if($currency_id != ''){
		$currencyData = json_decode($currency);
		$currencyObj = json_decode($currencyData[0]->option_value);
		$data['currency_name'] = $currencyObj->currency_name;
		$data['currency_icon'] = $currencyObj->currency_icon;
		$data['currency_position'] = $currencyObj->currency_position;
	}else{
		$data['currency_name'] = '';
		$data['currency_icon'] = '';
		$data['currency_position'] = '';
	}
	
	//theme_option_header
 	$theme_option_header = Tp_option::where('option_name', 'theme_option_header')->get();
	
	$theme_option_header_id = '';
	foreach ($theme_option_header as $row){
		$theme_option_header_id = $row->id;
	}

	if($theme_option_header_id != ''){
		$theme_option_headerData = json_decode($theme_option_header);
		$theme_option_headerObj = json_decode($theme_option_headerData[0]->option_value);
		$data['address'] = $theme_option_headerObj->address;
		$data['phone'] = $theme_option_headerObj->phone;
		$data['is_publish'] = $theme_option_headerObj->is_publish;
	}else{
		$data['address'] = '';
		$data['phone'] = '';
		$data['is_publish'] = '';
	}
	
	//Language Switcher
	$language_switcher_data = Tp_option::where('option_name', 'language_switcher')->get();
	
	$language_switcher_id = '';
	foreach ($language_switcher_data as $row){
		$language_switcher_id = $row->id;
	}

	if($language_switcher_id != ''){
		$wsData = json_decode($language_switcher_data);
		$wsObj = json_decode($wsData[0]->option_value);

		$data['is_language_switcher'] = $wsObj->is_language_switcher;
	}else{
		$data['is_language_switcher'] = '';
	}	
	
	//theme_option_footer
 	$theme_option_footer = Tp_option::where('option_name', 'theme_option_footer')->get();
	
	$theme_option_footer_id = '';
	foreach ($theme_option_footer as $row){
		$theme_option_footer_id = $row->id;
	}

	if($theme_option_footer_id != ''){
		$theme_option_footerData = json_decode($theme_option_footer);
		$theme_option_footerObj = json_decode($theme_option_footerData[0]->option_value);
		$data['address_footer'] = $theme_option_footerObj->address;
		$data['email_footer'] = $theme_option_footerObj->email;
		$data['phone_footer'] = $theme_option_footerObj->phone;
		$data['is_publish_contact'] = $theme_option_footerObj->is_publish_contact;
		$data['copyright'] = $theme_option_footerObj->copyright;
		$data['is_publish_copyright'] = $theme_option_footerObj->is_publish_copyright;
		$data['payment_gateway_icon'] = $theme_option_footerObj->payment_gateway_icon;
		$data['is_publish_payment'] = $theme_option_footerObj->is_publish_payment;
	}else{
		$data['address_footer'] = '';
		$data['email_footer'] = '';
		$data['phone_footer'] = '';
		$data['is_publish_contact'] = '';
		$data['copyright'] = '';
		$data['is_publish_copyright'] = '';
		$data['payment_gateway_icon'] = '';
		$data['is_publish_payment'] = '';
	}
	
	//isRTL
	$isRTL = Language::where('language_code', app()->getLocale())->first();
	$data['is_rtl'] = $isRTL['is_rtl'];
	
	//facebook
 	$facebook = Tp_option::where('option_name', 'facebook')->get();
	
	$facebook_id = '';
	foreach ($facebook as $row){
		$facebook_id = $row->id;
	}

	if($facebook_id != ''){
		$facebookData = json_decode($facebook);
		$facebookObj = json_decode($facebookData[0]->option_value);
		$data['fb_app_id'] = $facebookObj->fb_app_id;
		$data['fb_publish'] = $facebookObj->is_publish;
	}else{
		$data['fb_app_id'] = '';
		$data['fb_publish'] = '';
	}
	
	//twitter
 	$twitter = Tp_option::where('option_name', 'twitter')->get();
	
	$twitter_id = '';
	foreach ($twitter as $row){
		$twitter_id = $row->id;
	}

	if($twitter_id != ''){
		$twitterData = json_decode($twitter);
		$twitterObj = json_decode($twitterData[0]->option_value);
		$data['twitter_id'] = $twitterObj->twitter_id;
		$data['twitter_publish'] = $twitterObj->is_publish;
	}else{
		$data['twitter_id'] = '';
		$data['twitter_publish'] = '';
	}
	
	//Theme Option SEO
 	$theme_option_seo = Tp_option::where('option_name', 'theme_option_seo')->get();
	
	$theme_option_seo_id = '';
	foreach ($theme_option_seo as $row){
		$theme_option_seo_id = $row->id;
	}

	if($theme_option_seo_id != ''){
		$SEOData = json_decode($theme_option_seo);
		$SEOObj = json_decode($SEOData[0]->option_value);
		$data['og_title'] = $SEOObj->og_title;
		$data['og_image'] = $SEOObj->og_image;
		$data['og_description'] = $SEOObj->og_description;
		$data['og_keywords'] = $SEOObj->og_keywords;
		$data['seo_publish'] = $SEOObj->is_publish;
	}else{
		$data['og_title'] = '';
		$data['og_image'] = '';
		$data['og_description'] = '';
		$data['og_keywords'] = '';
		$data['seo_publish'] = '';
	}
	
	//Theme Option Facebook Pixel
 	$theme_option_facebook_pixel = Tp_option::where('option_name', 'facebook-pixel')->get();
	
	$theme_option_fb_pixel_id = '';
	foreach ($theme_option_facebook_pixel as $row){
		$theme_option_fb_pixel_id = $row->id;
	}

	if($theme_option_fb_pixel_id != ''){
		$fb_PixelData = json_decode($theme_option_facebook_pixel);
		$fb_PixelObj = json_decode($fb_PixelData[0]->option_value);
		$data['fb_pixel_id'] = $fb_PixelObj->fb_pixel_id;
		$data['fb_pixel_publish'] = $fb_PixelObj->is_publish;
	}else{
		$data['fb_pixel_id'] = '';
		$data['fb_pixel_publish'] = '';
	}
	
	//Theme Option Google Analytics
 	$theme_option_google_analytics = Tp_option::where('option_name', 'google_analytics')->get();
	
	$theme_option_ga_id = '';
	foreach ($theme_option_google_analytics as $row){
		$theme_option_ga_id = $row->id;
	}

	if($theme_option_ga_id != ''){
		$gaData = json_decode($theme_option_google_analytics);
		$gaObj = json_decode($gaData[0]->option_value);
		$data['tracking_id'] = $gaObj->tracking_id;
		$data['ga_publish'] = $gaObj->is_publish;
	}else{
		$data['tracking_id'] = '';
		$data['ga_publish'] = '';
	}
	
	//Theme Option Google Tag Manager
 	$theme_option_google_tag_manager = Tp_option::where('option_name', 'google_tag_manager')->get();
	
	$theme_option_gtm_id = '';
	foreach ($theme_option_google_tag_manager as $row){
		$theme_option_gtm_id = $row->id;
	}

	if($theme_option_gtm_id != ''){
		$gtmData = json_decode($theme_option_google_tag_manager);
		$gtmObj = json_decode($gtmData[0]->option_value);
		$data['google_tag_manager_id'] = $gtmObj->google_tag_manager_id;
		$data['gtm_publish'] = $gtmObj->is_publish;
	}else{
		$data['google_tag_manager_id'] = '';
		$data['gtm_publish'] = '';
	}
	
	//Google Recaptcha
 	$theme_option_google_recaptcha = Tp_option::where('option_name', 'google_recaptcha')->get();
	
	$google_recaptcha_id = '';
	foreach ($theme_option_google_recaptcha as $row){
		$google_recaptcha_id = $row->id;
	}
	
	if($google_recaptcha_id != ''){
		$grData = json_decode($theme_option_google_recaptcha);
		$grObj = json_decode($grData[0]->option_value);
		$data['sitekey'] = $grObj->sitekey;
		$data['secretkey'] = $grObj->secretkey;
		$data['is_recaptcha'] = $grObj->is_recaptcha;
	}else{
		$data['sitekey'] = '';
		$data['secretkey'] = '';
		$data['is_recaptcha'] = '';
	}
	
	//Theme Color
 	$theme_color = Tp_option::where('option_name', 'theme_color')->get();
	
	$theme_color_id = '';
	foreach ($theme_color as $row){
		$theme_color_id = $row->id;
	}
	
	if($theme_color_id != ''){
		$tcData = json_decode($theme_color);
		$tcObj = json_decode($tcData[0]->option_value);
		$data['theme_color'] = $tcObj->theme_color;
		$data['menu_background_color'] = $tcObj->menu_background_color;
		$data['backend_theme_color'] = $tcObj->backend_theme_color;
	}else{
		$data['theme_color'] = '#c62f2f';
		$data['menu_background_color'] = '#2d3748';
		$data['backend_theme_color'] = '#7c70f2';
	}
	
	//Mail Settings
 	$theme_option_mail_settings = Tp_option::where('option_name', 'mail_settings')->get();
	
	$mail_settings_id = '';
	foreach ($theme_option_mail_settings as $row){
		$mail_settings_id = $row->id;
	}

	if($mail_settings_id != ''){
		$msData = json_decode($theme_option_mail_settings);
		$msObj = json_decode($msData[0]->option_value);
		$data['ismail'] = $msObj->ismail;
		$data['from_name'] = $msObj->from_name;
		$data['from_mail'] = $msObj->from_mail;
		$data['to_name'] = $msObj->to_name;
		$data['to_mail'] = $msObj->to_mail;
		$data['mailer'] = $msObj->mailer;
		$data['smtp_host'] = $msObj->smtp_host;
		$data['smtp_port'] = $msObj->smtp_port;
		$data['smtp_security'] = $msObj->smtp_security;
		$data['smtp_username'] = $msObj->smtp_username;
		$data['smtp_password'] = $msObj->smtp_password;
	}else{
		$data['ismail'] = '';
		$data['from_name'] = '';
		$data['from_mail'] = '';
		$data['to_name'] = '';
		$data['to_mail'] = '';
		$data['mailer'] = '';
		$data['smtp_host'] = '';
		$data['smtp_port'] = '';
		$data['smtp_security'] = '';
		$data['smtp_username'] = '';
		$data['smtp_password'] = '';
	}

	//Stripe
	$stripe_data = Tp_option::where('option_name', 'stripe')->get();
	
	$stripe_id = '';
	foreach ($stripe_data as $row){
		$stripe_id = $row->id;
	}

	if($stripe_id != ''){
		$sData = json_decode($stripe_data);
		$sObj = json_decode($sData[0]->option_value);
		$data['stripe_key'] = $sObj->stripe_key;
		$data['stripe_secret'] = $sObj->stripe_secret;
		$data['stripe_currency'] = $sObj->currency;
		$data['stripe_isenable'] = $sObj->isenable;
	}else{
		$data['stripe_key'] = '';
		$data['stripe_secret'] = '';
		$data['stripe_currency'] = '';
		$data['stripe_isenable'] = '';
	}

	//Cash on Delivery (COD)
	$cod_data = Tp_option::where('option_name', 'cash_on_delivery')->get();
	
	$cod_id = '';
	foreach ($cod_data as $row){
		$cod_id = $row->id;
	}

	if($cod_id != ''){
		$codData = json_decode($cod_data);
		$codObj = json_decode($codData[0]->option_value);
		$data['cod_description'] = $codObj->description;
		$data['cod_isenable'] = $codObj->isenable;
	}else{
		$data['cod_description'] = '';
		$data['cod_isenable'] = '';
	}
	
	//Bank Transfer
	$bank_data = Tp_option::where('option_name', 'bank_transfer')->get();
	
	$bank_id = '';
	foreach ($bank_data as $row){
		$bank_id = $row->id;
	}

	if($bank_id != ''){
		$btData = json_decode($bank_data);
		$btObj = json_decode($btData[0]->option_value);
		$data['bank_description'] = $btObj->description;
		$data['bank_isenable'] = $btObj->isenable;
	}else{
		$data['bank_description'] = '';
		$data['bank_isenable'] = '';
	}

	//MailChimp
	$mailchimp_data = Tp_option::where('option_name', 'mailchimp')->get();
	
	$mailchimp_id = '';
	foreach ($mailchimp_data as $row){
		$mailchimp_id = $row->id;
	}

	if($mailchimp_id != ''){
		$mcData = json_decode($mailchimp_data);
		$mcObj = json_decode($mcData[0]->option_value);
		$data['mailchimp_api_key'] = $mcObj->mailchimp_api_key;
		$data['audience_id'] = $mcObj->audience_id;
		$data['is_mailchimp'] = $mcObj->is_mailchimp;
	}else{
		$data['mailchimp_api_key'] = '';
		$data['audience_id'] = '';
		$data['is_mailchimp'] = '';
	}

	//Subscribe Popup
	$subscribe_popup_data = Tp_option::where('option_name', 'subscribe_popup')->get();
	
	$subscribe_id = '';
	foreach ($subscribe_popup_data as $row){
		$subscribe_id = $row->id;
	}

	if($subscribe_id != ''){
		$spData = json_decode($subscribe_popup_data);
		$spObj = json_decode($spData[0]->option_value);
		$data['subscribe_popup_desc'] = $spObj->subscribe_popup_desc;
		$data['is_subscribe_popup'] = $spObj->is_subscribe_popup;
		$data['is_subscribe_footer'] = $spObj->is_subscribe_footer;
	}else{
		$data['subscribe_popup_desc'] = '';
		$data['is_subscribe_popup'] = '';
		$data['is_subscribe_footer'] = '';
	}
	
	//Whatsapp
	$whatsapp_data = Tp_option::where('option_name', 'whatsapp')->get();
	
	$whatsapp_id = '';
	foreach ($whatsapp_data as $row){
		$whatsapp_id = $row->id;
	}

	if($whatsapp_id != ''){
		$wsData = json_decode($whatsapp_data);
		$wsObj = json_decode($wsData[0]->option_value);
		$data['whatsapp_id'] = $wsObj->whatsapp_id;
		$data['whatsapp_text'] = $wsObj->whatsapp_text;
		$data['position'] = $wsObj->position;
		$data['is_whatsapp_publish'] = $wsObj->is_publish;
	}else{
		$data['whatsapp_id'] = '';
		$data['whatsapp_text'] = '';
		$data['position'] = '';
		$data['is_whatsapp_publish'] = '';
	}
	
	//custom_css
	$custom_css_data = Tp_option::where('option_name', 'custom_css')->get();
	$custom_css = '';
	foreach ($custom_css_data as $row){
		$custom_css = $row->option_value;
	}
	$data['custom_css'] = $custom_css;

	//custom_js
	$custom_js_data = Tp_option::where('option_name', 'custom_js')->get();
	$custom_js = '';
	foreach ($custom_js_data as $row){
		$custom_js = $row->option_value;
	}
	$data['custom_js'] = $custom_js;

	
	
	return $data;
}

//Category List for Filter
function CategoryListForFilter(){
	$lan = glan();

	$sql = "SELECT b.id, b.slug, b.name, COUNT(a.id) TotalProduct
	FROM products a
	RIGHT JOIN pro_categories b ON a.cat_id = b.id
	WHERE b.is_publish = 1 AND b.lan = '".$lan."'
	GROUP BY b.id, b.slug, b.name
	ORDER BY b.name;";
	$datalist = DB::select(DB::raw($sql));
	$li_List = '';
	foreach($datalist as $row){
		$id = $row->id;
		$slug = $row->slug;
		$TotalProduct = $row->TotalProduct;
		
		$li_List .= '<li>
					<label class="checkbox-title">
						<input type="checkbox" class="filter_by_category" name="filter_by_category" id="filter_by_category_'.$id.'" value="'.$id.'">'.$row->name.' <span class="num">('.$TotalProduct.')</span>
					</label>
				</li>';
	}
	
	return $li_List;
}

//Brand List for Filter
function BrandListForFilter(){
	$lan = glan();
	
	$sql = "SELECT b.id, b.name, COUNT(a.id) TotalProduct
	FROM products a
	RIGHT JOIN brands b ON a.brand_id = b.id
	WHERE b.is_publish = 1 AND b.lan = '".$lan."'
	GROUP BY b.id, b.name
	ORDER BY b.name;";
	$datalist = DB::select(DB::raw($sql));
	$li_List = '';
	foreach($datalist as $row){
		$id = $row->id;
		$TotalProduct = $row->TotalProduct;

		$li_List .= '<li>
					<label class="checkbox-title">
						<input type="checkbox" class="filter_by_brand" name="filter_by_brand" id="filter_by_brand_'.$id.'" value="'.$id.'">'.$row->name.' <span class="num">('.$TotalProduct.')</span>
					</label>
				</li>';
	}
	
	return $li_List;
}

//Color List for Filter
function ColorListForFilter(){

	$datalist = Attribute::where('att_type', 'Color')->orderBy('id','asc')->get();
	$li_List = '';
	foreach($datalist as $row){
		$id = $row->id;
		$name = $row->name;
		$color = $row->color;

		$li_List .= '<li class="active_color" id="color_'.$id.'"><a data-color="'.$id.'" id="'.$name.'|'.$color.'" class="filter_by_color" href="javascript:void(0);" title="'.$name.'"><span style="background:'.$color.';"></span></a></li>';
	}
	
	return $li_List;
}

//Size List for Filter
function SizeListForFilter(){
	$lan = glan();

	$datalist = Attribute::where('att_type', 'Size')->orderBy('id','asc')->get();

	$li_List = '';
	foreach($datalist as $row){
		$id = $row->id;
		$li_List .= '<li class="active_size" id="size_'.$id.'"><a data-size="'.$id.'" id="'.$row->name.'" class="filter_by_size" href="javascript:void(0);">'.$row->name.'</a></li>';
	}
	
	return $li_List;
}

//Social Media List
function SocialMediaList(){

	$datalist = Social_media::where('is_publish', '=', 1)->orderBy('id','ASC')->get();
	$li_List = '';
	foreach($datalist as $row){
		$id = $row->id;
		$url = $row->url;
		$target = $row->target;
		$social_icon = $row->social_icon;

		$li_List .= '<a href="'.$url.'" target="'.$target.'"><i class="'.$social_icon.'"></i></a>';
	}
	
	return $li_List;
}

function vipc(){

	$datalist = Tp_option::where('option_name', 'vipc')->get();

	$id = '';
	$option_value = '';
	foreach ($datalist as $row){
		$id = $row->id;
		$option_value = json_decode($row->option_value);
	}

	$data = array();
	if($id != ''){
		$data['bkey'] = $option_value->resetkey;
	}else{
		$data['bkey'] = 0;
	}
	return $data;
}

function getPurchaseData( $code ) {
	
	$header   = array();
	$header[] = 'Content-length: 0';
	$header[] = 'Content-type: application/json; charset=utf-8';
	$header[] = 'Authorization: bearer LkIHSQR0WsV9MADhIhiLPg4XmYqcu2TQ';
	$verify_url = 'https://api.envato.com/v3/market/author/sale/';
	$ch_verify = curl_init( $verify_url . '?code=' . $code );
	curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
	curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
	curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

	$cinit_verify_data = curl_exec( $ch_verify );
	curl_close( $ch_verify );

	if ($cinit_verify_data != ""){
		return json_decode($cinit_verify_data);  
	}else{
		return false;
	}
}

function verifyPurchase($code) {
	$verify_obj = getPurchaseData($code);

	$itemCode = 34899011; //Item Code for bShop
	$verifyItemCode = isset($verify_obj->item->id) ? $verify_obj->item->id : 0;

	if ((false === $verify_obj) || !is_object($verify_obj) || isset($verify_obj->error) || !isset($verify_obj->sold_at)){
		return 0;
	}else{
		//This code open for item approved
		if($itemCode == $verifyItemCode){
			return 1;
		}else{
			return 0;
		}
	}
}

//Get data for Language
function language(){

	$locale_language = glan();
	
	$data = Language::where('status', 1)->orderBy('language_name', 'ASC')->get();
	
	$base_url = url('/');
	
	$language = '';
	$selected_language = '';
	foreach ($data as $row){
		if($locale_language == $row['language_code']){
			$selected_language = $row['language_name'];
		}

		$language .= '<li><a class="dropdown-item" href="'.$base_url.'/lang/'.$row['language_code'].'">'.$row['language_name'].'</a></li>';
	}

	$languageList = '<div class="btn-group language-menu">
					<a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="bi bi-translate"></i>'.$selected_language.'
					</a>
					<ul class="dropdown-menu dropdown-menu-end">
						'.$language.'
					</ul>
				</div>';

	return $languageList;
}

function thumbnail($type){
	
	$datalist = array('width' => '', 'height' => '');
	$data = Media_setting::where('media_type', $type)->first();
	$datalist = array(
		'width' => $data['media_width'], 
		'height' => $data['media_height']
	);
	
	return $datalist;
}
	
function getReviews($item_id) {
	
	$sql = "SELECT COUNT(id) TotalReview, SUM(IFNULL(rating, 0)) TotalRating, 
	(SUM(IFNULL(rating, 0))/COUNT(id))*20 ReviewPercentage
	FROM reviews WHERE item_id = $item_id;";
	$datalist = DB::select(DB::raw($sql));

	return $datalist;
}
	
function getReviewsBySeller($seller_id) {
	
	$sql = "SELECT COUNT(a.id) TotalReview, SUM(IFNULL(a.rating, 0)) TotalRating, 
	(SUM(IFNULL(a.rating, 0))/COUNT(a.id))*20 ReviewPercentage
	FROM reviews a
	INNER JOIN products b ON a.item_id = b.id
	WHERE b.user_id = $seller_id;";
	$datalist = DB::select(DB::raw($sql));

	return $datalist;
}

function OrderCount($status_id) {
	if($status_id == 0){
		$count = Order_master::count();
	}else{
		$count = Order_master::where('order_status_id', '=', $status_id)->count();
	}
	
	return $count;
}

function OrderCountForSeller($status_id) {
	
	$seller_id = Auth::user()->id;
	
	if($status_id == 0){
		$count = Order_master::where('seller_id', '=', $seller_id)->count();
	}else{
		$count = Order_master::where('order_status_id', '=', $status_id)->where('seller_id', '=', $seller_id)->count();
	}
	
	return $count;
}

function getTax() {
	
	$results = Tax::offset(0)->limit(1)->get();

	$datalist = array('id' => '', 'title' => 'VAT', 'percentage' => 0, 'is_publish' => 2);
	foreach ($results as $row){
		$datalist['id'] = $row->id;
		$datalist['title'] = $row->title;
		if($row->is_publish == 2){
			$datalist['percentage'] = 0;
		}else{
			$datalist['percentage'] = $row->percentage;
		}
		$datalist['is_publish'] = $row->is_publish;
	}
	return $datalist;
}

function str_slug($str) {

	$str_slug = Str::slug($str, "-");
	
	return $str_slug;
}

function str_url($string) {
	$string = (string) $string;

	if ( 0 === strlen($string) ) {
		return '';
	}
	
	$str_slug = Str::slug($string, "+");
	
	return $str_slug;
}

function str_limit($str) {
	
	$str_limit = Str::limit($str, 30, '...');
	
	return $str_limit;
}

function sub_str($str, $start=0, $end=1) {
	
	$string = Str::substr($str, $start, $end);

	return $string;
}

function comma_remove($string) {

	$replaced = Str::remove(',', $string);

	return $replaced;
}

function esc($string){
	$string = (string) $string;

	if ( 0 === strlen($string) ) {
		return '';
	}
	
	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	
	return $string;
}

function gSellerSettings(){

	$datalist = Tp_option::where('option_name', 'seller_settings')->get();
	$id = '';
	$option_value = '';
	foreach ($datalist as $row){
		$id = $row->id;
		$option_value = json_decode($row->option_value);
	}

	$data = array();
	if($id != ''){
		$data['fee_withdrawal'] = $option_value->fee_withdrawal;
		$data['product_auto_publish'] = $option_value->product_auto_publish;
		$data['seller_auto_active'] = $option_value->seller_auto_active;
	}else{
		$data['fee_withdrawal'] = 0;
		$data['product_auto_publish'] = 0;
		$data['seller_auto_active'] = 0;
	}
	
	return $data;
}
