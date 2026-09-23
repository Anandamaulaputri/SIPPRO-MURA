@extends('layouts.app')

@section('content')
<div class="py-14 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-[#D4AF37] via-[#C99E2E] to-[#997C21] text-[#0B0B0B] font-black text-xl shadow-lg shadow-[#D4AF37]/20 mb-3">
            SP
        </div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight">Pendaftaran Akun Pengusul Proposal</h2>
        <p class="text-xs text-[#A3A3A3] mt-1">Lengkapi data akun dan profil pemohon untuk mengajukan usulan ke Pemerintah Kab. Murung Raya</p>
    </div>

    <div class="bg-[#151515] p-6 sm:p-8 rounded-2xl border border-[#2A2A2A] shadow-xl">
        <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
            @csrf

            <!-- Bagian 1: Data Akun -->
            <div class="border-b border-[#2A2A2A] pb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px]">1</span>
                    <span>Informasi Akun Login</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Lengkap Penanggung Jawab *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Nama lengkap sesuai KTP"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Email Aktif *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="nama@email.com"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('email') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">No. WhatsApp / Telepon *</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890" required
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('no_telepon') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Kata Sandi *</label>
                        <input type="password" name="password" required
                               placeholder="Minimal 8 karakter"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('password') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Konfirmasi Kata Sandi *</label>
                        <input type="password" name="password_confirmation" required
                               placeholder="Ulangi kata sandi"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Profil Lembaga / Pemohon -->
            <div class="border-b border-[#2A2A2A] pb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px]">2</span>
                    <span>Identitas Pemohon / Lembaga</span>
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-2">Jenis Pemohon *</label>
                        <div class="flex space-x-6 text-xs sm:text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer text-white">
                                <input type="radio" name="jenis_pemohon" value="organisasi" checked class="text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37]">
                                <span>Organisasi / Ormas / Lembaga</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-white">
                                <input type="radio" name="jenis_pemohon" value="perorangan" class="text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37]">
                                <span>Perorangan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Organisasi / Lembaga (Jika Organisasi)</label>
                        <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga') }}" placeholder="Contoh: Karang Taruna Mura Bersatu"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nomor Identitas Resmi (NIK Pemohon / No. SK Ormas) *</label>
                        <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" required
                               placeholder="NIK 16 digit atau Nomor SK Legalitas"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Alamat Lengkap di Kab. Murung Raya *</label>
                        <textarea name="alamat" rows="2" required placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan..."
                                  class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Rekening Bank Penyaluran -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-4 flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-[10px]">3</span>
                    <span>Informasi Rekening Penyaluran (Opsional)</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Bank</label>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank', 'Bank Kalteng') }}" placeholder="Bank Kalteng / BRI / BNI"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="123-456-7890"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Nama Pemilik Rekening</label>
                        <input type="text" name="nama_pemilik_rekening" value="{{ old('nama_pemilik_rekening') }}" placeholder="Nama di buku tabungan"
                               class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-[1.02]">
                Daftar & Buat Akun Pengusul
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-[#2A2A2A] text-center text-xs text-[#A3A3A3]">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-[#D4AF37] hover:underline">
                Masuk di sini
            </a>
        </div>
    </div>
</div>
@endsection
