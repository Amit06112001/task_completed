<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    use HasFactory;
   
    protected $table = 'tbl_tasks';

    protected $fillable = [
        'id',
        'title',
        'is_completed',
        'status',
        'created_at',
        'updated_at',
    ];
}
