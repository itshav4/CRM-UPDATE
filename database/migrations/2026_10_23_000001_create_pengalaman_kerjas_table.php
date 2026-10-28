<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengalaman_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandidat_id')->nullable()->constrained('kandidats')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans')->cascadeOnDelete();
            $table->string('nama_perusahaan');
            $table->string('jenis_perusahaan');
            $table->unsignedSmallInteger('tahun_masuk');
            $table->unsignedSmallInteger('tahun_keluar')->nullable();
            $table->string('jabatan');
            $table->text('pekerjaan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengalaman_kerjas');
    }
};
