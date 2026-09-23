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
        Schema::table('jelos', function (Blueprint $table) {
            $table->boolean('stalna_ponuda')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('jelos', function (Blueprint $table) {
            $table->dropColumn('stalna_ponuda');
        });
    }
};
