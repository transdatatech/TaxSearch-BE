<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCustomer extends Model
{
    use HasFactory;
    protected $table = 'payment_customers';

    protected $fillable = [
        'user_id',
        'payment_mode_id',
        'customer_id'
    ];
}
