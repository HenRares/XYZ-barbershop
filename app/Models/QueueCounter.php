<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueCounter extends Model
{
    protected $fillable = ['date', 'last_number'];
    protected function casts(): array { return ['date' => 'date:Y-m-d', 'last_number' => 'integer']; }
}
