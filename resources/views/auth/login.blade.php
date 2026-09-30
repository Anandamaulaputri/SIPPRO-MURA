@extends('layouts.app')

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-md mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-[#D4AF37] via-[#C99E2E] to-[#997C21] text-[#0B0B0B] font-black text-2xl shadow-lg shadow-[#D4AF37]/20 mb-3.5">
            SP
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Masuk ke SIPPRO MURA</h1>
        <p class="text-xs text-[#A3A3A3] mt-1.5">Sistem Informasi Pelayanan Proposal Murung Raya</p>
    </div>

    <!-- Standard Login Card -->
    <div class="bg-[#151515] p-6 sm:p-8 rounded-2xl border border-[#2A2A2A] shadow-xl">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                    Email <span class="text-rose-400">*</span>
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="nama@email.com"
                       class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all @error('email') border-rose-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                    Password <span class="text-rose-400">*</span>
                </label>
                <input id="password" type="password" name="password" required
                       placeholder="Masukkan password"
                       class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center space-x-2 cursor-pointer text-[#A3A3A3]">
                    <input type="checkbox" name="remember" class="rounded border-[#2A2A2A] bg-[#0B0B0B] text-[#D4AF37] focus:ring-[#D4AF37]">
                    <span>Ingat saya</span>
                </label>
            </div>

            <!-- CTA Utama -->
            <button type="submit" class="w-full py-3 px-4 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-[1.02] mt-2">
                Masuk
            </button>
        </form>

        <!-- Footer Link -->
        <div class="mt-6 pt-5 border-t border-[#2A2A2A] text-center text-xs text-[#A3A3A3]">
            Belum memiliki akun? 
            <a href="{{ route('register') }}" class="font-bold text-[#D4AF37] hover:underline">
                Daftarkan Akun
            </a>
        </div>
    </div>
</div>
@endsection
