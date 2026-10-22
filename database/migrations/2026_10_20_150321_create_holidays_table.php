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
    Schema::create('holidays', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., "Independence Day", "Typhoon Suspension"
        $table->date('date');
        $table->enum('type', ['regular', 'special', 'suspension']);
        $table->time('time_suspended')->nullable(); // e.g., '12:00:00' for half-day or calamity cut-off
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
