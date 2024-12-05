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
}else{
	die('<h1 class="text-center">Param "id_skpd" tidak boleh kosong!</h1>');
}
if (!empty($_GET) && !empty($_GET['tahun'])) {
    $tahun = $wpdb->prepare('%d', $_GET['tahun']);
}else{
	die('<h1 class="text-center">Param "tahun" tidak boleh kosong!</h1>');
}
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_indikator = $wpdb->prepare('%d', $_GET['id_indikator']);
}else{
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
", $tahun), ARRAY_A);

if(empty($data_id_jadwal['id_jadwal'])){
    $id_jadwal = 0;
}else{
    $id_jadwal = $data_id_jadwal['id_jadwal'];
}
$cek_id_jadwal = empty($data_id_jadwal['id_jadwal']) ? 0 : 1;

if(empty($data_id_jadwal['id_jadwal_wp_sipd'])){
    $id_jadwal_wpsipd = 0;
}else{
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

$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2 ;

$cek_settingan_menu = $wpdb->get_var(
    $wpdb->prepare(
    "SELECT 
        jenis_role
    FROM esakip_menu_dokumen 
    WHERE nama_dokumen='Rencana Aksi'
      AND user_role='perangkat_daerah' 
      AND active = 1
      AND tahun_anggaran=%d
", $tahun)
);

$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;

$ind_renaksi = $wpdb->get_row($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_rencana_aksi_indikator_opd
	WHERE id=%d
", $id_indikator), ARRAY_A);
if(empty($ind_renaksi)){
	die('<h1 class="text-center">Indikator Rencana Hasil Pekerjaan tidak ditemukan!</h1>');
}

$renaksi = $wpdb->get_row($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_rencana_aksi_opd
	WHERE id=%d
", $ind_renaksi['id_renaksi']), ARRAY_A);

$renaksi_parent1 = $wpdb->get_row($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_rencana_aksi_opd
	WHERE id=%d
", $renaksi['parent']), ARRAY_A);

$renaksi_parent2 = $wpdb->get_row($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_rencana_aksi_opd
	WHERE id=%d
", $renaksi_parent1['parent']), ARRAY_A);

$renaksi_parent3 = $wpdb->get_row($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_rencana_aksi_opd
	WHERE id=%d
", $renaksi_parent2['parent']), ARRAY_A);

$renaksi_parent_pemda = $wpdb->get_results($wpdb->prepare("
	SELECT 
		i.*,
		r.label,
		r.id as id_renaksi,
		r.parent
	FROM `esakip_data_label_rencana_aksi` l
	INNER JOIN esakip_data_rencana_aksi_indikator_pemda i ON l.parent_indikator_renaksi_pemda=i.id
	INNER JOIN esakip_data_rencana_aksi_pemda r ON l.parent_renaksi_pemda=r.id
	WHERE l.parent_renaksi_opd=%d
		AND i.active=1
		AND i.tahun_anggaran=%d
", $renaksi_parent1['parent'], $tahun), ARRAY_A);

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
	$renaksi_pemda4['label'][$val['id_renaksi']] = $val['label'].' ( '.$val['indikator'].' '.$val['target_akhir'].' '.$val['satuan'].' )';

}

// print_r($renaksi_parent_pemda); echo $wpdb->last_query;
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
    
	a.btn{
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
        font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; 
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
</style>

<!-- Table -->
<div class="container-md">
    <div id="cetak" title="Rencana Aksi Perangkat Daerah">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Rencana Hasil Kerja<br>Rincian Belanja Teknis Kegiatan<br><?php echo $skpd['nama_skpd'] ?><br> Tahun Anggaran <?php echo $tahun; ?></h1>
            <?php if (!$is_admin_panrb && $hak_akses_user): ?>
                <div id="action" class="action-section hide-excel"></div>
            <?php endif; ?>
            <table class="table table-bordered">
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
            <table class="table table-bordered">
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
            <table class="table table-bordered">
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
            <table class="table table-bordered">
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
            <table class="table table-bordered">
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
		                <td><?php echo $renaksi_parent3['kode_cascading_sasaran'].' '.$renaksi_parent3['label_cascading_sasaran']; ?></td>
		            </tr>
		        </tbody>
		    </table>
            <table class="table table-bordered">
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
		                <td><?php echo $renaksi_parent2['kode_cascading_program'].' '.$renaksi_parent2['label_cascading_program']; ?></td>
		            </tr>
		        </tbody>
		    </table>
            <table class="table table-bordered">
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
		                <td><?php echo $renaksi_parent1['kode_cascading_kegiatan'].' '.$renaksi_parent1['label_cascading_kegiatan']; ?></td>
		            </tr>
		        </tbody>
		    </table>
            <table class="table table-bordered">
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
		                <td><?php echo $renaksi['kode_cascading_sub_kegiatan'].' '.$renaksi['label_cascading_sub_kegiatan']; ?></td>
		            </tr>
		            <tr>
		                <td>Pagu Sub Kegiatan</td>
		                <td class="text-center">:</td>
		                <td>Rp 0</td>
		            </tr>
		        </tbody>
		    </table>
            <table class="table table-bordered">
		        <thead>
		            <tr>
		                <th class="text-center" rowspan="2" style="width: 400px;">INDIKATOR</th>
		                <th class="text-center" rowspan="2">SATUAN</th>
		                <th class="text-center" colspan="6">TARGET KEGIATAN PER TRIWULAN</th>
		                <th class="text-center" rowspan="2" style="width: 400px;">KETERANGAN</th>
		            </tr>
		            <tr>
		                <th class="text-center">AWAL</th>
		                <th class="text-center">TW-I</th>
		                <th class="text-center">TW-II</th>
		                <th class="text-center">TW-III</th>
		                <th class="text-center">TW-IV</th>
		                <th class="text-center">AKHIR</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<tr>
		        		<td><?php echo $ind_renaksi['indikator']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['satuan']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['target_awal']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['target_1']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['target_2']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['target_3']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['target_4']; ?></td>
		        		<td class="text-center"><?php echo $ind_renaksi['target_akhir']; ?></td>
		                <td></td>
		        	</tr>
		        </tbody>
		    </table>
            <table class="table table-bordered">
            	<thead>
		            <tr>
		                <th class="text-center" style="width: 25%;">PAGU RENCANA HASIL KERJA</th>
		                <th class="text-center" style="width: 25%;">ALOKASI APBD</th>
		                <th class="text-center" style="width: 25%;">REALISASI APBD</th>
		                <th class="text-center" style="width: 25%;">CAPAIAN REALIASI TERHADAP RENCANA PAGU</th>
		            </tr>
		        </thead>
		        <tbody>
		            <tr>
		        		<td class="text-center">Rp <?php echo $ind_renaksi['rencana_pagu']; ?></td>
		                <td class="text-center">Rp 0</td>
		        		<td class="text-center">Rp <?php echo $ind_renaksi['realisasi_pagu']; ?></td>
		                <td class="text-center">0%</td>
		            </tr>
		        </tbody>
		    </table>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 200px;">KODE REKENING</th>
                            <th class="text-center" rowspan="2">URAIAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">HARGA SATUAN</th>
                            <th class="text-center" rowspan="2" style="width: 90px;">JUMLAH</th>
                            <th class="text-center" rowspan="2" style="width: 100px;">SATUAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">TOTAL</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>