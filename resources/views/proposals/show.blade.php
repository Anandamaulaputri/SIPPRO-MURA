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
            <span class="text-xs text-[#A3A3A3] font-mono bg-[#151515] border border-[#2A2A2A] px-2.5 py-1 rounded-lg">
                Menampilkan: <strong class="text-white">v{{ $displayedVersion->nomor_versi }}.0</strong> 
                @if($displayedVersion->id === $activeVersion->id)
                    <span class="text-emerald-400 font-bold">(Aktif)</span>
                @else
                    <span class="text-amber-400 font-bold">(Arsip)</span>
                @endif
            </span>
        </div>
    </div>

    @if($displayedVersion->id !== $activeVersion->id)
        <div class="mb-6 p-4 rounded-2xl bg-amber-950/40 border border-amber-500/40 text-amber-200 text-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-lg">
            <div class="flex items-center space-x-2.5">
                <span class="text-lg">⚠️</span>
                <span>Anda sedang melihat arsip dokumen <strong>Versi {{ $displayedVersion->nomor_versi }}.0</strong>. Versi aktif usulan adalah <strong>v{{ $activeVersion->nomor_versi }}.0</strong>.</span>
            </div>
            <a href="{{ route('proposals.show', $proposal->id) }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-black font-extrabold rounded-xl text-xs transition-colors shrink-0 text-center">
                Buka Versi Aktif &rarr;
            </a>
        </div>
    @endif

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

            <!-- Banner Notifikasi & CTA Perlu Perbaikan -->
            @if($proposal->status === 'perlu_perbaikan')
                @php
                    $latestReview = $proposal->reviewDecisions->where('keputusan', 'perlu_perbaikan')->last() ?? $proposal->reviewDecisions->last();
                @endphp
                <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-amber-950/50 via-[#1C1812] to-[#151515] border-2 border-amber-500/60 shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div class="space-y-2 flex-grow min-w-0">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-950/80 text-amber-400 border border-amber-500/50">
                                ⚠️ Perlu Perbaikan Berkas
                            </span>
                            <span class="text-xs text-[#A3A3A3]">Instruksi dari: <strong class="text-white">{{ $latestReview?->reviewer?->name ?? 'Wakil Bupati' }}</strong></span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-[#0B0B0B] border border-amber-500/30 text-white text-xs sm:text-sm leading-relaxed italic">
                            "{{ $latestReview?->catatan_pimpinan ?? 'Harap melengkapi dokumen atau menyesuaikan rincian anggaran sesuai arahan.' }}"
                        </div>
                    </div>
                    @if(auth()->user()->isPengusul() && $proposal->user_id === auth()->id())
                        <a href="{{ route('proposals.revise', $proposal->id) }}" 
                           class="px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-[#0B0B0B] font-black text-xs rounded-xl shadow-lg shadow-amber-500/25 transition-all hover:scale-105 flex items-center space-x-2 shrink-0 justify-center">
                            <span>✏️</span>
                            <span>Ajukan Perbaikan Proposal</span>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Catatan Perbaikan dari Pemohon (Jika Versi Revisi) -->
            @if($displayedVersion->catatan_revisi_pemohon)
                <div class="p-5 rounded-2xl bg-[#181818] border border-[#D4AF37]/50 shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-[#D4AF37] flex items-center space-x-2">
                            <span>📝</span>
                            <span>Catatan Perbaikan dari Pemohon (Revisi v{{ $displayedVersion->nomor_versi }}.0)</span>
                        </h3>
                        <span class="text-[11px] text-[#737373] font-mono">{{ $displayedVersion->created_at?->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                    <p class="text-xs sm:text-sm text-white leading-relaxed bg-[#0B0B0B] p-3.5 rounded-xl border border-[#2A2A2A]">
                        "{{ $displayedVersion->catatan_revisi_pemohon }}"
                    </p>
                </div>
            @endif
            
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

            @php
                $rawLb = $displayedVersion?->latar_belakang ?? '';
                $extractedNomorSurat = null;
                if (preg_match('/Nomor Surat\/Proposal Pengusul:\s*([^\r\n]+)/i', $rawLb, $matches)) {
                    $extractedNomorSurat = trim($matches[1]);
                }

                // Bersihkan teks dari nomor surat dan placeholder default
                $cleanLb = trim(preg_replace('/Nomor Surat\/Proposal Pengusul:[^\r\n]*/i', '', $rawLb));
                $cleanLb = trim(str_ireplace([
                    'Lihat dokumen proposal PDF terlampir.',
                    'Lihat dokumen proposal PDF terlampir',
                    'Belum ada data latar belakang.',
                ], '', $cleanLb));

                $cleanTujuan = trim(str_ireplace([
                    'Lihat dokumen proposal PDF terlampir.',
                    'Lihat dokumen proposal PDF terlampir',
                    'Belum ada data tujuan.',
                ], '', $displayedVersion?->tujuan ?? ''));

                $rabJson = null;
                if ($displayedVersion && $displayedVersion->rincian_rab) {
                    $rabJson = json_decode($displayedVersion->rincian_rab, true);
                }
                $hasStructuredRab = is_array($rabJson) && count($rabJson) > 0;
                $hasCustomNarrative = (!empty($cleanLb) || !empty($cleanTujuan));
            @endphp

            <!-- Parameter Pelaksanaan Kegiatan -->
            <div class="p-5 sm:p-6 rounded-2xl bg-[#181818] border border-[#2A2A2A]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#D4AF37] mb-3.5 flex items-center space-x-1.5">
                    <span>📍 Parameter & Lokasi Pelaksanaan</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $extractedNomorSurat ? 'lg:grid-cols-3' : '' }} gap-4 text-xs">
                    @if($extractedNomorSurat)
                        <div>
                            <span class="text-[#737373] block mb-0.5 font-medium">No. Surat Pengusul:</span>
                            <strong class="font-mono font-bold text-white text-xs block truncate" title="{{ $extractedNomorSurat }}">{{ $extractedNomorSurat }}</strong>
                        </div>
                    @endif
                    <div>
                        <span class="text-[#737373] block mb-0.5 font-medium">Lokasi Pelaksanaan:</span>
                        <strong class="font-bold text-white text-xs block">{{ $displayedVersion?->lokasi_kegiatan ?? 'Kabupaten Murung Raya' }}</strong>
                    </div>
                    <div>
                        <span class="text-[#737373] block mb-0.5 font-medium">Waktu / Jadwal Kegiatan:</span>
                        <strong class="font-bold text-white text-xs block">
                            {{ $displayedVersion?->tanggal_kegiatan ? $displayedVersion->tanggal_kegiatan->translatedFormat('d F Y') : 'Sesuai Jadwal Proposal' }}
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Uraian Substansi Tambahan (Hanya tampil jika pengusul mengisi uraian narasi khusus) -->
            @if($hasCustomNarrative)
                <div>
                    <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center space-x-2">
                        <span class="text-[#D4AF37]">📄</span>
                        <span>Uraian Substansi Kegiatan</span>
                    </h2>

                    <div class="space-y-4 text-xs leading-relaxed text-[#D4D4D4]">
                        @if(!empty($cleanLb))
                            <div>
                                <h3 class="font-bold text-[#A3A3A3] uppercase tracking-wider text-[11px] mb-1.5">Latar Belakang:</h3>
                                <p class="bg-[#0B0B0B] p-4 rounded-xl border border-[#2A2A2A] whitespace-pre-line text-[#E5E5E5] leading-relaxed">
                                    {{ $cleanLb }}
                                </p>
                            </div>
                        @endif

                        @if(!empty($cleanTujuan))
                            <div>
                                <h3 class="font-bold text-[#A3A3A3] uppercase tracking-wider text-[11px] mb-1.5">Maksud & Tujuan:</h3>
                                <p class="bg-[#0B0B0B] p-4 rounded-xl border border-[#2A2A2A] whitespace-pre-line text-[#E5E5E5] leading-relaxed">
                                    {{ $cleanTujuan }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Rincian Anggaran Biaya (RAB) - Hanya tampil jika ada rincian tabel terstruktur -->
            @if($hasStructuredRab)
                <div>
                    <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center space-x-2">
                        <span class="text-[#D4AF37]">💰</span>
                        <span>Rincian Anggaran Biaya (RAB)</span>
                    </h2>

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
                </div>
            @endif

            <!-- Dokumen Proposal Utama (PDF) -->
            <div>
                <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-[#D4AF37]">📄</span>
                        <span>Dokumen Berkas Proposal Utama (PDF)</span>
                    </div>
                    @if($displayedVersion?->file_proposal)
                        <span class="text-[11px] font-bold text-emerald-400 bg-emerald-950/50 border border-emerald-500/30 px-2.5 py-0.5 rounded-full flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Berkas PDF Tersedia</span>
                        </span>
                    @endif
                </h2>

                @if($displayedVersion?->file_proposal)
                    @php
                        $rawFilename = basename($displayedVersion->file_proposal);
                        $filenameWithoutExt = pathinfo($rawFilename, PATHINFO_FILENAME);
                        $isRandomHash = (strlen($filenameWithoutExt) >= 30 && !str_contains($filenameWithoutExt, '_') && !str_contains($filenameWithoutExt, '-') && !str_contains($filenameWithoutExt, ' '));
                        
                        if ($isRandomHash) {
                            $displayPdfName = 'Proposal_' . \Illuminate\Support\Str::slug($proposal->judul_proposal, '_') . '.pdf';
                        } else {
                            $displayPdfName = $rawFilename;
                        }

                        $downloadPdfName = $displayPdfName;
                    @endphp
                    <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-[#1A1A1A] via-[#151515] to-[#101010] border border-[#D4AF37]/40 shadow-xl mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center space-x-4 min-w-0">
                                <div class="w-14 h-14 rounded-2xl bg-[#0B0B0B] border border-[#D4AF37]/50 flex items-center justify-center text-rose-400 text-2xl shadow-inner shrink-0">
                                    📑
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-black uppercase tracking-wider text-[#D4AF37]">Naskah Resmi Usulan</span>
                                        <span class="text-[10px] text-[#A3A3A3] bg-[#0E0E0E] px-2 py-0.5 rounded border border-[#2A2A2A] font-mono">PDF</span>
                                    </div>
                                    <h3 class="text-base font-bold text-white mt-1 truncate" title="{{ $displayPdfName }}">{{ $displayPdfName }}</h3>
                                    <p class="text-xs text-[#A3A3A3] mt-0.5 leading-relaxed">Naskah lengkap proposal yang diajukan pemohon beserta rencana teknis & rincian permohonan bantuan.</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                                <a href="{{ asset('storage/' . $displayedVersion->file_proposal) }}" target="_blank"
                                   class="px-4 py-2.5 bg-gradient-to-r from-[#D4AF37] to-[#C5A028] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black text-xs rounded-xl shadow-lg shadow-[#D4AF37]/25 transition-all hover:scale-105 flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-[#0B0B0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span>Buka PDF di Tab Baru</span>
                                </a>

                                <a href="{{ asset('storage/' . $displayedVersion->file_proposal) }}" download="{{ $downloadPdfName }}"
                                   class="px-4 py-2.5 bg-[#1F1F1F] hover:bg-[#252525] text-white border border-[#2A2A2A] hover:border-[#D4AF37]/50 font-bold text-xs rounded-xl transition-all flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Unduh PDF</span>
                                </a>

                                <button type="button" onclick="togglePdfPreview()"
                                        id="btnTogglePdfPreview"
                                        class="px-3.5 py-2.5 bg-[#111111] hover:bg-[#1E1E1E] text-[#D4AF37] border border-[#D4AF37]/30 hover:border-[#D4AF37] font-bold text-xs rounded-xl transition-all flex items-center space-x-1.5">
                                    <span id="btnTogglePdfIcon">▼</span>
                                    <span id="btnTogglePdfText">Lihat di Halaman Ini</span>
                                </button>
                            </div>
                        </div>

                        <!-- Embedded PDF Viewer (Accordion) -->
                        <div id="pdfPreviewContainer" class="hidden mt-5 pt-5 border-t border-[#2A2A2A]">
                            <div class="flex items-center justify-between mb-3 text-xs text-[#A3A3A3]">
                                <span class="font-semibold text-white">Pratinjau Dokumen Usulan Proposal:</span>
                                <span class="text-[11px] text-[#737373]">Gunakan navigasi di dalam bingkai untuk memperbesar atau berpindah halaman</span>
                            </div>
                            <iframe src="{{ asset('storage/' . $displayedVersion->file_proposal) }}" 
                                    class="w-full h-[620px] rounded-xl border border-[#2A2A2A] bg-[#0A0A0A] shadow-inner" 
                                    frameborder="0">
                            </iframe>
                        </div>
                    </div>
                @else
                    <div class="p-5 rounded-2xl bg-[#0B0B0B] border border-[#2A2A2A] text-xs text-[#737373] flex items-center space-x-3 mb-6">
                        <span class="text-xl">ℹ️</span>
                        <span>Pemohon tidak melampirkan berkas dokumen PDF terpisah. Rincian kegiatan dan anggaran mengacu pada uraian substansi di atas.</span>
                    </div>
                @endif
            </div>

            <!-- Lampiran Berkas Pendukung (KTP, SK, & Rekening) -->
            <div>
                <h2 class="text-sm font-bold text-white mb-4 pb-2 border-b border-[#2A2A2A] flex items-center space-x-2">
                    <span class="text-[#D4AF37]">📎</span>
                    <span>Berkas Lampiran Pendukung (KTP, Legalitas, & Rekening)</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @forelse($displayedVersion?->attachments ?? [] as $att)
                        @php
                            $icon = match($att->jenis_lampiran) {
                                'ktp' => '🪪',
                                'akta' => '📋',
                                'rekening' => '🏦',
                                default => '📎'
                            };
                            $label = match($att->jenis_lampiran) {
                                'ktp' => 'KTP Penanggung Jawab',
                                'akta' => 'Legalitas / SK Lembaga',
                                'rekening' => 'Buku Rekening Bank',
                                default => 'Lampiran Dokumen'
                            };
                        @endphp
                        <div class="p-4 rounded-2xl bg-[#181818] border border-[#2A2A2A] flex flex-col justify-between text-xs hover:border-[#D4AF37]/40 transition-all group">
                            <div>
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-base">{{ $icon }}</span>
                                    <span class="font-extrabold text-[10px] uppercase tracking-wider text-[#D4AF37]">{{ $label }}</span>
                                </div>
                                <span class="font-bold text-white block truncate mb-1" title="{{ $att->nama_file }}">{{ $att->nama_file }}</span>
                                <span class="text-[10px] text-[#737373] block mb-3 font-mono">{{ $att->formatted_size }}</span>
                            </div>

                            <div class="pt-2.5 border-t border-[#252525] flex items-center justify-between">
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                                   class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] transition-colors">
                                    <span>Buka Dokumen</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <a href="{{ asset('storage/' . $att->file_path) }}" download="{{ $att->nama_file }}" class="text-[#737373] hover:text-white p-1 rounded transition-colors" title="Unduh {{ $att->nama_file }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="sm:col-span-3 p-4 rounded-xl bg-[#0B0B0B] border border-[#2A2A2A] text-xs text-[#737373] text-center">
                            Tidak ada berkas lampiran fisik tambahan yang diunggah.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Riwayat Versi Proposal (Versioning) -->
            <div class="border-t border-[#2A2A2A] pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-white flex items-center space-x-2">
                        <span class="text-[#D4AF37]">📚</span>
                        <span>Riwayat Versi Proposal (Dokumen & Anggaran)</span>
                    </h2>
                    <span class="text-[11px] text-[#A3A3A3]">Total: {{ $proposal->versions->count() }} Versi Terdata</span>
                </div>

                <div class="space-y-3">
                    @foreach($proposal->versions->sortBy('nomor_versi') as $v)
                        @php
                            $isCurrentDisplay = ($v->nomor_versi == $displayedVersion?->nomor_versi);
                            $isActive = ($v->nomor_versi == $proposal->versi_aktif);
                            $versionDecision = $v->reviewDecisions->last();
                        @endphp
                        <div class="p-4 sm:p-5 rounded-2xl bg-[#181818] border {{ $isCurrentDisplay ? 'border-[#D4AF37]' : 'border-[#2A2A2A]' }} transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2.5">
                                <div class="flex items-center space-x-2.5">
                                    <span class="font-mono font-black text-sm px-2.5 py-1 rounded-lg {{ $isActive ? 'bg-[#D4AF37] text-[#0B0B0B]' : 'bg-[#2A2A2A] text-white' }}">
                                        Versi {{ $v->nomor_versi }}.0
                                    </span>
                                    @if($isActive)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37]/20 text-[#E6C65C] border border-[#D4AF37]/40">
                                            Aktif (Terkini)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#2A2A2A] text-[#A3A3A3]">
                                            Arsip Riwayat
                                        </span>
                                    @endif

                                    @if($versionDecision)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ match($versionDecision->keputusan) { 'disetujui' => 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/40', 'perlu_perbaikan' => 'bg-amber-950/60 text-amber-400 border border-amber-800/40', 'ditolak' => 'bg-rose-950/60 text-rose-400 border border-rose-800/40', default => 'bg-[#2A2A2A] text-[#A3A3A3]' } }}">
                                            {{ $versionDecision->keputusan }}
                                        </span>
                                    @elseif($isActive)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $proposal->status_color }}">
                                            {{ $proposal->status_label }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2 self-start sm:self-auto">
                                    <span class="text-xs text-[#737373] font-mono">
                                        Diajukan: {{ $v->created_at->translatedFormat('d F Y, H:i') }}
                                    </span>
                                    @if($isCurrentDisplay)
                                        <span class="px-3 py-1 rounded-lg bg-[#2A2A2A] text-white text-xs font-bold border border-[#404040] flex items-center space-x-1">
                                            <span>👁️</span>
                                            <span>Sedang Dilihat</span>
                                        </span>
                                    @else
                                        <a href="{{ route('proposals.show', [$proposal->id, 'version' => $v->nomor_versi]) }}"
                                           class="px-3 py-1 rounded-lg bg-[#0B0B0B] hover:bg-[#252525] border border-[#D4AF37]/40 text-[#D4AF37] hover:text-[#E6C65C] text-xs font-bold transition-all flex items-center space-x-1">
                                            <span>Buka Versi Ini</span>
                                            <span>&rarr;</span>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Catatan Pemohon untuk versi ini -->
                            <div class="mt-2 text-xs bg-[#0B0B0B] p-3 rounded-xl border border-[#222222]">
                                @if($v->catatan_revisi_pemohon)
                                    <div class="text-[#A3A3A3] mb-0.5 font-semibold text-[11px] flex items-center space-x-1">
                                        <span class="text-[#D4AF37]">💬 Catatan Perbaikan Pemohon:</span>
                                    </div>
                                    <p class="text-white italic">"{{ $v->catatan_revisi_pemohon }}"</p>
                                @else
                                    <p class="text-[#737373] italic">Dokumen awal yang pertama kali didaftarkan oleh pemohon ke dalam sistem.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ match($rev->keputusan) { 'disetujui' => 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/40', 'perlu_perbaikan' => 'bg-amber-950/60 text-amber-400 border border-amber-800/40', 'ditolak' => 'bg-rose-950/60 text-rose-400 border border-rose-800/40', default => 'bg-[#2A2A2A] text-[#A3A3A3]' } }}">
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

                    @if($displayedVersion->nomor_versi !== $activeVersion?->nomor_versi)
                        <div class="p-5 rounded-2xl bg-amber-950/30 border border-amber-500/40 text-xs text-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">⚠️</span>
                                <div>
                                    <h4 class="font-bold text-white text-sm">Anda Sedang Melihat Versi Arsip (v{{ $displayedVersion->nomor_versi }}.0)</h4>
                                    <p class="text-[#A3A3A3] mt-0.5">Penetapan telaah dan disposisi pimpinan harus dilakukan pada versi terbaru/aktif (v{{ $activeVersion->nomor_versi }}.0).</p>
                                </div>
                            </div>
                            <a href="{{ route('proposals.show', $proposal->id) }}" class="px-4 py-2 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-black rounded-xl shrink-0 text-center transition-all">
                                Buka Versi Aktif &rarr;
                            </a>
                        </div>
                    @elseif($proposal->status === 'perlu_perbaikan')
                        <div class="p-5 rounded-2xl bg-[#0B0B0B] border border-amber-500/30 text-xs flex items-center space-x-3.5">
                            <span class="text-2xl">⏳</span>
                            <div>
                                <h4 class="font-bold text-amber-400 text-sm">Menunggu Pengajuan Perbaikan Berkas</h4>
                                <p class="text-[#A3A3A3] mt-0.5">Arahan perbaikan telah diterbitkan untuk pemohon. Formulir disposisi akan kembali aktif setelah pemohon mengirimkan perbaikan usulan revisi.</p>
                            </div>
                        </div>
                    @else
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
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    function togglePdfPreview() {
        const container = document.getElementById('pdfPreviewContainer');
        const icon = document.getElementById('btnTogglePdfIcon');
        const text = document.getElementById('btnTogglePdfText');
        if (!container) return;

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            if (icon) icon.textContent = '▲';
            if (text) text.textContent = 'Tutup Pratinjau';
        } else {
            container.classList.add('hidden');
            if (icon) icon.textContent = '▼';
            if (text) text.textContent = 'Lihat di Halaman Ini';
        }
    }
</script>
@endsection
