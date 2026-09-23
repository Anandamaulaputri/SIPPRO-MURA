@extends('layouts.app')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Top Welcome Banner -->
    <div class="mb-8 p-6 sm:p-8 rounded-3xl bg-[#151515] border border-[#2A2A2A] text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#0B0B0B] border border-[#2A2A2A] text-[#D4AF37] text-xs font-semibold mb-3">
                    @if($user->isWabup())
                        <span>👑 Pimpinan Daerah (Wakil Bupati)</span>
                    @elseif($user->isAdmin())
                        <span>🛡️ Administrator Sistem</span>
                    @else
                        <span>👤 Pemohon Proposal</span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Selamat Datang, <span class="text-[#E6C65C]">{{ $user->name }}</span>!
                </h1>
                <p class="text-xs sm:text-sm text-[#A3A3A3] mt-1.5 max-w-2xl leading-relaxed">
                    @if($user->isWabup())
                        Ruang kerja eksekutif penelaahan dan penetapan disposisi proposal bantuan dana hibah & bansos Kabupaten Murung Raya.
                    @elseif($user->isAdmin())
                        Panel pengawasan dan tata usaha arsip kelengkapan berkas usulan proposal masuk.
                    @else
                        Pantau riwayat usulan, lengkapi berkas perbaikan, atau ajukan permohonan proposal baru.
                    @endif
                </p>
            </div>

            @if($user->isPengusul())
                <div class="shrink-0">
                    <a href="{{ route('proposals.create') }}" class="inline-flex items-center space-x-2 px-6 py-3.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-2xl text-xs sm:text-sm shadow-lg shadow-[#D4AF37]/20 transition-all hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Ajukan Usulan Baru</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- ROLE 1: WAKIL BUPATI (PIMPINAN DAERAH - EXECUTIVE VIEW) -->
    @if($user->isWabup())
        <!-- Executive KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] hover:border-[#D4AF37]/40 transition-colors shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#E6C65C] uppercase tracking-wider">Menunggu Telaah</span>
                    <span class="w-8 h-8 rounded-xl bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-sm font-bold">⏳</span>
                </div>
                <p class="text-3xl font-black text-white mt-2.5">{{ $stats['menunggu_telaah'] }}</p>
                <p class="text-[11px] text-[#A3A3A3] mt-1">Perlu pertimbangan & disposisi</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] hover:border-emerald-500/40 transition-colors shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Telah Disetujui</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-950/60 text-emerald-400 border border-emerald-800/40 flex items-center justify-center text-sm font-bold">✓</span>
                </div>
                <p class="text-3xl font-black text-white mt-2.5">{{ $stats['disetujui'] }}</p>
                <p class="text-[11px] text-[#A3A3A3] mt-1">Proposal disetujui Pimpinan</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] hover:border-amber-500/40 transition-colors shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Perlu Perbaikan</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-950/60 text-amber-400 border border-amber-800/40 flex items-center justify-center text-sm font-bold">✏️</span>
                </div>
                <p class="text-3xl font-black text-white mt-2.5">{{ $stats['perlu_perbaikan'] }}</p>
                <p class="text-[11px] text-[#A3A3A3] mt-1">Menunggu revisi pemohon</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] hover:border-[#D4AF37]/40 transition-colors shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">Total Dana Disetujui</span>
                    <span class="w-8 h-8 rounded-xl bg-[#1F1F1F] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-bold">Rp</span>
                </div>
                <p class="text-2xl font-black text-[#D4AF37] mt-2.5">Rp {{ number_format($stats['total_dana_disetujui'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-[#A3A3A3] mt-1">Komitmen anggaran daerah</p>
            </div>
        </div>

        <!-- Priority: Proposals Awaiting Review -->
        <div class="mb-10 bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-md overflow-hidden">
            <div class="p-5 bg-[#181818] border-b border-[#2A2A2A] flex justify-between items-center">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-white flex items-center space-x-2">
                        <span>⚡ Usulan Masuk Menunggu Disposisi Pimpinan</span>
                        <span class="px-2 py-0.5 rounded-full bg-[#D4AF37]/20 text-[#E6C65C] border border-[#D4AF37]/40 text-xs font-bold">{{ $pendingProposals->count() }}</span>
                    </h2>
                    <p class="text-xs text-[#A3A3A3] mt-0.5">Segera berikan telaah dan arahan kebijakan untuk usulan berikut</p>
                </div>
            </div>

            @if($pendingProposals->isEmpty())
                <div class="p-8 text-center text-[#A3A3A3]">
                    <p class="text-xs sm:text-sm">Tidak ada proposal yang sedang menunggu telaah saat ini.</p>
                </div>
            @else
                <div class="divide-y divide-[#2A2A2A]">
                    @foreach($pendingProposals as $p)
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-[#1A1A1A] transition-colors">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-[#0B0B0B] text-[#D4AF37] border border-[#2A2A2A]">{{ $p->nomor_registrasi }}</span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A]">{{ $p->category->nama_kategori }}</span>
                                </div>
                                <h3 class="text-base font-bold text-white">{{ $p->judul_proposal }}</h3>
                                <p class="text-xs text-[#A3A3A3]">
                                    Pengusul: <strong class="text-white">{{ $p->user->profile->nama_lembaga ?? $p->user->name }}</strong> &bull;
                                    Diajukan: {{ $p->tanggal_kirim ? $p->tanggal_kirim->translatedFormat('d M Y') : $p->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-4 self-end md:self-center">
                                <div class="text-right">
                                    <span class="text-[11px] text-[#A3A3A3] block">Anggaran Diusulkan</span>
                                    <span class="text-sm sm:text-base font-black text-[#D4AF37]">{{ $p->formatted_anggaran }}</span>
                                </div>
                                <a href="{{ route('proposals.show', $p->id) }}" class="px-4 py-2.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-xs rounded-xl shadow-md shadow-[#D4AF37]/20 transition-all flex items-center space-x-1.5 hover:scale-105">
                                    <span>Telaah & Disposisi</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- ROLE 2: PENGUSUL (MASYARAKAT / ORMAS) -->
    @if($user->isPengusul())
        <!-- Pengusul KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-xs font-bold text-[#A3A3A3] uppercase tracking-wider block">Total Usulan Saya</span>
                <p class="text-3xl font-black text-white mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-xs font-bold text-[#E6C65C] uppercase tracking-wider block">Sedang Diajukan</span>
                <p class="text-3xl font-black text-[#D4AF37] mt-2">{{ $stats['diajukan'] }}</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block">Perlu Perbaikan</span>
                <p class="text-3xl font-black text-amber-400 mt-2">{{ $stats['perlu_perbaikan'] }}</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block">Disetujui</span>
                <p class="text-3xl font-black text-emerald-400 mt-2">{{ $stats['disetujui'] }}</p>
            </div>
        </div>

        <!-- Proposals List Table -->
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-md overflow-hidden mb-8">
            <div class="p-5 border-b border-[#2A2A2A] flex justify-between items-center bg-[#181818]">
                <h2 class="text-base font-extrabold text-white">Riwayat Usulan Proposal Anda</h2>
                <a href="{{ route('proposals.create') }}" class="text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] transition-colors">+ Buat Usulan Baru</a>
            </div>

            @if($proposals->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-[#1F1F1F] border border-[#2A2A2A] text-[#D4AF37] flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                        📄
                    </div>
                    <h3 class="text-base font-bold text-white">Belum Ada Proposal</h3>
                    <p class="text-xs text-[#A3A3A3] max-w-sm mx-auto mt-1 mb-4">
                        Anda belum pernah mengirimkan proposal bantuan. Silakan klik tombol di bawah untuk membuat usulan baru.
                    </p>
                    <a href="{{ route('proposals.create') }}" class="px-5 py-2.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-xs rounded-xl shadow-md transition-all">
                        Ajukan Proposal Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#111111] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                            <tr>
                                <th class="py-3 px-4">No. Registrasi</th>
                                <th class="py-3 px-4">Judul Usulan</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Anggaran</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2A2A2A]">
                            @foreach($proposals as $prop)
                                <tr class="hover:bg-[#1A1A1A] transition-colors">
                                    <td class="py-3.5 px-4 font-mono font-bold text-[#D4AF37]">{{ $prop->nomor_registrasi }}</td>
                                    <td class="py-3.5 px-4 font-bold text-white max-w-xs truncate">{{ $prop->judul_proposal }}</td>
                                    <td class="py-3.5 px-4 text-[#A3A3A3]">{{ $prop->category->nama_kategori }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-white">{{ $prop->formatted_anggaran }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $prop->status_color }}">
                                            {{ $prop->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('proposals.show', $prop->id) }}" class="px-3 py-1.5 bg-[#1F1F1F] hover:bg-[#252525] text-[#D4AF37] hover:text-[#E6C65C] border border-[#D4AF37]/30 font-semibold rounded-lg transition-all text-xs">
                                            Rincian &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <!-- ROLE 3: ADMIN (STAF ADMINISTRASI PIMPINAN) -->
    @if($user->isAdmin())
        <!-- Admin KPI Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-[11px] font-bold text-[#A3A3A3] uppercase tracking-wider block">Total Usulan</span>
                <p class="text-2xl font-black text-white mt-1">{{ $stats['total_proposal'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-[11px] font-bold text-[#E6C65C] uppercase tracking-wider block">Menunggu</span>
                <p class="text-2xl font-black text-[#D4AF37] mt-1">{{ $stats['menunggu_telaah'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Disetujui</span>
                <p class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['disetujui'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">Perbaikan</span>
                <p class="text-2xl font-black text-amber-400 mt-1">{{ $stats['perlu_perbaikan'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-[11px] font-bold text-blue-400 uppercase tracking-wider block">Pengusul</span>
                <p class="text-2xl font-black text-blue-400 mt-1">{{ $stats['total_pengusul'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-[#151515] border border-[#2A2A2A] shadow-xs">
                <span class="text-[11px] font-bold text-[#D4AF37] uppercase tracking-wider block">Realisasi</span>
                <p class="text-lg font-black text-[#D4AF37] mt-1">Rp {{ number_format($stats['total_anggaran_disetujui'] / 1000000, 0) }}M</p>
            </div>
        </div>

        <!-- Proposals Table for Admin -->
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-md overflow-hidden mb-8">
            <div class="p-5 border-b border-[#2A2A2A] flex justify-between items-center bg-[#181818]">
                <h2 class="text-base font-extrabold text-white">Seluruh Usulan Proposal Masuk</h2>
                <a href="{{ route('proposals.index') }}" class="text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] transition-colors">Kelola Proposal &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#111111] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                        <tr>
                            <th class="py-3 px-4">No. Registrasi</th>
                            <th class="py-3 px-4">Pemohon</th>
                            <th class="py-3 px-4">Judul Usulan</th>
                            <th class="py-3 px-4">Anggaran</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2A2A2A]">
                        @foreach($proposals as $prop)
                            <tr class="hover:bg-[#1A1A1A] transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#D4AF37]">{{ $prop->nomor_registrasi }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white">{{ $prop->user->profile->nama_lembaga ?? $prop->user->name }}</div>
                                    <div class="text-[11px] text-[#A3A3A3]">{{ $prop->user->no_telepon }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-white max-w-xs truncate">{{ $prop->judul_proposal }}</td>
                                <td class="py-3.5 px-4 font-semibold text-white">{{ $prop->formatted_anggaran }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $prop->status_color }}">
                                        {{ $prop->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('proposals.show', $prop->id) }}" class="px-3 py-1.5 bg-[#1F1F1F] text-[#D4AF37] hover:bg-[#252525] hover:text-[#E6C65C] border border-[#D4AF37]/30 font-semibold rounded-lg transition-colors text-xs">
                                        Periksa Berkas &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Recent Activity Audit Log -->
    <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-md p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-4 flex items-center space-x-2">
            <span>📋 Jejak Audit & Aktivitas Terkini</span>
        </h2>
        <div class="space-y-3">
            @foreach($recentActivities as $act)
                <div class="flex items-start space-x-3 text-xs border-b border-[#2A2A2A] pb-2.5 last:border-0 last:pb-0">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                    <div class="flex-grow">
                        <span class="font-bold text-white">{{ $act->aktivitas }}</span>
                        @if($act->keterangan)
                            <span class="text-[#A3A3A3]">&bull; {{ $act->keterangan }}</span>
                        @endif
                        @if(isset($act->user))
                            <span class="text-[#737373]">({{ $act->user->name }})</span>
                        @endif
                    </div>
                    <span class="text-[#737373] font-mono text-[11px] shrink-0">{{ $act->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
