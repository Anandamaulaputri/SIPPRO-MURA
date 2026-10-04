@extends('layouts.public')

@section('content')
<div class="relative bg-[#0B0B0B] text-white py-16 sm:py-24 min-h-[calc(100vh-250px)] flex flex-col justify-center items-center">
    <!-- Subtle ambient glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-72 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
        <!-- Breadcrumb / Back -->
        <div class="mb-6 flex justify-center">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

        <!-- Heading -->
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#151515] border border-[#D4AF37]/40 text-[#D4AF37] text-xs font-bold uppercase tracking-wider mb-4">
            <span>🔍 Layanan Transparansi Publik</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight uppercase leading-tight font-serif mb-3">
            Pelacakan Status Usulan <span class="text-[#E6C65C]">Proposal</span>
        </h1>
        <p class="text-xs sm:text-sm text-[#A3A3A3] max-w-xl mx-auto leading-relaxed mb-8">
            Pantau secara langsung alur disposisi, catatan pimpinan, dan status terkini surat permohonan Anda tanpa perlu datang ke kantor Setda.
        </p>

        <!-- Kotak Pencarian Lacak Proposal -->
        <div class="max-w-xl mx-auto w-full mb-8">
            <form action="{{ route('tracking.search') }}" method="GET" class="relative flex flex-col sm:flex-row items-center gap-2 p-2.5 rounded-2xl bg-[#151515] border border-[#D4AF37]/50 shadow-2xl shadow-[#D4AF37]/10 focus-within:border-[#D4AF37] transition-all">
                <div class="flex items-center space-x-3 px-3 w-full">
                    <span class="text-[#D4AF37] text-xl">📄</span>
                    <input type="text" name="nomor_registrasi" value="{{ old('nomor_registrasi') }}"
                           placeholder="Masukkan Nomor Registrasi (contoh: PROP-202610-0001)..."
                           required autofocus
                           class="w-full bg-transparent text-white placeholder-[#737373] text-xs sm:text-sm font-mono outline-none py-2">
                </div>
                <button type="submit"
                        class="w-full sm:w-auto px-7 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#C5A028] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black text-xs uppercase tracking-wider shrink-0 transition-all hover:scale-105 shadow-md">
                    Cari Proposal
                </button>
            </form>
        </div>

        <!-- Panduan Informasi -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto text-left">
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A]">
                <div class="text-[#D4AF37] text-lg mb-1.5">1️⃣</div>
                <h4 class="text-xs font-bold text-white mb-1">Cek Nomor Registrasi</h4>
                <p class="text-[11px] text-[#737373] leading-relaxed">
                    Dapatkan kode registrasi unik berformat <span class="font-mono text-[#A3A3A3]">PROP-YYYYMM-XXXX</span> setelah pengajuan berhasil.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A]">
                <div class="text-[#D4AF37] text-lg mb-1.5">2️⃣</div>
                <h4 class="text-xs font-bold text-white mb-1">Status Real-time</h4>
                <p class="text-[11px] text-[#737373] leading-relaxed">
                    Lihat timeline apakah berkas sedang ditelaah staf, diproses Wakil Bupati, atau sudah disetujui.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A]">
                <div class="text-[#D4AF37] text-lg mb-1.5">3️⃣</div>
                <h4 class="text-xs font-bold text-white mb-1">Catatan Arahan</h4>
                <p class="text-[11px] text-[#737373] leading-relaxed">
                    Instruksi kebijakan pimpinan daerah dapat dibaca secara transparan oleh pihak pemohon.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
