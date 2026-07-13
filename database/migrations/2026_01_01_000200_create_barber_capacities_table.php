<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('barber_capacities', function (Blueprint $table) { $table->id(); $table->date('date')->unique(); $table->unsignedSmallInteger('active_barbers')->default(4); $table->time('opening_time')->default('10:00'); $table->time('closing_time')->default('21:00'); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('barber_capacities'); }
};
