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
            $table->date('semaine'); // Date du lundi de la semaine concernée
            $table->date('date_livraison')->nullable();
            $table->timestamps();

            $table->foreign('etablissement_id')->references('id')->on('etablissements')->onDelete('cascade');
            $table->unique(['etablissement_id', 'semaine'], 'unique_livraison_par_semaine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraison_etablissements');
    }
};
