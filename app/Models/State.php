<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';

    protected $fillable = [
        'name',
        'code',
        'price'
    ];

    public  function users(){
        return $this->belongsToMany(User::class,'state_users')->select('state_users.price','state_users.state_id','state_users.user_id');
    }

    public function propertyFilesData(){
        return $this->belongsToMany(PropertyFileData::class,'status_id','id');
    }

}
