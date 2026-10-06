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
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->foreignId('version_id')->constrained('proposal_versions')->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete()->comment('Staf/Admin yang mencatat ke sistem');
            $table->string('pejabat_disposisi')->default('Haziral, S.E. (KSB. Tata Usaha Pimpinan)')->comment('Pejabat yang melakukan disposisi fisik');
            $table->string('nomor_surat')->nullable()->comment('Nomor surat usulan dari pengusul');
            $table->date('tanggal_surat')->nullable()->comment('Tanggal surat dari pengusul');
            $table->string('asal_surat')->nullable()->comment('Asal instansi / organisasi / pemohon');
            $table->string('tujuan_disposisi')->default('Wakil Bupati Murung Raya')->comment('Tujuan arahan disposisi');
            $table->date('tanggal_disposisi')->comment('Tanggal pelaksanaan disposisi fisik');
            $table->text('catatan_disposisi')->nullable()->comment('Catatan/instruksi disposisi Pak Haziral');
            $table->string('file_bukti_disposisi')->nullable()->comment('Path file scan/foto lembar disposisi');
            $table->enum('status_disposisi', ['menunggu', 'selesai'])->default('selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};
