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
        Schema::create('monitor', function (Blueprint $table) {
            $table->id();
            $table->string('num_professional')->nullable(false);
            $table->string('num_cin')->nullable(false)->unique();
            $table->unsignedBigInteger('auto_ecole_id')->nullable();
            $table->foreign('auto_ecole_id')->references('id')->on('auto_ecoles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor');
    }
};
