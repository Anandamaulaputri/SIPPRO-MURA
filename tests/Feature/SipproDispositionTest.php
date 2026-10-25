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

    public function test_admin_melihat_antrean_proposal_masuk_menunggu_telaah_wabup_dan_dapat_mengatur_deadline(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // PROP-202609-0004 pada seeder dibuat tanpa disposisi fisik
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $this->assertNotNull($proposal);
        $this->assertFalse($proposal->hasDisposition());

        // Cek dashboard admin: melihat antrean proposal masuk menuju telaah Wabup
        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Antrean Proposal Masuk (Menunggu Telaah Wabup)');
        $dashboard->assertSee('PROP-202609-0004');
        $dashboard->assertSee('Atur Deadline');

        // Cek index proposal: menampilkan status Menunggu Telaah Wakil Bupati
        $indexResponse = $this->get(route('proposals.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('PROP-202609-0004');
        $indexResponse->assertSee('Menunggu Telaah Wakil Bupati');

        // Admin dapat mengatur batas waktu tindak lanjut
        $deadlineResponse = $this->patch(route('proposals.update_deadline', $proposal->id), [
            'batas_waktu' => now()->addDays(14)->format('Y-m-d'),
        ]);
        $deadlineResponse->assertRedirect();
        $deadlineResponse->assertSessionHas('success');

        $proposal->refresh();
        $this->assertEquals(now()->addDays(14)->format('Y-m-d'), $proposal->batas_waktu->format('Y-m-d'));
    }

    public function test_admin_dapat_mencatat_disposisi_fisik_sebagai_arsip_riwayat_dengan_upload_bukti(): void
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
            'catatan_disposisi' => 'Telah diverifikasi kelengkapan berkas fisik sebagai arsip.',
            'file_bukti_disposisi' => $dummyPdf,
        ];

        $response = $this->post(route('proposals.disposition', $proposal->id), $postData);
        $response->assertRedirect(route('proposals.show', $proposal->id));
        $response->assertSessionHas('success');

        // Verifikasi tabel dispositions menyimpan arsip
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

        // Verifikasi relasi hasDisposition() kini true
        $proposal->refresh();
        $this->assertTrue($proposal->hasDisposition());
    }

    public function test_wabup_dapat_langsung_menelaah_proposal_baru_tanpa_kewajiban_disposisi_tu(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        // PROP-202609-0004 tidak memiliki lembar disposisi
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $this->assertFalse($proposal->hasDisposition());

        // 1. Pada dashboard Wabup, proposal langsung muncul di antrean telaah
        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee('PROP-202609-0004');
        $dashboard->assertSee('Menunggu Telaah');

        // 2. Pada halaman show proposal, form telaah terbuka langsung tanpa halangan
        $showResponse = $this->get(route('proposals.show', $proposal->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Formulir Telaah');
        $showResponse->assertSee('Keputusan Disposisi Pimpinan');

        // 3. Wabup langsung memberikan keputusan substantif (Disetujui)
        $reviewResponse = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Proposal disetujui langsung melalui telaah Wakil Bupati.',
        ]);
        $reviewResponse->assertRedirect(route('proposals.show', $proposal->id));
        $reviewResponse->assertSessionHas('success');

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

    public function test_proposal_revisi_langsung_masuk_antrean_telaah_wabup_dan_disposisi_lama_tetap_utuh(): void
    {
        // Ambil proposal 1 yang v1-nya sudah ada disposisi dari DatabaseSeeder
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0001')->first();
        $this->assertNotNull($proposal);
        $this->assertTrue($proposal->hasDisposition());

        $v1 = $proposal->latestVersion;
        $dispositionV1 = $v1->disposition;
        $this->assertNotNull($dispositionV1);
        $v1DispositionId = $dispositionV1->id;

        // Simulasi revisi oleh pemohon: buat versi 2
        $v2 = ProposalVersion::create([
            'proposal_id' => $proposal->id,
            'nomor_versi' => 2,
            'latar_belakang' => 'Latar belakang v2 yang diperbaiki',
            'tujuan' => 'Tujuan v2',
            'lokasi_kegiatan' => 'Puruk Cahu',
            'rincian_rab' => json_encode([['item' => 'Item v2', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 10000000]]),
            'catatan_revisi_pemohon' => 'Koreksi volume kegiatan',
        ]);

        $proposal->update([
            'versi_aktif' => 2,
            'status' => 'diajukan',
        ]);

        $proposal->refresh();

        // 1. Disposisi versi 1 TETAP UTUH di database sebagai arsip historis
        $this->assertDatabaseHas('dispositions', [
            'id' => $v1DispositionId,
            'version_id' => $v1->id,
        ]);

        // 2. Versi 2 belum memiliki record disposisi baru
        $this->assertNull($proposal->activeDisposition);

        // 3. Wabup langsung dapat menelaah dan menyetujui versi 2 tanpa terhalang gerbang disposisi
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        $showResponse = $this->get(route('proposals.show', $proposal->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Formulir Telaah');
        $showResponse->assertSee('Keputusan Disposisi Pimpinan');

        $reviewResponse = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Revisi versi 2 diterima dan disetujui langsung oleh Wabup.',
        ]);
        $reviewResponse->assertRedirect(route('proposals.show', $proposal->id));
        $reviewResponse->assertSessionHas('success');

        $proposal->refresh();
        $this->assertEquals('disetujui', $proposal->status);

        // 4. Record arsip disposisi v1 tetap utuh di database
        $this->assertDatabaseHas('dispositions', [
            'id' => $v1DispositionId,
            'version_id' => $v1->id,
        ]);
    }

    public function test_arsip_versi_lama_menampilkan_data_disposisi_tata_usaha_pimpinan(): void
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
            'tujuan_disposisi' => 'Wakil Bupati (Disposisi Arsip Versi Dua)',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'status_disposisi' => 'selesai',
        ]);

        $proposal->update(['versi_aktif' => 2]);

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Akses versi aktif v2
        $responseV2 = $this->get(route('proposals.show', $proposal->id));
        $responseV2->assertStatus(200);
        $responseV2->assertSee('Wakil Bupati (Disposisi Arsip Versi Dua)');

        // Akses arsip versi 1
        $responseV1 = $this->get(route('proposals.show', [$proposal->id, 'version' => 1]));
        $responseV1->assertStatus(200);
        $responseV1->assertSee('(Arsip)');
        $responseV1->assertSee('Data Disposisi Tata Usaha Pimpinan');
    }

    public function test_public_tracking_menampilkan_tahapan_tiga_langkah_langsung_telaah_wabup(): void
    {
        // 1. Proposal dalam proses telaah Wabup (status: diajukan)
        $response = $this->post('/lacak', [
            'nomor_registrasi' => 'PROP-202609-0001',
        ]);
        $response->assertStatus(200);
        $response->assertSee('1. Pengajuan Usulan');
        $response->assertSee('2. Telaah Wakil Bupati');
        $response->assertSee('3. Penetapan Akhir');
        $response->assertSee('Meja Wakil Bupati');

        // 2. Proposal setelah disetujui
        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0002')->first();
        $response2 = $this->post('/lacak', [
            'nomor_registrasi' => $proposal->nomor_registrasi,
        ]);
        $response2->assertStatus(200);
        $response2->assertSee('Selesai Ditelaah');
        $response2->assertSee('Disetujui Penuh');
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
}
