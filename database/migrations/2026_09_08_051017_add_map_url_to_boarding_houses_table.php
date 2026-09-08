<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->string('map_url')->nullable()->after('thumbnail_url');
            $table->string('barangay')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropColumn('map_url');
        });
    }
};
