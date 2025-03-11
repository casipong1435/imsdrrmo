<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable =[
        'informant',
        'date_time',
        'incident_type_id',
        'description',
        'casualties',
        'barangay_id',
        'purok',
        'other_incident'
    ];


    public function incident_type(){
        return $this->belongsTo(IncidentType::class);
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class);
    }

    public function incident_images(){
        return $this->hasMany(IncidentImage::class);
    }

    public function affected_persons(){
        return $this->hasMany(AffectedPerson::class);
    }



}
