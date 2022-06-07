<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu_child extends Model
{
    use HasFactory;
	
	protected $table = 'menu_childs';
	
    protected $fillable = [
        'menu_id', 
        'menu_parent_id', 
        'mega_menu_id', 
        'menu_type', 
        'item_id', 
        'item_label', 
        'custom_url', 
        'target_window', 
        'css_class', 
        'lan', 
        'sort_order', 

    ];	
}
