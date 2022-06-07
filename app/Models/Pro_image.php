<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pro_image extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'product_id',
        'thumbnail',
        'large_image',
        'desc',
    ];
}
