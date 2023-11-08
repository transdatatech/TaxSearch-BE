<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFileData extends Model
{
    use HasFactory;

    protected $table = 'property_file_data';

    protected $fillable = [
        'file_id','batch_id','state_id','property_id','area','address','zip_code','country','status_id'
    ];
}
