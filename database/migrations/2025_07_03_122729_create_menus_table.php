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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id');
            $table->date('semaine'); // date du lundi de la semaine
            $table->date('semaine_fin');
            $table->timestamps();

            $table->foreign('etablissement_id')->references('id')->on('etablissements')->onDelete('cascade');
            $table->unique(['etablissement_id', 'semaine'], 'unique_menu_par_semaine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
