# ERP RPL - Sistem ERP untuk Program Studi RPL UAD

<p align="center">
<a href="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/laravel.yml"><img src="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/laravel.yml/badge.svg" alt="Laravel CI/CD"></a>
<a href="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/security.yml"><img src="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/security.yml/badge.svg" alt="Security Scan"></a>
<a href="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/coverage.yml"><img src="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/coverage.yml/badge.svg" alt="Code Coverage"></a>
<a href="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/docker.yml"><img src="https://github.com/ardiansyah-sweng/erp_rpl/actions/workflows/docker.yml/badge.svg" alt="Docker Build"></a>
<a href="https://github.com/ardiansyah-sweng/erp_rpl/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
</p>

## 🏛️ Tentang ERP RPL

ERP RPL adalah proyek mata kuliah Rekayasa Perangkat Lunak (RPL) di Prodi S1 Informatika Universitas Ahmad Dahlan (UAD). ERP merupakan singkatan dari Enterprise Resources Planning. Dari sudut pandang software, ERP adalah sistem perangkat lunak yang dirancang untuk mengintegrasikan berbagai aspek operasional bisnis, seperti:

- Keuangan (Akuntansi, pengelolaan kas, dll).
- Sumber daya manusia (pengelolaan karyawan, gaji, dll).
- Pengadaan (pengelolaan pembelian, inventori, dll).
- Produksi (pengelolaan produksi, kualitas, dll).
- Penjualan (pengelolaan penjualan, pemasaran, dll).

## 🛢️ Database

Proyek ini menggunakan arsitektur **Database-as-Code**. Semua perubahan struktur tabel wajib ditulis di file `database\schema.dbml` sebelum diadopsi ke Laravel Migrations.

## 🚀 Cara Memulai (Local Setup)

1. Clone repositori ini dan masuk ke folder proyek.
2. Jalankan instalasi dependency inti:
   ```bash
   composer install
   ```
3. Jalankan perintah otomatisasi ekosistem database (termasuk instalasi dbdiagram CLI):
   ```bash
   composer db-setup
   ```
4. Lakukan autentikasi akun dbdiagram Anda ke server pusat (Cukup sekali):
   ```bash
   dbdiagram auth login
   ```

## 🔄 Alur Kerja Pembaruan Database (Workflow)

Jika Anda mendapatkan tugas untuk memodifikasi atau menambah tabel baru:
- Jangan edit database via GUI (DBeaver/phpMyAdmin).
- Buka file `database\schema.dbml` di VS Code, lakukan modifikasi skema.
- Jalankan perintah ini di terminal untuk melihat pratinjau visualnya langsung di browser:
  ```bash
  dbdiagram push
  ```
- Setelah desain disetujui oleh Lead Architect, buat file Laravel Migration baru yang sesuai dengan kode DBML tersebut.


## ⏱️TIMELINE
⬆️ 2026
Pengembangan fitur utama

⬆️ 2025 
Pengembangan fase awal dengan fokus pada modul: master data (produk, item, supplier, branch)
