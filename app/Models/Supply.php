<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    
    protected $fillable =[
        'name',
        'quantity',
        'unit',
        'status',
        'description',
        'type'
    ];
}
