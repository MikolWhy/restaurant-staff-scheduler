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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table -> string('name');
            # could be bad if we were to add new roles in the future -> downtime. Could use enum class or string in the database.
            $table -> enum ('role', ['server' , 'cook' , 'manager']);
            # business rule is to limit to 10 but leaving room for future flexbility eg: 11 digits for international etc
            # Increasing to 15 for dashes in case we want that (including right now)
            # p.s to self have to rollback to update the migration :)
            $table -> string('phone_number', 15);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
