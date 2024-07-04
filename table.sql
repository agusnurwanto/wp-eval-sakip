CREATE TABLE `esakip_achievement` (
  `id` int(11) NOT NULL auto_increment,
  `tahun` varchar(255) DEFAULT NULL,
  `predikat` varchar(255) DEFAULT NULL,
  `nilai` varchar(255) DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_dokumen_lainnya` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_evaluasi_internal` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_iku` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_laporan_kinerja` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_lhe_opd` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_lkjip_lppd` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_other_file` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pengukuran_kinerja` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pengukuran_rencana_aksi` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_perjanjian_kinerja` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rencana_aksi` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_renja_rkt` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_renstra` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rkpd` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rpjmd` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_skp` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_data_unit` (
  `id` int(11) NOT NULL auto_increment,
  `id_setup_unit` int(11) DEFAULT NULL,
  `id_unit` int(11) DEFAULT NULL,
  `is_skpd` tinyint(4) DEFAULT NULL,
  `kode_skpd` varchar(50) DEFAULT NULL,
  `kunci_skpd` int(11) DEFAULT NULL,
  `nama_skpd` text DEFAULT NULL,
  `posisi` varchar(30) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `bidur_1` smallint(6) DEFAULT NULL,
  `bidur_2` smallint(6) DEFAULT NULL,
  `bidur_3` smallint(6) DEFAULT NULL,
  `idinduk` int(11) DEFAULT NULL,
  `ispendapatan` tinyint(4) DEFAULT NULL,
  `isskpd` tinyint(4) DEFAULT NULL,
  `kode_skpd_1` varchar(10) DEFAULT NULL,
  `kode_skpd_2` varchar(10) DEFAULT NULL,
  `kodeunit` varchar(30) DEFAULT NULL,
  `komisi` int(11) DEFAULT NULL,
  `namabendahara` text,
  `namakepala` text DEFAULT NULL,
  `namaunit` text DEFAULT NULL,
  `nipbendahara` varchar(30) DEFAULT NULL,
  `nipkepala` varchar(30) DEFAULT NULL,
  `pangkatkepala` varchar(50) DEFAULT NULL,
  `setupunit` int(11) DEFAULT NULL,
  `statuskepala` varchar(20) DEFAULT NULL,
  `mapping` varchar(10) DEFAULT NULL,
  `id_kecamatan` int(11) DEFAULT NULL,
  `id_strategi` int(11) DEFAULT NULL,
  `is_dpa_khusus` tinyint(4) DEFAULT NULL,
  `is_ppkd` tinyint(4) DEFAULT NULL,
  `set_input` tinyint(4) DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `tahun_anggaran` year(4) NOT NULL DEFAULT '2021',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_data_jadwal` (
  `id` int(11) NOT NULL auto_increment,
  `nama_jadwal` varchar(64) DEFAULT NULL,
  `nama_jadwal_renstra` varchar(64) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 = HAPUS, 1 = ACTIVE, 2 = DIKUNCI',
  `jenis_jadwal` varchar(30) DEFAULT NULL,
  `tipe` varchar(30) DEFAULT NULL COMMENT 'RPJMD, LKE',
  `lama_pelaksanaan` int(11) DEFAULT NULL,
  `tampil_nilai_penetapan` int(11) NOT NULL DEFAULT '1' COMMENT '1 Tampil, 0 Tidak Tampil',
  `tahun_anggaran` year(4) NOT NULL DEFAULT '2022',
  `default_verifikasi_upload` tinyint(4) DEFAULT NULL,
  `relasi_perencanaan` int(11) DEFAULT NULL,
  `tahun_selesai_anggaran` year(4) DEFAULT NULL,
  `jenis_jadwal_khusus` varchar(30) DEFAULT NULL COMMENT 'RPJMD, RPD',
  PRIMARY KEY  (id)
);

CREATE TABLE esakip_komponen (
  `id` int(11) NOT NULL auto_increment,
  `id_jadwal` int(11) NOT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `id_user_penilai` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `bobot` float DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_subkomponen (
  `id` int(11) NOT NULL auto_increment,
  `id_komponen` int(11) NOT NULL,
  `id_user_penilai` int(11) DEFAULT NULL,
  `metode_penilaian` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Rata-Rata, 2 = Nilai Dinamis',
  `nama` varchar(255) NOT NULL,
  `bobot` float NOT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_komponen_penilaian (
  `id` int(11) NOT NULL auto_increment,
  `id_subkomponen` int(11) NOT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `bobot` float DEFAULT NULL,
  `tipe` varchar(30) DEFAULT NULL COMMENT '1 = Ya/Tidak, 2 = A/B/C/D, 3 =Custom',
  `keterangan` varchar(255) DEFAULT NULL,
  `jenis_bukti_dukung` text DEFAULT NULL,
  `penjelasan` text DEFAULT NULL,
  `langkah_kerja` text DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_penilaian_custom (
  `id` int(11) NOT NULL auto_increment,
  `id_komponen_penilaian` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nilai` decimal(5, 2) DEFAULT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_penilaian_custom_history (
  `id` int(11) NOT NULL auto_increment,
  `id_komponen_penilaian` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nilai` decimal(5, 2) DEFAULT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `id_asli` int(11) DEFAULT NULL,
  `id_jadwal` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_pengisian_lke (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(11) DEFAULT NULL,
  `id_skpd` int(11) NOT NULL,
  `id_user_penilai` int(11) DEFAULT NULL,
  `id_komponen` int(11) NOT NULL,
  `id_subkomponen` int(11) NOT NULL,
  `id_komponen_penilaian` int(11) NOT NULL,
  `nilai_usulan` decimal(5, 2) DEFAULT NULL,
  `nilai_penetapan` decimal(5, 2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keterangan_penilai` text DEFAULT NULL,
  `bukti_dukung` text DEFAULT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp(),
  `tahun_anggaran` year(4) DEFAULT null,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_komponen_history (
  `id` int(11) NOT NULL auto_increment,
  `id_jadwal` int(11) NOT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `id_user_penilai` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `bobot` float DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `id_asli` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_subkomponen_history (
  `id` int(11) NOT NULL auto_increment,
  `id_komponen` int(11) NOT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `id_user_penilai` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `bobot` float DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `metode_penilaian` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Rata-Rata, 2 = Nilai Dinamis',
  `id_asli` int(11) DEFAULT NULL,
  `id_jadwal` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_komponen_penilaian_history (
  `id` int(11) NOT NULL auto_increment,
  `id_subkomponen` int(11) NOT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tipe` varchar(30) DEFAULT NULL COMMENT '1 = Ya/Tidak, 2 = A/B/C/D',
  `keterangan` varchar(255) DEFAULT NULL,
  `jenis_bukti_dukung` text DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `bobot` float DEFAULT NULL,
  `penjelasan` text DEFAULT NULL,
  `langkah_kerja` text DEFAULT NULL,
  `id_asli` int(11) DEFAULT NULL,
  `id_jadwal` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_pengisian_lke_history (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(11) DEFAULT NULL,
  `id_skpd` int(11) NOT NULL,
  `id_user_penilai` int(11) DEFAULT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_asli` int(11) NOT NULL,
  `id_komponen` int(11) NOT NULL,
  `id_subkomponen` int(11) NOT NULL,
  `id_komponen_penilaian` int(11) NOT NULL,
  `nilai_usulan` decimal(5, 2) DEFAULT NULL,
  `nilai_penetapan` decimal(5, 2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keterangan_penilai` text DEFAULT NULL,
  `bukti_dukung` text DEFAULT NULL,
  `id_asli` int(11) DEFAULT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp(),
  `tahun_anggaran` year(4) DEFAULT null,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `esakip_rpjpd` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(`id`)
);
 
CREATE TABLE esakip_kontrol_kerangka_logis (
 `id` int(11) NOT NULL auto_increment,
 `id_komponen_penilaian` int(11) NOT NULL,
 `jenis_kerangka_logis` int(11) DEFAULT NULL COMMENT '1 = Rata-Rata, 2 = Nilai',
 `id_komponen_pembanding` int(11) DEFAULT NULL,
 `pesan_kesalahan` varchar(255) DEFAULT NULL,
 `active` tinyint(4) NOT NULL DEFAULT 1,
 PRIMARY KEY  (`id`)
);

CREATE TABLE esakip_kontrol_kerangka_logis_history (
 `id` int(11) NOT NULL auto_increment,
 `id_komponen_penilaian` int(11) NOT NULL,
 `jenis_kerangka_logis` int(11) DEFAULT NULL COMMENT '1 = Rata-Rata, 2 = Nilai',
 `id_komponen_pembanding` int(11) DEFAULT NULL,
 `pesan_kesalahan` varchar(255) DEFAULT NULL,
 `id_jadwal` int(11) DEFAULT NULL,
 `id_asli` int(11) DEFAULT NULL,
 PRIMARY KEY  (`id`)
);

CREATE TABLE `esakip_pohon_kinerja_dan_cascading` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `id_jadwal` int(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_lhe_akip_internal` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_tl_lhe_akip_internal` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_tl_lhe_akip_kemenpan` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_laporan_monev_renaksi` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pedoman_teknis_perencanaan` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pedoman_teknis_pengukuran_dan_pengumpulan_data_kinerja` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_keterangan_verifikator` (
  `id` int NOT NULL auto_increment,
  `id_dokumen` int DEFAULT NULL,
  `status_verifikasi` tinyint DEFAULT NULL COMMENT '0=menunggu,1=disetujui,2=ditolak',
  `keterangan_verifikasi` text,
  `nama_tabel_dokumen` varchar(64) DEFAULT NULL,
  `active` tinyint DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_skpd` int DEFAULT NULL,
  `tahun_anggaran` year DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pedoman_teknis_evaluasi_internal` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_dpa` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pohon_kinerja` (
  `id` int(11) NOT NULL auto_increment,
  `label` varchar(255) NOT NULL,
  `parent` int(11) DEFAULT 0,
  `label_indikator_kinerja` varchar(255) DEFAULT null,
  `level` int(11) NOT null,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp(),
  PRIMARY key (id)
);

CREATE TABLE `esakip_pohon_kinerja_opd` (
  `id` int(11) NOT NULL auto_increment,
  `label` varchar(255) NOT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT 0,
  `label_indikator_kinerja` varchar(255) DEFAULT null,
  `level` int(11) NOT null,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp(),
  PRIMARY key (id)
);

CREATE TABLE `esakip_menu_dokumen` (
  `id` int(11) NOT NULL auto_increment,
  `nama_tabel` varchar(255) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `jenis_role` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `nomor_urut` DECIMAL(10,2) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `tahun_anggaran` year NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp,
  `id_jadwal` int(11) DEFAULT NULL,
  PRIMARY key (id)
);
  `verifikasi_upload_dokumen` tinyint DEFAULT NULL,

CREATE TABLE `esakip_dokumen_lainnya_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_iku_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_laporan_kinerja_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_lkjip_lppd_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_perjanjian_kinerja_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rencana_aksi_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rkpd_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_lhe_akip_internal_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_tl_lhe_akip_internal_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_tl_lhe_akip_kemenpan_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_laporan_monev_renaksi_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pedoman_teknis_perencanaan_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pedoman_teknis_pengukuran_dan_p_d_k_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pedoman_teknis_evaluasi_internal_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_dpa_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_pohon_kinerja_dan_cascading_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` varchar(50) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
  `id_jadwal` int(4) DEFAULT NULL,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rpjpd_visi` (
  `id` int(11) NOT NULL auto_increment,
  `id_jadwal` int(11) DEFAULT NULL,
  `visi_teks` text DEFAULT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpjpd_misi` (
  `id` int(11) NOT NULL auto_increment,
  `id_visi` int(11) DEFAULT NULL,
  `misi_teks` text DEFAULT NULL,
  `urut_misi` tinyint(4) DEFAULT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpjpd_sasaran` (
  `id` int(11) NOT NULL auto_increment,
  `id_misi` int(11) DEFAULT NULL,
  `saspok_teks` text DEFAULT NULL,
  `urut_saspok` tinyint(4) DEFAULT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpjpd_kebijakan` (
  `id` int(11) NOT NULL auto_increment,
  `id_saspok` int(11) DEFAULT NULL,
  `kebijakan_teks` text DEFAULT NULL,
  `urut_kebijakan` tinyint(4) DEFAULT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpjpd_isu` (
  `id` int(11) NOT NULL auto_increment,
  `id_kebijakan` int(11) DEFAULT NULL,
  `isu_teks` text DEFAULT NULL,
  `urut_isu` tinyint(4) DEFAULT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpd_tujuan` (
  `id` int(11) NOT NULL auto_increment,
  `head_teks` text DEFAULT NULL,
  `id_misi_old` int(11) DEFAULT NULL,
  `id_tujuan` int(11) DEFAULT NULL,
  `id_unik` text DEFAULT NULL,
  `id_unik_indikator` text DEFAULT NULL,
  `indikator_teks` text DEFAULT NULL,
  `is_locked` tinyint(4) DEFAULT NULL,
  `is_locked_indikator` tinyint(4) DEFAULT NULL,
  `isu_teks` text DEFAULT NULL,
  `kebijakan_teks` text DEFAULT NULL,
  `misi_lock` tinyint(4) DEFAULT NULL,
  `misi_teks` text DEFAULT NULL,
  `saspok_teks` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `target_1` text DEFAULT NULL,
  `target_2` text DEFAULT NULL,
  `target_3` text DEFAULT NULL,
  `target_4` text DEFAULT NULL,
  `target_5` text DEFAULT NULL,
  `target_akhir` text DEFAULT NULL,
  `target_awal` text DEFAULT NULL,
  `tujuan_teks` text DEFAULT NULL,
  `urut_misi` tinyint(4) DEFAULT NULL,
  `urut_saspok` tinyint(4) DEFAULT NULL,
  `urut_tujuan` tinyint(4) DEFAULT NULL,
  `visi_teks` text DEFAULT NULL,
  `id_isu` int(11) DEFAULT NULL,
  `no_urut` int(11) NOT NULL,
  `catatan_teks_tujuan` text DEFAULT NULL,
  `indikator_catatan_teks` text DEFAULT NULL,
  `nama_cascading` text DEFAULT NULL,
  `update_at` datetime NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpd_sasaran` (
  `id` int(11) NOT NULL auto_increment,
  `head_teks` text DEFAULT NULL,
  `id_misi_old` int(11) DEFAULT NULL,
  `id_sasaran` int(11) DEFAULT NULL,
  `id_unik` text DEFAULT NULL,
  `id_unik_indikator` text DEFAULT NULL,
  `indikator_teks` text DEFAULT NULL,
  `is_locked` tinyint(4) DEFAULT NULL,
  `is_locked_indikator` tinyint(4) DEFAULT NULL,
  `isu_teks` text DEFAULT NULL,
  `kebijakan_teks` text DEFAULT NULL,
  `kode_tujuan` text DEFAULT NULL,
  `misi_lock` tinyint(4) DEFAULT NULL,
  `misi_teks` text DEFAULT NULL,
  `sasaran_teks` text DEFAULT NULL,
  `saspok_teks` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `target_1` text DEFAULT NULL,
  `target_2` text DEFAULT NULL,
  `target_3` text DEFAULT NULL,
  `target_4` text DEFAULT NULL,
  `target_5` text DEFAULT NULL,
  `target_akhir` text DEFAULT NULL,
  `target_awal` text DEFAULT NULL,
  `tujuan_lock` tinyint(4) DEFAULT NULL,
  `tujuan_teks` text DEFAULT NULL,
  `urut_misi` text DEFAULT NULL,
  `urut_sasaran` text DEFAULT NULL,
  `urut_saspok` text DEFAULT NULL,
  `urut_tujuan` text DEFAULT NULL,
  `visi_teks` text DEFAULT NULL,
  `sasaran_no_urut` int(11) NOT NULL,
  `sasaran_catatan` text NOT NULL,
  `indikator_catatan_teks` text NOT NULL,
  `update_at` datetime NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_rpd_program` (
  `id` int(11) NOT NULL auto_increment,
  `head_teks` text DEFAULT NULL,
  `id_bidur_mth` int(11) DEFAULT NULL,
  `id_misi_old` int(11) DEFAULT NULL,
  `id_program` int(11) DEFAULT NULL,
  `id_program_mth` int(11) DEFAULT NULL,
  `id_unik` text DEFAULT NULL,
  `id_unik_indikator` text DEFAULT NULL,
  `id_unit` varchar(20) DEFAULT NULL,
  `id_unik_indikator_sasaran` text DEFAULT NULL,
  `indikator` text DEFAULT NULL,
  `is_locked` tinyint(4) DEFAULT NULL,
  `is_locked_indikator` tinyint(4) DEFAULT NULL,
  `isu_teks` text DEFAULT NULL,
  `kebijakan_teks` text DEFAULT NULL,
  `kode_sasaran` text DEFAULT NULL,
  `kode_skpd` text DEFAULT NULL,
  `kode_tujuan` text DEFAULT NULL,
  `misi_lock` text DEFAULT NULL,
  `misi_teks` tinyint(4) DEFAULT NULL,
  `nama_program` text DEFAULT NULL,
  `nama_skpd` text DEFAULT NULL,
  `pagu_1` double(20, 0) DEFAULT NULL,
  `pagu_2` double(20, 0) DEFAULT NULL,
  `pagu_3` double(20, 0) DEFAULT NULL,
  `pagu_4` double(20, 0) DEFAULT NULL,
  `pagu_5` double(20, 0) DEFAULT NULL,
  `program_lock` tinyint(4) DEFAULT NULL,
  `sasaran_lock` tinyint(4) DEFAULT NULL,
  `sasaran_teks` text DEFAULT NULL,
  `saspok_teks` text DEFAULT NULL,
  `satuan` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `target_1` text DEFAULT NULL,
  `target_2` text DEFAULT NULL,
  `target_3` text DEFAULT NULL,
  `target_4` text DEFAULT NULL,
  `target_5` text DEFAULT NULL,
  `target_akhir` text DEFAULT NULL,
  `target_awal` text DEFAULT NULL,
  `tujuan_lock` tinyint(4) DEFAULT NULL,
  `tujuan_teks` text DEFAULT NULL,
  `urut_misi` tinyint(4) DEFAULT NULL,
  `urut_sasaran` tinyint(4) DEFAULT NULL,
  `urut_saspok` tinyint(4) DEFAULT NULL,
  `urut_tujuan` tinyint(4) DEFAULT NULL,
  `visi_teks` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `update_at` datetime NOT NULL,
  `active` tinyint(4) NOT NULL,
  `id_program_lama` int(11) DEFAULT NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_prog_keg` (
  `id` int(11) NOT NULL auto_increment,
  `id_bidang_urusan` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `id_giat` int(11) NOT NULL,
  `id_sub_giat` int(11) NOT NULL,
  `id_urusan` int(11) NOT NULL,
  `is_locked` int(11) NOT NULL,
  `kode_bidang_urusan` varchar(50) NOT NULL,
  `kode_giat` varchar(50) NOT NULL,
  `kode_program` varchar(50) NOT NULL,
  `kode_sub_giat` varchar(50) NOT NULL,
  `kode_urusan` varchar(50) NOT NULL,
  `nama_bidang_urusan` text NOT NULL,
  `nama_giat` text NOT NULL,
  `nama_program` text NOT NULL,
  `nama_sub_giat` text NOT NULL,
  `nama_urusan` text NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `aceh` int(11) DEFAULT NULL,
  `bali` int(11) DEFAULT NULL,
  `papua` int(11) DEFAULT NULL,
  `papua_barat` int(11) DEFAULT NULL,
  `yogyakarta` int(11) DEFAULT NULL,
  `jakarta` int(11) DEFAULT NULL,
  `id_daerah` int(11) DEFAULT NULL,
  `id_daerah_khusus` int(11) DEFAULT NULL,
  `is_setda` int(11) DEFAULT NULL,
  `is_setwan` int(11) DEFAULT NULL,
  `kunci_tahun` int(11) DEFAULT NULL,
  `mulai_tahun` int(11) DEFAULT NULL,
  `set_kab_kota` int(11) DEFAULT NULL,
  `set_prov` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT 1 COMMENT '0=hapus, 1=aktif',
  `update_at` datetime DEFAULT NULL,
  `tahun_anggaran` year(4) NOT NULL DEFAULT '2021',
  PRIMARY KEY  (id)
);

CREATE TABLE `esakip_capaian_iku_pemda` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY key (id)
);

CREATE TABLE `esakip_croscutting_opd` (
  `id` int(11) NOT NULL auto_increment,
  `parent_pohon_kinerja` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT null,
  `label_croscutting` varchar(255) DEFAULT NULL,
  `label_indikator_croscutting` varchar(255) DEFAULT null,
  `id_skpd_croscutting` int(11) DEFAULT NULL,
  `keterangan_croscutting` varchar(255) DEFAULT null,
  `parent_croscutting` int(11) NOT NULL,
  `status_croscutting` tinyint(4) NOT NULL COMMENT '0 = MENUNGGU, 1 = DISETUJUI, 2 = DITOLAK',
  `active` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY key (id)
);

CREATE TABLE `esakip_capaian_indikator` (
  `id` int(11) NOT NULL auto_increment,
  `id_jadwal` int(11) DEFAULT NULL,
  `indikator_kinerja` text DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `kondisi_awal` varchar(255) DEFAULT NULL,
  `target_akhir_p_rpjmd` varchar(255) DEFAULT NULL,
  `bps_tahun_1` varchar(255) DEFAULT NULL,
  `bps_tahun_2` varchar(255) DEFAULT NULL,
  `bps_tahun_3` varchar(255) DEFAULT NULL,
  `bps_tahun_4` varchar(255) DEFAULT NULL,
  `bps_tahun_5` varchar(255) DEFAULT NULL,
  `lkpj_tahun_1` varchar(255) DEFAULT NULL,
  `lkpj_tahun_2` varchar(255) DEFAULT NULL,
  `lkpj_tahun_3` varchar(255) DEFAULT NULL,
  `lkpj_tahun_4` varchar(255) DEFAULT NULL,
  `lkpj_tahun_5` varchar(255) DEFAULT NULL,
  `sumber_data` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);