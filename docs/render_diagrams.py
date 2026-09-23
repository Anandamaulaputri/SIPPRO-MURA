import os
import subprocess
import re
import time

edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

diagrams = {
    "diag_kpi": '''pie title Sasaran Efisiensi Operasional SIPPRO
    "Penurunan Angka Revisi Berulang (<= 1 kali)" : 40
    "Percepatan Waktu Penilaian (SLA < 3 Hari)" : 30
    "Kepuasan Akses Transparansi Pengusul (100%)" : 20
    "Eliminasi Kehilangan Berkas Fisik (0 Kasus)" : 10''',

    "diag_modul": '''graph LR
    subgraph PortalPengusul ["Portal Pengusul"]
        FR01[FR-01: Autentikasi]
        FR02[FR-02: Form Proposal]
        FR03[FR-03: Upload Dokumen]
        FR04[FR-04: Checklist Ketelitian]
        FR08[FR-08: Revisi & Resubmit]
    end

    subgraph PortalEksekutif ["Portal Wakil Bupati"]
        FR05[FR-05: Executive Dashboard]
        FR06[FR-06: PDF Viewer]
        FR07[FR-07: Eksekusi Keputusan]
    end

    subgraph EngineSistem ["Sistem & Kearsipan"]
        FR09[FR-09: Versioning Engine]
        FR10[FR-10: Multi-Channel Notif]
        FR11[FR-11: Cetak Disposisi]
        FR12[FR-12: Audit Trail]
    end

    FR04 --> FR05
    FR05 --> FR06
    FR06 --> FR07
    FR07 --> FR08
    FR08 --> FR09
    FR07 --> FR10
    FR07 --> FR11''',

    "diag_arsitektur": '''flowchart TD
    subgraph ClientLayer ["1. Presentation Layer (Klien)"]
        A1[Pengusul Browser / Smartphone]
        A2[Wakil Bupati Tablet / Mobile]
        A3[Staf TU / Admin Desktop]
    end

    subgraph GatewayLayer ["2. Security & Web Server"]
        B1[Reverse Proxy / Nginx + SSL TLS 1.3]
        B2[Firewall / Rate Limiter / CORS Protection]
    end

    subgraph AppLayer ["3. Application / Business Logic Layer"]
        C1[Auth & RBAC Service]
        C2[Proposal Submission & Validation Engine]
        C3[Checklist Gatekeeper Engine]
        C4[Executive Review & Triage Engine]
        C5[Versioning & Audit Log Service]
        C6[Notification Dispatcher]
        C7[In-App PDF Viewer Engine]
    end

    subgraph DataLayer ["4. Persistence & Storage Layer"]
        D1[(Relational Database: PostgreSQL / MySQL)]
        D2[(Object File Storage: MinIO / AWS S3 / Local Secure)]
        D3[(Cache / Job Queue: Redis)]
    end

    subgraph ExternalLayer ["5. External Services"]
        E1[WhatsApp Gateway API]
        E2[SMTP Email Server]
    end

    A1 & A2 & A3 --> B1
    B1 --> B2
    B2 --> C1 & C2 & C3 & C4 & C5 & C6 & C7
    C1 & C2 & C4 & C5 & C6 --> D1
    C2 & C5 & C7 --> D2
    C6 --> D3
    D3 --> E1 & E2''',

    "diag_seq_submit": '''sequenceDiagram
    autonumber
    actor Pengusul
    participant Web as Antarmuka SIPPRO
    participant Gatekeeper as Modul Checklist Ketelitian
    participant Server as Backend API & DB
    actor Wabup as Wakil Bupati

    Pengusul->>Web: Isi Form Usulan, Kategori, & Tabel RAB
    Pengusul->>Web: Unggah File Proposal (PDF) & Berkas Pendukung
    Pengusul->>Web: Klik Tombol "Lanjut ke Pengiriman"
    Web->>Gatekeeper: Buka Modal Verifikasi Ketelitian Mandiri
    Note over Pengusul,Gatekeeper: Pengusul wajib memeriksa & mencentang 6 butir verifikasi mandiri
    Pengusul->>Gatekeeper: Centang Seluruh 6 Butir Checklist Integritas
    Gatekeeper-->>Web: Buka Kunci Tombol "Kirim Proposal Resmi"
    Pengusul->>Web: Klik "Kirim Proposal Resmi"
    Web->>Server: Simpan Data Proposal, Berkas, & Catatan Versi 1.0
    Server-->>Pengusul: Tampilkan Nomor Registrasi & Kirim Notif WhatsApp
    Server-->>Wabup: Kirim Notifikasi Proposal Masuk ke Dashboard''',

    "diag_seq_review": '''sequenceDiagram
    autonumber
    actor Wabup as Wakil Bupati
    participant Dash as Executive Dashboard
    participant Viewer as In-App PDF Viewer
    participant Engine as Decision Engine
    actor Pengusul as Pengusul

    Wabup->>Dash: Login ke Portal Eksekutif (Tablet / Ponsel)
    Dash-->>Wabup: Menampilkan Daftar Antrean Proposal Masuk
    Wabup->>Dash: Klik Kartu Proposal untuk Membuka Detail
    Dash->>Viewer: Buka Dokumen Proposal PDF Secara Langsung di Layar
    Wabup->>Viewer: Telaah Profil, Anggaran RAB, dan Berkas Pendukung
    alt Keputusan: DISETUJUI
        Wabup->>Engine: Klik Tombol Hijau "DISETUJUI" + Catatan Apresiasi
        Engine->>Engine: Terbitkan Nomor Disposisi & QR Code Validasi
    else Keputusan: PERLU PERBAIKAN
        Wabup->>Engine: Klik Tombol Kuning "PERLU PERBAIKAN" + Input Catatan Revisi Wajib
        Engine->>Engine: Ubah Status Menjadi NEEDS_REVISION & Buka Izin Edit Pengusul
    else Keputusan: TIDAK DISETUJUI
        Wabup->>Engine: Klik Tombol Merah "TIDAK DISETUJUI" + Input Alasan Penolakan
        Engine->>Engine: Ubah Status Menjadi REJECTED & Kunci Proposal Permanen
    end
    Engine-->>Pengusul: Kirim Notifikasi WhatsApp & Email Real-Time Hasil Keputusan''',

    "diag_status": '''stateDiagram-v2
    [*] --> DRAFT : Pengusul Membuat Proposal
    DRAFT --> DIAJUKAN : Lolos Checklist Ketelitian Mandiri (v1.0)
    DIAJUKAN --> DISETUJUI : Disetujui oleh Wakil Bupati
    DIAJUKAN --> DITOLAK : Ditolak oleh Wakil Bupati
    DIAJUKAN --> PERLU_PERBAIKAN : Wakil Bupati Meminta Perbaikan + Catatan
    PERLU_PERBAIKAN --> DIAJUKAN : Pengusul Resubmit Berkas Revisi (v2.0)
    DISETUJUI --> SELESAI : Terbit Lembar Disposisi Resmi
    DITOLAK --> [*] : Berkas Diarsipkan
    SELESAI --> [*] : Proses Selesai'''
}

html_template = """<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
mermaid.initialize({{
    startOnLoad: true,
    theme: 'base',
    themeVariables: {{
        primaryColor: '#1E3A8A',
        primaryTextColor: '#FFFFFF',
        primaryBorderColor: '#1E3A8A',
        lineColor: '#2563EB',
        secondaryColor: '#F1F5F9',
        tertiaryColor: '#FFFFFF'
    }}
}});
</script>
<style>
  body {{
      background: white;
      margin: 0;
      padding: 20px;
      display: inline-block;
  }}
  .mermaid {{
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }}
</style>
</head>
<body>
<div class="mermaid">
{content}
</div>
</body>
</html>"""

os.makedirs("c:/SIPPRO/diagrams", exist_ok=True)

for name, code in diagrams.items():
    html_file = f"c:/SIPPRO/diagrams/{name}.html"
    png_file = f"c:/SIPPRO/diagrams/{name}.png"
    
    with open(html_file, "w", encoding="utf-8") as f:
        f.write(html_template.format(content=code))
    
    cmd = [
        edge_path,
        "--headless",
        f"--screenshot={png_file}",
        "--window-size=1200,900",
        f"file:///{os.path.abspath(html_file).replace(os.sep, '/')}"
    ]
    subprocess.run(cmd, check=True)
    print(f"Rendered: {png_file}")

print("All diagrams rendered successfully!")
