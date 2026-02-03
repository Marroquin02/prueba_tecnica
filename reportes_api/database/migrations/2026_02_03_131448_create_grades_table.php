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
        Schema::create('grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('material_id');
            $table->string('student_id');
            $table->uuid('cycle_id');
            $table->float('grade');
            $table->timestamps();

            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('student_id')->references('carnet')->on('students');
            $table->foreign('cycle_id')->references('id')->on('cycles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
