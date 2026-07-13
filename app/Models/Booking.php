<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_WAITING = 'Menunggu';
    public const STATUS_SERVING = 'Sedang Dilayani';
    public const STATUS_DONE = 'Selesai';
    public const STATUS_CANCELLED = 'Dibatalkan';
    public const TYPE_ONLINE = 'Online';
    public const TYPE_WALK_IN = 'Walk-in';

    protected $fillable = [
        'public_id', 'booking_code', 'queue_number', 'customer_name', 'phone',
        'service_id', 'service_name', 'service_duration', 'visit_date',
        'estimated_waiting_time', 'estimated_service_time', 'queue_type',
        'status', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date:Y-m-d',
            'queue_number' => 'integer',
            'service_duration' => 'integer',
            'estimated_waiting_time' => 'integer',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function scopeForDate($query, string $date) { return $query->whereDate('visit_date', $date); }
    public function scopeActiveQueue($query) { return $query->whereIn('status', [self::STATUS_WAITING, self::STATUS_SERVING]); }
    public function isActive(): bool { return in_array($this->status, [self::STATUS_WAITING, self::STATUS_SERVING], true); }

    public function barberLog()
    {
        return $this->hasOne(BarberLog::class, 'booking_id', 'id');
    }
}
