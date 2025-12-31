<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gedung')->unique();
            $table->string('nama_gedung');
            $table->integer('jumlah_lantai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropColumn('gedung_id');
        });
    }

};
