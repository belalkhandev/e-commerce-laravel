<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'title', 
		'slug', 
		'content', 
		'thumbnail', 
		'lan', 
		'is_publish', 
		'og_title', 
		'og_image', 
		'og_description', 
		'og_keywords', 
    ];	
}
