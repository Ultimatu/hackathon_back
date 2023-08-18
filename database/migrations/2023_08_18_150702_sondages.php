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
        Schema::create('sondages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('titre', 50);
            $table->string('description', 255)->nullable();
            $table->string('url_media')->nullable();
            $table->string('type');
            $table->string('status')->enum = ['inactif', 'en cours', 'terminé'];
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('commune');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sondages');
    }
};
