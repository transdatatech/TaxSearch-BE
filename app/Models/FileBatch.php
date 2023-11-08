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
}
