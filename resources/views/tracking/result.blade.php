@extends('layouts.public')

@section('content')
<div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Beranda</span>
        </a>

        <span class="text-xs text-[#737373] font-mono">Pembaruan: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
    </div>

    <!-- Tracking Result Main Card -->
    <div class="bg-[#151515] rounded-3xl border border-[#2A2A2A] shadow-2xl overflow-hidden mb-8">
        <!-- Header status banner -->
        <div class="p-6 sm:p-8 bg-[#181818] border-b border-[#2A2A2A] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-mono font-bold px-2.5 py-1 rounded bg-[#0B0B0B] text-[#D4AF37] border border-[#2A2A2A]">
                    {{ $proposal->nomor_registrasi }}
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-white mt-2 leading-snug">
                    {{ $proposal->judul_proposal }}
                </h1>
                <p class="text-xs text-[#A3A3A3] mt-1">
                    Pemohon: <strong class="text-white">{{ $proposal->user->profile->nama_lembaga ?? $proposal->user->name }}</strong>
                    &bull; Kategori: {{ $proposal->category->nama_kategori }}
                </p>
            </div>

            <div class="sm:text-right shrink-0">
                <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide border {{ $proposal->status_color }}">
                    {{ $proposal->status_label }}
                </span>
                <p class="text-xs text-[#737373] mt-1 font-mono">Versi Terkini: v{{ $proposal->versi_aktif }}.0</p>
            </div>
        </div>

        <!-- Progress Timeline Steps (Transparansi 4 Tahap) -->
        <div class="p-6 sm:p-8 border-b border-[#2A2A2A] bg-[#151515]">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-6">Tahapan Alur Pelayanan Administrasi</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative">
                <!-- Step 1: Pengajuan -->
                <div class="p-4 rounded-2xl bg-[#0B0B0B] border border-emerald-500/40 relative">
                    <div class="flex items-center space-x-2 text-emerald-400 font-bold text-xs mb-1">
                        <span>✓ Selesai</span>
                    </div>
                    <h3 class="text-sm font-bold text-white">1. Pengajuan Usulan</h3>
                    <p class="text-xs text-[#A3A3A3] mt-1">
                        Dikirim pada {{ $proposal->tanggal_kirim ? $proposal->tanggal_kirim->translatedFormat('d M Y, H:i') : $proposal->created_at->format('d M Y') }}
                    </p>
                </div>

                <!-- Step 2: Disposisi TU Pimpinan (Pak Haziral) -->
                @php
                    $isDisposed = $proposal->isDisposed();
                    $isDecisionMade = in_array($proposal->status, ['disetujui', 'perlu_perbaikan', 'ditolak']);
                @endphp
                <div class="p-4 rounded-2xl bg-[#0B0B0B] border {{ ($proposal->status === 'diajukan' && !$isDisposed) ? 'border-amber-500/60' : ($isDisposed || $isDecisionMade ? 'border-emerald-500/40' : 'border-[#2A2A2A] opacity-50') }} relative">
                    <div class="flex items-center space-x-2 font-bold text-xs mb-1">
                        @if($isDisposed || $isDecisionMade)
                            <span class="text-emerald-400">✓ Selesai Disposisi</span>
                        @elseif($proposal->status === 'diajukan')
                            <span class="text-amber-400 animate-pulse">● Sedang Berlangsung</span>
                        @else
                            <span class="text-[#737373]">○ Belum Mulai</span>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-white">2. Disposisi TU Pimpinan</h3>
                    <p class="text-xs text-[#A3A3A3] mt-1">
                        Lembar disposisi KSB. Tata Usaha (Pak Haziral)
                    </p>
                </div>

                <!-- Step 3: Telaah Wakil Bupati -->
                <div class="p-4 rounded-2xl bg-[#0B0B0B] border {{ ($proposal->status === 'diajukan' && $isDisposed) ? 'border-[#D4AF37]/60' : ($isDecisionMade ? 'border-emerald-500/40' : 'border-[#2A2A2A] opacity-50') }} relative">
                    <div class="flex items-center space-x-2 font-bold text-xs mb-1">
                        @if($isDecisionMade)
                            <span class="text-emerald-400">✓ Selesai Ditelaah</span>
                        @elseif($proposal->status === 'diajukan' && $isDisposed)
                            <span class="text-[#D4AF37] animate-pulse">● Meja Wakil Bupati</span>
                        @elseif($proposal->status === 'diajukan' && !$isDisposed)
                            <span class="text-[#737373]">○ Antrean TU</span>
                        @else
                            <span class="text-[#737373]">○ Belum Mulai</span>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-white">3. Telaah Wakil Bupati</h3>
                    <p class="text-xs text-[#A3A3A3] mt-1">
                        Kajian kebijakan pimpinan daerah
                    </p>
                </div>

                <!-- Step 4: Penetapan Keputusan -->
                <div class="p-4 rounded-2xl bg-[#0B0B0B] border {{ $isDecisionMade ? 'border-[#D4AF37]/50' : 'border-[#2A2A2A] opacity-50' }} relative">
                    <div class="flex items-center space-x-2 font-bold text-xs mb-1">
                        @if($proposal->status === 'disetujui')
                            <span class="text-emerald-400">✓ Disetujui Penuh</span>
                        @elseif($proposal->status === 'perlu_perbaikan')
                            <span class="text-amber-400">✏️ Revisi Diperlukan</span>
                        @elseif($proposal->status === 'ditolak')
                            <span class="text-rose-400">✕ Belum Disetujui</span>
                        @else
                            <span class="text-[#737373]">○ Menunggu Telaah</span>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-white">4. Penetapan Akhir</h3>
                    <p class="text-xs text-[#A3A3A3] mt-1">
                        Arahan tindak lanjut resmi hibah/bansos
                    </p>
                </div>
            </div>
        </div>

        <!-- Disposisi & Catatan Pimpinan (Jika ada) -->
        @if($proposal->reviewDecisions->isNotEmpty())
            <div class="p-6 sm:p-8 bg-[#181818] border-b border-[#2A2A2A]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#E6C65C] mb-3.5 flex items-center space-x-1.5">
                    <span>📝 Catatan & Disposisi Pimpinan Daerah</span>
                </h2>
                @foreach($proposal->reviewDecisions as $rev)
                    <div class="p-4 rounded-2xl bg-[#0B0B0B] border border-[#2A2A2A] mb-3 last:mb-0">
                        <div class="flex justify-between items-center text-xs mb-2">
                            <span class="font-bold text-white">{{ $rev->reviewer->name }}</span>
                            <span class="text-[#737373] font-mono text-[11px]">{{ $rev->tanggal_keputusan ? $rev->tanggal_keputusan->translatedFormat('d M Y, H:i') : '' }}</span>
                        </div>
                        <p class="text-xs text-[#D4D4D4] italic leading-relaxed">
                            "{{ $rev->catatan_pimpinan }}"
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Summary Details -->
        <div class="p-6 sm:p-8 bg-[#151515]">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-4">Ringkasan Usulan</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 {{ $proposal->batas_waktu ? 'lg:grid-cols-3' : '' }} gap-4 text-xs">
                <div class="p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A]">
                    <dt class="text-[#737373] font-medium">Nilai Anggaran Diusulkan</dt>
                    <dd class="text-base font-extrabold text-[#D4AF37] mt-1">{{ $proposal->formatted_anggaran }}</dd>
                </div>
                <div class="p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A]">
                    <dt class="text-[#737373] font-medium">Lokasi Rencana Kegiatan</dt>
                    <dd class="text-sm font-bold text-white mt-1">{{ $proposal->latestVersion?->lokasi_kegiatan ?? 'Kab. Murung Raya' }}</dd>
                </div>
                @if($proposal->batas_waktu)
                    <div class="p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A]">
                        <dt class="text-[#737373] font-medium">Perkiraan Waktu Pelayanan</dt>
                        <dd class="text-sm font-bold text-white mt-1 flex flex-wrap items-center gap-1.5">
                            <span class="font-mono text-[#E6C65C]">{{ $proposal->formatted_batas_waktu }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $proposal->deadline_color }}">
                                {{ $proposal->deadline_label }}
                            </span>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
