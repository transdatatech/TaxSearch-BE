<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'uuid',
        'discount',
        'tax',
        'total_amount',
        'comments'
    ];

    public function invoiceDetails(){
        return $this->hasMany(InvoiceDetail::class);
    }
}
