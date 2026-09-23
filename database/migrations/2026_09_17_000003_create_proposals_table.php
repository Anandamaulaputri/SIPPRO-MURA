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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('judul_proposal');
            $table->decimal('total_anggaran', 15, 2)->default(0);
            $table->enum('status', ['draft', 'diajukan', 'perlu_perbaikan', 'disetujui', 'ditolak'])->default('draft');
            $table->unsignedInteger('versi_aktif')->default(1);
            $table->timestamp('tanggal_kirim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
