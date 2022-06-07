<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'language_code', 
		'language_name', 
		'flag', 
		'language_default',
		'is_rtl',
		'status',
    ];	
}
