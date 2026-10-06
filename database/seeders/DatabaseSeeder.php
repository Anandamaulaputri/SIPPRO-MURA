<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Disposition;
use App\Models\Profile;
use App\Models\Proposal;
use App\Models\ProposalAttachment;
use App\Models\ProposalVersion;
use App\Models\ReviewDecision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Kategori Bantuan Proposal
        $categoriesData = [
            [
                'nama_kategori' => 'Sosial Kemasyarakatan',
                'keterangan' => 'Bantuan untuk kegiatan ormas, paguyuban, lansia, dan pemberdayaan kesejahteraan masyarakat.',
            ],
            [
                'nama_kategori' => 'Keagamaan',
                'keterangan' => 'Bantuan pembangunan/rehabilitasi sarana ibadah, perayaan hari besar, dan lembaga dakwah.',
            ],
            [
                'nama_kategori' => 'Kepemudaan & Olahraga',
                'keterangan' => 'Bantuan penyelenggaraan turnamen olahraga daerah, kepemudaan KNPI/Karang Taruna, dan pembinaan atlet.',
            ],
            [
                'nama_kategori' => 'Seni & Budaya',
                'keterangan' => 'Pelestarian seni tradisi suku Dayak Murung Raya, sanggar tari, festival adat, dan pentas budaya.',
            ],
            [
                'nama_kategori' => 'Pendidikan & Pelatihan',
                'keterangan' => 'Bantuan sarana belajar non-formal, beasiswa mahasiswa berprestasi/kurang mampu, dan seminar ilmiah.',
            ],
            [
                'nama_kategori' => 'Sarana & Lingkungan Desa',
                'keterangan' => 'Perbaikan fasilitas umum swadaya masyarakat desa, kebersihan lingkungan, dan sarana air bersih.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[$data['nama_kategori']] = Category::create($data);
        }

        // 2. Akun Demo Bawaan
        // A. Admin (Staf Bagian Administrasi Pimpinan)
        $admin = User::create([
            'name' => 'Staf Administrasi Pimpinan (Admin SIPPRO)',
            'email' => 'admin@murungrayakab.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
            'email_verified_at' => now(),
        ]);

        // B. Pimpinan (Wakil Bupati Murung Raya)
        $wabup = User::create([
            'name' => 'Wakil Bupati Murung Raya',
            'email' => 'wabup@murungrayakab.go.id',
            'password' => Hash::make('password123'),
            'role' => 'wabup',
            'no_telepon' => '081234567891',
            'email_verified_at' => now(),
        ]);

        // C. Pengusul (Masyarakat / Ormas)
        $pemohon = User::create([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'pemohon@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pengusul',
            'no_telepon' => '085249001122',
            'email_verified_at' => now(),
        ]);

        Profile::create([
            'user_id' => $pemohon->id,
            'jenis_pemohon' => 'organisasi',
            'nama_lembaga' => 'Karang Taruna Mura Bersatu',
            'nomor_identitas' => 'KT-MURA-2024-089',
            'alamat' => 'Jl. Jenderal Sudirman No. 45, RT 04 / RW 02, Puruk Cahu, Kec. Murung, Kab. Murung Raya',
            'nama_bank' => 'Bank Kalteng Cabang Puruk Cahu',
            'nomor_rekening' => '100-02-005432-1',
            'nama_pemilik_rekening' => 'Karang Taruna Mura Bersatu',
        ]);

        // 3. Data Sampel Proposal untuk Demo Alur
        // Proposal 1: Status Diajukan (Menunggu Telaah Pimpinan)
        $prop1 = Proposal::create([
            'nomor_registrasi' => 'PROP-202609-0001',
            'user_id' => $pemohon->id,
            'category_id' => $categories['Kepemudaan & Olahraga']->id,
            'judul_proposal' => 'Pengadaan Sarana Olahraga & Turnamen Futsal Pemuda Murung Raya 2026',
            'total_anggaran' => 45000000,
            'status' => 'diajukan',
            'versi_aktif' => 1,
            'tanggal_kirim' => Carbon::now()->subHours(6),
            'batas_waktu' => Carbon::now()->addDays(10),
        ]);

        $ver1 = ProposalVersion::create([
            'proposal_id' => $prop1->id,
            'nomor_versi' => 1,
            'latar_belakang' => 'Pemuda merupakan motor penggerak daerah. Turnamen ini dirancang untuk menyalurkan energi positif generasi muda di Puruk Cahu serta mempererat persatuan antarkecamatan di Murung Raya.',
            'tujuan' => '1. Menumbuhkan sportivitas dan solidaritas generasi muda Kabupaten Murung Raya.\n2. Mengidentifikasi bibit-bibit atlet futsal berbakat untuk ajang tingkat provinsi Kalteng.\n3. Menyediakan wadah positif terhindar dari penyalahgunaan obat terlarang.',
            'lokasi_kegiatan' => 'GOR Tira Tangka Balang, Puruk Cahu',
            'tanggal_kegiatan' => Carbon::now()->addWeeks(3),
            'rincian_rab' => json_encode([
                ['item' => 'Sewa Gedung Olahraga (3 Hari)', 'volume' => 3, 'satuan' => 'hari', 'biaya' => 6000000],
                ['item' => 'Hadiah Juara I, II, III & Uang Pembinaan', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 20000000],
                ['item' => 'Honorarium Wasit Bersertifikat & Panitia', 'volume' => 10, 'satuan' => 'orang', 'biaya' => 10000000],
                ['item' => 'Medali, Piala Bergilir & Piagam', 'volume' => 1, 'satuan' => 'set', 'biaya' => 4000000],
                ['item' => 'Konsumsi & P3K', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 5000000],
            ]),
            'file_proposal' => 'proposals/proposal_futsal_mura_2026.pdf',
        ]);

        ProposalAttachment::create([
            'version_id' => $ver1->id,
            'jenis_lampiran' => 'ktp',
            'nama_file' => 'KTP_Ketua_Budi_Santoso.pdf',
            'file_path' => 'attachments/ktp_budi.pdf',
            'ukuran_file' => 512000,
        ]);

        ProposalAttachment::create([
            'version_id' => $ver1->id,
            'jenis_lampiran' => 'akta',
            'nama_file' => 'SK_Kepengurusan_Karang_Taruna.pdf',
            'file_path' => 'attachments/sk_karang_taruna.pdf',
            'ukuran_file' => 1240000,
        ]);

        // Disposisi Tata Usaha Pimpinan untuk Proposal 1
        Disposition::create([
            'proposal_id' => $prop1->id,
            'version_id' => $ver1->id,
            'petugas_id' => $admin->id,
            'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
            'nomor_surat' => '089/KT-MURA/IX/2026',
            'tanggal_surat' => Carbon::now()->subHours(5),
            'asal_surat' => 'Karang Taruna Mura Bersatu',
            'tujuan_disposisi' => 'Wakil Bupati Murung Raya',
            'tanggal_disposisi' => Carbon::now()->subHours(2),
            'catatan_disposisi' => 'Berkas permohonan sarana olahraga pemuda. Administrasi lengkap dan diteruskan ke meja Bapak Wakil Bupati.',
            'status_disposisi' => 'selesai',
        ]);

        // Proposal 2: Disetujui oleh Wakil Bupati
        $prop2 = Proposal::create([
            'nomor_registrasi' => 'PROP-202609-0002',
            'user_id' => $pemohon->id,
            'category_id' => $categories['Seni & Budaya']->id,
            'judul_proposal' => 'Pelestarian Seni Tari Tradisional Dayak Siang dan Festival Budaya Pelajar',
            'total_anggaran' => 35000000,
            'status' => 'disetujui',
            'versi_aktif' => 1,
            'tanggal_kirim' => Carbon::now()->subDays(5),
            'batas_waktu' => Carbon::now()->addDays(20),
        ]);

        $ver2 = ProposalVersion::create([
            'proposal_id' => $prop2->id,
            'nomor_versi' => 1,
            'latar_belakang' => 'Warisan budaya Dayak Siang di Murung Raya sangat kaya dan bernilai luhur. Penting bagi generasi muda dan pelajar untuk terus mempelajari serta melestarikan tarian dan musik tradisional.',
            'tujuan' => 'Melatih 100 pelajar SMP dan SMA se-Puruk Cahu mengenai tarian khas Dayak Siang dan menampilkan karya dalam pergelaran budaya HUT Murung Raya.',
            'lokasi_kegiatan' => 'Gedung Pertemuan Umum (GPU) Tira Tangka Balang',
            'tanggal_kegiatan' => Carbon::now()->addMonth(),
            'rincian_rab' => json_encode([
                ['item' => 'Honor Tokoh Adat / Pelatih Tari Senior', 'volume' => 4, 'satuan' => 'orang', 'biaya' => 12000000],
                ['item' => 'Pengadaan Kostum & Properti Tari Tradisional', 'volume' => 20, 'satuan' => 'set', 'biaya' => 15000000],
                ['item' => 'Sewa Sound System & Dokumentasi', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 8000000],
            ]),
            'file_proposal' => 'proposals/pelestarian_budaya_dayak.pdf',
        ]);

        Disposition::create([
            'proposal_id' => $prop2->id,
            'version_id' => $ver2->id,
            'petugas_id' => $admin->id,
            'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
            'nomor_surat' => '024/ST-MURA/VIII/2026',
            'tanggal_surat' => Carbon::now()->subDays(6),
            'asal_surat' => 'Karang Taruna Mura Bersatu',
            'tujuan_disposisi' => 'Wakil Bupati Murung Raya',
            'tanggal_disposisi' => Carbon::now()->subDays(4),
            'catatan_disposisi' => 'Berkas lengkap dan memenuhi syarat administrasi keormasan. Diteruskan ke meja Bapak Wakil Bupati untuk perkenan arahan kebijakan.',
            'status_disposisi' => 'selesai',
        ]);

        ReviewDecision::create([
            'proposal_id' => $prop2->id,
            'version_id' => $ver2->id,
            'reviewer_id' => $wabup->id,
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Sangat mendukung pelestarian seni budaya Dayak Siang khas Murung Raya. Usulan disetujui penuh sesuai permohonan. Segera koordinasikan dengan Bagian Kesra dan Dinas Dikbud untuk penatausahaan pencairan.',
            'tanggal_keputusan' => Carbon::now()->subDays(2),
        ]);

        // Proposal 3: Perlu Perbaikan (Ada catatan dari Wabup)
        $prop3 = Proposal::create([
            'nomor_registrasi' => 'PROP-202609-0003',
            'user_id' => $pemohon->id,
            'category_id' => $categories['Keagamaan']->id,
            'judul_proposal' => 'Bantuan Renovasi Kubah & Tempat Wudhu Musholla Al-Muhajirin Desa Beriwit',
            'total_anggaran' => 60000000,
            'status' => 'perlu_perbaikan',
            'versi_aktif' => 1,
            'tanggal_kirim' => Carbon::now()->subDays(3),
            'batas_waktu' => Carbon::now()->addDays(5),
        ]);

        $ver3 = ProposalVersion::create([
            'proposal_id' => $prop3->id,
            'nomor_versi' => 1,
            'latar_belakang' => 'Musholla Al-Muhajirin Desa Beriwit membutuhkan perbaikan kubah yang bocor serta perluasan tempat wudhu jemaah.',
            'tujuan' => 'Memberikan kenyamanan dan kekhusyukan beribadah bagi warga sekitar.',
            'lokasi_kegiatan' => 'Desa Beriwit, Puruk Cahu',
            'tanggal_kegiatan' => Carbon::now()->addWeeks(2),
            'rincian_rab' => json_encode([
                ['item' => 'Material Baja Ringan & Atap Kubah', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 35000000],
                ['item' => 'Pipa, Keramik, dan Sanitasi Tempat Wudhu', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 15000000],
                ['item' => 'Upah Tukang & Pengerjaan', 'volume' => 1, 'satuan' => 'paket', 'biaya' => 10000000],
            ]),
            'file_proposal' => 'proposals/renovasi_musholla.pdf',
        ]);

        Disposition::create([
            'proposal_id' => $prop3->id,
            'version_id' => $ver3->id,
            'petugas_id' => $admin->id,
            'pejabat_disposisi' => 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)',
            'nomor_surat' => '012/MAM-BERIWIT/IX/2026',
            'tanggal_surat' => Carbon::now()->subDays(4),
            'asal_surat' => 'Pengurus Musholla Al-Muhajirin Desa Beriwit',
            'tujuan_disposisi' => 'Wakil Bupati Murung Raya',
            'tanggal_disposisi' => Carbon::now()->subDays(2),
            'catatan_disposisi' => 'Permohonan bantuan perbaikan tempat ibadah desa. Berkas diteruskan ke meja Bapak Wakil Bupati.',
            'status_disposisi' => 'selesai',
        ]);

        ReviewDecision::create([
            'proposal_id' => $prop3->id,
            'version_id' => $ver3->id,
            'reviewer_id' => $wabup->id,
            'keputusan' => 'perlu_perbaikan',
            'catatan_pimpinan' => 'Mohon lampirkan foto fisik kondisi atap musholla yang rusak saat ini, serta surat pernyataan hibah/wakaf tanah dari pengurus desa agar memenuhi syarat tertib administrasi bansos.',
            'tanggal_keputusan' => Carbon::now()->subDay(),
        ]);

        // Proposal 4: Status Diajukan (Menunggu Disposisi Pak Haziral)
        $prop4 = Proposal::create([
            'nomor_registrasi' => 'PROP-202609-0004',
            'user_id' => $pemohon->id,
            'category_id' => $categories['Sosial Kemasyarakatan']->id,
            'judul_proposal' => 'Pengadaan Sarana & Alat Kesehatan Posyandu Lansia Sehat Mandiri Puruk Cahu',
            'total_anggaran' => 20000000,
            'status' => 'diajukan',
            'versi_aktif' => 1,
            'tanggal_kirim' => Carbon::now()->subHours(1),
            'batas_waktu' => Carbon::now()->addDays(1),
        ]);

        $ver4 = ProposalVersion::create([
            'proposal_id' => $prop4->id,
            'nomor_versi' => 1,
            'latar_belakang' => 'Peningkatan pelayanan kesehatan bagi lansia dan posyandu swadaya masyarakat.',
            'tujuan' => 'Menyediakan sarana tensimeter, timbangan, dan nutrisi sehat lansia.',
            'lokasi_kegiatan' => 'Puruk Cahu, Murung Raya',
            'tanggal_kegiatan' => Carbon::now()->addWeeks(3),
            'rincian_rab' => json_encode([
                ['item' => 'Tensimeter Digital & Timbangan', 'volume' => 5, 'satuan' => 'unit', 'biaya' => 5000000],
                ['item' => 'Paket Nutrisi PMT Lansia', 'volume' => 100, 'satuan' => 'paket', 'biaya' => 10000000],
                ['item' => 'Honorarium Petugas Kesehatan', 'volume' => 5, 'satuan' => 'orang', 'biaya' => 5000000],
            ]),
            'file_proposal' => 'proposals/posyandu_lansia.pdf',
        ]);

        // 4. Log Aktivitas Demo
        ActivityLog::create([
            'user_id' => $pemohon->id,
            'aktivitas' => 'Mengajukan Proposal Baru',
            'keterangan' => 'Proposal PROP-202609-0001 diajukan ke sistem.',
            'ip_address' => '127.0.0.1',
        ]);

        ActivityLog::create([
            'user_id' => $wabup->id,
            'aktivitas' => 'Menyetujui Usulan Proposal',
            'keterangan' => 'Wakil Bupati menyetujui proposal PROP-202609-0002.',
            'ip_address' => '127.0.0.1',
        ]);

        ActivityLog::create([
            'user_id' => $wabup->id,
            'aktivitas' => 'Memberikan Disposisi Perbaikan',
            'keterangan' => 'Catatan perbaikan dikirimkan untuk proposal PROP-202609-0003.',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
