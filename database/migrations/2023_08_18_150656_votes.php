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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('election_id')->constrained('elections')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

            Schema::dropIfExists('votes');
    }
};
