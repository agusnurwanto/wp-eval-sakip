<?php
if (!defined('WPINC')) {
	die;
}
global $wpdb;

$id_skpd = false;
$tahun = false;
$id_indikator = false;
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
	$id_skpd = $wpdb->prepare('%d', $_GET['id_skpd']);
} else {
	die('<h1 class="text-center">Param "id_skpd" tidak boleh kosong!</h1>');
}
if (!empty($_GET) && !empty($_GET['tahun'])) {
	$tahun = $wpdb->prepare('%d', $_GET['tahun']);
} else {
	die('<h1 class="text-center">Param "tahun" tidak boleh kosong!</h1>');
}
if (!empty($_GET) && !empty($_GET['id_indikator'])) {
	$id_indikator = $wpdb->prepare('%d', $_GET['id_indikator']);
} else {
	die('<h1 class="text-center">Param "id_indikator" tidak boleh kosong!</h1>');
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$data_id_jadwal = $wpdb->get_row(
	$wpdb->prepare("
		SELECT 
			id_jadwal,
			id_jadwal_wp_sipd
		FROM esakip_pengaturan_rencana_aksi
		WHERE tahun_anggaran =%d
		  AND active=1
	", $tahun),
	ARRAY_A
);

if (empty($data_id_jadwal['id_jadwal'])) {
	$id_jadwal = 0;
} else {
	$id_jadwal = $data_id_jadwal['id_jadwal'];
}
$cek_id_jadwal = empty($data_id_jadwal['id_jadwal']) ? 0 : 1;

if (empty($data_id_jadwal['id_jadwal_wp_sipd'])) {
	$id_jadwal_wpsipd = 0;
} else {
	$id_jadwal_wpsipd = $data_id_jadwal['id_jadwal_wp_sipd'];
}
$cek_id_jadwal_wpsipd = empty($data_id_jadwal['id_jadwal_wp_sipd']) ? 0 : 1;

$skpd = $wpdb->get_row(
	$wpdb->prepare("
		SELECT 
			nama_skpd
		FROM esakip_data_unit
		WHERE id_skpd=%d
		  AND tahun_anggaran=%d
		  AND active = 1
	", $id_skpd, $tahun_anggaran_sakip),
	ARRAY_A
);

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

$admin_role_pemda = array(
	'admin_bappeda',
	'admin_ortala'
);

$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2;

$cek_settingan_menu = $wpdb->get_var(
	$wpdb->prepare("
		SELECT 
			jenis_role
		FROM esakip_menu_dokumen 
		WHERE nama_dokumen='Rencana Aksi'
		  AND user_role='perangkat_daerah' 
		  AND active = 1
		  AND tahun_anggaran=%d
	", $tahun)
);

$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;

$ind_renaksi = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_indikator_opd
		WHERE id=%d
	", $id_indikator),
	ARRAY_A
);
if (empty($ind_renaksi)) {
	die('<h1 class="text-center">Indikator Rencana Hasil Pekerjaan tidak ditemukan!</h1>');
}

$renaksi = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $ind_renaksi['id_renaksi']),
	ARRAY_A
);

$renaksi_parent1 = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $renaksi['parent']),
	ARRAY_A
);

$renaksi_parent2 = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $renaksi_parent1['parent']),
	ARRAY_A
);

$renaksi_parent3 = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $renaksi_parent2['parent']),
	ARRAY_A
);

$renaksi_parent_pemda = $wpdb->get_results(
	$wpdb->prepare("
		SELECT 
			i.*,
			r.label,
			r.id as id_renaksi,
			r.parent
		FROM `esakip_data_label_rencana_aksi` l
		INNER JOIN esakip_data_rencana_aksi_indikator_pemda i 
				ON l.parent_indikator_renaksi_pemda=i.id
		INNER JOIN esakip_data_rencana_aksi_pemda r 
				ON l.parent_renaksi_pemda=r.id
		WHERE l.parent_renaksi_opd=%d
		  AND i.active=1
		  AND i.tahun_anggaran=%d
	", $renaksi_parent1['parent'], $tahun),
	ARRAY_A
);

$renaksi_pemda4 = array(
	'label' => array(),
	'pokin' => array(),
	'nomenklatur' => array()
);
$renaksi_pemda3 = array(
	'label' => array(),
	'pokin' => array(),
	'nomenklatur' => array()
);
$renaksi_pemda2 = array(
	'label' => array(),
	'pokin' => array(),
	'nomenklatur' => array()
);
$renaksi_pemda1 = array(
	'label' => array(),
	'pokin' => array(),
	'nomenklatur' => array()
);
foreach ($renaksi_parent_pemda as $val) {
	$renaksi_pemda4['label'][$val['id_renaksi']] = $val['label'] . ' ( ' . $val['indikator'] . ' ' . $val['target_akhir'] . ' ' . $val['satuan'] . ' )';
}
$disabled = '';
$wpsipd_status = get_option('_crb_url_server_sakip');
if (empty($wpsipd_status)) {
	$disabled = 'disabled';
}
?>
<style type="text/css">
	.wrap-table {
		overflow: auto;
		max-height: 100vh;
		width: 100%;
	}

	.btn-action-group {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.btn-action-group .btn {
		margin: 0 5px;
	}

	a.btn {
		text-decoration: none !important;
	}

	thead th {
		vertical-align: middle !important;
		font-size: small;
		text-align: center;
	}

	#modal-renaksi thead th {
		font-size: medium !important;
	}

	#modal-renaksi .modal-body {
		max-height: 70vh;
		overflow-y: auto;
	}

	.table_dokumen_rencana_aksi {
		font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
		border-collapse: collapse;
		table-layout: fixed;
		overflow-wrap: break-word;
		font-size: 90%;
	}

	.table_dokumen_rencana_aksi thead {
		position: sticky;
		top: -6px;
	}

	.table_dokumen_rencana_aksi .badge {
		white-space: normal;
		line-height: 1.3;
	}

	/* Level 1 */
	.akun-row td {
		background-color: #adf7b6;
		color: #212529;
		font-weight: bold;
	}

	.akun-row:hover td {
		background-color: #d4edda !important;
	}

	/* Level 2 */
	.subs-row td {
		background-color: #ffee93;
		color: #212529;
	}

	.subs-row:hover td {
		background-color: #fff3cd !important;
	}

	/* Level 3 */
	.ket-row td {
		background-color: #ffc09f;
		color: #212529;
	}

	.ket-row:hover td {
		background-color: #f8d7da !important;
	}

	/* Level 4 */
	.rinci-row td {
		background-color: #f8f9fa;
		color: #212529;
	}

	.rinci-row:hover td {
		background-color: #e2e6ea !important;
	}
</style>

<!-- Table -->
<div id="cetak" title="Rencana Hasil Kerja Perangkat Daerah">
	<div style="padding: 10px;margin:0 0 3rem 0;">
		<h1 class="text-center" style="margin: 3rem; font-weight: 700; font-size: 2rem; line-height: 1.5;">
			Rencana Hasil Kerja<br>
			<span style="font-size: 1.75rem; font-weight: 700;">Rincian Belanja Teknis Kegiatan</span><br>
			<span style="font-size: 1.75rem; font-weight: 550;"> <?php echo $skpd['nama_skpd']; ?></span><br>
			<span style="font-size: 1.75rem; font-weight: 550;">Tahun Anggaran <?php echo $tahun; ?></span>
		</h1>
		<?php if (!$is_admin_panrb && $hak_akses_user): ?>
			<div id="action" class="action-section hide-excel"></div>
		<?php endif; ?>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Pemda Level 1</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td style="width: 270px;">Rencana Hasil Kerja Pemda Level 1</td>
					<td>:</td>
					<td></td>
				</tr>
				<tr>
					<td style="width: 270px;">Tujuan RPJMD</td>
					<td>:</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Pemda Level 2</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja Pemda Level 2</td>
					<td class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>Sasaran RPJMD</td>
					<td class="text-center">:</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Pemda Level 3</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja Pemda Level 3</td>
					<td class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>Strategi RPJMD</td>
					<td class="text-center">:</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Pemda Level 4</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja Pemda Level 4</td>
					<td class="text-center">:</td>
					<td><?php echo implode('<br>', $renaksi_pemda4['label']); ?></td>
				</tr>
				<tr>
					<td>Arah Kebijakan</td>
					<td class="text-center">:</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja OPD Level 1</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent3['label_pokin_1']; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Pohon Kinerja OPD Level 2</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent3['label_pokin_2']; ?></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja OPD Level 1</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent3['label']; ?></td>
				</tr>
				<tr>
					<td>Sasaran RENSTRA</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent3['kode_cascading_sasaran'] . ' ' . $renaksi_parent3['label_cascading_sasaran']; ?></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja OPD Level 3</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent2['label_pokin_3']; ?></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja OPD Level 2</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent2['label']; ?></td>
				</tr>
				<tr>
					<td>Program</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent2['kode_cascading_program'] . ' ' . $renaksi_parent2['label_cascading_program']; ?></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja OPD Level 4</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent1['label_pokin_4']; ?></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja OPD Level 3</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent1['label']; ?></td>
				</tr>
				<tr>
					<td>Kegiatan</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent1['kode_cascading_kegiatan'] . ' ' . $renaksi_parent1['label_cascading_kegiatan']; ?></td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja OPD Level 5</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi['label_pokin_5']; ?></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja OPD Level 4</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi['label']; ?></td>
				</tr>
				<tr>
					<td>Dasar Kegiatan</td>
					<td class="text-center">:</td>
					<td>Mandatory Pusat, Kebijakan Kepala Daerah, POKIR, MUSRENBANG</td>
				</tr>
				<tr>
					<td>Sub Kegiatan</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi['kode_cascading_sub_kegiatan'] . ' ' . $renaksi['label_cascading_sub_kegiatan']; ?></td>
				</tr>
				<tr>
					<td>Pagu Sub Kegiatan</td>
					<td class="text-center">:</td>
					<td>Rp 0</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead style="background-color: #bde0fe; color: #212529;">
				<tr>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 400px;">INDIKATOR</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2">SATUAN</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" colspan="6">TARGET KEGIATAN PER TRIWULAN</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 400px;">KETERANGAN</th>
				</tr>
				<tr>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">AWAL</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW-I</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW-II</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW-III</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW-IV</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">AKHIR</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['indikator']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['satuan']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_awal']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_1']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_2']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_3']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_4']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_akhir']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead style="background-color: #bde0fe; color: #212529;">
				<tr>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">PAGU RENCANA HASIL KERJA</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">ALOKASI APBD</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">REALISASI APBD</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">CAPAIAN REALIASI TERHADAP RENCANA PAGU</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Rp <?php echo $ind_renaksi['rencana_pagu']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Rp 0</td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Rp <?php echo $ind_renaksi['realisasi_pagu']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">0%</td>
				</tr>
			</tbody>
		</table>
		<div class="m-2 text-center">
			<button class="btn btn-primary m-2 text-center" onclick="handleModalTambahDataManual()" title="Tambah Data">
				<span class="dashicons dashicons-insert"></span> Tambah Rincian Belanja Manual
			</button>
			<button class="btn btn-primary m-2 text-center" title="Tambah Data Dari WP-SIPD" onclick="handleModalTambahDataWpsipd()" <?php echo $disabled; ?>>
				<span class="dashicons dashicons-insert"></span> Tambah Rincian Belanja dari RKA/DPA
			</button>
		</div>
		<div class="wrap-table">
			<table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi">
				<thead style="background-color: #dee2e6; text-align: center;">
					<tr>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 200px;">KODE REKENING</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2">URAIAN</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 140px;">HARGA SATUAN</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 90px;">JUMLAH</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 100px;">SATUAN</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 140px;">TOTAL</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 300px;">KETERANGAN</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- modal tambah data -->
<div class="modal fade mt-4" id="modalTambahData" tabindex="-1" role="dialog" aria-labelledby="modalTambahData" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title-label">Tambah Rincian Belanja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="id_data" name="id_data">
				<div class="card bg-light mb-3">
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="subKegiatan">Sub Kegiatan</label>
								<input type="text" name="subKegiatan" class="form-control" value="<?php echo $renaksi['label_cascading_sub_kegiatan']; ?>" disabled>
							</div>
						</div>

						<table id="tableRincian" class="mt-2 table table-hover">
							<thead style="background-color: #343a40; color: #fff; text-align: center;">
								<tr>
									<th scope="col" class="text-center" style="width: 35px;">
										<input type="checkbox" value="" id="checkAll">
									</th>
									<th scope="col" class="text-center">Nama Akun / Rincian Belanja</th>
									<th scope="col" class="text-center" style="width: 75px;">Volume</th>
									<th scope="col" class="text-center" style="width: 75px;">Satuan</th>
									<th scope="col" class="text-center" style="width: 160px;">Anggaran</th>
									<th scope="col" class="text-center" style="width: 160px;">Realisasi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" onclick="simpanTagRinciBl()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(() => {
		const statusWpsipd = '<?php echo esc_js($wpsipd_status); ?>';
		const tahunAnggaran = '<?php echo esc_js($tahun); ?>';
		const kodeSbl = '<?php echo esc_js($renaksi['kode_sbl']); ?>';

		if (statusWpsipd) {
			loadRkaWpSipd(tahunAnggaran, kodeSbl);
		}
	});

	function loadRkaWpSipd(tahunAnggaran, kodeSbl) {
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: "POST",
			data: {
				action: "get_sub_keg_rka_wpsipd",
				api_key: esakip.api_key,
				tahun_anggaran: tahunAnggaran,
				kode_sbl: kodeSbl,
			},
			dataType: "json",
			success: function(response) {
				jQuery("#wrap-loading").hide();
				const tableBody = jQuery("#tableRincian tbody");
				tableBody.empty();

				const data = response.data.data;

				// Group data
				const groupedData = {};
				data.forEach((item) => {
					const {
						kode_akun: kodeAkun,
						nama_akun: namaAkun,
						subs_bl_teks: subsBl,
						ket_bl_teks: ketBl,
						nama_komponen: namaKomponen,
						volume,
						satuan,
						total_harga: totalHarga = 0,
						realisasi_rincian: totalRealisasi = 0,
					} = item;

					// Akun
					if (!groupedData[kodeAkun]) {
						groupedData[kodeAkun] = {
							namaAkun: namaAkun.replace(/^\S+(\.\S+)*\s/, ""), // Hapus kode akun di awal
							kodeAkun: kodeAkun,
							total: 0,
							totalRealisasi: 0,
							subs: {},
						};
					}

					// Subs (Kelompok/Subs Bl)
					if (!groupedData[kodeAkun].subs[subsBl]) {
						groupedData[kodeAkun].subs[subsBl] = {
							namaKelompok: subsBl,
							total: 0,
							totalRealisasi: 0,
							ket: {},
						};
					}

					// Ket (Keterangan)
					if (!groupedData[kodeAkun].subs[subsBl].ket[ketBl]) {
						groupedData[kodeAkun].subs[subsBl].ket[ketBl] = {
							namaKeterangan: ketBl,
							total: 0,
							totalRealisasi: 0,
							data: [],
						};
					}

					// Masukkan data rincian ke keterangan
					groupedData[kodeAkun].subs[subsBl].ket[ketBl].data.push({
						namaKomponen,
						volume,
						satuan,
						totalHarga,
						totalRealisasi,
					});

					// Perbarui total di setiap level
					groupedData[kodeAkun].total += totalHarga;
					groupedData[kodeAkun].totalRealisasi += totalRealisasi;
					groupedData[kodeAkun].subs[subsBl].total += totalHarga;
					groupedData[kodeAkun].subs[subsBl].totalRealisasi += totalRealisasi;
					groupedData[kodeAkun].subs[subsBl].ket[ketBl].total += totalHarga;
					groupedData[kodeAkun].subs[subsBl].ket[ketBl].totalRealisasi += totalRealisasi;
				});

				// Render data ke tabel
				Object.values(groupedData).forEach((akunData) => {
					tableBody.append(`
                    <tr class="akun-row" data-id="${akunData.kodeAkun}">
                        <td class="text-center">
                            <input class="akun-checkbox" type="checkbox" value="${akunData.kodeAkun}">
                        </td>
                        <td colspan="5" class="pl-3">${akunData.kodeAkun} ${akunData.namaAkun}</td>
                    </tr>
                `);

					Object.values(akunData.subs).forEach((subsData) => {
						tableBody.append(`
                        <tr class="subs-row" data-parent-id="${akunData.kodeAkun}" data-id="${subsData.subsBl}">
                            <td class="text-center">
                                <input class="subs-checkbox" type="checkbox" value="${subsData.subsBl}">
                            </td>
                            <td colspan="5" class="pl-4">${subsData.namaKelompok}</td>
                        </tr>
                    `);

						Object.values(subsData.ket).forEach((ketData) => {
							tableBody.append(`
                            <tr class="ket-row" data-parent-id="${subsData.subsBl}" data-id="${ketData.ketBl}" data-grandparent-id="${akunData.kodeAkun}">
                                <td class="text-center">
                                    <input class="ket-checkbox" type="checkbox" value="${ketData.ketBl}">
                                </td>
                                <td colspan="5" class="pl-5">${ketData.namaKeterangan}</td>
                            </tr>
                        `);

							ketData.data.forEach((rinci) => {
								tableBody.append(`
                                <tr class="rinci-row" data-id="${rinci.id_rinci_sub_bl}" data-parent-id="${ketData.ketBl}" data-grandparent-id="${subsData.subsBl}" data-greatgrandparent-id="${akunData.kodeAkun}">
                                    <td class="text-center">
                                        <input class="rinci-checkbox" type="checkbox" value="${rinci.id_rinci_sub_bl}">
                                    </td>
                                    <td class="pl-5">${rinci.namaKomponen}</td>
                                    <td class="text-center">${formatRupiah(rinci.volume)}</td>
                                    <td class="text-center">${rinci.satuan}</td>
                                    <td class="text-right">${formatRupiah(rinci.totalHarga)}</td>
                                    <td class="text-right">${formatRupiah(rinci.totalRealisasi)}</td>
                                </tr>
                            `);
							});
						});
					});
				});

				handleCheckboxRinci()
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
				jQuery("#wrap-loading").hide();
				alert("Terjadi kesalahan saat memuat rincian wp-sipd!");
			},
		});
	}

	function handleCheckboxRinci() {
        // Checkbox utama (select all)
        jQuery("#checkAll").on("change", function() {
            const isChecked = jQuery(this).is(":checked");
            jQuery(".akun-checkbox, .subs-checkbox, .ket-checkbox, .rinci-checkbox").prop("checked", isChecked);
        });

        // Akun ke subs_bl_teks
        jQuery(".akun-checkbox").on("change", function() {
            const isChecked = jQuery(this).is(":checked"); //bool
            const akunId = jQuery(this).val();
            jQuery(`.subs-row[data-parent-id="${akunId}"] .subs-checkbox`).prop("checked", isChecked);
            jQuery(`.ket-row[data-grandparent-id="${akunId}"] .ket-checkbox`).prop("checked", isChecked);
            jQuery(`.rinci-row[data-greatgrandparent-id="${akunId}"] .rinci-checkbox`).prop("checked", isChecked);

            updateSelectAllState();
        });

        // Subs_bl_teks ke ket_bl_teks
        jQuery(".subs-checkbox").on("change", function() {
            const isChecked = jQuery(this).is(":checked");
            const subsId = jQuery(this).val();
            const parentId = jQuery(this).closest(".subs-row").data("parent-id");

            jQuery(`.ket-row[data-parent-id="${subsId}"] .ket-checkbox`).prop("checked", isChecked);
            jQuery(`.rinci-row[data-grandparent-id="${subsId}"] .rinci-checkbox`).prop("checked", isChecked);

            updateSelectAllStateAkun(parentId) //akun

            updateParentCheckbox(parentId, ".akun-checkbox", ".subs-checkbox");
            updateSelectAllState();
        });

        // Ket_bl_teks ke id_rinci_sub_bl
        jQuery(".ket-checkbox").on("change", function() {
            const isChecked = jQuery(this).is(":checked");
            const ketId = jQuery(this).val();
            const parentId = jQuery(this).closest(".ket-row").data("parent-id");
            const grandParentId = jQuery(`.subs-row[data-id="${parentId}"]`).data("parent-id");

            jQuery(`.rinci-row[data-parent-id="${ketId}"] .rinci-checkbox`).prop("checked", isChecked);

            updateSelectAllStateKelompok(parentId) //kelompok
            updateSelectAllStateAkun(grandParentId) //akun

            updateParentCheckbox(parentId, ".subs-checkbox", ".ket-checkbox");
            updateSelectAllState();
        });

        // Id_rinci_sub_bl ke ket_bl_teks
        jQuery(".rinci-checkbox").on("change", function() {
            const parentId = jQuery(this).closest(".rinci-row").data("parent-id"); //keterangan
            const grandParentId = jQuery(`.ket-row[data-id="${parentId}"]`).data("parent-id"); //kelompok
            const greatGrandParentId = jQuery(`.subs-row[data-id="${grandParentId}"]`).data("parent-id"); //akun

            updateSelectAllStateKeterangan(parentId)
            updateSelectAllStateKelompok(grandParentId)
            updateSelectAllStateAkun(greatGrandParentId) //akun

            updateParentCheckbox(parentId, ".ket-checkbox", ".rinci-checkbox");
            updateParentCheckbox(grandParentId, ".subs-checkbox", ".ket-checkbox");
            updateParentCheckbox(greatGrandParentId, ".akun-checkbox", ".subs-checkbox");

            updateSelectAllState();
        });

        // Update state of "select all" checkbox
        function updateSelectAllState() {
            const allChecked = jQuery(".rinci-checkbox").length === jQuery(".rinci-checkbox:checked").length;
            jQuery("#checkAll").prop("checked", allChecked);
        }

        function updateSelectAllStateAkun(akunId) {
            const allChildren = jQuery(`.subs-row[data-parent-id="${akunId}"] .subs-checkbox`);
            const allChecked = allChildren.length === allChildren.filter(":checked").length;

            jQuery(`.akun-checkbox[value="${akunId}"]`).prop("checked", allChecked);
        }

        function updateSelectAllStateKelompok(kelompokId) {
            const allChildren = jQuery(`.ket-row[data-parent-id="${kelompokId}"] .ket-checkbox`);
            const allChecked = allChildren.length === allChildren.filter(":checked").length;

            jQuery(`.subs-checkbox[value="${kelompokId}"]`).prop("checked", allChecked);
        }

        function updateSelectAllStateKeterangan(keteranganId) {
            const allChildren = jQuery(`.rinci-row[data-parent-id="${keteranganId}"] .rinci-checkbox`);
            const allChecked = allChildren.length === allChildren.filter(":checked").length;

            jQuery(`.ket-checkbox[value="${keteranganId}"]`).prop("checked", allChecked);
        }


        // Update parent checkbox
        function updateParentCheckbox(parentId, parentSelector, childSelector) {
            const allChildren = jQuery(`${childSelector}[data-parent-id="${parentId}"]`);
            const parentCheckbox = jQuery(`${parentSelector}[data-id="${parentId}"]`);
            parentCheckbox.prop("checked", allChildren.length === allChildren.filter(":checked").length);
        }
    }

	function handleModalTambahDataManual() {
		jQuery('#modalTambahData').modal('show')
	}

	function handleModalTambahDataWpsipd() {
		jQuery('#modalTambahData').modal('show')
	}
</script>