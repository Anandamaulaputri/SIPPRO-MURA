<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TrackingController;
use App\Models\Category;
use App\Models\Proposal;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPPRO MURA (Sistem Informasi Pelayanan Proposal Murung Raya)
|--------------------------------------------------------------------------
*/

// Halaman Publik
Route::get('/', function () {
    $categories = Category::withCount('proposals')->get();
    $totalProposals = Proposal::count();
    $totalApproved = Proposal::where('status', 'disetujui')->count();
    $totalBudget = Proposal::where('status', 'disetujui')->sum('total_anggaran');

    return view('welcome', compact('categories', 'totalProposals', 'totalApproved', 'totalBudget'));
})->name('home');

// Pelacakan Proposal Publik (Transparansi Layanan)
Route::match(['get', 'post'], '/lacak', [TrackingController::class, 'search'])->name('tracking.search');

// Otentikasi Pengguna
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('login.quick');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Area Terproteksi (Login Diperlukan)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pemohon & Rekening
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Manajemen Proposal
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/create', [ProposalController::class, 'create'])->name('proposals.create');
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::get('/proposals/{id}', [ProposalController::class, 'show'])->name('proposals.show');

    // Telaah & Disposisi Pimpinan (Hak Eksklusif Wakil Bupati sesuai PRD)
    Route::post('/proposals/{id}/review', [ProposalController::class, 'review'])
        ->middleware('role:wabup')
        ->name('proposals.review');
});
