<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Media_option;
use Image;

class UploadController extends Controller
{
	public function FileUpload(Request $request){
		
		$destinationPath = public_path('media');
		$dateTime = date('dmYHis');
		
		$file = $request->file('FileName');

		//Display File Name
		$FileName = $dateTime.'-'.$file->getClientOriginalName();
		//$FileName = $file->getClientOriginalName();
		
		//get file extension
		$FileExt = $file->getClientOriginalExtension();
		
		//Convert uppercase to lowercase
		$Filetype = Str::lower($FileExt);
		
		//Display File Real Path
		$FileRealPath = $file->getRealPath();
		
		//Display File Size
		$FileSize = $file->getSize();
		
		//Original file name
		$OriginalFileName = basename($file->getClientOriginalName(), ".".$FileExt);
		
		//Display File Mime Type
		$FileMimeType = $file->getMimeType();
		
		if (file_exists(public_path('media/'.$FileName))) {
			unlink(public_path('media/'.$FileName));			
		}

		$msgList = array();
		
		//The file Check extension
		if (($Filetype == 'jpg') || ($Filetype == 'jpeg') || ($Filetype == 'png') || ($Filetype == 'gif') || ($Filetype == 'PNG') || ($Filetype == 'JPG') || ($Filetype == 'JPEG') || ($Filetype == 'ico') || ($Filetype == 'ICO') || ($Filetype == 'svg') || ($Filetype == 'SVG')) {
			if($file->move($destinationPath, $FileName)) {
				$data = array(
					'title' => $OriginalFileName,
					'alt_title' => $OriginalFileName,
					'thumbnail' => $FileName,
					'large_image' => $FileName,
					'option_value' => $FileSize
				);
				
				$response = Media_option::create($data)->id;
				
				if($response){
					$msgList["msgType"] = 'success';
					$msgList['msg'] = __('The file uploaded Successfully');
					$msgList["FileName"] = $FileName;
				}else{
					$msgList['msgType'] = 'error';
					$msgList['msg'] = __('Data insert failed');
					$msgList["FileName"] = '';
				}
				
			} else {
				$msgList["msgType"] = 'error';
				$msgList['msg'] = __('Sorry, there was an error uploading your file');
				$msgList["FileName"] = '';
			}
		} else {
			$msgList["msgType"] = 'error';
			$msgList['msg'] = __('Sorry only you can upload jpg, png and gif file type');
			$msgList["FileName"] = '';
		}
		
		return response()->json($msgList);
	}
	
	public function MediaUpload(Request $request){

		$destinationPath = public_path('media');
		$dateTime = date('dmYHis');
		
		$thumbnail = thumbnail($request['media_type']);
		$width = $thumbnail['width'];
		$height = $thumbnail['height'];

		$file = $request->file('FileName');

		//Display File Name
		$FileName = $dateTime.'-'.$file->getClientOriginalName();
		$ThumFileName = $dateTime.'-'.$width.'x'.$height.'-'.$file->getClientOriginalName();
		//$FileName = $file->getClientOriginalName();
		
		//get file extension
		$FileExt = $file->getClientOriginalExtension();
		
		//Convert uppercase to lowercase
		$Filetype = Str::lower($FileExt);
		
		//Display File Real Path
		$FileRealPath = $file->getRealPath();
		
		//Display File Size
		$FileSize = $file->getSize();
		
		//Original file name
		$OriginalFileName = basename($file->getClientOriginalName(), ".".$FileExt);
		
		//Display File Mime Type
		$FileMimeType = $file->getMimeType();
		
		if (file_exists(public_path('media/'.$FileName))) {
			unlink(public_path('media/'.$FileName));
		}

		$msgList = array();
		
		//The file Check extension
		if (($Filetype == 'jpg') || ($Filetype == 'JPG') || ($Filetype == 'jpeg') || ($Filetype == 'JPEG') || ($Filetype == 'png') || ($Filetype == 'PNG') || ($Filetype == 'gif') || ($Filetype == 'ico') || ($Filetype == 'ICO') || ($Filetype == 'svg') || ($Filetype == 'SVG')) {
			
			if(($Filetype == 'gif') || ($Filetype == 'ico') || ($Filetype == 'ICO') || ($Filetype == 'svg') || ($Filetype == 'SVG')){
				$ThumFileName = $FileName;
			}else{
				$img = Image::make($FileRealPath);
				$img->resize($width, $height, function ($constraint) {
					$constraint->aspectRatio();
				})->save($destinationPath.'/'.$ThumFileName);
			}
			
			if($file->move($destinationPath, $FileName)) {

				$data = array(
					'title' => $OriginalFileName,
					'alt_title' => $OriginalFileName,
					'thumbnail' => $ThumFileName,
					'large_image' => $FileName,
					'option_value' => $FileSize
				);
				
				$response = Media_option::create($data)->id;
				
				if($response){
					$msgList["msgType"] = 'success';
					$msgList['msg'] = __('The file uploaded Successfully');
					$msgList["thumbnail"] = $ThumFileName;
					$msgList["large_image"] = $FileName;
					$msgList["id"] = $response;
				}else{
					$msgList['msgType'] = 'error';
					$msgList['msg'] = __('Data insert failed');
					$msgList["thumbnail"] = '';
					$msgList["large_image"] = '';
					$msgList["id"] = '';
				}
				
			} else {
				$msgList["msgType"] = 'error';
				$msgList['msg'] = __('Sorry, there was an error uploading your file');
				$msgList["thumbnail"] = '';
				$msgList["large_image"] = '';
				$msgList["id"] = '';
			}
		} else {
			$msgList["msgType"] = 'error';
			$msgList['msg'] = __('Sorry only you can upload jpg, png and gif file type');
			$msgList["thumbnail"] = '';
			$msgList["large_image"] = '';
			$msgList["id"] = '';
		}
		
		return response()->json($msgList);
	}
}
