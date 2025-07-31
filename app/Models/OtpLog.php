<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpLog extends Model
{
    protected $fillable = [
        'patient_id', 'channel', 'success', 'message'
    ];
}
