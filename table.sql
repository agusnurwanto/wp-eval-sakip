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
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_other_file` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
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
  `tahun_anggaran` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_renstra` (
  `id` int(11) NOT NULL auto_increment,
  `opd` varchar(255) DEFAULT NULL,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rkpd` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `active` tinyint(4) DEFAULT 1,
 PRIMARY KEY(id)
);

CREATE TABLE `esakip_rpjmd` (
  `id` int(11) NOT NULL auto_increment,
  `id_skpd` int(11) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
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
  `keterangan` varchar(255) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 = HAPUS 1 = ACTIVE, 2 = DIKUNCI',
  `jenis_jadwal` varchar(30) DEFAULT NULL,
  `tipe` varchar(30) DEFAULT NULL COMMENT 'RPJMD, LKE',
  `lama_pelaksanaan` int(11) DEFAULT NULL,
  `tahun_anggaran` year(4) NOT NULL DEFAULT '2022',
  PRIMARY KEY  (id)
);