@extends('layouts.app')

@section('content')
<!-- Focused Executive Hero Section -->
<div class="relative bg-[#0B0B0B] text-white py-20 sm:py-28 lg:py-36 min-h-[calc(100vh-280px)] flex flex-col justify-center items-center border-b border-[#2A2A2A]">
    <!-- Subtle ambient background accent -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl h-64 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center my-auto">
        <!-- Headline Utama (Tepat 2 Baris) -->
        <h1 class="text-2xl sm:text-4xl lg:text-[44px] xl:text-[48px] font-bold text-white tracking-normal leading-[1.25] max-w-5xl mx-auto font-serif">
            Sistem Informasi Pelayanan Proposal <br class="hidden sm:inline">
            <span class="text-[#E6C65C] italic sm:not-italic font-extrabold">Murung Raya</span>
        </h1>

        <!-- Subheadline -->
        <p class="mt-6 mb-10 sm:mt-7 sm:mb-12 text-sm sm:text-base lg:text-lg text-[#A3A3A3] max-w-2xl mx-auto font-normal leading-relaxed">
            Ajukan dan pantau proses proposal secara mudah, transparan, dan terstruktur melalui&nbsp; <strong class="text-white font-semibold">SIPPRO MURA</strong>.
        </p>

        <!-- Call to Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-3.5 sm:gap-4 max-w-xs sm:max-w-md mx-auto">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-sm shadow-md shadow-[#D4AF37]/15 transition-all hover:scale-[1.01] text-center">
                    Buka Dasbor Saya &rarr;
                </a>
            @else
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-sm shadow-md shadow-[#D4AF37]/15 transition-all hover:scale-[1.01] text-center">
                    Daftar Akun
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-[#151515] hover:bg-[#1E1E1E] text-[#D4AF37] hover:text-[#E6C65C] border border-[#2A2A2A] hover:border-[#D4AF37]/50 font-semibold text-sm transition-all text-center">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
