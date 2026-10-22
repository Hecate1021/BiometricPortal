<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
// Overall status: pending_dept_head, pending_hrmo, pending_mayor, approved, declined
          // Track who signed it and when
            $table->unsignedBigInteger('dept_head_id')->nullable();
            $table->timestamp('dept_head_approved_at')->nullable();
            
            $table->unsignedBigInteger('hrmo_id')->nullable();
            $table->timestamp('hrmo_approved_at')->nullable();
            
            $table->unsignedBigInteger('mayor_id')->nullable();
            $table->timestamp('mayor_approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            //
        });
    }
};
