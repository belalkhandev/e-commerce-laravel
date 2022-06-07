<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mega_menu extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'menu_id', 
        'menu_parent_id', 
        'mega_menu_title', 
        'is_title', 
        'is_image', 
        'image', 
        'css_class', 
        'lan', 
        'sort_order', 

    ];	
}
