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
        Schema::table('sondages', function (Blueprint $table) {
            $table->foreignId('id_type_sondage')->nullable()->constrained('types_sondages')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sondages', function (Blueprint $table) {
            $table->dropForeign('sondages_id_type_sondage_foreign');
            $table->dropColumn('id_type_sondage');
        });
    }
};
