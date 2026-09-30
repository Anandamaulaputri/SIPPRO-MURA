@extends('layouts.app')

@section('content')
<div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Top Bar -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('proposals.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Proposal</span>
        </a>

        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#151515] border border-[#2A2A2A] text-[#D4AF37]">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                <span>Tahap 1: Pengusulan Berkas (v1.0)</span>
            </span>
        </div>
    </div>

    <!-- Info Pemohon & Lembaga Banner -->
    <div class="mb-8 p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-[#181818] via-[#141414] to-[#181818] border border-[#2A2A2A] shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-start sm:items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-[#0B0B0B] border border-[#D4AF37]/40 flex items-center justify-center text-[#D4AF37] text-xl shrink-0 shadow-inner">
                🏢
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs uppercase font-extrabold tracking-wider text-[#D4AF37]">Identitas Lembaga Pengusul</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A]">Terverifikasi</span>
                </div>
                <h2 class="text-base sm:text-lg font-black text-white mt-0.5">
                    {{ $profile->nama_lembaga ?? auth()->user()->name }}
                </h2>
                <p class="text-xs text-[#A3A3A3]">
                    Penanggung Jawab: <span class="text-white font-medium">{{ auth()->user()->name }}</span> &bull; 
                    Kontak: <span class="text-white font-medium">{{ auth()->user()->no_telepon ?? '-' }}</span>
                </p>
            </div>
        </div>

        <div class="md:text-right border-t md:border-t-0 md:border-l border-[#2A2A2A] pt-3 md:pt-0 md:pl-6 text-xs text-[#A3A3A3]">
            <span class="block text-[11px] text-[#737373] uppercase font-bold">Rekening Penyaluran:</span>
            <span class="font-bold text-white block">{{ $profile->nama_bank ?? 'Bank Kalteng' }}</span>
            <span class="font-mono text-[#D4AF37] block">{{ $profile->nomor_rekening ?? 'Belum diatur' }}</span>
            <span class="text-[10px] text-[#737373] block truncate max-w-[200px]">a.n {{ $profile->nama_pemilik_rekening ?? '-' }}</span>
        </div>
    </div>

    <!-- Form Utama Proposal -->
    <div class="bg-[#151515] rounded-3xl border border-[#2A2A2A] shadow-2xl p-6 sm:p-10">
        <div class="border-b border-[#2A2A2A] pb-6 mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <span class="px-2.5 py-1 rounded bg-[#0B0B0B] text-[#D4AF37] border border-[#2A2A2A] font-mono text-xs font-bold">
                    SIPPRO MURA
                </span>
                <span class="text-xs text-[#737373]">Bagian Pelayanan Administrasi Pimpinan Setda Kab. Murung Raya</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Formulir Pengajuan Proposal Baru</h1>
            <p class="text-xs text-[#A3A3A3] mt-1.5 leading-relaxed">
                Silakan lengkapi formulir usulan dan susun tabel rincian anggaran (RAB) di bawah ini. Sebelum dikirim, sistem akan memandu Anda melalui <strong class="text-[#D4AF37]">Gerbang Cek Ketelitian Mandiri</strong> guna mencegah revisi berulang.
            </p>
        </div>

        <form id="proposalForm" method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data" class="space-y-10">
            @csrf

            <!-- Hidden field for serialized JSON RAB -->
            <input type="hidden" name="rincian_rab" id="rincian_rab_json" value="{{ old('rincian_rab') }}">

            <!-- SECTION 1: Informasi Umum Usulan -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#2A2A2A]">
                    <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-white flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">1</span>
                        <span>Informasi Umum & Kategori Usulan</span>
                    </h2>
                    <span class="text-[11px] text-[#737373]">* Wajib diisi</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                            Kategori Bantuan Proposal <span class="text-rose-400">*</span>
                        </label>
                        <select name="category_id" id="category_id" required class="w-full px-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                            <option value="">-- Pilih Kategori Permohonan Bantuan --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }} — {{ $cat->keterangan }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                            Judul Proposal Kegiatan / Usulan <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="judul_proposal" id="judul_proposal" value="{{ old('judul_proposal') }}" required
                               placeholder="Contoh: Pengadaan Alat Musik Tradisional Dayak & Peringatan Seni Sanggar Belia Murung Raya"
                               class="w-full px-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('judul_proposal') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5 flex items-center justify-between">
                            <span>Total Usulan Anggaran (Rp) <span class="text-rose-400">*</span></span>
                            <span class="text-[10px] text-[#D4AF37] font-normal normal-case">Tersinkron Otomatis dari RAB</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-[#A3A3A3]">Rp</span>
                            <input type="number" name="total_anggaran" id="total_anggaran" value="{{ old('total_anggaran', 0) }}" required min="100000" step="10000"
                                   placeholder="0"
                                   class="w-full pl-10 pr-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-[#D4AF37] font-bold text-sm sm:text-base font-mono rounded-xl focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        </div>
                        <p class="text-[10px] text-[#737373] mt-1">Nilai di atas akan diperbarui otomatis saat Anda mengisi Tabel RAB di bawah.</p>
                        @error('total_anggaran') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                            Rencana Tanggal Pelaksanaan
                        </label>
                        <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}"
                               class="w-full px-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        <p class="text-[10px] text-[#737373] mt-1">Estimasi hari/tanggal pelaksanaan agenda kegiatan.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                            Lokasi Pelaksanaan di Kab. Murung Raya <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="lokasi_kegiatan" id="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" required
                               placeholder="Contoh: Gedung Pertemuan Umum (GPU) Tira Tangka Balang, Puruk Cahu / Desa Beriwit"
                               class="w-full px-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Substansi Usulan -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#2A2A2A]">
                    <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-white flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">2</span>
                        <span>Substansi Permohonan & Latar Belakang</span>
                    </h2>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                            Latar Belakang Permohonan <span class="text-rose-400">*</span>
                        </label>
                        <textarea name="latar_belakang" id="latar_belakang" rows="3" required
                                  placeholder="Jelaskan kondisi saat ini, permasalahan yang dihadapi, serta urgensi mengapa kegiatan ini penting didukung oleh Pemerintah Kabupaten Murung Raya..."
                                  class="w-full px-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all leading-relaxed">{{ old('latar_belakang') }}</textarea>
                        @error('latar_belakang') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                            Maksud & Tujuan Kegiatan <span class="text-rose-400">*</span>
                        </label>
                        <textarea name="tujuan" id="tujuan" rows="3" required
                                  placeholder="Uraikan target output kegiatan, sasaran penerima manfaat, dan dampak positif bagi masyarakat Murung Raya..."
                                  class="w-full px-3.5 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all leading-relaxed">{{ old('tujuan') }}</textarea>
                        @error('tujuan') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 3: TABEL PERHITUNGAN DINAMIS RAB (FITUR UNGGULAN TAHAP 2 & 3 SESUAI PRD) -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-2 border-b border-[#2A2A2A]">
                    <div>
                        <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-white flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">3</span>
                            <span>Tabel Perhitungan Dinamis Rencana Anggaran Biaya (RAB)</span>
                        </h2>
                        <p class="text-[11px] text-[#A3A3A3] mt-0.5">
                            Susun rincian kebutuhan per item. Sistem akan menghitung subtotal dan akumulasi total secara langsung.
                        </p>
                    </div>

                    <!-- Quick Tools -->
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="loadSampleRab()" class="px-3 py-1.5 rounded-lg bg-[#1F1F1F] hover:bg-[#252525] border border-[#2A2A2A] text-[#D4AF37] text-xs font-bold transition-colors flex items-center space-x-1">
                            <span>⚡</span>
                            <span>Muat Format Contoh</span>
                        </button>
                        <button type="button" onclick="addRabRow()" class="px-3 py-1.5 rounded-lg bg-[#D4AF37]/10 hover:bg-[#D4AF37]/20 border border-[#D4AF37]/40 text-[#D4AF37] text-xs font-bold transition-colors flex items-center space-x-1">
                            <span>+</span>
                            <span>Tambah Baris</span>
                        </button>
                    </div>
                </div>

                <!-- Interactive Table Container -->
                <div class="overflow-x-auto rounded-2xl border border-[#2A2A2A] bg-[#0E0E0E]">
                    <table class="w-full text-left text-xs" id="rabTable">
                        <thead class="bg-[#141414] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                            <tr>
                                <th class="py-3 px-3 text-center w-10">No</th>
                                <th class="py-3 px-3 min-w-[200px]">Uraian Kebutuhan / Pos Anggaran</th>
                                <th class="py-3 px-3 text-center w-24">Volume</th>
                                <th class="py-3 px-3 text-center w-28">Satuan</th>
                                <th class="py-3 px-3 text-right min-w-[140px]">Harga Satuan (Rp)</th>
                                <th class="py-3 px-3 text-right min-w-[140px]">Subtotal (Rp)</th>
                                <th class="py-3 px-2 text-center w-12">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="rabTableBody" class="divide-y divide-[#1F1F1F]">
                            <!-- Rows will be injected and managed by JavaScript -->
                        </tbody>
                        <tfoot class="bg-[#141414] border-t-2 border-[#2A2A2A] font-bold text-white">
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-right text-xs uppercase tracking-wider text-[#A3A3A3]">
                                    Total Estimasi Anggaran Diajukan:
                                </td>
                                <td colspan="2" class="py-4 px-4 text-right">
                                    <span id="rabFormattedTotal" class="text-base sm:text-lg font-mono font-black text-[#D4AF37]">
                                        Rp 0
                                    </span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2.5 flex items-center justify-between text-[11px] text-[#737373]">
                    <span>* Minimal 1 baris item RAB terisi. Nilai satuan harus rasional sesuai standar lokal Murung Raya.</span>
                    <button type="button" onclick="clearRabTable()" class="hover:text-rose-400 transition-colors">
                        Bersihkan Semua Baris
                    </button>
                </div>
            </div>

            <!-- SECTION 4: Unggah Berkas Proposal & Lampiran Pendukung -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#2A2A2A]">
                    <div>
                        <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-white flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">4</span>
                            <span>Dokumen Proposal & Berkas Lampiran Wajib</span>
                        </h2>
                        <p class="text-[11px] text-[#A3A3A3] mt-0.5">
                            Unggah berkas digital untuk ditinjau langsung oleh Wakil Bupati melalui penampil dokumen PDF web.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Proposal Utama PDF -->
                    <div class="p-5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/40 transition-colors relative">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <label class="block text-xs font-bold text-white">
                                    Dokumen Proposal Utama (PDF)
                                </label>
                                <span class="text-[11px] text-[#A3A3A3] block mt-0.5">
                                    Proposal lengkap bertandatangan & cap stempel resmi (Maks 10 MB)
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-[#D4AF37]/10 text-[#D4AF37] border border-[#D4AF37]/30 uppercase">
                                PDF Utama
                            </span>
                        </div>
                        <input type="file" name="file_proposal" id="file_proposal" accept=".pdf" onchange="previewFileName(this, 'badge_proposal')"
                               class="w-full text-xs text-[#A3A3A3] file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                        <div id="badge_proposal" class="hidden mt-2 text-[11px] text-emerald-400 font-mono truncate"></div>
                        @error('file_proposal') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- KTP Penanggung Jawab -->
                    <div class="p-5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/40 transition-colors relative">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <label class="block text-xs font-bold text-white">
                                    KTP Penanggung Jawab
                                </label>
                                <span class="text-[11px] text-[#A3A3A3] block mt-0.5">
                                    Scan / Foto KTP Ketua atau Pemohon (PDF/JPG/PNG, Maks 5 MB)
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A] uppercase">
                                Lampiran 1
                            </span>
                        </div>
                        <input type="file" name="lampiran_ktp" id="lampiran_ktp" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFileName(this, 'badge_ktp')"
                               class="w-full text-xs text-[#A3A3A3] file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                        <div id="badge_ktp" class="hidden mt-2 text-[11px] text-emerald-400 font-mono truncate"></div>
                        @error('lampiran_ktp') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Legalitas Lembaga / SK -->
                    <div class="p-5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/40 transition-colors relative">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <label class="block text-xs font-bold text-white">
                                    Legalitas Lembaga / SK Kepengurusan
                                </label>
                                <span class="text-[11px] text-[#A3A3A3] block mt-0.5">
                                    SK Organisasi / Izin Kemenkumham / Surat Domisili (Maks 10 MB)
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A] uppercase">
                                Lampiran 2
                            </span>
                        </div>
                        <input type="file" name="lampiran_organisasi" id="lampiran_organisasi" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFileName(this, 'badge_organisasi')"
                               class="w-full text-xs text-[#A3A3A3] file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                        <div id="badge_organisasi" class="hidden mt-2 text-[11px] text-emerald-400 font-mono truncate"></div>
                        @error('lampiran_organisasi') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Buku Rekening Bank -->
                    <div class="p-5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/40 transition-colors relative">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <label class="block text-xs font-bold text-white">
                                    Buku Rekening Penampung
                                </label>
                                <span class="text-[11px] text-[#A3A3A3] block mt-0.5">
                                    Scan halaman depan buku rekening resmi lembaga (Maks 5 MB)
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A] uppercase">
                                Lampiran 3
                            </span>
                        </div>
                        <input type="file" name="lampiran_rekening" id="lampiran_rekening" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFileName(this, 'badge_rekening')"
                               class="w-full text-xs text-[#A3A3A3] file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1F1F1F] file:text-[#D4AF37] file:border file:border-[#D4AF37]/30 hover:file:bg-[#252525] cursor-pointer">
                        <div id="badge_rekening" class="hidden mt-2 text-[11px] text-emerald-400 font-mono truncate"></div>
                        @error('lampiran_rekening') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Bottom Action Navigation -->
            <div class="border-t border-[#2A2A2A] pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('proposals.index') }}" class="px-5 py-2.5 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                    ← Batalkan Pengisian
                </a>

                <!-- Trigger Button for Anti-Revisi Checklist Modal -->
                <button type="button" onclick="openChecklistModal()"
                        class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#D4AF37] to-[#E6C65C] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black rounded-xl text-xs sm:text-sm shadow-xl shadow-[#D4AF37]/20 transition-all hover:scale-105 flex items-center justify-center space-x-2">
                    <span>Lanjutkan ke Cek Ketelitian Mandiri (Anti-Revisi)</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL GERBANG CEK KETELITIAN MANDIRI (ANTI-REVISI SIPPRO MURA)             -->
<!-- Sesuai PRD Bab 2.2, 4.1, 7 Pekan 2-3, & Bab 8 No. 4 & 5                   -->
<!-- ========================================================================= -->
<div id="modalChecklistKetelitian" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-black/85 backdrop-blur-md transition-opacity" onclick="closeChecklistModal()"></div>

    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 text-center">
        <div class="relative transform overflow-hidden rounded-3xl bg-[#151515] border-2 border-[#D4AF37]/40 text-left shadow-2xl transition-all w-full max-w-2xl my-8">
            
            <!-- Modal Header -->
            <div class="p-6 bg-[#181818] border-b border-[#2A2A2A] flex items-start justify-between">
                <div class="flex items-start space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-[#0B0B0B] border border-[#D4AF37]/50 flex items-center justify-center text-xl shrink-0 text-[#D4AF37] shadow-inner">
                        🛡️
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40">
                                Gerbang Anti-Revisi
                            </span>
                            <span class="text-[11px] text-[#737373]">SOP Setda Kab. Murung Raya</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-white">
                            Checklist Ketelitian Mandiri Pengusul
                        </h3>
                        <p class="text-xs text-[#A3A3A3] mt-0.5">
                            Konfirmasi 5 butir kelengkapan di bawah ini sebelum proposal diteruskan ke meja telaah Wakil Bupati.
                        </p>
                    </div>
                </div>

                <button type="button" onclick="closeChecklistModal()" class="rounded-xl p-2 text-[#737373] hover:text-white hover:bg-[#252525] transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 sm:p-7 space-y-6">
                <!-- Progress Indicator -->
                <div class="p-4 rounded-2xl bg-[#0B0B0B] border border-[#2A2A2A]">
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="font-bold text-white flex items-center space-x-1.5">
                            <span>Status Verifikasi Mandiri:</span>
                            <span id="checklistLabel" class="text-amber-400 font-mono font-bold">Belum Lengkap</span>
                        </span>
                        <span id="checklistCounter" class="font-mono font-black text-sm text-[#D4AF37]">
                            0 / 5 Butir
                        </span>
                    </div>
                    <div class="w-full bg-[#1F1F1F] rounded-full h-2.5 overflow-hidden">
                        <div id="checklistProgressBar" class="bg-gradient-to-r from-[#997C21] to-[#D4AF37] h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- 5 Checklist Cards -->
                <div class="space-y-3" id="checklistContainer">
                    <!-- Item 1: Identitas -->
                    <label class="check-item flex items-start space-x-3.5 p-3.5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/50 cursor-pointer transition-all">
                        <input type="checkbox" id="chk_1" onchange="updateChecklistProgress()"
                               class="mt-1 w-4 h-4 rounded text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37] focus:ring-offset-0 cursor-pointer">
                        <div class="text-xs">
                            <span class="font-bold text-white block">1. Identitas & Legalitas Penanggung Jawab Valid</span>
                            <span class="text-[#A3A3A3] text-[11px] block mt-0.5 leading-relaxed">
                                Data KTP penanggung jawab dan bukti legalitas organisasi/surat domisili telah terlampir jelas dan berdomisili di Kabupaten Murung Raya.
                            </span>
                        </div>
                    </label>

                    <!-- Item 2: Akurasi RAB -->
                    <label class="check-item flex items-start space-x-3.5 p-3.5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/50 cursor-pointer transition-all">
                        <input type="checkbox" id="chk_2" onchange="updateChecklistProgress()"
                               class="mt-1 w-4 h-4 rounded text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37] focus:ring-offset-0 cursor-pointer">
                        <div class="text-xs">
                            <span class="font-bold text-white block">2. Kewajaran & Ketelitian Rincian Anggaran (RAB)</span>
                            <span class="text-[#A3A3A3] text-[11px] block mt-0.5 leading-relaxed">
                                Pos-pos belanja pada tabel RAB telah diperiksa, nilai satuan wajar sesuai harga pasar Puruk Cahu, dan bebas dari pos biaya fiktif.
                            </span>
                        </div>
                    </label>

                    <!-- Item 3: Legalitas Proposal PDF -->
                    <label class="check-item flex items-start space-x-3.5 p-3.5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/50 cursor-pointer transition-all">
                        <input type="checkbox" id="chk_3" onchange="updateChecklistProgress()"
                               class="mt-1 w-4 h-4 rounded text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37] focus:ring-offset-0 cursor-pointer">
                        <div class="text-xs">
                            <span class="font-bold text-white block">3. Dokumen Proposal Utama Bertandatangan & Cap Basah</span>
                            <span class="text-[#A3A3A3] text-[11px] block mt-0.5 leading-relaxed">
                                File proposal utama (PDF) telah ditandatangani oleh pimpinan/ketua panitia serta dibubuhi stempel/cap resmi organisasi.
                            </span>
                        </div>
                    </label>

                    <!-- Item 4: Rekening Bank -->
                    <label class="check-item flex items-start space-x-3.5 p-3.5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/50 cursor-pointer transition-all">
                        <input type="checkbox" id="chk_4" onchange="updateChecklistProgress()"
                               class="mt-1 w-4 h-4 rounded text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37] focus:ring-offset-0 cursor-pointer">
                        <div class="text-xs">
                            <span class="font-bold text-white block">4. Keabsahan Nomor Rekening Penyaluran</span>
                            <span class="text-[#A3A3A3] text-[11px] block mt-0.5 leading-relaxed">
                                Rekening bank yang terdaftar aktif atas nama lembaga/organisasi pemohon resmi (bukan rekening perorangan yang tidak berwenang).
                            </span>
                        </div>
                    </label>

                    <!-- Item 5: Pakta Integritas -->
                    <label class="check-item flex items-start space-x-3.5 p-3.5 rounded-2xl bg-[#181818] border border-[#2A2A2A] hover:border-[#D4AF37]/50 cursor-pointer transition-all">
                        <input type="checkbox" id="chk_5" onchange="updateChecklistProgress()"
                               class="mt-1 w-4 h-4 rounded text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37] focus:ring-offset-0 cursor-pointer">
                        <div class="text-xs">
                            <span class="font-bold text-white block">5. Pakta Integritas & Kesiapan Tanggung Jawab Mutlak</span>
                            <span class="text-[#A3A3A3] text-[11px] block mt-0.5 leading-relaxed">
                                Saya bersedia mempertanggungjawabkan kebenaran seluruh dokumen di hadapan hukum dan siap menerima pemeriksaan fisik/audit Setda Kab. Murung Raya.
                            </span>
                        </div>
                    </label>
                </div>

                <div class="p-3.5 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A] text-[11px] text-[#A3A3A3] flex items-center space-x-2">
                    <span class="text-base text-[#D4AF37]">ℹ️</span>
                    <span>Proposal yang telah lolos verifikasi akan langsung diterbitkan <strong>Nomor Registrasi</strong> resmi dan masuk ke antrean digital Wakil Bupati.</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-5 sm:p-6 bg-[#111111] border-t border-[#2A2A2A] flex flex-col sm:flex-row justify-between items-center gap-3">
                <button type="button" onclick="closeChecklistModal()"
                        class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                    Periksa Kembali Isian Form
                </button>

                <!-- Tombol ini terkunci (disabled) sesuai PRD Bab 8 No. 4 & 5 hingga ke-5 checkbox dicentang -->
                <button type="button" id="btnSubmitProposal" onclick="submitFinalProposal()" disabled
                        class="w-full sm:w-auto px-7 py-3 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-300 opacity-40 cursor-not-allowed bg-[#2A2A2A] text-[#737373]">
                    Centang 5 Butir Verifikasi Mandiri (0/5)
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /**
     * Manajemen Tabel Perhitungan Dinamis RAB (Rencana Anggaran Biaya)
     * SIPPRO MURA - Tahap 2 & 3
     */
    let rabRows = [];

    // Format Rupiah Helper
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    // Inisialisasi awal saat load
    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada data lama (old value) dari session validation failure
        const existingRabJson = document.getElementById('rincian_rab_json').value;
        if (existingRabJson) {
            try {
                const parsed = JSON.parse(existingRabJson);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    rabRows = parsed;
                    renderRabTable();
                    return;
                }
            } catch (e) {
                console.warn('Gagal membaca data RAB lama:', e);
            }
        }

        // Default 2 baris awal
        rabRows = [
            { item: 'Sewa Tenda & Sound System Kegiatan', volume: 1, satuan: 'paket', biaya: 5000000 },
            { item: 'Pengadaan Hadiah / Piagam & Konsumsi Panitia', volume: 1, satuan: 'paket', biaya: 10000000 }
        ];
        renderRabTable();
    });

    // Render baris-baris RAB ke tabel DOM
    function renderRabTable() {
        const tbody = document.getElementById('rabTableBody');
        tbody.innerHTML = '';

        if (rabRows.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="py-8 text-center text-[#737373] text-xs">
                        Belum ada item anggaran. Silakan klik <button type="button" onclick="addRabRow()" class="text-[#D4AF37] underline font-bold">Tambah Baris</button> atau gunakan <button type="button" onclick="loadSampleRab()" class="text-[#D4AF37] underline font-bold">Muat Format Contoh</button>.
                    </td>
                </tr>
            `;
            calculateRabTotal();
            return;
        }

        rabRows.forEach((row, index) => {
            const subtotal = (Number(row.volume) || 0) * (Number(row.biaya) || 0);
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-[#151515] transition-colors';
            tr.innerHTML = `
                <td class="py-2.5 px-3 text-center font-mono text-[#737373] text-xs">${index + 1}</td>
                <td class="py-2.5 px-3">
                    <input type="text" value="${escapeHtml(row.item || '')}" 
                           oninput="updateRabRow(${index}, 'item', this.value)"
                           placeholder="Uraian barang / belanja..."
                           class="w-full px-2.5 py-1.5 bg-[#0B0B0B] border border-[#2A2A2A] rounded-lg text-white text-xs focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none">
                </td>
                <td class="py-2.5 px-3 text-center">
                    <input type="number" min="1" step="1" value="${row.volume || 1}" 
                           oninput="updateRabRow(${index}, 'volume', this.value)"
                           class="w-20 px-2 py-1.5 bg-[#0B0B0B] border border-[#2A2A2A] rounded-lg text-white text-xs text-center font-mono focus:border-[#D4AF37] outline-none">
                </td>
                <td class="py-2.5 px-3 text-center">
                    <input type="text" value="${escapeHtml(row.satuan || 'paket')}" 
                           oninput="updateRabRow(${index}, 'satuan', this.value)"
                           placeholder="Satuan"
                           class="w-24 px-2 py-1.5 bg-[#0B0B0B] border border-[#2A2A2A] rounded-lg text-white text-xs text-center focus:border-[#D4AF37] outline-none">
                </td>
                <td class="py-2.5 px-3 text-right">
                    <input type="number" min="0" step="50000" value="${row.biaya || 0}" 
                           oninput="updateRabRow(${index}, 'biaya', this.value)"
                           class="w-32 px-2.5 py-1.5 bg-[#0B0B0B] border border-[#2A2A2A] rounded-lg text-white text-xs text-right font-mono focus:border-[#D4AF37] outline-none">
                </td>
                <td class="py-2.5 px-3 text-right font-mono font-bold text-white text-xs">
                    ${formatRupiah(subtotal)}
                </td>
                <td class="py-2.5 px-2 text-center">
                    <button type="button" onclick="deleteRabRow(${index})" title="Hapus Baris"
                            class="p-1 rounded-lg text-[#737373] hover:text-rose-400 hover:bg-[#252525] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        calculateRabTotal();
    }

    // Update single cell in memory and recalculate
    function updateRabRow(index, field, value) {
        if (!rabRows[index]) return;
        rabRows[index][field] = value;

        // Recalculate without re-rendering entire inputs to keep cursor focus
        calculateRabTotal();

        // Update the subtotal cell directly in DOM
        const tbody = document.getElementById('rabTableBody');
        const tr = tbody.children[index];
        if (tr) {
            const vol = Number(rabRows[index].volume) || 0;
            const biaya = Number(rabRows[index].biaya) || 0;
            const subtotal = vol * biaya;
            const subtotalCell = tr.children[5];
            if (subtotalCell) {
                subtotalCell.textContent = formatRupiah(subtotal);
            }
        }
    }

    // Tambah baris baru
    function addRabRow() {
        rabRows.push({
            item: '',
            volume: 1,
            satuan: 'paket',
            biaya: 0
        });
        renderRabTable();
    }

    // Hapus baris tertentu
    function deleteRabRow(index) {
        rabRows.splice(index, 1);
        renderRabTable();
    }

    // Bersihkan semua baris
    function clearRabTable() {
        if (confirm('Apakah Anda yakin ingin mengosongkan seluruh tabel RAB?')) {
            rabRows = [];
            renderRabTable();
        }
    }

    // Muat template contoh RAB
    function loadSampleRab() {
        rabRows = [
            { item: 'Sewa Gedung / Lapangan & Sound System (3 Hari)', volume: 3, satuan: 'hari', biaya: 2000000 },
            { item: 'Honor Narasumber / Wasit / Instruktur Terlatih', volume: 5, satuan: 'orang', biaya: 1500000 },
            { item: 'Hadiah Pembinaan, Trofi, dan Piagam Penghargaan', volume: 1, satuan: 'paket', biaya: 12500000 },
            { item: 'Konsumsi Peserta & Panitia Pelaksana (150 Porsi)', volume: 150, satuan: 'kotak', biaya: 35000 },
            { item: 'Spanduk, Backdrop Panggung & Dokumentasi Publikasi', volume: 1, satuan: 'paket', biaya: 2750000 }
        ];
        renderRabTable();
    }

    // Hitung total akumulasi RAB dan sinkronkan dengan total_anggaran & input JSON
    function calculateRabTotal() {
        let total = 0;
        rabRows.forEach(row => {
            const vol = Number(row.volume) || 0;
            const biaya = Number(row.biaya) || 0;
            total += (vol * biaya);
        });

        // Tampilkan format total di tabel footer
        document.getElementById('rabFormattedTotal').textContent = formatRupiah(total);

        // Update nilai total anggaran di Section 1
        const totalAnggaranInput = document.getElementById('total_anggaran');
        if (totalAnggaranInput) {
            totalAnggaranInput.value = total;
        }

        // Serialize ke JSON hidden input
        document.getElementById('rincian_rab_json').value = JSON.stringify(rabRows);
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Visual file name decorator
    function previewFileName(input, badgeId) {
        const badge = document.getElementById(badgeId);
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            badge.textContent = `✓ Berkas terpilih: ${file.name} (${sizeMb} MB)`;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    /**
     * Logika Gerbang Cek Ketelitian Mandiri (Modal Anti-Revisi)
     */
    function openChecklistModal() {
        const form = document.getElementById('proposalForm');

        // Validasi HTML5 bawaan formulir
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Validasi kelayakan RAB
        const totalAnggaran = Number(document.getElementById('total_anggaran').value) || 0;
        if (totalAnggaran < 100000) {
            alert('Perhatian: Total usulan anggaran minimal adalah Rp 100.000. Mohon lengkapi item pada Tabel Rincian Anggaran (RAB).');
            return;
        }

        // Buka modal
        const modal = document.getElementById('modalChecklistKetelitian');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Reset progress calculation
        updateChecklistProgress();
    }

    function closeChecklistModal() {
        const modal = document.getElementById('modalChecklistKetelitian');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Update Progres Checklist & Kunci / Buka Tombol Submit Final
    function updateChecklistProgress() {
        const checkboxes = [
            document.getElementById('chk_1'),
            document.getElementById('chk_2'),
            document.getElementById('chk_3'),
            document.getElementById('chk_4'),
            document.getElementById('chk_5')
        ];

        let checkedCount = 0;
        checkboxes.forEach(chk => {
            if (chk && chk.checked) checkedCount++;
        });

        // Update Counter & Progress Bar
        const counter = document.getElementById('checklistCounter');
        const progressBar = document.getElementById('checklistProgressBar');
        const label = document.getElementById('checklistLabel');
        const btnSubmit = document.getElementById('btnSubmitProposal');

        const percentage = (checkedCount / 5) * 100;
        counter.textContent = `${checkedCount} / 5 Butir`;
        progressBar.style.width = `${percentage}%`;

        if (checkedCount === 5) {
            label.textContent = 'Lengkap & Siap Kirim';
            label.className = 'text-emerald-400 font-mono font-bold';

            // AKTIFKAN TOMBOL SUBMIT SESUAI PRD BAB 8 NO. 4 & 5
            btnSubmit.disabled = false;
            btnSubmit.className = 'w-full sm:w-auto px-8 py-3.5 rounded-xl text-xs sm:text-sm font-black transition-all duration-300 bg-gradient-to-r from-[#D4AF37] to-[#E6C65C] hover:scale-105 text-[#0B0B0B] shadow-lg shadow-[#D4AF37]/30 cursor-pointer animate-pulse';
            btnSubmit.innerHTML = '✓ Kirimkan Proposal ke Wakil Bupati Sekarang';
        } else {
            label.textContent = 'Belum Lengkap';
            label.className = 'text-amber-400 font-mono font-bold';

            // KUNCI TOMBOL SUBMIT (DISABLED)
            btnSubmit.disabled = true;
            btnSubmit.className = 'w-full sm:w-auto px-7 py-3 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-300 opacity-40 cursor-not-allowed bg-[#2A2A2A] text-[#737373]';
            btnSubmit.innerHTML = `Centang 5 Butir Verifikasi Mandiri (${checkedCount}/5)`;
        }
    }

    // Eksekusi Submit Form setelah semua lolos verifikasi
    function submitFinalProposal() {
        const btnSubmit = document.getElementById('btnSubmitProposal');
        if (btnSubmit.disabled) return;

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'Memproses Pengiriman Proposal...';

        // Pastikan RAB tersinkronkan sebelum submit
        calculateRabTotal();

        // Submit form
        document.getElementById('proposalForm').submit();
    }
</script>
@endpush
@endsection
