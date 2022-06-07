<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tp_option;
use App\Models\Media_option;

class ThemeOptionsController extends Controller
{
    //Theme Options page load
    public function getThemeOptionsPageLoad() {

		$results = Tp_option::where('option_name', 'theme_logo')->get();

		$id = '';
		$favicon = '';
		$front_logo = '';
		$back_logo = '';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['favicon'] = $dataObj->favicon;
			$data['front_logo'] = $dataObj->front_logo;
			$data['back_logo'] = $dataObj->back_logo;
		}else{
			$data['favicon'] = '';
			$data['front_logo'] = '';
			$data['back_logo'] = '';
		}
		
		$datalist = $data;
		
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
        return view('backend.theme-options', compact('datalist', 'media_datalist'));
    }
	
	//Save data for Theme Logo
    public function saveThemeLogo(Request $request){
		$res = array();
		
		$favicon = $request->input('favicon');
		$front_logo = $request->input('front_logo');
		$back_logo = $request->input('back_logo');
		
		$validator_array = array(
			'favicon' => $request->input('favicon'),
			'front_logo' => $request->input('front_logo'),
			'back_logo' => $request->input('back_logo')
		);
		
		$validator = Validator::make($validator_array, [
			'favicon' => 'required',
			'front_logo' => 'required',
			'back_logo' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('favicon')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('favicon');
			return response()->json($res);
		}
		
		if($errors->has('front_logo')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('front_logo');
			return response()->json($res);
		}
		
		if($errors->has('back_logo')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('back_logo');
			return response()->json($res);
		}
		
		$option = array(
			'favicon' => $favicon,
			'front_logo' => $front_logo,
			'back_logo' => $back_logo
		);
		
		$data = array(
			'option_name' => 'theme_logo',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'theme_logo')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }

    //Theme Options Header page load
    public function getThemeOptionsHeaderPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'theme_option_header')->get();

		$id = '';
		$address = '';
		$phone = '';
		$is_publish = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['address'] = $dataObj->address;
			$data['phone'] = $dataObj->phone;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['address'] = '';
			$data['phone'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;

        return view('backend.theme-options-header', compact('datalist', 'statuslist'));
    }
	
	//Save data for Theme Options Header
    public function saveThemeOptionsHeader(Request $request){
		$res = array();

		$address = $request->input('address');
		$phone = $request->input('phone');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'address' => $address,
			'phone' => $phone,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'theme_option_header',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'theme_option_header')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }	

    //Language Switcher page load
    public function getLanguageSwitcher() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'language_switcher')->get();

		$id = '';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['is_language_switcher'] = $dataObj->is_language_switcher;
		}else{
			$data['is_language_switcher'] = '2';
		}
		
		$datalist = $data;

        return view('backend.language-switcher', compact('datalist', 'statuslist'));
    }
	
	//Save data for Language Switcher
    public function saveLanguageSwitcher(Request $request){
		$res = array();

		$is_language_switcher = $request->input('is_language_switcher');
		
		$option = array(
			'is_language_switcher' => $is_language_switcher
		);
		
		$data = array(
			'option_name' => 'language_switcher',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'language_switcher')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
    //Theme Options Footer page load
    public function getThemeOptionsFooterPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'theme_option_footer')->get();

		$id = '';
		$address = '';
		$email = '';
		$phone = '';
		$is_publish_contact = '2';
		$copyright = '';
		$is_publish_copyright = '2';
		$payment_gateway_icon = '';
		$is_publish_payment = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['address'] = $dataObj->address;
			$data['email'] = $dataObj->email;
			$data['phone'] = $dataObj->phone;
			$data['is_publish_contact'] = $dataObj->is_publish_contact;
			$data['copyright'] = $dataObj->copyright;
			$data['is_publish_copyright'] = $dataObj->is_publish_copyright;
			$data['payment_gateway_icon'] = $dataObj->payment_gateway_icon;
			$data['is_publish_payment'] = $dataObj->is_publish_payment;
		}else{
			$data['address'] = '';
			$data['email'] = '';
			$data['phone'] = '';
			$data['is_publish_contact'] = '2';
			$data['copyright'] = '';
			$data['is_publish_copyright'] = '2';
			$data['payment_gateway_icon'] = '';
			$data['is_publish_payment'] = '2';
		}
		
		$datalist = $data;
		
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
        return view('backend.theme-options-footer', compact('datalist', 'media_datalist', 'statuslist'));
    }

	//Save data for Theme Options Footer
    public function saveThemeOptionsFooter(Request $request){
		$res = array();

		$address = $request->input('address');
		$phone = $request->input('phone');
		$email = $request->input('email');
		$is_publish_contact = $request->input('is_publish_contact');
		$copyright = $request->input('copyright');
		$is_publish_copyright = $request->input('is_publish_copyright');
		$payment_gateway_icon = $request->input('payment_gateway_icon');
		$is_publish_payment = $request->input('is_publish_payment');
		
		$option = array(
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'is_publish_contact' => $is_publish_contact,
			'copyright' => $copyright,
			'is_publish_copyright' => $is_publish_copyright,
			'payment_gateway_icon' => $payment_gateway_icon,
			'is_publish_payment' => $is_publish_payment
		);
		
		$data = array(
			'option_name' => 'theme_option_footer',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'theme_option_footer')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }	
	
    //Custom CSS page load
    public function getCustomCSSPageLoad() {
		$results = Tp_option::where('option_name', 'custom_css')->get();

		$id = '';
		$option_value = '';
		foreach ($results as $row){
			$id = $row->id;
			$option_value = $row->option_value;
		}

		$data = array();
		if($id != ''){
			$data['custom_css'] = $option_value;
		}else{
			$data['custom_css'] = '';
		}
		
		$datalist = $data;

        return view('backend.custom-css', compact('datalist'));
    }
	
	//Save data for Custom CSS
    public function saveCustomCSS(Request $request){
		$res = array();
		
		$custom_css = $request->input('custom_css');
		
		$data = array(
			'option_name' => 'custom_css',
			'option_value' => $custom_css
		);

		$gData = Tp_option::where('option_name', 'custom_css')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }

    //Custom JS page load
    public function getCustomJSPageLoad() {
		$results = Tp_option::where('option_name', 'custom_js')->get();

		$id = '';
		$option_value = '';
		foreach ($results as $row){
			$id = $row->id;
			$option_value = $row->option_value;
		}

		$data = array();
		if($id != ''){
			$data['custom_js'] = $option_value;
		}else{
			$data['custom_js'] = '';
		}
		
		$datalist = $data;

        return view('backend.custom-js', compact('datalist'));
    }
	
	//Save data for Custom JS
    public function saveCustomJS(Request $request){
		$res = array();
		
		$custom_js = $request->input('custom_js');
		
		$data = array(
			'option_name' => 'custom_js',
			'option_value' => $custom_js
		);

		$gData = Tp_option::where('option_name', 'custom_js')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
    //Theme Options SEO page load
    public function getThemeOptionsSEOPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'theme_option_seo')->get();

		$id = '';
		$og_title = '';
		$og_image = '';
		$og_description = '';
		$og_keywords = '';
		$is_publish = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['og_title'] = $dataObj->og_title;
			$data['og_image'] = $dataObj->og_image;
			$data['og_description'] = $dataObj->og_description;
			$data['og_keywords'] = $dataObj->og_keywords;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['og_title'] = '';
			$data['og_image'] = '';
			$data['og_description'] = '';
			$data['og_keywords'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
        return view('backend.theme-options-seo', compact('datalist', 'media_datalist', 'statuslist'));
    }

	//Save data for Theme Options SEO
    public function saveThemeOptionsSEO(Request $request){
		$res = array();

		$og_title = $request->input('og_title');
		$og_image = $request->input('og_image');
		$og_description = $request->input('og_description');
		$og_keywords = $request->input('og_keywords');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'og_title' => $og_title,
			'og_image' => $og_image,
			'og_description' => $og_description,
			'og_keywords' => $og_keywords,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'theme_option_seo',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'theme_option_seo')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
    //Theme Options Color page load
    public function getThemeOptionsColorPageLoad() {

		$results = Tp_option::where('option_name', 'theme_color')->get();

		$id = '';
		$theme_color = '';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['theme_color'] = $dataObj->theme_color;
			$data['menu_background_color'] = $dataObj->menu_background_color;
			$data['backend_theme_color'] = $dataObj->backend_theme_color;
		}else{
			$data['theme_color'] = '';
			$data['backend_theme_color'] = '';
		}
		
		$datalist = $data;

        return view('backend.theme-options-color', compact('datalist'));
    }
	
	//Save data for Theme Options Color
    public function saveThemeOptionsColor(Request $request){
		$res = array();
		
		$theme_color = $request->input('theme_color');
		$menu_background_color = $request->input('menu_background_color');
		$backend_theme_color = $request->input('backend_theme_color');
		
		$validator_array = array(
			'theme_color' => $request->input('theme_color'),
			'menu_background_color' => $request->input('menu_background_color'),
			'backend_theme_color' => $request->input('backend_theme_color')
		);
		
		$validator = Validator::make($validator_array, [
			'theme_color' => 'required',
			'menu_background_color' => 'required',
			'backend_theme_color' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('theme_color')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('theme_color');
			return response()->json($res);
		}
		
		if($errors->has('menu_background_color')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('menu_background_color');
			return response()->json($res);
		}
		if($errors->has('backend_theme_color')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('backend_theme_color');
			return response()->json($res);
		}
		
		$option = array(
			'theme_color' => $theme_color,
			'menu_background_color' => $menu_background_color,
			'backend_theme_color' => $backend_theme_color
		);
		
		$data = array(
			'option_name' => 'theme_color',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'theme_color')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }

    //Theme Options Facebook page load
    public function getThemeOptionsFacebookPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'facebook')->get();

		$id = '';
		$fb_app_id = '';
		$is_publish = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['fb_app_id'] = $dataObj->fb_app_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['fb_app_id'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.theme-options-facebook', compact('datalist', 'statuslist'));
    }
	
	//Save data for Theme Options Facebook
    public function saveThemeOptionsFacebook(Request $request){
		$res = array();

		$fb_app_id = $request->input('fb_app_id');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'fb_app_id' => $fb_app_id,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'facebook',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'facebook')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }	

    //Theme Options Facebook Pixel page load
    public function getThemeOptionsFacebookPixelLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'facebook-pixel')->get();

		$id = '';
		$fb_pixel_id = '';
		$is_publish = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['fb_pixel_id'] = $dataObj->fb_pixel_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['fb_pixel_id'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.theme-options-facebook-pixel', compact('datalist', 'statuslist'));
    }
	
	//Save data for Theme Options Facebook Pixel
    public function saveThemeOptionsFacebookPixel(Request $request){
		$res = array();

		$fb_pixel_id = $request->input('fb_pixel_id');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'fb_pixel_id' => $fb_pixel_id,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'facebook-pixel',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'facebook-pixel')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
    //Theme Options Twitter page load
    public function getThemeOptionsTwitterPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'twitter')->get();

		$id = '';
		$twitter_id = '';
		$is_publish = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['twitter_id'] = $dataObj->twitter_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['twitter_id'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.theme-options-twitter', compact('datalist', 'statuslist'));
    }
	
	//Save data for Theme Options Twitter
    public function saveThemeOptionsTwitter(Request $request){
		$res = array();

		$twitter_id = $request->input('twitter_id');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'twitter_id' => $twitter_id,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'twitter',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'twitter')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
    //Google Analytics page load
    public function getGoogleAnalytics() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'google_analytics')->get();

		$id = '';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['tracking_id'] = $dataObj->tracking_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['tracking_id'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.google-analytics', compact('datalist', 'statuslist'));
    }

	//Save data for Google Analytics
    public function saveGoogleAnalytics(Request $request){
		$res = array();

		$tracking_id = $request->input('tracking_id');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'tracking_id' => $tracking_id,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'google_analytics',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'google_analytics')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
    //Google Tag Manager page load
    public function getGoogleTagManager() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'google_tag_manager')->get();

		$id = '';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['google_tag_manager_id'] = $dataObj->google_tag_manager_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['google_tag_manager_id'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.google-tag-manager', compact('datalist', 'statuslist'));
    }
	
	//Save data for Google Tag Manager
    public function saveGoogleTagManager(Request $request){
		$res = array();

		$google_tag_manager_id = $request->input('google_tag_manager_id');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'google_tag_manager_id' => $google_tag_manager_id,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'google_tag_manager',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'google_tag_manager')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }

    //Theme Options Whatsapp page load
    public function getThemeOptionsWhatsappPageLoad() {
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'whatsapp')->get();

		$id = '';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
			
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['whatsapp_id'] = $dataObj->whatsapp_id;
			$data['whatsapp_text'] = $dataObj->whatsapp_text;
			$data['position'] = $dataObj->position;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['whatsapp_id'] = '';
			$data['whatsapp_text'] = '';
			$data['position'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.theme-options-whatsapp', compact('datalist', 'statuslist'));
    }
	
	//Save data for Theme Options Whatsapp
    public function saveThemeOptionsWhatsapp(Request $request){
		$res = array();

		$whatsapp_id = $request->input('whatsapp_id');
		$whatsapp_text = $request->input('whatsapp_text');
		$position = $request->input('position');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'whatsapp_id' => $whatsapp_id,
			'whatsapp_text' => $whatsapp_text,
			'position' => $position,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'whatsapp',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'whatsapp')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }

    //Theme Options Trending
    public function getThemeOptionsTrending() {
		
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
		$statuslist = DB::table('tp_status')->orderBy('id', 'asc')->get();
		
		$results = Tp_option::where('option_name', 'trending')->get();

		$id = '';
		$title = '';
		$short_desc = '';
		$url = '';
		$image = '';
		$is_publish = '2';
		foreach ($results as $row){
			$id = $row->id;
		}

		$data = array();
		if($id != ''){
		
			$sData = json_decode($results);
			$dataObj = json_decode($sData[0]->option_value);
			
			$data['title'] = $dataObj->title;
			$data['short_desc'] = $dataObj->short_desc;
			$data['url'] = $dataObj->url;
			$data['image'] = $dataObj->image;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['title'] = '';
			$data['short_desc'] = '';
			$data['url'] = '';
			$data['image'] = '';
			$data['is_publish'] = '2';
		}
		
		$datalist = $data;
		
        return view('backend.trending', compact('media_datalist', 'datalist', 'statuslist'));
    }
	
	//Save data for Theme Options Trending
    public function saveThemeOptionsTrending(Request $request){
		$res = array();

		$title = $request->input('title');
		$short_desc = $request->input('short_desc');
		$url = $request->input('url');
		$image = $request->input('image');
		$is_publish = $request->input('is_publish');
		
		$option = array(
			'title' => $title,
			'short_desc' => $short_desc,
			'url' => $url,
			'image' => $image,
			'is_publish' => $is_publish
		);
		
		$data = array(
			'option_name' => 'trending',
			'option_value' => json_encode($option)
		);

		$gData = Tp_option::where('option_name', 'trending')->get();
		$id = '';
		foreach ($gData as $row){
			$id = $row['id'];
		}
		
		if($id == ''){
			$response = Tp_option::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Tp_option::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }	
	
}
