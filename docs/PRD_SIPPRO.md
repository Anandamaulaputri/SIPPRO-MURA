# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# SIPPRO MURA: SISTEM INFORMASI PELAYANAN PROPOSAL MURUNG RAYA

---

### IDENTITAS AKADEMIK & KEDINASAN

| Parameter Dokumen | Keterangan Lengkap |
| :--- | :--- |
| **Nama Sistem** | SIPPRO MURA (Sistem Informasi Pelayanan Proposal Murung Raya) |
| **Instansi Tempat Magang** | Sekretariat Daerah Kabupaten Murung Raya — Bagian Pelayanan Administrasi Pimpinan |
| **Institusi Pendidikan** | Politeknik Negeri Banjarmasin (Poliban) — Jurusan Teknik Elektro |
| **Program Studi** | D3 Teknik Informatika |
| **Penyusun (Mahasiswa)** | **Ananda Maula Putri** (NIM: **C030324009**) |
| **Dosen Pembimbing** | **Dhiyaussalam, M.Kom.** |
| **Pembimbing Lapangan** | **Haziral, S.E.** (KSB. Tata Usaha Pimpinan Setda Kab. Murung Raya) |
| **Versi Dokumen** | 1.0 (Edisi Magang Mahasiswa D3) |
| **Status Dokumen** | Siap Direview & Disahkan |
| **Tahun Penyusunan** | 2026 |

---

## DAFTAR ISI

1. Pendahuluan
   1.1 Latar Belakang
   1.2 Maksud dan Tujuan Pembuatan Sistem
   1.3 Batasan Masalah (Ruang Lingkup)
   1.4 Definisi Istilah
2. Gambaran Umum Sistem
   2.1 Deskripsi Produk SIPPRO MURA
   2.2 Fitur Unggulan Sistem
   2.3 Perangkat Lunak & Teknologi yang Digunakan
3. Pengguna Sistem & Hak Akses
   3.1 Peran Pengguna (User Roles)
   3.2 Aturan Khusus Pengambilan Keputusan
   3.3 Matriks Hak Akses Pengguna
4. Kebutuhan Sistem
   4.1 Kebutuhan Fungsional (Functional Requirements)
   4.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)
5. Perancangan Basis Data (Database)
   5.1 Daftar Tabel Basis Data
   5.2 Struktur Kolom dan Tipe Data
   5.3 Hubungan Antar Tabel (Relasi Data)
6. Alur Kerja Sistem (Workflow)
   6.1 Alur Pengajuan Proposal Baru
   6.2 Alur Penelaahan & Keputusan oleh Wakil Bupati
   6.3 Alur Perbaikan (Revisi) Proposal
   6.4 Status Siklus Hidup Proposal
7. Rencana Tahapan Pengembangan Sistem
8. Pengujian dan Kriteria Penerimaan Sistem

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang
Dalam penyelenggaraan pelayanan administrasi di Sekretariat Daerah Kabupaten Murung Raya, pimpinan daerah—khususnya Wakil Bupati—secara berkala menerima proposal kegiatan, permohonan bantuan, dan aspirasi dari masyarakat, organisasi kepemudaan, komunitas, maupun lembaga sosial keagamaan.

Berdasarkan pengamatan selama pelaksanaan magang di Bagian Pelayanan Administrasi Pimpinan, proses pengelolaan proposal saat ini masih dilakukan secara manual dengan beberapa kendala nyata:
1. **Penumpukan Berkas Fisik**: Berkas proposal yang masuk dalam bentuk dokumen cetak menumpuk di meja administrasi, sehingga rawan terselip, rusak, atau sulit dicari kembali saat dibutuhkan.
2. **Keterbatasan Pemantauan Status**: Pihak pemohon sering kali harus datang langsung ke kantor atau berulang kali menghubungi staf administrasi hanya untuk menanyakan apakah proposal mereka sudah dibaca oleh pimpinan.
3. **Frekuensi Revisi yang Kurang Terstruktur**: Banyak proposal yang diajukan belum lengkap berkas pendukungnya (seperti rincian anggaran yang belum jelas atau identitas legalitas yang belum dilampirkan), sehingga proses perbaikan memakan waktu lama.
4. **Kebutuhan Mobilitas Pimpinan**: Wakil Bupati memiliki agenda kerja kedinasan yang dinamis dan sering bertugas di luar kantor, sehingga diperlukan media yang memungkinkan peninjauan berkas proposal secara praktis melalui perangkat tablet atau ponsel pintar.

Oleh karena itu, dibangun sistem informasi berbasis web bernama **SIPPRO MURA (Sistem Informasi Pelayanan Proposal Murung Raya)** untuk membantu mempermudah pengajuan proposal oleh masyarakat serta mendukung penelaahan langsung oleh pimpinan daerah secara tertib dan terkomputerisasi.

### 1.2 Maksud dan Tujuan Pembuatan Sistem
Tujuan dari perancangan sistem informasi ini adalah:
1. Menyediakan aplikasi web mandiri bagi masyarakat/organisasi untuk mengajukan proposal permohonan secara online.
2. Membantu Wakil Bupati meninjau berkas proposal secara ringkas langsung di layar perangkat (komputer/tablet/ponsel) dan memberikan keputusan secara cepat.
3. Mengurangi potensi terjadinya revisi berulang dengan menyediakan panduan verifikasi kelengkapan mandiri sebelum pengusul mengirimkan proposal.
4. Membantu staf Bagian Pelayanan Administrasi Pimpinan dalam mengelola arsip proposal masuk dan mencetak rekapitulasi data laporan secara teratur.

### 1.3 Batasan Masalah (Ruang Lingkup)
Agar ruang lingkup tugas magang ini terarah dan realistis untuk diselesaikan sesuai jenjang D3 Teknik Informatika, batasan sistem ditentukan sebagai berikut:
1. Sistem mencakup pendaftaran akun pengusul, pengisian formulir usulan dan anggaran, unggah berkas PDF, pemeriksaan mandiri, serta peninjauan oleh Wakil Bupati.
2. Keputusan yang dapat diberikan oleh Wakil Bupati berupa tiga opsi resmi: **Disetujui**, **Perlu Perbaikan**, atau **Tidak Disetujui**.
3. Sistem menyediakan fitur pencetakan lembar disposisi persetujuan digital berformat PDF.
4. **Tidak mencakup proses pencairan anggaran keuangan daerah**. Proses pencairan uang tetap mengikuti prosedur perbendaharaan daerah yang berlaku melalui instansi/bagian keuangan terkait.
5. Format proposal utama yang diunggah difokuskan pada format dokumen **PDF**.

### 1.4 Definisi Istilah
- **SIPPRO MURA**: Sistem Informasi Pelayanan Proposal Murung Raya.
- **Pengusul**: Perorangan atau perwakilan organisasi masyarakat yang mengajukan proposal permohonan ke pimpinan daerah.
- **RAB**: Rencana Anggaran Biaya, yaitu perincian item estimasi biaya yang diajukan dalam proposal.
- **Checklist Ketelitian Mandiri**: Daftar periksa wajib yang harus dikonfirmasi oleh pengusul sebelum mengirimkan proposal agar berkas yang dikirim benar-benar lengkap dan siap dinilai.
- **Riwayat Versi (Versioning)**: Pencatatan nomor perbaikan dokumen (misalnya Versi 1 dan Versi 2) agar berkas lama tetap tersimpan dan tidak tertimpa saat ada perbaikan.
- **Disposisi**: Lembar keputusan/arahan tertulis dari Wakil Bupati terhadap usulan proposal yang masuk.

---

## 2. GAMBARAN UMUM SISTEM

### 2.1 Deskripsi Produk SIPPRO MURA
SIPPRO MURA adalah sistem informasi berbasis web yang dirancang untuk mengelola alur persuratan proposal masuk pada Bagian Pelayanan Administrasi Pimpinan Sekretariat Daerah Kabupaten Murung Raya. Sistem ini menghubungkan pengusul secara daring dengan meja kerja digital Wakil Bupati, sehingga proses verifikasi dan disposisi proposal dapat berlangsung lebih cepat dan tertib.

### 2.2 Fitur Unggulan Sistem
1. **Pengajuan Proposal Mandiri Secara Online**: Pengusul dapat mengisi data kegiatan, memasukkan item rincian anggaran, dan mengunggah berkas kapan saja tanpa harus mengantarkan berkas fisik ke kantor.
2. **Gerbang Cek Ketelitian Mandiri**: Fitur verifikasi mandiri yang mewajibkan pengusul memeriksa kelengkapan proposal sebelum tombol kirim aktif, sehingga mencegah pengiriman proposal yang belum lengkap.
3. **Penelaahan Langsung oleh Wakil Bupati**: Antarmuka ringkas khusus pimpinan untuk melihat daftar proposal masuk, memeriksa profil pengusul, dan membaca berkas proposal langsung di layar peramban.
4. **Keputusan Cepat 1-Klik**: Pimpinan dapat langsung memilih keputusan: *Disetujui*, *Perlu Perbaikan* (disertai catatan arahan revisi), atau *Tidak Disetujui*.
5. **Riwayat Perubahan Tersimpan Rapi**: Setiap perbaikan proposal akan tercatat sebagai versi baru tanpa menghapus data pengajuan sebelumnya.
6. **Notifikasi Informasi Status**: Pengusul mendapatkan pemberitahuan mengenai perubahan status proposal mereka secara berkala.

### 2.3 Perangkat Lunak & Teknologi yang Digunakan
Teknologi yang digunakan disesuaikan dengan kompetensi dasar mahasiswa D3 Teknik Informatika:

| Komponen Sistem | Teknologi yang Dipilih | Alasan Pemilihan |
| :--- | :--- | :--- |
| **Bahasa & Framework Backend** | PHP (Framework Laravel) | Framework web standar industri yang dipelajari di perkuliahan, memiliki struktur MVC rapi, dan mudah dikembangkan. |
| **Basis Data (Database)** | MySQL / MariaDB | Sistem manajemen basis data relasional yang stabil, populer, dan kompatibel penuh dengan Laravel. |
| **Antarmuka Pengguna (Frontend)**| HTML5, CSS / Tailwind CSS, JavaScript | Menghasilkan tampilan antarmuka web yang rapi, modern, dan responsif saat dibuka di laptop maupun ponsel/tablet. |
| **Penampil Dokumen** | Browser PDF Viewer / PDF.js | Menampilkan file proposal PDF secara langsung di halaman web tanpa harus mengunduh berkas terlebih dahulu. |
| **Penyimpanan Berkas** | Direktori Penyimpanan Server (*Laravel Storage*) | Menyimpan berkas proposal dan lampiran di direktori server lokal aplikasi secara terstruktur dan aman. |
| **Notifikasi Pengguna** | WhatsApp API / Email SMTP Sederhana | Mengirimkan pemberitahuan informasi status pengajuan kepada pengusul. |

---

## 3. PENGGUNA SISTEM & HAK AKSES

### 3.1 Peran Pengguna (User Roles)

#### A. Pengusul (Masyarakat / Organisasi)
Perwakilan organisasi kemasyarakatan, karang taruna, panitia kegiatan, yayasan, atau warga masyarakat yang memiliki hak akses untuk:
- Mendaftarkan akun dan mengelola data profil pemohon.
- Membuat usulan proposal baru dan mengisi rincian anggaran (RAB).
- Mengunggah berkas proposal utama dan dokumen lampiran pendukung.
- Mengisi checklist verifikasi mandiri sebelum mengirimkan proposal.
- Melihat status terkini dan membaca catatan revisi dari pimpinan.
- Mengunggah berkas perbaikan jika proposal dinyatakan perlu perbaikan.

#### B. Wakil Bupati (Pimpinan Daerah)
Pejabat pengambil keputusan utama atas proposal yang masuk, dengan hak akses untuk:
- Membuka halaman dasbor eksekutif yang ringkas dan mudah dioperasikan.
- Melihat daftar proposal yang masuk dengan status menunggu peninjauan.
- Membaca berkas proposal dan lampirannya secara langsung di layar web.
- Memberikan tindakan keputusan resmi: **Disetujui**, **Perlu Perbaikan**, atau **Tidak Disetujui**.
- Mengisikan catatan arahan perbaikan jika proposal perlu direvisi.

#### C. Staf Pelayanan Administrasi Pimpinan (Administrator)
Staf tata usaha yang bertugas mengelola ketertiban data dan administrasi sistem, dengan hak akses untuk:
- Mengelola data master kategori proposal dan akun pengguna.
- Memantau rekapitulasi proposal masuk secara keseluruhan.
- Membantu mencetak lembar disposisi resmi dan rekap data laporan berkala.

### 3.2 Aturan Khusus Pengambilan Keputusan
> **Catatan Penting**:
> Hak untuk menyetujui, menolak, atau meminta perbaikan proposal berada sepenuhnya pada **Wakil Bupati**. Staf Pelayanan Administrasi Pimpinan berperan sebagai administrator pengelola data dan arsip, serta tidak memiliki hak untuk mengubah atau mengambil keputusan persetujuan proposal.

### 3.3 Matriks Hak Akses Pengguna

| Fitur / Menu Sistem | Pengusul | Wakil Bupati | Staf Administrasi |
| :--- | :---: | :---: | :---: |
| Registrasi Akun Mandiri | Ya | Tidak | Tidak (Dibuat Admin) |
| Login ke Sistem | Ya | Ya | Ya |
| Mengisi Formulir & Mengunggah Proposal | Ya (Milik Sendiri) | Tidak | Tidak |
| Mengisi Checklist Ketelitian Mandiri | Ya | Tidak | Tidak |
| Melihat Proposal Masuk (Semua Data) | Tidak | Ya | Ya (Hanya Melihat) |
| Melihat Proposal Milik Sendiri | Ya | Tidak | Tidak |
| Membaca Berkas Dokumen PDF di Layar | Ya (Milik Sendiri) | Ya (Semua) | Ya (Semua) |
| Memberikan Keputusan (Setuju/Revisi/Tolak) | Tidak | **Ya (Hak Eksklusif)** | Tidak |
| Mengunggah Berkas Revisi Perbaikan | Ya (Milik Sendiri) | Tidak | Tidak |
| Melihat Riwayat Versi Proposal | Ya (Milik Sendiri) | Ya | Ya |
| Mengelola Master Kategori Proposal | Tidak | Tidak | Ya |
| Mencetak Lembar Disposisi & Rekapitulasi | Ya (Jika Disetujui) | Ya | Ya |

---

## 4. KEBUTUHAN SISTEM

### 4.1 Kebutuhan Fungsional (Functional Requirements)

#### FR-01: Modul Autentikasi & Pengelolaan Akun
1. Sistem menyediakan formulir pendaftaran akun bagi pengusul dengan mengisi nama lengkap, alamat email, kata sandi, nomor WhatsApp, dan jenis pemohon (perorangan atau organisasi).
2. Sistem menyediakan fitur login menggunakan email dan password terenkripsi.
3. Sistem menyediakan hak akses berbasis peran (*Role-Based Access*) yang memisahkan akses antara Pengusul, Wakil Bupati, dan Staf Administrasi.

#### FR-02: Modul Formulir Pengajuan Proposal Dinamis
1. Sistem menyediakan formulir input usulan proposal: Judul Proposal, Kategori Kegiatan, Rencana Tanggal Kegiatan, Lokasi Acara, Latar Belakang, dan Maksud Tujuan.
2. Sistem menyediakan tabel input dinamis Rencana Anggaran Biaya (RAB) yang menghitung total pengajuan secara otomatis dari baris nama barang, volume, satuan, dan harga satuan.
3. Pengusul dapat menyimpan data usulan sementara sebagai draf sebelum dikirimkan.

#### FR-03: Modul Unggah Berkas & Lampiran
1. Sistem menyediakan fasilitas unggah file dokumen proposal utama berformat PDF (dengan batas ukuran maksimal yang wajar, misalnya 10 MB).
2. Sistem menyediakan slot unggah berkas pendukung: Surat Pengantar, Kartu Tanda Penduduk (KTP) penanggung jawab, Dokumen Legalitas (bagi organisasi), dan Salinan Rekening Bank.
3. Sistem melakukan pemeriksaan sederhana untuk memastikan file yang diunggah bertipe dokumen atau gambar yang sah.

#### FR-04: Modul Checklist Ketelitian Mandiri (Anti-Revisi)
1. Sebelum tombol pengiriman aktif, sistem menampilkan jendela periksa mandiri berisi butir konfirmasi kelengkapan.
2. Pengusul wajib mencentang seluruh pernyataan integritas, antara lain:
   - Data kontak dan identitas pemohon sudah benar dan dapat dihubungi.
   - Berkas PDF proposal dapat dibuka dengan jelas dan bertanda tangan sah.
   - Perhitungan RAB telah sesuai dengan total nominal usulan.
   - Dokumen lampiran pendukung telah lengkap diunggah.
3. Tombol *"Kirim Proposal"* hanya dapat ditekan setelah seluruh checklist terkonfirmasi lengkap.

#### FR-05: Modul Penelaahan & Dasbor Wakil Bupati
1. Sistem menampilkan dasbor ringkas bagi Wakil Bupati berisi ringkasan jumlah proposal masuk, proposal yang disetujui, perlu perbaikan, dan ditolak.
2. Sistem menyajikan daftar proposal dalam bentuk tabel atau kartu informasi yang mencantumkan nama pengusul, judul kegiatan, nominal anggaran, dan tanggal pengajuan.
3. Sistem menyediakan filter pencarian proposal berdasarkan kategori dan status.

#### FR-06: Modul Penampil Dokumen PDF (PDF Viewer)
1. Saat detail proposal dibuka, sistem dapat menampilkan isi dokumen PDF proposal secara langsung di halaman web tanpa harus mengunduh file secara manual.
2. Penampil dokumen dilengkapi navigasi halaman sederhana dan opsi perbesaran (*zoom*).

#### FR-07: Modul Keputusan & Disposisi Pimpinan
1. Sistem menyediakan tiga tombol tindakan bagi Wakil Bupati:
   - **Disetujui**: Menyetujui usulan proposal (bisa disertai catatan arahan pimpinan).
   - **Perlu Perbaikan**: Mengembalikan proposal kepada pengusul (wajib mengisi catatan poin perbaikan).
   - **Tidak Disetujui**: Menolak usulan proposal disertai alasan penolakan.
2. Begitu keputusan disimpan, sistem memperbarui status proposal dan mengunci berkas agar tidak dapat diubah sembarangan.

#### FR-08: Modul Perbaikan (Revisi) Proposal
1. Jika proposal berstatus *Perlu Perbaikan*, pengusul dapat membuka kembali formulir usulannya.
2. Halaman pengusul menampilkan kotak catatan revisi resmi yang ditulis oleh Wakil Bupati.
3. Pengusul dapat memperbaiki data formulir atau mengganti file PDF proposal yang telah diperbaiki.
4. Pengusul wajib mengisi kolom keterangan revisi sebelum mengirimkan kembali usulan perbaikan.

#### FR-09: Modul Pengelolaan Versi Proposal (Versioning)
1. Pengajuan pertama kali dicatat sebagai **Versi 1.0 (v1)**.
2. Setiap kali pengusul mengirimkan kembali berkas hasil revisi, sistem mencatatnya sebagai **Versi 2.0 (v2)**, dan seterusnya.
3. Sistem menyimpan arsip berkas versi sebelumnya agar riwayat perubahan dapat ditinjau kembali jika diperlukan.

#### FR-10: Modul Notifikasi Pengguna
1. Sistem menyediakan notifikasi di dalam aplikasi (tanda lonceng/pesan status) kepada pengusul saat status proposal berubah.
2. Sistem mendukung pengiriman notifikasi ringkas melalui pesan WhatsApp atau Email saat proposal dinyatakan disetujui, perlu perbaikan, atau ditolak.

#### FR-11: Modul Cetak Lembar Disposisi & Rekapitulasi
1. Untuk proposal yang telah berstatus *Disetujui*, sistem dapat mencetak Lembar Disposisi resmi berformat PDF.
2. Sistem menyediakan fitur cetak rekapitulasi data daftar proposal untuk keperluan pengarsipan staf administrasi.

#### FR-12: Modul Catatan Riwayat Aktivitas (Log Aktivitas)
1. Sistem mencatat riwayat kejadian penting: waktu pengajuan proposal, waktu penelaahan oleh pimpinan, dan waktu pengiriman revisi.

### 4.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)

1. **Kemudahan Penggunaan (Usability)**:
   - Tampilan antarmuka sistem dibuat sederhana, bersih, dan menggunakan bahasa Indonesia yang mudah dipahami oleh pengguna umum.
   - Halaman pimpinan dirancang responsif dan nyaman diakses melalui perangkat tablet atau smartphone.
2. **Keamanan Dasar (Security)**:
   - Kata sandi akun pengguna disimpan dalam format terenkripsi (*hashing* aman menggunakan fitur bawaan framework).
   - Sistem dilengkapi mekanisme proteksi login dan validasi formulir untuk mencegah masukan data kosong atau tidak valid.
   - Pengusul hanya dapat melihat dan mengedit proposal miliknya sendiri.
3. **Kinerja yang Wajar (Performance)**:
   - Waktu buka halaman sistem cepat dan stabil pada koneksi internet standar perkantoran.
   - Penampilan berkas PDF proposal dapat termuat dengan lancar di peramban web.
4. **Ketersediaan (Availability)**:
   - Sistem dapat diakses selama jam operasional kerja untuk melayani kebutuhan pengajuan masyarakat dan pemeriksaan pimpinan.

---

## 5. PERANCANGAN BASIS DATA (DATABASE DESIGN)

### 5.1 Daftar Tabel Basis Data
Basis data SIPPRO MURA menggunakan MySQL dengan 8 tabel utama yang saling berelasi:

| No | Nama Tabel | Fungsi & Keterangan |
| :---: | :--- | :--- |
| 1 | `users` | Menyimpan data akun pengguna (Pengusul, Wakil Bupati, dan Staf Administrasi). |
| 2 | `profiles` | Menyimpan data identitas lengkap dan kontak lembaga/organisasi pemohon. |
| 3 | `categories` | Master data klasifikasi jenis kegiatan proposal (Sosial, Keagamaan, Kepemudaan, dll.). |
| 4 | `proposals` | Tabel utama pencatatan proposal (nomor registrasi, judul, status terkini). |
| 5 | `proposal_versions` | Menyimpan rincian data dan berkas proposal untuk setiap versi pengajuan (v1, v2). |
| 6 | `proposal_attachments`| Menyimpan data berkas lampiran pendukung (KTP, Surat Pengantar, Rekening). |
| 7 | `review_decisions` | Menyimpan riwayat keputusan, catatan revisi, dan disposisi dari Wakil Bupati. |
| 8 | `activity_logs` | Menyimpan catatan riwayat waktu aktivitas penting pada sistem. |

### 5.2 Struktur Kolom dan Tipe Data

#### Tabel 1: `users`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | Nomor identitas unik pengguna |
| `nama` | VARCHAR(100) | Nama lengkap pengguna |
| `email` | VARCHAR(100) (Unique) | Alamat email untuk login |
| `password` | VARCHAR(255) | Kata sandi terenkripsi |
| `role` | ENUM('pengusul', 'wabup', 'admin') | Peran hak akses akun |
| `no_telepon` | VARCHAR(20) | Nomor WhatsApp/telepon |
| `created_at` | TIMESTAMP | Waktu pembuatan akun |

#### Tabel 2: `profiles`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Profil |
| `user_id` | INT (Foreign Key -> `users.id`) | Relasi ke tabel pengguna |
| `jenis_pemohon` | ENUM('organisasi', 'perorangan') | Tipe pengusul |
| `nama_lembaga` | VARCHAR(150) | Nama organisasi / kelompok pemohon |
| `nomor_identitas` | VARCHAR(50) | Nomor KTP (NIK) atau Nomor SK Organisasi |
| `alamat` | TEXT | Alamat lengkap domisili pemohon |
| `nama_bank` | VARCHAR(50) | Nama bank rekening resmi |
| `nomor_rekening`| VARCHAR(50) | Nomor rekening penerima |
| `nama_pemilik_rekening` | VARCHAR(100) | Nama pemilik pada buku tabungan |

#### Tabel 3: `categories`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Kategori |
| `nama_kategori` | VARCHAR(100) | Nama kategori usulan (misal: Keagamaan, Kepemudaan) |
| `keterangan` | TEXT | Penjelasan kategori |

#### Tabel 4: `proposals`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Proposal |
| `nomor_registrasi` | VARCHAR(50) (Unique) | Contoh format: `PROP/MURA/2026/001` |
| `user_id` | INT (Foreign Key -> `users.id`) | Pengusul pemilik proposal |
| `category_id` | INT (Foreign Key -> `categories.id`)| Kategori kegiatan |
| `judul_proposal` | VARCHAR(255) | Judul permohonan |
| `total_anggaran` | DECIMAL(15,2) | Total nilai biaya yang diajukan |
| `status` | ENUM('draft', 'diajukan', 'perlu_perbaikan', 'disetujui', 'ditolak') | Status terkini proposal |
| `versi_aktif` | INT | Nomor versi pengajuan yang sedang aktif (1, 2, dst.) |
| `tanggal_kirim` | TIMESTAMP | Tanggal proposal resmi dikirimkan |

#### Tabel 5: `proposal_versions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Versi Proposal |
| `proposal_id` | INT (Foreign Key -> `proposals.id`) | Relasi ke proposal utama |
| `nomor_versi` | INT | Urutan versi (1, 2, dst.) |
| `latar_belakang` | TEXT | Uraian latar belakang kegiatan |
| `tujuan` | TEXT | Maksud dan tujuan kegiatan |
| `lokasi_kegiatan`| VARCHAR(200) | Tempat pelaksanaan acara |
| `tanggal_kegiatan`| DATE | Estimasi tanggal pelaksanaan |
| `rincian_rab` | TEXT / JSON | Rincian tabel baris anggaran biaya |
| `file_proposal` | VARCHAR(255) | Lokasi path file PDF proposal |
| `catatan_revisi_pemohon` | TEXT | Keterangan perbaikan saat resubmit |
| `created_at` | TIMESTAMP | Waktu versi ini dibuat |

#### Tabel 6: `proposal_attachments`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Lampiran |
| `version_id` | INT (Foreign Key -> `proposal_versions.id`) | Relasi ke versi dokumen terkait |
| `jenis_lampiran` | VARCHAR(50) | Jenis file (KTP, Surat Pengantar, SK, Rekening) |
| `nama_file` | VARCHAR(255) | Nama file dokumen yang disimpan |
| `file_path` | VARCHAR(255) | Lokasi penyimpanan di server |

#### Tabel 7: `review_decisions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Keputusan |
| `proposal_id` | INT (Foreign Key -> `proposals.id`) | Relasi ke proposal yang dinilai |
| `version_id` | INT (Foreign Key -> `proposal_versions.id`) | Versi dokumen yang dinilai |
| `reviewer_id` | INT (Foreign Key -> `users.id`) | ID akun Wakil Bupati |
| `keputusan` | ENUM('disetujui', 'perlu_perbaikan', 'ditolak') | Keputusan resmi pimpinan |
| `catatan_pimpinan` | TEXT | Catatan arahan atau poin evaluasi perbaikan |
| `tanggal_keputusan`| TIMESTAMP | Waktu penentuan keputusan |

#### Tabel 8: `activity_logs`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Primary Key, Auto Increment) | ID Log |
| `user_id` | INT (Foreign Key -> `users.id`) | Pengguna yang melakukan aktivitas |
| `aktivitas` | VARCHAR(200) | Keterangan tindakan (misal: "Kirim Proposal v1") |
| `created_at` | TIMESTAMP | Waktu peristiwa |

### 5.3 Hubungan Antar Tabel (Relasi Data)
- Satu akun pengguna (`users`) dengan peran pengusul memiliki satu data profil (`profiles`) dan dapat memiliki banyak permohonan proposal (`proposals`).
- Setiap satu proposal (`proposals`) dapat memiliki satu atau lebih versi perbaikan (`proposal_versions`).
- Setiap versi dokumen proposal (`proposal_versions`) dapat memiliki beberapa file lampiran pendukung (`proposal_attachments`).
- Setiap evaluasi yang dilakukan oleh pimpinan dicatat dalam tabel keputusan (`review_decisions`) yang terhubung langsung ke proposal dan nomor versi yang dinilai.

---

## 6. ALUR KERJA SISTEM (WORKFLOW)

### 6.1 Alur Pengajuan Proposal Baru
1. **Pendaftaran & Login**: Pengusul membuat akun dan masuk ke sistem menggunakan email dan kata sandi.
2. **Pengisian Usulan**: Pengusul memilih menu usulan baru, memilih kategori kegiatan, memasukkan judul, lokasi, tanggal, serta mengisi tabel rincian anggaran (RAB).
3. **Unggah Berkas**: Pengusul mengunggah file PDF proposal utama beserta lampiran wajib (KTP penanggung jawab, legalitas organisasi, dan buku rekening).
4. **Pemeriksaan Ketelitian Mandiri**: Saat pengusul menekan tombol kirim, sistem memunculkan jendela verifikasi mandiri. Pengusul wajib mencentang konfirmasi bahwa data yang diisi sudah teliti dan berkas lampiran sudah lengkap.
5. **Proposal Terkirim**: Sistem menyimpan data sebagai **Versi 1.0**, menerbitkan nomor registrasi proposal, dan mengubah status menjadi **`Diajukan`**. Proposal kini masuk ke antrean penelaahan pimpinan.

### 6.2 Alur Penelaahan & Keputusan oleh Wakil Bupati
1. **Akses Dasbor Pimpinan**: Wakil Bupati membuka halaman sistem dari tablet atau ponsel pintar.
2. **Melihat Antrean**: Pimpinan melihat daftar proposal yang baru masuk dengan status *Diajukan*.
3. **Membaca Dokumen**: Pimpinan memilih proposal untuk membaca rincian latar belakang, nominal anggaran, dan berkas PDF langsung di layar tanpa harus mengunduh file.
4. **Penetapan Keputusan**: Pimpinan memilih salah satu opsi keputusan:
   - Jika **Disetujui**: Status berubah menjadi *Disetujui* dan sistem dapat menerbitkan lembar disposisi.
   - Jika **Perlu Perbaikan**: Pimpinan wajib mengisi catatan perbaikan (misalnya: *"Rincian sewa peralatan belum jelas, tolong perbaiki"*). Status berubah menjadi *Perlu Perbaikan*.
   - Jika **Tidak Disetujui**: Pimpinan mengisikan alasan penolakan. Status berubah menjadi *Ditolak*.
5. **Notifikasi**: Sistem mengirimkan pemberitahuan status kepada pengusul.

### 6.3 Alur Perbaikan (Revisi) Proposal
1. Pengusul menerima informasi bahwa proposalnya memerlukan perbaikan dan membuka kembali akunnya di SIPPRO MURA.
2. Pengusul membaca catatan arahan resmi dari Wakil Bupati yang tampil di halaman proposal.
3. Pengusul memperbarui data usulan atau mengunggah file PDF proposal baru yang telah disesuaikan.
4. Pengusul menuliskan ringkasan keterangan perbaikan yang telah dilakukan.
5. Pengusul kembali melalui konfirmasi ketelitian mandiri dan menekan tombol ajukan ulang (*resubmit*).
6. Sistem menyimpan perbaikan sebagai **Versi 2.0 (v2.0)** tanpa menghapus arsip pengajuan versi 1.0. Status proposal kembali menjadi **`Diajukan`** untuk ditinjau ulang oleh pimpinan.

### 6.4 Status Siklus Hidup Proposal
Status perkembangan proposal di dalam sistem terbagi menjadi lima tahap:
1. **DRAFT**: Usulan proposal sedang disusun dan disimpan sementara oleh pengusul, belum dikirimkan secara resmi.
2. **DIAJUKAN**: Proposal telah lolos konfirmasi ketelitian dan telah masuk ke antrean penelaahan Wakil Bupati.
3. **PERLU PERBAIKAN**: Proposal dikembalikan oleh Wakil Bupati kepada pengusul karena berkas/data membutuhkan revisi sesuai catatan pimpinan.
4. **DISETUJUI**: Proposal telah ditelaah dan disetujui oleh Wakil Bupati.
5. **DITOLAK**: Proposal telah ditelaah dan dinyatakan tidak disetujui oleh Wakil Bupati.

---

## 7. RENCANA TAHAPAN PENGEMBANGAN SISTEM

Pengembangan sistem SIPPRO MURA direncanakan berlangsung mulai tanggal **21 September 2026** dan ditargetkan telah melalui tahap **Serah Terima Produk** pada tanggal **19 November 2026** (tepat 2 minggu sebelum penarikan magang pada tanggal **3 Desember 2026**). Sisa 2 minggu terakhir dialokasikan untuk pendampingan operasional dan penyusunan Laporan Akhir Magang.

Rincian jadwal tahapan kerja disusun sebagai berikut:

| Periode Waktu | Tahapan Kegiatan | Rincian Aktivitas & Hasil yang Diharapkan (Output) |
| :--- | :--- | :--- |
| **Pekan 1**<br>(21 – 27 Sep 2026) | **Inisialisasi & Fondasi Sistem** | • Persiapan lingkungan kerja framework Laravel dan basis data MySQL.<br>• Pembuatan struktur tabel database dan relasi antar tabel.<br>• Pembuatan template dasar antarmuka web yang responsif. |
| **Pekan 2 – 3**<br>(28 Sep – 11 Okt 2026) | **Pengembangan Portal Pengusul** | • Pembuatan modul registrasi akun, login, dan profil pemohon.<br>• Pembuatan formulir usulan proposal dan tabel perhitungan dinamis RAB.<br>• Pembuatan fitur unggah berkas proposal PDF dan lampiran pendukung. |
| **Pekan 4 – 5**<br>(12 – 25 Okt 2026) | **Checklist Ketelitian & Dasbor Pimpinan** | • Pembuatan modul modal Checklist Ketelitian Mandiri (Anti-Revisi).<br>• Pembuatan dasbor penelaahan Wakil Bupati yang nyaman diakses di tablet/ponsel.<br>• Integrasi penampil dokumen PDF langsung di peramban web (*PDF Viewer*). |
| **Pekan 6 – 7**<br>(26 Okt – 8 Nov 2026) | **Modul Keputusan, Revisi, & Disposisi** | • Pembuatan fitur eksekusi keputusan pimpinan (Setuju, Revisi, Tolak).<br>• Penerapan pencatatan versi dokumen (Versi 1.0 ke Versi 2.0).<br>• Pembuatan formulir perbaikan pengusul dan fitur cetak Lembar Disposisi PDF. |
| **Pekan 8 – 9**<br>(9 – 19 Nov 2026) | **Uji Coba Sistem & Serah Terima Produk** | • Pengujian fungsionalitas menyeluruh dan perbaikan kendala (*bug fixing*).<br>• Pengujian penerimaan pengguna (UAT) bersama staf administrasi.<br>• **Serah Terima Produk SIPPRO MURA resmi pada 19 November 2026** (2 pekan sebelum 3 Desember). |
| **Pekan 10 – 11**<br>(20 Nov – 3 Des 2026) | **Pendampingan & Laporan Akhir Magang** | • Pendampingan operasional penggunaan sistem bagi staf administrasi.<br>• Penyusunan buku petunjuk penggunaan sistem (*User Manual*).<br>• Penyusunan dan penyempurnaan Laporan Akhir Magang D3 Teknik Informatika hingga penarikan magang pada 3 Desember 2026. |

---

## 8. PENGUJIAN DAN KRITERIA PENERIMAAN SISTEM

Pengujian sistem dilakukan untuk memastikan bahwa fungsi-fungsi utama berjalan sesuai dengan kebutuhan yang telah dirancang:

| No | Modul yang Diuji | Skenario Pengujian | Hasil yang Diharapkan |
| :---: | :--- | :--- | :--- |
| 1 | Pendaftaran & Login | Pengusul mendaftarkan akun dan mencoba login dengan kredensial terdaftar. | Akun berhasil dibuat dan pengguna dapat masuk ke dasbor pengusul. |
| 2 | Input Form & RAB | Pengusul memasukkan baris rincian anggaran pada tabel RAB. | Sistem menjumlahkan total nominal pengajuan secara otomatis dan tepat. |
| 3 | Unggah Berkas | Pengusul mengunggah file proposal PDF dan lampiran pendukung. | Berkas berhasil tersimpan di server dan dapat dibuka kembali dengan jelas. |
| 4 | Checklist Ketelitian | Pengusul mencoba menekan tombol kirim sebelum mencentang semua butir verifikasi. | Tombol kirim tetap tidak aktif (*disabled*) hingga seluruh butir tercentang lengkap. |
| 5 | Pengiriman Proposal | Pengusul menyelesaikan checklist dan menekan tombol kirim. | Proposal berhasil terkirim, berstatus *Diajukan*, dan memiliki nomor registrasi resmi. |
| 6 | Dasbor Pimpinan | Wakil Bupati membuka daftar proposal masuk melalui perangkat komputer atau tablet. | Daftar proposal tampil rapi dengan informasi nama pengusul, judul, dan nominal biaya. |
| 7 | Pembaca PDF Web | Wakil Bupati membuka detail dokumen proposal. | File PDF langsung tertampil di layar halaman web tanpa perlu mengunduh file secara manual. |
| 8 | Tindakan Persetujuan | Wakil Bupati memilih tombol *Disetujui*. | Status proposal berubah menjadi *Disetujui* dan lembar disposisi siap dicetak. |
| 9 | Tindakan Revisi | Wakil Bupati memilih tombol *Perlu Perbaikan* dan menuliskan catatan arahan. | Status berubah menjadi *Perlu Perbaikan*, catatan pimpinan tersimpan, dan pengusul dapat mengedit usulan. |
| 10 | Pengajuan Revisi (v2) | Pengusul mengunggah berkas perbaikan dan mengirimkan kembali proposal. | Sistem mencatat berkas sebagai Versi 2.0 (v2.0) tanpa menghapus berkas pengajuan versi awal. |

---

> Dokumen Spesifikasi Kebutuhan Sistem (PRD) ini disusun secara mandiri dan realistis sebagai acuan pelaksanaan kegiatan magang Program Studi D3 Teknik Informatika, Jurusan Teknik Elektro, Politeknik Negeri Banjarmasin, bekerja sama dengan Bagian Pelayanan Administrasi Pimpinan Sekretariat Daerah Kabupaten Murung Raya.
