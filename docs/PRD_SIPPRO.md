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
| **Versi Dokumen** | 1.1 (Edisi Sinkronisasi Implementasi Aktual Sistem) |
| **Status Dokumen** | Telah Disinkronkan dengan Implementasi Sistem Final |
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
   6.5 Alur Pelacakan Mandiri Publik (Public Tracking)
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
4. Menyediakan layanan pelacakan mandiri publik (*Public Tracking*) berbasis nomor registrasi resmi tanpa mewajibkan masyarakat untuk login.
5. Membantu staf Bagian Pelayanan Administrasi Pimpinan dalam mengelola arsip proposal masuk dan mencatat riwayat aktivitas secara transparan.

### 1.3 Batasan Masalah (Ruang Lingkup)
Agar ruang lingkup tugas magang ini terarah dan realistis untuk diselesaikan sesuai jenjang D3 Teknik Informatika, batasan sistem ditentukan sebagai berikut:
1. Sistem mencakup pendaftaran akun pengusul, pengisian formulir usulan dan anggaran, unggah berkas PDF, pemeriksaan mandiri, serta peninjauan oleh Wakil Bupati.
2. Keputusan yang dapat diberikan oleh Wakil Bupati berupa tiga opsi resmi: **Disetujui**, **Perlu Perbaikan**, atau **Tidak Disetujui**.
3. Sistem mencatat riwayat keputusan dan arahan pimpinan secara digital pada setiap versi dokumen.
4. **Tidak mencakup proses pencairan anggaran keuangan daerah**. Proses pencairan uang tetap mengikuti prosedur perbendaharaan daerah yang berlaku melalui instansi/bagian keuangan terkait.
5. Format proposal utama yang diunggah difokuskan pada format dokumen **PDF**.

### 1.4 Definisi Istilah
- **SIPPRO MURA**: Sistem Informasi Pelayanan Proposal Murung Raya.
- **Pengusul**: Perorangan atau perwakilan organisasi masyarakat yang mengajukan proposal permohonan ke pimpinan daerah.
- **RAB**: Rencana Anggaran Biaya, yaitu perincian item estimasi biaya yang diajukan dalam proposal.
- **Checklist Ketelitian Mandiri**: Daftar periksa wajib yang harus dikonfirmasi oleh pengusul sebelum mengirimkan proposal agar berkas yang dikirim benar-benar lengkap dan siap dinilai.
- **Riwayat Versi (Versioning)**: Pencatatan nomor perbaikan dokumen (misalnya Versi 1.0 dan Versi 2.0) agar berkas lama tetap tersimpan dan tidak tertimpa saat ada perbaikan.
- **Disposisi / Telaah**: Lembar keputusan/arahan tertulis dari Wakil Bupati terhadap usulan proposal yang masuk.
- **Public Tracking**: Fitur pelacakan status proposal terbuka berbasis nomor registrasi resmi tanpa login.

---

## 2. GAMBARAN UMUM SISTEM

### 2.1 Deskripsi Produk SIPPRO MURA
SIPPRO MURA adalah sistem informasi berbasis web yang dirancang untuk mengelola alur persuratan proposal masuk pada Bagian Pelayanan Administrasi Pimpinan Sekretariat Daerah Kabupaten Murung Raya. Sistem ini menghubungkan pengusul secara daring dengan meja kerja digital Wakil Bupati, sehingga proses verifikasi dan disposisi proposal dapat berlangsung lebih cepat dan tertib.

### 2.2 Fitur Unggulan Sistem
1. **Pengajuan Proposal Terstruktur Secara Online**: Pengusul dapat mengisi data kegiatan, memasukkan nominal usulan dengan format dinamis otomatis, dan mengunggah berkas kapan saja tanpa harus mengantarkan berkas fisik ke kantor.
2. **Gerbang Cek Ketelitian Mandiri (Anti-Revisi)**: Modal verifikasi mandiri yang merangkum data usulan dan status unggah dokumen. Tombol kirim hanya aktif setelah pengusul mencentang pakta ketelitian data.
3. **Penelaahan Langsung oleh Wakil Bupati**: Antarmuka ringkas khusus pimpinan untuk melihat daftar antrean proposal masuk, memeriksa profil pengusul, dan membaca berkas proposal langsung di layar peramban.
4. **Keputusan Cepat 1-Klik**: Pimpinan dapat langsung memilih keputusan: *Disetujui*, *Perlu Perbaikan* (disertai catatan arahan revisi), atau *Tidak Disetujui*.
5. **Riwayat Perubahan Tersimpan Rapi (Versioning)**: Setiap perbaikan proposal tercatat sebagai versi baru (v1.0 → v2.0) tanpa menghapus data pengajuan sebelumnya, dilengkapi duplikasi berkas otomatis dan selector arsip versi.
6. **Tabel Dinamis Perhitungan RAB**: Fasilitas penyusunan rincian anggaran interaktif pada alur revisi dengan kalkulasi subtotal dan total biaya realtime.
7. **Pelacakan Proposal Mandiri Publik (Public Tracking)**: Fasilitas transparansi bagi masyarakat umum atau pemohon untuk melacak status usulan secara instan hanya dengan memasukkan nomor registrasi proposal.
8. **Simulasi Akses Peran Cepat (Quick Role Access)**: Fasilitas demonstrasi sistem yang memudahkan penguji mencoba peran Pengusul, Staf Administrasi, maupun Wakil Bupati dalam 1-klik.

### 2.3 Perangkat Lunak & Teknologi yang Digunakan
Teknologi yang digunakan disesuaikan dengan kompetensi dasar mahasiswa D3 Teknik Informatika:

| Komponen Sistem | Teknologi yang Dipilih | Implementasi Aktual & Keterangan |
| :--- | :--- | :--- |
| **Bahasa & Framework Backend** | PHP 8.4 (Framework Laravel 12) | Framework web standar industri dengan arsitektur MVC yang rapi, Eloquent ORM, dan performa tinggi. |
| **Basis Data (Database)** | MySQL / SQLite (Testing) | Sistem manajemen basis data relasional dengan 8 tabel utama yang terelasi secara integritas referensial. |
| **Antarmuka Pengguna (Frontend)**| HTML5, Blade, Tailwind CSS, JavaScript | Menghasilkan tampilan modern bertema gelap-emas (*gold-dark theme*) yang responsif di desktop, tablet, dan smartphone. |
| **Penampil Dokumen** | Responsive Iframe PDF Viewer | Menampilkan file proposal PDF secara langsung di halaman web setinggi 620px dengan opsi unduh berkas. |
| **Penyimpanan Berkas** | Laravel Storage (*Public Disk*) | Menyimpan berkas proposal dan lampiran di direktori server terstruktur (`storage/app/public/proposals` & `attachments`). |
| **Notifikasi Pengguna** | In-App Flash Alert & Activity Logs | Menggunakan alert status interaktif dengan auto-dismiss 3 detik dan badge indikator. *(Integrasi WhatsApp API / SMTP dijadwalkan pada tahap lanjutan)*. |

---

## 3. PENGGUNA SISTEM & HAK AKSES

### 3.1 Peran Pengguna (User Roles)

#### A. Pengusul (Masyarakat / Organisasi)
Perwakilan organisasi kemasyarakatan, karang taruna, panitia kegiatan, yayasan, atau warga masyarakat yang memiliki hak akses untuk:
- Mendaftarkan akun secara mandiri dan mengelola profil pemohon (identitas perorangan/lembaga serta nomor rekening bank penyaluran).
- Membuat usulan proposal baru dan mengunggah berkas proposal PDF beserta lampiran (KTP, Legalitas/SK, Buku Rekening).
- Mengonfirmasi modal checklist ketelitian mandiri sebelum mengirimkan proposal.
- Melihat daftar dan riwayat proposal miliknya sendiri.
- Membaca catatan instruksi perbaikan dari Wakil Bupati jika proposal berstatus *Perlu Perbaikan*.
- Mengajukan perbaikan usulan (revisi) lengkap dengan penyesuaian berkas/tabel RAB dan catatan keterangan perbaikan.

#### B. Wakil Bupati (Pimpinan Daerah)
Pejabat pengambil keputusan utama atas proposal yang masuk, dengan hak akses eksklusif untuk:
- Membuka halaman dasbor eksekutif yang ringkas dan ramah perangkat bergerak (tablet/smartphone).
- Melihat seluruh antrean proposal masuk dari seluruh masyarakat Kabupaten Murung Raya.
- Membaca berkas dokumen PDF secara langsung di layar peramban melalui embedded PDF Viewer.
- Menetapkan keputusan resmi: **Disetujui**, **Perlu Perbaikan** (wajib catatan arahan), atau **Tidak Disetujui**.
- Meninjau riwayat arsip perbaikan versi proposal (v1.0 vs v2.0) secara komparatif.

#### C. Staf Pelayanan Administrasi Pimpinan (Administrator)
Staf tata usaha yang bertugas mengelola ketertiban data dan administrasi sistem, dengan hak akses untuk:
- Mengelola data master kategori proposal dan memantau akun pengguna.
- Memantau rekapitulasi proposal masuk secara menyeluruh (daftar usulan, status, dan riwayat arsip).
- Memantau log aktivitas sistem (*Activity Logs*) yang mencatat setiap peristiwa penting beserta alamat IP pengguna.
- *Catatan*: Staf Administrasi tidak memiliki wewenang untuk mengeksekusi telaah keputusan maupun mengajukan revisi atas nama pengusul.

### 3.2 Aturan Khusus Pengambilan Keputusan
> **Catatan Penting Otorisasi**:
> Hak untuk menyetujui, menolak, atau meminta perbaikan proposal secara mutlak berada pada **Wakil Bupati**. Sistem menerapkan proteksi ganda: middleware route `role:wabup` dan verifikasi peran internal pada controller. Staf Administrasi dan Pengusul secara otomatis diblokir (`403 Forbidden`) jika mencoba mengeksekusi fungsi keputusan.

### 3.3 Matriks Hak Akses Pengguna

| Fitur / Menu Sistem | Pengusul | Wakil Bupati | Staf Administrasi | Pengguna Publik |
| :--- | :---: | :---: | :---: | :---: |
| Registrasi Akun Mandiri | Ya | Tidak | Tidak | Ya |
| Login ke Sistem | Ya | Ya | Ya | Tidak |
| Quick Login Demonstration | Ya | Ya | Ya | Ya |
| Mengisi Formulir & Unggah Proposal | Ya (Milik Sendiri) | Tidak | Tidak | Tidak |
| Konfirmasi Checklist Ketelitian | Ya | Tidak | Tidak | Tidak |
| Melihat Daftar Semua Proposal | Tidak | Ya | Ya | Tidak |
| Melihat Proposal Milik Sendiri | Ya | Tidak | Tidak | Tidak |
| Membaca Dokumen PDF di Layar | Ya (Milik Sendiri) | Ya (Semua) | Ya (Semua) | Tidak |
| Menetapkan Keputusan (Setuju/Revisi/Tolak) | Tidak | **Ya (Hak Eksklusif)** | Tidak | Tidak |
| Mengakses Formulir Revisi (Status Perlu Perbaikan) | Ya (Milik Sendiri) | Tidak | Tidak | Tidak |
| Membuka Riwayat Versi Proposal | Ya (Milik Sendiri) | Ya | Ya | Tidak |
| Pelacakan Status Publik (Public Tracking) | Ya | Ya | Ya | **Ya (Tanpa Login)** |
| Mengelola Profil & Rekening Penyaluran | Ya (Milik Sendiri) | Tidak | Tidak | Tidak |
| Memantau Catatan Riwayat Aktivitas | Tidak | Tidak | Ya | Tidak |

---

## 4. KEBUTUHAN SISTEM

### 4.1 Kebutuhan Fungsional (Functional Requirements)

#### FR-01: Modul Autentikasi, Akun, & Simulasi Peran
1. Sistem menyediakan formulir registrasi minimalis bagi pengusul: Nama Lengkap, Alamat Email, Nomor WhatsApp/Telepon, Kata Sandi, dan Konfirmasi Kata Sandi.
2. Sistem menyediakan fitur login menggunakan email dan password terenkripsi bcrypt.
3. Sistem menyediakan hak akses berbasis peran (*Role-Based Access Control* - RBAC) dengan pemisahan peran: `pengusul`, `wabup`, dan `admin`.
4. Sistem menyediakan tombol demonstrasi peran cepat (*Quick Login*) untuk mempermudah pengujian dan evaluasi fungsionalitas antar peran dalam satu klik.

#### FR-02: Modul Formulir Pengajuan Proposal
1. Sistem menyajikan formulir pengajuan proposal yang ringkas dan terstruktur:
   - **Bagian 1 (Informasi Proposal)**: Nomor Surat/Proposal Pengusul, Perihal/Judul Usulan, Jumlah Dana yang Diajukan, dan Lokasi Kegiatan.
   - **Bagian 2 (Dokumen Utama & Lampiran)**: Unggah dokumen berkas proposal utama (PDF) dan berkas pendukung (KTP, SK Organisasi, Buku Rekening).
2. Sistem menerapkan pemformatan dinamis otomatis pada kolom jumlah dana (menampilkan pemisah titik ribuan rupiah secara realtime saat diketik).
3. Batas minimal nominal dana yang diajukan divalidasi sebesar minimal Rp 100.000.

#### FR-03: Modul Unggah Berkas & Lampiran Pendukung
1. Sistem menyediakan slot unggah berkas proposal utama wajib berformat **PDF** dengan batas ukuran maksimal **10 MB**.
2. Sistem menyediakan slot unggah berkas pendukung dengan format gambar/dokumen yang sah (PDF, JPG, JPEG, PNG):
   - KTP Penanggung Jawab (maksimal 5 MB).
   - Legalitas / SK Lembaga / Surat Keterangan (maksimal 10 MB).
   - Buku Rekening Bank Penyaluran (maksimal 5 MB).
3. Berkas tersimpan secara terisolasi pada direktori server lokal aplikasi (`storage/app/public`).

#### FR-04: Modul Checklist Ketelitian Mandiri (Anti-Revisi)
1. Sebelum pengajuan diproses, penekanan tombol submit memunculkan jendela dialog (*Modal Checklist Ketelitian*).
2. Modal secara otomatis merangkum perihal, nomor surat, nominal dana, lokasi, serta mendeteksi status ketersediaan masing-masing dokumen lampiran yang telah dipilih.
3. Pengusul wajib mencentang konfirmasi pakta integritas bahwa berkas yang diunggah telah benar, sah, dan dapat dipertanggungjawabkan.
4. Tombol konfirmasi final dinonaktifkan (*disabled*) hingga checkbox pakta dicentang oleh pengusul.

#### FR-05: Modul Penelaahan & Dasbor Pengguna
1. **Dasbor Pengusul**: Menyajikan ringkasan proposal yang diajukan, status telaah terkini, filter pencarian, dan tautan pengajuan baru.
2. **Dasbor Wakil Bupati**: Menyajikan metrik total proposal masuk, proposal menunggu telaah, disetujui, dan perlu perbaikan, disertai tabel antrean telaah yang dioptimalkan untuk perangkat layar sentuh (tablet/smartphone).
3. **Dasbor Staf Administrasi**: Menyajikan ringkasan rekapitulasi data proposal masuk dan pemantauan aktivitas persuratan.

#### FR-06: Modul Penampil Dokumen PDF (PDF Viewer)
1. Pada halaman detail proposal, sistem menyematkan penampil PDF interaktif berbasis *responsive iframe* setinggi 620px yang langsung menampilkan isi proposal tanpa harus mengunduh file.
2. Sistem menyediakan tombol pintas untuk membuka berkas pada tab peramban baru dan tombol unduh langsung bagi pimpinan maupun pengusul.

#### FR-07: Modul Keputusan & Disposisi Pimpinan
1. Wakil Bupati memiliki hak eksklusif untuk menetapkan keputusan melalui formulir telaah:
   - **Disetujui Penuh**: Merekomendasikan usulan untuk ditindaklanjuti.
   - **Perlu Perbaikan**: Mengembalikan berkas ke pengusul dengan kewajiban mengisi instruksi/poin arahan perbaikan.
   - **Tidak Disetujui**: Menolak usulan dengan memberikan alasan kebijakan pimpinan.
2. Keputusan pimpinan tersimpan permanen di basis data dengan penanda waktu (*timestamp*) dan nama pimpinan penelaah.

#### FR-08: Modul Perbaikan (Revisi) Proposal
1. Proposal berstatus *Perlu Perbaikan* mengaktifkan tombol khusus *"Ajukan Perbaikan Proposal"* bagi pengusul pemilik proposal.
2. Halaman revisi menampilkan kotak arahan resmi dari Wakil Bupati dalam mode baca (*read-only*).
3. Pengusul dapat memperbarui judul, lokasi, tanggal, dan mengunggah dokumen PDF revisi baru.
4. Halaman revisi dilengkapi **Tabel Dinamis Perhitungan RAB** yang memungkinkan pengusul menyesuaikan volume, satuan, dan rincian biaya per item barang secara otomatis.
5. Pengusul wajib mengisi kolom *"Catatan Perbaikan dari Pemohon"* (minimal 5 karakter) untuk menjelaskan poin yang telah disesuaikan sebelum mengirimkan revisi.

#### FR-09: Modul Pengelolaan Versi Proposal (Versioning)
1. Pengajuan pertama kali secara otomatis dicatat sebagai **Versi 1.0 (v1.0)**.
2. Setiap kali perbaikan dikirimkan melalui alur revisi, sistem menerbitkan **Versi 2.0 (v2.0)** tanpa mengubah nomor registrasi proposal dan tanpa menimpa data versi sebelumnya.
3. Lampiran pendukung dari versi terdahulu diduplikasi otomatis ke versi baru untuk menjaga keutuhan dokumen.
4. Pada halaman detail proposal, disediakan selector riwayat versi interaktif (`?version=1` vs `?version=2`) sehingga pimpinan dan pengusul dapat meninjau arsip riwayat dokumen lama.
5. Jika pimpinan membuka versi arsip lama, form disposisi otomatis dinonaktifkan dengan peringatan agar disposisi hanya diberikan pada versi aktif terbaru.

#### FR-10: Modul Notifikasi Pengguna
1. Sistem menyediakan notifikasi berbasis aplikasi (*In-App Alert Notification*) dengan pesan umpan balik sukses/error yang dilengkapi penghitung waktu tutup otomatis (*auto-dismiss* 3 detik).
2. Setiap status usulan ditampilkan dengan lencana warna terstandarisasi (*badge color status*).
3. *(Rencana Lanjutan)*: Integrasi pengiriman pesan otomatis melalui WhatsApp Gateway API dan Email SMTP dijadwalkan pada tahap integrasi eksternal.

#### FR-11: Modul Lembar Disposisi & Rekapitulasi Data
1. Riwayat keputusan dan arahan pimpinan tercatat secara digital dan terikat pada nomor versi dokumen yang dinilai.
2. *(Rencana Lanjutan)*: Modul generator pencetakan dokumen fisik Lembar Disposisi berformat PDF (*PDF export generator*) dijadwalkan untuk diselesaikan pada tahap pengemasan akhir sistem.

#### FR-12: Modul Catatan Riwayat Aktivitas (Activity Logs)
1. Sistem mencatat log peristiwa penting secara transparan: pendaftaran akun, pengajuan proposal baru, penetapan telaah pimpinan, dan pengiriman revisi.
2. Setiap entri log mencatat ID pengguna, jenis aktivitas, rincian keterangan tindakan, alamat IP (*IP Address*), dan waktu kejadian.

#### FR-13: Modul Pelacakan Proposal Publik Mandiri (Public Tracking)
1. Sistem menyediakan halaman pelacakan publik terbuka (`/lacak`) yang dapat diakses oleh masyarakat tanpa harus login ke dalam sistem.
2. Pengguna cukup memasukkan nomor registrasi proposal resmi (format `PROP-YYYYMM-XXXX`) untuk memantau perkembangan usulan.
3. Sistem menyajikan informasi nomor registrasi, nama pemohon/lembaga, perihal usulan, tanggal masuk, lencana status saat ini, dan catatan disposisi resmi pimpinan.

### 4.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)

1. **Kemudahan Penggunaan & Estetika Antarmuka (Usability)**:
   - Menggunakan tema visual modern berbasis dark theme dengan aksen keemasan (*gold accent*) yang elegan dan representatif bagi instansi kedinasan Sekretariat Daerah.
   - Menerapkan arsitektur navigasi bersih: sidebar vertikal terpadu untuk area internal aplikasi dan navigasi publik minimalis tanpa duplikasi tombol yang membingungkan.
   - Formulir dilengkapi validasi interaktif dan pemformatan nominal otomatis.
2. **Keamanan & Otorisasi Akses (Security)**:
   - Pengamanan kata sandi menggunakan enkripsi bcrypt bawaan framework.
   - Proteksi rute menggunakan middleware autentikasi dan otorisasi peran (*Role Middleware*).
   - Pengusul hanya dapat melihat, mengedit, dan merevisi proposal miliknya sendiri. Akses ke data proposal pengguna lain diblokir dengan kode keamanan `403 Forbidden`.
3. **Kinerja & Responsivitas (Performance & Responsiveness)**:
   - Desain tata letak sepenuhnya responsif menggunakan utility classes Tailwind CSS yang adaptif pada resolusi desktop, tablet (iPad/Android tablet), dan smartphone.
   - Waktu respons pengujian sistem lokal berada di bawah 3 detik untuk seluruh 34 feature and unit tests.
4. **Integritas Data (Data Integrity)**:
   - Penggunaan transaksi basis data (`DB::transaction`) pada alur pengajuan proposal, telaah pimpinan, dan resubmit revisi untuk mencegah inkonsistensi data jika terjadi gangguan jaringan.

---

## 5. PERANCANGAN BASIS DATA (DATABASE DESIGN)

### 5.1 Daftar Tabel Basis Data
Basis data SIPPRO MURA terdiri atas 8 tabel utama yang saling berelasi:

| No | Nama Tabel | Fungsi & Keterangan |
| :---: | :--- | :--- |
| 1 | `users` | Menyimpan data akun pengguna dan peran hak akses (`pengusul`, `wabup`, `admin`). |
| 2 | `profiles` | Menyimpan profil pemohon, jenis pengusul, alamat, dan nomor rekening penyaluran bank. |
| 3 | `categories` | Master klasifikasi bidang usulan proposal (Sosial, Keagamaan, Kepemudaan, dll.). |
| 4 | `proposals` | Tabel entitas induk proposal (nomor registrasi unik, total dana, status, versi aktif). |
| 5 | `proposal_versions` | Menyimpan data detail usulan, dokumen proposal PDF, dan catatan revisi per nomor versi. |
| 6 | `proposal_attachments`| Menyimpan berkas lampiran pendukung fisik (KTP, SK Lembaga, Buku Rekening). |
| 7 | `review_decisions` | Menyimpan keputusan resmi, catatan instruksi pimpinan, dan identitas penelaah. |
| 8 | `activity_logs` | Menyimpan catatan audit trail aktivitas pengguna beserta keterangan dan IP address. |

### 5.2 Struktur Kolom dan Tipe Data

#### Tabel 1: `users`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | Nomor identitas unik pengguna |
| `name` | VARCHAR(255) | Nama lengkap pengguna / perwakilan |
| `email` | VARCHAR(255) (Unique) | Alamat email untuk masuk sistem |
| `email_verified_at` | TIMESTAMP (Nullable) | Waktu verifikasi email |
| `password` | VARCHAR(255) | Kata sandi terenkripsi (bcrypt) |
| `role` | ENUM('pengusul', 'wabup', 'admin') | Hak akses peran pengguna |
| `no_telepon` | VARCHAR(255) (Nullable) | Nomor telepon seluler / WhatsApp |
| `remember_token` | VARCHAR(100) (Nullable) | Token sesi login tersimpan |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pembuatan dan pembaruan akun |

#### Tabel 2: `profiles`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | Nomor ID profil |
| `user_id` | BIGINT (Foreign Key -> `users.id`) | Pengguna pemilik profil |
| `jenis_pemohon` | ENUM('organisasi', 'perorangan') | Tipe subjek pengusul |
| `nama_lembaga` | VARCHAR(255) (Nullable) | Nama organisasi / yayasan (jika ada) |
| `nomor_identitas` | VARCHAR(255) (Nullable) | NIK KTP atau Nomor SK Izin Organisasi |
| `alamat` | TEXT (Nullable) | Alamat lengkap domisili pemohon |
| `nama_bank` | VARCHAR(255) (Nullable) | Nama bank penyaluran (misal: Bank Kalteng) |
| `nomor_rekening`| VARCHAR(255) (Nullable) | Nomor rekening penyaluran bantuan |
| `nama_pemilik_rekening` | VARCHAR(255) (Nullable) | Nama pemilik pada buku tabungan |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pencatatan data profil |

#### Tabel 3: `categories`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Kategori |
| `nama_kategori` | VARCHAR(255) | Nama klasifikasi bidang usulan proposal |
| `keterangan` | TEXT (Nullable) | Penjelasan ruang lingkup bantuan |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pencatatan master kategori |

#### Tabel 4: `proposals`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Proposal |
| `nomor_registrasi` | VARCHAR(255) (Unique) | Nomor registrasi resmi (Format: `PROP-YYYYMM-XXXX`) |
| `user_id` | BIGINT (Foreign Key -> `users.id`) | Akun pengusul pemilik proposal |
| `category_id` | BIGINT (Foreign Key -> `categories.id`)| Klasifikasi kategori usulan |
| `judul_proposal` | VARCHAR(255) | Perihal / judul permohonan |
| `total_anggaran` | DECIMAL(15,2) | Total nilai dana yang diajukan |
| `status` | ENUM('draft', 'diajukan', 'perlu_perbaikan', 'disetujui', 'ditolak') | Status perkembangan usulan |
| `versi_aktif` | INT (Unsigned) | Nomor versi yang sedang aktif (default: 1) |
| `tanggal_kirim` | TIMESTAMP (Nullable) | Waktu proposal dikirimkan ke pimpinan |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pembuatan proposal |

#### Tabel 5: `proposal_versions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Versi Dokumen |
| `proposal_id` | BIGINT (Foreign Key -> `proposals.id`) | Relasi ke proposal induk |
| `nomor_versi` | INT (Unsigned) | Urutan nomor versi (1, 2, dst.) |
| `latar_belakang` | LONGTEXT (Nullable) | Uraian latar belakang & nomor proposal pengusul |
| `tujuan` | LONGTEXT (Nullable) | Maksud dan tujuan permohonan |
| `lokasi_kegiatan`| VARCHAR(255) (Nullable) | Tempat / desa lokasi kegiatan |
| `tanggal_kegiatan`| DATE (Nullable) | Estimasi tanggal kegiatan |
| `rincian_rab` | LONGTEXT (Nullable) | Rincian tabel baris item RAB format JSON/Teks |
| `file_proposal` | VARCHAR(255) (Nullable) | Lokasi path file dokumen proposal PDF |
| `catatan_revisi_pemohon` | TEXT (Nullable) | Keterangan poin yang telah diperbaiki pengusul |
| `created_at` / `updated_at` | TIMESTAMP | Waktu penerbitan versi dokumen |

#### Tabel 6: `proposal_attachments`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Berkas Lampiran |
| `version_id` | BIGINT (Foreign Key -> `proposal_versions.id`) | Relasi ke versi dokumen terkait |
| `jenis_lampiran` | VARCHAR(255) | Tipe berkas (`ktp`, `akta`, `rekening`, dll.) |
| `nama_file` | VARCHAR(255) | Nama asli berkas dokumen |
| `file_path` | VARCHAR(255) | Lokasi penyimpanan berkas di server |
| `ukuran_file` | BIGINT (Unsigned) | Besaran ukuran file dalam satuan bytes |
| `created_at` / `updated_at` | TIMESTAMP | Waktu unggah dokumen |

#### Tabel 7: `review_decisions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Catatan Keputusan |
| `proposal_id` | BIGINT (Foreign Key -> `proposals.id`) | Proposal yang dinilai |
| `version_id` | BIGINT (Foreign Key -> `proposal_versions.id`) | Versi dokumen yang dinilai |
| `reviewer_id` | BIGINT (Foreign Key -> `users.id`) | Akun Wakil Bupati penilai |
| `keputusan` | ENUM('disetujui', 'perlu_perbaikan', 'ditolak') | Keputusan resmi pimpinan |
| `catatan_pimpinan` | TEXT (Nullable) | Instruksi, poin arahan, atau evaluasi pimpinan |
| `tanggal_keputusan`| TIMESTAMP | Waktu penetapan keputusan |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pencatatan data telaah |

#### Tabel 8: `activity_logs`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Log Audit |
| `user_id` | BIGINT (Foreign Key -> `users.id`, Nullable) | Pengguna pelaku aktivitas |
| `aktivitas` | VARCHAR(255) | Judul tindakan (misal: "Mengajukan Proposal Baru") |
| `keterangan` | TEXT (Nullable) | Rincian detail keterangan peristiwa |
| `ip_address` | VARCHAR(45) (Nullable) | Alamat IP jaringan pengguna |
| `created_at` / `updated_at` | TIMESTAMP | Waktu peristiwa tercatat |

### 5.3 Hubungan Antar Tabel (Relasi Data)
- Satu pengguna (`users`) dengan peran pengusul memiliki satu profil data (`profiles`) dan dapat memiliki banyak berkas permohonan (`proposals`).
- Setiap satu proposal (`proposals`) memiliki satu kategori (`categories`) dan dapat memiliki banyak riwayat versi (`proposal_versions`).
- Setiap versi dokumen proposal (`proposal_versions`) dapat memiliki beberapa file lampiran pendukung (`proposal_attachments`) dan dapat memiliki catatan keputusan penelaahan (`review_decisions`).
- Seluruh tindakan penting pengguna di dalam sistem dicatat secara terpusat pada tabel rekam jejak aktivitas (`activity_logs`).

---

## 6. ALUR KERJA SISTEM (WORKFLOW)

### 6.1 Alur Pengajuan Proposal Baru
1. **Pendaftaran & Masuk Akun**: Pengusul mendaftarkan akun dan masuk ke dalam sistem SIPPRO MURA.
2. **Pengisian Formulir Usulan**: Pengusul memilih tombol *Ajukan Usulan Baru*, memasukkan nomor surat pemohon, perihal usulan, estimasi jumlah dana (dengan format rupiah otomatis), lokasi kegiatan, dan mengunggah berkas proposal PDF beserta lampiran (KTP, SK Organisasi, Buku Rekening).
3. **Pemeriksaan Ketelitian Mandiri (Modal Anti-Revisi)**: Saat pengusul menekan tombol ajukan, sistem memunculkan jendela konfirmasi modal. Pengusul memeriksa ringkasan data dan kelengkapan dokumen yang telah terdeteksi oleh sistem, kemudian mencentang pakta ketelitian.
4. **Penerbitan Registrasi & Pengiriman**: Tombol ajukan aktif, sistem memproses transaksi penyimpanan, menerbitkan nomor registrasi resmi unik berformat `PROP-YYYYMM-XXXX`, mencatat dokumen sebagai **Versi 1.0 (v1.0)**, mengubah status menjadi **`diajukan`**, dan mencatat log aktivitas pengajuan.

### 6.2 Alur Penelaahan & Keputusan oleh Wakil Bupati
1. **Akses Dasbor Pimpinan**: Wakil Bupati membuka halaman dasbor melalui komputer, tablet, atau smartphone.
2. **Pemeriksaan Antrean**: Pimpinan melihat daftar permohonan dengan status *Diajukan*.
3. **Penelaahan Berkas**: Pimpinan memilih proposal untuk memeriksa data pemohon, rincian dana, dan membaca dokumen berkas proposal PDF langsung pada *embedded PDF Viewer*.
4. **Penetapan Keputusan 1-Klik**:
   - Jika **Disetujui Penuh**: Pimpinan memilih opsi persetujuan, menuliskan catatan disposisi, dan status proposal berubah menjadi **`disetujui`**.
   - Jika **Perlu Perbaikan**: Pimpinan memilih opsi revisi dan wajib mengisikan catatan arahan perbaikan teknis. Status berubah menjadi **`perlu_perbaikan`**.
   - Jika **Tidak Disetujui**: Pimpinan memilih opsi penolakan disertai alasan kebijakan daerah. Status berubah menjadi **`ditolak`**.

### 6.3 Alur Perbaikan (Revisi) Proposal (v1.0 → v2.0)
1. Pengusul menerima informasi bahwa proposalnya memerlukan perbaikan dan membuka detail proposalnya.
2. Pengusul membaca catatan instruksi resmi dari Wakil Bupati yang disajikan dalam kotak arahan pimpinan.
3. Pengusul menekan tombol *"Ajukan Perbaikan Proposal"* dan diarahkan ke formulir revisi khusus.
4. Pengusul memperbarui dokumen usulan, mengunggah file PDF proposal revisi, menyesuaikan item pada **Tabel Dinamis RAB**, dan wajib mengisi keterangan perbaikan pada kolom *Catatan Perbaikan dari Pemohon*.
5. Pengusul menekan tombol kirim perbaikan.
6. Sistem memproses perbaikan sebagai **Versi 2.0 (v2.0)** tanpa mengubah nomor registrasi dan tanpa menghapus arsip versi 1.0. Berkas lampiran dari versi sebelumnya diduplikasi secara otomatis.
7. Status proposal kembali menjadi **`diajukan`** dan secara otomatis masuk kembali ke antrean telaah Wakil Bupati untuk ditinjau ulang.

### 6.4 Status Siklus Hidup Proposal
Sistem mengelola lima status tahapan proposal yang terstandarisasi:
1. **DRAFT**: Usulan proposal sedang disusun dan disimpan sementara oleh pengusul, belum dikirimkan secara resmi ke pimpinan.
2. **DIAJUKAN**: Proposal telah lolos konfirmasi ketelitian mandiri dan berada dalam antrean meja kerja penelaahan Wakil Bupati.
3. **PERLU PERBAIKAN**: Proposal dikembalikan oleh Wakil Bupati kepada pengusul karena terdapat poin administrasi atau anggaran yang harus diperbaiki sesuai catatan arahan pimpinan.
4. **DISETUJUI**: Proposal telah ditelaah dan memperoleh persetujuan resmi dari Wakil Bupati.
5. **DITOLAK**: Proposal telah ditelaah dan dinyatakan tidak disetujui oleh Wakil Bupati.

### 6.5 Alur Pelacakan Mandiri Publik (Public Tracking)
1. Masyarakat atau pemohon mengakses menu pelacakan publik pada alamat web `/lacak`.
2. Pengguna memasukkan nomor registrasi resmi proposal (contoh: `PROP-202610-0001`) pada kotak pencarian tanpa perlu membuat akun atau login.
3. Sistem memverifikasi nomor registrasi pada basis data:
   - Jika nomor registrasi ditemukan: Sistem menyajikan halaman informasi status transparan berisi perihal usulan, nama pemohon/lembaga, tanggal pengajuan, versi dokumen aktif, lencana status saat ini, serta catatan resmi disposisi pimpinan.
   - Jika nomor registrasi tidak ditemukan: Sistem memberikan notifikasi ramah yang menyarankan pengguna memeriksa kembali nomor yang dimasukkan tanpa memindahkan halaman.

---

## 7. RENCANA TAHAPAN PENGEMBANGAN SISTEM

Pengembangan sistem SIPPRO MURA direncanakan berlangsung mulai tanggal **21 September 2026** dan ditargetkan telah melalui tahap **Serah Terima Produk** pada tanggal **19 November 2026** (tepat 2 minggu sebelum penarikan magang pada tanggal **3 Desember 2026**). Sisa 2 minggu terakhir dialokasikan untuk pendampingan operasional dan penyusunan Laporan Akhir Magang.

Rincian jadwal tahapan kerja disusun sebagai berikut:

| Periode Waktu | Tahapan Kegiatan | Rincian Aktivitas & Hasil yang Diharapkan (Output) |
| :--- | :--- | :--- |
| **Pekan 1**<br>(21 – 27 Sep 2026) | **Inisialisasi & Fondasi Sistem** | • Persiapan lingkungan kerja framework Laravel 12 dan basis data MySQL.<br>• Pembuatan struktur 8 tabel database, relasi model Eloquent, dan seeding peran.<br>• Pembuatan template dasar antarmuka web responsif bertema dark-gold. |
| **Pekan 2 – 3**<br>(28 Sep – 11 Okt 2026) | **Pengembangan Portal Pengusul** | • Pembuatan modul registrasi akun minimalis, login, profil, dan data rekening penyaluran.<br>• Pembuatan formulir usulan proposal terstruktur dan format dinamis nominal anggaran.<br>• Pembuatan fitur unggah berkas proposal PDF dan slot berkas lampiran pendukung. |
| **Pekan 4 – 5**<br>(12 – 25 Okt 2026) | **Checklist Ketelitian & Dasbor Pimpinan** | • Pembuatan dialog modal Checklist Ketelitian Mandiri (Anti-Revisi) dengan rangkuman berkas.<br>• Pembuatan dasbor penelaahan Wakil Bupati yang ramah sentuhan tablet/ponsel.<br>• Integrasi penampil dokumen PDF responsif langsung di peramban web (*PDF Viewer*). |
| **Pekan 6 – 7**<br>(26 Okt – 8 Nov 2026) | **Modul Keputusan, Revisi, & Tracking** | • Pembuatan fitur eksekusi keputusan pimpinan (Setuju, Revisi dengan Catatan, Tolak).<br>• Penerapan pencatatan versi dokumen (v1.0 ke v2.0) dan duplikasi lampiran otomatis.<br>• Pembuatan formulir perbaikan pengusul, tabel dinamis kalkulator RAB, dan layanan Public Tracking. |
| **Pekan 8 – 9**<br>(9 – 19 Nov 2026) | **Uji Coba Sistem & Serah Terima Produk** | • Pengujian fungsionalitas otomatis menyeluruh (34 feature tests PHPUnit 100% lulus).<br>• Pengujian penerimaan pengguna (UAT) bersama staf Bagian Administrasi Pimpinan.<br>• **Serah Terima Produk SIPPRO MURA resmi pada 19 November 2026** (2 pekan sebelum penarikan magang). |
| **Pekan 10 – 11**<br>(20 Nov – 3 Des 2026) | **Pendampingan & Laporan Akhir Magang** | • Pendampingan operasional penggunaan sistem bagi staf administrasi Setda.<br>• Penyusunan buku petunjuk penggunaan sistem (*User Manual*).<br>• Penyusunan dan penyempurnaan Laporan Akhir Magang D3 Teknik Informatika hingga penarikan magang pada 3 Desember 2026. |

---

## 8. PENGUJIAN DAN KRITERIA PENERIMAAN SISTEM

Pengujian sistem dilakukan secara otomatis melalui rangkaian pengujian unit dan fitur (*Feature Tests* PHPUnit) untuk memastikan bahwa fungsi-fungsi utama berjalan sesuai dengan kebutuhan yang telah dirancang:

| No | Modul yang Diuji | Skenario Pengujian | Hasil yang Diharapkan & Aktual | Status |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Pendaftaran & Login | Pengusul mendaftar dengan data minimalis dan login dengan kredensial terdaftar. | Akun berhasil dibuat, sesi login terbentuk, dan diarahkan ke dasbor pengusul. | **LULUS** |
| 2 | Simulasi Akses Cepat | Pengguna memilih opsi Quick Login untuk peran Pengusul, Staf Admin, atau Wabup. | Sistem mengautentikasi pengguna secara instan sesuai peran yang dipilih. | **LULUS** |
| 3 | Input Form & Anggaran | Pengusul mengisi perihal usulan dan jumlah dana dengan format rupiah otomatis. | Sistem memformat tampilan secara dinamis dan menyimpan nilai numerik bersih. | **LULUS** |
| 4 | Unggah Berkas & Validasi | Pengusul mengunggah file PDF proposal utama dan lampiran pendukung. | Berkas tersimpan di server lokal dan tautan pratinjau dokumen berfungsi. | **LULUS** |
| 5 | Modal Ketelitian Mandiri | Pengusul mencoba mengirimkan proposal sebelum mencentang pakta integritas. | Tombol kirim tetap tidak aktif (*disabled*) hingga pakta integritas dicentang. | **LULUS** |
| 6 | Penomoran & Registrasi | Pengusul menyelesaikan checklist dan menekan tombol kirim proposal. | Proposal berstatus *Diajukan*, nomor registrasi unik `PROP-YYYYMM-XXXX` terbit, dan versi 1.0 terbentuk. | **LULUS** |
| 7 | Pembaca PDF Web | Pengguna atau Wakil Bupati membuka detail dokumen proposal. | Berkas dokumen PDF tertampil langsung di layar peramban melalui embedded iframe. | **LULUS** |
| 8 | Otorisasi Disposisi Pimpinan | Staf Administrasi atau Pengusul mencoba mengeksekusi route telaah/keputusan. | Akses ditolak dengan kode respon HTTP `403 Forbidden` (hak eksklusif Wabup). | **LULUS** |
| 9 | Tindakan Persetujuan | Wakil Bupati memilih opsi persetujuan dan menyimpan catatan arahan. | Status proposal diperbarui menjadi *Disetujui* dan keputusan tersimpan di basis data. | **LULUS** |
| 10 | Tindakan Permintaan Revisi | Wakil Bupati memilih *Perlu Perbaikan* dan menuliskan catatan arahan. | Status proposal menjadi *Perlu Perbaikan* dan catatan instruksi tersimpan permanen. | **LULUS** |
| 11 | Formulir Perbaikan (Revisi) | Pengusul membuka halaman revisi, mengunggah revisi PDF, dan mengisi catatan revisi. | Sistem menyimpan berkas sebagai Versi 2.0 (v2.0) tanpa mengubah nomor registrasi. | **LULUS** |
| 12 | Riwayat Versi Multi-Dokumen | Pengguna membuka arsip versi lama melalui query parameter `?version=1`. | Sistem menampilkan data versi 1 tanpa menimpa data versi 2 yang sedang aktif. | **LULUS** |
| 13 | Pelacakan Publik Mandiri | Pengguna publik memasukkan nomor registrasi pada route `/lacak` tanpa login. | Halaman menampilkan status terkini, perihal, dan catatan arahan pimpinan secara transparan. | **LULUS** |
| 14 | Profil & Rekening Penyaluran | Pengusul memperbarui data profil perorangan/lembaga dan nomor rekening bank. | Data rekening dan kontak penanggung jawab tersimpan dengan notifikasi sukses. | **LULUS** |

---

> Dokumen Spesifikasi Kebutuhan Sistem (PRD) ini telah disinkronkan secara presisi dengan implementasi aktual sistem SIPPRO MURA sebagai acuan pelaksanaan kegiatan magang Program Studi D3 Teknik Informatika, Jurusan Teknik Elektro, Politeknik Negeri Banjarmasin, bekerja sama dengan Bagian Pelayanan Administrasi Pimpinan Sekretariat Daerah Kabupaten Murung Raya.
