<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tp_status extends Model
{
    use HasFactory;
	
	protected $table = 'tp_status';
	
    protected $fillable = [
        'status',
    ];	
}
