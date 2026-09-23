<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
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

        $query = Proposal::with(['category', 'user.profile', 'latestVersion']);

        if ($user->isPengusul()) {
            $query->where('user_id', $user->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_proposal', 'like', "%{$search}%")
                    ->orWhere('nomor_registrasi', 'like', "%{$search}%");
            });
        }

        $proposals = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('proposals.index', compact('proposals', 'categories', 'status', 'search'));
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

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'judul_proposal' => ['required', 'string', 'max:255'],
            'total_anggaran' => ['required', 'numeric', 'min:100000'],
            'lokasi_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'latar_belakang' => ['required', 'string'],
            'tujuan' => ['required', 'string'],
            'rincian_rab' => ['nullable', 'string'],
            'file_proposal' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // 10MB max
            'lampiran_ktp' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'lampiran_organisasi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        return DB::transaction(function () use ($request, $user, $validated) {
            // Generate Nomor Registrasi unik: PROP-YYYYMM-XXXX
            $datePrefix = Carbon::now()->format('Ym');
            $lastProposal = Proposal::where('nomor_registrasi', 'like', "PROP-{$datePrefix}-%")->latest('id')->first();
            $nextSeq = 1;
            if ($lastProposal) {
                $parts = explode('-', $lastProposal->nomor_registrasi);
                $nextSeq = intval(end($parts)) + 1;
            }
            $nomorRegistrasi = sprintf('PROP-%s-%04d', $datePrefix, $nextSeq);

            // Simpan Proposal Induk
            $proposal = Proposal::create([
                'nomor_registrasi' => $nomorRegistrasi,
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'judul_proposal' => $validated['judul_proposal'],
                'total_anggaran' => $validated['total_anggaran'],
                'status' => 'diajukan',
                'versi_aktif' => 1,
                'tanggal_kirim' => now(),
            ]);

            // Handle file proposal jika diunggah
            $fileProposalPath = null;
            if ($request->hasFile('file_proposal')) {
                $fileProposalPath = $request->file('file_proposal')->store('proposals', 'public');
            }

            // Simpan Versi 1
            $version = ProposalVersion::create([
                'proposal_id' => $proposal->id,
                'nomor_versi' => 1,
                'latar_belakang' => $validated['latar_belakang'],
                'tujuan' => $validated['tujuan'],
                'lokasi_kegiatan' => $validated['lokasi_kegiatan'],
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'] ?? null,
                'rincian_rab' => $validated['rincian_rab'] ?? null,
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

    public function show($id)
    {
        $proposal = Proposal::with([
            'category',
            'user.profile',
            'versions.attachments',
            'versions.reviewDecisions.reviewer',
            'reviewDecisions.reviewer',
        ])->findOrFail($id);

        $user = Auth::user();

        // Otorisasi: Pengusul hanya boleh melihat miliknya, sedangkan Wabup dan Admin bisa melihat semua
        if ($user->isPengusul() && $proposal->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke proposal ini.');
        }

        $activeVersion = $proposal->versions->firstWhere('nomor_versi', $proposal->versi_aktif) ?? $proposal->versions->first();

        return view('proposals.show', compact('proposal', 'activeVersion', 'user'));
    }

    public function review(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user->isWabup()) {
            abort(403, 'Hak untuk menyetujui, menolak, atau meminta perbaikan proposal secara eksklusif merupakan wewenang Wakil Bupati.');
        }

        $validated = $request->validate([
            'keputusan' => ['required', 'in:disetujui,perlu_perbaikan,ditolak'],
            'catatan_pimpinan' => ['required', 'string', 'min:5'],
        ]);

        $proposal = Proposal::with('latestVersion')->findOrFail($id);

        DB::transaction(function () use ($proposal, $validated, $user, $request) {
            $proposal->status = $validated['keputusan'];
            $proposal->save();

            ReviewDecision::create([
                'proposal_id' => $proposal->id,
                'version_id' => $proposal->latestVersion?->id ?? 1,
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
