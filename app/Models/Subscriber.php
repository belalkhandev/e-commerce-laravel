<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'email_address',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'status',
    ];	
}
