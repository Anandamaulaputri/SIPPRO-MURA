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
        Schema::create('proposal_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->unsignedInteger('nomor_versi')->default(1);
            $table->longText('latar_belakang')->nullable();
            $table->longText('tujuan')->nullable();
            $table->string('lokasi_kegiatan')->nullable();
            $table->date('tanggal_kegiatan')->nullable();
            $table->longText('rincian_rab')->nullable()->comment('Rincian item anggaran format JSON/Teks');
            $table->string('file_proposal')->nullable()->comment('Path file dokumen proposal PDF');
            $table->text('catatan_revisi_pemohon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_versions');
    }
};
