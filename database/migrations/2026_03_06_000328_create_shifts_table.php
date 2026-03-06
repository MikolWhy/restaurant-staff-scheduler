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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            # foreign key to staff table -> staff can have multiple shifts but shifts can only have one staff optionally eg: can be null/unassigned. 
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->date('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('role', ['server', 'cook', 'manager']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
