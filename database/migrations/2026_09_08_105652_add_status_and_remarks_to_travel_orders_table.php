<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_orders', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('travel_orders', 'remarks')) {
                $table->text('remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            if (Schema::hasColumn('travel_orders', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('travel_orders', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
