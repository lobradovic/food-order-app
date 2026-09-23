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
        Schema::create('dnevna_ponuda_jelo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dnevna_ponuda_id')
                ->constrained('dnevna_ponudas')
                ->onDelete('cascade');
            $table->foreignId('jelo_id')
                ->constrained('jelos')
                ->onDelete('cascade');
            $table->unsignedInteger('cena'); // "zamrznuta" cena za taj dan, isti princip kao trenutna_cena
            $table->unsignedInteger('kolicina_dostupna')->nullable(); // null = neograniceno
            $table->timestamps();

            $table->unique(['dnevna_ponuda_id', 'jelo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dnevna_ponuda_jelo');
    }
};
