<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Disposition;
use App\Models\Proposal;
use App\Models\ProposalAttachment;
use App\Models\ProposalVersion;
use App\Models\ReviewDecision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');
        $search = $request->input('search');
        $disposisi = $request->input('disposisi');

        $query = Proposal::with([
            'category',
            'user.profile',
            'latestVersion',
            'reviewDecisions.reviewer',
            'versions.disposition.petugas',
            'dispositions.petugas',
        ]);

        if ($user->isPengusul()) {
            $query->where('user_id', $user->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($disposisi === 'menunggu') {
            $query->where('status', 'diajukan')->waitingDisposition();
        } elseif ($disposisi === 'selesai') {
            $query->where('status', 'diajukan')->disposed();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_proposal', 'like', "%{$search}%")
                    ->orWhere('nomor_registrasi', 'like', "%{$search}%");
            });
        }

        $proposals = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('proposals.index', compact('proposals', 'categories', 'status', 'search', 'disposisi'));
    }

    public function create()
    {
        $user = Auth::user();
        if (! $user->isPengusul()) {
            return redirect()->route('proposals.index')->with('error', 'Hanya akun pengusul yang dapat membuat permohonan proposal.');
        }

        $categories = Category::all();
        $profile = $user->profile;

        return view('proposals.create', compact('categories', 'profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Normalisasi total_anggaran jika dikirim dengan pemisah ribuan (titik/koma) atau dari input display
        $anggaranRaw = $request->input('total_anggaran');
        if (empty($anggaranRaw) && $request->has('total_anggaran_display')) {
            $anggaranRaw = $request->input('total_anggaran_display');
        }
        if (is_string($anggaranRaw)) {
            $cleaned = preg_replace('/[^\d]/', '', $anggaranRaw);
            $request->merge(['total_anggaran' => $cleaned !== '' ? (float) $cleaned : null]);
        }

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'judul_proposal' => ['required', 'string', 'max:255'],
            'total_anggaran' => ['required', 'numeric', 'min:100000'],
            'lokasi_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'latar_belakang' => ['nullable', 'string'],
            'tujuan' => ['nullable', 'string'],
            'nomor_proposal_pengusul' => ['nullable', 'string', 'max:100'],
            'rincian_rab' => ['nullable', 'string'],
            'rab_items' => ['nullable', 'array'],
            'rab_items.*.item' => ['nullable', 'string', 'max:255'],
            'rab_items.*.volume' => ['nullable', 'numeric'],
            'rab_items.*.satuan' => ['nullable', 'string', 'max:50'],
            'rab_items.*.biaya' => ['nullable', 'numeric'],
            'file_proposal' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // 10MB max
            'lampiran_ktp' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'lampiran_organisasi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_rekening' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'judul_proposal.required' => 'Perihal proposal wajib diisi.',
            'total_anggaran.required' => 'Jumlah dana yang diajukan wajib diisi.',
            'total_anggaran.min' => 'Jumlah dana minimal yang diajukan adalah Rp 100.000.',
            'lokasi_kegiatan.required' => 'Lokasi kegiatan wajib diisi.',
            'file_proposal.mimes' => 'File dokumen proposal utama wajib berformat PDF.',
            'file_proposal.max' => 'Ukuran file proposal maksimal 10 MB.',
            'file_proposal.uploaded' => 'File proposal gagal diunggah (melebihi batas ukuran maksimal 10 MB).',
            'lampiran_ktp.mimes' => 'File lampiran KTP harus berupa PDF, JPG, atau PNG.',
            'lampiran_ktp.max' => 'Ukuran file lampiran KTP maksimal 5 MB.',
            'lampiran_ktp.uploaded' => 'Lampiran KTP gagal diunggah (melebihi batas ukuran maksimal 5 MB).',
            'lampiran_organisasi.mimes' => 'File SK/Organisasi harus berupa PDF, JPG, atau PNG.',
            'lampiran_organisasi.max' => 'Ukuran file SK/Organisasi maksimal 10 MB.',
            'lampiran_organisasi.uploaded' => 'Lampiran SK/Organisasi gagal diunggah (melebihi batas ukuran maksimal 10 MB).',
            'lampiran_rekening.mimes' => 'File buku rekening harus berupa PDF, JPG, atau PNG.',
            'lampiran_rekening.max' => 'Ukuran file buku rekening maksimal 5 MB.',
            'lampiran_rekening.uploaded' => 'Lampiran buku rekening gagal diunggah (melebihi batas ukuran maksimal 5 MB).',
        ]);

        return DB::transaction(function () use ($request, $user, $validated) {
            // Normalisasi Rincian RAB dari tabel dinamis atau input JSON
            $rabJson = null;
            if (! empty($request->input('rab_items')) && is_array($request->input('rab_items'))) {
                $formattedItems = [];
                foreach ($request->input('rab_items') as $row) {
                    if (! empty($row['item'])) {
                        $formattedItems[] = [
                            'item' => trim($row['item']),
                            'volume' => isset($row['volume']) ? (int) $row['volume'] : 1,
                            'satuan' => ! empty($row['satuan']) ? trim($row['satuan']) : 'paket',
                            'biaya' => isset($row['biaya']) ? (float) $row['biaya'] : 0,
                        ];
                    }
                }
                if (! empty($formattedItems)) {
                    $rabJson = json_encode($formattedItems, JSON_UNESCAPED_UNICODE);
                }
            }

            if (! $rabJson && ! empty($request->input('rincian_rab'))) {
                $rawRab = $request->input('rincian_rab');
                $decoded = json_decode($rawRab, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $rabJson = $rawRab;
                } else {
                    $rabJson = $rawRab;
                }
            }

            // Resolve category: use provided value or default to first category
            $categoryId = $validated['category_id'] ?? Category::first()?->id;

            // Generate Nomor Registrasi unik: PROP-YYYYMM-XXXX
            $datePrefix = Carbon::now()->format('Ym');
            $lastProposal = Proposal::where('nomor_registrasi', 'like', "PROP-{$datePrefix}-%")->latest('id')->first();
            $nextSeq = 1;
            if ($lastProposal) {
                $parts = explode('-', $lastProposal->nomor_registrasi);
                $nextSeq = intval(end($parts)) + 1;
            }
            $nomorRegistrasi = sprintf('PROP-%s-%04d', $datePrefix, $nextSeq);

            // Build latar_belakang: include nomor proposal pengusul if provided
            $latarBelakang = $validated['latar_belakang'] ?? 'Lihat dokumen proposal PDF terlampir.';
            $nomorProposalPengusul = $validated['nomor_proposal_pengusul'] ?? null;
            if ($nomorProposalPengusul) {
                $latarBelakang = "Nomor Surat/Proposal Pengusul: {$nomorProposalPengusul}\n\n{$latarBelakang}";
            }

            // Simpan Proposal Induk
            $proposal = Proposal::create([
                'nomor_registrasi' => $nomorRegistrasi,
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'judul_proposal' => $validated['judul_proposal'],
                'total_anggaran' => $validated['total_anggaran'],
                'status' => 'diajukan',
                'versi_aktif' => 1,
                'tanggal_kirim' => now(),
            ]);

            // Handle file proposal jika diunggah
            $fileProposalPath = null;
            if ($request->hasFile('file_proposal')) {
                $file = $request->file('file_proposal');
                $originalName = $file->getClientOriginalName();
                $cleanName = str_replace(['#', '%', '?', '\\', '/'], '_', $originalName);
                $fileProposalPath = $file->storeAs('proposals/'.$proposal->nomor_registrasi, $cleanName, 'public');
            }

            // Simpan Versi 1
            $version = ProposalVersion::create([
                'proposal_id' => $proposal->id,
                'nomor_versi' => 1,
                'latar_belakang' => $latarBelakang,
                'tujuan' => $validated['tujuan'] ?? 'Lihat dokumen proposal PDF terlampir.',
                'lokasi_kegiatan' => $validated['lokasi_kegiatan'],
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
                'rincian_rab' => $rabJson,
                'file_proposal' => $fileProposalPath,
            ]);

            // Handle lampiran KTP
            if ($request->hasFile('lampiran_ktp')) {
                $file = $request->file('lampiran_ktp');
                $path = $file->store('attachments', 'public');
                ProposalAttachment::create([
                    'version_id' => $version->id,
                    'jenis_lampiran' => 'ktp',
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'ukuran_file' => $file->getSize(),
                ]);
            }

            // Handle lampiran legalitas / surat
            if ($request->hasFile('lampiran_organisasi')) {
                $file = $request->file('lampiran_organisasi');
                $path = $file->store('attachments', 'public');
                ProposalAttachment::create([
                    'version_id' => $version->id,
                    'jenis_lampiran' => 'akta',
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'ukuran_file' => $file->getSize(),
                ]);
            }

            // Handle lampiran buku rekening
            if ($request->hasFile('lampiran_rekening')) {
                $file = $request->file('lampiran_rekening');
                $path = $file->store('attachments', 'public');
                ProposalAttachment::create([
                    'version_id' => $version->id,
                    'jenis_lampiran' => 'rekening',
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'ukuran_file' => $file->getSize(),
                ]);
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Mengajukan Proposal Baru',
                'keterangan' => "Mengajukan proposal {$proposal->nomor_registrasi}: {$proposal->judul_proposal}",
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('proposals.show', $proposal->id)
                ->with('success', "Proposal berhasil diajukan dengan Nomor Registrasi: {$proposal->nomor_registrasi}. Silakan simpan nomor registrasi untuk melacak status!");
        });
    }

    public function show(Request $request, $id)
    {
        $proposal = Proposal::with([
            'category',
            'user.profile',
            'versions.attachments',
            'versions.reviewDecisions.reviewer',
            'versions.disposition.petugas',
            'dispositions.petugas',
            'reviewDecisions.reviewer',
        ])->findOrFail($id);

        $user = Auth::user();

        // Otorisasi: Pengusul hanya boleh melihat miliknya, sedangkan Wabup dan Admin bisa melihat semua
        if ($user->isPengusul() && $proposal->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke proposal ini.');
        }

        $activeVersion = $proposal->versions->firstWhere('nomor_versi', $proposal->versi_aktif) ?? $proposal->versions->first();
        $selectedVersionNum = $request->query('version') ? (int) $request->query('version') : $proposal->versi_aktif;
        $displayedVersion = $proposal->versions->firstWhere('nomor_versi', $selectedVersionNum) ?? $activeVersion;

        $activeDisposition = $activeVersion?->disposition;
        $displayedDisposition = $displayedVersion?->disposition;

        return view('proposals.show', compact('proposal', 'activeVersion', 'displayedVersion', 'user', 'activeDisposition', 'displayedDisposition'));
    }

    public function revise($id)
    {
        $user = Auth::user();
        if (! $user->isPengusul()) {
            abort(403, 'Hanya akun pengusul yang dapat mengajukan perbaikan proposal.');
        }

        $proposal = Proposal::with([
            'category',
            'user.profile',
            'versions.attachments',
            'reviewDecisions.reviewer',
        ])->findOrFail($id);

        if ($proposal->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk merevisi proposal ini.');
        }

        if ($proposal->status !== 'perlu_perbaikan') {
            return redirect()->route('proposals.show', $proposal->id)
                ->with('error', 'Hanya proposal dengan status "Perlu Perbaikan" yang dapat diajukan perbaikan.');
        }

        $activeVersion = $proposal->versions->firstWhere('nomor_versi', $proposal->versi_aktif) ?? $proposal->latestVersion;
        $latestReview = $proposal->reviewDecisions->where('keputusan', 'perlu_perbaikan')->last() ?? $proposal->reviewDecisions->last();

        $rabItems = [];
        if (! empty($activeVersion?->rincian_rab)) {
            $decoded = json_decode($activeVersion->rincian_rab, true);
            if (is_array($decoded)) {
                $rabItems = $decoded;
            }
        }

        return view('proposals.revise', compact('proposal', 'activeVersion', 'latestReview', 'rabItems'));
    }

    public function submitRevision(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user->isPengusul()) {
            abort(403, 'Hanya akun pengusul yang dapat mengajukan perbaikan proposal.');
        }

        $proposal = Proposal::with(['versions.attachments'])->findOrFail($id);

        if ($proposal->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk merevisi proposal ini.');
        }

        if ($proposal->status !== 'perlu_perbaikan') {
            return redirect()->route('proposals.show', $proposal->id)
                ->with('error', 'Hanya proposal dengan status "Perlu Perbaikan" yang dapat diajukan perbaikan.');
        }

        // Normalisasi format total_anggaran jika mengandung titik ribuan
        if ($request->has('total_anggaran') && is_string($request->input('total_anggaran'))) {
            $rawAnggaran = preg_replace('/[^0-9]/', '', $request->input('total_anggaran'));
            if ($rawAnggaran !== '') {
                $request->merge(['total_anggaran' => (float) $rawAnggaran]);
            }
        }

        $validated = $request->validate([
            'judul_proposal' => ['required', 'string', 'max:255'],
            'lokasi_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'latar_belakang' => ['required', 'string', 'min:20'],
            'tujuan' => ['nullable', 'string'],
            'total_anggaran' => ['required', 'numeric', 'min:5000000'],
            'rab_items' => ['nullable', 'array'],
            'rab_items.*.item' => ['nullable', 'string'],
            'rab_items.*.volume' => ['nullable', 'numeric'],
            'rab_items.*.satuan' => ['nullable', 'string'],
            'rab_items.*.biaya' => ['nullable', 'numeric'],
            'file_proposal' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'catatan_revisi_pemohon' => ['required', 'string', 'min:5'],
        ], [
            'judul_proposal.required' => 'Perihal/judul usulan proposal wajib diisi.',
            'total_anggaran.min' => 'Total anggaran proposal minimal Rp 5.000.000 (sesuai ketentuan batas bantuan).',
            'file_proposal.mimes' => 'File dokumen proposal utama wajib berformat PDF.',
            'file_proposal.max' => 'Ukuran file proposal maksimal 10 MB.',
            'catatan_revisi_pemohon.required' => 'Catatan perbaikan pemohon wajib diisi untuk menjelaskan poin yang telah diperbaiki.',
            'catatan_revisi_pemohon.min' => 'Catatan perbaikan pemohon minimal 5 karakter.',
        ]);

        $rabJson = null;
        if ($request->has('rab_items') && is_array($request->input('rab_items'))) {
            $formattedItems = [];
            foreach ($request->input('rab_items') as $row) {
                if (! empty($row['item'])) {
                    $formattedItems[] = [
                        'item' => trim($row['item']),
                        'volume' => isset($row['volume']) ? (int) $row['volume'] : 1,
                        'satuan' => ! empty($row['satuan']) ? trim($row['satuan']) : 'paket',
                        'biaya' => isset($row['biaya']) ? (float) $row['biaya'] : 0,
                    ];
                }
            }
            if (! empty($formattedItems)) {
                $rabJson = json_encode($formattedItems, JSON_UNESCAPED_UNICODE);
            }
        }

        DB::transaction(function () use ($proposal, $validated, $user, $request, $rabJson) {
            $currentVersion = $proposal->versions->firstWhere('nomor_versi', $proposal->versi_aktif) ?? $proposal->versions()->latest('nomor_versi')->first();
            $nextVersionNumber = ($proposal->versions()->max('nomor_versi') ?? 1) + 1;

            $fileProposalPath = $currentVersion?->file_proposal;
            if ($request->hasFile('file_proposal')) {
                $file = $request->file('file_proposal');
                $originalName = $file->getClientOriginalName();
                $cleanName = str_replace(['#', '%', '?', '\\', '/'], '_', $originalName);
                $fileName = 'v'.$nextVersionNumber.'_'.$cleanName;
                $fileProposalPath = $file->storeAs('proposals/'.$proposal->nomor_registrasi, $fileName, 'public');
            }

            if (! $rabJson && $currentVersion) {
                $rabJson = $currentVersion->rincian_rab;
            }

            // Simpan Versi Baru tanpa menimpa versi sebelumnya
            $newVersion = ProposalVersion::create([
                'proposal_id' => $proposal->id,
                'nomor_versi' => $nextVersionNumber,
                'latar_belakang' => $validated['latar_belakang'],
                'tujuan' => $validated['tujuan'] ?? 'Lihat dokumen proposal PDF terlampir.',
                'lokasi_kegiatan' => $validated['lokasi_kegiatan'],
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
                'rincian_rab' => $rabJson,
                'file_proposal' => $fileProposalPath,
                'catatan_revisi_pemohon' => $validated['catatan_revisi_pemohon'],
            ]);

            // Salin lampiran pendukung dari versi sebelumnya jika ada
            if ($currentVersion) {
                foreach ($currentVersion->attachments as $att) {
                    ProposalAttachment::create([
                        'version_id' => $newVersion->id,
                        'jenis_lampiran' => $att->jenis_lampiran,
                        'nama_file' => $att->nama_file,
                        'file_path' => $att->file_path,
                        'ukuran_file' => $att->ukuran_file,
                    ]);
                }
            }

            // Perbarui proposal: nomor registrasi tetap sama, versi aktif bertambah, status kembali diajukan
            $proposal->update([
                'judul_proposal' => $validated['judul_proposal'],
                'total_anggaran' => $validated['total_anggaran'],
                'status' => 'diajukan',
                'versi_aktif' => $nextVersionNumber,
                'tanggal_kirim' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Mengajukan Perbaikan Proposal (Revisi)',
                'keterangan' => "Mengajukan perbaikan Versi {$nextVersionNumber} untuk proposal {$proposal->nomor_registrasi}: {$proposal->judul_proposal}",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Perbaikan proposal berhasil diajukan dan telah dikirim kembali untuk ditelaah.');
    }

    public function storeDisposition(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403, 'Hanya Staf Administrasi yang memiliki akses untuk mencatat disposisi Tata Usaha Pimpinan.');
        }

        $proposal = Proposal::with(['versions.disposition', 'user.profile'])->findOrFail($id);

        if ($proposal->status !== 'diajukan') {
            return redirect()->route('proposals.show', $proposal->id)
                ->with('error', 'Hanya proposal dengan status diajukan yang dapat diproses disposisinya.');
        }

        $activeVersion = $proposal->versions->firstWhere('nomor_versi', $proposal->versi_aktif)
            ?? $proposal->versions()->latest('nomor_versi')->first();

        if (! $activeVersion) {
            return redirect()->route('proposals.show', $proposal->id)
                ->with('error', 'Versi aktif proposal tidak ditemukan.');
        }

        $validated = $request->validate([
            'nomor_surat' => ['nullable', 'string', 'max:100'],
            'tanggal_surat' => ['nullable', 'date'],
            'asal_surat' => ['nullable', 'string', 'max:255'],
            'tujuan_disposisi' => ['required', 'string', 'max:255'],
            'tanggal_disposisi' => ['required', 'date'],
            'catatan_disposisi' => ['nullable', 'string'],
            'file_bukti_disposisi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'batas_waktu' => ['nullable', 'date'],
        ], [
            'tujuan_disposisi.required' => 'Tujuan arahan disposisi wajib diisi.',
            'tanggal_disposisi.required' => 'Tanggal disposisi wajib diisi.',
            'file_bukti_disposisi.mimes' => 'File bukti lembar disposisi harus berformat PDF, JPG, JPEG, atau PNG.',
            'file_bukti_disposisi.max' => 'Ukuran file lembar disposisi maksimal 5 MB.',
        ]);

        if ($request->filled('batas_waktu')) {
            $proposal->update(['batas_waktu' => $validated['batas_waktu']]);
        }

        $fileBuktiPath = null;
        if ($request->hasFile('file_bukti_disposisi')) {
            $file = $request->file('file_bukti_disposisi');
            $originalName = $file->getClientOriginalName();
            $cleanName = str_replace(['#', '%', '?', '\\', '/'], '_', $originalName);
            $fileName = 'disposisi_v'.$proposal->versi_aktif.'_'.$cleanName;
            $fileBuktiPath = $file->storeAs('dispositions/'.$proposal->nomor_registrasi, $fileName, 'public');
        }

        $existingDisposition = Disposition::where('proposal_id', $proposal->id)
            ->where('version_id', $activeVersion->id)
            ->first();

        if ($existingDisposition && ! $fileBuktiPath) {
            $fileBuktiPath = $existingDisposition->file_bukti_disposisi;
        }

        Disposition::updateOrCreate(
            [
                'proposal_id' => $proposal->id,
                'version_id' => $activeVersion->id,
            ],
            [
                'petugas_id' => $user->id,
                'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
                'nomor_surat' => $validated['nomor_surat'] ?? null,
                'tanggal_surat' => $validated['tanggal_surat'] ?? null,
                'asal_surat' => $validated['asal_surat'] ?? ($proposal->user->profile->nama_lembaga ?? $proposal->user->name),
                'tujuan_disposisi' => $validated['tujuan_disposisi'],
                'tanggal_disposisi' => $validated['tanggal_disposisi'],
                'catatan_disposisi' => $validated['catatan_disposisi'] ?? null,
                'file_bukti_disposisi' => $fileBuktiPath,
                'status_disposisi' => 'selesai',
            ]
        );

        ActivityLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Mencatat Disposisi Tata Usaha Pimpinan',
            'keterangan' => "Mencatat lembar disposisi Pak Haziral (KSB. TU Pimpinan) untuk proposal {$proposal->nomor_registrasi} (Versi {$proposal->versi_aktif}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Data disposisi Tata Usaha Pimpinan (Pak Haziral) berhasil dicatat. Berkas proposal kini siap ditelaah oleh Wakil Bupati.');
    }

    public function updateDeadline(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403, 'Hanya Staf Administrasi yang berwenang menetapkan batas waktu tindak lanjut proposal.');
        }

        $proposal = Proposal::findOrFail($id);

        $validated = $request->validate([
            'batas_waktu' => ['required', 'date'],
        ], [
            'batas_waktu.required' => 'Batas waktu tindak lanjut wajib diisi.',
            'batas_waktu.date' => 'Format tanggal batas waktu tidak valid.',
        ]);

        $proposal->update([
            'batas_waktu' => $validated['batas_waktu'],
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Menetapkan Batas Waktu Proposal',
            'keterangan' => "Menetapkan batas waktu tindak lanjut proposal {$proposal->nomor_registrasi} menjadi ".Carbon::parse($validated['batas_waktu'])->translatedFormat('d F Y').'.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()
            ->with('success', 'Batas waktu tindak lanjut proposal berhasil diperbarui!');
    }

    public function review(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user->isWabup()) {
            abort(403, 'Hak untuk menyetujui, menolak, atau meminta perbaikan proposal secara eksklusif merupakan wewenang Wakil Bupati.');
        }

        $proposal = Proposal::with(['versions.disposition'])->findOrFail($id);

        // Gatekeeper Disposisi: Proposal yang belum memiliki disposisi selesai pada versi aktif dilarang ditelaah
        if (! $proposal->isDisposed()) {
            abort(403, 'Proposal ini belum dapat ditelaah karena belum memiliki disposisi administratif dari Tata Usaha Pimpinan (Pak Haziral).');
        }

        $validated = $request->validate([
            'keputusan' => ['required', 'in:disetujui,perlu_perbaikan,ditolak'],
            'catatan_pimpinan' => ['required', 'string', 'min:5'],
        ]);

        $activeVersion = $proposal->versions->firstWhere('nomor_versi', $proposal->versi_aktif) ?? $proposal->versions()->latest('nomor_versi')->first();

        DB::transaction(function () use ($proposal, $activeVersion, $validated, $user, $request) {
            $proposal->status = $validated['keputusan'];
            $proposal->save();

            ReviewDecision::create([
                'proposal_id' => $proposal->id,
                'version_id' => $activeVersion?->id ?? 1,
                'reviewer_id' => $user->id,
                'keputusan' => $validated['keputusan'],
                'catatan_pimpinan' => $validated['catatan_pimpinan'],
                'tanggal_keputusan' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Memberikan Telaah / Disposisi Proposal',
                'keterangan' => 'Keputusan: '.strtoupper($validated['keputusan'])." untuk proposal {$proposal->nomor_registrasi}.",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('proposals.show', $proposal->id)
            ->with('success', 'Keputusan telaah berhasil disimpan dan status proposal telah diperbarui!');
    }
}
