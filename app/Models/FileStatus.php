<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStatus extends Model
{
    use HasFactory;
    protected $table = 'file_status';

    protected $fillable = [
        'name',
    ];
}
