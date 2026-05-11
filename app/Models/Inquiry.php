<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'email',
        'subject',
        'message',
        'source_type',
        'source_url',
        'attachment',
        'status',
        'assigned_agency',
        'investigation_status', 
        'reviewed_by',
        'supporting_document',
        'status_updated_by',
        'status_updated_at',
    ];

    // One-to-one: Inquiry -> Assignment
    public function assignment()
    {
        return $this->hasOne(\App\Models\InquiryAssignment::class, 'inquiry_id');
    }

    // One-to-many: Inquiry -> Progress History
    public function progress()
    {
        return $this->hasMany(\App\Models\InquiryProgress::class, 'inquiry_id');
    }

    // (Optional) One-to-One: Inquiry belongs to user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
