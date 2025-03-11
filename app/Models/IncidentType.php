<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    protected $fillable =[
        'type'
    ];

    public function incidents(){
        return $this->hasMany(Incident::class);
    }
}
