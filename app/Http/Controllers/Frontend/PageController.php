<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    //get Page
    public function getPage($id, $title){
		
		$datalist = Page::where('id', '=', $id)->where('is_publish', '=', 1)->get();
		$data = array(
			'title' => '', 
			'slug' => '', 
			'content' => '', 
			'thumbnail' => '', 
			'og_title' => '', 
			'og_image' => '', 
			'og_description' => '', 
			'og_keywords' => ''
		);
		foreach ($datalist as $row){
			$data['title'] = $row->title;
			$data['slug'] = $row->slug;
			$data['content'] = $row->content;
			$data['thumbnail'] = $row->thumbnail;
			$data['og_title'] = $row->og_title;
			$data['og_image'] = $row->og_image;
			$data['og_description'] = $row->og_description;
			$data['og_keywords'] = $row->og_keywords;
		}
		
        return view('frontend.page', compact('data'));
    }
}
