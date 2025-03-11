<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffectedPerson extends Model
{
    protected $fillable = [
        'name',
        'incident_id',
        'purok'
    ];
}
