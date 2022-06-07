<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Models\Media_option;
use App\Models\Menu;
use App\Models\Menu_parent;
use App\Models\Mega_menu;
use App\Models\Menu_child;
use App\Models\Page;
use App\Models\Pro_category;
use App\Models\Product;
use App\Models\Brand;

class MenuBuilderController extends Controller
{
    //Menu Builder page load
    public function getMenuBuilderPageLoad($lan, $id){

		$main_menu = Menu::where('id', $id)->first();
		
		$page_datalist = Page::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);

		$brand_datalist = Brand::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
		
		$product_category_datalist = Pro_category::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
		$product_datalist = Product::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
		
		$menulist = self::makeParentMenu($id);
		$media_datalist = Media_option::orderBy('id','desc')->paginate(28);
		
        return view('backend.menu-builder', compact('main_menu', 'page_datalist', 'brand_datalist', 'menulist', 'media_datalist', 'product_category_datalist', 'product_datalist'));
    }
	
	//Make Parent Menu
	public function makeParentMenu($id){
		
		$datalist = Menu_parent::where('menu_id', $id)->orderBy('sort_order','ASC')->get();
		$menulist = '';
		$custom_url = '';
		$MegaDropdownMenuList = '';
		
		foreach($datalist as $row){
			$menu_id = $id;
			$menu_parent_id = $row->id;
			
			$MenuChildCount = Menu_child::where('menu_parent_id', $menu_parent_id)->count();
			$dnone = '';
			if($MenuChildCount>0){
				$dnone = 'dnone';
			}else{
				$dnone = '';
			}
			
			$parent_menu_item_class = 'parent_menu_item';
			$menu_item_id = '_parent_'.$row->id;
			$submitformid = 'parent_'.$row->id.'_'.$menu_id;
			$data_childmenu_id = 'data-parentmenu-id="'.$row->id.'"';
			$MainMenuParentMenuChildMenuId = 'parent_'.$row->menu_id.'_'.$row->id.'_0';
			
			$none = ($row->child_menu_type == 'none') ?  "checked" : "checked";
			$dropdown = ($row->child_menu_type == 'dropdown') ?  "checked" : "";
			$mega_menu = ($row->child_menu_type == 'mega_menu') ?  "checked" : "";
			$mega_menu_settings_dnone = ($row->child_menu_type == 'mega_menu') ?  "" : "dnone";
			
			$full_width = ($row->width_type == 'full_width') ?  "checked" : "checked";
			$fixed_width = ($row->width_type == 'fixed_width') ?  "checked" : "";
			$width_type_dnone = ($row->width_type == 'fixed_width') ?  "" : "dnone";

			$self = ($row->target_window == '_self') ?  "checked" : "checked";
			$blank = ($row->target_window == '_blank') ?  "checked" : "";

			if($row->child_menu_type == 'mega_menu'){
				$MegaDropdownMenuList = self::makeMegaMenu($menu_id, $menu_parent_id);
			}elseif($row->child_menu_type == 'dropdown'){
				$MegaDropdownMenuList = self::makeDropdownMenu($menu_id, $menu_parent_id);
			}else{
				$MegaDropdownMenuList = '';
			}
			
			if($row->menu_type == 'custom_link'){
				$item_id = $row->id;
				$custom_url = '<div class="form-group">
								<label for="custom_url'.$menu_item_id.'">'. __('URL') .'<span class="red">*</span></label>
								<input type="text" name="custom_url'.$menu_item_id.'" id="custom_url'.$menu_item_id.'" class="form-control" placeholder="https://" value="'.$row->custom_url.'" required>
							</div>';
			}else{
				$custom_url = '';
				$item_id = $row->item_id;
			}
			
			//For Parent Menu (menu_id, menu_type, item_id, lan, Primary = id (custom_link == $row->id), menu_parent_child_id, menu_parent_child)
			$maintypeitemlan = 'data-maintypeitemlan="'.$menu_id.'-'.$row->menu_type.'-'.$item_id.'-'.$row->lan.'-'.$row->id.'-'.$row->id.'-parent"';
			
			//menu_type, item_id, lan (custom_link == $row->id)
			$TypeItemLan = 'data-typeitemlan="'.$row->menu_type.'-'.$item_id.'-'.$row->lan.'"';
			
			$menulist .= '<li '.$maintypeitemlan.' '.$TypeItemLan.' '.$data_childmenu_id.' id="menu_item'.$menu_item_id.'" class="menu-item '.$parent_menu_item_class.'">
						<div class="menu-item-bar">
							<div class="menu-item-handle">
								<span class="item-title">
									<span class="menu-item-title">'.$row->item_label.'</span>
								</span>
								<span class="item-controls">
									<span class="item-type">'.$row->menu_type.'</span>
									<a href="javascript:void(0);" class="collapsed item-edit" data-toggle="collapse" data-target="#collapse_menu_item'.$menu_item_id.'" aria-expanded="true" aria-controls="collapse_menu_item'.$menu_item_id.'"><i class="fa fa-angle-down"></i></a>
								</span>
							</div>
						</div>
						<div id="collapse_menu_item'.$menu_item_id.'" class="collapse" aria-labelledby="menu_item'.$menu_item_id.'" data-parent="#menu_management_accordion">
							<div class="menu-item-settings">
								<form id="MenuSettingsSubmitForm_id_'.$submitformid.'">
									'.$custom_url.'
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="item_label'.$menu_item_id.'">'. __('Navigation Label') .'<span class="red">*</span></label>
												<input type="text" name="item_label'.$menu_item_id.'" id="item_label'.$menu_item_id.'" class="form-control" value="'.$row->item_label.'" required>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label>'. __('Target Window') .'</label>
												<ul class="checkboxlist child_menu_type_list w-100">
													<li><label class="checkbox-title"><input type="radio" name="target_window'.$menu_item_id.'" value="_self" '.$self.'>Self</label></li>
													<li><label class="checkbox-title"><input type="radio" name="target_window'.$menu_item_id.'" value="_blank" '.$blank.'>Blank</label></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="css_class'.$menu_item_id.'">'. __('CSS Class (Optional)') .'</label>
												<input type="text" name="css_class'.$menu_item_id.'" id="css_class'.$menu_item_id.'" class="form-control" value="'.$row->css_class.'">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group '.$dnone.'">
												<label>'. __('Select menu option') .'</label>
												<ul class="checkboxlist child_menu_type_list w-100">
													<li><label class="checkbox-title"><input type="radio" id="none-'.$menu_item_id.'" class="child_menu_type" name="child_menu_type'.$menu_item_id.'" value="none" '.$none.'>'. __('None') .'</label></li>
													<li><label class="checkbox-title"><input type="radio" id="dropdown-'.$menu_item_id.'" class="child_menu_type" name="child_menu_type'.$menu_item_id.'" value="dropdown" '.$dropdown.'>'. __('Dropdown') .'</label></li>
													<li><label class="checkbox-title"><input type="radio" id="mega_menu-'.$menu_item_id.'" class="child_menu_type" name="child_menu_type'.$menu_item_id.'" value="mega_menu" '.$mega_menu.'>'. __('Mega Menu') .'</label></li>
												</ul>
											</div>
										</div>
									</div>
									<div id="mega_menu_settings_hide_show'.$menu_item_id.'" class="for-mega-menu '.$mega_menu_settings_dnone.' '.$dnone.'">
										<div class="row">
											<div class="col-lg-4">
												<div class="form-group mega-column">
													<label for="column'.$menu_item_id.'">Choose column (1 to 6)</label>
													<input type="number" name="column'.$menu_item_id.'" id="column'.$menu_item_id.'" class="form-control" value="'.$row->column.'" min="1" max="6">
												</div>
											</div>
											<div class="col-lg-5">
												<div class="form-group">
													<label>'. __('Select width') .'</label>
													<ul class="checkboxlist child_menu_type_list w-100">
														<li><label class="checkbox-title"><input type="radio" id="full_width-'.$menu_item_id.'" class="width_type" name="width_type'.$menu_item_id.'" value="full_width" '.$full_width.'>'. __('Full Width') .'</label></li>
														<li><label class="checkbox-title"><input type="radio" id="fixed_width-'.$menu_item_id.'" class="width_type" name="width_type'.$menu_item_id.'" value="fixed_width" '.$fixed_width.'>'. __('Fixed Width') .'</label></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3">
												<div id="width_type_hide_show'.$menu_item_id.'" class="form-group mega-column '.$width_type_dnone.'">
													<label for="width'.$menu_item_id.'">Width</label>
													<input type="number" name="width'.$menu_item_id.'" id="width'.$menu_item_id.'" class="form-control" value="'.$row->width.'">
												</div>
											</div>
										</div>
									</div>
									<div class="bottom-controls">
										<input type="hidden" name="lan'.$menu_item_id.'" id="lan'.$menu_item_id.'" value="'.$row->lan.'" />
										<input type="hidden" name="menu_type'.$menu_item_id.'" id="menu_type'.$menu_item_id.'" value="'.$row->menu_type.'" />
										<button type="submit" data-submitformid="'.$submitformid.'" class="btn blue-btn mr-10 MenuSettingsSubmitForm">'. __('Save') .'</button>
										<button type="submit" data-parentchildid="'.$MainMenuParentMenuChildMenuId.'" class="btn danger-btn DeleteParentChildMenu">'. __('Delete') .'</button>
									</div>
								</form>
							</div>
						</div>
						'.$MegaDropdownMenuList.'
					</li>';
		}
		
		return $menulist;
	}
	
	//Make Child Menu
	public function makeDropdownMenu($menu_id, $menu_parent_id){
	
		$datalist = Menu_child::where('menu_id', '=', $menu_id)
					->where('menu_parent_id', '=', $menu_parent_id)
					->orderBy('sort_order','ASC')->get();
		
		$menulist = '';
		$custom_url = '';
		
		//menu_id, menu_parent_id, mega_menu_id=0
		$mega_menu_id = 0;
		$MainParentMega = 'data-mainparentmega-id="'.$menu_id.'-'.$menu_parent_id.'-'.$mega_menu_id.'"';
		
		foreach($datalist as $row){
			$child_menu_item_class = 'child_menu_item';
			
			$menu_item_id = '_child_'.$row->id;
			$submitformid = 'child_'.$row->id.'_'.$row->menu_parent_id;
			$data_childmenu_id = 'data-parentmenu-id="'.$row->id.'"';
			$MainMenuParentMenuChildMenuId = 'child_'.$row->menu_id.'_'.$row->menu_parent_id.'_'.$row->id;

			$self = ($row->target_window == '_self') ?  "checked" : "checked";
			$blank = ($row->target_window == '_blank') ?  "checked" : "";
			
			if($row->menu_type == 'custom_link'){
				$custom_url = '<div class="form-group">
								<label for="custom_url'.$menu_item_id.'">'. __('URL') .'<span class="red">*</span></label>
								<input type="text" name="custom_url'.$menu_item_id.'" id="custom_url'.$menu_item_id.'" class="form-control" placeholder="https://" value="'.$row->custom_url.'" required>
							</div>';
				$item_id = $row->id;
			}else{
				$custom_url = '';
				$item_id = $row->item_id;
			}
			
			//For Child Menu (menu_id, menu_parent_id, mega_menu_id = 0, menu_type, item_id, lan, Primary = id(If custom_link item_id = id), menu_parent_child_id, menu_parent_child)
			$MainParentMegaTypeItemLan = 'data-mainparentmegatypeitemlan="'.$menu_id.'-'.$row->menu_parent_id.'-'.$mega_menu_id.'-'.$row->menu_type.'-'.$item_id.'-'.$row->lan.'-'.$row->id.'-'.$row->id.'-child"';
			
			//menu_type, item_id, lan (custom_link == $row->id)
			$TypeItemLan = 'data-typeitemlan="'.$row->menu_type.'-'.$item_id.'-'.$row->lan.'"';
			
			$menulist .= '<li '.$MainParentMegaTypeItemLan.' '.$TypeItemLan.' '.$data_childmenu_id.' id="menu_item'.$menu_item_id.'" class="menu-item '.$child_menu_item_class.'">
						<div class="menu-item-bar">
							<div class="menu-item-handle">
								<span class="item-title">
									<span class="menu-item-title">'.$row->item_label.'</span>
								</span>
								<span class="item-controls">
									<span class="item-type">'.$row->menu_type.'</span>
									<a href="javascript:void(0);" class="collapsed item-edit" data-toggle="collapse" data-target="#collapse_menu_item'.$menu_item_id.'" aria-expanded="true" aria-controls="collapse_menu_item'.$menu_item_id.'"><i class="fa fa-angle-down"></i></a>
								</span>
							</div>
						</div>
						<div id="collapse_menu_item'.$menu_item_id.'" class="collapse" aria-labelledby="menu_item'.$menu_item_id.'" data-parent="#menu_management_accordion">
							<div class="menu-item-settings">
								<form id="MenuSettingsSubmitForm_id_'.$submitformid.'">
									'.$custom_url.'
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="item_label'.$menu_item_id.'">'. __('Navigation Label') .'<span class="red">*</span></label>
												<input type="text" name="item_label'.$menu_item_id.'" id="item_label'.$menu_item_id.'" class="form-control" value="'.$row->item_label.'" required>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label>'. __('Target Window') .'</label>
												<ul class="checkboxlist child_menu_type_list w-100">
													<li><label class="checkbox-title"><input type="radio" name="target_window'.$menu_item_id.'" value="_self" '.$self.'>Self</label></li>
													<li><label class="checkbox-title"><input type="radio" name="target_window'.$menu_item_id.'" value="_blank" '.$blank.'>Blank</label></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="css_class'.$menu_item_id.'">'. __('CSS Class (Optional)') .'</label>
												<input type="text" name="css_class'.$menu_item_id.'" id="css_class'.$menu_item_id.'" class="form-control" value="'.$row->css_class.'">
											</div>
										</div>
										<div class="col-lg-6"></div>
									</div>
									<div class="bottom-controls">
										<input type="hidden" name="lan'.$menu_item_id.'" id="lan'.$menu_item_id.'" value="'.$row->lan.'" />
										<input type="hidden" name="menu_type'.$menu_item_id.'" id="menu_type'.$menu_item_id.'" value="'.$row->menu_type.'" />
										<button type="submit" data-submitformid="'.$submitformid.'" class="btn blue-btn mr-10 MenuSettingsSubmitForm">'. __('Save') .'</button>
										<button type="submit" data-parentchildid="'.$MainMenuParentMenuChildMenuId.'" class="btn danger-btn DeleteParentChildMenu">'. __('Delete') .'</button>
									</div>
								</form>
							</div>
						</div>
					</li>';
		}
		
		$datalist = '<div class="child-menu-section">
					<span class="child-menu-type">'. __('Dropdown Menu') .'</span>
					<div class="inner-child-menu">
						<ul '.$MainParentMega.' class="child-menu-list">
							'.$menulist.'
						</ul>
					</div>
				</div>';
		
		return $datalist;
	}
	
	//Make Mega Menu
	public function makeMegaMenu($menu_id, $menu_parent_id){
		
		$data = Mega_menu::where('menu_id', '=', $menu_id)
				->where('menu_parent_id', '=', $menu_parent_id)
				->orderBy('sort_order','ASC')->get();
		$mega_menu_item_list = '';
		foreach($data as $row){
			$mega_menu_id = $row->id;
			
			//menu_id, menu_parent_id, mega_menu_id
			$MainParentMega = 'data-mainparentmega-id="'.$menu_id.'-'.$menu_parent_id.'-'.$mega_menu_id.'"';
			
			$mega_menu_item_list .= '
			<div class="inner-child-menu">
				<div class="child-menu-title">'.$row->mega_menu_title.'
					<div class="title-edit">
						<a onclick="onEditMegaMenuTitle('.$mega_menu_id.')" href="javascript:void(0);"><i class="fa fa-pencil-square"></i></a>
					</div>
				</div>
				<ul '.$MainParentMega.' class="child-menu-list">
					'.self::makeChildMenu($menu_id, $menu_parent_id, $mega_menu_id).'					
				</ul>
			</div>';
		}

		$datalist = '<div class="child-menu-section">
				<span class="child-menu-type">'. __('Mega Menu') .'</span>
				'.$mega_menu_item_list.'
			</div>';
		
		return $datalist;
	}
	
	//Make Child Menu
	public function makeChildMenu($menu_id, $menu_parent_id, $mega_menu_id){
		
		$datalist = Menu_child::where('menu_id', '=', $menu_id)
					->where('menu_parent_id', '=', $menu_parent_id)
					->where('mega_menu_id', '=', $mega_menu_id)
					->orderBy('sort_order','ASC')->get();
		
		$menulist = '';
		$custom_url = '';
		foreach($datalist as $row){
			$child_menu_item_class = 'child_menu_item';
			$menu_item_id = '_child_'.$row->id;
			$submitformid = 'child_'.$row->id.'_'.$row->menu_parent_id;
			$data_childmenu_id = 'data-parentmenu-id="'.$row->id.'"';
			$MainMenuParentMenuChildMenuId = 'child_'.$row->menu_id.'_'.$row->menu_parent_id.'_'.$row->id;
			
			$self = ($row->target_window == '_self') ?  "checked" : "checked";
			$blank = ($row->target_window == '_blank') ?  "checked" : "";
			
			if($row->menu_type == 'custom_link'){
				$custom_url = '<div class="form-group">
								<label for="custom_url'.$menu_item_id.'">'. __('URL') .'<span class="red">*</span></label>
								<input type="text" name="custom_url'.$menu_item_id.'" id="custom_url'.$menu_item_id.'" class="form-control" placeholder="https://" value="'.$row->custom_url.'" required>
							</div>';
				$item_id = $row->id;
			}else{
				$custom_url = '';
				$item_id = $row->item_id;
			}
			
			//For Child Menu (menu_id, menu_parent_id, mega_menu_id, menu_type, item_id, lan, Primary = id(If custom_link item_id = id), menu_parent_child_id, menu_parent_child)
			$MainParentMegaTypeItemLan = 'data-mainparentmegatypeitemlan="'.$menu_id.'-'.$menu_parent_id.'-'.$mega_menu_id.'-'.$row->menu_type.'-'.$item_id.'-'.$row->lan.'-'.$row->id.'-'.$row->id.'-child"';
			
			//menu_type, item_id, lan (custom_link == $row->id)
			$TypeItemLan = 'data-typeitemlan="'.$row->menu_type.'-'.$item_id.'-'.$row->lan.'"';
			
			$menulist .= '<li '.$MainParentMegaTypeItemLan.' '.$TypeItemLan.' '.$data_childmenu_id.' id="menu_item'.$menu_item_id.'" class="menu-item '.$child_menu_item_class.'">
						<div class="menu-item-bar">
							<div class="menu-item-handle">
								<span class="item-title">
									<span class="menu-item-title">'.$row->item_label.'</span>
								</span>
								<span class="item-controls">
									<span class="item-type">'.$row->menu_type.'</span>
									<a href="javascript:void(0);" class="collapsed item-edit" data-toggle="collapse" data-target="#collapse_menu_item'.$menu_item_id.'" aria-expanded="true" aria-controls="collapse_menu_item'.$menu_item_id.'"><i class="fa fa-angle-down"></i></a>
								</span>
							</div>
						</div>
						<div id="collapse_menu_item'.$menu_item_id.'" class="collapse" aria-labelledby="menu_item'.$menu_item_id.'" data-parent="#menu_management_accordion">
							<div class="menu-item-settings">
								<form id="MenuSettingsSubmitForm_id_'.$submitformid.'">
									'.$custom_url.'
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="item_label'.$menu_item_id.'">'. __('Navigation Label') .'<span class="red">*</span></label>
												<input type="text" name="item_label'.$menu_item_id.'" id="item_label'.$menu_item_id.'" class="form-control" value="'.$row->item_label.'" required>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label>'. __('Target Window') .'</label>
												<ul class="checkboxlist child_menu_type_list w-100">
													<li><label class="checkbox-title"><input type="radio" name="target_window'.$menu_item_id.'" value="_self" '.$self.'>Self</label></li>
													<li><label class="checkbox-title"><input type="radio" name="target_window'.$menu_item_id.'" value="_blank" '.$blank.'>Blank</label></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="css_class'.$menu_item_id.'">'. __('CSS Class (Optional)') .'</label>
												<input type="text" name="css_class'.$menu_item_id.'" id="css_class'.$menu_item_id.'" class="form-control" value="'.$row->css_class.'">
											</div>
										</div>
										<div class="col-lg-6"></div>
									</div>
									<div class="bottom-controls">
										<input type="hidden" name="lan'.$menu_item_id.'" id="lan'.$menu_item_id.'" value="'.$row->lan.'" />
										<input type="hidden" name="menu_type'.$menu_item_id.'" id="menu_type'.$menu_item_id.'" value="'.$row->menu_type.'" />
										<button type="submit" data-submitformid="'.$submitformid.'" class="btn blue-btn mr-10 MenuSettingsSubmitForm">'. __('Save') .'</button>
										<button type="submit" data-parentchildid="'.$MainMenuParentMenuChildMenuId.'" class="btn danger-btn DeleteParentChildMenu">'. __('Delete') .'</button>
									</div>
								</form>
							</div>
						</div>
					</li>';
		}

		return $menulist;
	}
	
	//Ajax make menu list
	public function ajaxMakeMenuList(Request $request){

		$id = $request->id;
		if($request->ajax()){

			$menulist = self::makeParentMenu($id);
			
			return view('backend.partials.make_menu_list', compact('menulist'))->render();
		}
	}
	
	//Get data for Page Menu Builder Pagination
	public function getPageMenuBuilderData(Request $request){

		$search = $request->search;
		$id = $request->id;
		$lan = $request->lan;
		
		if($request->ajax()){

			if($search != ''){
				
				$main_menu = Menu::where('id', $id)->first();
				
				$page_datalist = Page::where('lan', '=', $lan)
							->where('is_publish', '=', 1)
							->where(function ($query) use ($search){
								$query->where('title', 'like', '%'.$search.'%')
									->orWhere('slug', 'like', '%'.$search.'%');
							})
							->orderBy('id','desc')
							->paginate(10);
				
			}else{
				$main_menu = Menu::where('id', $id)->first();
				$page_datalist = Page::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
			}

			return view('backend.partials.page_menu_builder', compact('main_menu', 'page_datalist'))->render();
		}
	}

	//Get data for Brand Menu Builder Pagination
	public function getBrandMenuBuilderData(Request $request){

		$search = $request->search;
		$id = $request->id;
		$lan = $request->lan;
		
		if($request->ajax()){

			if($search != ''){
				
				$main_menu = Menu::where('id', $id)->first();

				$brand_datalist = Brand::where('lan', '=', $lan)
							->where('is_publish', '=', 1)
							->where(function ($query) use ($search){
								$query->where('name', 'like', '%'.$search.'%');
							})
							->orderBy('id','desc')
							->paginate(10);
				
			}else{
				$main_menu = Menu::where('id', $id)->first();
				$brand_datalist = Brand::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
			}

			return view('backend.partials.brand_menu_builder', compact('main_menu', 'brand_datalist'))->render();
		}
	}
	
	//Get data for Product Menu Builder Pagination
	public function getProductMenuBuilderData(Request $request){

		$search = $request->search;
		$id = $request->id;
		$lan = $request->lan;
		
		if($request->ajax()){

			if($search != ''){
				
				$main_menu = Menu::where('id', $id)->first();
				
				$product_datalist = Product::where('lan', '=', $lan)
							->where('is_publish', '=', 1)
							->where(function ($query) use ($search){
								$query->where('title', 'like', '%'.$search.'%')
									->orWhere('slug', 'like', '%'.$search.'%');
							})
							->orderBy('id','desc')
							->paginate(10);
				
			}else{
				$main_menu = Menu::where('id', $id)->first();
				$product_datalist = Product::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
			}

			return view('backend.partials.product_menu_builder', compact('main_menu', 'product_datalist'))->render();
		}
	}
	
	//Get data for Product Category Menu Builder Pagination
	public function getProductCategoryMenuBuilderData(Request $request){

		$search = $request->search;
		$id = $request->id;
		$lan = $request->lan;
		
		if($request->ajax()){

			if($search != ''){
				
				$main_menu = Menu::where('id', $id)->first();
				
				$product_category_datalist = Pro_category::where('lan', '=', $lan)
							->where('is_publish', '=', 1)
							->where(function ($query) use ($search){
								$query->where('name', 'like', '%'.$search.'%')
									->orWhere('slug', 'like', '%'.$search.'%');
							})
							->orderBy('id','desc')
							->paginate(10);
				
			}else{
				$main_menu = Menu::where('id', $id)->first();
				$product_category_datalist = Pro_category::where('lan', '=', $lan)->where('is_publish', '=', 1)->orderBy('id','desc')->paginate(10);
			}

			return view('backend.partials.product_category_menu_builder', compact('main_menu', 'product_category_datalist'))->render();
		}
	}
	
	//Save for menu parent
	public function SaveParentMenu(Request $request){
		$res = array();

		$menu_id = $request->menu_id;
		$menu_type = $request->menu_type;
		$lan = $request->lan;

		$max_id = Menu_parent::where('menu_id', $menu_id)->max('sort_order');
		$maxid = $max_id+1;
		$index = 0;
		if($menu_type == 'custom_link'){
			
			$item_label = esc($request->link_text);
			$custom_url = esc($request->url);
			
			$sort_order = $maxid;
			$data = array(
				'menu_id' => $menu_id,
				'menu_type' => $menu_type,
				'child_menu_type' => 'none',
				'item_label' => $item_label,
				'custom_url' => $custom_url,
				'target_window' => '_self',
				'lan' => $lan,
				'sort_order' => $sort_order
			);
			
			Menu_parent::create($data);
			
			$index = 1;
			
		}else{
			$idsStr = $request->ids;
			$idsArray = explode(',', $idsStr);
			
			foreach($idsArray as $item_id){
				$sort_order = $maxid++;
				
				$name_slug = self::getPagePostCategoryNameSlug($menu_type, $item_id);
				if($menu_type == 'page'){
					$item_label = esc($name_slug['title']);
					$custom_url = esc($name_slug['slug']);
				}elseif($menu_type == 'brand'){
					$item_label = esc($name_slug['name']);
					$custom_url = esc(str_slug($name_slug['name']));
				}elseif($menu_type == 'product'){
					$item_label = esc($name_slug['title']);
					$custom_url = esc($name_slug['slug']);
				}else{
					$item_label = esc($name_slug['name']);
					$custom_url = esc($name_slug['slug']);
				}

				$data = array(
					'menu_id' => $menu_id, 
					'menu_type' => $menu_type, 
					'child_menu_type' => 'none', 
					'item_id' => $item_id,
					'item_label' => $item_label, 
					'custom_url' => $custom_url,
					'target_window' => '_self',
					'lan' => $lan, 
					'sort_order' => $sort_order
				);
				
				Menu_parent::create($data);
				
				$index++;
			}
		}
		
		if($index>0){
			$res['msgType'] = 'success';
			$res['msg'] = __('New Data Added Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data insert failed');
		}

		return response()->json($res);
	}
	
	//Get name and slug for page, post, brand, product and product category 
	public function getPagePostCategoryNameSlug($menu_type, $id){
		
		if($menu_type == 'page'){
			
			$data = Page::where('id', $id)->first();

		}elseif($menu_type == 'brand'){
			
			$data = Brand::where('id', $id)->first();
		
		}elseif($menu_type == 'product'){
			
			$data = Product::where('id', $id)->first();
		
		}elseif($menu_type == 'product_category'){
			
			$data = Pro_category::where('id', $id)->first();
		}
		
		return $data;
	}
	
	//Update Menu Settings
    public function UpdateMenuSettings(Request $request){
		$res = array();
		
		$menu_parent_id = $request->input('menu_parent_id');
		$id = $request->input('id');
		$menu_next = $request->input('menu_next');
		
		$menu_type = $request->input('menu_type_'.$menu_next.'_'.$id);
		$item_label = esc($request->input('item_label_'.$menu_next.'_'.$id));
		$target_window = $request->input('target_window_'.$menu_next.'_'.$id);
		$css_class = esc($request->input('css_class_'.$menu_next.'_'.$id));
		$lan = $request->input('lan_'.$menu_next.'_'.$id);
		
		$MenuChildCount = Menu_child::where('menu_parent_id', $id)->count();
		
		//parent
		if($menu_next == 'parent'){
			$child_menu_type = $request->input('child_menu_type_'.$menu_next.'_'.$id);
			
			//Delete child menu and mega menu
			if($MenuChildCount == 0){
				Menu_child::where('menu_id', '=', $menu_parent_id)->where('menu_parent_id', '=', $id)->delete();
				Mega_menu::where('menu_id', '=', $menu_parent_id)->where('menu_parent_id', '=', $id)->delete();
			}
			
			if($child_menu_type == 'mega_menu'){
				$column = $request->input('column_'.$menu_next.'_'.$id) == '' ? 4 : $request->input('column_'.$menu_next.'_'.$id);
				$width_type = $request->input('width_type_'.$menu_next.'_'.$id) == '' ? 'full_width' : $request->input('width_type_'.$menu_next.'_'.$id);
				if($width_type == 'full_width'){
					$width = NULL;
				}else{
					$width = $request->input('width_'.$menu_next.'_'.$id);
				}
			}else{
				$column = NULL;
				$width_type = NULL;
				$width = NULL;
			}
			
			if($menu_type == 'custom_link'){
				$custom_url = esc($request->input('custom_url_'.$menu_next.'_'.$id));
				
				if($MenuChildCount>0){
					$data = array(
						'custom_url' => $custom_url,
						'item_label' => $item_label,
						'target_window' => $target_window,
						'css_class' => $css_class
					);
				}else{
					$data = array(
						'custom_url' => $custom_url,
						'item_label' => $item_label,
						'target_window' => $target_window,
						'css_class' => $css_class,
						'child_menu_type' => $child_menu_type,
						'column' => $column,
						'width_type' => $width_type,
						'width' => $width
					);
				}
			}else{
				if($MenuChildCount>0){
					$data = array(
						'item_label' => $item_label,
						'target_window' => $target_window,
						'css_class' => $css_class
					);
				}else{
					$data = array(
						'item_label' => $item_label,
						'target_window' => $target_window,
						'css_class' => $css_class,
						'child_menu_type' => $child_menu_type,
						'column' => $column,
						'width_type' => $width_type,
						'width' => $width
					);
				}
			}
			
			$response = Menu_parent::where('id', $id)->update($data);
			
			//child_menu_type
			if($child_menu_type == 'mega_menu'){
				if($MenuChildCount == 0){
					for ($col = 1; $col <= $column; $col++) {
						$sort_order = $col;
						$mega_menu_title = 'Mega menu title '.$col;
						
						$data = array(
							'menu_id' => $menu_parent_id, 
							'menu_parent_id' => $id, 
							'mega_menu_title' => $mega_menu_title, 
							'is_title' => 1,
							'is_image' => 0,
							'lan' => $lan, 
							'sort_order' => $sort_order
						);
						
						Mega_menu::create($data);
					}
				}
			}
			
		//child
		}elseif($menu_next == 'child'){
			
			if($menu_type == 'custom_link'){
				$custom_url = esc($request->input('custom_url_'.$menu_next.'_'.$id));
				$data = array(
					'custom_url' => $custom_url,
					'item_label' => $item_label,
					'target_window' => $target_window,
					'css_class' => $css_class
				);
			}else{
				$data = array(
					'item_label' => $item_label,
					'target_window' => $target_window,
					'css_class' => $css_class
				);
			}
			
			$response = Menu_child::where('id', $id)->update($data);
		}
		
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }
	
	//Delete Parent Child Menu
	public function deleteParentChildMenu(Request $request){
		
		$res = array();

		$lan = $request->lan;
		$menu_next = $request->menu_next;
		$main_menu_id = $request->main_menu_id;
		$menu_parent_id = $request->menu_parent_id;
		$menu_child_id = $request->menu_child_id;

		//Delete Parent Menu
		if($menu_next == 'parent'){

			$response = Menu_parent::where('id', $menu_parent_id)->delete();
			
			if($response){
				Mega_menu::where('menu_id', '=', $main_menu_id)
							->where('menu_parent_id', '=', $menu_parent_id)->delete();
							
				Menu_child::where('menu_id', '=', $main_menu_id)
							->where('menu_parent_id', '=', $menu_parent_id)->delete();
							
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
			
		//Delete Child Menu
		}elseif($menu_next == 'child'){
			$response = Menu_child::where('id', $menu_child_id)->delete();
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}

		return response()->json($res);
	}
	
	//Get data for Mega Menu Title by id
    public function getMegaMenuTitleById(Request $request){

		$id = $request->id;
		
		$data = Mega_menu::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Update Mega Menu Title
    public function UpdateMegaMenuTitle(Request $request){
		$res = array();
		
		$id = $request->input('mega_menu_RecordId');
		$mega_menu_title = esc($request->input('mega_menu_title'));
		$istitle = $request->input('is_title');
		$isimage = $request->input('is_image');
		$image = $request->input('mega_menu_image');
		
		if ($istitle == 'true' || $istitle == 'on') {
			$is_title = 1;
		}else {
			$is_title = 0;
		}
		if ($isimage == 'true' || $isimage == 'on') {
			$is_image = 1;
		}else {
			$is_image = 0;
		}
		
		$data = array(
			'mega_menu_title' => $mega_menu_title,
			'is_title' => $is_title,
			'is_image' => $is_image,
			'image' => $image
		);
		
		$response = Mega_menu::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }	
	
	//Update Sortable Menu List
    public function UpdateSortableMenuList(Request $request){
		$res = array();
		
		$ParentMenuList = json_decode($request->input('ParentMenuList'), true);
		$ChildMenuList = json_decode($request->input('ChildMenuList'), true);

		$ParentMenuCollection = collect($ParentMenuList);
		$CountParent = $ParentMenuCollection->count();

		$ChildMenuCollection = collect($ChildMenuList);
		$CountChild = $ChildMenuCollection->count();
		
		if($CountParent == 0){
			$res['msgType'] = 'error';
			$res['msg'] = __('No data available');
			return response()->json($res);
		}
		
		$incri = 0;
		foreach ($ParentMenuList as $key => $row) {
			$sort_order = $key;
			
			$RowArray = explode("-", $row);
			$menu_id = $RowArray[0];
			$menu_type = $RowArray[1];
			$item_id = $RowArray[2];
			$lan = $RowArray[3];
			$primary_id = $RowArray[4];
			$menu_parents_primary_id = $RowArray[5];
			$menu_parent_child = $RowArray[6];
			
			if($primary_id == 0){
				if($menu_type == 'custom_link'){
					$dataCount = Menu_parent::where('id', $menu_parents_primary_id)->count();
					
					if($dataCount>0){
						$data = array('sort_order' => $sort_order);
						Menu_parent::where('id', $menu_parents_primary_id)->update($data);

					}else{
						//Copy from child menu for parent menu
						$ChildMenuRow = self::getParentChildMenuList($menu_parents_primary_id, $menu_parent_child);

						$item_label = $ChildMenuRow['item_label'];
						$custom_url = $ChildMenuRow['custom_url'];
						$target_window = $ChildMenuRow['target_window'];
						$css_class = $ChildMenuRow['css_class'];
						
						$data = array(
							'menu_id' => $menu_id, 
							'menu_type' => $menu_type,
							'child_menu_type' => 'none',
							'item_label' => $item_label, 
							'custom_url' => $custom_url,
							'target_window' => $target_window,
							'css_class' => $css_class,
							'lan' => $lan, 
							'sort_order' => $sort_order
						);
						
						Menu_parent::create($data);
						
						//new code
						if($menu_parent_child == 'parent'){
							Menu_parent::where('id', $menu_parents_primary_id)->delete();
						}else{
							Menu_child::where('id', $menu_parents_primary_id)->delete();
						}
					}
				}else{

					$dataCount = Menu_parent::where('id', $menu_parents_primary_id)->count();
								
					if($dataCount>0){
						$data = array('sort_order' => $sort_order);
						Menu_parent::where('id', $menu_parents_primary_id)->update($data);
						
					}else{
						
						//Copy from child menu for parent menu
						$ChildMenuRow = self::getParentChildMenuList($menu_parents_primary_id, $menu_parent_child);

						$item_label = $ChildMenuRow['item_label'];
						$custom_url = $ChildMenuRow['custom_url'];
						$target_window = $ChildMenuRow['target_window'];
						$css_class = $ChildMenuRow['css_class'];
						
						$data = array(
							'menu_id' => $menu_id, 
							'menu_type' => $menu_type,
							'child_menu_type' => 'none',
							'item_id' => $item_id,
							'item_label' => $item_label, 
							'custom_url' => $custom_url,
							'target_window' => $target_window,
							'css_class' => $css_class,
							'lan' => $lan, 
							'sort_order' => $sort_order
						);
						
						Menu_parent::create($data);
						
						//new code
						if($menu_parent_child == 'parent'){
							Menu_parent::where('id', $menu_parents_primary_id)->delete();
						}else{
							Menu_child::where('id', $menu_parents_primary_id)->delete();
						}
					}
				}			
			}else{
				$data = array('sort_order' => $sort_order);
				Menu_parent::where('id', $primary_id)->update($data);
			}
			
			$incri++;
		}
		
		if($CountChild>0){
			self::UpdateSortableChildMenuList($ChildMenuList);
		}
		
		if($incri>0){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }
	
	//Update Sortable Child Menu List
    public function UpdateSortableChildMenuList($ChildMenuList){
		
		$res = 0;
		$incri = 0;
		foreach ($ChildMenuList as $key => $row) {
			$sort_order = $key;
			
			$RowArray = explode("-", $row);
			$menu_id = $RowArray[0];
			$menu_parent_id = $RowArray[1];
			$mega_menu_id = $RowArray[2] == '0' ? NULL : $RowArray[2];
			$menu_type = $RowArray[3];
			$item_id = $RowArray[4];
			$lan = $RowArray[5];
			$primary_id = $RowArray[6];
			$menu_parents_primary_id = $RowArray[7];
			$menu_parent_child = $RowArray[8];
			
			if($primary_id == 0){
				if($menu_type == 'custom_link'){

					$dataCount = Menu_child::where('id', $menu_parents_primary_id)->count();
					
					if($dataCount>0){
						$data = array('menu_parent_id' => $menu_parent_id, 'mega_menu_id' => $mega_menu_id, 'sort_order' => $sort_order);
						Menu_child::where('id', $menu_parents_primary_id)->update($data);
					}else{
						
						//Copy from parent menu for child menu
						$ParentMenuRow = self::getParentChildMenuList($menu_parents_primary_id, $menu_parent_child);

						$item_label = $ParentMenuRow['item_label'];
						$custom_url = $ParentMenuRow['custom_url'];
						$target_window = $ParentMenuRow['target_window'];
						$css_class = $ParentMenuRow['css_class'];
						
						$data = array(
							'menu_id' => $menu_id, 
							'menu_parent_id' => $menu_parent_id,
							'mega_menu_id' => $mega_menu_id,
							'menu_type' => $menu_type,
							'item_label' => $item_label, 
							'custom_url' => $custom_url,
							'target_window' => $target_window,
							'css_class' => $css_class,
							'lan' => $lan, 
							'sort_order' => $sort_order
						);
						
						Menu_child::create($data);
						
						//new code
						if($menu_parent_child == 'parent'){
							Menu_parent::where('id', $menu_parents_primary_id)->delete();
						}else{
							Menu_child::where('id', $menu_parents_primary_id)->delete();
						}
					}
				}else{

					$dataCount = Menu_child::where('id', '=', $menu_parents_primary_id)->count();
								
					if($dataCount>0){

						$data = array('menu_parent_id' => $menu_parent_id, 'mega_menu_id' => $mega_menu_id, 'sort_order' => $sort_order);

						Menu_child::where('id', '=', $menu_parents_primary_id)->update($data);
					}else{
						
						//Copy from parent menu for child menu
						$ParentMenuRow = self::getParentChildMenuList($menu_parents_primary_id, $menu_parent_child);

						$item_label = $ParentMenuRow['item_label'];
						$custom_url = $ParentMenuRow['custom_url'];
						$target_window = $ParentMenuRow['target_window'];
						$css_class = $ParentMenuRow['css_class'];
						
						$data = array(
							'menu_id' => $menu_id, 
							'menu_parent_id' => $menu_parent_id,
							'mega_menu_id' => $mega_menu_id,
							'menu_type' => $menu_type,
							'item_id' => $item_id,
							'item_label' => $item_label, 
							'custom_url' => $custom_url,
							'target_window' => $target_window,
							'css_class' => $css_class,
							'lan' => $lan, 
							'sort_order' => $sort_order
						);
						
						Menu_child::create($data);

						//new code
						if($menu_parent_child == 'parent'){
							Menu_parent::where('id', $menu_parents_primary_id)->delete();
						}else{
							Menu_child::where('id', $menu_parents_primary_id)->delete();
						}
					}
				}			
			}else{
				$data = array('sort_order' => $sort_order);
				Menu_child::where('id', $primary_id)->update($data);
			}
				
			$incri++;
		}
		
		if($incri>0){
			$res = 1;
		}else{
			$res = 0;
		}
		
		return $res;
    }
	
	//get Parent Child Menu List
	public function getParentChildMenuList($menu_parents_primary_id, $menu_parent_child){
		
		if($menu_parent_child == 'parent'){
			$data = Menu_parent::where('id', $menu_parents_primary_id)->first();
		}else{
			$data = Menu_child::where('id', $menu_parents_primary_id)->first();
		}
		
		return $data;
	}	
}
