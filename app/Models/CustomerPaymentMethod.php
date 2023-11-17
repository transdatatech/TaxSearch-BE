<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentMethod extends Model
{
    use HasFactory;

    protected  $table="customer_payment_methods";

    protected $fillable = [
        'user_id',
        'customer_id',
        'payment_setup_intent',
        'created_payment_method_id',
        'attached_payment_method_id',
        'is_default_method',
        'status',
    ];
}
