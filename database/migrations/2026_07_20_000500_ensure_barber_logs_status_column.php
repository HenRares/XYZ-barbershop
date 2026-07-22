<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Database lama yang dibuat dari db_barbershop.sql belum memiliki kolom ini.
        if (! Schema::hasColumn('barber_logs', 'status')) {
            Schema::table('barber_logs', function (Blueprint $table) {
                $table->string('status', 20)->default('waiting')->after('service_end_at');
            });
        }
    }

    public function down(): void
    {
        // Sengaja no-op: pada instalasi baru kolom berasal dari migration awal.
    }
};
