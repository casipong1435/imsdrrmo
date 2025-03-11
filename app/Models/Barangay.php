<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $fillable = [
        'barangay_name'
    ];

    public function incidents(){
        return $this->hasMany(Incident::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}
