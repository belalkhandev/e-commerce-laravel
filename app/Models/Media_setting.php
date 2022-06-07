<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media_setting extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'media_type', 
		'media_desc', 
		'media_width', 
		'media_height', 
    ];		
}
