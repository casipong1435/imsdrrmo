<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyActivity extends Model
{
    protected $fillable = [
        'item_name',
        'quantity',
    ];
}
