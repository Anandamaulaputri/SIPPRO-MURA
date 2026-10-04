@extends('layouts.app')

@section('content')
<div class="py-8 sm:py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Top Bar -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 text-xs sm:text-sm font-semibold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>

        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#151515] border border-[#2A2A2A] text-[#D4AF37] font-mono">
                ID Pengguna: #{{ $user->id }}
            </span>
        </div>
    </div>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 mb-2.5">
            <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40">
                Profil Pengusul
            </span>
            <span class="text-xs text-[#737373] font-medium">Pemerintah Kabupaten Murung Raya</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
            Profil & Rekening
        </h1>
        <p class="text-sm text-[#A3A3A3] mt-2 max-w-2xl leading-relaxed">
            Kelola data identitas dan rekening yang digunakan untuk pengajuan proposal.
        </p>
    </div>

    <!-- Alert Kesalahan Validasi -->
    @if($errors->any())
        <div class="mb-8 p-5 rounded-2xl bg-rose-950/60 border border-rose-800/60 text-rose-300 text-sm flex flex-col space-y-2 shadow-lg shadow-black/40">
            <div class="flex items-center space-x-2.5 font-bold text-rose-200">
                <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Mohon periksa kembali isian formulir:</span>
            </div>
            <ul class="list-disc list-inside pl-6 space-y-1 text-xs text-rose-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- ============================================================ --}}
        {{-- CARD 1: INFORMASI AKUN --}}
        {{-- ============================================================ --}}
        <div class="p-6 sm:p-8 rounded-[18px] bg-[#151515] border border-[#2A2A2A] shadow-xl">
            <!-- Header Card -->
            <div class="border-b border-[#2A2A2A] pb-4 mb-6 flex items-start space-x-3.5">
                <span class="px-2.5 py-1 rounded-lg bg-[#0B0B0B] border border-[#D4AF37]/40 text-[#D4AF37] font-mono text-xs font-black tracking-wider shadow-inner shrink-0 mt-0.5">
                    01
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-white tracking-wide uppercase">
                        Informasi Akun
                    </h2>
                    <p class="text-xs sm:text-sm text-[#A3A3A3] mt-1 leading-relaxed">
                        Data akun dan kontak penanggung jawab yang terdaftar di SIPPRO MURA.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Nama Lengkap <span class="text-[#D4AF37]">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           placeholder="Contoh: Budi Santoso, S.Pd"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-xs text-[#808080] mt-1.5">Nama lengkap penanggung jawab atau pemohon.</p>
                    @error('name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email (Read-Only) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0]">
                            Email
                        </label>
                        <span class="text-[11px] text-[#A3A3A3] bg-[#0E0E0E] px-2 py-0.5 rounded border border-[#2A2A2A] font-mono">Terkunci</span>
                    </div>
                    <div class="relative">
                        <input type="email" value="{{ $user->email }}" disabled readonly
                               class="w-full px-4 py-3 bg-[#121212] border border-[#222222] text-[#888888] rounded-xl text-sm cursor-not-allowed select-none">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-[#555555]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-[#808080] mt-1.5">Email terikat dengan autentikasi akun dan tidak dapat diubah.</p>
                </div>

                <!-- Nomor WhatsApp / Telepon -->
                <div class="md:col-span-2">
                    <label for="no_telepon" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Nomor WhatsApp / Telepon <span class="text-[#D4AF37]">*</span>
                    </label>
                    <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required
                           placeholder="Contoh: 085249001122"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-xs text-[#808080] mt-1.5">Nomor aktif untuk keperluan konfirmasi dan koordinasi administrasi proposal.</p>
                    @error('no_telepon') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- CARD 2: IDENTITAS PEMOHON --}}
        {{-- ============================================================ --}}
        <div class="p-6 sm:p-8 rounded-[18px] bg-[#151515] border border-[#2A2A2A] shadow-xl">
            <!-- Header Card -->
            <div class="border-b border-[#2A2A2A] pb-4 mb-6 flex items-start space-x-3.5">
                <span class="px-2.5 py-1 rounded-lg bg-[#0B0B0B] border border-[#D4AF37]/40 text-[#D4AF37] font-mono text-xs font-black tracking-wider shadow-inner shrink-0 mt-0.5">
                    02
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-white tracking-wide uppercase">
                        Identitas Pemohon
                    </h2>
                    <p class="text-xs sm:text-sm text-[#A3A3A3] mt-1 leading-relaxed">
                        Lengkapi identitas pemohon serta Kontak & Alamat Domisili di wilayah Kabupaten Murung Raya.
                    </p>
                </div>
            </div>

            <!-- Pilihan Jenis Pemohon -->
            <div class="mb-6">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-3">
                    Jenis Pemohon <span class="text-[#D4AF37]">*</span>
                </label>

                @php
                    $currentJenis = old('jenis_pemohon', $profile->jenis_pemohon ?? 'organisasi');
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Opsi Organisasi / Lembaga -->
                    <label id="card_organisasi" class="relative flex items-start p-5 rounded-2xl border cursor-pointer transition-all duration-200 {{ $currentJenis === 'organisasi' ? 'bg-[#D4AF37]/5 border-[#D4AF37] shadow-sm shadow-[#D4AF37]/10' : 'bg-[#0E0E0E] border-[#2A2A2A] hover:border-[#383838]' }}">
                        <input type="radio" name="jenis_pemohon" value="organisasi" class="sr-only" {{ $currentJenis === 'organisasi' ? 'checked' : '' }} onchange="switchJenisPemohon('organisasi')">
                        <div class="flex items-center space-x-3.5 w-full">
                            <div id="radio_dot_organisasi" class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 {{ $currentJenis === 'organisasi' ? 'border-[#D4AF37]' : 'border-[#444444]' }}">
                                <div id="radio_inner_organisasi" class="w-2.5 h-2.5 rounded-full bg-[#D4AF37] {{ $currentJenis === 'organisasi' ? '' : 'hidden' }}"></div>
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-bold text-white">
                                    Organisasi / Lembaga
                                </div>
                                <p class="text-xs text-[#9E9E9E] mt-1 leading-relaxed">
                                    Lembaga kemasyarakatan, yayasan, ormas, atau perkumpulan berlegalitas.
                                </p>
                            </div>
                        </div>
                    </label>

                    <!-- Opsi Perorangan / Masyarakat -->
                    <label id="card_perorangan" class="relative flex items-start p-5 rounded-2xl border cursor-pointer transition-all duration-200 {{ $currentJenis === 'perorangan' ? 'bg-[#D4AF37]/5 border-[#D4AF37] shadow-sm shadow-[#D4AF37]/10' : 'bg-[#0E0E0E] border-[#2A2A2A] hover:border-[#383838]' }}">
                        <input type="radio" name="jenis_pemohon" value="perorangan" class="sr-only" {{ $currentJenis === 'perorangan' ? 'checked' : '' }} onchange="switchJenisPemohon('perorangan')">
                        <div class="flex items-center space-x-3.5 w-full">
                            <div id="radio_dot_perorangan" class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 {{ $currentJenis === 'perorangan' ? 'border-[#D4AF37]' : 'border-[#444444]' }}">
                                <div id="radio_inner_perorangan" class="w-2.5 h-2.5 rounded-full bg-[#D4AF37] {{ $currentJenis === 'perorangan' ? '' : 'hidden' }}"></div>
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-bold text-white">
                                    Perorangan / Masyarakat
                                </div>
                                <p class="text-xs text-[#9E9E9E] mt-1 leading-relaxed">
                                    Warga perorangan berdomisili dan ber-KTP di Kabupaten Murung Raya.
                                </p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('jenis_pemohon') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Field Sesuai Jenis Pemohon -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <!-- Nama Organisasi / Lembaga (Hanya untuk Organisasi) -->
                <div id="container_nama_lembaga" class="{{ $currentJenis === 'organisasi' ? '' : 'hidden' }}">
                    <label for="nama_lembaga" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Nama Organisasi / Lembaga <span class="text-[#D4AF37]">*</span>
                    </label>
                    <input type="text" name="nama_lembaga" id="nama_lembaga" value="{{ old('nama_lembaga', $profile->nama_lembaga) }}"
                           placeholder="Contoh: Karang Taruna Mura Bersatu"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-xs text-[#808080] mt-1.5">Nama resmi organisasi atau lembaga pemohon.</p>
                    @error('nama_lembaga') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor Identitas / Legalitas -->
                <div id="container_nomor_identitas" class="{{ $currentJenis === 'organisasi' ? '' : 'md:col-span-2' }}">
                    <label id="label_nomor_identitas" for="nomor_identitas" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        {{ $currentJenis === 'organisasi' ? 'Nomor Legalitas / SK / Izin' : 'Nomor Identitas / NIK' }}
                    </label>
                    <input type="text" name="nomor_identitas" id="nomor_identitas" value="{{ old('nomor_identitas', $profile->nomor_identitas) }}"
                           placeholder="{{ $currentJenis === 'organisasi' ? 'Contoh: SK-01/MURA/2026 atau No. Akta Notaris' : 'Contoh: 621201xxxxxxxxxx (16 digit NIK KTP)' }}"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p id="helper_nomor_identitas" class="text-xs text-[#808080] mt-1.5">
                        {{ $currentJenis === 'organisasi' ? 'Nomor SK kepengurusan, izin operasional, atau akta pendirian resmi.' : 'Nomor Induk Kependudukan (NIK) resmi sesuai KTP pemohon di Kab. Murung Raya.' }}
                    </p>
                    @error('nomor_identitas') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label id="label_alamat" for="alamat" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Alamat
                    </label>
                    <textarea name="alamat" id="alamat" rows="3"
                              placeholder="Contoh: Jl. Jenderal Sudirman No. 45, RT 04 / RW 02, Kelurahan Beriwit, Kecamatan Murung, Kabupaten Murung Raya"
                              class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all leading-relaxed">{{ old('alamat', $profile->alamat) }}</textarea>
                    <p id="helper_alamat" class="text-xs text-[#808080] mt-1.5">
                        {{ $currentJenis === 'organisasi' ? 'Alamat lengkap kantor sekretariat lembaga di wilayah Kabupaten Murung Raya.' : 'Alamat domisili tempat tinggal pemohon sesuai KTP di Kabupaten Murung Raya.' }}
                    </p>
                    @error('alamat') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- CARD 3: REKENING PENYALURAN --}}
        {{-- ============================================================ --}}
        <div class="p-6 sm:p-8 rounded-[18px] bg-[#151515] border border-[#2A2A2A] shadow-xl">
            <!-- Header Card -->
            <div class="border-b border-[#2A2A2A] pb-4 mb-6 flex items-start space-x-3.5">
                <span class="px-2.5 py-1 rounded-lg bg-[#0B0B0B] border border-[#D4AF37]/40 text-[#D4AF37] font-mono text-xs font-black tracking-wider shadow-inner shrink-0 mt-0.5">
                    03
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-white tracking-wide uppercase">
                        Rekening Penyaluran
                    </h2>
                    <p class="text-xs sm:text-sm text-[#A3A3A3] mt-1 leading-relaxed">
                        Data rekening digunakan sebagai informasi administrasi dan penyaluran bantuan apabila proposal disetujui.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Nama Bank -->
                <div>
                    <label for="nama_bank" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Nama Bank
                    </label>
                    <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank', $profile->nama_bank ?? 'Bank Kalteng Cabang Puruk Cahu') }}"
                           placeholder="Contoh: Bank Kalteng / BRI"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-xs text-[#808080] mt-1.5">Nama bank penerbit rekening.</p>
                    @error('nama_bank') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor Rekening -->
                <div>
                    <label for="nomor_rekening" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Nomor Rekening
                    </label>
                    <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $profile->nomor_rekening) }}"
                           placeholder="Contoh: 100-02-005432-1"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm font-mono focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-xs text-[#808080] mt-1.5">Nomor rekening aktif.</p>
                    @error('nomor_rekening') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Pemilik Rekening -->
                <div>
                    <label for="nama_pemilik_rekening" class="block text-xs font-bold uppercase tracking-wider text-[#C0C0C0] mb-2">
                        Nama Pemilik Rekening
                    </label>
                    <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening" value="{{ old('nama_pemilik_rekening', $profile->nama_pemilik_rekening) }}"
                           placeholder="Contoh: Karang Taruna Mura Bersatu"
                           class="w-full px-4 py-3 bg-[#0E0E0E] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-xs text-[#808080] mt-1.5">Sesuai nama pada buku tabungan.</p>
                    @error('nama_pemilik_rekening') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TOMBOL AKSI / CTA UTAMA --}}
        {{-- ============================================================ --}}
        <div class="mt-10 pt-4 flex flex-col sm:flex-row items-center justify-end gap-4">
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-[#2A2A2A] bg-[#151515] hover:bg-[#1E1E1E] hover:border-[#D4AF37]/50 text-[#A3A3A3] hover:text-white font-bold text-sm text-center transition-all">
                Kembali ke Dashboard
            </a>
            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-extrabold rounded-xl text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-[1.02] flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

<script>
function switchJenisPemohon(jenis) {
    const cardOrg = document.getElementById('card_organisasi');
    const cardPer = document.getElementById('card_perorangan');
    const radioDotOrg = document.getElementById('radio_dot_organisasi');
    const radioDotPer = document.getElementById('radio_dot_perorangan');
    const radioInnerOrg = document.getElementById('radio_inner_organisasi');
    const radioInnerPer = document.getElementById('radio_inner_perorangan');
    
    const containerLembaga = document.getElementById('container_nama_lembaga');
    const containerIdentitas = document.getElementById('container_nomor_identitas');
    const labelIdentitas = document.getElementById('label_nomor_identitas');
    const inputIdentitas = document.getElementById('nomor_identitas');
    const helperIdentitas = document.getElementById('helper_nomor_identitas');
    const helperAlamat = document.getElementById('helper_alamat');

    if (jenis === 'organisasi') {
        // Style active card Organisasi
        cardOrg.classList.add('bg-[#D4AF37]/5', 'border-[#D4AF37]', 'shadow-sm', 'shadow-[#D4AF37]/10');
        cardOrg.classList.remove('bg-[#0E0E0E]', 'border-[#2A2A2A]');
        radioDotOrg.classList.add('border-[#D4AF37]');
        radioDotOrg.classList.remove('border-[#444444]');
        radioInnerOrg.classList.remove('hidden');

        // Style inactive card Perorangan
        cardPer.classList.remove('bg-[#D4AF37]/5', 'border-[#D4AF37]', 'shadow-sm', 'shadow-[#D4AF37]/10');
        cardPer.classList.add('bg-[#0E0E0E]', 'border-[#2A2A2A]');
        radioDotPer.classList.remove('border-[#D4AF37]');
        radioDotPer.classList.add('border-[#444444]');
        radioInnerPer.classList.add('hidden');

        // Tampilkan field nama lembaga
        containerLembaga.classList.remove('hidden');
        containerIdentitas.classList.remove('md:col-span-2');

        // Update label & placeholder identitas
        labelIdentitas.textContent = 'Nomor Legalitas / SK / Izin';
        inputIdentitas.placeholder = 'Contoh: SK-01/MURA/2026 atau No. Akta Notaris';
        helperIdentitas.textContent = 'Nomor SK kepengurusan, izin operasional, atau akta pendirian resmi.';
        if (helperAlamat) {
            helperAlamat.textContent = 'Alamat lengkap kantor sekretariat lembaga di wilayah Kabupaten Murung Raya.';
        }
    } else {
        // Style active card Perorangan
        cardPer.classList.add('bg-[#D4AF37]/5', 'border-[#D4AF37]', 'shadow-sm', 'shadow-[#D4AF37]/10');
        cardPer.classList.remove('bg-[#0E0E0E]', 'border-[#2A2A2A]');
        radioDotPer.classList.add('border-[#D4AF37]');
        radioDotPer.classList.remove('border-[#444444]');
        radioInnerPer.classList.remove('hidden');

        // Style inactive card Organisasi
        cardOrg.classList.remove('bg-[#D4AF37]/5', 'border-[#D4AF37]', 'shadow-sm', 'shadow-[#D4AF37]/10');
        cardOrg.classList.add('bg-[#0E0E0E]', 'border-[#2A2A2A]');
        radioDotOrg.classList.remove('border-[#D4AF37]');
        radioDotOrg.classList.add('border-[#444444]');
        radioInnerOrg.classList.add('hidden');

        // Sembunyikan field nama lembaga
        containerLembaga.classList.add('hidden');
        containerIdentitas.classList.add('md:col-span-2');

        // Update label & placeholder identitas
        labelIdentitas.textContent = 'Nomor Identitas / NIK';
        inputIdentitas.placeholder = 'Contoh: 621201xxxxxxxxxx (16 digit NIK KTP)';
        helperIdentitas.textContent = 'Nomor Induk Kependudukan (NIK) resmi sesuai KTP pemohon di Kab. Murung Raya.';
        if (helperAlamat) {
            helperAlamat.textContent = 'Alamat domisili tempat tinggal pemohon sesuai KTP di Kabupaten Murung Raya.';
        }
    }
}
</script>
@endsection
