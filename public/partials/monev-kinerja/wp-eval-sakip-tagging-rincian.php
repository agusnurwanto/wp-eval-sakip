<?php
if (!defined('WPINC')) {
	die;
}
global $wpdb;

$params = ['id_skpd', 'tahun', 'id_indikator'];
foreach ($params as $param) {
	if (!empty($_GET[$param])) {
		$$param = $wpdb->prepare('%d', $_GET[$param]);
	} else {
		die("<h1 class='text-center'>Param \"$param\" tidak boleh kosong!</h1>");
	}
}

function get_pegawai_skpd_rhk($rhk, $wpdb)
{
	if (empty($rhk)) {
		return null;
	}

	// Mengambil data pegawai berdasarkan NIP
	$nama_pegawai = $wpdb->get_row(
		$wpdb->prepare("
            SELECT 
                nip_baru,
                nama_pegawai
            FROM esakip_data_pegawai_simpeg
            WHERE nip_baru = %s
        ", $rhk['nip']),
		ARRAY_A
	);

	// Mengambil nama SKPD berdasarkan tahun anggaran dan ID SKPD
	$nama_skpd = $wpdb->get_var(
		$wpdb->prepare("
            SELECT 
                nama_skpd
            FROM esakip_data_unit
            WHERE tahun_anggaran = %d
              AND id_skpd = %d
              AND active = 1
        ", $rhk['tahun_anggaran'], $rhk['id_skpd'])
	);

	return [
		'pegawai' => $nama_pegawai ?: null,
		'skpd'    => $nama_skpd ?: null,
	];
}


$ind_renaksi = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_indikator_opd
		WHERE id=%d
	", $id_indikator),
	ARRAY_A
);
if (empty($ind_renaksi)) {
	die('<h1 class="text-center">Indikator Rencana Hasil Kerja tidak ditemukan!</h1>');
}

$data_satuan = $wpdb->get_results(
	$wpdb->prepare("
		SELECT 
			id_satuan,
			nama_satuan 
		FROM esakip_data_satuan 
		WHERE tahun_anggaran = %d
		  AND active = 1
	", $tahun),
	ARRAY_A
);

$data_satuan_key_value = array_column($data_satuan, 'nama_satuan', 'id_satuan');

$option_satuan = '';
if (!empty($data_satuan)) {
	foreach ($data_satuan as $val) {
		$option_satuan .= '<option value="' . $val['id_satuan'] . '">' . $val['nama_satuan'] . '</option>';
	}
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$data_id_jadwal = $wpdb->get_row(
	$wpdb->prepare("
		SELECT 
			id_jadwal_rpjmd as id_jadwal,
			id_jadwal_wp_sipd
		FROM esakip_pengaturan_upload_dokumen
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
$user_nip = $current_user->data->user_login;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

$admin_role_pemda = array(
	'admin_bappeda',
	'admin_ortala'
);

$this_jenis_role = (array_intersect($admin_role_pemda, $user_roles)) ? 1 : 2;

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

$renaksi = $wpdb->get_row(
	$wpdb->prepare("
        SELECT *
        FROM esakip_data_rencana_aksi_opd
        WHERE id = %d
    ", $ind_renaksi['id_renaksi']),
	ARRAY_A
);

if (!empty($renaksi)) {
	$data = get_pegawai_skpd_rhk($renaksi, $wpdb);

	$nama_pegawai_4 = $data['pegawai'] ?? null;
	$nama_skpd_4 = $data['skpd'] ?? null;
} else {
	$nama_pegawai_4 = null;
	$nama_skpd_4 = null;
}

$subkeg = array();
$nama_sub_keg = '';
if(!empty($renaksi['label_cascading_sub_kegiatan'])){
	$subkeg = explode(' ', $renaksi['label_cascading_sub_kegiatan'], 2);
	$nama_sub_keg = $subkeg[1];
}

$renaksi_parent1 = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $renaksi['parent']),
	ARRAY_A
);

if (!empty($renaksi_parent1)) {
	$data = get_pegawai_skpd_rhk($renaksi_parent1, $wpdb);

	$nama_pegawai_1 = $data['pegawai'] ?? null;
	$nama_skpd_1 = $data['skpd'] ?? null;
} else {
	$nama_pegawai_1 = null;
	$nama_skpd_1 = null;
}

$renaksi_parent2 = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $renaksi_parent1['parent']),
	ARRAY_A
);

if (!empty($renaksi_parent2)) {
	$data = get_pegawai_skpd_rhk($renaksi_parent2, $wpdb);

	$nama_pegawai_2 = $data['pegawai'] ?? null;
	$nama_skpd_2 = $data['skpd'] ?? null;
} else {
	$nama_pegawai_2 = null;
	$nama_skpd_2 = null;
}

$renaksi_parent3 = $wpdb->get_row(
	$wpdb->prepare("
		SELECT *
		FROM esakip_data_rencana_aksi_opd
		WHERE id=%d
	", $renaksi_parent2['parent']),
	ARRAY_A
);

if (!empty($renaksi_parent3)) {
	$data = get_pegawai_skpd_rhk($renaksi_parent3, $wpdb);

	$nama_pegawai_3 = $data['pegawai'] ?? null;
	$nama_skpd_3 = $data['skpd'] ?? null;
} else {
	$nama_pegawai_3 = null;
	$nama_skpd_3 = null;
}

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
	'label' => array()
);
$renaksi_pemda3 = array(
	'label' => array()
);
$renaksi_pemda2 = array(
	'label' => array()
);
$renaksi_pemda1 = array(
	'label' => array()
);
foreach ($renaksi_parent_pemda as $val) {
	$renaksi_pemda4['label'][$val['id_renaksi']] = $val['label'] . ' ( ' . $val['indikator'] . ' ' . $val['target_akhir'] . ' ' . $val['satuan'] . ' )';
}

//------ hak akses user pegawai ------//
$data_user_pegawai = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT
            nip_baru,
            nama_pegawai,
            satker_id,
            tipe_pegawai_id,
            plt_plh,
            tmt_sk_plth,
            berakhir
        FROM 
            esakip_data_pegawai_simpeg
        WHERE
            nip_baru=%s
            AND active=%d
        ORDER BY satker_id ASC, tipe_pegawai_id ASC",
        $user_nip,
        1
    ),
    ARRAY_A
);

$skpd_user_pegawai = array();
$hak_akses_user_pegawai_per_skpd = array();
if (!empty($data_user_pegawai)) {
	foreach ($data_user_pegawai as $k_user => $v_user) {
		$satker_pegawai_simpeg = substr($v_user['satker_id'], 0, 2);
		$hak_akses_user_pegawai = 0;
        $nip_user_pegawai = 0;

		$skpd_user_pegawai = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT 
					simpeg.id_satker_simpeg,
					unit.nama_skpd, 
					unit.id_skpd, 
					unit.kode_skpd,
					unit.is_skpd
				FROM 
					esakip_data_mapping_unit_sipd_simpeg AS simpeg
				JOIN 
					esakip_data_unit AS unit
				ON 
					simpeg.id_skpd = unit.id_skpd
				WHERE 
					simpeg.id_satker_simpeg=%d 
				AND simpeg.tahun_anggaran=%d
				AND simpeg.active=%d
				AND unit.tahun_anggaran=%d
				AND unit.active=%d
			GROUP BY unit.id_skpd",
				$satker_pegawai_simpeg,
				$tahun_anggaran_sakip,
				1,
				$tahun_anggaran_sakip,
				1
			),
			ARRAY_A
		);
		
		// TIPE HAK AKSES USER PEGAWAI | 0 = TIDAK ADA | 1 = ALL | 2 = HANYA RHK TERKAIT
		if (!empty($skpd_user_pegawai)) {
			if (($skpd_user_pegawai['id_skpd'] == $id_skpd && $v_user['tipe_pegawai_id'] == 11 && strlen($v_user['satker_id']) == 2) || $is_administrator) {
				$hak_akses_user_pegawai = 1;
			} else if ($skpd_user_pegawai['id_skpd'] == $id_skpd && $nama_pegawai_4['nip_baru'] == $user_nip) {
				$hak_akses_user_pegawai = 2;
			}
			if(empty($hak_akses_user_pegawai_per_skpd[$skpd_user_pegawai['id_skpd']])){
                $hak_akses_user_pegawai_per_skpd[$skpd_user_pegawai['id_skpd']] = $hak_akses_user_pegawai;
            }
		}
	}
}

if ($is_administrator) {
	$hak_akses_user_pegawai = 1;
}else{
	// ----- hak akses by skpd terkait ----- //
	$hak_akses_user_pegawai = $hak_akses_user_pegawai_per_skpd[$id_skpd];
}

//------ end ------ //

$data_tagging = $wpdb->get_results(
	$wpdb->prepare("
		SELECT * 
		FROM esakip_tagging_rincian_belanja 
		WHERE active = 1 
		  AND id_skpd = %d
		  AND id_indikator = %d
		  AND kode_sbl = %s
	", $id_skpd, $id_indikator, $renaksi['kode_sbl']),
	ARRAY_A
);

$grouped_data = [];
if (!empty($data_tagging)) {
}
foreach ($data_tagging as $value) {
	$badge_tipe = $value['tipe'] == 1
		? "<span class='badge badge-primary'>Manual</span>"
		: "<span class='badge badge-success'>RKA/DPA</span>";

	$kode_akun = $value['kode_akun'];
	$keterangan = $value['keterangan'];
	$subs_bl = $value['subs_bl_teks'];
	$ket_bl = $value['ket_bl_teks'];
	$nama_komponen = $value['nama_komponen'];
	$id_rincian = $value['id'];
	$harga_satuan = $value['harga_satuan'];
	$volume = $value['volume'];
	$satuan = $value['satuan'];
	$tipe = $value['tipe'];
	$total_harga = $volume * $harga_satuan;
	$total_realisasi = $value['realisasi'] ?? 0;

	// Akun
	if (!isset($grouped_data[$kode_akun])) {
		$grouped_data[$kode_akun] = [
			'nama_akun' => $value['nama_akun'],
			'kode_akun' => $kode_akun,
			'total' => 0,
			'total_realisasi' => 0,
			'subs' => []
		];
	}

	// Subs (Kelompok/Subs BL)
	if (!isset($grouped_data[$kode_akun]['subs'][$subs_bl])) {
		$grouped_data[$kode_akun]['subs'][$subs_bl] = [
			'nama_kelompok' => $subs_bl,
			'total' => 0,
			'total_realisasi' => 0,
			'ket' => []
		];
	}

	// Ket (Keterangan)
	if (!isset($grouped_data[$kode_akun]['subs'][$subs_bl]['ket'][$ket_bl])) {
		$grouped_data[$kode_akun]['subs'][$subs_bl]['ket'][$ket_bl] = [
			'nama_keterangan' => $ket_bl,
			'total' => 0,
			'total_realisasi' => 0,
			'data' => []
		];
	}

	// Rinci
	$grouped_data[$kode_akun]['subs'][$subs_bl]['ket'][$ket_bl]['data'][] = [
		'nama_komponen' => $nama_komponen,
		'volume' => $volume,
		'satuan' => $satuan,
		'total_harga' => $total_harga,
		'total_realisasi' => $total_realisasi,
		'id_rincian' => $id_rincian,
		'harga_satuan' => $harga_satuan,
		'keterangan' => $keterangan,
		'tipe' => $tipe,
		'badge' => $badge_tipe
	];

	// Update total harga dan realisasi pada level Keterangan
	$grouped_data[$kode_akun]['subs'][$subs_bl]['ket'][$ket_bl]['total'] += $total_harga;
	$grouped_data[$kode_akun]['subs'][$subs_bl]['ket'][$ket_bl]['total_realisasi'] += $total_realisasi;

	// Update total harga dan realisasi pada level Subs
	$grouped_data[$kode_akun]['subs'][$subs_bl]['total'] += $total_harga;
	$grouped_data[$kode_akun]['subs'][$subs_bl]['total_realisasi'] += $total_realisasi;

	// Update total harga dan realisasi pada level Akun
	$grouped_data[$kode_akun]['total'] += $total_harga;
	$grouped_data[$kode_akun]['total_realisasi'] += $total_realisasi;
}

// Rendering tbody
$total_all = 0;
$total_all_realisasi = 0;
$option_subs = '';
$option_keterangan = '';
$tbody = "";
if (!empty($grouped_data)) {
	foreach ($grouped_data as $kode_akun => $akun) {
		$tbody .= "
		<tr class='akun-row'>
			<td class='esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
				{$akun['kode_akun']}
			</td>
			<td colspan='5' class='pl-3 esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
				{$akun['nama_akun']}
			</td>
			<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
				" . number_format($akun['total'], 2, ',', '.') . "
			</td>
			<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
				" . number_format($akun['total_realisasi'], 2, ',', '.') . "
			</td>
			<td class='esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
			</td>
		</tr>";

		foreach ($akun['subs'] as $subs_bl => $subs) {
			$value_kelompok = preg_replace('/^\[\#\]\s*-?\s*/', '', $subs['nama_kelompok']);
			$option_subs .= "<option value='{$value_kelompok}'>{$value_kelompok}</option>";

			$tbody .= "
			<tr class='subs-row'>
				<td class='esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
				</td>
				<td colspan='5' class='pl-4 esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
					{$subs['nama_kelompok']}
				</td>
				<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
					" . number_format($subs['total'], 2, ',', '.') . "
				</td>
				<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
					" . number_format($subs['total_realisasi'], 2, ',', '.') . "
				</td>
				<td class='esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
				</td>
			</tr>";

			foreach ($subs['ket'] as $ket_bl => $ket) {
				$value_keterangan = preg_replace('/^\[\-\]\s*/', '', $ket['nama_keterangan']);
				$option_keterangan .= "<option value='{$value_keterangan}'>{$value_keterangan}</option>";

				$tbody .= "
				<tr class='ket-row'>
					<td class='esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
					</td>
					<td colspan='5' class='pl-5 esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
						{$ket['nama_keterangan']}
					</td>
					<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
						" . number_format($ket['total'], 2, ',', '.') . "
					</td>
					<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
						" . number_format($ket['total_realisasi'], 2, ',', '.') . "
					</td>
					<td class='esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
					</td>
				</tr>";

				foreach ($ket['data'] as $item) {
					$btn_edit = "";
					$val_satuan = $item['satuan'];
					if ($item['tipe'] == 1) {
						if($hak_akses_user_pegawai == 1 || $hak_akses_user_pegawai == 2){
							$btn_edit = "<span class='btn btn-sm btn-warning' onclick='editDataRincian({$item['id_rincian']});' title='Edit Rincian Belanja'><i class='dashicons dashicons-edit'></i></span>";
						}else{
							$btn_edit = "";
						}
						$val_satuan = $data_satuan_key_value[$item['satuan']] ?? 'Tidak ditemukan';
					}

					if($hak_akses_user_pegawai == 1 || $hak_akses_user_pegawai == 2){
						$btn_delete = "<span class='btn btn-sm btn-danger' onclick='deleteRincianById({$item['id_rincian']});' title='Hapus Rincian Belanja'><i class='dashicons dashicons-trash'></i></span>";
					}else{
						$btn_delete = "";
					}

					$tbody .= "
					<tr class='rinci-row'>
						<td class='esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
						</td>
						<td class='pl-5 esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
							{$item['nama_komponen']} {$item['badge']}
						</td>
						<td class='esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
							<div class='align-middle'>
								{$btn_delete}
								{$btn_edit}
							</div>
						</td>
						<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah' style='text-align: right;'>
							" . number_format($item['harga_satuan'], 2, ',', '.') . "
						</td>
						<td class='esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah' style='text-align: right;'>
							" . number_format($item['volume'], 2, ',', '.') . "
						</td>
						<td class='esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
							{$val_satuan}
						</td>
						<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah' style='text-align: right;'>
							" . number_format($item['total_harga'], 2, ',', '.') . "
						</td>
						<td class='esakip-text_kanan esakip-kiri esakip-kanan esakip-atas esakip-bawah' style='text-align: right;'>
							" . number_format($item['total_realisasi'], 2, ',', '.') . "
						</td>
						<td class='esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah'>
							{$item['keterangan']}
						</td>
					</tr>";
					$total_all += $item['total_harga'];
					$total_all_realisasi += $item['total_realisasi'];
				}
			}
		}
	}
} else {
	$tbody = "<tr><td colspan='9' class='esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah'>Tidak ada data tersedia</td></tr>";
}
//sisa pagu rhk
$sisa_pagu_rhk = $ind_renaksi['rencana_pagu'] - $total_all;

//disabled button jika ada parameter yg tidak lengkap
$disabled = '';
$disabled_manual = '';
$text_pesan = '-';
$wpsipd_status = get_option('_crb_url_server_sakip');
if (empty($wpsipd_status)) {
	$disabled = 'disabled';
	$text_pesan = 'Kolom URL Server WP-SIPD Di Menu Pengaturan Perangkat Daerah Kosong!';
} else if (empty($renaksi['kode_sbl'])) {
	$disabled = 'disabled';
	$disabled_manual = 'disabled';
	$text_pesan = 'Kolom Cascading Sub Kegiatan Kosong!';
}


$error_message = array(); 
$satker_id_pegawai_indikator = ''; 
if(!empty($renaksi) && !empty($renaksi['satker_id'])){
	$satker_id_pegawai_indikator = $renaksi['satker_id'];
}else{
	array_push($error_message, 'Satker Id Kosong!');
}

// ----- get data e-kin perbulan ----- //
if(!empty($tahun) && !empty($satker_id_pegawai_indikator) && !empty($renaksi['nip']) && !empty($ind_renaksi['id'])){
	// $data_ekin = $this->get_data_perbulan_ekinerja($tahun, $satker_id_pegawai_indikator, $renaksi['nip'], $ind_renaksi['id']);
	// $data_ekin_terbaru = json_decode($data_ekin, true);
	// if (!isset($data_ekin_terbaru['status']) || $data_ekin_terbaru['status'] === false) {
	// 	array_push($error_message, $data_ekin_terbaru['message']);
	// }
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

	.no-hover {
		pointer-events: none;
	}

	.no-hover-but-input td {
		pointer-events: auto;
	}

	.info-row {
		border-bottom: 1px solid #eee;
		padding: 0.8rem 0;
	}

	.amount {
		font-size: 1.1rem;
	}
</style>
<!-- Table -->
<div id="cetak">
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
		<h3 class="text-center">Data Pemerintah Daerah</h3>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Level 1</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td style="width: 270px;">RHK Level 1</td>
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
					<td style="width: 270px;">Pohon Kinerja Level 2</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>RHK Level 2</td>
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
					<td style="width: 270px;">Pohon Kinerja Level 3</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>RHK Level 3</td>
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
					<td style="width: 270px;">Pohon Kinerja Level 4</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td></td>
				</tr>
				<tr>
					<td>RHK Level 4</td>
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
		<h3 class="text-center">Data Organisasi Perangkat Daerah</h3>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Level 1</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent3['label_pokin_1']; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Level 2</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent3['label_pokin_2']; ?></td>
				</tr>
				<tr>
					<td>Kegiatan Utama | RHK Level 1</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent3['label']; ?></td>
				</tr>
				<tr>
					<td>Sasaran RENSTRA</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent3['kode_cascading_sasaran'] . ' ' . $renaksi_parent3['label_cascading_sasaran']; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Satuan Kerja</td>
					<td>:</td>
					<td><?php echo $nama_skpd_3; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Nama Pegawai</td>
					<td>:</td>
					<td>
						<?php
						if (!empty($nama_pegawai_3)) {
							echo $nama_pegawai_3['nip_baru'] . ' - ' . $nama_pegawai_3['nama_pegawai'];
						} else {
							echo '-';
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Level 3</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent2['label_pokin_3']; ?></td>
				</tr>
				<tr>
					<td>Rencana Hasil Kerja | RHK Level 2</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent2['label']; ?></td>
				</tr>
				<tr>
					<td>Program</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent2['kode_cascading_program'] . ' ' . $renaksi_parent2['label_cascading_program']; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Satuan Kerja</td>
					<td>:</td>
					<td><?php echo $nama_skpd_2; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Nama Pegawai</td>
					<td>:</td>
					<td>
						<?php
						if (!empty($nama_pegawai_2)) {
							echo $nama_pegawai_2['nip_baru'] . ' - ' . $nama_pegawai_2['nama_pegawai'];
						} else {
							echo '-';
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Level 4</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi_parent1['label_pokin_4']; ?></td>
				</tr>
				<tr>
					<td>Uraian Kegiatan RHK | RHK Level 3</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent1['label']; ?></td>
				</tr>
				<tr>
					<td>Kegiatan</td>
					<td class="text-center">:</td>
					<td><?php echo $renaksi_parent1['kode_cascading_kegiatan'] . ' ' . $renaksi_parent1['label_cascading_kegiatan']; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Satuan Kerja</td>
					<td>:</td>
					<td><?php echo $nama_skpd_1; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Nama Pegawai</td>
					<td>:</td>
					<td>
						<?php
						if (!empty($nama_pegawai_1)) {
							echo $nama_pegawai_1['nip_baru'] . ' - ' . $nama_pegawai_1['nama_pegawai'];
						} else {
							echo '-';
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="borderless-table">
			<tbody>
				<tr>
					<td style="width: 270px;">Pohon Kinerja Level 5</td>
					<td style="width: 20px;" class="text-center">:</td>
					<td><?php echo $renaksi['label_pokin_5']; ?></td>
				</tr>
				<tr>
					<td>Uraian Teknis Kegiatan | RHK Level 4</td>
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
					<td><?php echo $renaksi['kode_cascading_sub_kegiatan'] . ' ' . $nama_sub_keg; ?></td>
				</tr>
				<tr>
					<td>Pagu Sub Kegiatan</td>
					<td class="text-center">:</td>
					<td>Rp 0</td>
				</tr>
				<tr>
					<td style="width: 270px;">Satuan Kerja</td>
					<td>:</td>
					<td><?php echo $nama_skpd_4; ?></td>
				</tr>
				<tr>
					<td style="width: 270px;">Nama Pegawai</td>
					<td>:</td>
					<td>
						<?php
						if (!empty($nama_pegawai_4)) {
							echo $nama_pegawai_4['nip_baru'] . ' - ' . $nama_pegawai_4['nama_pegawai'];
						} else {
							echo '-';
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>


		<h3 class="text-center">Target dan Realisasi Per Bulan</h3>
		<table>
			<thead style="background-color: #bde0fe; color: #212529;">
				<tr>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" colspan="3">INDIKATOR</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">SATUAN</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">TARGET AWAL</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">TARGET AKHIR</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" colspan="3"><?php echo $ind_renaksi['indikator']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['satuan']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_awal']; ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_akhir']; ?></td>
				</tr>
				<tr>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" colspan="6">
						<table>
							<thead style="background-color: #bde0fe; color: #212529;">
								<tr>
									<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Bulan/TW</th>
									<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Satuan</th>
									<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Target</th>
									<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Realisasi</th>
									<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Capaian</th>
									<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Januari</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Februari</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Maret</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr style="background-color:#FDFFB6;">
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW 1</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_1']; ?></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">April</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Mei</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Juni</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr style="background-color:#FDFFB6;">
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW 2</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_2']; ?></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Juli</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Agustus</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">September</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr style="background-color:#FDFFB6;">
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW 3</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_3']; ?></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Oktober</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">November</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Desember</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr style="background-color:#FDFFB6;">
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">TW 4</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah"><?php echo $ind_renaksi['target_4']; ?></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
								<tr style="background-color:#FDFFB6;">
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah">Total</td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
									<td class="esakip-text_kiri esakip-kiri esakip-kanan esakip-atas esakip-bawah"></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<thead style="background-color: #bde0fe; color: #212529;">
				<tr>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">PAGU RENCANA HASIL KERJA</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">PAGU RINCIAN RENCANA HASIL KERJA</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">REALISASI</th>
					<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" style="width: 25%;">CAPAIAN REALIASI TERHADAP RENCANA PAGU</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Rp. <?php echo number_format($ind_renaksi['rencana_pagu'], 2, ',', '.'); ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Rp. <?php echo number_format($total_all, 2, ',', '.'); ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">Rp. <?php echo number_format($total_all_realisasi, 2, ',', '.'); ?></td>
					<td class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah">
						<?php echo $ind_renaksi['rencana_pagu'] > 0
							? round(($total_all_realisasi / $ind_renaksi['rencana_pagu']) * 100, 2) . '%'
							: '-'; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="text-center">Rincian Belanja Teknis Kegiatan</h3>
		<div class="m-2 text-center">
			<?php if($hak_akses_user_pegawai == 1 || $hak_akses_user_pegawai == 2): ?>
				<button class="btn btn-primary m-2 text-center rincian_manual" onclick="handleModalTambahDataManual()" title="Tambah Data" <?php echo $disabled_manual; ?>>
					<span class="dashicons dashicons-plus"></span> Tambah Rincian Belanja Manual
				</button>
				<button class="btn btn-success m-2 text-center rincian_rka" title="Tambah Data Dari WP-SIPD" onclick="handleModalTambahDataWpsipd()" <?php echo $disabled; ?>>
					<span class="dashicons dashicons-insert"></span> Tambah Rincian Belanja dari RKA/DPA
				</button>
			<?php endif; ?>
		</div>
		<div class="wrap-table">
			<table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi">
				<thead style="background-color: #dee2e6; text-align: center;">
					<tr>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 150px;">KODE REKENING</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2">URAIAN</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 120px;">AKSI</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 140px;">HARGA SATUAN</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 90px;">JUMLAH</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 100px;">SATUAN</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 140px;">TOTAL</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 140px;">REALISASI</th>
						<th class="esakip-text_tengah esakip-kiri esakip-kanan esakip-atas esakip-bawah" rowspan="2" style="width: 160px;">CATATAN</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $tbody; ?>
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
				<h5 class="modal-title" id="title-label">Tambah Rincian Belanja RKA/DPA</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="id_data" name="id_data">
				<div class="card bg-light mb-3">
					<div class="card-body" style="overflow:auto; height: 90vh;">
						<!-- Informasi RHK -->
						<div class="card bg-light mb-3">
							<div class="card-header">
								<strong>Informasi RHK</strong>
							</div>
							<div class="card-body">
								<table class="borderless-table mb-4">
									<tbody>
										<tr>
											<td class="text-left" style="width: 20%;"><strong>Nama RHK</strong></td>
											<td class="text-left"><strong>:</strong></td>
											<td class="text-left"><?php echo $renaksi['label']; ?></td>
										</tr>
										<tr>
											<td class="text-left"><strong>Indikator</strong></td>
											<td class="text-left"><strong>:</strong></td>
											<td class="text-left"><?php echo $ind_renaksi['indikator']; ?></td>
										</tr>
										<tr>
											<td class="text-left"><strong>Sub Kegiatan</strong></td>
											<td class="text-left"><strong>:</strong></td>
											<td class="text-left"><?php echo $renaksi['kode_cascading_sub_kegiatan'] . ' ' . $nama_sub_keg; ?></td>
										</tr>
									</tbody>
								</table>

								<div class="bg-light p-3 rounded">
									<div class="row">
										<div class="col-md-3 mb-3">
											<div class="text-muted text-center">Rencana Pagu</div>
											<div class="amount font-weight-bold text-primary text-center"><?php echo number_format($ind_renaksi['rencana_pagu'], 2, ',', '.'); ?></div>
										</div>
										<div class="col-md-3 mb-3">
											<div class="text-muted text-center">Total Rincian</div>
											<div class="amount font-weight-bold text-primary text-center"><?php echo number_format($total_all, 2, ',', '.'); ?></div>
										</div>
										<div class="col-md-3 mb-3">
											<div class="text-muted text-center">Sisa Rencana Pagu</div>
											<div class="amount font-weight-bold text-success text-center"><?php echo number_format($sisa_pagu_rhk, 2, ',', '.'); ?></div>
										</div>
										<div class="col-md-3 mb-3">
											<div class="text-muted text-center">Total Realisasi</div>
											<div class="amount font-weight-bold text-primary text-center"><?php echo number_format($total_all_realisasi, 2, ',', '.'); ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card bg-light mb-3">
							<table id="tableRincian" class="mt-2 table table-hover">
								<thead style="background-color: #343a40; color: #fff; text-align: center;">
									<tr>
										<th scope="col" class="text-center" style="width: 35px;">
											<input type="checkbox" value="" id="checkAll">
										</th>
										<th scope="col" class="text-center">Nama Akun / Rincian Belanja</th>
										<th scope="col" class="text-center" style="width: 75px;">Harga Satuan</th>
										<th scope="col" class="text-center" style="width: 75px;">Volume</th>
										<th scope="col" class="text-center" style="width: 75px;">Satuan</th>
										<th scope="col" class="text-center" style="width: 160px;">Total</th>
										<th scope="col" class="text-center" style="width: 160px;">Realisasi</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" onclick="simpanCheckedTagRinciBl()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade mt-4" id="modalTambahDataManual" tabindex="-1" role="dialog" aria-labelledby="modalTambahDataManual" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title-label">Tambah Rincian Belanja Manual</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="id_data" name="id_data">

				<!-- Informasi RHK -->
				<div class="card bg-light mb-3">
					<div class="card-header">
						<strong>Informasi RHK</strong>
					</div>
					<div class="card-body">
						<table class="borderless-table mb-4">
							<tbody>
								<tr>
									<td class="text-left" style="width: 20%;"><strong>Nama RHK</strong></td>
									<td class="text-left"><strong>:</strong></td>
									<td class="text-left"><?php echo $renaksi['label']; ?></td>
								</tr>
								<tr>
									<td class="text-left"><strong>Indikator</strong></td>
									<td class="text-left"><strong>:</strong></td>
									<td class="text-left"><?php echo $ind_renaksi['indikator']; ?></td>
								</tr>
								<tr>
									<td class="text-left"><strong>Sub Kegiatan</strong></td>
									<td class="text-left"><strong>:</strong></td>
									<td class="text-left"><?php echo $renaksi['kode_cascading_sub_kegiatan'] . ' ' . $nama_sub_keg; ?></td>
								</tr>
							</tbody>
						</table>

						<div class="bg-light p-3 rounded">
							<div class="row">
								<div class="col-md-3 mb-3">
									<div class="text-muted text-center">Rencana Pagu</div>
									<div class="amount font-weight-bold text-primary text-center"><?php echo number_format($ind_renaksi['rencana_pagu'], 2, ',', '.'); ?></div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="text-muted text-center">Total Rincian</div>
									<div class="amount font-weight-bold text-primary text-center"><?php echo number_format($total_all, 2, ',', '.'); ?></div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="text-muted text-center">Sisa Rencana Pagu</div>
									<div class="amount font-weight-bold text-success text-center"><?php echo number_format($sisa_pagu_rhk, 2, ',', '.'); ?></div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="text-muted text-center">Total Realisasi</div>
									<div class="amount font-weight-bold text-primary text-center"><?php echo number_format($total_all_realisasi, 2, ',', '.'); ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Informasi Akun -->
				<div class="card bg-light mb-3">
					<div class="card-header">
						<strong>Akun</strong>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="kode_akun">Pilih Akun</label>
								<select class="form-control" id="kode_akun" name="kode_akun" style="width: 100%;">
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="subs_bl_teks">Pengelompokan</label>
								<select name="subs_bl_teks" class="form-control" id="subs_bl_teks">
									<option value="">Pilih atau Input Kelompok</option>
									<?php echo $option_subs; ?>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="ket_bl_teks">Keterangan</label>
								<select name="ket_bl_teks" class="form-control" id="ket_bl_teks">
									<option value="">Pilih atau Input Keterangan</option>
									<?php echo $option_keterangan; ?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<!-- Informasi Rincian -->
				<div class="card bg-light mb-3">
					<div class="card-header">
						<strong>Komponen Rincian Belanja</strong>
					</div>
					<div class="card-body">
						<div class="form-row">
							<!-- Kolom Kiri -->
							<div class="col-md-6">
								<div class="form-group">
									<label for="nama_komponen">Nama Komponen</label>
									<input type="text" class="form-control" id="nama_komponen" name="nama_komponen" placeholder="Masukkan Nama Komponen">
								</div>
								<div class="form-group">
									<label for="keterangan">Catatan</label>
									<textarea class="form-control" id="keterangan" name="keterangan" rows="5" placeholder="Masukkan Catatan"></textarea>
								</div>
							</div>

							<!-- Kolom Kanan -->
							<div class="col-md-6">
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="volume">Volume</label>
										<input type="number" class="form-control" id="volume" name="volume" placeholder="Masukkan Volume" oninput="hitungTotalHarga()">
									</div>
									<div class="form-group col-md-6">
										<label for="satuan">Satuan</label>
										<select class="form-control" id="satuan" name="satuan">
											<option value="">Pilih Satuan</option>
											<?php echo $option_satuan; ?>
										</select>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="harga_satuan">Harga Satuan</label>
										<input type="number" class="form-control" id="harga_satuan" name="harga_satuan" placeholder="Masukkan Harga Satuan" oninput="hitungTotalHarga()">
									</div>
									<div class="form-group col-md-6">
										<label for="realisasi">Realisasi</label>
										<input type="number" class="form-control" id="realisasi" name="realisasi" placeholder="Masukkan Realisasi" onkeyup="validasiNilaiRealisasi()">
									</div>
								</div>
								<div class="form-group pl-3">
									<label for="total_harga">Total Harga</label>
									<input type="hidden" id="total_harga_asli" value="">
									<h3 class="font-weight-bold" id="total_harga"></h3>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" onclick="simpanRinciBlManual()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(() => {
		window.statusWpsipd = '<?php echo esc_js($wpsipd_status); ?>';
		window.tahunAnggaran = '<?php echo esc_js($tahun); ?>';
		window.kodeSbl = '<?php echo esc_js($renaksi['kode_sbl']); ?>';
		window.idIndikator = '<?php echo esc_js($id_indikator); ?>';
		window.hak_akses_user_pegawai = <?php echo $hak_akses_user_pegawai; ?>;
		window.text_pesan = '<?php echo $text_pesan; ?>';
		window.data_changed = false;

		if (statusWpsipd) {
			loadRkaWpSipd(tahunAnggaran, kodeSbl, idIndikator);
		}

		jQuery('#modalTambahDataManual').on('hidden.bs.modal', function() {
			// Tampilkan konfirmasi setelah modal tertutup dan ada data berubah
			if (window.data_changed === true) {
				if (confirm('Data telah berubah. Apakah Anda ingin merefresh halaman?')) {
					location.reload(); // Refresh halaman
				}
			}
		});

		jQuery('#modalTambahData').on('hidden.bs.modal', function() {
			// Tampilkan konfirmasi setelah modal tertutup dan ada data berubah
			if (window.data_changed === true) {
				if (confirm('Data telah berubah. Apakah Anda ingin merefresh halaman?')) {
					location.reload(); // Refresh halaman
				}
			}
		});

		jQuery('#kode_akun').select2({
			dropdownParent: jQuery('#modalTambahDataManual .modal-body'), // Tentukan modal sebagai parent dropdown agar select2 search tidak error
			placeholder: 'Masukkan Kode atau Nama Akun',
			ajax: {
				url: esakip.url,
				type: 'POST',
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						action: "get_data_akun",
						api_key: esakip.api_key,
						tahun_anggaran: tahunAnggaran,
						search: params.term,
						page: params.page || 0
					};
				},
				processResults: function(data) {
					return {
						results: data.results,
						pagination: {
							more: data.pagination.more
						}
					};
				},
				error: function(xhr) {
					alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
				},
				cache: true
			},
			minimumInputLength: 3
		});

		if((hak_akses_user_pegawai == 1 || hak_akses_user_pegawai == 2) && text_pesan != '-'){
			jQuery('.rincian_rka').attr('title', text_pesan);
			jQuery('.rincian_manual').attr('title', text_pesan);
			alert(text_pesan);
		}

	});

	function loadRkaWpSipd(tahunAnggaran, kodeSbl, idIndikator) {
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			url: esakip.url,
			type: "POST",
			data: {
				action: "get_sub_keg_rka_wpsipd",
				api_key: esakip.api_key,
				tahun_anggaran: tahunAnggaran,
				kode_sbl: kodeSbl,
				id_indikator: idIndikator
			},
			dataType: "json",
			success: function(response) {
				jQuery("#wrap-loading").hide();
				const tableBody = jQuery("#tableRincian tbody");
				tableBody.empty();

				const data = response.data;

				// Group data
				window.groupedData = {};
				data.forEach((item) => {
					const {
						kode_akun: kodeAkun,
						nama_akun: namaAkun,
						subs_bl_teks: subsBl,
						ket_bl_teks: ketBl,
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
					const subsKey = `${kodeAkun}-${subsBl}`; // Tambahkan kode akun untuk unik
					if (!groupedData[kodeAkun].subs[subsBl]) {
						groupedData[kodeAkun].subs[subsBl] = {
							idUnik: subsKey,
							namaKelompok: subsBl,
							total: 0,
							totalRealisasi: 0,
							ket: {},
						};
					}

					// Ket (Keterangan)
					const ketKey = `${subsKey}-${ketBl}`; // Tambahkan subsKey untuk unik
					if (!groupedData[kodeAkun].subs[subsBl].ket[ketBl]) {
						groupedData[kodeAkun].subs[subsBl].ket[ketBl] = {
							idUnik: ketKey,
							namaKeterangan: ketBl,
							total: 0,
							totalRealisasi: 0,
							data: [],
						};
					}

					// Rinci
					groupedData[kodeAkun].subs[subsBl].ket[ketBl].data.push(item);

					// Keterangan
					groupedData[kodeAkun].subs[subsBl].ket[ketBl].total += parseFloat(totalHarga);
					groupedData[kodeAkun].subs[subsBl].ket[ketBl].totalRealisasi += parseFloat(totalRealisasi);

					// Subs (kelompok)
					groupedData[kodeAkun].subs[subsBl].total += parseFloat(totalHarga);
					groupedData[kodeAkun].subs[subsBl].totalRealisasi += parseFloat(totalRealisasi);

					// Akun
					groupedData[kodeAkun].total += parseFloat(totalHarga);
					groupedData[kodeAkun].totalRealisasi += parseFloat(totalRealisasi);
				});

				// Render data ke tabel
				Object.values(groupedData).forEach((akunData) => {
					tableBody.append(`
						<tr class="akun-row" data-id="${akunData.kodeAkun}">
							<td class="text-center">
								<input class="akun-checkbox" type="checkbox" value="${akunData.kodeAkun}">
							</td>
							<td colspan="4" class="pl-3">
								${akunData.kodeAkun} ${akunData.namaAkun}
							</td>
							<td class="text-right">
								${formatRupiah(akunData.total)}
							</td>
							<td class="text-right">
								${formatRupiah(akunData.totalRealisasi)}
							</td>
						</tr>
					`);

					Object.values(akunData.subs).forEach((subsData) => {
						tableBody.append(`
							<tr class="subs-row" data-parent-id="${akunData.kodeAkun}" data-id="${subsData.idUnik}">
								<td class="text-center">
									<input class="subs-checkbox" type="checkbox" value="${subsData.idUnik}">
								</td>
								<td colspan="4" class="pl-4">
									${subsData.namaKelompok}
								</td>
								<td class="text-right">
									${formatRupiah(subsData.total)}
								</td>
								<td class="text-right">
									${formatRupiah(subsData.totalRealisasi)}
								</td>
							</tr>
						`);

						Object.values(subsData.ket).forEach((ketData) => {

							tableBody.append(`
								<tr class="ket-row" data-parent-id="${subsData.idUnik}" data-id="${ketData.idUnik}" data-grandparent-id="${akunData.kodeAkun}">
									<td class="text-center">
										<input class="ket-checkbox" type="checkbox" value="${ketData.idUnik}">
									</td>
									<td colspan="4" class="pl-5">${ketData.namaKeterangan}</td>
									<td class="text-right">
										${formatRupiah(ketData.total)}
									</td>
									<td class="text-right">
										${formatRupiah(ketData.totalRealisasi)}
									</td>
								</tr>
							`);

							Object.values(ketData.data).forEach((rinci) => {
								const isChecked = rinci.is_checked ? "checked" : "";
								let displayLabel = 'display:none;';

								let list_labels = [];
								let check_existing = false;

								Object.entries(rinci.labels).forEach(([key, label]) => {
									// Cek apakah id_indikator cocok
									if (label.id_indikator == idIndikator) {
										check_existing = label; // Jika ada yang sama, simpan label
										return;
									}

									// Tambahkan label ke list_labels
									let totalRincian = label.volume * rinci.harga_satuan
									list_labels.push(`
										<tr class="no-hover-but-input">
											<td class="align-middle text-left">
												${label.nama_rhk || "-"}
											</td>
											<td class="align-middle text-left">
												${label.nama_indikator || "-"}
											</td>
											<td class="align-middle text-center">
												${label.volume || "-"}
											</td>
											<td class="align-middle text-center">
												${rinci.satuan || "-"}
											</td>
											<td class="align-middle text-right">
												${formatRupiah(totalRincian) || "-"}
											</td>
											<td class="align-middle text-right">
												${formatRupiah(label.realisasi) || "-"}
											</td>
											<td class="align-middle text-right">
												${label.keterangan || "-"}
											</td>
										</tr>
									`);
								});

								if (list_labels != '' || check_existing != false) {
									displayLabel = '';
								}

								let label_nama_rhk = '<?php echo $renaksi['label']; ?>';
								let label_nama_indikator = '<?php echo $ind_renaksi['indikator']; ?>'
								let label_volume = rinci.volume || 0;
								let label_realisasi = rinci.realisasi || 0;
								let label_keterangan = '';
								if (check_existing) {
									label_nama_rhk = check_existing.nama_rhk || '-';
									label_nama_indikator = check_existing.nama_indikator || '-';
									label_volume = check_existing.volume || 0;
									label_realisasi = check_existing.realisasi || 0;
									label_keterangan = check_existing.keterangan || '';
								}
								list_labels = `
									<tr class="no-hover-but-input">
										<td class="align-middle text-left">
											${label_nama_rhk}
										</td>
										<td class="align-middle text-left">
											${label_nama_indikator}
										</td>
										<td>
											<input type="number" class="align-middle form-control text-center volume-pisah" onkeyup="handleChangeVolume(${rinci.id_rinci_sub_bl}, ${rinci.rincian}, ${rinci.volume})" id="volumePisah${rinci.id_rinci_sub_bl}" value="${label_volume}">
										</td>
										<td class="align-middle text-center">
											${rinci.satuan}
										</td>
										<td class="align-middle text-right" id="anggaranPisah${rinci.id_rinci_sub_bl}">
										</td>
										<td>
											<input type="number" class="align-middle form-control text-right" id="realisasiPisah${rinci.id_rinci_sub_bl}" value="${label_realisasi}">
										</td>
										<td>
											<textarea class="align-middle form-control text-right" id="keteranganPisah${rinci.id_rinci_sub_bl}">${label_keterangan}</textarea>
										</td>
									</tr>
								` + list_labels.join('');


								tableBody.append(`
									<tr class="rinci-row" data-id="${rinci.id_rinci_sub_bl}" data-parent-id="${ketData.idUnik}" data-grandparent-id="${subsData.idUnik}" data-greatgrandparent-id="${akunData.kodeAkun}">
										<td class="text-center">
           									<input class="rinci-checkbox" type="checkbox" value="${rinci.id_rinci_sub_bl}" ${isChecked}>
										</td>
										<td class="pl-5">
											${rinci.nama_komponen}
										</td>
										<td class="text-right">
											${formatRupiah(parseInt(rinci.harga_satuan))}
										</td>
										<td class="text-center">
											${formatRupiah(rinci.volume)}
										</td>
										<td class="text-center">
											${rinci.satuan}
										</td>
										<td class="text-right">
											${formatRupiah(rinci.rincian)}
										</td>
										<td class="text-right">
											${formatRupiah(rinci.realisasi)}
										</td>
									</tr>
									<tr id="parentDetail${rinci.id_rinci_sub_bl}" style="${displayLabel}" class="no-hover">
										<td colspan="7">
											<table class="table table-bordered">
												<thead style="background-color: #343a40; color: #fff;">
													<tr>
														<th class="text-center">Rencana Hasil Kerja</th>
														<th class="text-center" style="width: 100px;">Indikator</th>
														<th class="text-center" style="width: 100px;">Volume</th>
														<th class="text-center" style="width: 75px;">Satuan</th>
														<th class="text-center" style="width: 135px;">Anggaran</th>
														<th class="text-center" style="width: 135px;">Realisasi</th>
														<th class="text-center" style="width: 200px;">Keterangan</th>
													</tr>
												</thead>
												<tbody>
													${list_labels}
												</tbody>
											</table>
										</td>
									</tr>
								`);
							});

						});
					});
				});
				handleCheckboxRinci()
				// jQuery('.rinci-checkbox').trigger('change')
				jQuery('.volume-pisah').keyup()
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
				jQuery("#wrap-loading").hide();
				alert("Terjadi kesalahan saat memuat rincian wp-sipd!");
			},
		});
	}

	function handleChangeVolume(idRinciSubBl, totalAnggaran, totalVolume) {
		const volumeElement = jQuery(`#volumePisah${idRinciSubBl}`);
		const anggaranElement = jQuery(`#anggaranPisah${idRinciSubBl}`);

		// Ambil nilai volume yang diinputkan
		const volume = parseFloat(volumeElement.val()) || 0;
		if (volume > totalVolume) {
			alert('Volume rincian pisah anggaran tidak boleh lebih besar dari volume aslinya!');
			return volumeElement.val(totalVolume);
		}

		// Hitung anggaran berdasarkan volume yang diinputkan
		const anggaranPerVolume = totalVolume > 0 ? totalAnggaran / totalVolume : 0;
		const anggaran = volume * anggaranPerVolume;

		// Tampilkan anggaran yang diperbarui
		anggaranElement.text(new Intl.NumberFormat("id-ID").format(anggaran));
	}

	function hitungTotalHarga() {
		const volume = parseFloat(jQuery('#volume').val()) || 0;
		const hargaSatuan = parseFloat(jQuery('#harga_satuan').val()) || 0;
		const totalHarga = volume * hargaSatuan;

		jQuery('#total_harga').text(totalHarga.toLocaleString('id-ID', {
			style: 'currency',
			currency: 'IDR'
		}));

		jQuery('#total_harga_asli').val(totalHarga);
	}


	function handleCheckboxRinci() {
		// Checkbox utama (select all)
		jQuery("#checkAll").on("change", function() {
			const isChecked = jQuery(this).is(":checked");
			jQuery(".akun-checkbox, .subs-checkbox, .ket-checkbox").prop("checked", isChecked);
			jQuery(".rinci-checkbox").prop("checked", isChecked).trigger("change"); // Hanya trigger perubahan pada rinci-checkbox
		});

		// Akun ke subs_bl_teks
		jQuery(".akun-checkbox").on("change", function() {
			const isChecked = jQuery(this).is(":checked");
			const akunId = jQuery(this).val();
			jQuery(`.subs-row[data-parent-id="${akunId}"] .subs-checkbox`).prop("checked", isChecked);
			jQuery(`.ket-row[data-grandparent-id="${akunId}"] .ket-checkbox`).prop("checked", isChecked);
			jQuery(`.rinci-row[data-greatgrandparent-id="${akunId}"] .rinci-checkbox`)
				.prop("checked", isChecked)
				.trigger("change");
		});

		// Subs_bl_teks ke ket_bl_teks
		jQuery(".subs-checkbox").on("change", function() {
			const isChecked = jQuery(this).is(":checked");
			const subsId = jQuery(this).val();
			const parentId = jQuery(this).closest(".subs-row").data("parent-id");

			jQuery(`.ket-row[data-parent-id="${subsId}"] .ket-checkbox`).prop("checked", isChecked);
			jQuery(`.rinci-row[data-grandparent-id="${subsId}"] .rinci-checkbox`)
				.prop("checked", isChecked)
				.trigger("change");

			updateParentCheckbox(parentId, ".akun-checkbox", ".subs-checkbox");
		});

		// Ket_bl_teks ke id_rinci_sub_bl
		jQuery(".ket-checkbox").on("change", function() {
			const isChecked = jQuery(this).is(":checked");
			const ketId = jQuery(this).val();
			const parentId = jQuery(this).closest(".ket-row").data("parent-id");

			jQuery(`.rinci-row[data-parent-id="${ketId}"] .rinci-checkbox`)
				.prop("checked", isChecked)
				.trigger("change");

			updateParentCheckbox(parentId, ".subs-checkbox", ".ket-checkbox");
		});

		// Id_rinci_sub_bl ke ket_bl_teks
		jQuery(".rinci-checkbox").on("change", function() {
			const parentId = jQuery(this).closest(".rinci-row").data("parent-id");

			const idRinci = jQuery(this).val();
			if (jQuery(this).is(":checked")) {
				jQuery(`#parentDetail${idRinci}`).show();
			} else {
				jQuery(`#parentDetail${idRinci}`).hide();
			}

			updateParentCheckbox(parentId, ".ket-checkbox", ".rinci-checkbox");
		});

		// Update parent checkbox
		function updateParentCheckbox(parentId, parentSelector, childSelector) {
			const allChildren = jQuery(`${childSelector}[data-parent-id="${parentId}"]`);
			const parentCheckbox = jQuery(`${parentSelector}[data-id="${parentId}"]`);
			parentCheckbox.prop("checked", allChildren.length === allChildren.filter(":checked").length);
		}
	}

	function handleModalTambahDataManual() {
		jQuery('#subs_bl_teks').select2({
			width: '100%',
			dropdownParent: jQuery('#modalTambahDataManual .modal-body'), // Tentukan modal sebagai parent dropdown agar select2 search tidak error
			placeholder: 'Pilih Kelompok / Masukan Nama Kelompok...',
			tags: true,
		});
		jQuery('#ket_bl_teks').select2({
			width: '100%',
			dropdownParent: jQuery('#modalTambahDataManual .modal-body'), // Tentukan modal sebagai parent dropdown agar select2 search tidak error
			placeholder: 'Pilih Keterangan / Masukan Nama Keterangan...',
			tags: true,
		});
		jQuery('#satuan').select2({
			width: '100%',
			dropdownParent: jQuery('#modalTambahDataManual .modal-body'), // Tentukan modal sebagai parent dropdown agar select2 search tidak error
			placeholder: 'Pilih Satuan...',
		});
		jQuery('#id_data').val('')
		jQuery('#total_harga_asli').val('')
		jQuery('#kode_akun').val('').trigger('change')
		jQuery('#subs_bl_teks').val('').trigger('change')
		jQuery('#ket_bl_teks').val('').trigger('change')
		jQuery('#nama_komponen').val('')
		jQuery('#volume').val('').trigger('input')
		jQuery('#satuan').val('').trigger('change')
		jQuery('#harga_satuan').val('').trigger('input')
		jQuery('#keterangan').val('')
		jQuery('#modalTambahDataManual').modal('show')
	}

	function handleModalTambahDataWpsipd() {
		jQuery('#modalTambahData').modal('show')
	}

	function simpanCheckedTagRinciBl() {
		let checkedRinci = [];
		let dataRinci = [];
		let valid = true; // Flag untuk validasi

		// Iterasi checkbox rincian yang dicentang
		jQuery(".rinci-checkbox:checked").each(function() {
			let rincianId = jQuery(this).val(); // ID rincian
			let namaKomponen = jQuery(this).closest(".rinci-row").find("td:nth-child(2)").text().trim(); // Nama komponen
			let kodeAkun = jQuery(this).closest(".rinci-row").data("greatgrandparent-id"); // Kode akun (level akun)
			let namaAkunFull = jQuery(`.akun-row[data-id="${kodeAkun}"]`).find("td:nth-child(2)").text().trim(); // Nama akun lengkap
			let namaAkun = namaAkunFull.split(" ").slice(1).join(" "); // Hanya ambil nama setelah kode akun
			let subs = jQuery(this).closest(".rinci-row").data("grandparent-id"); // Subs
			let ket = jQuery(this).closest(".rinci-row").data("parent-id"); // Ket
			let subsNama = jQuery(`.subs-row[data-id="${subs}"]`).find("td:nth-child(2)").text().trim(); // Subs nama dari teks langsung
			let ketNama = jQuery(`.ket-row[data-id="${ket}"]`).find("td:nth-child(2)").text().trim(); // Ket nama dari teks langsung
			let hargaSatuanText = jQuery(this).closest(".rinci-row").find("td:nth-child(3)").text(); // Ambil teks harga satuan
			let hargaSatuan = parseFloat(hargaSatuanText.replace(/\./g, "").replace(",", ".")); // Hapus pemisah ribuan dan ubah ke angka
			let satuan = jQuery(this).closest(".rinci-row").find("td:nth-child(5)").text().trim(); // Satuan (kolom ke-5)
			let volume = parseFloat(jQuery(`#volumePisah${rincianId}`).val()) || 0; // Input volume
			let realisasi = parseFloat(jQuery(`#realisasiPisah${rincianId}`).val()) || 0; // Input realisasi
			let keteranganPisah = jQuery(`#keteranganPisah${rincianId}`).val(); // Input keterangan

			// Validasi volume wajib diisi
			if (!volume) {
				valid = false;
				alert(`Volume harus diisi untuk komponen: ${namaKomponen}!`);
				return false;
			}

			// Validasi realisasi tidak boleh lebih besar dari (volume * hargaSatuan)
			const maxRealisasi = volume * hargaSatuan;
			if (realisasi > maxRealisasi) {
				valid = false;
				alert(
					`Nilai realisasi untuk komponen "${namaKomponen}" tidak boleh lebih besar dari Total Harga (${maxRealisasi.toLocaleString(
					"id-ID",
					{ style: "currency", currency: "IDR" }
				)})!`
				);
				return false;
			}

			// Tambahkan ID rincian ke array checked
			checkedRinci.push(rincianId);

			// Tambahkan data rincian ke array dataRinci
			dataRinci.push({
				id_rincian: rincianId,
				nama_komponen: namaKomponen,
				kode_akun: kodeAkun,
				nama_akun: namaAkun,
				subs: subsNama,
				ket: ketNama,
				keterangan: subs,
				harga_satuan: hargaSatuan,
				volume: volume,
				realisasi: realisasi,
				keterangan: keteranganPisah,
				satuan: satuan,
			});
		});

		// Jika validasi gagal, hentikan proses
		if (!valid) {
			return;
		}

		// Validasi jika tidak ada rincian yang dicentang
		if (checkedRinci.length === 0) {
			return alert("Harap pilih rincian belanja yang akan ditag!");
		}

		jQuery("#wrap-loading").show();
		const tempData = new FormData();
		tempData.append("action", "simpan_rinci_bl_tagging");
		tempData.append("api_key", esakip.api_key);
		tempData.append("rincian_belanja_ids", JSON.stringify(checkedRinci));
		tempData.append("data_rinci", JSON.stringify(dataRinci));
		tempData.append("tahun_anggaran", tahunAnggaran);
		tempData.append("kode_sbl", kodeSbl);
		tempData.append("id_skpd", '<?php echo $id_skpd; ?>');
		tempData.append("id_indikator", '<?php echo $id_indikator; ?>');

		jQuery.ajax({
			method: "POST",
			url: esakip.url,
			data: tempData,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			success: function(res) {
				alert(res.message);
				jQuery("#wrap-loading").hide();
				if (res.status === "success") {
					jQuery("#modalTambahData").modal("hide");
					window.data_changed = true;
				}
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
				jQuery("#wrap-loading").hide();
				alert("Terjadi kesalahan saat menyimpan data!");
			},
		});
	}



	function simpanRinciBlManual() {
		const validationRules = {
			'kode_akun': 'Silakan pilih akun terlebih dahulu.',
			'subs_bl_teks': 'Harap mengisi Subs BL Teks.',
			'ket_bl_teks': 'Harap mengisi Keterangan BL Teks.',
			'nama_komponen': 'Nama Komponen tidak boleh kosong. Silakan diisi.',
			'volume': 'Volume harus diisi. Mohon masukkan nilai volume.',
			'satuan': 'Silakan pilih satuan terlebih dahulu.',
			'harga_satuan': 'Harap mengisi Harga Satuan dengan benar.',
			'tahun_anggaran': 'Tahun Anggaran wajib diisi. Mohon periksa kembali.',
		};

		const {
			error,
			data
		} = validateForm(validationRules);
		if (error) {
			return alert(error);
		}

		const id_data = jQuery('#id_data').val();
		const keterangan = jQuery('#keterangan').val();
		const realisasi = jQuery('#realisasi').val();
		const tempData = new FormData();
		tempData.append("action", "simpan_rinci_bl_tagging_manual");
		tempData.append("api_key", esakip.api_key);
		tempData.append("tahun_anggaran", tahunAnggaran);
		tempData.append("kode_sbl", kodeSbl);
		tempData.append("id_skpd", '<?php echo $id_skpd; ?>');
		tempData.append("id_indikator", '<?php echo $id_indikator; ?>');
		tempData.append("keterangan", keterangan);
		tempData.append("realisasi", realisasi);

		for (const [key, value] of Object.entries(data)) {
			tempData.append(key, value);
		}

		if (id_data) {
			tempData.append("id_data", id_data);
		}

		jQuery("#wrap-loading").show();

		jQuery.ajax({
			method: "POST",
			url: esakip.url,
			data: tempData,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			success: function(res) {
				alert(res.message);
				jQuery("#wrap-loading").hide();
				if (res.status === "success") {
					jQuery('#modalTambahDataManual').modal('hide');
					window.data_changed = true
				}
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
				jQuery("#wrap-loading").hide();
				alert("Terjadi kesalahan saat menyimpan data!");
			},
		});
	}

	function validasiNilaiRealisasi() {
		const totalHargaAsli = parseFloat(jQuery('#total_harga_asli').val()) || 0;
		let realisasi = parseFloat(jQuery('#realisasi').val()) || 0;

		if (realisasi > totalHargaAsli) {
			alert('Nilai realisasi tidak boleh lebih besar dari Total Harga!');

			realisasi = totalHargaAsli;
			jQuery('#realisasi').val(realisasi);
		}
	}

	function deleteRincianById(id) {
		if (!confirm('Apakah Anda yakin ingin menghapus rincian ini?')) {
			return;
		}
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'delete_rincian_tagging_by_id_rinci_bl',
				api_key: esakip.api_key,
				id: id
			},
			dataType: 'json',
			success: function(response) {
				console.log(response);
				jQuery('#wrap-loading').hide();
				if (response.status === 'success') {
					alert(response.message);
					location.reload()
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
				jQuery('#wrap-loading').hide();
				alert('Terjadi kesalahan saat mengirim data!');
			}
		});
	}

	function editDataRincian(id) {
		jQuery('#wrap-loading').show();
		jQuery.ajax({
			url: esakip.url,
			type: 'POST',
			data: {
				action: 'get_rinci_tagging_by_id',
				api_key: esakip.api_key,
				id: id
			},
			dataType: 'json',
			success: function(response) {
				jQuery('#wrap-loading').hide();
				if (response.status === 'success') {
					let data = response.data;

					// Input biasa
					jQuery('#id_data').val(data.id);
					jQuery('#nama_komponen').val(data.nama_komponen);
					jQuery('#volume').val(data.volume).trigger('input');
					jQuery('#harga_satuan').val(data.harga_satuan).trigger('input');
					jQuery('#realisasi').val(data.realisasi);
					jQuery('#keterangan').val(data.keterangan);

					// Select2 dengan Ajax (kode_akun)
					jQuery('#kode_akun').select2("trigger", "select", {
						data: {
							id: data.kode_akun,
							text: data.kode_akun + ' - ' + data.nama_akun
						}
					});

					// Select2 dengan input bebas (subs_bl_teks dan ket_bl_teks)
					jQuery('#subs_bl_teks')
						.append(new Option(data.subs_bl_teks, data.subs_bl_teks, true, true))
						.trigger('change');
					jQuery('#ket_bl_teks')
						.append(new Option(data.ket_bl_teks, data.ket_bl_teks, true, true))
						.trigger('change');

					jQuery('#satuan').val(data.satuan).trigger('change');

					jQuery('#modalTambahDataManual').modal('show');
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				jQuery('#wrap-loading').hide();
				console.error(xhr.responseText);
				alert('Terjadi kesalahan saat memuat data!');
			}
		});
	}
</script>