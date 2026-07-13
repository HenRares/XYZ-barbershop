<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberLog extends Model
{
    protected $fillable = [
        'booking_id',
        'barber_slot',
        'service_start_at',
        'service_end_at',
        'status',
    ];

    protected $casts = [
        'service_start_at' => 'datetime',
        'service_end_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}