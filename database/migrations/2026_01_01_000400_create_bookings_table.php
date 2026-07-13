<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); $table->ulid('public_id')->unique(); $table->string('booking_code')->nullable()->unique();
            $table->unsignedInteger('queue_number'); $table->string('customer_name'); $table->string('phone', 30)->index();
            $table->foreignId('service_id')->constrained()->restrictOnDelete(); $table->string('service_name'); $table->unsignedSmallInteger('service_duration');
            $table->date('visit_date')->index(); $table->unsignedInteger('estimated_waiting_time')->default(0); $table->string('estimated_service_time', 5);
            $table->string('queue_type', 20)->default('Online')->index(); $table->string('status', 30)->default('Menunggu')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->timestamps();
            $table->unique(['visit_date', 'queue_number']); $table->index(['visit_date', 'status', 'queue_number']);
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};
