@extends('layouts.app')

@section('content')
<div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Dasbor</span>
        </a>

        <span class="text-xs text-[#737373] font-mono">ID Pengguna: #{{ $user->id }}</span>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-950/60 border border-emerald-800/60 text-emerald-300 text-xs flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-[#151515] rounded-3xl border border-[#2A2A2A] shadow-xl p-6 sm:p-10">
        <div class="border-b border-[#2A2A2A] pb-6 mb-8">
            <div class="flex items-center space-x-2 mb-2">
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40">
                    Data Master Pengusul
                </span>
                <span class="text-xs text-[#737373]">{{ $user->role === 'pengusul' ? 'Akun Pemohon Resmi' : 'Aparatur Sipil Negara' }}</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Profil Pengusul & Rekening Penyaluran</h1>
            <p class="text-xs text-[#A3A3A3] mt-1.5 leading-relaxed">
                Kelola informasi identitas lembaga pemohon dan nomor rekening bank penyaluran resmi untuk keperluan pencatatan berkas proposal masuk Setda Kab. Murung Raya.
            </p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Data Akun & Kontak Penanggung Jawab -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px] font-black">1</span>
                    <span>Identitas Penanggung Jawab</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Lengkap Penanggung Jawab *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Alamat Email Terdaftar</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full px-3.5 py-2.5 bg-[#1F1F1F] border border-[#2A2A2A] text-[#737373] rounded-xl text-xs sm:text-sm cursor-not-allowed">
                        <p class="text-[10px] text-[#737373] mt-1">Alamat email digunakan untuk autentikasi sistem dan tidak dapat diubah sembarangan.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nomor WhatsApp / Kontak Aktif *</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required
                               placeholder="Contoh: 085249001122"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('no_telepon') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Lembaga & Domisili Murung Raya -->
            <div class="border-t border-[#2A2A2A] pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px] font-black">2</span>
                    <span>Data Lembaga / Organisasi & Domisili</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Lembaga / Komunitas / Kelompok</label>
                        <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $profile->nama_lembaga) }}"
                               placeholder="Contoh: Sanggar Belia Mura / Karang Taruna Bersatu"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nomor Identitas SK / KTP / Izin</label>
                        <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas', $profile->nomor_identitas) }}"
                               placeholder="Contoh: 621201... / SK-01/MURA/2026"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Alamat Domisili di Kab. Murung Raya</label>
                        <textarea name="alamat" rows="2"
                                  placeholder="Contoh: Jl. Jenderal Sudirman No. 45, RT 04, Puruk Cahu, Kec. Murung, Kab. Murung Raya"
                                  class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">{{ old('alamat', $profile->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Rekening Bank Penyaluran Resmi -->
            <div class="border-t border-[#2A2A2A] pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px] font-black">3</span>
                    <span>Rekening Penyaluran Dana Bantuan</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Bank</label>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank', $profile->nama_bank ?? 'Bank Kalteng Cabang Puruk Cahu') }}"
                               placeholder="Contoh: Bank Kalteng / BRI"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $profile->nomor_rekening) }}"
                               placeholder="Contoh: 100-02-005432-1"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm font-mono focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Atas Nama Rekening</label>
                        <input type="text" name="nama_pemilik_rekening" value="{{ old('nama_pemilik_rekening', $profile->nama_pemilik_rekening) }}"
                               placeholder="Contoh: Karang Taruna Mura"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t border-[#2A2A2A] pt-6 flex justify-end items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                    Kembali
                </a>
                <button type="submit" class="px-7 py-3 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-105">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
