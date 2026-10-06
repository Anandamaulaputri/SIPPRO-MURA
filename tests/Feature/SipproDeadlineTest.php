<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Disposition;
use App\Models\Proposal;
use App\Models\ProposalVersion;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SipproDeadlineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test 1: deadline > 7 hari = AMAN
     */
    public function test_deadline_lebih_dari_7_hari_berstatus_aman(): void
    {
        $proposal = Proposal::first();
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(10)]);

        $this->assertEquals(10, $proposal->sisa_hari);
        $this->assertEquals('aman', $proposal->deadline_status);
        $this->assertEquals('Aman (Sisa 10 hari)', $proposal->deadline_label);
        $this->assertStringContainsString('emerald', $proposal->deadline_color);
    }

    /**
     * Test 2: deadline 3–7 hari = MENDEKATI BATAS WAKTU
     */
    public function test_deadline_3_sampai_7_hari_berstatus_mendekati_batas_waktu(): void
    {
        $proposal = Proposal::first();

        // Uji batas atas: 7 hari
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(7)]);
        $this->assertEquals(7, $proposal->sisa_hari);
        $this->assertEquals('mendekati', $proposal->deadline_status);
        $this->assertEquals('Mendekati Batas Waktu (7 hari lagi)', $proposal->deadline_label);

        // Uji nilai tengah: 5 hari
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(5)]);
        $this->assertEquals(5, $proposal->sisa_hari);
        $this->assertEquals('mendekati', $proposal->deadline_status);
        $this->assertEquals('Mendekati Batas Waktu (5 hari lagi)', $proposal->deadline_label);

        // Uji batas bawah: 3 hari
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(3)]);
        $this->assertEquals(3, $proposal->sisa_hari);
        $this->assertEquals('mendekati', $proposal->deadline_status);
        $this->assertEquals('Mendekati Batas Waktu (3 hari lagi)', $proposal->deadline_label);
    }

    /**
     * Test 3: deadline 0–2 hari = MENDESAK
     */
    public function test_deadline_0_sampai_2_hari_berstatus_mendesak(): void
    {
        $proposal = Proposal::first();

        // 2 hari lagi
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(2)]);
        $this->assertEquals(2, $proposal->sisa_hari);
        $this->assertEquals('mendesak', $proposal->deadline_status);
        $this->assertEquals('Mendesak (2 hari lagi)', $proposal->deadline_label);
        $this->assertStringContainsString('orange', $proposal->deadline_color);

        // 1 hari lagi
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(1)]);
        $this->assertEquals(1, $proposal->sisa_hari);
        $this->assertEquals('mendesak', $proposal->deadline_status);
        $this->assertEquals('Mendesak (1 hari lagi)', $proposal->deadline_label);

        // 0 hari (Hari Ini)
        $proposal->update(['batas_waktu' => Carbon::now()]);
        $this->assertEquals(0, $proposal->sisa_hari);
        $this->assertEquals('mendesak', $proposal->deadline_status);
        $this->assertEquals('Mendesak (Hari Ini)', $proposal->deadline_label);
    }

    /**
     * Test 4: deadline < 0 hari = TERLEWAT
     */
    public function test_deadline_kurang_dari_0_hari_berstatus_terlewat(): void
    {
        $proposal = Proposal::first();
        $proposal->update(['batas_waktu' => Carbon::now()->subDays(2)]);

        $this->assertLessThan(0, $proposal->sisa_hari);
        $this->assertEquals('terlewat', $proposal->deadline_status);
        $this->assertEquals('Terlewat / Perlu Perhatian', $proposal->deadline_label);
        $this->assertStringContainsString('rose', $proposal->deadline_color);
    }

    /**
     * Test 5: deadline terlewat tidak mengubah status proposal (tidak auto-reject / auto-cancel)
     */
    public function test_deadline_terlewat_tidak_mengubah_status_proposal(): void
    {
        $proposal = Proposal::where('status', 'diajukan')->first();
        $this->assertNotNull($proposal);

        // Atur deadline sudah lewat 10 hari yang lalu
        $proposal->update(['batas_waktu' => Carbon::now()->subDays(10)]);
        $proposal->refresh();

        // Status proposal WAJIB tetap 'diajukan'
        $this->assertEquals('diajukan', $proposal->status);
        $this->assertEquals('terlewat', $proposal->deadline_status);
        $this->assertEquals('Terlewat / Perlu Perhatian', $proposal->deadline_label);
    }

    /**
     * Test 6: revisi v2 tidak mereset deadline
     */
    public function test_revisi_v2_tidak_mereset_deadline(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        // Buat proposal dengan deadline tertentu
        $targetDeadline = Carbon::parse('2026-10-10');
        $proposal = Proposal::where('user_id', $pengusul->id)->first();
        $proposal->update([
            'status' => 'perlu_perbaikan',
            'batas_waktu' => $targetDeadline,
            'versi_aktif' => 1,
        ]);

        $this->assertEquals('2026-10-10', $proposal->batas_waktu->format('Y-m-d'));

        // Pengusul mengirim revisi v2
        $response = $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Judul Proposal Versi 2 Perbaikan',
            'lokasi_kegiatan' => 'Puruk Cahu, Murung Raya',
            'latar_belakang' => 'Latar belakang perbaikan proposal versi 2 telah disesuaikan secara detail dan komprehensif.',
            'total_anggaran' => 15000000,
            'catatan_revisi_pemohon' => 'Telah melengkapi rincian biaya sesuai arahan pimpinan.',
            'batas_waktu' => '2026-12-31', // Upaya manipulasi oleh pengusul
        ]);

        $response->assertRedirect(route('proposals.show', $proposal->id));

        $proposal->refresh();
        $this->assertEquals(2, $proposal->versi_aktif);
        $this->assertEquals('diajukan', $proposal->status);

        // DEADLINE HARUS TETAP 10 OKTOBER 2026, TIDAK TER-RESET DAN TIDAK TERMANIPULASI
        $this->assertEquals('2026-10-10', $proposal->batas_waktu->format('Y-m-d'));
    }

    /**
     * Test 7: revisi v3 tidak mereset deadline
     */
    public function test_revisi_v3_tidak_mereset_deadline(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $targetDeadline = Carbon::parse('2026-10-10');
        $proposal = Proposal::where('user_id', $pengusul->id)->first();
        $proposal->update([
            'status' => 'perlu_perbaikan',
            'batas_waktu' => $targetDeadline,
            'versi_aktif' => 1,
        ]);

        // Kirim revisi v2
        $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Judul Proposal Versi 2',
            'lokasi_kegiatan' => 'Puruk Cahu, Murung Raya',
            'latar_belakang' => 'Latar belakang versi 2 perbaikan berkas administrasi dan data dukung proposal.',
            'total_anggaran' => 16000000,
            'catatan_revisi_pemohon' => 'Catatan revisi versi 2.',
        ]);

        $proposal->refresh();
        $this->assertEquals(2, $proposal->versi_aktif);

        // Set status kembali ke perlu_perbaikan untuk membuka revisi v3
        $proposal->update(['status' => 'perlu_perbaikan']);

        // Pengusul mengirim revisi v3
        $response = $this->post(route('proposals.submit_revision', $proposal->id), [
            'judul_proposal' => 'Judul Proposal Versi 3 Final',
            'lokasi_kegiatan' => 'Puruk Cahu, Murung Raya',
            'latar_belakang' => 'Latar belakang perbaikan proposal versi 3 lengkap dengan kajian kebutuhan masyarakat.',
            'total_anggaran' => 18000000,
            'catatan_revisi_pemohon' => 'Revisi tahap 3 dengan penyempurnaan lampiran.',
        ]);

        $response->assertRedirect(route('proposals.show', $proposal->id));

        $proposal->refresh();
        $this->assertEquals(3, $proposal->versi_aktif);
        $this->assertEquals('diajukan', $proposal->status);

        // Batas waktu tetap 10 Oktober 2026
        $this->assertEquals('2026-10-10', $proposal->batas_waktu->format('Y-m-d'));
    }

    /**
     * Test 8: Admin dapat melihat deadline dan memperbarui batas waktu
     */
    public function test_admin_dapat_melihat_dan_memperbarui_deadline(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::where('status', 'diajukan')->first();
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(5)]);

        // Cek dashboard admin melihat badge deadline
        $responseDashboard = $this->get(route('dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('Mendekati Batas Waktu');

        // Cek halaman show proposal
        $responseShow = $this->get(route('proposals.show', $proposal->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Batas Waktu Tindak Lanjut');

        // Admin memperbarui batas waktu
        $newDeadline = Carbon::now()->addDays(14)->format('Y-m-d');
        $responseUpdate = $this->patch(route('proposals.update_deadline', $proposal->id), [
            'batas_waktu' => $newDeadline,
        ]);

        $responseUpdate->assertRedirect();
        $proposal->refresh();
        $this->assertEquals($newDeadline, $proposal->batas_waktu->format('Y-m-d'));
    }

    /**
     * Test 9: Wabup dapat melihat deadline pada dashboard dan detail
     */
    public function test_wabup_dapat_melihat_deadline_pada_dashboard_dan_detail(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        // Pastikan ada proposal diajukan yang sudah selesai disposisi
        $proposal = Proposal::where('status', 'diajukan')->disposed()->first();
        $this->assertNotNull($proposal);
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(4)]);

        // Cek dashboard Wabup
        $dashboardResponse = $this->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Mendekati Batas Waktu');

        // Cek detail proposal
        $showResponse = $this->get(route('proposals.show', $proposal->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Batas Waktu Tindak Lanjut');
    }

    /**
     * Test 10: Sorting berdasarkan deadline berjalan di dashboard
     */
    public function test_sorting_berdasarkan_deadline_berjalan_di_dashboard(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        // Buat 2 proposal dengan disposisi selesai tapi deadline berbeda
        $propA = Proposal::where('status', 'diajukan')->disposed()->first();
        $propA->update(['batas_waktu' => Carbon::now()->addDays(9)]); // Deadline lebih lambat

        $category = Category::first();
        $propB = Proposal::create([
            'nomor_registrasi' => 'PROP-URGENT-001',
            'user_id' => $propA->user_id,
            'category_id' => $category->id,
            'judul_proposal' => 'Proposal Sangat Mendesak',
            'total_anggaran' => 25000000,
            'status' => 'diajukan',
            'versi_aktif' => 1,
            'tanggal_kirim' => Carbon::now(),
            'batas_waktu' => Carbon::now()->addDays(1), // Deadline lebih mendesak
        ]);

        $verB = ProposalVersion::create([
            'proposal_id' => $propB->id,
            'nomor_versi' => 1,
            'lokasi_kegiatan' => 'Puruk Cahu',
        ]);

        Disposition::create([
            'proposal_id' => $propB->id,
            'version_id' => $verB->id,
            'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
            'tujuan_disposisi' => 'Wakil Bupati Murung Raya',
            'tanggal_disposisi' => Carbon::now(),
            'status_disposisi' => 'selesai',
        ]);

        // Cek dashboard Wabup: proposal dengan deadline paling dekat (propB) harus muncul
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Verifikasi urutan query di model
        $sorted = Proposal::where('status', 'diajukan')->disposed()->orderByDeadline('asc')->pluck('nomor_registrasi')->toArray();
        $indexB = array_search('PROP-URGENT-001', $sorted);
        $indexA = array_search($propA->nomor_registrasi, $sorted);

        $this->assertTrue($indexB < $indexA, 'Proposal dengan deadline lebih dekat harus muncul lebih dahulu');
    }

    /**
     * Test 11: Pengusul tidak dapat memanipulasi batas waktu
     */
    public function test_pengusul_tidak_dapat_memanipulasi_batas_waktu(): void
    {
        $pengusul = User::where('role', 'pengusul')->first();
        $this->actingAs($pengusul);

        $proposal = Proposal::first();

        // Pengusul mencoba akses route update_deadline
        $response = $this->patch(route('proposals.update_deadline', $proposal->id), [
            'batas_waktu' => '2026-12-31',
        ]);

        $response->assertStatus(403);

        // Pengusul mencoba memasukkan batas_waktu saat create proposal baru
        $category = Category::first();
        $createResponse = $this->post(route('proposals.store'), [
            'judul_proposal' => 'Pengusul Coba Masukkan Deadline Sendiri',
            'category_id' => $category->id,
            'total_anggaran' => 10000000,
            'lokasi_kegiatan' => 'Puruk Cahu',
            'batas_waktu' => '2026-12-31',
        ]);

        $createdProp = Proposal::where('judul_proposal', 'Pengusul Coba Masukkan Deadline Sendiri')->first();
        $this->assertNotNull($createdProp);
        // Batas waktu harus null (tidak boleh diisi oleh pengusul)
        $this->assertNull($createdProp->batas_waktu);
    }

    /**
     * Test 12: Admin dapat mengisi batas waktu saat mencatat disposisi
     */
    public function test_admin_dapat_mengisi_batas_waktu_saat_mencatat_disposisi(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::where('status', 'diajukan')->waitingDisposition()->first();
        $this->assertNotNull($proposal);

        $targetDeadline = Carbon::now()->addDays(7)->format('Y-m-d');

        $response = $this->post(route('proposals.disposition', $proposal->id), [
            'nomor_surat' => '123/DISP-TU/X/2026',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'asal_surat' => 'Organisasi Masyarakat',
            'tujuan_disposisi' => 'Wakil Bupati Murung Raya',
            'tanggal_disposisi' => Carbon::now()->format('Y-m-d'),
            'catatan_disposisi' => 'Telah diverifikasi berkas administratif.',
            'batas_waktu' => $targetDeadline,
        ]);

        $response->assertRedirect(route('proposals.show', $proposal->id));
        $proposal->refresh();

        $this->assertTrue($proposal->isDisposed());
        $this->assertEquals($targetDeadline, $proposal->batas_waktu->format('Y-m-d'));
    }

    /**
     * Test 13: Public tracking menampilkan estimasi waktu pelayanan dengan bahasa aman
     */
    public function test_public_tracking_menampilkan_estimasi_waktu_dengan_aman(): void
    {
        $proposal = Proposal::first();
        $proposal->update(['batas_waktu' => Carbon::now()->addDays(5)]);

        $response = $this->post(route('tracking.search'), [
            'nomor_registrasi' => $proposal->nomor_registrasi,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Perkiraan Waktu Pelayanan');
        $response->assertSee($proposal->formatted_batas_waktu);
        $response->assertSee('Mendekati Batas Waktu');
        $response->assertDontSee('SLA'); // Memastikan tidak mengekspos istilah internal
    }
}
