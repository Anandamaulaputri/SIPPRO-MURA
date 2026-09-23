@extends('layouts.app')

@section('content')
<div class="py-14 px-4 sm:px-6 lg:px-8 max-w-md mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-[#D4AF37] via-[#C99E2E] to-[#997C21] text-[#0B0B0B] font-black text-2xl shadow-lg shadow-[#D4AF37]/20 mb-3.5">
            SP
        </div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight">Masuk ke Portal SIPPRO</h2>
        <p class="text-xs text-[#A3A3A3] mt-1">Sistem Informasi Pelayanan Proposal Kab. Murung Raya</p>
    </div>

    <!-- Quick Demo Logins Box -->
    <div class="mb-6 p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-md">
        <div class="flex items-center justify-between mb-2.5">
            <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider flex items-center space-x-1.5">
                <span>⚡ Akses Cepat Mode Demo</span>
            </span>
            <span class="text-[10px] bg-[#D4AF37]/15 text-[#E6C65C] border border-[#D4AF37]/40 font-semibold px-2 py-0.5 rounded-full">1-Klik Masuk</span>
        </div>
        <p class="text-xs text-[#A3A3A3] mb-3">
            Pilih peran di bawah ini untuk langsung masuk tanpa perlu mengetik kata sandi:
        </p>
        <div class="grid grid-cols-1 gap-2">
            <a href="{{ route('login.quick', 'wabup') }}" class="flex items-center justify-between px-3.5 py-2.5 bg-[#0B0B0B] hover:bg-[#1A1A1A] border border-[#2A2A2A] hover:border-[#D4AF37]/50 rounded-xl text-xs font-bold text-white transition-all group">
                <span class="flex items-center space-x-2.5">
                    <span class="w-6 h-6 rounded-lg bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[11px]">⭐</span>
                    <span class="group-hover:text-[#D4AF37] transition-colors">Wakil Bupati (Pimpinan Penelaah)</span>
                </span>
                <span class="text-[11px] text-[#D4AF37] group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </a>

            <a href="{{ route('login.quick', 'admin') }}" class="flex items-center justify-between px-3.5 py-2.5 bg-[#0B0B0B] hover:bg-[#1A1A1A] border border-[#2A2A2A] hover:border-blue-500/50 rounded-xl text-xs font-bold text-white transition-all group">
                <span class="flex items-center space-x-2.5">
                    <span class="w-6 h-6 rounded-lg bg-blue-950/70 text-blue-400 border border-blue-800/40 flex items-center justify-center text-[11px]">🛡️</span>
                    <span class="group-hover:text-blue-300 transition-colors">Staf Administrasi Pimpinan (Admin)</span>
                </span>
                <span class="text-[11px] text-blue-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </a>

            <a href="{{ route('login.quick', 'pengusul') }}" class="flex items-center justify-between px-3.5 py-2.5 bg-[#0B0B0B] hover:bg-[#1A1A1A] border border-[#2A2A2A] hover:border-emerald-500/50 rounded-xl text-xs font-bold text-white transition-all group">
                <span class="flex items-center space-x-2.5">
                    <span class="w-6 h-6 rounded-lg bg-emerald-950/70 text-emerald-400 border border-emerald-800/40 flex items-center justify-center text-[11px]">📝</span>
                    <span class="group-hover:text-emerald-300 transition-colors">Pengusul (Masyarakat / Ormas)</span>
                </span>
                <span class="text-[11px] text-emerald-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Standard Login Form -->
    <div class="bg-[#151515] p-6 sm:p-7 rounded-2xl border border-[#2A2A2A] shadow-xl">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                    Alamat Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="nama@email.com"
                       class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all @error('email') border-rose-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">
                    Kata Sandi
                </label>
                <input id="password" type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center space-x-2 cursor-pointer text-[#A3A3A3]">
                    <input type="checkbox" name="remember" class="rounded border-[#2A2A2A] bg-[#0B0B0B] text-[#D4AF37] focus:ring-[#D4AF37]">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-[1.02] mt-2">
                Masuk ke Akun
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-[#2A2A2A] text-center text-xs text-[#A3A3A3]">
            Belum memiliki akun pengusul? 
            <a href="{{ route('register') }}" class="font-bold text-[#D4AF37] hover:underline">
                Daftar sekarang
            </a>
        </div>
    </div>
</div>
@endsection
