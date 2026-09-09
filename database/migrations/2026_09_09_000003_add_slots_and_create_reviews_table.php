<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add slot booking and companion fields to reservations
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('slots_reserved')->default(1)->after('room_id');
            $table->string('companion_name')->nullable()->after('slots_reserved');
        });

        // 2. Create real reviews and ratings table
        // SQL Server does not allow ON DELETE CASCADE on multiple FK paths (cycles),
        // so we use NO ACTION and handle cleanup in application logic.
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boarding_house_id')->constrained('boarding_houses')->onDelete('no action');
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->unsignedTinyInteger('rating'); // 1 to 5 stars
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['boarding_house_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['slots_reserved', 'companion_name']);
        });
    }
};
