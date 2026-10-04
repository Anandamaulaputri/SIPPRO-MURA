@extends('layouts.app')

@section('content')
<div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back Link -->
    <a href="{{ route('proposals.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-[#A3A3A3] hover:text-[#D4AF37] transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Daftar Proposal</span>
    </a>

    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Ajukan Usulan Baru</h1>
        <p class="text-sm text-[#A3A3A3] mt-1.5 leading-relaxed">
            Lengkapi data singkat proposal dan unggah dokumen proposal Anda.
        </p>
    </div>

    <!-- Main Form Card -->
    <form id="proposalForm" method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ============================================================ --}}
        {{-- SECTION 1: INFORMASI PROPOSAL --}}
        {{-- ============================================================ --}}
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-lg p-6 sm:p-8 mb-6">
            <div class="flex items-center space-x-2.5 mb-6 pb-4 border-b border-[#2A2A2A]">
                <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">1</span>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Informasi Proposal</h2>
            </div>

            <div class="space-y-5">
                <!-- Nomor Proposal -->
                <div>
                    <label for="nomor_proposal_pengusul" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Nomor Proposal <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="nomor_proposal_pengusul" id="nomor_proposal_pengusul" value="{{ old('nomor_proposal_pengusul') }}" required
                           placeholder="Contoh: 001/POKTAN/IX/2026"
                           class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-[11px] text-[#737373] mt-1">Masukkan nomor yang tercantum pada surat atau proposal Anda.</p>
                    @error('nomor_proposal_pengusul') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Perihal Proposal -->
                <div>
                    <label for="judul_proposal" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Perihal Proposal <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="judul_proposal" id="judul_proposal" value="{{ old('judul_proposal') }}" required
                           placeholder="Contoh: Permohonan Bantuan Pembangunan Jalan Desa"
                           class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-[11px] text-[#737373] mt-1">Masukkan perihal sesuai yang tercantum pada proposal.</p>
                    @error('judul_proposal') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jumlah Dana -->
                <div>
                    <label for="total_anggaran" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Jumlah Dana yang Diajukan <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-bold text-[#A3A3A3]">Rp</span>
                        <input type="text" name="total_anggaran_display" id="total_anggaran_display" value="{{ old('total_anggaran') ? number_format(old('total_anggaran'), 0, ',', '.') : '' }}"
                               placeholder="50.000.000"
                               inputmode="numeric"
                               required
                               class="w-full pl-12 pr-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-[#D4AF37] font-bold text-base font-mono rounded-xl focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                        <input type="hidden" name="total_anggaran" id="total_anggaran" value="{{ old('total_anggaran') }}">
                    </div>
                    <p class="text-[11px] text-[#737373] mt-1">Masukkan total dana yang tercantum dalam proposal.</p>
                    @error('total_anggaran') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Lokasi Kegiatan -->
                <div>
                    <label for="lokasi_kegiatan" class="block text-xs font-bold text-[#A3A3A3] uppercase tracking-wider mb-1.5">
                        Lokasi Kegiatan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="lokasi_kegiatan" id="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" required
                           placeholder="Desa ..., Kecamatan ..., Kabupaten Murung Raya"
                           class="w-full px-4 py-3 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all">
                    <p class="text-[11px] text-[#737373] mt-1">Masukkan lokasi kegiatan yang tercantum dalam proposal.</p>
                    @error('lokasi_kegiatan') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SECTION 2: DOKUMEN PROPOSAL UTAMA --}}
        {{-- ============================================================ --}}
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-lg p-6 sm:p-8 mb-6">
            <div class="flex items-center space-x-2.5 mb-6 pb-4 border-b border-[#2A2A2A]">
                <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">2</span>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Dokumen Proposal Utama</h2>
            </div>

            <p class="text-xs text-[#A3A3A3] mb-5 leading-relaxed">
                Silakan unggah proposal yang telah disiapkan dalam format PDF. Pastikan isi proposal, termasuk RAB dan dokumen yang diperlukan, sudah tercantum dalam file.
            </p>

            <!-- Upload Zone -->
            <div id="uploadZonePdf" class="relative rounded-2xl border-2 border-dashed border-[#2A2A2A] hover:border-[#D4AF37]/50 bg-[#0E0E0E] transition-all cursor-pointer"
                 onclick="document.getElementById('file_proposal').click()">

                <!-- Before Upload State -->
                <div id="uploadStateBefore" class="py-10 px-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-[#1A1A1A] border border-[#2A2A2A] flex items-center justify-center mx-auto mb-4 text-2xl">
                        📄
                    </div>
                    <h3 class="text-sm font-bold text-white mb-1">Upload Proposal Lengkap</h3>
                    <p class="text-xs text-[#A3A3A3] mb-4">Format file: PDF &bull; Maksimal 10 MB</p>
                    <span class="inline-flex items-center space-x-2 px-5 py-2.5 bg-[#D4AF37] hover:bg-[#E6C65C] text-[#0B0B0B] font-bold text-xs rounded-xl shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Pilih File Proposal</span>
                    </span>
                </div>

                <!-- After Upload State -->
                <div id="uploadStateAfter" class="hidden py-6 px-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-950/60 border border-emerald-500/40 flex items-center justify-center text-emerald-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="text-xs font-bold text-emerald-400 mb-0.5">File Proposal Siap Diunggah</p>
                            <p id="pdfFileName" class="text-sm font-bold text-white truncate"></p>
                            <p id="pdfFileSize" class="text-[11px] text-[#A3A3A3]"></p>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <button type="button" onclick="event.stopPropagation(); document.getElementById('file_proposal').click();"
                                    class="px-3 py-1.5 text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] bg-[#1F1F1F] rounded-lg border border-[#2A2A2A] transition-colors">
                                Ganti
                            </button>
                            <button type="button" onclick="event.stopPropagation(); clearFileProposal();"
                                    class="px-3 py-1.5 text-xs font-bold text-rose-400 hover:text-rose-300 bg-[#1F1F1F] rounded-lg border border-[#2A2A2A] transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <input type="file" name="file_proposal" id="file_proposal" accept=".pdf" class="hidden" onchange="handleProposalUpload(this)">
            </div>
            @error('file_proposal') <p class="text-xs text-rose-400 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- ============================================================ --}}
        {{-- SECTION 3: DOKUMEN PENDUKUNG --}}
        {{-- ============================================================ --}}
        <div class="bg-[#151515] rounded-2xl border border-[#2A2A2A] shadow-lg p-6 sm:p-8 mb-6">
            <div class="flex items-center space-x-2.5 mb-6 pb-4 border-b border-[#2A2A2A]">
                <span class="w-7 h-7 rounded-lg bg-[#0B0B0B] text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center text-xs font-black">3</span>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-white">Dokumen Pendukung</h2>
            </div>

            <div class="space-y-4">
                <!-- KTP Penanggung Jawab -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-[#0E0E0E] border border-[#2A2A2A] hover:border-[#D4AF37]/30 transition-colors">
                    <div class="flex items-center space-x-3 min-w-0 flex-grow">
                        <div class="w-9 h-9 rounded-lg bg-[#1A1A1A] border border-[#2A2A2A] flex items-center justify-center text-base flex-shrink-0">🪪</div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white">KTP Penanggung Jawab</p>
                            <p id="badge_ktp_text" class="text-[11px] text-[#737373] truncate">PDF / JPG / PNG &bull; Maks 5 MB</p>
                        </div>
                    </div>
                    <label class="px-3 py-1.5 text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] bg-[#1F1F1F] rounded-lg border border-[#2A2A2A] cursor-pointer transition-colors flex-shrink-0">
                        <span id="btn_ktp_label">+ Upload</span>
                        <input type="file" name="lampiran_ktp" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleSupportDoc(this, 'badge_ktp_text', 'btn_ktp_label')">
                    </label>
                </div>
                @error('lampiran_ktp') <p class="text-xs text-rose-400 -mt-2 mb-2">{{ $message }}</p> @enderror

                <!-- Legalitas / SK Lembaga -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-[#0E0E0E] border border-[#2A2A2A] hover:border-[#D4AF37]/30 transition-colors">
                    <div class="flex items-center space-x-3 min-w-0 flex-grow">
                        <div class="w-9 h-9 rounded-lg bg-[#1A1A1A] border border-[#2A2A2A] flex items-center justify-center text-base flex-shrink-0">📋</div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white">Legalitas / SK Lembaga</p>
                            <p id="badge_org_text" class="text-[11px] text-[#737373] truncate">PDF / JPG / PNG &bull; Maks 10 MB &bull; Opsional untuk perorangan</p>
                        </div>
                    </div>
                    <label class="px-3 py-1.5 text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] bg-[#1F1F1F] rounded-lg border border-[#2A2A2A] cursor-pointer transition-colors flex-shrink-0">
                        <span id="btn_org_label">+ Upload</span>
                        <input type="file" name="lampiran_organisasi" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleSupportDoc(this, 'badge_org_text', 'btn_org_label')">
                    </label>
                </div>
                @error('lampiran_organisasi') <p class="text-xs text-rose-400 -mt-2 mb-2">{{ $message }}</p> @enderror

                <!-- Buku Rekening -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-[#0E0E0E] border border-[#2A2A2A] hover:border-[#D4AF37]/30 transition-colors">
                    <div class="flex items-center space-x-3 min-w-0 flex-grow">
                        <div class="w-9 h-9 rounded-lg bg-[#1A1A1A] border border-[#2A2A2A] flex items-center justify-center text-base flex-shrink-0">🏦</div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white">Buku Rekening</p>
                            <p id="badge_rek_text" class="text-[11px] text-[#737373] truncate">PDF / JPG / PNG &bull; Maks 5 MB</p>
                        </div>
                    </div>
                    <label class="px-3 py-1.5 text-xs font-bold text-[#D4AF37] hover:text-[#E6C65C] bg-[#1F1F1F] rounded-lg border border-[#2A2A2A] cursor-pointer transition-colors flex-shrink-0">
                        <span id="btn_rek_label">+ Upload</span>
                        <input type="file" name="lampiran_rekening" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleSupportDoc(this, 'badge_rek_text', 'btn_rek_label')">
                    </label>
                </div>
                @error('lampiran_rekening') <p class="text-xs text-rose-400 -mt-2 mb-2">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ACTION BUTTONS --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('proposals.index') }}" class="px-5 py-2.5 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                Batal
            </a>
            <button type="button" onclick="openReviewModal()"
                    class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#D4AF37] to-[#E6C65C] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] font-black rounded-xl text-sm shadow-xl shadow-[#D4AF37]/20 transition-all hover:scale-105 flex items-center justify-center space-x-2">
                <span>Lanjutkan Pemeriksaan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>

{{-- ============================================================ --}}
{{-- MODAL: PERIKSA KEMBALI SEBELUM SUBMIT --}}
{{-- ============================================================ --}}
<div id="modalChecklistKetelitian" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/85 backdrop-blur-md transition-opacity" onclick="closeReviewModal()"></div>

    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6">
        <div class="relative transform overflow-hidden rounded-2xl bg-[#151515] border border-[#D4AF37]/40 text-left shadow-2xl w-full max-w-lg my-8">

            <!-- Modal Header -->
            <div class="p-5 sm:p-6 bg-[#181818] border-b border-[#2A2A2A]">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-black text-white">Periksa Kembali Usulan Anda</h3>
                    <button type="button" onclick="closeReviewModal()" class="rounded-lg p-1.5 text-[#737373] hover:text-white hover:bg-[#252525] transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-[#A3A3A3] mt-1">Pastikan semua data sudah benar sebelum mengajukan proposal.</p>
            </div>

            <!-- Modal Body: Review Data -->
            <div class="p-5 sm:p-6 space-y-4">
                <!-- Review Items -->
                <div class="space-y-3">
                    <div class="flex justify-between items-start gap-3">
                        <span class="text-xs text-[#737373] flex-shrink-0">Nomor Proposal</span>
                        <span id="review_nomor" class="text-xs font-bold text-white text-right">-</span>
                    </div>
                    <div class="border-t border-[#2A2A2A]"></div>
                    <div class="flex justify-between items-start gap-3">
                        <span class="text-xs text-[#737373] flex-shrink-0">Perihal</span>
                        <span id="review_perihal" class="text-xs font-bold text-white text-right">-</span>
                    </div>
                    <div class="border-t border-[#2A2A2A]"></div>
                    <div class="flex justify-between items-start gap-3">
                        <span class="text-xs text-[#737373] flex-shrink-0">Jumlah Dana</span>
                        <span id="review_dana" class="text-xs font-bold text-[#D4AF37] text-right font-mono">-</span>
                    </div>
                    <div class="border-t border-[#2A2A2A]"></div>
                    <div class="flex justify-between items-start gap-3">
                        <span class="text-xs text-[#737373] flex-shrink-0">Lokasi</span>
                        <span id="review_lokasi" class="text-xs font-bold text-white text-right">-</span>
                    </div>
                    <div class="border-t border-[#2A2A2A]"></div>
                    <div>
                        <span class="text-xs text-[#737373] block mb-2">Dokumen</span>
                        <div id="review_docs" class="space-y-1.5"></div>
                    </div>
                </div>

                <!-- Pakta Checkbox -->
                <label class="flex items-start space-x-3 p-4 rounded-xl bg-[#0E0E0E] border border-[#2A2A2A] cursor-pointer hover:border-[#D4AF37]/40 transition-colors mt-4">
                    <input type="checkbox" id="chk_pakta" onchange="toggleSubmitButton()"
                           class="mt-0.5 w-4 h-4 rounded text-[#D4AF37] bg-[#0B0B0B] border-[#2A2A2A] focus:ring-[#D4AF37] focus:ring-offset-0 cursor-pointer">
                    <span class="text-xs text-[#A3A3A3] leading-relaxed">
                        Saya memastikan data dan dokumen yang saya ajukan sudah benar dan dapat dipertanggungjawabkan.
                    </span>
                </label>
                <p id="pakta_hint" class="text-[11px] text-[#D4AF37] mt-2 flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Wajib mencentang kotak pernyataan di atas untuk mengaktifkan tombol kirim.</span>
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="p-5 sm:p-6 bg-[#111111] border-t border-[#2A2A2A] flex flex-col sm:flex-row justify-between items-center gap-3">
                <button type="button" onclick="closeReviewModal()"
                        class="w-full sm:w-auto px-4 py-2.5 text-xs font-bold text-[#A3A3A3] hover:text-white rounded-xl transition-colors">
                    ← Kembali & Edit
                </button>
                <button type="button" id="btnSubmitProposal" onclick="submitFinalProposal()" disabled
                        class="w-full sm:w-auto px-7 py-3 rounded-xl text-sm font-black transition-all duration-300 opacity-40 cursor-not-allowed bg-[#2A2A2A] text-[#737373]">
                    Ajukan Proposal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /**
     * Rupiah Formatting for the Jumlah Dana input
     */
    const displayInput = document.getElementById('total_anggaran_display');
    const hiddenInput = document.getElementById('total_anggaran');

    displayInput.addEventListener('input', function() {
        // Remove non-digit characters
        let raw = this.value.replace(/\D/g, '');
        // Store raw numeric value in hidden input
        hiddenInput.value = raw;
        // Format display with dots
        if (raw) {
            this.value = new Intl.NumberFormat('id-ID').format(parseInt(raw));
        }
    });

    /**
     * Proposal PDF Upload Handler
     */
    function handleProposalUpload(input) {
        const before = document.getElementById('uploadStateBefore');
        const after = document.getElementById('uploadStateAfter');
        const zone = document.getElementById('uploadZonePdf');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file proposal (' + (file.size / (1024 * 1024)).toFixed(1) + ' MB) melebihi batas maksimal 10 MB. Silakan kompres atau pilih berkas yang lebih kecil.');
                input.value = '';
                return;
            }

            const sizeMb = (file.size / (1024 * 1024)).toFixed(1);

            document.getElementById('pdfFileName').textContent = file.name;
            document.getElementById('pdfFileSize').textContent = sizeMb + ' MB';

            before.classList.add('hidden');
            after.classList.remove('hidden');
            zone.classList.remove('border-dashed');
            zone.classList.add('border-emerald-500/40');
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
        zone.classList.remove('border-emerald-500/40');
    }

    /**
     * Supporting Document Upload Handler
     */
    function handleSupportDoc(input, textId, btnId) {
        const textEl = document.getElementById(textId);
        const btnEl = document.getElementById(btnId);

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxMb = input.name === 'lampiran_organisasi' ? 10 : 5;
            if (file.size > maxMb * 1024 * 1024) {
                alert('Ukuran file ' + file.name + ' (' + (file.size / (1024 * 1024)).toFixed(1) + ' MB) melebihi batas maksimal ' + maxMb + ' MB. Silakan kompres atau pilih berkas yang lebih kecil.');
                input.value = '';
                return;
            }
            const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
            textEl.textContent = '✓ ' + file.name + ' (' + sizeMb + ' MB)';
            textEl.classList.remove('text-[#737373]');
            textEl.classList.add('text-emerald-400');
            btnEl.textContent = 'Ganti';
        }
    }

    /**
     * Review Modal Logic
     */
    function openReviewModal() {
        // Validate required fields
        const nomor = document.getElementById('nomor_proposal_pengusul').value.trim();
        const perihal = document.getElementById('judul_proposal').value.trim();
        const danaRaw = document.getElementById('total_anggaran').value;
        const lokasi = document.getElementById('lokasi_kegiatan').value.trim();

        if (!nomor || !perihal || !danaRaw || !lokasi) {
            // Trigger native validation
            document.getElementById('proposalForm').reportValidity();
            return;
        }

        if (parseInt(danaRaw) < 100000) {
            alert('Jumlah dana minimal yang dapat diajukan adalah Rp 100.000.');
            return;
        }

        // Populate review data
        document.getElementById('review_nomor').textContent = nomor;
        document.getElementById('review_perihal').textContent = perihal;
        document.getElementById('review_dana').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(parseInt(danaRaw));
        document.getElementById('review_lokasi').textContent = lokasi;

        // Documents review
        const docsContainer = document.getElementById('review_docs');
        docsContainer.innerHTML = '';

        const fileProposal = document.getElementById('file_proposal');
        addDocReviewItem(docsContainer, 'Proposal PDF', fileProposal.files && fileProposal.files[0]);

        const lampKtp = document.querySelector('[name="lampiran_ktp"]');
        addDocReviewItem(docsContainer, 'KTP Penanggung Jawab', lampKtp.files && lampKtp.files[0]);

        const lampOrg = document.querySelector('[name="lampiran_organisasi"]');
        addDocReviewItem(docsContainer, 'Legalitas / SK', lampOrg.files && lampOrg.files[0]);

        const lampRek = document.querySelector('[name="lampiran_rekening"]');
        addDocReviewItem(docsContainer, 'Buku Rekening', lampRek.files && lampRek.files[0]);

        // Reset checkbox
        document.getElementById('chk_pakta').checked = false;
        toggleSubmitButton();

        // Show modal
        document.getElementById('modalChecklistKetelitian').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function addDocReviewItem(container, label, file) {
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 text-xs';
        if (file) {
            div.innerHTML = '<span class="text-emerald-400 font-bold">✓</span><span class="text-white">' + label + '</span><span class="text-[#737373] truncate">— ' + file.name + '</span>';
        } else {
            div.innerHTML = '<span class="text-[#737373]">—</span><span class="text-[#737373]">' + label + ' (belum diunggah)</span>';
        }
        container.appendChild(div);
    }

    function closeReviewModal() {
        document.getElementById('modalChecklistKetelitian').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleSubmitButton() {
        const chk = document.getElementById('chk_pakta');
        const btn = document.getElementById('btnSubmitProposal');
        const hint = document.getElementById('pakta_hint');

        if (chk.checked) {
            btn.disabled = false;
            btn.className = 'w-full sm:w-auto px-7 py-3 rounded-xl text-sm font-black transition-all duration-300 bg-gradient-to-r from-[#D4AF37] to-[#E6C65C] hover:from-[#E6C65C] hover:to-[#D4AF37] text-[#0B0B0B] shadow-lg shadow-[#D4AF37]/30 cursor-pointer hover:scale-105';
            if (hint) hint.classList.add('hidden');
        } else {
            btn.disabled = true;
            btn.className = 'w-full sm:w-auto px-7 py-3 rounded-xl text-sm font-black transition-all duration-300 opacity-40 cursor-not-allowed bg-[#2A2A2A] text-[#737373]';
            if (hint) hint.classList.remove('hidden');
        }
    }

    function submitFinalProposal() {
        const btn = document.getElementById('btnSubmitProposal');
        if (btn.disabled) return;

        // Pastikan hidden input anggaran tersinkronisasi sebelum submit
        const displayVal = document.getElementById('total_anggaran_display').value;
        if (displayVal) {
            document.getElementById('total_anggaran').value = displayVal.replace(/\D/g, '');
        }

        btn.disabled = true;
        btn.innerHTML = 'Memproses Pengiriman Proposal...';

        document.getElementById('proposalForm').submit();
    }
</script>
@endpush
@endsection
