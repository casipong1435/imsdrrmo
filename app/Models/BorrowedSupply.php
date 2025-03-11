<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedSupply extends Model
{
    //
    protected $fillable = [
        'supply_id',
        'quantity',
        'borrower'
    ];

    public function supply(){
        return $this->belongsTo(Supply::class);
    }
}
