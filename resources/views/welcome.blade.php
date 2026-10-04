@extends('layouts.public')

@section('content')
<div class="relative bg-[#0B0B0B] text-white py-24 sm:py-32 lg:py-40 min-h-[calc(100vh-250px)] flex flex-col justify-center items-center">
    <!-- Subtle ambient glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-72 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center my-auto">
        <!-- Hero Utama -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight uppercase leading-tight font-serif">
            Sistem Informasi Pelayanan<br>
            Proposal<br>
            <span class="text-[#E6C65C]">Murung Raya</span>
        </h1>

        <!-- Subheadline -->
        <p class="mt-6 mb-8 text-sm sm:text-base lg:text-lg text-[#A3A3A3] max-w-2xl mx-auto font-normal leading-relaxed">
            Ajukan dan pantau proses proposal secara mudah, transparan, dan terstruktur melalui SIPPRO MURA.
        </p>

        <!-- Kotak Pelacakan Berkas Publik -->
        <div class="mb-10 max-w-xl mx-auto w-full">
            <form action="{{ route('tracking.search') }}" method="GET" class="relative flex flex-col sm:flex-row items-center gap-2 p-2 rounded-2xl bg-[#151515] border border-[#D4AF37]/50 shadow-2xl shadow-[#D4AF37]/10 focus-within:border-[#D4AF37] transition-all">
                <div class="flex items-center space-x-3 px-3 w-full">
                    <span class="text-[#D4AF37] text-lg">🔍</span>
                    <input type="text" name="nomor_registrasi" value="{{ old('nomor_registrasi') }}"
                           placeholder="Lacak usulan: Masukkan Nomor Registrasi (contoh: PROP-202610-0001)..."
                           required
                           class="w-full bg-transparent text-white placeholder-[#737373] text-xs sm:text-sm font-mono outline-none py-2">
                </div>
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#C5A028] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black text-xs uppercase tracking-wider shrink-0 transition-all hover:scale-105 shadow-md">
                    Lacak Usulan
                </button>
            </form>
            <p class="text-[11px] text-[#737373] mt-2.5 text-center flex items-center justify-center space-x-1.5">
                <span>💡</span>
                <span>Masyarakat & perwakilan ormas dapat memantau status disposisi surat tanpa harus login.</span>
            </p>
        </div>

        <!-- 2 CTA Utama -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 max-w-xs sm:max-w-md mx-auto pt-4 border-t border-[#1F1F1F]">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-black text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-105 text-center">
                    Buka Dasbor Saya &rarr;
                </a>
            @else
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#1A1A1A] hover:bg-[#222222] text-white hover:text-[#E6C65C] border border-[#2A2A2A] hover:border-[#D4AF37]/50 font-bold text-sm transition-all text-center">
                    Daftarkan Akun
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#151515] hover:bg-[#1E1E1E] text-white hover:text-[#E6C65C] border border-[#2A2A2A] hover:border-[#D4AF37]/50 font-bold text-sm transition-all text-center">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
