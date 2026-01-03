<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom role jika ada
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    public function down(): void
    {
        // Jika rollback, tambahkan kembali kolom role
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'staff', 'guest'])->default('guest')->after('email');
        });
    }
};
