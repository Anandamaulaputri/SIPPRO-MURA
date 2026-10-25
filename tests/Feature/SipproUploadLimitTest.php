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

class SipproUploadLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_unggah_dokumen_di_bawah_10_mb_berhasil_diterima(): void
    {
        Storage::fake('public');

        $user = User::where('role', 'pengusul')->first();
        $this->actingAs($user);

        $category = Category::first();

        // Buat file 8 MB (8192 KB) yang valid (> 5 MB tapi < 10 MB)
        $validPdf = UploadedFile::fake()->create('proposal_8mb.pdf', 8192, 'application/pdf');
        $validKtp = UploadedFile::fake()->create('ktp_7mb.jpg', 7168, 'image/jpeg');
        $validRekening = UploadedFile::fake()->create('buku_rekening_6mb.png', 6144, 'image/png');

        $payload = [
            'category_id' => $category->id,
            'nomor_proposal_pengusul' => 'PROP-TEST-10MB-001',
            'judul_proposal' => 'Pengajuan Bantuan Sarana Uji Upload 10 MB',
            'latar_belakang' => 'Latar belakang pengajuan proposal untuk pengujian batas upload dokumen hingga 10 MB.',
            'tujuan' => 'Menguji penerimaan file berukuran antara 5 MB dan 10 MB agar berjalan lancar.',
            'total_anggaran' => 25000000,
            'lokasi_kegiatan' => 'Kecamatan Murung, Puruk Cahu',
            'file_proposal' => $validPdf,
            'lampiran_ktp' => $validKtp,
            'lampiran_rekening' => $validRekening,
        ];

        $response = $this->post(route('proposals.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $proposal = Proposal::where('judul_proposal', 'Pengajuan Bantuan Sarana Uji Upload 10 MB')->first();
        $this->assertNotNull($proposal);
        $this->assertCount(2, $proposal->latestVersion->attachments);
        $this->assertNotNull($proposal->latestVersion->file_proposal);
        Storage::disk('public')->assertExists($proposal->latestVersion->file_proposal);
    }

    public function test_unggah_dokumen_di_atas_10_mb_ditolak_oleh_validasi(): void
    {
        Storage::fake('public');

        $user = User::where('role', 'pengusul')->first();
        $this->actingAs($user);

        $category = Category::first();

        // Buat file 11 MB (11264 KB) > 10 MB
        $oversizedPdf = UploadedFile::fake()->create('proposal_11mb.pdf', 11264, 'application/pdf');
        $oversizedKtp = UploadedFile::fake()->create('ktp_11mb.jpg', 11264, 'image/jpeg');
        $oversizedRekening = UploadedFile::fake()->create('buku_rekening_11mb.png', 11264, 'image/png');

        $payload = [
            'category_id' => $category->id,
            'nomor_proposal_pengusul' => 'PROP-TEST-OVERSIZE',
            'judul_proposal' => 'Pengajuan Proposal Uji Batas Ukuran Melebihi 10 MB',
            'latar_belakang' => 'Latar belakang pengajuan proposal untuk pengujian penolakan file melebihi batas 10 MB.',
            'tujuan' => 'Memastikan sistem secara konsisten menolak berkas yang melebihi batas 10 MB.',
            'total_anggaran' => 25000000,
            'lokasi_kegiatan' => 'Kecamatan Murung, Puruk Cahu',
            'file_proposal' => $oversizedPdf,
            'lampiran_ktp' => $oversizedKtp,
            'lampiran_rekening' => $oversizedRekening,
        ];

        $response = $this->post(route('proposals.store'), $payload);

        $response->assertSessionHasErrors(['file_proposal', 'lampiran_ktp', 'lampiran_rekening']);
    }

    public function test_unggah_dokumen_dengan_tipe_file_tidak_diizinkan_tetap_ditolak(): void
    {
        Storage::fake('public');

        $user = User::where('role', 'pengusul')->first();
        $this->actingAs($user);

        $category = Category::first();

        // Buat file dengan ekstensi terlarang / tidak diizinkan
        $invalidProposal = UploadedFile::fake()->create('proposal.docx', 1024, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $invalidKtp = UploadedFile::fake()->create('ktp.exe', 512, 'application/x-msdownload');
        $invalidRekening = UploadedFile::fake()->create('rekening.zip', 512, 'application/zip');

        $payload = [
            'category_id' => $category->id,
            'nomor_proposal_pengusul' => 'PROP-TEST-INVALID-MIME',
            'judul_proposal' => 'Pengajuan Proposal Uji Format Tidak Sah',
            'latar_belakang' => 'Latar belakang pengujian validasi tipe file yang diizinkan sistem.',
            'tujuan' => 'Memastikan sistem hanya mengizinkan berkas sesuai format yang sah.',
            'total_anggaran' => 15000000,
            'lokasi_kegiatan' => 'Kecamatan Tanah Siang',
            'file_proposal' => $invalidProposal,
            'lampiran_ktp' => $invalidKtp,
            'lampiran_rekening' => $invalidRekening,
        ];

        $response = $this->post(route('proposals.store'), $payload);

        $response->assertSessionHasErrors(['file_proposal', 'lampiran_ktp', 'lampiran_rekening']);
    }

    public function test_unggah_bukti_disposisi_admin_hingga_10_mb_berhasil_dan_di_atas_10_mb_ditolak(): void
    {
        Storage::fake('public');

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $proposal = Proposal::where('nomor_registrasi', 'PROP-202609-0004')->first();
        $this->assertNotNull($proposal);

        // 1. File di atas 10 MB harus ditolak
        $oversizedDisposisi = UploadedFile::fake()->create('disposisi_12mb.pdf', 12288, 'application/pdf');
        $failResponse = $this->post(route('proposals.disposition', $proposal->id), [
            'tujuan_disposisi' => 'Bapak Wakil Bupati Murung Raya',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'file_bukti_disposisi' => $oversizedDisposisi,
        ]);
        $failResponse->assertSessionHasErrors(['file_bukti_disposisi']);

        // 2. File tipe terlarang harus ditolak
        $invalidMimeDisposisi = UploadedFile::fake()->create('disposisi.zip', 1024, 'application/zip');
        $invalidResponse = $this->post(route('proposals.disposition', $proposal->id), [
            'tujuan_disposisi' => 'Bapak Wakil Bupati Murung Raya',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'file_bukti_disposisi' => $invalidMimeDisposisi,
        ]);
        $invalidResponse->assertSessionHasErrors(['file_bukti_disposisi']);

        // 3. File 8 MB valid (< 10 MB dan > 5 MB) berhasil diterima
        $validDisposisi = UploadedFile::fake()->create('disposisi_8mb.pdf', 8192, 'application/pdf');
        $successResponse = $this->post(route('proposals.disposition', $proposal->id), [
            'tujuan_disposisi' => 'Bapak Wakil Bupati Murung Raya',
            'tanggal_disposisi' => now()->format('Y-m-d'),
            'file_bukti_disposisi' => $validDisposisi,
        ]);
        $successResponse->assertRedirect(route('proposals.show', $proposal->id));
        $successResponse->assertSessionHas('success');
    }
}
