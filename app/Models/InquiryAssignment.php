<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryAssignment extends Model
{
    use HasFactory;

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id');
    }

    public function inquiry()
    {
    return $this->belongsTo(\App\Models\Inquiry::class, 'inquiry_id');
        }

    public function mcmcStaff()
    {
    return $this->belongsTo(\App\Models\User::class, 'mcmc_staff_id');
    }
}
