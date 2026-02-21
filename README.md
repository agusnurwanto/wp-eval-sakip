# WP EVALUASI SAKIP

**WP EVALUASI SAKIP** adalah plugin WordPress yang dirancang khusus untuk mendukung aplikasi Evaluasi SAKIP (Sistem Akuntabilitas Kinerja Instansi Pemerintah). Plugin ini memudahkan pengelolaan dokumen, penilaian kinerja, dan pemantauan akuntabilitas kinerja instansi pemerintah secara terintegrasi di platform WordPress.

## ✨ Fitur Utama

- **Manajemen Dokumen SAKIP**: Pengelolaan dokumen mulai dari RPJPD, RPJMD, Renstra, IKU, RKPD, PK, hingga Laporan Kinerja.
- **Pohon Kinerja & Cascading**: Visualisasi sistematis keterkaitan kinerja antar unit kerja.
- **Evaluasi Internal & Monev**: Fasilitas input dan penilaian Lembar Kerja Evaluasi (LKE) secara transparan.
- **Manajemen Unit Kerja (SKPD)**: Pemetaan data unit kerja dan struktur organisasi.
- **Integrasi User & Role**: Pengaturan hak akses khusus untuk Admin Bappeda, Ortala, Inspektorat, dan Perangkat Daerah.
- **Keamanan Data**: Menggunakan framework Carbon Fields untuk pengaturan yang aman dan terstruktur.

## 📋 Persyaratan Sistem

Untuk menjalankan plugin ini dengan optimal, pastikan lingkungan Anda memenuhi kriteria berikut:

- **WordPress**: Versi 6.2 atau lebih tinggi.
- **PHP**: Versi 7.0 atau lebih tinggi.
- **Tema Utama**: [Astra Theme](https://wpastra.com/) (v4.10.1+) diperlukan untuk kompatibilitas tata letak.
- **Plugin Pendukung**: [Ultimate Member](https://wordpress.org/plugins/ultimate-member/) (v2.10.5+) diperlukan untuk manajemen profil dan akses pengguna.

> [!NOTE]
> Semua pustaka (libraries) yang diperlukan sudah termasuk di dalam plugin ini, sehingga tidak perlu menginstal Composer secara manual.

## 🚀 Instalasi

1.  **Instal Tema & Plugin Pendukung**: Pastikan **Astra Theme** dan plugin **Ultimate Member** sudah terpasang dan aktif.
2.  **Unduh Plugin**: Dapatkan paket plugin WP EVALUASI SAKIP.
3.  **Upload & Aktifkan**: Buka Dashboard WordPress > Plugin > Tambah Baru > Unggah Plugin. Setelah selesai, klik **Aktifkan**.
4.  **Inisialisasi Database**: Plugin akan otomatis membuat tabel-tabel yang diperlukan (prefix `esakip_`) saat aktivasi.

## 🛠️ Penggunaan & Konfigurasi

1.  Buka menu **Evaluasi SAKIP** di sidebar admin WordPress.
2.  Lakukan konfigurasi awal melalui tab **Pengaturan** (API Key, Nama Pemda, dll).
3.  Gunakan shortcode yang tersedia untuk menampilkan fitur di sisi publik situs.

## 💬 Dukungan & Komunitas

### Grup Koordinasi
Bergabunglah dengan komunitas untuk diskusi dan koordinasi teknis:
- **Telegram**: [Grup Koordinasi SAKIP](https://t.me/sipd_chrome_extension/20969)

### Donasi
Dukung pengembangan plugin ini agar terus berkelanjutan:
- [Donasi Pengembangan SMK Asiyah](https://smkasiyahhomeschooling.blogspot.com/p/donasi-pengembangan-smk-asiyah.html)
- [Qodr Donation](https://donasi.qodr.or.id/)

## 🤝 Kontribusi & Tutorial

- **Permintaan Fitur**: Pengguna umum dapat mengajukan penambahan fitur dengan membuat [Issue](https://github.com/agusnurwanto/wp-eval-sakip/issues) di repositori ini.
- **Video Tutorial**: Pelajari cara penggunaan lengkap di [Video Tutorial](...).

## 📜 Lisensi

Plugin ini dilisensikan di bawah [GPL-2.0+](LICENSE.txt).