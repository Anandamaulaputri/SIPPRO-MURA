@extends('layouts.app')

@section('content')
<div class="py-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb & Navigasi -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('proposals.show', $proposal->id) }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Detail Proposal</span>
        </a>

        <div class="flex items-center space-x-2">
            <span class="text-xs text-[#A3A3A3] font-mono bg-[#151515] border border-[#2A2A2A] px-2.5 py-1 rounded-lg">
                Revisi Menuju: <strong class="text-[#D4AF37]">v{{ $proposal->versi_aktif + 1 }}.0</strong>
            </span>
        </div>
    </div>

    <!-- Header Banner Informasi Proposal -->
    <div class="bg-[#151515] rounded-3xl border border-[#2A2A2A] shadow-xl overflow-hidden mb-6">
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#181818] via-[#151515] to-[#121212] border-b border-[#2A2A2A] flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded bg-[#0B0B0B] text-[#D4AF37] border border-[#2A2A2A]">
                        {{ $proposal->nomor_registrasi }}
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#1F1F1F] text-[#A3A3A3] border border-[#2A2A2A]">
                        {{ $proposal->category->nama_kategori }}
                    </span>
                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $proposal->status_color }}">
                        {{ $proposal->status_label }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-white leading-snug">Ajukan Perbaikan Proposal</h1>
                <p class="text-xs text-[#A3A3A3] mt-1.5">
                    Perbaiki dokumen usulan atau rincian anggaran sesuai arahan evaluasi pimpinan daerah.
                </p>
            </div>

            <div class="md:text-right shrink-0">
                <span class="text-[11px] text-[#737373] uppercase tracking-wider block font-bold">Versi Saat Ini</span>
                <span class="text-lg font-mono font-bold text-white">v{{ $proposal->versi_aktif }}.0</span>
                <span class="text-xs text-[#D4AF37] block mt-0.5">&rarr; Akan diterbitkan sebagai v{{ $proposal->versi_aktif + 1 }}.0</span>
            </div>
        </div>
    </div>

    <!-- Catatan Perbaikan Wakil Bupati (READ-ONLY) -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-amber-950/40 via-[#181818] to-[#151515] border-2 border-amber-500/50 shadow-lg mb-8">
        <div class="flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-950/80 border border-amber-500/50 flex items-center justify-center text-amber-400 text-lg shrink-0 mt-0.5">
                ✏️
            </div>
            <div class="flex-grow min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-2">
                    <h3 class="text-sm font-black text-amber-300 uppercase tracking-wide">
                        Catatan & Arahan Perbaikan dari Wakil Bupati
                    </h3>
                    <span class="text-[11px] text-[#A3A3A3] font-mono">
                        {{ $latestReview?->tanggal_keputusan ? $latestReview->tanggal_keputusan->translatedFormat('d F Y, H:i') : '' }} WIB
                    </span>
                </div>
                <div class="p-4 rounded-xl bg-[#0B0B0B] border border-amber-500/30 text-white text-xs sm:text-sm leading-relaxed italic">
                    "{{ $latestReview?->catatan_pimpinan ?? 'Harap melengkapi dokumen dan merapikan rincian anggaran sesuai arahan.' }}"
                </div>
                <p class="text-[11px] text-amber-400/80 mt-2 flex items-center space-x-1.5">
                    <span>💡</span>
                    <span>Pastikan Anda menjawab seluruh poin perbaikan di atas sebelum mengirimkan kembali berkas revisi.</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Formulir Perbaikan Proposal -->
    <form action="{{ route('proposals.submit_revision', $proposal->id) }}" method="POST" enctype="multipart/form-data" id="revisionForm" class="space-y-8">
        @csrf

        {{-- SECTION 1: PERBAIKAN INFORMASI USULAN --}}
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-lg p-6 sm:p-8">
            <div class="flex items-center space-x-2.5 mb-6 pb-4 border-b border-[#2A2A2A]">
                <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">1</span>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Perbaikan Substansi Usulan</h2>
            </div>

            <div class="space-y-5">
                <!-- Perihal Proposal -->
                <div>
                    <label for="judul_proposal" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Perihal / Judul Usulan Proposal <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="judul_proposal" id="judul_proposal" value="{{ old('judul_proposal', $proposal->judul_proposal) }}" required
                           class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    @error('judul_proposal') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Grid Lokasi & Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="lokasi_kegiatan" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                            Lokasi Kegiatan <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="lokasi_kegiatan" id="lokasi_kegiatan" value="{{ old('lokasi_kegiatan', $activeVersion?->lokasi_kegiatan) }}" required
                               placeholder="Nama desa, kecamatan, atau lokasi acara..."
                               class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('lokasi_kegiatan') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_kegiatan" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                            Estimasi Tanggal Kegiatan
                        </label>
                        <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $activeVersion?->tanggal_kegiatan?->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        @error('tanggal_kegiatan') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Latar Belakang -->
                <div>
                    <label for="latar_belakang" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Latar Belakang Usulan <span class="text-rose-400">*</span>
                    </label>
                    <textarea name="latar_belakang" id="latar_belakang" rows="4" required
                              class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all leading-relaxed">{{ old('latar_belakang', $activeVersion?->latar_belakang) }}</textarea>
                    @error('latar_belakang') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Maksud & Tujuan -->
                <div>
                    <label for="tujuan" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Maksud & Tujuan Kegiatan
                    </label>
                    <textarea name="tujuan" id="tujuan" rows="3"
                              class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all leading-relaxed">{{ old('tujuan', $activeVersion?->tujuan) }}</textarea>
                    @error('tujuan') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- SECTION 2: PERBAIKAN RENCANA ANGGARAN BIAYA (RAB) --}}
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-lg p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#2A2A2A]">
                <div class="flex items-center space-x-2.5">
                    <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">2</span>
                    <div>
                        <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Perbaikan Rencana Anggaran Biaya (RAB)</h2>
                        <p class="text-[11px] text-[#A3A3A3] mt-0.5">Sesuaikan nominal rincian item kebutuhan sesuai arahan pimpinan</p>
                    </div>
                </div>

                <button type="button" onclick="addRabRow()"
                        class="px-3.5 py-1.5 bg-[#1F1F1F] hover:bg-[#252525] text-[#D4AF37] hover:text-[#E6C65C] border border-[#D4AF37]/40 font-bold rounded-xl text-xs transition-all flex items-center space-x-1.5">
                    <span>+ Tambah Baris RAB</span>
                </button>
            </div>

            <!-- Tabel Dinamis RAB -->
            <div class="overflow-x-auto rounded-xl border border-[#2A2A2A] mb-4">
                <table class="w-full text-left text-xs" id="rabTable">
                    <thead class="bg-[#111111] text-[#A3A3A3] font-bold uppercase tracking-wider border-b border-[#2A2A2A]">
                        <tr>
                            <th class="py-3 px-4 w-2/5">Nama Barang / Uraian Kebutuhan</th>
                            <th class="py-3 px-3 text-center w-24">Volume</th>
                            <th class="py-3 px-3 text-center w-28">Satuan</th>
                            <th class="py-3 px-4 text-right w-44">Harga Satuan (Rp)</th>
                            <th class="py-3 px-4 text-right w-40">Subtotal</th>
                            <th class="py-3 px-3 text-center w-14">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rabTableBody" class="divide-y divide-[#2A2A2A]">
                        <!-- Baris RAB akan diisi via JavaScript -->
                    </tbody>
                    <tfoot class="bg-[#111111] border-t-2 border-[#2A2A2A] font-bold text-white">
                        <tr>
                            <td colspan="4" class="py-3.5 px-4 text-right text-xs uppercase tracking-wider text-[#A3A3A3]">
                                Total Anggaran Usulan Revisi:
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-base text-[#D4AF37]" id="totalAnggaranText">
                                Rp 0
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Hidden Input Total Anggaran -->
            <input type="hidden" name="total_anggaran" id="total_anggaran" value="{{ old('total_anggaran', $proposal->total_anggaran) }}">
            @error('total_anggaran') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            <p class="text-[11px] text-[#737373]">
                * Total anggaran dihitung otomatis dari perkalian volume &times; harga satuan seluruh item rincian. Minimal anggaran Rp 5.000.000.
            </p>
        </div>

        {{-- SECTION 3: UNGGAH DOKUMEN PROPOSAL PDF REVISI --}}
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-lg p-6 sm:p-8">
            <div class="flex items-center space-x-2.5 mb-6 pb-4 border-b border-[#2A2A2A]">
                <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">3</span>
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Dokumen Proposal PDF Terbaru</h2>
                    <p class="text-[11px] text-[#A3A3A3] mt-0.5">Unggah berkas PDF proposal yang telah disesuaikan</p>
                </div>
            </div>

            <!-- Info Dokumen Versi Sebelumnya -->
            @if($activeVersion?->file_proposal)
                <div class="mb-4 p-4 rounded-xl bg-[#0E0E0E] border border-[#2A2A2A] flex items-center justify-between">
                    <div class="flex items-center space-x-3 min-w-0">
                        <span class="text-2xl">📄</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-[#D4AF37] font-bold uppercase tracking-wider block">Dokumen Versi Saat Ini (v{{ $activeVersion->nomor_versi }}.0)</span>
                            <span class="text-xs text-white font-bold truncate block" title="{{ basename($activeVersion->file_proposal) }}">{{ basename($activeVersion->file_proposal) }}</span>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $activeVersion->file_proposal) }}" target="_blank"
                       class="px-3 py-1.5 rounded-lg bg-[#1F1F1F] text-[#D4AF37] hover:text-[#E6C65C] border border-[#2A2A2A] text-xs font-semibold shrink-0">
                        Buka Dokumen Lama
                    </a>
                </div>
            @endif

            <!-- Upload Zone PDF Revisi -->
            <div id="uploadZonePdf" class="relative rounded-2xl border-2 border-dashed border-[#2A2A2A] hover:border-[#D4AF37]/50 bg-[#0E0E0E] transition-all cursor-pointer p-6 text-center"
                 onclick="document.getElementById('file_proposal').click()">
                
                <div id="uploadStateBefore">
                    <div class="w-12 h-12 rounded-2xl bg-[#1A1A1A] border border-[#2A2A2A] flex items-center justify-center mx-auto mb-3 text-xl">
                        📤
                    </div>
                    <h4 class="text-xs font-bold text-white mb-1">Unggah PDF Proposal Hasil Revisi (Opsional jika tidak ada perubahan PDF)</h4>
                    <p class="text-[11px] text-[#737373] mb-3">Format PDF &bull; Maksimal 10 MB &bull; Berkas versi lama tetap tersimpan aman</p>
                    <span class="inline-flex items-center space-x-2 px-4 py-2 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-xs rounded-xl shadow-md transition-all">
                        <span>Pilih Berkas PDF Baru</span>
                    </span>
                </div>

                <div id="uploadStateAfter" class="hidden text-left">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-emerald-950/60 border border-emerald-500/40 flex items-center justify-center text-emerald-400 shrink-0">
                                ✓
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-emerald-400">Berkas PDF Revisi Baru Terpilih:</p>
                                <p id="pdfFileName" class="text-xs font-bold text-white truncate"></p>
                                <p id="pdfFileSize" class="text-[10px] text-[#A3A3A3]"></p>
                            </div>
                        </div>
                        <button type="button" onclick="event.stopPropagation(); clearFileProposal();"
                                class="px-3 py-1.5 text-xs font-bold text-rose-400 hover:text-rose-300 bg-[#1F1F1F] rounded-lg border border-[#2A2A2A] transition-colors">
                            Hapus Pilihan
                        </button>
                    </div>
                </div>

                <input type="file" name="file_proposal" id="file_proposal" accept=".pdf" class="hidden" onchange="handleProposalUpload(this)">
            </div>
            @error('file_proposal') <p class="text-xs text-rose-400 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- SECTION 4: CATATAN PERBAIKAN PEMOHON (WAJIB) --}}
        <div class="bg-[#151515] rounded-2xl border-2 border-[#D4AF37]/40 shadow-xl p-6 sm:p-8">
            <div class="flex items-center space-x-2.5 mb-4 pb-4 border-b border-[#2A2A2A]">
                <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">4</span>
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Catatan Perbaikan Pemohon <span class="text-rose-400">*</span></h2>
                    <p class="text-[11px] text-[#A3A3A3] mt-0.5">Jelaskan poin perbaikan yang telah Anda lakukan agar memudahkan penelaahan ulang oleh Wakil Bupati</p>
                </div>
            </div>

            <textarea name="catatan_revisi_pemohon" id="catatan_revisi_pemohon" rows="4" required
                      placeholder="Jelaskan perubahan atau perbaikan yang telah dilakukan pada proposal. Contoh: Telah melengkapi rincian biaya sewa sound system dan menyesuaikan total anggaran menjadi Rp 18.000.000 sesuai instruksi pimpinan."
                      class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all leading-relaxed">{{ old('catatan_revisi_pemohon') }}</textarea>
            @error('catatan_revisi_pemohon') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            <p class="text-[11px] text-[#737373] mt-2">
                * Catatan ini akan dicatat bersama Versi {{ $proposal->versi_aktif + 1 }}.0 dan langsung dibaca oleh Wakil Bupati.
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-2">
            <a href="{{ route('proposals.show', $proposal->id) }}" class="px-5 py-3 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                Batal & Kembali
            </a>

            <button type="submit" id="btnSubmitRevision"
                    class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#D4AF37] to-[#C5A028] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black rounded-xl text-sm shadow-xl shadow-[#D4AF37]/25 transition-all hover:scale-105 flex items-center justify-center space-x-2">
                <span>Kirim Perbaikan Proposal (Versi {{ $proposal->versi_aktif + 1 }}.0)</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Initial RAB Data from controller
    const initialRabItems = @json($rabItems);
    const fallbackTotal = {{ (float) $proposal->total_anggaran }};

    document.addEventListener('DOMContentLoaded', function () {
        if (Array.isArray(initialRabItems) && initialRabItems.length > 0) {
            initialRabItems.forEach(item => {
                addRabRow(item.item, item.volume, item.satuan, item.biaya);
            });
        } else {
            // Add initial single row with proposal budget
            addRabRow('Kebutuhan Pelaksanaan Kegiatan Usulan', 1, 'paket', fallbackTotal);
        }
        recalculateRabTotal();
    });

    function addRabRow(item = '', volume = 1, satuan = 'paket', biaya = 0) {
        const tbody = document.getElementById('rabTableBody');
        const rowIndex = tbody.children.length;

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-[#1A1A1A] transition-colors';
        tr.innerHTML = `
            <td class="py-2.5 px-3">
                <input type="text" name="rab_items[${rowIndex}][item]" value="${escapeHtml(item)}" required
                       placeholder="Nama barang / uraian kegiatan..."
                       class="w-full px-3 py-2 bg-[#0B0B0B] border border-[#2A2A2A] text-white rounded-lg text-xs focus:border-[#D4AF37] outline-none">
            </td>
            <td class="py-2.5 px-2">
                <input type="number" name="rab_items[${rowIndex}][volume]" value="${volume}" min="1" step="1" required
                       oninput="updateRowSubtotal(this)"
                       class="rab-volume w-full px-2 py-2 bg-[#0B0B0B] border border-[#2A2A2A] text-white text-center rounded-lg text-xs focus:border-[#D4AF37] outline-none">
            </td>
            <td class="py-2.5 px-2">
                <input type="text" name="rab_items[${rowIndex}][satuan]" value="${escapeHtml(satuan)}" required
                       placeholder="Paket/Unit..."
                       class="w-full px-2 py-2 bg-[#0B0B0B] border border-[#2A2A2A] text-white text-center rounded-lg text-xs focus:border-[#D4AF37] outline-none">
            </td>
            <td class="py-2.5 px-3">
                <input type="number" name="rab_items[${rowIndex}][biaya]" value="${biaya}" min="0" step="1000" required
                       oninput="updateRowSubtotal(this)"
                       class="rab-biaya w-full px-3 py-2 bg-[#0B0B0B] border border-[#2A2A2A] text-white text-right font-mono rounded-lg text-xs focus:border-[#D4AF37] outline-none">
            </td>
            <td class="py-2.5 px-3 text-right font-mono font-bold text-white rab-subtotal">
                Rp 0
            </td>
            <td class="py-2.5 px-2 text-center">
                <button type="button" onclick="removeRabRow(this)"
                        class="p-1.5 rounded-lg text-rose-400 hover:text-rose-300 hover:bg-rose-950/40 transition-colors" title="Hapus Baris">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        updateRowSubtotal(tr.querySelector('.rab-volume'));
    }

    function removeRabRow(button) {
        const tbody = document.getElementById('rabTableBody');
        if (tbody.children.length <= 1) {
            alert('Minimal harus ada 1 baris item pada Rencana Anggaran Biaya (RAB).');
            return;
        }
        button.closest('tr').remove();
        recalculateRabTotal();
    }

    function updateRowSubtotal(input) {
        const tr = input.closest('tr');
        const volume = parseFloat(tr.querySelector('.rab-volume').value) || 0;
        const biaya = parseFloat(tr.querySelector('.rab-biaya').value) || 0;
        const subtotal = volume * biaya;

        tr.querySelector('.rab-subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        recalculateRabTotal();
    }

    function recalculateRabTotal() {
        const rows = document.querySelectorAll('#rabTableBody tr');
        let total = 0;

        rows.forEach(tr => {
            const vol = parseFloat(tr.querySelector('.rab-volume')?.value) || 0;
            const biaya = parseFloat(tr.querySelector('.rab-biaya')?.value) || 0;
            total += (vol * biaya);
        });

        document.getElementById('total_anggaran').value = total;
        document.getElementById('totalAnggaranText').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/[&<>"']/g, function (m) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[m];
        });
    }

    function handleProposalUpload(input) {
        const before = document.getElementById('uploadStateBefore');
        const after = document.getElementById('uploadStateAfter');
        const zone = document.getElementById('uploadZonePdf');
        const nameEl = document.getElementById('pdfFileName');
        const sizeEl = document.getElementById('pdfFileSize');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                alert('Dokumen proposal harus berformat PDF.');
                input.value = '';
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file proposal (' + (file.size / (1024 * 1024)).toFixed(1) + ' MB) melebihi batas maksimal 10 MB.');
                input.value = '';
                return;
            }

            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            before.classList.add('hidden');
            after.classList.remove('hidden');
            zone.classList.remove('border-dashed');
            zone.classList.add('border-emerald-500/50');
        }
    }

    function clearFileProposal() {
        const input = document.getElementById('file_proposal');
        const before = document.getElementById('uploadStateBefore');
        const after = document.getElementById('uploadStateAfter');
        const zone = document.getElementById('uploadZonePdf');

        input.value = '';
        after.classList.add('hidden');
        before.classList.remove('hidden');
        zone.classList.add('border-dashed');
        zone.classList.remove('border-emerald-500/50');
    }
</script>
@endpush
@endsection
