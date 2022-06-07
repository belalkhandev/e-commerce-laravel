<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu_parent extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'menu_id', 
		'menu_type', 
		'child_menu_type',
		'item_id',
		'item_label',
		'custom_url',
		'target_window',
		'css_class',
		'column',
		'width_type',
		'width',
		'lan',
		'sort_order',
    ];	
}
