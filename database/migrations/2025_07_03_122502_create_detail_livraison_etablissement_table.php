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
        Schema::create('detail_livraison_etablissement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livraison_etablissement_id');
            // $table->foreignId('livraison_etablissement_id')->constrained('livraisons_etablissement')->onDelete('cascade');
            $table->foreign('livraison_etablissement_id', 'fk_detail_livraison_livraison_id')
                ->references('id')
                ->on('livraisons_etablissement')
                ->onDelete('cascade');

            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('lot_stock_admin_id')->nullable()->constrained('lot_stock_admins')->onDelete('cascade');
            $table->integer('quantite_livree');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_livraison_etablissement');
    }
};
