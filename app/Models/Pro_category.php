<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pro_category extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'subheader_image',
        'description',
        'layout',
        'lan',
        'parent_id',
        'is_subheader',
        'is_publish',
        'og_title',
        'og_image',
        'og_description',
        'og_keywords',
    ];
}
