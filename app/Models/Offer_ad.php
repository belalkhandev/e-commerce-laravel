<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer_ad extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'offer_ad_type',
        'url',
        'image',
        'desc',
        'is_publish',
    ];
}
