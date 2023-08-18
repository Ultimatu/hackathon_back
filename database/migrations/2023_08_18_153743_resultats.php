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
        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_candidat')->constrained('candidats')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_election')->constrained('elections')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('nb_votes');
            $table->integer('rang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }
};
