<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandidat_id')->nullable()->constrained('kandidats')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans')->cascadeOnDelete();
            $table->string('nama_sertifikat');
            $table->string('bidang_sertifikasi');
            $table->unsignedSmallInteger('tahun_lulus');
            $table->string('file_sertifikat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasis');
    }
};
