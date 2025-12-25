<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete();

            $table->enum('action', [
                'booked',
                'returned',
                'canceled'
            ]);

            $table->text('description')->nullable();
            $table->dateTime('action_time');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_logs');
    }
};

