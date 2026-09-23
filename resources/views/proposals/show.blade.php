@extends('layouts.app')

@section('content')
<div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb & Navigation -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('proposals.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Proposal</span>
        </a>

        <div class="flex items-center space-x-2">
            <span class="text-xs text-[#A3A3A3] font-mono bg-[#151515] border border-[#2A2A2A] px-2.5 py-1 rounded-lg">Versi Aktif: v{{ $proposal->versi_aktif }}.0</span>
        </div>
    </div>

    <!-- Main Proposal Dossier Card -->
    <div class="bg-[#151515] rounded-3xl border border-[#2A2A2A] shadow-xl overflow-hidden mb-8">
        
        <!-- Header Banner -->
        <div class="p-6 sm:p-8 bg-[#181818] border-b border-[#2A2A2A] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded bg-[#0B0B0B] text-[#D4AF37] border border-[#2A2A2A]">
                        {{ $proposal->nomor_registrasi }}
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A]">
                        {{ $proposal->category->nama_kategori }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-white leading-snug">{{ $proposal->judul_proposal }}</h1>
                <p class="text-xs text-[#A3A3A3] mt-1.5">
                    Diajukan pada {{ $proposal->tanggal_kirim ? $proposal->tanggal_kirim->translatedFormat('d F Y, H:i') : $proposal->created_at->format('d M Y') }} WIB
                </p>
            </div>

            <div class="sm:text-right shrink-0">
                <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider border {{ $proposal->status_color }}">
                    {{ $proposal->status_label }}
                </span>
                <p class="text-xl font-black text-[#D4AF37] mt-2">{{ $proposal->formatted_anggaran }}</p>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            
            <!-- Profil Pengusul & Rekening -->
            <div class="bg-[#181818] p-5 sm:p-6 rounded-2xl border border-[#2A2A2A]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#D4AF37] mb-3.5 flex items-center space-x-1.5">
                    <span>🏢 Data Pemohon & Penyaluran</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                    <div>
                        <span class="text-[#737373] block mb-0.5">Lembaga / Pemohon:</span>
                        <strong class="text-white font-bold text-sm block">{{ $proposal->user->profile->nama_lembaga ?? $proposal->user->name }}</strong>
                        <span class="block text-[#A3A3A3] mt-0.5">Penanggung Jawab: {{ $proposal->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#737373] block mb-0.5">Kontak & Alamat:</span>
                        <span class="text-white font-medium block">{{ $proposal->user->no_telepon }}</span>
                        <span class="text-[#A3A3A3] block mt-0.5">{{ $proposal->user->profile->alamat ?? 'Puruk Cahu, Murung Raya' }}</span>
                    </div>
                    <div>
                        <span class="text-[#737373] block mb-0.5">Rekening Penyaluran:</span>
                        <strong class="text-white block">{{ $proposal->user->profile->nama_bank ?? 'Bank Kalteng' }}</strong>
                        <span class="text-[#D4AF37] font-mono block mt-0.5">{{ $proposal->user->profile->nomor_rekening ?? '-' }}</span>
                        <span class="text-[#737373] block text-[11px]">a.n {{ $proposal->user->profile->nama_pemilik_rekening ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Substansi Usulan -->
            <div>
                <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center space-x-2">
                    <span class="text-[#D4AF37]">📄</span>
                    <span>Substansi Permohonan Kegiatan</span>
                </h2>

                <div class="space-y-4 text-xs leading-relaxed text-[#D4D4D4]">
                    <div>
                        <h3 class="font-bold text-[#A3A3A3] uppercase tracking-wider text-[11px] mb-1.5">Latar Belakang:</h3>
                        <p class="bg-[#0B0B0B] p-4 rounded-xl border border-[#2A2A2A] whitespace-pre-line text-[#E5E5E5] leading-relaxed">
                            {{ $activeVersion?->latar_belakang ?? 'Belum ada data latar belakang.' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold text-[#A3A3A3] uppercase tracking-wider text-[11px] mb-1.5">Maksud & Tujuan:</h3>
                        <p class="bg-[#0B0B0B] p-4 rounded-xl border border-[#2A2A2A] whitespace-pre-line text-[#E5E5E5] leading-relaxed">
                            {{ $activeVersion?->tujuan ?? 'Belum ada data tujuan.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A]">
                            <span class="text-[#737373] font-medium block text-[11px]">Lokasi Pelaksanaan:</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $activeVersion?->lokasi_kegiatan ?? 'Kabupaten Murung Raya' }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A]">
                            <span class="text-[#737373] font-medium block text-[11px]">Tanggal Kegiatan:</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">
                                {{ $activeVersion?->tanggal_kegiatan ? $activeVersion->tanggal_kegiatan->translatedFormat('d F Y') : 'Sesuai Jadwal' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Anggaran Biaya (RAB) -->
            <div>
                <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center space-x-2">
                    <span class="text-[#D4AF37]">💰</span>
                    <span>Rincian Anggaran Biaya (RAB)</span>
                </h2>

                @php
                    $rabJson = null;
                    if ($activeVersion && $activeVersion->rincian_rab) {
                        $rabJson = json_decode($activeVersion->rincian_rab, true);
                    }
                @endphp

                @if(is_array($rabJson))
                    <div class="overflow-x-auto rounded-xl border border-[#2A2A2A]">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#111111] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                                <tr>
                                    <th class="py-3 px-4">Item Kebutuhan</th>
                                    <th class="py-3 px-4 text-center">Volume</th>
                                    <th class="py-3 px-4 text-center">Satuan</th>
                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#2A2A2A]">
                                @foreach($rabJson as $item)
                                    <tr class="hover:bg-[#1A1A1A] transition-colors">
                                        <td class="py-3 px-4 font-semibold text-white">{{ $item['item'] ?? '-' }}</td>
                                        <td class="py-3 px-4 text-center text-[#A3A3A3]">{{ $item['volume'] ?? '1' }}</td>
                                        <td class="py-3 px-4 text-center text-[#A3A3A3]">{{ $item['satuan'] ?? 'paket' }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-white">
                                            Rp {{ number_format($item['biaya'] ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-[#111111] border-t border-[#2A2A2A] font-bold text-white">
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-right text-[#A3A3A3]">Total Anggaran:</td>
                                    <td class="py-3 px-4 text-right font-mono text-sm text-[#D4AF37]">
                                        {{ $proposal->formatted_anggaran }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A] text-xs font-mono whitespace-pre-line text-[#E5E5E5]">
                        {{ $activeVersion?->rincian_rab ?? 'RAB terlampir dalam berkas PDF proposal.' }}
                    </div>
                @endif
            </div>

            <!-- Lampiran Berkas Pendukung -->
            <div>
                <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center space-x-2">
                    <span class="text-[#D4AF37]">📎</span>
                    <span>Berkas Lampiran & Dokumen Terunggah</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @forelse($activeVersion?->attachments ?? [] as $att)
                        <div class="p-4 rounded-2xl bg-[#181818] border border-[#2A2A2A] flex items-center justify-between text-xs hover:border-[#D4AF37]/30 transition-colors">
                            <div>
                                <span class="font-bold text-white block truncate max-w-[180px]">{{ $att->nama_file }}</span>
                                <span class="text-[10px] text-[#737373] uppercase font-semibold">{{ $att->jenis_lampiran }} &bull; {{ $att->formatted_size }}</span>
                            </div>
                            <span class="px-2.5 py-1 bg-[#1F1F1F] text-[#D4AF37] border border-[#2A2A2A] rounded-lg text-[10px] font-bold">Terlampir</span>
                        </div>
                    @empty
                        <div class="sm:col-span-3 p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A] text-xs text-[#737373] text-center">
                            Dokumen digital tersimpan dalam arsip sistem.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Riwayat Disposisi Pimpinan -->
            @if($proposal->reviewDecisions->isNotEmpty())
                <div class="border-t border-[#2A2A2A] pt-6">
                    <h2 class="text-sm font-bold text-[#E6C65C] mb-4 flex items-center space-x-2">
                        <span>⭐ Riwayat Telaah & Keputusan Disposisi Pimpinan</span>
                    </h2>

                    <div class="space-y-3">
                        @foreach($proposal->reviewDecisions as $rev)
                            <div class="p-5 rounded-2xl bg-[#181818] border border-[#2A2A2A]">
                                <div class="flex justify-between items-center text-xs mb-2.5">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-white text-sm">{{ $rev->reviewer->name }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $proposal->status_color }}">
                                            {{ $rev->keputusan }}
                                        </span>
                                    </div>
                                    <span class="text-[#737373] font-mono text-[11px]">{{ $rev->tanggal_keputusan ? $rev->tanggal_keputusan->translatedFormat('d F Y, H:i') : '' }}</span>
                                </div>
                                <p class="text-xs text-[#D4D4D4] leading-relaxed italic bg-[#0B0B0B] p-3.5 rounded-xl border border-[#2A2A2A]">
                                    "{{ $rev->catatan_pimpinan }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Form Telaah & Disposisi Pimpinan (Hak Eksklusif Wakil Bupati) -->
            @if(auth()->user()->isWabup())
                <div class="border-t-2 border-[#D4AF37]/50 pt-6 bg-[#181818] p-6 sm:p-7 rounded-3xl border border-[#2A2A2A]">
                    <div class="mb-5">
                        <h2 class="text-base font-black text-white flex items-center space-x-2">
                            <span class="text-[#D4AF37]">📝</span>
                            <span>Formulir Telaah & Keputusan Disposisi Pimpinan</span>
                        </h2>
                        <p class="text-xs text-[#A3A3A3] mt-1">
                            Tetapkan keputusan persetujuan, permintaan perbaikan berkas, atau penolakan permohonan secara berwenang.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('proposals.review', $proposal->id) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-2">Keputusan Disposisi *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="p-3.5 rounded-2xl bg-[#0B0B0B] border border-[#2A2A2A] hover:border-emerald-500/60 cursor-pointer flex items-center space-x-3 transition-colors group">
                                    <input type="radio" name="keputusan" value="disetujui" required class="text-emerald-500 bg-[#151515] border-[#2A2A2A] focus:ring-emerald-500">
                                    <div>
                                        <span class="text-xs font-bold text-emerald-400 block group-hover:text-emerald-300">✓ Disetujui Penuh</span>
                                        <span class="text-[10px] text-[#737373]">Rekomendasikan realisasi</span>
                                    </div>
                                </label>

                                <label class="p-3.5 rounded-2xl bg-[#0B0B0B] border border-[#2A2A2A] hover:border-amber-500/60 cursor-pointer flex items-center space-x-3 transition-colors group">
                                    <input type="radio" name="keputusan" value="perlu_perbaikan" class="text-amber-500 bg-[#151515] border-[#2A2A2A] focus:ring-amber-500">
                                    <div>
                                        <span class="text-xs font-bold text-amber-400 block group-hover:text-amber-300">✏️ Perlu Perbaikan</span>
                                        <span class="text-[10px] text-[#737373]">Minta revisi RAB/berkas</span>
                                    </div>
                                </label>

                                <label class="p-3.5 rounded-2xl bg-[#0B0B0B] border border-[#2A2A2A] hover:border-rose-500/60 cursor-pointer flex items-center space-x-3 transition-colors group">
                                    <input type="radio" name="keputusan" value="ditolak" class="text-rose-500 bg-[#151515] border-[#2A2A2A] focus:ring-rose-500">
                                    <div>
                                        <span class="text-xs font-bold text-rose-400 block group-hover:text-rose-300">✕ Tidak Disetujui</span>
                                        <span class="text-[10px] text-[#737373]">Tidak memenuhi kriteria</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3] mb-1.5">Catatan Instruksi / Arahan Kebijakan Pimpinan *</label>
                            <textarea name="catatan_pimpinan" rows="3" required placeholder="Tuliskan catatan disposisi, instruksi ke dinas teknis terkait, atau rincian perbaikan berkas..."
                                      class="w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all"></textarea>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-6 py-3 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold rounded-xl text-xs shadow-md shadow-[#D4AF37]/20 transition-all hover:scale-105">
                                Simpan & Terbitkan Disposisi
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
