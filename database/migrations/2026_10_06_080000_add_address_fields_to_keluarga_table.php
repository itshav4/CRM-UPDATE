<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->string('province_id', 10)->nullable();
            $table->string('regency_id', 20)->nullable();
            $table->string('district_id', 20)->nullable();
            $table->string('village_id', 30)->nullable();
            $table->text('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropColumn([
                'province_id',
                'regency_id',
                'district_id',
                'village_id',
                'address',
            ]);
        });
    }
};