<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryProgress extends Model
{
    use HasFactory;

    protected $table = 'inquiry_progress';

    protected $fillable = [
        'inquiry_id',
        'status',
        'remarks',
        'updated_by',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
