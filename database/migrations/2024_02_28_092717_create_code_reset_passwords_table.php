<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('code_reset_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('code');
            //$table->timestamp('created_at')->nullable(false); // Définir created_at comme non nullable
            $table->timestamp('expires_at')->nullable(false); // Définir expires_at comme non nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_reset_passwords');
    }
};
