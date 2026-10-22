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
        Schema::create('pass_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            // Transaction Details
            $table->text('transaction_details');
            $table->date('transaction_date');
            $table->time('time_from');
            $table->time('time_to');
            
            // Biometrics / Unlogged Attendance
            $table->boolean('unlogged_time_in_am')->default(false);
            $table->boolean('unlogged_time_out_am')->default(false);
            $table->boolean('unlogged_time_in_pm')->default(false);
            $table->boolean('unlogged_time_out_pm')->default(false);
            $table->integer('unlogged_count')->nullable();
            
            // Approval Workflow
            $table->string('status')->default('pending_dept_head');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('dept_head_id')->nullable();
            $table->timestamp('dept_head_approved_at')->nullable();
            $table->unsignedBigInteger('hrmo_id')->nullable();
            $table->timestamp('hrmo_approved_at')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pass_slips');
    }
};
