<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Only add 'status' if it doesn't exist yet
            if (!Schema::hasColumn('leaves', 'status')) {
                $table->string('status')->default('pending');
            }
            
            // Only add 'remarks' if it doesn't exist yet
            if (!Schema::hasColumn('leaves', 'remarks')) {
                $table->text('remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('leaves', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
