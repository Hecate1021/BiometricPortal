<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('office_department')->nullable();
            $table->string('leave_type')->nullable(); // Maps to 6.A
            $table->string('leave_type_others')->nullable();
            $table->string('leave_details')->nullable(); // Maps to 6.B
            $table->string('details_specify_abroad')->nullable();
            $table->string('details_specify_hospital')->nullable();
            $table->string('details_specify_outpatient')->nullable();
            $table->string('details_specify_women')->nullable();
            $table->decimal('working_days', 4, 1)->nullable(); // Allows half days (e.g., 1.5)
            $table->string('commutation')->default('Not Requested');
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn([
                'office_department',
                'leave_type',
                'leave_type_others',
                'leave_details',
                'details_specify_abroad',
                'details_specify_hospital',
                'details_specify_outpatient',
                'details_specify_women',
                'working_days',
                'commutation'
            ]);
        });
    }
};
