@extends('layouts.app')

@section('content')
<div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('proposals.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Proposal</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-[#151515] rounded-3xl border border-[#2A2A2A] shadow-xl p-6 sm:p-10">
        <div class="border-b border-[#2A2A2A] pb-6 mb-8">
            <h1 class="text-2xl font-black text-white tracking-tight">Formulir Pengajuan Proposal Baru</h1>
            <p class="text-xs text-[#A3A3A3] mt-1.5">
                Isi rincian usulan permohonan bantuan dana hibah atau bantuan sosial Pemerintah Kabupaten Murung Raya.
            </p>
        </div>

        <form method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Informasi Dasar -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px]">1</span>
                    <span>Informasi Umum Usulan</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Kategori Bantuan Proposal *</label>
                        <select name="category_id" required class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Judul Proposal Kegiatan / Usulan *</label>
                        <input type="text" name="judul_proposal" value="{{ old('judul_proposal') }}" required
                               placeholder="Contoh: Pengadaan Alat Kesenian Tradisional Dayak Siang Sanggar Belia Mura"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('judul_proposal') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Total Nilai Anggaran yang Diusulkan (Rp) *</label>
                        <input type="number" name="total_anggaran" value="{{ old('total_anggaran') }}" required min="100000" step="10000"
                               placeholder="Contoh: 25000000"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all font-mono">
                        @error('total_anggaran') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Rencana Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Lokasi Pelaksanaan Kegiatan di Kab. Murung Raya *</label>
                        <input type="text" name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" required
                               placeholder="Contoh: Gedung GPU Tira Tangka Balang, Puruk Cahu"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Substansi Teknis Usulan -->
            <div class="border-t border-[#2A2A2A] pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px]">2</span>
                    <span>Substansi & Latar Belakang Usulan</span>
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Latar Belakang Permohonan *</label>
                        <textarea name="latar_belakang" rows="3" required placeholder="Jelaskan kondisi saat ini dan urgensi kegiatan ini dilaksanakan di Murung Raya..."
                                  class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">{{ old('latar_belakang') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Maksud & Tujuan Kegiatan *</label>
                        <textarea name="tujuan" rows="3" required placeholder="Jelaskan target hasil atau manfaat nyata bagi masyarakat Murung Raya..."
                                  class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">{{ old('tujuan') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Ringkasan Rencana Anggaran Biaya (RAB)</label>
                        <textarea name="rincian_rab" rows="3" placeholder="Rincikan pos-pos belanja utama, misal: 1. Sewa alat: Rp 5.000.000, 2. Hadiah: Rp 10.000.000..."
                                  class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all font-mono">{{ old('rincian_rab') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Unggah Berkas & Lampiran -->
            <div class="border-t border-[#2A2A2A] pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px]">3</span>
                    <span>Dokumen Proposal & Lampiran Pendukung</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="p-4 rounded-2xl bg-[#181818] border border-[#2A2A2A]">
                        <label class="block text-xs font-bold text-white mb-1">Dokumen Proposal (PDF)</label>
                        <p class="text-[11px] text-[#A3A3A3] mb-2.5">Proposal resmi bertandatangan</p>
                        <input type="file" name="file_proposal" accept=".pdf"
                               class="w-full text-xs text-[#A3A3A3] file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                    </div>

                    <div class="p-4 rounded-2xl bg-[#181818] border border-[#2A2A2A]">
                        <label class="block text-xs font-bold text-white mb-1">KTP Penanggung Jawab</label>
                        <p class="text-[11px] text-[#A3A3A3] mb-2.5">Scan KTP Pemohon (PDF/JPG)</p>
                        <input type="file" name="lampiran_ktp" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-[#A3A3A3] file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                    </div>

                    <div class="p-4 rounded-2xl bg-[#181818] border border-[#2A2A2A]">
                        <label class="block text-xs font-bold text-white mb-1">Legalitas Lembaga / SK</label>
                        <p class="text-[11px] text-[#A3A3A3] mb-2.5">SK Organisasi / Izin / Rekom</p>
                        <input type="file" name="lampiran_organisasi" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-[#A3A3A3] file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t border-[#2A2A2A] pt-6 flex justify-end items-center space-x-3">
                <a href="{{ route('proposals.index') }}" class="px-5 py-2.5 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-7 py-3 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-105">
                    Kirimkan Usulan Proposal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
