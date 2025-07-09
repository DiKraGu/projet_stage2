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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_stock_admin_id')->constrained('lot_stock_admins')->onDelete('cascade');
            $table->enum('type', ['stock_faible', 'bientot_perime', 'perime', 'epuise']);
            $table->enum('statut', ['active', 'ignoree'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
