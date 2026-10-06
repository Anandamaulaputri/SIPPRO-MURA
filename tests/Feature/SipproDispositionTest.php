<?php

namespace Tests\Feature;

use App\Models\Disposition;
use App\Models\Proposal;
use App\Models\ProposalVersion;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SipproDispositionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_melihat_antrean_proposal_menunggu_disposisi_di_dashboard_dan_index(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // PROP-202609-0004 pada seeder dibuat tanpa disposisi
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $this->assertNotNull($proposal);
        $this->assertFalse($proposal->isDisposed());

        // Cek dashboard admin
        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Menunggu Disposisi');
        $dashboard->assertSee('PROP-202609-0004');
        $dashboard->assertSee('Catat Disposisi');

        // Cek index proposal dengan filter disposisi menunggu
        $indexResponse = $this->get(route('proposals.index', ['disposisi' => 'menunggu']));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('PROP-202609-0004');
        $indexResponse->assertSee('Menunggu Disposisi');
    }

    public function test_admin_dapat_mencatat_disposisi_fisik_pak_haziral_dengan_data_lengkap_dan_upload_bukti(): void
    {
        Storage::fake('public');

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $this->assertNotNull($proposal);

        $dummyPdf = UploadedFile::fake()->create('lembar_disposisi_fisik.pdf', 800, 'application/pdf');

        $postData = [
            'nomor_surat' => '045.2/112/TU-PIMP/2026',
            'tanggal_surat' => now()->subDay()->format('Y-m-d'),
            'asal_surat' => 'Forum Komunikasi Pemuda Murung',
            'tujuan_disposisi' => 'Bapak Wakil Bupati Murung Raya',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'catatan_disposisi' => 'Telah diverifikasi kelengkapan berkas fisik. Diteruskan ke Bapak Wabup untuk telaah.',
            'file_bukti_disposisi' => $dummyPdf,
        ];

        $response = $this->post(route('proposals.disposition', $proposal->id), $postData);
        $response->assertRedirect(route('proposals.show', $proposal->id));
        $response->assertSessionHas('success');

        // Verifikasi tabel dispositions
        $this->assertDatabaseHas('dispositions', [
            'proposal_id' => $proposal->id,
            'version_id' => $proposal->latestVersion->id,
            'petugas_id' => $admin->id,
            'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
            'nomor_surat' => '045.2/112/TU-PIMP/2026',
            'status_disposisi' => 'selesai',
        ]);

        $disposition = Disposition::where('proposal_id', $proposal->id)->first();
        $this->assertNotNull($disposition->file_bukti_disposisi);
        Storage::disk('public')->assertExists($disposition->file_bukti_disposisi);

        // Verifikasi Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'aktivitas' => 'Mencatat Disposisi Tata Usaha Pimpinan',
        ]);

        // Verifikasi relasi isDisposed() kini true
        $proposal->refresh();
        $this->assertTrue($proposal->isDisposed());
    }

    public function test_wabup_dilarang_menelaah_proposal_yang_belum_memiliki_disposisi_aktif(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $this->assertFalse($proposal->isDisposed());

        // 1. Direct POST review harus 403 Forbidden
        $response = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Mencoba bypass tanpa disposisi Pak Haziral',
        ]);
        $response->assertStatus(403);

        // 2. Pada halaman show, muncul peringatan gate disposisi dan formulir telaah belum aktif
        $showResponse = $this->get(route('proposals.show', $proposal->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Menunggu Disposisi Administrasi TU Pimpinan');
        $showResponse->assertSee('Pak Haziral (KSB. Tata Usaha Pimpinan)');
        $showResponse->assertSee('terbuka otomatis setelah lembar disposisi dicatat');
    }

    public function test_wabup_hanya_dapat_menelaah_setelah_disposisi_selesai_dicatat(): void
    {
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $admin = User::where('role', 'admin')->first();
        $wabup = User::where('role', 'wabup')->first();

        // 1. Admin mencatat disposisi Pak Haziral
        $this->actingAs($admin);
        $this->post(route('proposals.disposition', $proposal->id), [
            'tujuan_disposisi' => 'Wakil Bupati Murung Raya',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'catatan_disposisi' => 'Berkas lengkap dan sah.',
        ])->assertRedirect(route('proposals.show', $proposal->id));

        // 2. Sekarang Wabup membuka dashboard dan antrean telaah
        $this->actingAs($wabup);
        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee($proposal->nomor_registrasi);

        // 3. Wabup membuka halaman show proposal
        $showResponse = $this->get(route('proposals.show', $proposal->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Disposisi Selesai');
        $showResponse->assertSee('Formulir Telaah', false);
        $showResponse->assertSee('Keputusan Disposisi Pimpinan (Wakil Bupati)', false);

        // 4. Wabup menyetujui proposal
        $reviewResponse = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Disetujui untuk dianggarkan pada APBD perubahan.',
        ]);
        $reviewResponse->assertRedirect(route('proposals.show', $proposal->id));

        $proposal->refresh();
        $this->assertEquals('disetujui', $proposal->status);
    }

    public function test_pengusul_dan_wabup_dilarang_mencatat_disposisi(): void
    {
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();

        // Pengusul dilarang
        $pengusul = $proposal->user;
        $this->actingAs($pengusul);
        $this->post(route('proposals.disposition', $proposal->id), [
            'tujuan_disposisi' => 'Wabup',
            'tanggal_disposisi' => now()->format('Y-m-d'),
        ])->assertStatus(403);

        // Wabup dilarang mencatat lembar administrasi disposisi (khusus staf/admin)
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);
        $this->post(route('proposals.disposition', $proposal->id), [
            'tujuan_disposisi' => 'Wabup',
            'tanggal_disposisi' => now()->format('Y-m-d'),
        ])->assertStatus(403);
    }

    public function test_admin_dilarang_mengambil_keputusan_review_substantif(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::where('status', 'diajukan')->first();

        // Admin dilarang menyetujui / menolak proposal
        $response = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Admin mencoba menyetujui proposal',
        ]);

        $response->assertStatus(403);
    }

    public function test_revisi_versi_baru_wajib_melewati_disposisi_ulang_dan_disposisi_lama_tidak_tertimpa(): void
    {
        // Ambil proposal 1 yang v1-nya sudah ada disposisi dari DatabaseSeeder
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0001')->first();
        $this->assertNotNull($proposal);
        $this->assertTrue($proposal->isDisposed());

        $v1 = $proposal->latestVersion;
        $dispositionV1 = $v1->disposition;
        $this->assertNotNull($dispositionV1);
        $v1DispositionId = $dispositionV1->id;

        // Simulasi revisi: buat versi 2
        $v2 = ProposalVersion::create([
            'proposal_id' => $proposal->id,
            'nomor_versi' => 2,
            'latar_belakang' => 'Latar belakang v2',
            'tujuan' => 'Tujuan v2',
            'lokasi_kegiatan' => 'Puruk Cahu',
            'rincian_rab' => json_encode([['item' => 'Item v2', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 10000000]]),
            'catatan_revisi_pemohon' => 'Koreksi volume',
        ]);

        $proposal->update([
            'versi_aktif' => 2,
            'status' => 'diajukan',
        ]);

        $proposal->refresh();

        // 1. Versi baru (v2) belum memiliki disposisi
        $this->assertFalse($proposal->isDisposed());
        $this->assertNull($proposal->activeDisposition);

        // 2. Disposisi versi 1 TETAP UTUH di database
        $this->assertDatabaseHas('dispositions', [
            'id' => $v1DispositionId,
            'version_id' => $v1->id,
        ]);

        // 3. Wabup mencoba review v2 langsung -> 403 Forbidden!
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);
        $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Mencoba review v2 tanpa disposisi',
        ])->assertStatus(403);

        // 4. Admin mencatat disposisi untuk versi 2
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);
        $this->post(route('proposals.disposition', $proposal->id), [
            'nomor_surat' => '045.2/REV/001/2026',
            'tujuan_disposisi' => 'Bapak Wakil Bupati',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'catatan_disposisi' => 'Disposisi berkas revisi versi 2',
        ])->assertRedirect(route('proposals.show', $proposal->id));

        // 5. Sekarang proposal memiliki 2 record disposisi terpisah (v1 dan v2)
        $this->assertEquals(2, $proposal->dispositions()->count());

        $dispositionV2 = Disposition::where('version_id', $v2->id)->first();
        $this->assertNotNull($dispositionV2);
        $this->assertNotEquals($v1DispositionId, $dispositionV2->id);

        // 6. Wabup sekarang dapat mereview dan menyetujui versi 2
        $this->actingAs($wabup);
        $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Revisi v2 disetujui',
        ])->assertRedirect(route('proposals.show', $proposal->id));

        $proposal->refresh();
        $this->assertEquals('disetujui', $proposal->status);
    }

    public function test_arsip_versi_lama_menampilkan_data_disposisi_versi_lama(): void
    {
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0001')->first();
        $v1 = $proposal->latestVersion;

        // Buat versi 2
        $v2 = ProposalVersion::create([
            'proposal_id' => $proposal->id,
            'nomor_versi' => 2,
            'latar_belakang' => 'Latar belakang v2',
            'tujuan' => 'Tujuan v2',
            'lokasi_kegiatan' => 'Puruk Cahu',
        ]);

        // Buat disposisi spesifik v2
        Disposition::create([
            'proposal_id' => $proposal->id,
            'version_id' => $v2->id,
            'petugas_id' => User::where('role', 'admin')->first()->id,
            'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
            'tujuan_disposisi' => 'Wakil Bupati (Disposisi Versi Dua)',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'status_disposisi' => 'selesai',
        ]);

        $proposal->update(['versi_aktif' => 2]);

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Akses versi aktif v2
        $responseV2 = $this->get(route('proposals.show', $proposal->id));
        $responseV2->assertStatus(200);
        $responseV2->assertSee('Wakil Bupati (Disposisi Versi Dua)');

        // Akses arsip versi 1
        $responseV1 = $this->get(route('proposals.show', [$proposal->id, 'version' => 1]));
        $responseV1->assertStatus(200);
        $responseV1->assertSee('(Arsip)');
        $responseV1->assertSee('Data Disposisi Tata Usaha Pimpinan');
    }

    public function test_public_tracking_menampilkan_tahapan_disposisi_tata_usaha_pimpinan(): void
    {
        // 1. Proposal dengan disposisi selesai (PROP-202609-0001)
        $response = $this->post('/lacak', [
            'nomor_registrasi' => 'PROP-202609-0001',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Disposisi TU Pimpinan');
        $response->assertSee('Pak Haziral');
        $response->assertSee('Selesai Disposisi');

        // 2. Proposal tanpa disposisi (PROP-202609-0004)
        $response2 = $this->post('/lacak', [
            'nomor_registrasi' => 'PROP-202609-0004',
        ]);
        $response2->assertStatus(200);
        $response2->assertSee('Sedang Berlangsung');
        $response2->assertSee('Antrean TU');
    }

    public function test_validasi_pencatatan_disposisi_wajib_tujuan_dan_tanggal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();

        // Kirim form kosong
        $response = $this->post(route('proposals.disposition', $proposal->id), []);
        $response->assertSessionHasErrors(['tujuan_disposisi', 'tanggal_disposisi']);
    }

    public function test_filter_disposisi_pada_halaman_daftar_proposal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Filter menunggu disposisi
        $responseMenunggu = $this->get(route('proposals.index', ['disposisi' => 'menunggu']));
        $responseMenunggu->assertStatus(200);
        $responseMenunggu->assertSee('PROP-202609-0004');
        $responseMenunggu->assertDontSee('PROP-202609-0001');

        // Filter disposisi selesai
        $responseSelesai = $this->get(route('proposals.index', ['disposisi' => 'selesai']));
        $responseSelesai->assertStatus(200);
        $responseSelesai->assertSee('PROP-202609-0001');
        $responseSelesai->assertDontSee('PROP-202609-0004');
    }
}
