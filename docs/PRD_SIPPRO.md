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
| **Versi Dokumen** | 1.2 (Edisi Sinkronisasi Pasca-Implementasi Disposisi TU Pimpinan & Manajemen Batas Waktu Proposal — Commit 92def8f) |
| **Status Dokumen** | Telah Disinkronkan Penuh dengan Implementasi Sistem Final (Commit 92def8f) |
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
   3.2 Aturan Khusus Pengambilan Keputusan & Gerbang Disposisi
   3.3 Matriks Hak Akses Pengguna
4. Kebutuhan Sistem
   4.1 Kebutuhan Fungsional (Functional Requirements)
   4.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)
5. Perancangan Basis Data (Database)
   5.1 Daftar Tabel Basis Data
   5.2 Struktur Kolom dan Tipe Data
   5.3 Hubungan Antar Tabel (Relasi Data)
6. Alur Kerja Sistem (Workflow)
   6.1 Alur Kerja Utama Dua Tahap (Two-Stage Governance Workflow)
   6.2 Alur Siklus Perbaikan Proposal & Kewajiban Disposisi Ulang
   6.3 Rincian Prosedur Operasional per Tahapan
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
5. **Kebutuhan Pengendalian Waktu & Tertib Disposisi Fisik**: Diperlukan pemantauan batas waktu tindak lanjut berkas proposal yang transparan serta pencatatan lembar disposisi fisik pimpinan (Kepala Sub Bagian Tata Usaha Pimpinan) sebagai verifikasi administratif sebelum berkas dinaikkan ke meja kerja Wakil Bupati.

Oleh karena itu, dibangun sistem informasi berbasis web bernama **SIPPRO MURA (Sistem Informasi Pelayanan Proposal Murung Raya)** untuk membantu mempermudah pengajuan proposal oleh masyarakat serta mendukung penelaahan langsung oleh pimpinan daerah secara tertib dan terkomputerisasi.

### 1.2 Maksud dan Tujuan Pembuatan Sistem
Tujuan dari perancangan sistem informasi ini adalah:
1. Menyediakan aplikasi web mandiri bagi masyarakat/organisasi untuk mengajukan proposal permohonan secara online.
2. Membantu Staf Bagian Tata Usaha Pimpinan mencatat hasil verifikasi dan lembar disposisi fisik pimpinan (Pak Haziral) secara digital sebelum berkas ditelaah pimpinan daerah.
3. Membantu Wakil Bupati meninjau berkas proposal yang telah tuntas didisposisi secara ringkas langsung di layar perangkat (komputer/tablet/ponsel) dan memberikan keputusan secara cepat.
4. Menyediakan sistem pengingat dan pemantauan batas waktu (*deadline management*) dengan indikator urgensi visual terstandarisasi.
5. Mengurangi potensi terjadinya revisi berulang dengan menyediakan panduan verifikasi kelengkapan mandiri sebelum pengusul mengirimkan proposal.
6. Menyediakan layanan pelacakan mandiri publik (*Public Tracking*) berbasis nomor registrasi resmi tanpa mewajibkan masyarakat untuk login.
7. Membantu staf Bagian Pelayanan Administrasi Pimpinan dalam mengelola arsip proposal masuk dan mencatat riwayat aktivitas secara transparan.

### 1.3 Batasan Masalah (Ruang Lingkup)
Agar ruang lingkup tugas magang ini terarah dan realistis untuk diselesaikan sesuai jenjang D3 Teknik Informatika, batasan sistem ditentukan sebagai berikut:
1. Sistem mencakup pendaftaran akun pengusul, pengisian formulir usulan dan anggaran, unggah berkas PDF, pemeriksaan mandiri, pencatatan disposisi administratif oleh Staf TU Pimpinan, penetapan batas waktu tindak lanjut, serta peninjauan akhir oleh Wakil Bupati.
2. Keputusan yang dapat diberikan oleh Wakil Bupati berupa tiga opsi resmi: **Disetujui**, **Perlu Perbaikan**, atau **Ditolak**.
3. Sistem mencatat riwayat keputusan dan arahan pimpinan secara digital pada setiap versi dokumen.
4. **Tidak mencakup proses pencairan anggaran keuangan daerah**. Proses pencairan uang tetap mengikuti prosedur perbendaharaan daerah yang berlaku melalui instansi/bagian keuangan terkait.
5. Format proposal utama yang diunggah difokuskan pada format dokumen **PDF**.

### 1.4 Definisi Istilah
- **SIPPRO MURA**: Sistem Informasi Pelayanan Proposal Murung Raya.
- **Pengusul**: Perorangan atau perwakilan organisasi masyarakat yang mengajukan proposal permohonan ke pimpinan daerah.
- **RAB**: Rencana Anggaran Biaya, yaitu perincian item estimasi biaya yang diajukan dalam proposal.
- **Checklist Ketelitian Mandiri**: Daftar periksa wajib yang harus dikonfirmasi oleh pengusul sebelum mengirimkan proposal agar berkas yang dikirim benar-benar lengkap dan siap dinilai.
- **Riwayat Versi (Versioning)**: Pencatatan nomor perbaikan dokumen (misalnya Versi 1.0, Versi 2.0, Versi 3.0) agar berkas lama tetap tersimpan dan tidak tertimpa saat ada perbaikan.
- **Disposisi TU Pimpinan (Pak Haziral)**: Lembar verifikasi administratif dan disposisi fisik yang ditandatangani oleh KSB. Tata Usaha Pimpinan (Haziral, S.E.) dan dicatat oleh Staf Administrasi ke dalam sistem sebagai gerbang kendali (*gatekeeper*) sebelum berkas dapat ditelaah oleh Wakil Bupati.
- **Telaah & Keputusan Pimpinan**: Keputusan resmi (Disetujui, Perlu Perbaikan, Ditolak) yang secara eksklusif merupakan wewenang Wakil Bupati.
- **Manajemen Batas Waktu (Deadline) Proposal**: Pengaturan tanggal batas akhir tindak lanjut berkas proposal yang dikelola oleh Staf Administrasi di level entitas induk proposal (`proposals.batas_waktu`) dengan indikator warna berbasis hari kalender tanpa mereset saat revisi dan tanpa auto-reject.
- **Public Tracking**: Fitur pelacakan status proposal terbuka berbasis nomor registrasi resmi tanpa login.

---

## 2. GAMBARAN UMUM SISTEM

### 2.1 Deskripsi Produk SIPPRO MURA
SIPPRO MURA adalah sistem informasi berbasis web yang dirancang untuk mengelola alur persuratan proposal masuk pada Bagian Pelayanan Administrasi Pimpinan Sekretariat Daerah Kabupaten Murung Raya. Sistem ini menghubungkan pengusul secara daring dengan meja kerja Staf Tata Usaha Pimpinan dan meja kerja digital Wakil Bupati, sehingga proses verifikasi administrasi, pencatatan disposisi fisik, dan telaah kebijakan proposal dapat berlangsung lebih cepat, transparan, dan tertib.

### 2.2 Fitur Unggulan Sistem
1. **Pengajuan Proposal Terstruktur Secara Online**: Pengusul dapat mengisi data kegiatan, memasukkan nominal usulan dengan format dinamis otomatis, dan mengunggah berkas kapan saja tanpa harus mengantarkan berkas fisik ke kantor.
2. **Gerbang Cek Ketelitian Mandiri (Anti-Revisi)**: Modal verifikasi mandiri yang merangkum data usulan dan status unggah dokumen. Tombol kirim hanya aktif setelah pengusul mencentang pakta ketelitian data.
3. **Pencatatan Disposisi Fisik TU Pimpinan (Pak Haziral)**: Modul administrasi staf untuk mencatat nomor surat, tanggal surat, asal surat, tanggal disposisi, catatan instruksi TU, dan unggahan scan berkas bukti disposisi fisik sebelum berkas diteruskan ke pimpinan daerah.
4. **Gerbang Disposisi Wajib (Disposition Gate)**: Mekanisme kontrol di mana Wakil Bupati hanya dapat menelaah dan memberikan keputusan pada proposal yang telah memiliki disposisi berstatus *Selesai* pada versi aktif usulan.
5. **Penelaahan Langsung oleh Wakil Bupati**: Antarmuka ringkas khusus pimpinan untuk melihat daftar antrean usulan yang telah selesai didisposisi, memeriksa profil pengusul, dan membaca berkas proposal langsung di layar peramban.
6. **Keputusan Cepat 1-Klik**: Pimpinan dapat langsung memilih keputusan: *Disetujui*, *Perlu Perbaikan* (disertai catatan arahan revisi), atau *Ditolak*.
7. **Riwayat Perubahan Tersimpan Rapi (Versioning)**: Setiap perbaikan proposal tercatat sebagai versi baru (v1.0 → v2.0 → v3.0) tanpa menghapus data pengajuan sebelumnya, dilengkapi duplikasi berkas otomatis dan selector arsip versi.
8. **Kewajiban Disposisi Ulang pada Setiap Revisi**: Setiap versi perbaikan baru wajib melalui pencatatan disposisi administratif ulang oleh TU Pimpinan sebelum dapat ditelaah kembali oleh Wakil Bupati.
9. **Manajemen Batas Waktu Proposal (Deadline Management)**: Pengaturan tanggal batas waktu tindak lanjut berkas proposal di level induk proposal, dengan indikator urgensi visual berbasis hari kalender (Aman, Mendekati Batas Waktu, Mendesak, Terlewat/Perlu Perhatian) yang kebal dari reset saat terjadi revisi dokumen.
10. **Tabel Dinamis Perhitungan RAB**: Fasilitas penyusunan rincian anggaran interaktif pada alur revisi dengan kalkulasi subtotal dan total biaya realtime.
11. **Pelacakan Proposal Mandiri Publik (Public Tracking)**: Fasilitas transparansi bagi masyarakat umum atau pemohon untuk melacak status usulan dan perkiraan waktu pelayanan secara instan hanya dengan memasukkan nomor registrasi proposal.
12. **Simulasi Akses Peran Cepat (Quick Role Access)**: Fasilitas demonstrasi sistem yang memudahkan penguji mencoba peran Pengusul, Staf Administrasi, maupun Wakil Bupati dalam 1-klik.

### 2.3 Perangkat Lunak & Teknologi yang Digunakan
Teknologi yang digunakan disesuaikan dengan kompetensi dasar mahasiswa D3 Teknik Informatika:

| Komponen Sistem | Teknologi yang Dipilih | Implementasi Aktual & Keterangan |
| :--- | :--- | :--- |
| **Bahasa & Framework Backend** | PHP 8.4 (Framework Laravel 12) | Framework web standar industri dengan arsitektur MVC yang rapi, Eloquent ORM, dan performa tinggi. |
| **Basis Data (Database)** | MySQL / SQLite (Testing) | Sistem manajemen basis data relasional dengan 9 tabel utama yang terelasi secara integritas referensial. |
| **Antarmuka Pengguna (Frontend)**| HTML5, Blade, Tailwind CSS, JavaScript | Menghasilkan tampilan modern bertema gelap-emas (*Black Luxury & Gold*) yang responsif di desktop, tablet, dan smartphone. |
| **Penampil Dokumen** | Responsive Iframe PDF Viewer | Menampilkan file proposal PDF secara langsung di halaman web setinggi 620px dengan opsi unduh berkas. |
| **Penyimpanan Berkas** | Laravel Storage (*Public Disk*) | Menyimpan berkas proposal, lampiran pendukung, dan bukti scan lembar disposisi fisik di direktori server terstruktur (`storage/app/public/proposals`, `attachments`, `dispositions`). |
| **Notifikasi Pengguna** | In-App Flash Alert & Activity Logs | Menggunakan alert status interaktif dengan auto-dismiss 3 detik, badge indikator status, dan badge urgensi batas waktu. |

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
- *Batasan Ketat*: Pengusul **dilarang** mencatat disposisi, **dilarang** memberikan keputusan telaah, dan **dilarang** menentukan, mengubah, atau memanipulasi batas waktu proposal.

#### B. Wakil Bupati (Pimpinan Daerah)
Pejabat pengambil keputusan utama atas proposal yang masuk, dengan hak akses eksklusif untuk:
- Membuka halaman dasbor eksekutif yang ringkas dan ramah perangkat bergerak (tablet/smartphone).
- Melihat daftar antrean proposal masuk yang **telah berstatus selesai disposisi administratif oleh TU Pimpinan**, dengan urutan prioritas batas waktu terdekat (*nearest deadline*).
- Membaca berkas dokumen PDF secara langsung di layar peramban melalui embedded PDF Viewer serta memeriksa bukti lembar disposisi fisik Pak Haziral.
- Menetapkan keputusan resmi: **Disetujui**, **Perlu Perbaikan** (wajib catatan arahan), atau **Ditolak**.
- Meninjau riwayat arsip perbaikan versi proposal (v1.0 vs v2.0 vs v3.0) secara komparatif.
- *Batasan Ketat*: Wakil Bupati diblokir oleh sistem (`403 Forbidden`) jika mencoba menelaah proposal yang belum selesai didisposisi oleh TU Pimpinan pada versi aktifnya.

#### C. Staf Pelayanan Administrasi Pimpinan (Administrator)
Staf tata usaha yang bertugas mengelola ketertiban data dan administrasi persuratan pimpinan, dengan hak akses untuk:
- Mengelola data master kategori proposal dan memantau akun pengguna.
- Memantau rekapitulasi proposal masuk secara menyeluruh melalui dasbor admin (antrean menunggu disposisi TU, berkas disposisi selesai, status telaah).
- Melakukan pencatatan lembar disposisi fisik Pak Haziral (`storeDisposition`) lengkap dengan metadata surat pengusul, tanggal disposisi, instruksi, dan unggahan scan lembar disposisi fisik.
- Menetapkan dan memperbarui batas waktu (*deadline*) tindak lanjut proposal (`updateDeadline`) baik saat pencatatan disposisi maupun secara mandiri melalui panel administrasi proposal.
- Memantau log aktivitas sistem (*Activity Logs*) yang mencatat setiap peristiwa penting (termasuk audit trail perubahan deadline) beserta alamat IP pengguna.
- *Catatan Penting*: Staf Administrasi **tidak memiliki wewenang** untuk mengeksekusi telaah keputusan substantif (Disetujui/Perlu Perbaikan/Ditolak) maupun mengajukan revisi atas nama pengusul. Keputusan substantif mutlak milik Wakil Bupati.

### 3.2 Aturan Khusus Pengambilan Keputusan & Gerbang Disposisi
> **Catatan Penting Otorisasi & Alur Kontrol**:
> 1. Hak untuk menyetujui, menolak, atau meminta perbaikan proposal secara mutlak berada pada **Wakil Bupati**. Sistem menerapkan proteksi ganda: middleware route `role:wabup` dan verifikasi peran internal pada controller. Staf Administrasi dan Pengusul secara otomatis diblokir (`403 Forbidden`) jika mencoba mengeksekusi fungsi keputusan.
> 2. **Gerbang Disposisi Wajib (*Disposition Gatekeeper*)**: Proposal yang berstatus `diajukan` tidak dapat ditelaah oleh Wakil Bupati sebelum lembar disposisi administratif Pak Haziral (KSB. Tata Usaha Pimpinan) dicatat dan berstatus `selesai` pada versi aktif proposal.
> 3. **Kewajiban Disposisi Ulang**: Setiap kali pengusul mengajukan revisi dokumen ke versi baru (v2, v3, dst.), versi baru tersebut wajib melalui proses disposisi fisik ulang oleh TU Pimpinan sebelum dapat ditelaah kembali oleh Wakil Bupati.

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
| Pencatatan Disposisi Fisik Pak Haziral | Tidak | Tidak | **Ya (Hak Khusus)** | Tidak |
| Menetapkan / Memperbarui Batas Waktu | Tidak | Tidak | **Ya (Hak Khusus)** | Tidak |
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
2. **Dasbor Wakil Bupati**: Menyajikan metrik total proposal masuk, proposal siap ditelaah (yang telah berstatus selesai disposisi TU), disetujui, dan perlu perbaikan. Tabel antrean telaah diurutkan berdasarkan batas waktu terdekat (`orderByDeadline('asc')`) untuk memprioritaskan berkas mendesak.
3. **Dasbor Staf Administrasi**: Menyajikan metrik usulan masuk, antrean berkas menunggu pencatatan disposisi Pak Haziral, dan berkas disposisi selesai yang diteruskan ke meja Wabup, dilengkapi prioritas pengurutan batas waktu terdekat.

#### FR-06: Modul Penampil Dokumen PDF (PDF Viewer)
1. Pada halaman detail proposal, sistem menyematkan penampil PDF interaktif berbasis *responsive iframe* setinggi 620px yang langsung menampilkan isi proposal tanpa harus mengunduh file.
2. Sistem menyediakan tombol pintas untuk membuka berkas pada tab peramban baru dan tombol unduh langsung bagi pimpinan maupun pengusul.

#### FR-07: Modul Pencatatan Disposisi Fisik TU Pimpinan (Pak Haziral)
1. Staf Administrasi memiliki formulir khusus untuk mencatat lembar disposisi fisik pimpinan:
   - Nomor Surat Usulan Masuk dari Pengusul
   - Tanggal Surat Pengusul
   - Asal Surat / Lembaga Pengusul
   - Tujuan Arahan Disposisi (default: *Wakil Bupati Murung Raya*)
   - Tanggal Disposisi Fisik Pak Haziral
   - Pejabat Disposisi (default: *Haziral, S.E. (KSB. Tata Usaha Pimpinan)*)
   - Catatan / Arahan Disposisi Pak Haziral
   - Unggah Bukti Scan / Foto Lembar Disposisi Fisik (PDF, JPG, JPEG, PNG maksimal 5 MB).
   - Pengaturan Batas Waktu Tindak Lanjut Proposal (opsional).
2. Status disposisi dicatat sebagai **`selesai`**.
3. **Gerbang Disposisi Wajib (*Disposition Gatekeeper*)**: Berkas proposal yang belum memiliki lembar disposisi berstatus selesai pada versi aktifnya diblokir secara otomatis dari akses telaah Wakil Bupati (`403 Forbidden`).

#### FR-08: Modul Keputusan & Telaah Eksekutif Wakil Bupati
1. Wakil Bupati memiliki hak eksklusif untuk menetapkan keputusan melalui formulir telaah:
   - **Disetujui Penuh**: Merekomendasikan usulan untuk ditindaklanjuti ke penatausahaan pencairan.
   - **Perlu Perbaikan**: Mengembalikan berkas ke pengusul dengan kewajiban mengisi instruksi/poin arahan perbaikan.
   - **Ditolak**: Menolak usulan dengan memberikan catatan pertimbangan kebijakan daerah.
2. Sistem memverifikasi bahwa berkas telah memiliki disposisi berstatus selesai sebelum formulir keputusan pimpinan dapat diproses.
3. Keputusan pimpinan tersimpan permanen di basis data dengan penanda waktu (*timestamp*) dan nama pimpinan penelaah.

#### FR-09: Modul Perbaikan (Revisi) Proposal & Versioning
1. Proposal berstatus *Perlu Perbaikan* mengaktifkan tombol khusus *"Ajukan Perbaikan Proposal"* bagi pengusul pemilik proposal.
2. Halaman revisi menampilkan kotak arahan resmi dari Wakil Bupati dalam mode baca (*read-only*).
3. Pengusul dapat memperbarui judul, lokasi, tanggal, dan mengunggah dokumen PDF revisi baru.
4. Halaman revisi dilengkapi **Tabel Dinamis Perhitungan RAB** yang memungkinkan pengusul menyesuaikan volume, satuan, dan rincian biaya per item barang secara otomatis.
5. Pengusul wajib mengisi kolom *"Catatan Perbaikan dari Pemohon"* (minimal 5 karakter) untuk menjelaskan poin yang telah disesuaikan sebelum mengirimkan revisi.
6. **Penerbitan Versi Baru**: Sistem mencatat dokumen perbaikan sebagai **Versi 2.0 (v2.0)** atau **Versi 3.0 (v3.0)** tanpa mengubah nomor registrasi proposal dan tanpa menimpa data versi sebelumnya. Lampiran pendukung dari versi terdahulu diduplikasi secara otomatis.
7. Status proposal kembali menjadi **`diajukan`**.
8. **Kewajiban Disposisi Ulang**: Setiap versi revisi baru secara otomatis mengaktifkan kembali gerbang disposisi, di mana Staf Administrasi wajib mencatatkan lembar disposisi administratif ulang untuk versi tersebut sebelum Wakil Bupati dapat menelaah kembali versi perbaikan.

#### FR-10: Modul Manajemen Batas Waktu Proposal (Deadline Management)
1. Field `batas_waktu` tersimpan pada entitas induk proposal (`proposals`) bertipe `date`, sehingga bersifat permanen dan **kebal dari reset saat terjadi revisi dokumen**.
2. Batas waktu hanya dapat ditentukan dan diperbarui oleh Staf Administrasi (Admin), baik saat pengisian formulir disposisi maupun melalui formulir pengaturan batas waktu mandiri (`PATCH /proposals/{id}/batas-waktu`).
3. Pengusul dilarang keras menentukan, mengubah, atau memanipulasi batas waktu proposal (percobaan akses ditolak `403 Forbidden`).
4. Sistem menerapkan kalkulasi selisih hari berbasis tanggal kalender murni (`now()->startOfDay()->diffInDays(...)`) dengan 4 tingkatan indikator urgensi visual:
   - **> 7 hari**: AMAN (Badge Emerald, *"Aman (Sisa X hari)"*)
   - **3–7 hari**: MENDEKATI BATAS WAKTU (Badge Emas/Kuning, *"Mendekati Batas Waktu (X hari lagi)"*)
   - **0–2 hari**: MENDESAK (Badge Oranye, *"Mendesak (X hari lagi / Hari Ini)"*)
   - **< 0 hari**: TERLEWAT (Badge Merah Gelap, *"Terlewat / Perlu Perhatian"*)
5. **Aturan Non-Interferensi**: Deadline yang terlewat (< 0 hari) **TIDAK** mengubah status proposal, tidak auto-reject, tidak auto-cancel, dan tidak membatalkan keputusan pimpinan daerah. Deadline murni berfungsi sebagai indikator pengingat dan prioritas administratif.

#### FR-11: Modul Notifikasi Pengguna & Catatan Riwayat Aktivitas (Activity Logs)
1. Sistem menyediakan notifikasi berbasis aplikasi (*In-App Alert Notification*) dengan pesan umpan balik sukses/error yang dilengkapi penghitung waktu tutup otomatis (*auto-dismiss* 3 detik).
2. Setiap status usulan ditampilkan dengan lencana warna terstandarisasi (*badge color status*).
3. Sistem mencatat log audit peristiwa penting secara transparan: pendaftaran akun, pengajuan proposal baru, pencatatan disposisi TU, penetapan/pembaruan batas waktu proposal, pengiriman revisi, dan penetapan telaah pimpinan.
4. Setiap entri log mencatat ID pengguna, jenis aktivitas, rincian keterangan tindakan, alamat IP (*IP Address*), dan waktu kejadian.

#### FR-12: Modul Lembar Disposisi & Rekapitulasi Data
1. Riwayat lembar disposisi fisik Pak Haziral dan keputusan telaah Wakil Bupati tercatat secara digital dan terikat pada nomor versi dokumen yang dinilai.
2. File bukti scan lembar disposisi dapat dibuka dan diunduh langsung dari halaman detail proposal.
3. *(Rencana Lanjutan)*: Modul generator pencetakan dokumen rekapitulasi berformat PDF (*PDF export generator*) dijadwalkan untuk diselesaikan pada tahap pengemasan lanjutan.

#### FR-13: Modul Pelacakan Proposal Publik Mandiri (Public Tracking)
1. Sistem menyediakan halaman pelacakan publik terbuka (`/lacak`) yang dapat diakses oleh masyarakat tanpa harus login ke dalam sistem.
2. Pengguna cukup memasukkan nomor registrasi proposal resmi (format `PROP-YYYYMM-XXXX`) untuk memantau perkembangan usulan.
3. Sistem menyajikan informasi 4 tahapan alur pelayanan administrasi transparan (Pengajuan → Disposisi TU Pimpinan → Telaah Wakil Bupati → Penetapan Akhir), perihal usulan, nama pemohon/lembaga, tanggal masuk, lencana status saat ini, catatan resmi disposisi pimpinan, serta perkiraan waktu pelayanan dengan bahasa aman publik tanpa mengekspos istilah internal birokrasi teknis.

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
   - Waktu respons pengujian sistem lokal berada di bawah 5 detik untuk seluruh 58 feature and unit tests (329 assertions 100% lulus).
4. **Integritas Data (Data Integrity)**:
   - Penggunaan transaksi basis data (`DB::transaction`) pada alur pengajuan proposal, telaah pimpinan, dan resubmit revisi untuk mencegah inkonsistensi data jika terjadi gangguan jaringan.

---

## 5. PERANCANGAN BASIS DATA (DATABASE DESIGN)

### 5.1 Daftar Tabel Basis Data
Basis data SIPPRO MURA terdiri atas 9 tabel utama yang saling berelasi:

| No | Nama Tabel | Fungsi & Keterangan |
| :---: | :--- | :--- |
| 1 | `users` | Menyimpan data akun pengguna dan peran hak akses (`pengusul`, `wabup`, `admin`). |
| 2 | `profiles` | Menyimpan profil pemohon, jenis pengusul, alamat, dan nomor rekening penyaluran bank. |
| 3 | `categories` | Master klasifikasi bidang usulan proposal (Sosial, Keagamaan, Kepemudaan, dll.). |
| 4 | `proposals` | Tabel entitas induk proposal (nomor registrasi unik, total dana, status, versi aktif, batas waktu). |
| 5 | `proposal_versions` | Menyimpan data detail usulan, dokumen proposal PDF, dan catatan revisi per nomor versi. |
| 6 | `proposal_attachments`| Menyimpan berkas lampiran pendukung fisik (KTP, SK Lembaga, Buku Rekening). |
| 7 | `review_decisions` | Menyimpan keputusan resmi, catatan instruksi pimpinan, dan identitas penelaah (Wakil Bupati). |
| 8 | `activity_logs` | Menyimpan catatan audit trail aktivitas pengguna beserta rincian tindakan dan IP address. |
| 9 | `dispositions` | Menyimpan pencatatan lembar disposisi fisik TU Pimpinan (Pak Haziral) sebagai gerbang (*gatekeeper*) sebelum telaah Wabup. |

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
| `judul_proposal` | VARCHAR(255) | Perihal / judul permohonan usulan |
| `total_anggaran` | DECIMAL(15,2) | Total nilai dana yang diajukan |
| `status` | ENUM('draft', 'diajukan', 'perlu_perbaikan', 'disetujui', 'ditolak') | Status perkembangan usulan proposal |
| `versi_aktif` | INT (Unsigned) | Nomor versi dokumen yang sedang aktif (default: 1) |
| `tanggal_kirim` | TIMESTAMP (Nullable) | Waktu proposal pertama kali diajukan ke sistem |
| `batas_waktu` | DATE (Nullable) | Batas waktu tindak lanjut administratif proposal (dikelola oleh Admin, tidak reset saat revisi) |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pembuatan dan pembaruan proposal |

#### Tabel 5: `proposal_versions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Versi Dokumen |
| `proposal_id` | BIGINT (Foreign Key -> `proposals.id`) | Relasi ke entitas induk proposal |
| `nomor_versi` | INT (Unsigned) | Urutan nomor versi dokumen (1, 2, dst.) |
| `latar_belakang` | LONGTEXT (Nullable) | Uraian latar belakang & nomor proposal pemohon |
| `tujuan` | LONGTEXT (Nullable) | Maksud dan tujuan permohonan usulan |
| `lokasi_kegiatan`| VARCHAR(255) (Nullable) | Tempat / desa lokasi kegiatan usulan |
| `tanggal_kegiatan`| DATE (Nullable) | Estimasi tanggal kegiatan |
| `rincian_rab` | LONGTEXT (Nullable) | Rincian tabel baris item RAB format JSON |
| `file_proposal` | VARCHAR(255) (Nullable) | Lokasi path file dokumen proposal PDF di server |
| `catatan_revisi_pemohon` | TEXT (Nullable) | Keterangan poin yang telah diperbaiki oleh pengusul |
| `created_at` / `updated_at` | TIMESTAMP | Waktu penerbitan versi dokumen |

#### Tabel 6: `proposal_attachments`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Berkas Lampiran |
| `version_id` | BIGINT (Foreign Key -> `proposal_versions.id`) | Relasi ke versi dokumen terkait |
| `jenis_lampiran` | VARCHAR(255) | Tipe berkas (`ktp`, `akta`, `rekening`, dll.) |
| `nama_file` | VARCHAR(255) | Nama asli berkas dokumen saat diunggah |
| `file_path` | VARCHAR(255) | Lokasi penyimpanan berkas di storage server |
| `ukuran_file` | BIGINT (Unsigned) | Besaran ukuran berkas dalam satuan bytes |
| `created_at` / `updated_at` | TIMESTAMP | Waktu unggah dokumen lampiran |

#### Tabel 7: `review_decisions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Catatan Keputusan |
| `proposal_id` | BIGINT (Foreign Key -> `proposals.id`) | Proposal yang dinilai |
| `version_id` | BIGINT (Foreign Key -> `proposal_versions.id`) | Versi dokumen yang dinilai |
| `reviewer_id` | BIGINT (Foreign Key -> `users.id`) | Akun Wakil Bupati selaku penelaah tunggal |
| `keputusan` | ENUM('disetujui', 'perlu_perbaikan', 'ditolak') | Keputusan resmi pimpinan |
| `catatan_pimpinan` | TEXT (Nullable) | Instruksi, poin arahan, atau evaluasi resmi pimpinan |
| `tanggal_keputusan`| TIMESTAMP | Waktu penetapan keputusan oleh pimpinan |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pencatatan data telaah |

#### Tabel 8: `activity_logs`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Log Audit Trail |
| `user_id` | BIGINT (Foreign Key -> `users.id`, Nullable) | Pengguna pelaku aktivitas |
| `aktivitas` | VARCHAR(255) | Judul tindakan (misal: "Pencatatan Disposisi TU", "Pembaruan Batas Waktu Proposal") |
| `keterangan` | TEXT (Nullable) | Rincian detail keterangan peristiwa |
| `ip_address` | VARCHAR(45) (Nullable) | Alamat IP jaringan pengguna |
| `created_at` / `updated_at` | TIMESTAMP | Waktu peristiwa tercatat secara sistem |

#### Tabel 9: `dispositions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (Primary Key, Auto Increment) | ID Catatan Disposisi TU Pimpinan |
| `proposal_id` | BIGINT (Foreign Key -> `proposals.id`, Cascade Delete) | Relasi ke entitas induk proposal |
| `version_id` | BIGINT (Foreign Key -> `proposal_versions.id`, Cascade Delete) | Relasi ke versi dokumen spesifik yang didisposisi |
| `petugas_id` | BIGINT (Foreign Key -> `users.id`, Nullable, Null on Delete) | Akun Staf Admin yang mencatat lembar fisik ke sistem |
| `pejabat_disposisi` | VARCHAR(255) | Pejabat yang melaksanakan disposisi fisik (default: 'Haziral, S.E. (KSB. Tata Usaha Pimpinan)') |
| `nomor_surat` | VARCHAR(255) (Nullable) | Nomor surat permohonan resmi dari pihak pengusul |
| `tanggal_surat` | DATE (Nullable) | Tanggal surat permohonan pengusul |
| `asal_surat` | VARCHAR(255) (Nullable) | Asal instansi, organisasi, atau nama pemohon |
| `tujuan_disposisi` | VARCHAR(255) | Tujuan arahan disposisi (default: 'Wakil Bupati Murung Raya') |
| `tanggal_disposisi` | DATE | Tanggal pelaksanaan disposisi fisik TU Pimpinan |
| `catatan_disposisi` | TEXT (Nullable) | Instruksi, arahan, atau catatan lembar disposisi fisik Pak Haziral |
| `file_bukti_disposisi` | VARCHAR(255) (Nullable) | Path penyimpanan berkas scan/foto lembar disposisi fisik (PDF/JPG/PNG) |
| `status_disposisi` | ENUM('menunggu', 'selesai') | Status pencatatan disposisi (default: 'selesai') |
| `created_at` / `updated_at` | TIMESTAMP | Waktu pencatatan dan pembaruan data disposisi |

### 5.3 Hubungan Antar Tabel (Relasi Data)
- **Pengguna & Profil**: Satu pengguna (`users`) dengan peran pengusul memiliki satu profil data (`profiles`) dan dapat memiliki banyak berkas permohonan usulan (`proposals`).
- **Proposal & Kategori**: Setiap proposal (`proposals`) memiliki satu kategori (`categories`).
- **Proposal & Versi Dokumen**: Setiap proposal (`proposals`) memiliki relasi *one-to-many* ke tabel riwayat versi (`proposal_versions`). Nomor versi aktif dicatat pada kolom `versi_aktif`.
- **Versi Dokumen & Berkas Lampiran**: Setiap versi dokumen (`proposal_versions`) dapat memiliki beberapa berkas lampiran pendukung (`proposal_attachments`).
- **Disposisi sebagai Gerbang Administratif**: Setiap proposal (`proposals`) dan versinya (`proposal_versions`) dapat memiliki catatan disposisi fisik (`dispositions`) yang dicatat oleh Staf Administrasi (`petugas_id` merujuk ke `users.id`). Suatu versi proposal hanya dapat ditelaah oleh Wakil Bupati jika versi aktif tersebut telah memiliki catatan disposisi berstatus `selesai` (`Proposal::isDisposed() == true`).
- **Versi Dokumen & Keputusan Telaah**: Setiap versi dokumen proposal (`proposal_versions`) dapat memiliki satu catatan telaah keputusan pimpinan (`review_decisions`) yang secara eksklusif diputuskan oleh Wakil Bupati (`reviewer_id` merujuk ke akun Wabup).
- **Integritas Batas Waktu Proposal**: Field `batas_waktu` tersimpan pada entitas induk proposal (`proposals.batas_waktu`) sehingga nilainya tetap persisten dan tidak mengalami reset saat pengusul mengajukan versi perbaikan baru (`submitRevision()`). Pengelolaan nilai batas waktu hanya dapat dilakukan oleh Staf Administrasi.
- **Audit Trail Terpusat**: Seluruh tindakan penting pengguna di dalam sistem dicatat secara terpusat pada tabel rekam jejak aktivitas (`activity_logs`).

---

## 6. ALUR KERJA SISTEM (WORKFLOW)

### 6.1 Alur Kerja Utama Dua Tahap (Two-Stage Governance Workflow)

SIPPRO MURA tidak menerapkan alur pengajuan langsung dari Pengusul ke Wakil Bupati. Mengikuti tata kelola persuratan resmi di Sekretariat Daerah Kabupaten Murung Raya, sistem menerapkan alur gerbang dua tahap (*Two-Stage Governance Gate*):

```
┌─────────────────┐
│    PENGUSUL     │  Menginput usulan, upload berkas, RAB dinamis, & checklist ketelitian
└────────┬────────┘
         │
         ▼ (Status: diajukan, Versi 1.0)
┌─────────────────────────────────┐
│     STAF ADMIN / TU PIMPINAN    │  Verifikasi administratif surat masuk & berkas
└────────┬────────────────────────┘
         │
         ▼
┌─────────────────────────────────┐
│ PENCATATAN DISPOSISI FISIK      │  Staf Admin mencatat disposisi fisik Pak Haziral,
│ TU PIMPINAN (PAK HAZIRAL)       │  upload scan bukti, & menetapkan batas waktu proposal
└────────┬────────────────────────┘
         │
         ▼ (Gate Terbuka: status_disposisi = selesai)
┌─────────────────────────────────┐
│          WAKIL BUPATI           │  Menelaah dokumen usulan, RAB, & lembar disposisi fisik
└────────┬────────────────────────┘
         │
         ├───────────────────────────────┬───────────────────────────────┐
         ▼                               ▼                               ▼
   [DISETUJUI]                  [PERLU PERBAIKAN]                    [DITOLAK]
 Usulan disetujui penuh        Dikembalikan ke Pengusul           Usulan tidak disetujui
                              disertai catatan arahan
```

### 6.2 Alur Siklus Perbaikan Proposal & Kewajiban Disposisi Ulang

Apabila Wakil Bupati menetapkan keputusan **Perlu Perbaikan**, proposal masuk ke dalam siklus revisi berulang dengan kewajiban disposisi ulang (*Mandatory Repeat Disposition Gate*):

```
┌─────────────────────────────────┐
│          WAKIL BUPATI           │  Menetapkan keputusan: PERLU PERBAIKAN
└────────┬────────────────────────┘
         │
         ▼ (Status: perlu_perbaikan)
┌─────────────────────────────────┐
│            PENGUSUL             │  Membaca arahan Wabup, memperbarui dokumen/RAB,
└────────┬────────────────────────┘  mengisi catatan revisi, & submit perbaikan
         │
         ▼ (Status: diajukan, Versi 2.0 terbit, deadline TETAP & TIDAK reset)
┌─────────────────────────────────┐
│     STAF ADMIN / TU PIMPINAN    │  Menerima versi revisi di antrean "Menunggu Disposisi"
└────────┬────────────────────────┘
         │
         ▼
┌─────────────────────────────────┐
│  DISPOSISI FISIK ULANG          │  Staf Admin mencatat lembar disposisi fisik baru
│  TU PIMPINAN (PAK HAZIRAL)      │  khusus untuk Versi 2.0
└────────┬────────────────────────┘
         │
         ▼ (Gate Terbuka untuk Versi 2.0)
┌─────────────────────────────────┐
│          WAKIL BUPATI           │  Menelaah ulang Versi 2.0 beserta riwayat disposisi baru
└────────┬────────────────────────┘
         │
         ▼
   [KEPUTUSAN AKHIR] (Disetujui / Perlu Perbaikan / Ditolak)
```

### 6.3 Rincian Prosedur Operasional per Tahapan

#### A. Alur Pengajuan Proposal Baru (Pengusul)
1. **Pendaftaran & Masuk Akun**: Pengusul mendaftarkan akun atau masuk ke dalam sistem SIPPRO MURA.
2. **Pengisian Formulir Usulan**: Pengusul memilih tombol *Ajukan Usulan Baru*, menginput kategori, perihal usulan, lokasi, tanggal kegiatan, menyusun rincian item pada tabel dinamis kalkulator RAB, serta mengunggah dokumen proposal PDF utama dan berkas lampiran pendukung fisik (KTP, SK Lembaga, Rekening Bank).
3. **Pemeriksaan Ketelitian Mandiri (Modal Anti-Revisi)**: Saat pengusul menekan tombol ajukan, sistem memunculkan jendela dialog konfirmasi modal. Pengusul memeriksa ringkasan data dan kelengkapan dokumen yang telah divalidasi sistem, kemudian mencentang pakta ketelitian mandiri.
4. **Penerbitan Registrasi & Pengiriman**: Tombol ajukan aktif, sistem memproses transaksi database, menerbitkan nomor registrasi unik `PROP-YYYYMM-XXXX`, mencatat dokumen sebagai **Versi 1.0 (v1.0)**, menetapkan status menjadi **`diajukan`**, dan mencatat log audit aktivitas pengajuan.
5. **Antrean Administrasi**: Proposal masuk ke dalam antrean kerja Staf Administrasi dengan label indikator *"Menunggu Disposisi TU Pimpinan"*. Pada tahap ini, proposal belum dapat ditelaah oleh Wakil Bupati.

#### B. Alur Verifikasi & Pencatatan Disposisi Fisik TU Pimpinan (Staf Admin)
1. **Penerimaan Surat Masuk**: Staf Administrasi menerima berkas fisik atau surat pengantar yang telah dibubuhi lembar disposisi fisik oleh Kepala Sub Bagian Tata Usaha Pimpinan, **Haziral, S.E. (Pak Haziral)**.
2. **Pemeriksaan Antrean Disposisi**: Staf Admin membuka dasbor atau daftar proposal yang terfilter dengan status *"Menunggu Disposisi"*.
3. **Pencatatan Disposisi**: Staf Admin memilih menu *"Catat Disposisi"* dan mengisikan metadata lembar fisik:
   - Nomor surat pengusul, tanggal surat, dan asal surat/pemohon.
   - Nama pejabat disposisi (terisi otomatis: *Haziral, S.E. (KSB. Tata Usaha Pimpinan)*).
   - Tujuan disposisi (*Wakil Bupati Murung Raya*).
   - Tanggal pelaksanaan disposisi fisik.
   - Catatan instruksi disposisi dari Pak Haziral.
   - Unggahan berkas scan/foto bukti lembar disposisi fisik (PDF/JPG/PNG).
   - Menetapkan tanggal **Batas Waktu Proposal (Deadline)** tindak lanjut.
4. **Validasi & Pembukaan Gate**: Staf Admin menyimpan form disposisi. Sistem mencatat data ke tabel `dispositions` dengan status `selesai`, mengikat catatan disposisi ke nomor versi aktif proposal, memperbarui `batas_waktu`, mencatat audit trail di `activity_logs`, dan secara otomatis membuka akses telaah (*unlocked*) bagi Wakil Bupati.
5. **Batasan Ketat Staf Admin**: Staf Administrasi **TIDAK** memiliki wewenang memberikan keputusan substantif persetujuan/penolakan proposal. Tugas Staf Admin murni bersifat tata kelola administratif dan pencatatan disposisi fisik persuratan resmi pimpinan.

#### C. Alur Penelaahan & Keputusan Eksklusif oleh Wakil Bupati
1. **Akses Dasbor Pimpinan**: Wakil Bupati membuka halaman dasbor melalui komputer, tablet, atau smartphone.
2. **Penyaringan Antrean Telaah**: Dasbor Wabup hanya menampilkan proposal yang telah berstatus disposisi `selesai` pada versi aktifnya. Proposal yang belum didisposisi oleh TU Pimpinan tidak dapat diakses untuk penelaahan.
3. **Proteksi Keamanan (*Gatekeeper*)**: Jika ada upaya langsung mengakses formulir telaah proposal yang belum berstatus disposisi selesai, sistem memblokir tindakan dengan respon `403 Forbidden` (*"Proposal belum memiliki lembar disposisi dari Tata Usaha Pimpinan"*).
4. **Penelaahan Berkas Lengkap**: Wakil Bupati memeriksa data permohonan, profil pemohon, rincian anggaran RAB, membaca dokumen proposal PDF via *embedded PDF viewer*, dan meninjau bukti scan lembar disposisi fisik Pak Haziral.
5. **Penetapan Keputusan 1-Klik**:
   - Jika **Disetujui**: Pimpinan memilih opsi persetujuan, memasukkan catatan pimpinan, dan status proposal diperbarui menjadi **`disetujui`**.
   - Jika **Perlu Perbaikan**: Pimpinan memilih opsi revisi dan **wajib** mengisi catatan instruksi perbaikan teknis. Status proposal diperbarui menjadi **`perlu_perbaikan`**.
   - Jika **Ditolak**: Pimpinan memilih opsi penolakan disertai alasan kebijakan daerah. Status proposal diperbarui menjadi **`ditolak`**.

#### D. Alur Perbaikan (Revisi) Proposal & Kewajiban Disposisi Ulang (v1.0 → v2.0)
1. **Notifikasi Arahan**: Pengusul menerima pembaruan bahwa proposal memerlukan perbaikan dan membuka halaman detail proposal.
2. **Pemeriksaan Arahan Pimpinan**: Pengusul membaca catatan instruksi perbaikan resmi dari Wakil Bupati yang disajikan dalam panel khusus instruksi pimpinan.
3. **Akses Formulir Perbaikan**: Pengusul menekan tombol *"Ajukan Perbaikan Proposal"* untuk membuka formulir revisi.
4. **Pembaruan Berkas & Anggaran**: Pengusul mengunggah dokumen proposal PDF yang telah disempurnakan, memperbarui baris-baris item pada kalkulator dinamis RAB, dan **wajib** mengisi ringkasan perbaikan pada kolom *Catatan Perbaikan dari Pemohon*.
5. **Pengiriman Versi Revisi**: Pengusul mengirimkan perbaikan (`submitRevision`).
6. **Mekanisme Versioning Otomatis**:
   - Sistem menerbitkan **Versi 2.0 (v2.0)** (atau nomor versi inkremental berikutnya) tanpa mengubah nomor registrasi proposal.
   - Arsip data, file proposal, dan rincian RAB dari versi sebelumnya (v1.0) tetap tersimpan utuh dan dapat dilihat kembali melalui pemilih riwayat versi (`?version=1`).
   - Berkas lampiran fisik (KTP, SK, Buku Rekening) otomatis diduplikasi ke versi baru sehingga pengusul tidak perlu mengunggah ulang lampiran identitas.
   - Status proposal kembali diperbarui menjadi **`diajukan`**.
   - **Batas Waktu (Deadline) Tetap Persisten**: Nilai `proposals.batas_waktu` **TIDAK DIRESET** dan tidak mengalami perubahan saat revisi diserahkan.
7. **Kewajiban Disposisi Ulang**: Versi 2.0 yang baru terbit belum memiliki catatan disposisi (`isDisposed() == false`). Oleh karena itu, proposal kembali masuk ke antrean Staf Admin untuk dicatatkan lembar disposisi fisik baru dari Pak Haziral. Wakil Bupati tidak dapat melakukan penelaahan versi 2.0 sebelum disposisi fisik ulang selesai dicatat.

#### E. Alur Manajemen Batas Waktu (Deadline) & Indikator Urgensi
1. **Kewenangan Tunggal Admin**: Batas waktu tindak lanjut proposal (`batas_waktu`) hanya dapat ditentukan dan diubah oleh Staf Administrasi. Pengusul sama sekali tidak memiliki akses menetapkan maupun mengubah tanggal batas waktu.
2. **Penetapan & Pembaruan Fleksibel**: Staf Admin dapat menetapkan batas waktu saat pertama kali mencatat disposisi atau memperbaruinya sewaktu-waktu melalui tombol kelola batas waktu (`PATCH /proposals/{id}/batas-waktu`). Setiap kali batas waktu diubah, sistem mencatat rincian perubahan tanggal pada `activity_logs`.
3. **Logika Perhitungan Urgensi Kalender**:
   Tingkat urgensi dihitung murni berdasarkan selisih hari kalender (`Carbon::startOfDay()->diffInDays(...)`):
   - **Sisa hari > 7**: Status **AMAN** (Lencana Hijau Emerald).
   - **Sisa hari 3 – 7**: Status **MENDEKATI BATAS WAKTU** (Lencana Emas / Amber).
   - **Sisa hari 0 – 2**: Status **MENDESAK** (Lencana Oranye).
   - **Sisa hari < 0**: Status **TERLEWAT / PERLU PERHATIAN** (Lencana Merah Rose).
4. **Prinsip Non-Interference**:
   - Batas waktu yang telah terlewat (*overdue*) **TIDAK PERNAH** secara otomatis mengubah status proposal menjadi ditolak (*auto-reject*).
   - Batas waktu yang terlewat **TIDAK PERNAH** membatalkan proposal secara otomatis (*auto-cancel*).
   - Batas waktu yang terlewat **TIDAK MEMBATALKAN** keputusan yang telah diambil oleh Wakil Bupati.
   - Indikator deadline berfungsi murni sebagai sinyal visual prioritas operasional bagi Staf Administrasi dan Wakil Bupati untuk mengidentifikasi usulan yang membutuhkan perhatian administratif segera.

### 6.4 Status Siklus Hidup Proposal
Sistem mengelola lima status resmi siklus hidup proposal:
1. **DRAFT**: Usulan sedang disusun dan disimpan sementara oleh pengusul, belum diajukan secara resmi.
2. **DIAJUKAN**: Proposal telah diajukan secara resmi oleh pengusul. Memiliki dua sub-tahap administratif:
   - *Menunggu Disposisi*: Berkas menunggu pencatatan lembar disposisi fisik Pak Haziral oleh Staf Admin.
   - *Siap Ditelaah*: Disposisi fisik telah dicatat dan berkas berada dalam antrean meja kerja Wakil Bupati.
3. **PERLU PERBAIKAN**: Proposal dikembalikan oleh Wakil Bupati kepada pengusul disertai catatan instruksi perbaikan administrasi atau anggaran.
4. **DISETUJUI**: Proposal telah ditelaah dan memperoleh persetujuan resmi penuh dari Wakil Bupati.
5. **DITOLAK**: Proposal telah ditelaah dan dinyatakan tidak disetujui oleh Wakil Bupati.

### 6.5 Alur Pelacakan Mandiri Publik (Public Tracking)
1. **Akses Publik Tanpa Login**: Masyarakat umum dan pemohon dapat membuka menu pelacakan publik pada alamat web `/lacak` tanpa perlu membuat akun atau login.
2. **Pencarian Nomor Registrasi**: Pengguna memasukkan nomor registrasi resmi proposal (contoh: `PROP-202610-0001`) pada formulir pencarian.
3. **Penyajian Informasi Transparan 4-Tahap**:
   - Sistem menampilkan diagram linier 4 tahapan alur pelayanan administrasi:
     1. *Pengajuan Usulan* (Pemohon menyampaikan proposal)
     2. *Disposisi TU Pimpinan* (Verifikasi persuratan oleh Bagian Tata Usaha Pimpinan)
     3. *Telaah Wakil Bupati* (Penelaahan substantif oleh pimpinan daerah)
     4. *Penetapan Akhir* (Penerbitan keputusan resmi)
   - Informasi disajikan dengan bahasa aman publik (*citizen-safe language*), menampilkan nama pemohon/lembaga, perihal usulan, tanggal masuk, lencana status saat ini, nomor versi aktif, catatan resmi disposisi pimpinan, dan perkiraan waktu pelayanan tanpa mengekspos istilah teknis birokrasi internal yang sensitif.

---

## 7. RENCANA TAHAPAN PENGEMBANGAN SISTEM

Pengembangan sistem SIPPRO MURA dilaksanakan mulai tanggal **21 September 2026** hingga tahap **Serah Terima Produk** pada tanggal **19 November 2026** (tepat 2 minggu sebelum penarikan magang pada tanggal **3 Desember 2026**). Sisa 2 minggu terakhir dialokasikan untuk pendampingan operasional staf Setda dan penyusunan Laporan Akhir Magang.

Rincian jadwal tahapan kerja terealisasi sebagai berikut:

| Periode Waktu | Tahapan Kegiatan | Rincian Aktivitas & Hasil yang Diharapkan (Output) |
| :--- | :--- | :--- |
| **Pekan 1**<br>(21 – 27 Sep 2026) | **Inisialisasi & Fondasi Sistem** | • Persiapan lingkungan kerja framework Laravel 12 dan basis data MySQL.<br>• Pembuatan struktur tabel database relasional, relasi model Eloquent, dan seeding peran pengguna.<br>• Pembuatan template dasar antarmuka web responsif bertema dark-gold Setda. |
| **Pekan 2 – 3**<br>(28 Sep – 11 Okt 2026) | **Pengembangan Portal Pengusul** | • Pembuatan modul registrasi akun minimalis, login, profil, dan data rekening penyaluran bank.<br>• Pembuatan formulir usulan proposal terstruktur, format dinamis nominal, dan kalkulator baris item RAB.<br>• Pembuatan fitur unggah berkas proposal PDF dan slot berkas lampiran pendukung fisik. |
| **Pekan 4 – 5**<br>(12 – 25 Okt 2026) | **Checklist Ketelitian & Dasbor Pimpinan** | • Pembuatan dialog modal Checklist Ketelitian Mandiri (Anti-Revisi) dengan ringkasan berkas otomatis.<br>• Pembuatan dasbor penelaahan Wakil Bupati yang ramah sentuhan tablet dan smartphone.<br>• Integrasi penampil dokumen PDF responsif langsung di peramban web (*PDF Viewer*). |
| **Pekan 6 – 7**<br>(26 Okt – 8 Nov 2026) | **Disposisi TU, Manajemen Deadline, & Versioning** | • Pembuatan modul pencatatan disposisi fisik TU Pimpinan (Pak Haziral) sebagai *gatekeeper* telaah Wabup.<br>• Penerapan aturan disposisi ulang (*repeat disposition*) pada setiap versi revisi baru proposal.<br>• Pembuatan modul manajemen batas waktu (*deadline*) proposal, indikator urgensi kalender, dan audit trail.<br>• Pembuatan formulir perbaikan proposal, pencatatan versi (v1.0 ke v2.0), dan modul Public Tracking 4-tahap. |
| **Pekan 8 – 9**<br>(9 – 19 Nov 2026) | **Uji Coba Sistem & Serah Terima Produk** | • Pengujian otomatis menyeluruh (58 tests PHPUnit / 329 assertions 100% LULUS).<br>• Pengujian penerimaan pengguna (UAT) bersama staf Bagian Administrasi Pimpinan Setda.<br>• **Serah Terima Produk SIPPRO MURA resmi pada 19 November 2026** (2 pekan sebelum penarikan magang). |
| **Pekan 10 – 11**<br>(20 Nov – 3 Des 2026) | **Pendampingan & Laporan Akhir Magang** | • Pendampingan operasional penggunaan sistem bagi staf administrasi persuratan Setda.<br>• Penyusunan buku petunjuk penggunaan sistem (*User Manual*).<br>• Penyusunan dan penyempurnaan Laporan Akhir Magang D3 Teknik Informatika hingga penarikan magang pada 3 Desember 2026. |

---

## 8. PENGUJIAN DAN KRITERIA PENERIMAAN SISTEM

Pengujian sistem dilakukan secara otomatis menggunakan rangkaian pengujian fitur (*Feature Tests*) dan unit (*Unit Tests*) framework PHPUnit. Seluruh **58 skenario pengujian dengan 329 asersi telah lolos 100% (PASS)** tanpa kegagalan:

| No | Modul yang Diuji | Skenario Pengujian | Hasil yang Diharapkan & Aktual | Status |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Pendaftaran & Login | Pengusul mendaftar akun baru dan login dengan kredensial yang valid. | Akun terdaftar, sesi terbentuk, dan diarahkan ke dasbor pengusul. | **LULUS** |
| 2 | Quick Login Role | Pengguna memilih opsi Quick Login untuk peran Pengusul, Staf Admin, atau Wabup. | Sistem mengautentikasi pengguna secara instan sesuai peran yang dipilih. | **LULUS** |
| 3 | Input Form & Anggaran | Pengusul mengisi perihal usulan dan anggaran dengan format rupiah dinamis. | Sistem memformat tampilan secara dinamis dan menyimpan nilai numerik bersih. | **LULUS** |
| 4 | Kalkulator Tabel RAB | Pengusul menambahkan beberapa baris uraian belanja, volume, satuan, dan harga. | Sistem menghitung subtotal tiap baris dan total anggaran secara akurat dalam format JSON. | **LULUS** |
| 5 | Unggah Berkas & Validasi | Pengusul mengunggah file PDF proposal utama dan lampiran pendukung fisik. | Berkas tersimpan di disk storage publik dan tautan pratinjau dokumen berfungsi. | **LULUS** |
| 6 | Modal Ketelitian Mandiri | Pengusul mencoba mengirimkan proposal sebelum mencentang pakta ketelitian. | Tombol kirim tetap tidak aktif (*disabled*) hingga pakta dicentang oleh pengusul. | **LULUS** |
| 7 | Registrasi & Nomor Unik | Pengusul menyelesaikan checklist ketelitian dan menekan tombol kirim usulan. | Proposal berstatus *Diajukan*, nomor registrasi `PROP-YYYYMM-XXXX` terbit, dan versi 1.0 terbentuk. | **LULUS** |
| 8 | Pembaca PDF Web | Pengguna atau pimpinan membuka detail dokumen proposal di layar aplikasi. | Dokumen PDF tertampil langsung di peramban web melalui embedded iframe responsif. | **LULUS** |
| 9 | Pencatatan Disposisi TU | Staf Admin mencatat disposisi fisik Pak Haziral beserta upload berkas scan bukti. | Data tersimpan di tabel `dispositions`, terikat ke versi 1, dan status disposisi menjadi *selesai*. | **LULUS** |
| 10 | Gatekeeper Disposisi | Wabup mencoba menelaah proposal yang belum memiliki disposisi TU Pimpinan. | Akses telaah diblokir oleh sistem dengan respon kode keamanan `403 Forbidden`. | **LULUS** |
| 11 | Pembukaan Akses Telaah | Wabup menelaah proposal setelah Staf Admin menyelesaikan pencatatan disposisi TU. | Formulir telaah terbuka sukses dan Wabup dapat membaca berkas serta bukti disposisi. | **LULUS** |
| 12 | Otorisasi Keputusan Wabup | Staf Admin atau Pengusul mencoba mengeksekusi route keputusan telaah proposal. | Akses ditolak mutlak dengan respon `403 Forbidden` (wewenang eksklusif Wakil Bupati). | **LULUS** |
| 13 | Keputusan Persetujuan Wabup | Wakil Bupati memilih opsi persetujuan dan menyimpan catatan instruksi pimpinan. | Status proposal diperbarui menjadi *Disetujui* dan keputusan tersimpan di `review_decisions`. | **LULUS** |
| 14 | Keputusan Permintaan Revisi | Wakil Bupati memilih opsi *Perlu Perbaikan* dan menuliskan catatan arahan wajib. | Status proposal diperbarui menjadi *Perlu Perbaikan* dan instruksi tersimpan permanen. | **LULUS** |
| 15 | Formulir Perbaikan Revisi | Pengusul membuka halaman revisi, mengunggah revisi PDF, dan memperbarui rincian RAB. | Sistem menerbitkan Versi 2.0 (v2.0) tanpa mengubah nomor registrasi induk proposal. | **LULUS** |
| 16 | Duplikasi Lampiran Otomatis | Sistem memproses revisi v2.0 dari proposal yang memiliki berkas lampiran pendukung. | Berkas lampiran identitas dari v1.0 otomatis diduplikasi ke v2.0 tanpa upload ulang. | **LULUS** |
| 17 | Riwayat Multi-Versi Dokumen| Pengguna membuka arsip versi lama proposal melalui parameter `?version=1`. | Sistem menampilkan data versi 1 tanpa merusak data versi 2 yang sedang aktif. | **LULUS** |
| 18 | Kewajiban Disposisi Ulang | Wabup mencoba menelaah proposal versi 2.0 sebelum versi 2.0 didisposisi ulang oleh TU. | Akses telaah versi 2 diblokir dengan respon `403 Forbidden` hingga disposisi ulang dicatat. | **LULUS** |
| 19 | Pencatatan Disposisi Ulang | Staf Admin mencatat lembar disposisi fisik baru khusus untuk versi 2.0 proposal. | Disposisi versi 2 tersimpan, dan akses telaah Wabup untuk versi 2 resmi terbuka kembali. | **LULUS** |
| 20 | Penetapan Deadline Admin | Staf Admin menginput tanggal batas waktu tindak lanjut saat mencatat disposisi. | Field `proposals.batas_waktu` tersimpan dengan benar dan tercatat di `activity_logs`. | **LULUS** |
| 21 | Pembaruan Deadline Mandiri | Staf Admin memperbarui tanggal batas waktu melalui route `PATCH /proposals/{id}/batas-waktu`.| Batas waktu terbarui sukses dan audit trail mencatat riwayat perubahan tanggal. | **LULUS** |
| 22 | Proteksi Akses Deadline | Pengusul mencoba mengakses atau mengubah tanggal batas waktu proposal. | Sistem menolak akses dengan respon `403 Forbidden` (hak kelola eksklusif Admin). | **LULUS** |
| 23 | Immutabilitas Deadline Revisi | Pengusul mengajukan revisi baru dari versi 1.0 ke versi 2.0 pada proposal ber-deadline. | Nilai `batas_waktu` tetap persisten dan tidak mengalami reset saat resubmit revisi. | **LULUS** |
| 24 | Indikator Urgensi (>7 hari) | Proposal memiliki sisa hari batas waktu lebih dari 7 hari kalender. | Sistem menetapkan status *AMAN* dengan lencana berwarna hijau emerald. | **LULUS** |
| 25 | Indikator Urgensi (3-7 hari)| Proposal memiliki sisa hari batas waktu antara 3 sampai 7 hari kalender. | Sistem menetapkan status *MENDEKATI BATAS WAKTU* dengan lencana emas/amber. | **LULUS** |
| 26 | Indikator Urgensi (0-2 hari)| Proposal memiliki sisa hari batas waktu antara 0 sampai 2 hari kalender. | Sistem menetapkan status *MENDESAK* dengan lencana berwarna oranye. | **LULUS** |
| 27 | Indikator Urgensi (<0 hari) | Proposal melewati batas waktu tindak lanjut (sisa hari kalender negatif). | Sistem menetapkan status *TERLEWAT / PERLU PERHATIAN* dengan lencana merah rose. | **LULUS** |
| 28 | Non-Interference Deadline | Batas waktu proposal terlewat (*overdue*) saat proposal berada pada status diajukan. | Status proposal tetap *Diajukan* tanpa auto-reject dan tanpa auto-cancel oleh sistem. | **LULUS** |
| 29 | Pelacakan Publik Mandiri | Pengguna publik memasukkan nomor registrasi pada route `/lacak` tanpa login. | Halaman menampilkan status 4-tahap, perihal, dan catatan pimpinan dengan bahasa aman publik. | **LULUS** |
| 30 | Profil & Rekening Penyaluran| Pengusul memperbarui profil pemohon, jenis pemohon, dan nomor rekening bank. | Data tersimpan rapi pada tabel `profiles` dan memunculkan notifikasi sukses auto-dismiss. | **LULUS** |

---

> Dokumen Spesifikasi Kebutuhan Sistem (PRD) ini telah disinkronkan secara resmi dan presisi dengan implementasi aktual sistem SIPPRO MURA setelah commit `92def8f` sebagai acuan tunggal pelaksanaan operasional dan penyusunan Laporan Akhir Magang Program Studi D3 Teknik Informatika, Jurusan Teknik Elektro, Politeknik Negeri Banjarmasin, bekerja sama dengan Bagian Pelayanan Administrasi Pimpinan Sekretariat Daerah Kabupaten Murung Raya.

