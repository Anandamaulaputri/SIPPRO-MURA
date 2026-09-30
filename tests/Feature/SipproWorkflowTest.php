<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Proposal;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SipproWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_halaman_beranda_dapat_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SIPPRO');
        $response->assertSee('Murung Raya');
        $response->assertSee('Daftarkan Akun');
        $response->assertSee('Masuk');

        // Memastikan tombol Daftarkan Akun hanya muncul tepat 1 kali (tidak ganda di navbar)
        $this->assertEquals(1, substr_count($response->getContent(), 'Daftarkan Akun'));
    }

    public function test_pelacakan_proposal_publik_berfungsi(): void
    {
        $response = $this->post('/lacak', [
            'nomor_registrasi' => 'PROP-202609-0001',
        ]);
        $response->assertStatus(200);
        $response->assertSee('PROP-202609-0001');
    }

    public function test_halaman_login_tersedia(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke SIPPRO MURA');
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Masuk');
    }

    public function test_quick_login_wabup_berhasil_ke_dasbor(): void
    {
        $response = $this->get('/quick-login/wabup');
        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertEquals('wabup', auth()->user()->role);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Wakil Bupati Murung Raya');
    }

    public function test_quick_login_pengusul_berhasil_ke_dasbor(): void
    {
        $response = $this->get('/quick-login/pengusul');
        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertEquals('pengusul', auth()->user()->role);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Budi Santoso');
    }

    public function test_halaman_daftar_proposal_dan_detail_berfungsi(): void
    {
        $user = User::where('role', 'wabup')->first();
        $this->actingAs($user);

        $response = $this->get('/proposals');
        $response->assertStatus(200);
        $response->assertSee('PROP-202609-0001');

        $prop = Proposal::first();
        $detail = $this->get('/proposals/'.$prop->id);
        $detail->assertStatus(200);
        $detail->assertSee($prop->nomor_registrasi);
    }

    public function test_halaman_registrasi_dan_buat_proposal_dapat_diakses(): void
    {
        $register = $this->get('/register');
        $register->assertStatus(200);
        $register->assertSee('Daftarkan Akun');

        $user = User::where('role', 'pengusul')->first();
        $this->actingAs($user);

        $create = $this->get('/proposals/create');
        $create->assertStatus(200);
        $create->assertSee('Formulir Pengajuan Proposal Baru');
    }

    public function test_staf_admin_tidak_bisa_memberikan_keputusan_proposal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $prop = Proposal::first();

        // Staf Admin dilarang menyetujui / menolak proposal (harus 403 Forbidden)
        $response = $this->post('/proposals/'.$prop->id.'/review', [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Catatan staf admin',
        ]);

        $response->assertStatus(403);
    }

    public function test_wakil_bupati_bisa_memberikan_keputusan_proposal(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        $prop = Proposal::where('status', 'diajukan')->first();

        $response = $this->post('/proposals/'.$prop->id.'/review', [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Disetujui penuh oleh Wakil Bupati Murung Raya untuk diterbitkan rekomendasi.',
        ]);

        $response->assertRedirect('/proposals/'.$prop->id);
        $this->assertDatabaseHas('proposals', [
            'id' => $prop->id,
            'status' => 'disetujui',
        ]);
    }

    public function test_halaman_formulir_proposal_menampilkan_tabel_rab_dan_modal_gerbang_cek_ketelitian(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $response = $this->get('/proposals/create');
        $response->assertStatus(200);
        $response->assertSee('Tabel Perhitungan Dinamis Rencana Anggaran Biaya (RAB)');
        $response->assertSee('Gerbang Cek Ketelitian Mandiri');
        $response->assertSee('modalChecklistKetelitian');
        $response->assertSee('rabTable');
        $response->assertSee('Muat Format Contoh');
    }

    public function test_pengusul_bisa_mengajukan_proposal_dengan_tabel_rab_dinamis_dan_lampiran_lengkap(): void
    {
        Storage::fake('public');

        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $category = Category::first();

        $postData = [
            'category_id' => $category->id,
            'judul_proposal' => 'Pengadaan Perlengkapan Sanggar Tari Tradisional Murung Raya',
            'total_anggaran' => 25000000,
            'lokasi_kegiatan' => 'Gedung Tira Tangka Balang, Puruk Cahu',
            'tanggal_kegiatan' => now()->addWeeks(2)->format('Y-m-d'),
            'latar_belakang' => 'Sanggar membutuhkan peremajaan kostum dan instrumen tradisional khas Murung Raya.',
            'tujuan' => 'Mendukung penampilan seni pelajar di tingkat provinsi Kalimantan Tengah.',
            'rab_items' => [
                ['item' => 'Sewa Gedung Latihan', 'volume' => 5, 'satuan' => 'hari', 'biaya' => 1000000],
                ['item' => 'Kostum Tari Dayak Siang', 'volume' => 10, 'satuan' => 'set', 'biaya' => 1500000],
                ['item' => 'Konsumsi Peserta', 'volume' => 100, 'satuan' => 'kotak', 'biaya' => 50000],
            ],
            'file_proposal' => UploadedFile::fake()->create('proposal_resmi.pdf', 1024, 'application/pdf'),
            'lampiran_ktp' => UploadedFile::fake()->create('ktp_pemohon.pdf', 500, 'application/pdf'),
            'lampiran_organisasi' => UploadedFile::fake()->create('sk_organisasi.pdf', 800, 'application/pdf'),
            'lampiran_rekening' => UploadedFile::fake()->image('buku_rekening.png'),
        ];

        $response = $this->post('/proposals', $postData);

        $newProposal = Proposal::where('judul_proposal', 'Pengadaan Perlengkapan Sanggar Tari Tradisional Murung Raya')->first();
        $this->assertNotNull($newProposal);

        $response->assertRedirect('/proposals/'.$newProposal->id);
        $this->assertEquals('diajukan', $newProposal->status);
        $this->assertEquals(25000000, $newProposal->total_anggaran);
        $this->assertStringStartsWith('PROP-', $newProposal->nomor_registrasi);

        // Verifikasi versi aktif dan penyimpanan JSON RAB
        $version = $newProposal->latestVersion;
        $this->assertNotNull($version);
        $this->assertStringContainsString('Sewa Gedung Latihan', $version->rincian_rab);
        $this->assertStringContainsString('Kostum Tari Dayak Siang', $version->rincian_rab);

        // Verifikasi lampiran
        $this->assertDatabaseHas('proposal_attachments', [
            'version_id' => $version->id,
            'jenis_lampiran' => 'ktp',
        ]);
        $this->assertDatabaseHas('proposal_attachments', [
            'version_id' => $version->id,
            'jenis_lampiran' => 'akta',
        ]);
        $this->assertDatabaseHas('proposal_attachments', [
            'version_id' => $version->id,
            'jenis_lampiran' => 'rekening',
        ]);

        // Verifikasi Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $pengusul->id,
            'aktivitas' => 'Mengajukan Proposal Baru',
        ]);
    }

    public function test_pengajuan_proposal_gagal_jika_total_anggaran_di_bawah_minimum(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $category = Category::first();

        $response = $this->post('/proposals', [
            'category_id' => $category->id,
            'judul_proposal' => 'Proposal Nominal Tidak Valid',
            'total_anggaran' => 50000, // Di bawah batas minimum 100000
            'lokasi_kegiatan' => 'Puruk Cahu',
            'latar_belakang' => 'Latar belakang singkat',
            'tujuan' => 'Tujuan singkat',
        ]);

        $response->assertSessionHasErrors(['total_anggaran']);
    }

    public function test_bukan_pengusul_tidak_bisa_mengakses_halaman_buat_proposal(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        $response = $this->get('/proposals/create');
        $response->assertRedirect('/proposals');
        $response->assertSessionHas('error');
    }

    public function test_halaman_profil_dapat_diakses_dan_diperbarui_oleh_pengusul(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $getProfile = $this->get('/profile');
        $getProfile->assertStatus(200);
        $getProfile->assertSee('Profil Pengusul');
        $getProfile->assertSee('Informasi Akun');
        $getProfile->assertSee('Identitas Pemohon');
        $getProfile->assertSee('Kontak & Alamat Domisili', false);
        $getProfile->assertSee('Rekening Penyaluran');
        $getProfile->assertSee('Simpan Perubahan');

        $updateResponse = $this->put('/profile', [
            'name' => 'Budi Santoso Diperbarui',
            'no_telepon' => '081234567888',
            'nama_lembaga' => 'Karang Taruna Mura Hebat',
            'nomor_identitas' => 'KT-MURA-2026-999',
            'alamat' => 'Jl. Jenderal Sudirman No. 99, Puruk Cahu',
            'nama_bank' => 'Bank Kalteng Puruk Cahu',
            'nomor_rekening' => '100-99-887766-5',
            'nama_pemilik_rekening' => 'Karang Taruna Mura Hebat',
        ]);

        $updateResponse->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $pengusul->id,
            'name' => 'Budi Santoso Diperbarui',
            'no_telepon' => '081234567888',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $pengusul->id,
            'nama_lembaga' => 'Karang Taruna Mura Hebat',
            'nomor_rekening' => '100-99-887766-5',
        ]);
    }

    public function test_pengusul_dapat_memperbarui_profil_sebagai_perorangan(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $response = $this->put('/profile', [
            'name' => 'Budi Santoso Mandiri',
            'no_telepon' => '081234567999',
            'jenis_pemohon' => 'perorangan',
            'nama_lembaga' => '',
            'nomor_identitas' => '6212011234560001',
            'alamat' => 'Jl. Merdeka No. 10, Puruk Cahu',
            'nama_bank' => 'Bank BRI Puruk Cahu',
            'nomor_rekening' => '0123-01-001234-50-1',
            'nama_pemilik_rekening' => 'Budi Santoso',
        ]);

        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('profiles', [
            'user_id' => $pengusul->id,
            'jenis_pemohon' => 'perorangan',
            'nama_lembaga' => null,
            'nomor_identitas' => '6212011234560001',
            'nomor_rekening' => '0123-01-001234-50-1',
        ]);

        // Verifikasi bahwa data yang disimpan muncul kembali ketika halaman profil dibuka ulang
        $reopenedProfile = $this->get('/profile');
        $reopenedProfile->assertStatus(200);
        $reopenedProfile->assertSee('Budi Santoso Mandiri');
        $reopenedProfile->assertSee('081234567999');
        $reopenedProfile->assertSee('6212011234560001');
        $reopenedProfile->assertSee('Bank BRI Puruk Cahu');
        $reopenedProfile->assertSee('0123-01-001234-50-1');
        $reopenedProfile->assertSee('Budi Santoso');
    }

    public function test_validasi_profil_gagal_jika_nama_atau_no_telepon_kosong(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $response = $this->put('/profile', [
            'name' => '',
            'no_telepon' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'no_telepon']);
    }

    public function test_dialog_konfirmasi_logout_tersedia_di_dasbor_untuk_pengguna_login(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('logout-confirm-modal');
        $response->assertSee('Keluar dari SIPPRO MURA?');
        $response->assertSee('Apakah Anda yakin ingin keluar dari akun?');
        $response->assertSee('Batal');
        $response->assertSee('Ya, Keluar');
    }

    public function test_registrasi_akun_baru_berhasil_hanya_dengan_data_akun_minimal(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Rahmawati',
            'email' => 'siti.rahmawati@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();

        $newUser = User::where('email', 'siti.rahmawati@gmail.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('Siti Rahmawati', $newUser->name);
        $this->assertEquals('pengusul', $newUser->role);

        // Memastikan record profiles otomatis terinisialisasi
        $this->assertDatabaseHas('profiles', [
            'user_id' => $newUser->id,
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Akun berhasil dibuat. Selamat datang di SIPPRO MURA!');
    }

    public function test_registrasi_gagal_jika_konfirmasi_password_salah(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'passwordBeda',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_pengguna_bisa_logout_dan_sesi_dibersihkan(): void
    {
        $user = User::where('role', 'pengusul')->first();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
