<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Login ke Sistem',
                'keterangan' => 'Login berhasil via antarmuka web.',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Demo Fast Login Helper for seamless testing and presentation
     */
    public function quickLogin($role, Request $request)
    {
        $emailMap = [
            'admin' => 'admin@murungrayakab.go.id',
            'wabup' => 'wabup@murungrayakab.go.id',
            'pengusul' => 'pemohon@gmail.com',
        ];

        if (! isset($emailMap[$role])) {
            return redirect()->route('login')->with('error', 'Peran tidak dikenali.');
        }

        $user = User::where('email', $emailMap[$role])->first();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Akun demo belum tersedia di basis data.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Quick Demo Login',
            'keterangan' => 'Login instan sebagai ' . strtoupper($role),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai demo ' . strtoupper($role) . ': ' . $user->name);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'jenis_pemohon' => ['required', 'in:organisasi,perorangan'],
            'nama_lembaga' => ['nullable', 'string', 'max:255'],
            'nomor_identitas' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
            'nama_bank' => ['nullable', 'string', 'max:100'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'nama_pemilik_rekening' => ['nullable', 'string', 'max:150'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pengusul',
            'no_telepon' => $validated['no_telepon'],
        ]);

        Profile::create([
            'user_id' => $user->id,
            'jenis_pemohon' => $validated['jenis_pemohon'],
            'nama_lembaga' => $validated['jenis_pemohon'] === 'organisasi' ? $validated['nama_lembaga'] : null,
            'nomor_identitas' => $validated['nomor_identitas'],
            'alamat' => $validated['alamat'],
            'nama_bank' => $validated['nama_bank'] ?? null,
            'nomor_rekening' => $validated['nomor_rekening'] ?? null,
            'nama_pemilik_rekening' => $validated['nama_pemilik_rekening'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Pendaftaran Akun Baru',
            'keterangan' => 'Pemohon mendaftarkan akun pengusul baru.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil didaftarkan! Selamat datang di SIPPRO MURA.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Logout dari Sistem',
                'keterangan' => 'Pengguna keluar secara sukarela.',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }
}
