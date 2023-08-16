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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->nullable(false);
            $table->string('prenom', 50)->nullable(false);
            $table->string('elector_card', 50)->nullable();
            $table->string('adresse', 50)->nullable();
            $table->string('numero_cni', 50)->nullable();
            $table->string('commune', 50)->nullable();
            $table->unsignedBigInteger('role_id')->nullable(false);
            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable(false)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
