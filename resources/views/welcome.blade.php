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
        <p class="mt-6 mb-10 text-sm sm:text-base lg:text-lg text-[#A3A3A3] max-w-2xl mx-auto font-normal leading-relaxed">
            Ajukan dan pantau proses proposal secara mudah, transparan, dan terstruktur melalui SIPPRO MURA.
        </p>

        <!-- 2 CTA Utama -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 max-w-xs sm:max-w-md mx-auto">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-black text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-105 text-center">
                    Buka Dasbor Saya &rarr;
                </a>
            @else
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-black text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-105 text-center">
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
