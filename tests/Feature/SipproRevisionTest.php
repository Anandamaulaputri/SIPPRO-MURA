<?php

namespace Tests\Feature;

use App\Models\Proposal;
use App\Models\ProposalAttachment;
use App\Models\ProposalVersion;
use App\Models\ReviewDecision;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SipproRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_wabup_dapat_meminta_perbaikan_dengan_catatan(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        $proposal = Proposal::where('status', 'diajukan')->first();
        $this->assertNotNull($proposal);

        $response = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'perlu_perbaikan',
            'catatan_pimpinan' => 'Mohon sesuaikan rincian satuan volume RAB dan lampirkan spesifikasi detail.',
        ]);

        $response->assertRedirect(route('proposals.show', $proposal->id));

        $proposal->refresh();
        $this->assertEquals('perlu_perbaikan', $proposal->status);

        $this->assertDatabaseHas('review_decisions', [
            'proposal_id' => $proposal->id,
            'reviewer_id' => $wabup->id,
            'keputusan' => 'perlu_perbaikan',
            'catatan_pimpinan' => 'Mohon sesuaikan rincian satuan volume RAB dan lampirkan spesifikasi detail.',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $wabup->id,
            'aktivitas' => 'Memberikan Telaah / Disposisi Proposal',
        ]);
    }

    public function test_pengusul_melihat_status_perlu_perbaikan_dan_catatan_wabup_pada_daftar_dan_detail(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        $wabup = User::where('role', 'wabup')->first();
        ReviewDecision::create([
            'proposal_id' => $proposal->id,
            'version_id' => $proposal->latestVersion->id,
            'reviewer_id' => $wabup->id,
            'keputusan' => 'perlu_perbaikan',
            'catatan_pimpinan' => 'Catatan arahan perbaikan khusus dari Bapak Wakil Bupati.',
            'tanggal_keputusan' => now(),
        ]);

        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        // Halaman Daftar Proposal Saya
        $indexResponse = $this->get(route('proposals.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Catatan Wakil Bupati:');
        $indexResponse->assertSee('Catatan arahan perbaikan khusus dari Bapak Wakil Bupati.');
        $indexResponse->assertSee('Ajukan Perbaikan');

        // Halaman Detail Proposal
        $showResponse = $this->get(route('proposals.show', $proposal->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Perlu Perbaikan Berkas');
        $showResponse->assertSee('Catatan arahan perbaikan khusus dari Bapak Wakil Bupati.');
        $showResponse->assertSee('Ajukan Perbaikan Proposal');
    }

    public function test_tombol_revisi_tidak_muncul_untuk_proposal_status_selain_perlu_perbaikan(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        // Set semua proposal milik pengusul menjadi "diajukan"
        Proposal::where('user_id', $pengusul->id)->update(['status' => 'diajukan']);
        $response = $this->get(route('proposals.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Ajukan Perbaikan');

        // Set semua proposal menjadi "disetujui"
        Proposal::where('user_id', $pengusul->id)->update(['status' => 'disetujui']);
        $response = $this->get(route('proposals.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Ajukan Perbaikan');

        // Set semua proposal menjadi "ditolak"
        Proposal::where('user_id', $pengusul->id)->update(['status' => 'ditolak']);
        $response = $this->get(route('proposals.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Ajukan Perbaikan');
    }

    public function test_pengusul_dapat_membuka_form_revisi_proposal(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        $wabup = User::where('role', 'wabup')->first();
        ReviewDecision::create([
            'proposal_id' => $proposal->id,
            'version_id' => $proposal->latestVersion->id,
            'reviewer_id' => $wabup->id,
            'keputusan' => 'perlu_perbaikan',
            'catatan_pimpinan' => 'Koreksi volume kegiatan konsumsi.',
            'tanggal_keputusan' => now(),
        ]);

        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        $response = $this->get(route('proposals.revise', $proposal->id));
        $response->assertStatus(200);
        $response->assertSee('Ajukan Perbaikan Proposal');
        $response->assertSee('Arahan Perbaikan dari Wakil Bupati');
        $response->assertSee('Koreksi volume kegiatan konsumsi.');
        $response->assertSee('Catatan Perbaikan Pemohon');
    }

    public function test_pengusul_dapat_mengirim_revisi_dan_membuat_versi_baru_tanpa_mengubah_nomor_registrasi(): void
    {
        Storage::fake('public');

        $proposal = Proposal::where('status', 'diajukan')->first();
        $initialRegistrationNumber = $proposal->nomor_registrasi;
        $proposal->update(['status' => 'perlu_perbaikan']);

        $version1 = $proposal->latestVersion;
        $this->assertEquals(1, $version1->nomor_versi);

        // Buat dummy attachment pada version 1
        ProposalAttachment::create([
            'version_id' => $version1->id,
            'jenis_lampiran' => 'ktp',
            'nama_file' => 'ktp_lama.pdf',
            'file_path' => 'attachments/ktp_lama.pdf',
            'ukuran_file' => 12345,
        ]);

        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        $newPdf = UploadedFile::fake()->create('proposal_revisi_v2.pdf', 1024, 'application/pdf');

        $revisionData = [
            'judul_proposal' => 'Pengadaan Sarana & Prasarana Olahraga Desa - Hasil Revisi',
            'total_anggaran' => 35000000,
            'lokasi_kegiatan' => 'Kecamatan Murung, Puruk Cahu',
            'tanggal_kegiatan' => now()->addMonth()->format('Y-m-d'),
            'latar_belakang' => 'Latar belakang diperbarui dengan data statistik pemuda desa.',
            'tujuan' => 'Tujuan diperbarui dengan sasaran kompetisi tingkat kabupaten.',
            'rab_items' => [
                ['item' => 'Bola Voli Standar PBVSI', 'volume' => 10, 'satuan' => 'buah', 'biaya' => 500000],
                ['item' => 'Net & Tiang Portabel', 'volume' => 2, 'satuan' => 'set', 'biaya' => 15000000],
            ],
            'file_proposal' => $newPdf,
            'catatan_revisi_pemohon' => 'Kami telah merevisi volume bola dan mengganti tiang dengan spesifikasi resmi sesuai arahan Bapak Wabup.',
        ];

        $response = $this->post(route('proposals.submit_revision', $proposal->id), $revisionData);
        $response->assertRedirect(route('proposals.show', $proposal->id));
        $response->assertSessionHas('success');

        $proposal->refresh();

        // 1. Nomor registrasi TIDAK BERUBAH
        $this->assertEquals($initialRegistrationNumber, $proposal->nomor_registrasi);

        // 2. Status kembali menjadi "diajukan"
        $this->assertEquals('diajukan', $proposal->status);

        // 3. Versi aktif menjadi 2
        $this->assertEquals(2, $proposal->versi_aktif);

        // 4. Versi 1 dan Versi 2 keduanya ada di database
        $this->assertEquals(2, $proposal->versions()->count());

        $version1After = ProposalVersion::where('proposal_id', $proposal->id)->where('nomor_versi', 1)->first();
        $version2 = ProposalVersion::where('proposal_id', $proposal->id)->where('nomor_versi', 2)->first();

        $this->assertNotNull($version1After);
        $this->assertNotNull($version2);

        // Versi 1 tetap utuh
        $this->assertNotEquals($version1After->id, $version2->id);

        // Versi 2 memiliki catatan revisi pemohon
        $this->assertEquals(
            'Kami telah merevisi volume bola dan mengganti tiang dengan spesifikasi resmi sesuai arahan Bapak Wabup.',
            $version2->catatan_revisi_pemohon
        );

        // Versi 2 memiliki RAB baru
        $this->assertStringContainsString('Bola Voli Standar PBVSI', $version2->rincian_rab);
        $this->assertStringContainsString('Net & Tiang Portabel', $version2->rincian_rab);

        // Versi 2 menyalin attachment dari versi 1
        $this->assertDatabaseHas('proposal_attachments', [
            'version_id' => $version2->id,
            'jenis_lampiran' => 'ktp',
            'nama_file' => 'ktp_lama.pdf',
        ]);

        // File proposal versi 2 tersimpan di disk
        $this->assertNotNull($version2->file_proposal);
        Storage::disk('public')->assertExists($version2->file_proposal);

        // Activity log tercatat
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $pengusul->id,
            'aktivitas' => 'Mengajukan Perbaikan Proposal (Revisi)',
        ]);
    }

    public function test_riwayat_versi_dapat_dibuka_secara_kronologis(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        $version1 = $proposal->latestVersion;

        // Buat versi 2
        $version2 = ProposalVersion::create([
            'proposal_id' => $proposal->id,
            'nomor_versi' => 2,
            'latar_belakang' => 'Latar belakang v2',
            'tujuan' => 'Tujuan v2',
            'lokasi_kegiatan' => 'Lokasi v2',
            'rincian_rab' => json_encode([['item' => 'Item v2', 'volume' => 1, 'satuan' => 'unit', 'biaya' => 20000000]]),
            'file_proposal' => 'proposals/sample_v2.pdf',
            'catatan_revisi_pemohon' => 'Catatan revisi versi 2 dari pemohon',
        ]);

        $proposal->update([
            'versi_aktif' => 2,
            'status' => 'diajukan',
        ]);

        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        // Mengakses versi aktif default
        $responseActive = $this->get(route('proposals.show', $proposal->id));
        $responseActive->assertStatus(200);
        $responseActive->assertSee('v2.0');
        $responseActive->assertSee('Catatan revisi versi 2 dari pemohon');

        // Mengakses versi 1 (arsip) melalui query parameter ?version=1
        $responseArchive = $this->get(route('proposals.show', [$proposal->id, 'version' => 1]));
        $responseArchive->assertSee('v1.0');
        $responseArchive->assertSee('(Arsip)');
        $responseArchive->assertSee('Anda sedang melihat arsip dokumen');
    }

    public function test_proposal_revisi_kembali_masuk_antrean_telaah_wabup(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        // Simulasi revisi dikirim
        $version2 = ProposalVersion::create([
            'proposal_id' => $proposal->id,
            'nomor_versi' => 2,
            'latar_belakang' => 'Latar belakang v2 revisi',
            'tujuan' => 'Tujuan v2 revisi',
            'lokasi_kegiatan' => 'Lokasi Puruk Cahu',
            'rincian_rab' => json_encode([['item' => 'Perlengkapan V2', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 15000000]]),
            'catatan_revisi_pemohon' => 'Telah disesuaikan sesuai arahan pimpinan.',
        ]);

        $proposal->update([
            'versi_aktif' => 2,
            'status' => 'diajukan',
        ]);

        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        // Masuk di dasbor Wabup
        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee($proposal->nomor_registrasi);

        // Detail menampilkan versi 2 aktif dan catatan pemohon
        $detail = $this->get(route('proposals.show', $proposal->id));
        $detail->assertStatus(200);
        $detail->assertSee('Telah disesuaikan sesuai arahan pimpinan.');
        $detail->assertSee('Keputusan Disposisi Pimpinan');

        // Wabup menyetujui versi 2
        $reviewResponse = $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Revisi telah sesuai dan disetujui penuh untuk realisasi.',
        ]);

        $reviewResponse->assertRedirect(route('proposals.show', $proposal->id));

        $proposal->refresh();
        $this->assertEquals('disetujui', $proposal->status);

        $this->assertDatabaseHas('review_decisions', [
            'proposal_id' => $proposal->id,
            'version_id' => $version2->id,
            'keputusan' => 'disetujui',
        ]);
    }

    public function test_pengusul_a_tidak_dapat_merevisi_proposal_pengusul_b(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        // Buat pengusul lain
        $otherPengusul = User::factory()->create([
            'role' => 'pengusul',
        ]);

        $this->actingAs($otherPengusul);

        // GET form revisi harus 403
        $getResponse = $this->get(route('proposals.revise', $proposal->id));
        $getResponse->assertStatus(403);

        // POST revisi harus 403
        $postResponse = $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Upaya Pembajakan Proposal',
            'total_anggaran' => 10000000,
            'lokasi_kegiatan' => 'Puruk Cahu',
            'catatan_revisi_pemohon' => 'Catatan ilegal',
        ]);
        $postResponse->assertStatus(403);
    }

    public function test_pengusul_tidak_dapat_merevisi_proposal_yang_statusnya_bukan_perlu_perbaikan(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        // Status masih "diajukan"
        $response = $this->get(route('proposals.revise', $proposal->id));
        $response->assertRedirect(route('proposals.show', $proposal->id));
        $response->assertSessionHas('error');

        $postResponse = $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Judul Baru',
            'total_anggaran' => 10000000,
            'lokasi_kegiatan' => 'Puruk Cahu',
            'catatan_revisi_pemohon' => 'Catatan',
        ]);
        $postResponse->assertRedirect(route('proposals.show', $proposal->id));
        $postResponse->assertSessionHas('error');
    }

    public function test_admin_tidak_dapat_melakukan_keputusan_maupun_revisi(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        // Admin dilarang merevisi (403)
        $this->get(route('proposals.revise', $proposal->id))->assertStatus(403);
        $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Judul Admin',
            'total_anggaran' => 10000000,
            'lokasi_kegiatan' => 'Puruk Cahu',
            'catatan_revisi_pemohon' => 'Catatan Admin',
        ])->assertStatus(403);

        // Admin dilarang memberikan keputusan review (403)
        $this->post(route('proposals.review', $proposal->id), [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Persetujuan ilegal dari Admin',
        ])->assertStatus(403);
    }

    public function test_validasi_form_revisi_wajib_mengisi_catatan_perbaikan_pemohon(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['status' => 'perlu_perbaikan']);

        $pengusul = $proposal->user;
        $this->actingAs($pengusul);

        $response = $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Proposal Revisi Tanpa Catatan',
            'total_anggaran' => 20000000,
            'lokasi_kegiatan' => 'Puruk Cahu',
            // catatan_revisi_pemohon sengaja dikosongkan
        ]);

        $response->assertSessionHasErrors(['catatan_revisi_pemohon']);
    }
}
