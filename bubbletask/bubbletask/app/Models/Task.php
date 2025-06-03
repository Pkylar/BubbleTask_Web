<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',  // penting agar $task->due_date jadi objek Carbon
    ];
}
