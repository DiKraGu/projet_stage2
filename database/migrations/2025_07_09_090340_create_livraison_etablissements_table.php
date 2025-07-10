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
        Schema::create('livraisons_etablissement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id');
            $table->unsignedBigInteger('menu_id'); // Nouvelle colonne : référence au menu

            $table->date('date_livraison')->nullable();
            $table->enum('statut', ['en_attente', 'livrée', 'annulée'])->default('en_attente');
            $table->timestamps();
            $table->foreign('etablissement_id')->references('id')->on('etablissements')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

            $table->unique(['etablissement_id','menu_id'], 'unique_livraison_par_semaine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraisons_etablissement');
    }
};
