<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gedung_id')
                ->constrained('buildings')
                ->cascadeOnDelete();

            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->integer('kapasitas');
            $table->enum('status', ['tersedia', 'tidak tersedia']);
            $table->string('gambar')->nullable();

            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
