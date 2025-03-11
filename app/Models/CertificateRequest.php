<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
    //
    protected $fillable = [
        'recipient',
        'address',
        'reason',
        'incident',
        'incident_id',
        'date',
        'date_given',
    ];
}
