<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('services', function (Blueprint $table) { $table->id(); $table->string('name'); $table->text('description')->nullable(); $table->unsignedSmallInteger('duration'); $table->unsignedBigInteger('price'); $table->string('status', 20)->default('aktif')->index(); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('services'); }
};
