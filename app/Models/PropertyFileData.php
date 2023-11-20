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

    public function state(){
        return $this->belongsTo(State::class,);
    }

    public function propertyFiles(){
        return $this->belongsTo(PropertyFile::class);
    }

    public function batches(){
        return $this->belongsTo(FileBatch::class);
    }
    public function fileStatus(){
        return $this->belongsTo(FileStatus::class,'status_id','id');
    }

    public function invoiceDetails(){
        return $this->hasMany(InvoiceDetail::class,'property_file_data_id','id');
    }
}
