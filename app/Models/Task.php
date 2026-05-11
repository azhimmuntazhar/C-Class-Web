<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'title',
        'description',
    ];
}
