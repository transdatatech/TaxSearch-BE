<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFile extends Model
{
    use HasFactory;

    protected $table = 'property_files';

    protected $fillable = [
        'name','batch_id','status_id','path'
    ];
}
