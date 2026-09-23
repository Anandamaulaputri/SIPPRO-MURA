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
        Schema::create('proposal_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->constrained('proposal_versions')->cascadeOnDelete();
            $table->string('jenis_lampiran')->comment('ktp, akta, surat_keterangan, rab, dokumen_pendukung');
            $table->string('nama_file');
            $table->string('file_path');
            $table->unsignedBigInteger('ukuran_file')->default(0)->comment('Ukuran dalam bytes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_attachments');
    }
};
