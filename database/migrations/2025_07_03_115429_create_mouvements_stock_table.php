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
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['entrée', 'sortie', 'correction']);
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('lot_stock_admin_id')->constrained('lot_stock_admins')->onDelete('cascade');
            $table->integer('quantite');
            $table->date('date');
            $table->string('origine'); // ex: fournisseur, livraison, correction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
