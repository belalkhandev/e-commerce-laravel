<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lankeyvalue extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'language_code', 'language_key', 'language_value',
    ];	
}
