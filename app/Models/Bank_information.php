<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank_information extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'seller_id', 
        'bank_name', 
		'bank_code',
		'account_number',
		'account_holder',
		'paypal_id',
		'description',
    ];	
}
