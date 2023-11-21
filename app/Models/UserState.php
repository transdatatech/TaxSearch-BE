<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserState extends Model
{
    use HasFactory;

    protected $table = 'state_users';

    protected $fillable = [
        'user_id',
        'state_id',
        'price'
    ];
}
