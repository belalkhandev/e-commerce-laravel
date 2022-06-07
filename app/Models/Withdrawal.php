<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'seller_id', 
        'amount', 
		'fee_amount',
		'payment_method',
		'transaction_id',
		'description',
		'status_id',
    ];		
}
