<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop any existing status check constraints on appointments
        $apptConstraints = DB::select("
            SELECT cc.name
            FROM sys.check_constraints cc
            JOIN sys.columns c ON cc.parent_object_id = c.object_id AND cc.parent_column_id = c.column_id
            JOIN sys.tables t ON t.object_id = c.object_id
            WHERE t.name = 'appointments' AND c.name = 'status'
        ");
        foreach ($apptConstraints as $c) {
            DB::statement("ALTER TABLE appointments DROP CONSTRAINT [{$c->name}]");
        }
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT chk_appt_status CHECK (status IN ('pending','approved','rejected','rescheduled','completed','cancelled'))");

        // Drop any existing status check constraints on reservations
        $resConstraints = DB::select("
            SELECT cc.name
            FROM sys.check_constraints cc
            JOIN sys.columns c ON cc.parent_object_id = c.object_id AND cc.parent_column_id = c.column_id
            JOIN sys.tables t ON t.object_id = c.object_id
            WHERE t.name = 'reservations' AND c.name = 'status'
        ");
        foreach ($resConstraints as $c) {
            DB::statement("ALTER TABLE reservations DROP CONSTRAINT [{$c->name}]");
        }
        DB::statement("ALTER TABLE reservations ADD CONSTRAINT chk_res_status CHECK (status IN ('pending','approved','rejected','reserved','occupied','completed','cancelled'))");
    }

    public function down(): void
    {
        try { DB::statement("ALTER TABLE appointments DROP CONSTRAINT chk_appt_status"); } catch (\Exception $e) {}
        try { DB::statement("ALTER TABLE reservations DROP CONSTRAINT chk_res_status"); } catch (\Exception $e) {}
    }
};
