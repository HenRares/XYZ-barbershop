<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberCapacity extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'active_barbers', 'opening_time', 'closing_time'];
    protected function casts(): array { return ['date' => 'date:Y-m-d', 'active_barbers' => 'integer']; }
}
