<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media_option extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'title', 
		'alt_title', 
		'thumbnail', 
		'large_image', 
		'option_value', 
    ];	
}
