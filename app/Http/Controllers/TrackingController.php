<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function search(Request $request)
    {
        $nomorRegistrasi = trim($request->input('nomor_registrasi', ''));

        if (empty($nomorRegistrasi)) {
            return redirect()->route('home')->with('error', 'Silakan masukkan nomor registrasi proposal yang ingin dilacak.');
        }

        $proposal = Proposal::with([
            'category',
            'user.profile',
            'latestVersion.attachments',
            'reviewDecisions.reviewer',
        ])
        ->where('nomor_registrasi', $nomorRegistrasi)
        ->first();

        if (! $proposal) {
            return redirect()->route('home')
                ->with('error', "Proposal dengan nomor registrasi '{$nomorRegistrasi}' tidak ditemukan. Mohon periksa kembali nomor registrasi Anda.")
                ->withInput();
        }

        return view('tracking.result', compact('proposal'));
    }
}
