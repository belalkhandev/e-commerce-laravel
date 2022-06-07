<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Language;
use App\Models\Lankeyvalue;
use File;
use App;

class LanguagesController extends Controller {
	
    //languages page load
    public function getLanguagePageLoad(){
		
		$datalist = Language::paginate(10);

        return view('backend.languages', compact('datalist'));
    }
	
	//Get data for languages Pagination
	public function getLanguagesTableData(Request $request){

		$search = $request->search;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = Language::where(function ($query) use ($search){
						$query->where('language_code', 'like', '%'.$search.'%')
							->orWhere('language_name', 'like', '%'.$search.'%');
					})
					->paginate(10);
			}else{
				$datalist = Language::paginate(10);
			}

			return view('backend.partials.languages_table', compact('datalist'))->render();
		}
	}

	//Save data for Languages
    public function saveLanguagesData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$language_code = $request->input('language_code');
		$old_language_code = $request->input('old_language_code');
		$language_name = $request->input('language_name');
		
		$isrtl = $request->input('is_rtl');
        if ($isrtl == 'true' || $isrtl == 'on') {
            $is_rtl = 1;
        } else {
            $is_rtl = 0;
        }
		
		$languagedefault = $request->input('language_default');
        if ($languagedefault == 'true' || $languagedefault == 'on') {
            $language_default = 1;
        } else {
            $language_default = 0;
        }
		
		$is_status = $request->input('status');
        if ($is_status == 'true' || $is_status == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }
		
		$validator_array = array(
			'language_code' => $request->input('language_code'),
			'language_name' => $request->input('language_name')
		);
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'language_code' => 'required|max:30|unique:languages,language_code' . $rId,
			'language_name' => 'required|max:100|unique:languages,language_name' . $rId
		]);

		$errors = $validator->errors();

		if($errors->has('language_code')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_code');
			return response()->json($res);
		}
		
		if($errors->has('language_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_name');
			return response()->json($res);
		}

		$data = array(
			'language_code' => $language_code,
			'language_name' => $language_name,
			'language_default' => $language_default,
			'is_rtl' => $is_rtl,
			'status' => $status
		);
		
		if($language_default == 1){
			DB::update('update languages set language_default = "0"');
		}
		
		if($id ==''){
			$response = Language::create($data);
			if($response){
				self::LanguageKeyValueInsert($language_code);
				
				self::saveJSONFile($language_code);
				
				if($language_default == 1){
					self::saveDefaultLanguageFile($language_code);
				}
				
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');

			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Language::where('id', $id)->update($data);
			if($response){
				
				DB::update('update lankeyvalues set language_code = "'.$language_code.'" where language_code = ?', [$old_language_code]);
				
				$count = Lankeyvalue::where('language_code','=', $language_code)->count();
				if($count == 0){
					self::LanguageKeyValueInsert($language_code);
					self::saveJSONFile($language_code);
				}
				
				if($language_default == 1){
					self::saveDefaultLanguageFile($language_code);
				}
				
				$defaultCount = Language::where('language_default','=','1')->count();
				if($defaultCount == 0){
					self::saveDefaultLanguageFile('en');
					DB::update('update languages set language_default = 1 where language_code = ?', ['en']);
				}
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Get data for Language by id
    public function getLanguageById(Request $request){

		$id = $request->RecordId;
        $data= Language::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Delete data for Language
	public function deleteLanguage(Request $request){
		
		$res = array();

		$id = $request->RecordId;
		$language_code = $request->language_code;
		
		if($id != ''){
			Lankeyvalue::where('language_code', $language_code)->delete();	
			$response = Language::where('id', $id)->delete();
			if($response){
				
				$locale = session()->get('locale');
				if($locale == $language_code){
					$count = Language::where('language_default','=','1')->count();
					if($count == 0){
						self::saveDefaultLanguageFile('en');
						DB::update('update languages set language_default = 1 where language_code = ?', ['en']);
					}
					
					App::setLocale('en');
					session()->put('locale', 'en');
				}
				
				$defaultCount = Language::where('language_default','=','1')->count();
				if($defaultCount == 0){
					self::saveDefaultLanguageFile('en');
					DB::update('update languages set language_default = 1 where language_code = ?', ['en']);
				}
				
				self::deleteJSONFile($language_code);
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}
	
    //languages Keywords page load
    public function getLanguageKeywordsPageLoad(Request $request){
		
		$language_code = $request->language_code;
		
		$languageslist = DB::table('languages')->orderBy('language_name', 'asc')->get();
		
		$datalist = Lankeyvalue::where('language_code', $language_code)->paginate(50);

        return view('backend.language-keywords', compact('datalist', 'languageslist'));
    }
	
	//Get data for Language Keywords Pagination
	public function getLanguageKeywordsTableData(Request $request){

		$search = $request->search;
		$language_code = $request->language_code;
		
		if($request->ajax()){

			if($search != ''){
				$datalist = Lankeyvalue::where('language_code', $language_code)
					->where(function ($query) use ($search){
						$query->where('language_key', 'like', '%'.$search.'%')
							->orWhere('language_value', 'like', '%'.$search.'%');
					})
					->paginate(50);
			}else{
				$datalist = Lankeyvalue::where('language_code', $language_code)->paginate(50);
			}

			return view('backend.partials.languages_keywords_table', compact('datalist'))->render();
		}
	}
	
	//Insert for Langauge key Value
	public function LanguageKeyValueInsert($language_code){
		
		$currentDataTime = Carbon::now();
  
		DB::insert("INSERT INTO lankeyvalues(language_code, language_key, language_value, created_at, updated_at) 
		SELECT '".$language_code."', language_key, language_value, '".$currentDataTime."', '".$currentDataTime."'
		FROM lankeyvalues WHERE language_code = 'en'");
	}
	
	//Save data for Language Keywords
    public function saveLanguageKeywordsData(Request $request){
		$res = array();

		$id = $request->input('RecordId');
		$language_code = $request->input('language_code');
		$language_key = $request->input('language_key');
		$language_value = $request->input('language_value');

 		$validator_array = array(
			'language_key' => $request->input('language_key'),
			'language_value' => $request->input('language_value'),
			'language_code' => $request->input('language_code')
		);
		
		$validator = Validator::make($validator_array, [
			'language_key' => 'required',
			'language_value' => 'required',
			'language_code' => 'required'
		]);
		
		$errors = $validator->errors();	
		
		if($errors->has('language_code')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_code');
			return response()->json($res);
		}
		
		if($errors->has('language_key')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_key');
			return response()->json($res);
		}
		
		if($errors->has('language_value')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_value');
			return response()->json($res);
		}

		$data = array(
			'language_code' => $language_code,
			'language_key' => $language_key,
			'language_value' => $language_value
		);
		
		if($id ==''){
			$response = Lankeyvalue::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');

				self::saveJSONFile($language_code);
				
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Lankeyvalue::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				self::saveJSONFile($language_code);
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }	
	
	//Get data for Language keywords by id
    public function getLanguageKeywordsById(Request $request){

		$id = $request->RecordId;
        $data= Lankeyvalue::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Delete data for Language Keywords
	public function deleteLanguageKeywords(Request $request){
		
		$res = array();

		$id = $request->RecordId;
		$language_code = $request->language_code;
		
		if($id != ''){
			$response = Lankeyvalue::where('id', $id)->delete();
			if($response){
				
				self::saveJSONFile($language_code);
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}	
	
    private function saveJSONFile($language_code){
        
		if(File::exists(base_path('resources/lang/'.$language_code.'.json'))){
			File::delete(base_path('resources/lang/'.$language_code.'.json'));
        }
		
		$data = array();
		$lanList = Lankeyvalue::where('language_code', $language_code)->get();
		foreach ($lanList as $row){
			$data[$row['language_key']] = $row['language_value'];
		}

        ksort($data);

        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        file_put_contents(base_path('resources/lang/'.$language_code.'.json'), stripslashes($jsonData));
    }
	
    private function deleteJSONFile($language_code){
        
		if(File::exists(base_path('resources/lang/'.$language_code.'.json'))){
			File::delete(base_path('resources/lang/'.$language_code.'.json'));
        }
    }
	
    private function saveDefaultLanguageFile($language_code){

		if(File::exists(base_path('resources/lang/custom/locales.php'))){
			File::delete(base_path('resources/lang/custom/locales.php'));
        }

		$data = '<?php $lan = ["lan" => "'.$language_code.'"];';

        file_put_contents(base_path('resources/lang/custom/locales.php'), $data);
    }	
}
