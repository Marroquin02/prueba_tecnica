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
        Schema::create('students', function (Blueprint $table) {
            $table->string('carnet')->primary();
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->uuid('career_id');
            $table->integer('ingress');
            $table->string('status');
            $table->timestamps();

            $table->foreign('career_id')->references('id')->on('careers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
