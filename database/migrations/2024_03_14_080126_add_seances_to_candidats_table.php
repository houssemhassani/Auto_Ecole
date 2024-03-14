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
            // Ajoutez les colonnes seances_theoriques et seances_pratiques
            $table->integer('seances_theoriques')->default(0);
            $table->integer('seances_pratiques')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            // Supprimez les colonnes seances_theoriques et seances_pratiques
            $table->dropColumn('seances_theoriques');
            $table->dropColumn('seances_pratiques');
        });
    }
};
