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
        width: 2900px; 
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
            <h1 class="text-center" style="margin:3rem;">Rincian Belanja Rencana Aksi <br><?php echo $skpd['nama_skpd'] ?><br> Tahun Anggaran <?php echo $tahun; ?></h1>
            <?php if (!$is_admin_panrb && $hak_akses_user): ?>
                <div id="action" class="action-section hide-excel"></div>
            <?php endif; ?>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">RENCANA AKSI</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">OUTCOME/OUTPUT</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN KEGIATAN RENCANA AKSI</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN TEKNIS KEGIATAN</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 100px;">SATUAN</th>
                            <th class="text-center" colspan="6" style="width: 400px;">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" colspan="4" style="width: 250px;">REALISASI KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI (%)</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">RENCANA PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">ALOKASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">PAGU SUB KEGIATAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">KETERANGAN</th>
                        </tr>
                        <tr>
                            <th>AWAL</th>
                            <th>TW-I</th>
                            <th>TW-II</th>
                            <th>TW-III</th>
                            <th>TW-IV</th>
                            <th>AKHIR</th>
                            <th>TW-I</th>
                            <th>TW-II</th>
                            <th>TW-III</th>
                            <th>TW-IV</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>