<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rezervacijas', function (Blueprint $table) {
            $table->unsignedTinyInteger('termin')->after('datum'); // 9, 10, 11, 12, 13 ili 14
        });
    }

    public function down(): void
    {
        Schema::table('rezervacijas', function (Blueprint $table) {
            $table->dropColumn('termin');
        });
    }
};
