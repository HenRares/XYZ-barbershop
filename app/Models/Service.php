<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'duration', 'price', 'status'];
    protected function casts(): array { return ['duration' => 'integer', 'price' => 'integer']; }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function scopeActive($query) { return $query->where('status', 'aktif'); }
}
