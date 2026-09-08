<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Boarding Houses Table
        Schema::create('boarding_houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('barangay');
            $table->string('city')->default('Kidapawan City');
            $table->string('contact_number')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
        });

        // 2. Rooms Table
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boarding_house_id')->constrained('boarding_houses')->onDelete('cascade');
            $table->string('room_number');
            $table->string('room_type'); // Single, Duo, Studio, Bedspace
            $table->integer('capacity')->default(1);
            $table->decimal('monthly_rent', 10, 2);
            $table->text('description')->nullable();
            $table->enum('availability_status', ['available', 'occupied', 'under_maintenance'])->default('available');
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });

        // 3. Amenities Table
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 4. Room Amenities Pivot Table
        Schema::create('room_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Viewing Appointments Table
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->foreignId('boarding_house_id')->constrained('boarding_houses')->onDelete('no action');
            $table->date('scheduled_date');
            $table->string('time_slot'); // 09:00 AM, 02:00 PM, etc.
            $table->enum('status', ['pending', 'approved', 'rejected', 'rescheduled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Reservations Table
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('no action');
            $table->date('reservation_date');
            $table->date('intended_move_in_date');
            $table->decimal('deposit_amount', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'approved', 'rejected', 'reserved', 'occupied', 'completed'])->default('pending');
            $table->string('qr_reference')->unique()->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 7. Active Tenants Table
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('no action');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('monthly_rate', 10, 2);
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('room_amenities');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('boarding_houses');
    }
};
