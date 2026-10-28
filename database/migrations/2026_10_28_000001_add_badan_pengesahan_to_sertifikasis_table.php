<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikasis', function (Blueprint $table) {
            $table->string('badan_pengesahan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sertifikasis', function (Blueprint $table) {
            $table->dropColumn('badan_pengesahan');
        });
    }
};
