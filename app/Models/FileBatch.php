<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileBatch extends Model
{
    use HasFactory;
    protected $table = 'file_batches';

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function propertyFiles(){
        return $this->hasMany(PropertyFile::class,'batch_id','id');
    }

    public function propertyFilesData(){
        return $this->hasMany(PropertyFileData::class,'batch_id','id')->with(['state.users'=>function($q){
            $q->where('state_users.user_id',auth()->user()->id)->get();
        },'fileStatus','invoiceDetails']);
    }
}
