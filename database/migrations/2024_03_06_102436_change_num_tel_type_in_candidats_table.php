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
        Schema::table('candidats', function (Blueprint $table) {
            $table->string('num_tel')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            // Si vous souhaitez revenir en arrière, vous pouvez spécifier le type de colonne d'origine ici.
            // Notez cependant que la perte de données peut se produire si le type est réduit.
            // $table->timestamp('num_tel')->nullable()->change();
        });
    }
};
