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
        Schema::create('stanje_nalepnicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('trenutna_pozicija')->default(1);
            $table->timestamps();
        });

        DB::table('stanje_nalepnicas')->insert(['trenutna_pozicija' => 1]);
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stanje_nalepnicas');
    }
};
