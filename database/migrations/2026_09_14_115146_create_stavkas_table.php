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
        Schema::create('stavkas', function (Blueprint $table) {
            $table->id();
            $table->decimal('trenutna_cena',10,2);
            $table->unsignedInteger('kolicina');
            $table->foreignId('rezervacija_id')
                ->constrained('rezervacijas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('jelo_id')
                ->constrained('jelos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stavkas');
    }
};
