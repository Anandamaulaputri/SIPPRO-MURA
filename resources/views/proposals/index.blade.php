@extends('layouts.app')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Top Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-black text-white tracking-tight">Daftar Usulan Proposal</h1>
        <p class="text-xs text-[#A3A3A3] mt-1">
            {{ auth()->user()->isPengusul() ? 'Kelola dan pantau seluruh proposal bantuan yang Anda ajukan' : 'Daftar seluruh permohonan proposal masuk ke Pemerintah Kabupaten Murung Raya' }}
        </p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-[#151515] p-4 rounded-2xl border border-[#2A2A2A] shadow-md mb-6">
        <form method="GET" action="{{ route('proposals.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul proposal atau nomor registrasi..."
                       class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
            </div>

            <div class="sm:w-60">
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-xs focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan (Menunggu Telaah)</option>
                    <option value="perlu_perbaikan" {{ request('status') === 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs shadow-md shadow-[#D4AF37]/20 transition-all">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('proposals.index') }}" class="px-3.5 py-2 text-xs font-semibold text-[#A3A3A3] hover:text-[#D4AF37] self-center transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Proposals Table -->
    <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-md overflow-hidden mb-6">
        @if($proposals->isEmpty())
            <div class="py-12 px-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-[#1B1B1B] border border-[#2A2A2A] text-[#D4AF37] flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                    📑
                </div>
                <h3 class="text-sm sm:text-base font-bold text-white">Belum Ada Usulan Proposal</h3>
                <p class="text-xs text-[#A3A3A3] max-w-sm mx-auto mt-1 mb-5 leading-relaxed">
                    @if(request('search') || request('status'))
                        Tidak ada usulan proposal yang cocok dengan kriteria filter Anda. Silakan ubah atau reset filter.
                    @else
                        Anda belum memiliki riwayat pengajuan proposal bantuan ke Pemerintah Kabupaten Murung Raya.
                    @endif
                </p>
                @if(request('search') || request('status'))
                    <a href="{{ route('proposals.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#1F1F1F] text-[#D4AF37] hover:text-[#E6C65C] border border-[#2A2A2A] text-xs font-bold transition-all">
                        <span>Reset Filter Pencarian</span>
                    </a>
                @elseif(auth()->user()->isPengusul())
                    <a href="{{ route('proposals.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-xs rounded-xl shadow-md transition-all hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Usulan Proposal Pertama</span>
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#111111] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                        <tr>
                            <th class="py-3.5 px-4">No. Registrasi</th>
                            @if(!auth()->user()->isPengusul())
                                <th class="py-3.5 px-4">Pengusul / Lembaga</th>
                            @endif
                            <th class="py-3.5 px-4">Judul Usulan</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Anggaran</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2A2A2A]">
                        @foreach($proposals as $prop)
                            <tr class="hover:bg-[#1A1A1A] transition-colors">
                                <td class="py-4 px-4 font-mono font-bold text-[#D4AF37]">
                                    {{ $prop->nomor_registrasi }}
                                </td>
                                @if(!auth()->user()->isPengusul())
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-white">{{ $prop->user->profile->nama_lembaga ?? $prop->user->name }}</div>
                                        <div class="text-[11px] text-[#A3A3A3]">{{ $prop->user->email }}</div>
                                    </td>
                                @endif
                                <td class="py-4 px-4">
                                    <div class="font-bold text-white max-w-sm">{{ $prop->judul_proposal }}</div>
                                    <div class="text-[11px] text-[#A3A3A3] mt-0.5">
                                        Diajukan: {{ $prop->tanggal_kirim ? $prop->tanggal_kirim->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-2.5 py-1 rounded-full bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A] font-medium text-[11px]">
                                        {{ $prop->category->nama_kategori }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-bold text-white">
                                    {{ $prop->formatted_anggaran }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $prop->status_color }}">
                                        {{ $prop->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('proposals.show', $prop->id) }}" class="px-3.5 py-2 bg-[#1F1F1F] hover:bg-[#252525] text-[#D4AF37] hover:text-[#E6C65C] border border-[#D4AF37]/30 font-bold rounded-xl text-xs transition-all">
                                        Lihat Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#2A2A2A] bg-[#111111]">
                {{ $proposals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
