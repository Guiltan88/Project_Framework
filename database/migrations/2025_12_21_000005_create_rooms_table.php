<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->foreignId('gedung_id')->constrained('buildings')->onDelete('cascade');
            $table->integer('lantai')->default(1);
            $table->integer('kapasitas');
            $table->enum('status', ['tersedia', 'tidak tersedia', 'dalam perbaikan'])->default('tersedia');
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
