<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Dashboard untuk Pengusul (Masyarakat / Ormas)
        if ($user->isPengusul()) {
            $proposals = Proposal::with(['category', 'latestVersion'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            $stats = [
                'total' => $proposals->count(),
                'diajukan' => $proposals->where('status', 'diajukan')->count(),
                'perlu_perbaikan' => $proposals->where('status', 'perlu_perbaikan')->count(),
                'disetujui' => $proposals->where('status', 'disetujui')->count(),
                'ditolak' => $proposals->where('status', 'ditolak')->count(),
                'total_anggaran_disetujui' => $proposals->where('status', 'disetujui')->sum('total_anggaran'),
            ];

            $recentActivities = ActivityLog::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', compact('user', 'proposals', 'stats', 'recentActivities'));
        }

        // 2. Dashboard untuk Pimpinan (Wakil Bupati)
        if ($user->isWabup()) {
            // Hanya tampilkan proposal yang SUDAH selesai disposisi administratif pada versi aktif
            $pendingProposals = Proposal::with(['user.profile', 'category', 'latestVersion', 'versions.disposition.petugas'])
                ->where('status', 'diajukan')
                ->disposed()
                ->orderByDeadline('asc')
                ->latest('tanggal_kirim')
                ->get();

            $proposals = Proposal::with(['user.profile', 'category', 'latestVersion', 'reviewDecisions.reviewer', 'versions.disposition.petugas'])
                ->latest()
                ->take(10)
                ->get();

            $stats = [
                'menunggu_telaah' => Proposal::where('status', 'diajukan')->disposed()->count(),
                'disetujui' => Proposal::where('status', 'disetujui')->count(),
                'perlu_perbaikan' => Proposal::where('status', 'perlu_perbaikan')->count(),
                'ditolak' => Proposal::where('status', 'ditolak')->count(),
                'total_dana_disetujui' => Proposal::where('status', 'disetujui')->sum('total_anggaran'),
            ];

            $recentActivities = ActivityLog::with('user')
                ->latest()
                ->take(8)
                ->get();

            return view('dashboard', compact('user', 'pendingProposals', 'proposals', 'stats', 'recentActivities'));
        }

        // 3. Dashboard untuk Admin (Staf Bagian Tata Usaha Pimpinan)
        // Proposal yang masuk dan menunggu pencatatan disposisi Pak Haziral
        $waitingDispositions = Proposal::with(['user.profile', 'category', 'latestVersion'])
            ->where('status', 'diajukan')
            ->waitingDisposition()
            ->orderByDeadline('asc')
            ->latest('tanggal_kirim')
            ->get();

        // Proposal yang telah selesai dicatat lembar disposisinya dan diteruskan ke Wabup
        $completedDispositions = Proposal::with(['user.profile', 'category', 'latestVersion', 'versions.disposition.petugas'])
            ->where('status', 'diajukan')
            ->disposed()
            ->orderByDeadline('asc')
            ->latest('tanggal_kirim')
            ->take(10)
            ->get();

        $proposals = Proposal::with(['user.profile', 'category', 'latestVersion', 'versions.disposition.petugas'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_proposal' => Proposal::count(),
            'menunggu_disposisi' => Proposal::where('status', 'diajukan')->waitingDisposition()->count(),
            'disposisi_selesai' => Proposal::where('status', 'diajukan')->disposed()->count(),
            'disetujui' => Proposal::where('status', 'disetujui')->count(),
            'perlu_perbaikan' => Proposal::where('status', 'perlu_perbaikan')->count(),
            'total_pengusul' => User::where('role', 'pengusul')->count(),
            'total_anggaran_semua' => Proposal::sum('total_anggaran'),
            'total_anggaran_disetujui' => Proposal::where('status', 'disetujui')->sum('total_anggaran'),
        ];

        $categories = Category::withCount('proposals')->get();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('user', 'waitingDispositions', 'completedDispositions', 'proposals', 'stats', 'categories', 'recentActivities'));
    }
}
