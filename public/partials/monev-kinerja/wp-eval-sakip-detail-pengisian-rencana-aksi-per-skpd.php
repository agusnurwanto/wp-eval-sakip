<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
$set_tabel_individu = get_option('_crb_set_tabel_individu');
$renaksi_pemda = array();
$data_id_jadwal = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        id_jadwal_rpjmd as id_jadwal,
        id_jadwal_wp_sipd
    FROM esakip_pengaturan_upload_dokumen
    WHERE tahun_anggaran =%d
    AND active=1
", $input['tahun']),
    ARRAY_A
);

if (empty($data_id_jadwal['id_jadwal'])) {
    $id_jadwal = 0;
} else {
    $id_jadwal = $data_id_jadwal['id_jadwal'];
}

if (empty($data_id_jadwal['id_jadwal_wp_sipd'])) {
    $id_jadwal_wpsipd = 0;
} else {
    $id_jadwal_wpsipd = $data_id_jadwal['id_jadwal_wp_sipd'];
}

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        *
    FROM esakip_data_unit
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);
if(empty($skpd)){
    die('<h1 class="text-center">Perangkat Daerah dengan ID = '.$id_skpd.', tahun = '.$input['tahun'].' tidak ditemukan!</h1>');
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$user_nip = $current_user->data->user_login;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

$admin_role_pemda = array(
    'admin_bappeda',
    'admin_ortala'
);

$this_admin_pemda = (array_intersect($admin_role_pemda, $user_roles)) ? 1 : 2;

// hak akses user pegawai
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
        FROM esakip_data_pegawai_simpeg
        WHERE nip_baru=%s
            AND active=%d
        ORDER BY satker_id ASC, tipe_pegawai_id ASC",
        $user_nip,
        1
    ),
    ARRAY_A
);

$hak_akses_user_pegawai = 0;
$nip_user_pegawai = 0;
$skpd_user_pegawai = array();
$hak_akses_user_pegawai_per_skpd = array();
if (!empty($data_user_pegawai)) {
    foreach ($data_user_pegawai as $k_user => $v_user) {
        $satker_pegawai_simpeg = substr($v_user['satker_id'], 0, 2);
        $skpd_user_pegawai = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT 
                    simpeg.id_satker_simpeg,
                    unit.nama_skpd, 
                    unit.id_skpd, 
                    unit.kode_skpd,
                    unit.nipkepala,
                    unit.is_skpd
                FROM esakip_data_mapping_unit_sipd_simpeg AS simpeg
                JOIN esakip_data_unit AS unit ON simpeg.id_skpd = unit.id_skpd
                WHERE simpeg.id_satker_simpeg=%d 
                    AND simpeg.tahun_anggaran=%d
                    AND simpeg.active=%d
                    AND unit.tahun_anggaran=%d
                    AND unit.active=%d
                GROUP BY unit.id_skpd",
                $satker_pegawai_simpeg,
                $input['tahun'],
                1,
                $tahun_anggaran_sakip,
                1
            ),
            ARRAY_A
        );

        // TIPE HAK AKSES USER PEGAWAI | 0 = TIDAK ADA | 1 = ALL | 2 = HANYA RHK TERKAIT
        if (!empty($skpd_user_pegawai)) {
            if (
                // jika pegawai adalah kepala dinas di data simpeg
                (
                    $skpd_user_pegawai['id_skpd'] == $id_skpd 
                    && $v_user['tipe_pegawai_id'] == 11 
                    && strlen($v_user['satker_id']) == 2
                )
                // jika pegawai adalah kepala di data sipd dan satker id pegawainya berjumlah 2
                || (
                    $skpd_user_pegawai['id_skpd'] == $id_skpd 
                    && $v_user['nip_baru'] == $skpd_user_pegawai['nipkepala']
                    && strlen($v_user['satker_id']) == 2
                )
            ){
                $hak_akses_user_pegawai = 1;
            } else if ($skpd_user_pegawai['id_skpd'] == $id_skpd) {
                $hak_akses_user_pegawai = 2;
            }
            $nip_user_pegawai = $v_user['nip_baru'];
            if(empty($hak_akses_user_pegawai_per_skpd[$skpd_user_pegawai['id_skpd']])){
                $hak_akses_user_pegawai_per_skpd[$skpd_user_pegawai['id_skpd']] = $hak_akses_user_pegawai;
            }
        }
    }
}

// print_r($data_user_pegawai); print_r($skpd_user_pegawai); print_r($hak_akses_user_pegawai_per_skpd); die($wpdb->last_query);

// jika user admin
if (
    $is_administrator 
    || $this_admin_pemda == 1
){
    $hak_akses_user_pegawai = 1;
    $nip_user_pegawai = 0;

// jika user PA SIPD
}else if($skpd['nipkepala'] == $user_nip){
    $hak_akses_user_pegawai = 1;
    $nip_user_pegawai = $user_nip;

// ----- hak akses by skpd terkait ----- //
}else if(!empty($hak_akses_user_pegawai_per_skpd[$id_skpd])){
    $hak_akses_user_pegawai = $hak_akses_user_pegawai_per_skpd[$id_skpd];
}

////////////end////////////
$renaksi_pemda = $wpdb->get_results($wpdb->prepare("
    SELECT 
        *
    FROM esakip_detail_rencana_aksi_pemda 
    WHERE active = 1
        AND id_skpd = %d
        AND tahun_anggaran = %d
", $id_skpd, $input['tahun']), ARRAY_A);

$get_data_pemda = array();

if (!empty($renaksi_pemda)) {
    $id_pk = array();
    $id_detail_renaksi_pemda = [];

    foreach ($renaksi_pemda as $item) {
        $id_pk[] = $item['id_pk'];
        $id_detail_renaksi_pemda[$item['id_pk']] = $item['id']; 
    }

    $get_id_pk = implode(',', $id_pk);

    if (!empty($get_id_pk)) {
        $get_data_pemda = $wpdb->get_results("
            SELECT 
                pk.*,
                ik.label_sasaran as ik_label_sasaran,
                ik.label_indikator as ik_label_indikator
            FROM esakip_laporan_pk_pemda pk
            LEFT JOIN esakip_data_iku_pemda ik
                ON pk.id_iku = ik.id 
                AND pk.id_jadwal = ik.id_jadwal
            WHERE pk.active = 1
                AND pk.id IN ($get_id_pk)
        ", ARRAY_A);

        foreach ($get_data_pemda as &$row) {
            $row['id_detail'] = $id_detail_renaksi_pemda[$row['id']] ?? null;
        }
    }
}

$renaksi_opd = $wpdb->get_results(
    $wpdb->prepare("
        SELECT
            *
        FROM esakip_data_rencana_aksi_opd
        WHERE id_skpd = %d
          AND tahun_anggaran = %d
          AND active = 1
          AND level = 2
          AND parent IS NOT NULL
    ", $id_skpd, $input['tahun']),
    ARRAY_A
);

$set_renaksi = get_option('_crb_input_renaksi');
$get_mapping = $wpdb->get_var($wpdb->prepare('
    SELECT 
        u.id_satker_simpeg
    FROM esakip_data_mapping_unit_sipd_simpeg AS u
    WHERE u.tahun_anggaran = %d
        AND u.id_skpd = %d
        AND u.active = 1
', $tahun_anggaran_sakip, $id_skpd));
if (empty($get_mapping)) {
    $ret['message'] = 'ID Satker SIMPEG belum dimapping dengan ID SKPD!';
}

$all_sakter = array();
$get_satker = $wpdb->get_results($wpdb->prepare('
    SELECT 
        s.id,
        s.satker_id,
        s.active,
        s.nama
    FROM esakip_data_satker_simpeg AS s
    WHERE s.satker_id like %s
        AND s.tahun_anggaran = %d
    ORDER BY satker_id ASC
', $get_mapping . '%', $tahun_anggaran_sakip), ARRAY_A);
$select_satker = '<option value="">Pilih Satuan Kerja</option>';
foreach ($get_satker as $satker) {
    $select_satker .= '<option value="' . $satker['satker_id'] . '">' . $satker['satker_id'] . ' | ' . $satker['nama'] . '</option>';
    $all_sakter[$satker['satker_id']] = $satker['nama'];
}
$get_pegawai = $wpdb->get_results(
    $wpdb->prepare('
        SELECT 
            s.*
        FROM esakip_data_pegawai_simpeg s
        WHERE s.satker_id like %s
          AND s.active = 1
        ORDER BY s.satker_id ASC, s.tipe_pegawai_id ASC, s.nama_pegawai ASC
    ', $get_mapping . '%'),
    ARRAY_A
);
$select_pegawai = '<option value="">Pilih Pegawai Pelaksana</option>';
foreach ($get_pegawai as $pegawai) {
    $satker = '';
    if(!empty($all_sakter[$pegawai['satker_id']])){
        $satker = $all_sakter[$pegawai['satker_id']];
    }
    $select_pegawai .= '<option value="' . $pegawai['nip_baru'].'-'.$pegawai['satker_id']. '-'.$pegawai['id_jabatan']. '" satker-id="' . $pegawai['satker_id'] . '" jabatan-id="' . $pegawai['id_jabatan'] . '">' . $pegawai['jabatan'] . ' | ' . $satker . ' | ' . $pegawai['nip_baru'] . ' | ' . $pegawai['nama_pegawai'] . '</option>';
}

// ----- get data e-kin perbulan ----- //
$satker_id_pegawai_indikator = $get_mapping;
$satker_id_pegawai_indikator_string = "'". $get_mapping ."'";
$tahun = $input['tahun'];
$show_alert_bulanan = 0;
$get_bulanan_message = "-";
if(!empty($tahun) && !empty($satker_id_pegawai_indikator) && !empty($id_skpd)){
	$opsi_param = array(
		'tahun' => $tahun,
        'satker_id' => $satker_id_pegawai_indikator,
		'id_skpd' => $id_skpd,
		'tipe' => 'satker'
	);

	$data_ekin = $this->get_data_perbulan_ekinerja($opsi_param);
	$data_ekin_terbaru = json_decode($data_ekin, true);
	$get_bulanan_message = $data_ekin_terbaru['message'];
	if(!empty($data_ekin_terbaru['is_error']) && $data_ekin_terbaru['is_error']){
		$show_alert_bulanan = 1;
	}
}

$rincian_tagging = $this->functions->generatePage(array(
    'nama_page' => 'Halaman Tagging Rincian Belanja',
    'content' => '[tagging_rincian_sakip]',
    'show_header' => 1,
    'post_status' => 'private'
));
$rincian_tagging_url = $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $tahun_anggaran_sakip . '&id_skpd=' . $id_skpd);
$data_rhk_individu = $wpdb->get_results($wpdb->prepare("
    SELECT
        *
    FROM esakip_data_rhk_individu
    WHERE id_skpd=%d
        AND tahun_anggaran=%d
        AND active=1
", $id_skpd, $input['tahun']), ARRAY_A);
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

    .table_notifikasi_pemda {
        display: none;
        /* Default disembunyikan */
    }

    .help-rhk .dashicons {
        text-decoration: none;
        vertical-align: text-bottom !important;
        font-size: 23px !important;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div id="cetak" title="Rencana Hasil Kerja Perangkat Daerah">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">RENCANA AKSI <br><?php echo !empty($skpd['nama_skpd']) ? $skpd['nama_skpd'] : '' ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <h4 id="notifikasi-title" style="text-align: center; margin-top: 10px; font-weight: bold;margin-bottom: .5em;">Notifikasi Rencana Hasil Kerja Pemda</h4>
            <div title="Notifikasi Rencana Hasil Kerja Pemda" style="padding: 5px; overflow: auto; display:flex; justify-content:center;">
                <table class="table_notifikasi_pemda" style="width: 50em;text-align: center;">
                    <thead>
                        <tr>
                            <th>Sasaran Strategis</th>
                            <th>Indikator Kinerja</th>
                            <th style="min-width: 10em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <?php if (
                !empty($data_rhk_individu) && $set_tabel_individu == 0
            ): ?>            
            <h4 class="text-center">Tabel Data Rencana Aksi Individu yang tidak ada di Rencana Aksi OPD</h4>
            <div style="padding: 5px; overflow: auto; max-height: 80vh; margin-bottom: 20px;">
                <table class="table table-bordered" id="table-rhk-individu" cellpadding="2" cellspacing="0" contenteditable="false">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th class="text-center" style="width: 50%">Rencana Aksi / RHK</th>
                            <th class="text-center">Indikator</th>
                            <th class="text-center">NIP</th>
                            <th class="text-center">Pegawal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <?php endif; ?>
            <?php if (
                !$is_admin_panrb 
                && $hak_akses_user_pegawai != 0
            ): ?>
                <div id="action" class="action-section hide-excel"></div>
            <?php endif; ?>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">KETERANGAN</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">NOMENKLATUR RENJA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">KEGIATAN UTAMA<br>RHK LEVEL 1<br>POKIN LEVEL 2</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">RENCANA HASIL KERJA<br>RHK LEVEL 2<br>POKIN LEVEL 3</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">OUTCOME/OUTPUT</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN KEGIATAN RENCANA HASIL KERJA<br>RHK LEVEL 3<br>POKIN LEVEL 4</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN TEKNIS KEGIATAN<br>RHK LEVEL 4<br>POKIN LEVEL 5</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 100px;">SATUAN</th>
                            <th class="text-center" colspan="6" style="width: 400px;">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" colspan="4" style="width: 250px;">REALISASI KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI (%)</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">RENCANA PAGU</th>
                            <th class="text-center anggaran_column" rowspan="2" style="width: 140px;">PAGU RINCIAN</th>
                            <th class="text-center anggaran_column" rowspan="2" style="width: 140px;">REALISASI PAGU</th>
                            <th class="text-center anggaran_column" rowspan="2" style="width: 140px;">CAPAIAN REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">PAGU RENJA</th>
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
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                            <th>8</th>
                            <th>9</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th class="anggaran_column">24</th>
                            <th class="anggaran_column">25</th>
                            <th class="anggaran_column">26</th>
                            <th>27</th>
                            <th>28</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="hide-print" id="catatan" style="max-width: 900px; margin: 40px auto; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background-color: #f9f9f9;">
    <h4 style="font-weight: bold; margin-bottom: 20px; color: #333;">Catatan</h4>
    <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6; color: #555;">
        <li>Baris Kolom Tabel Dengan Background Warna <strong>Kuning</strong> Menunjukkan Data Kegiatan Utama dan Pohon Kinerja Level 2</li>
        <li>Baris Kolom Tabel Dengan Background Warna <strong>Ungu</strong> Menunjukkan Data Rencana Hasil Kerja dan Pohon Kinerja Level 3</li>
        <li>Baris Kolom Tabel Dengan Background Warna <strong>Biru</strong> Menunjukkan Data Uraian Kegiatan Rencana Hasil Kerja dan Pohon Kinerja Level 4</li>
        <li>Baris Kolom Tabel Dengan Background Warna <strong>Putih</strong> Menunjukkan Data Uraian Teknis Kegiatan dan Pohon Kinerja Level 5</li>
    </ul>
</div>
<!-- Modal Renaksi -->
<div class="modal fade" id="modal-renaksi" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1500px;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Rencana Hasil Kerja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-level-1-tab" data-toggle="tab" href="#nav-level-1" role="tab" aria-controls="nav-level-1" aria-selected="false">Kegiatan Utama | RHK LEVEL 1</a>
                        <a class="nav-item nav-link" id="nav-level-2-tab" data-toggle="tab" href="#nav-level-2" role="tab" aria-controls="nav-level-2" aria-selected="false">Rencana Hasil Kerja | RHK LEVEL 2</a>
                        <a class="nav-item nav-link" id="nav-level-3-tab" data-toggle="tab" href="#nav-level-3" role="tab" aria-controls="nav-level-3" aria-selected="false">Uraian Kegiatan Rencana Hasil Kerja | RHK LEVEL 3</a>
                        <a class="nav-item nav-link" id="nav-level-4-tab" data-toggle="tab" href="#nav-level-4" role="tab" aria-controls="nav-level-4" aria-selected="false">Uraian Teknis Kegiatan | RHK LEVEL 4</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
                    <div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
                    <div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
                    <div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static" role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- Modal crud renaksi pemda -->
<div class="modal fade" id="modal-renaksi-pemda" data-backdrop="static" role="dialog" aria-labelledby="modal-renaksi-pemda-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- Modal detail renaksi opd -->
<div class="modal fade mt-5" id="modal-detail-renaksi" tabindex="-1" role="dialog" aria-labelledby="modal-detail-renaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detail-renaksiLabel">Detail Rencana Hasil Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" value="" id="id_data">
                    <input type="hidden" value="" id="tipe">
                    <div class="form-group">
                        <label for="detail_kegiatan_utama">KEGIATAN UTAMA | RHK LEVEL 1</label>
                        <input type="text" id="detail_kegiatan_utama" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_rhk">RENCANA HASIL KERJA | RHK LEVEL 2</label>
                        <input type="text" id="detail_rhk" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_uraian_kegiatan">URAIAN KEGIATAN | RHK LEVEL 3</label>
                        <input type="text" id="detail_uraian_kegiatan" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_uraian_tk">URAIAN TEKNIS KEGIATAN | RHK LEVEL 4</label>
                        <input type="text" id="detail_uraian_tk" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_pokin_1">POKIN LEVEL 1</label>
                        <input type="text" id="detail_pokin_1" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_pokin_2">POKIN LEVEL 2</label>
                        <input type="text" id="detail_pokin_2" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_pokin_3">POKIN LEVEL 3</label>
                        <input type="text" id="detail_pokin_3" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_pokin_4">POKIN LEVEL 4</label>
                        <input type="text" id="detail_pokin_4" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_pokin_5">POKIN LEVEL 5</label>
                        <input type="text" id="detail_pokin_5" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_cascading_sasaran">CASCADING SASARAN</label>
                        <input type="text" id="detail_cascading_sasaran" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_cascading_program">CASCADING PROGRAM</label>
                        <input type="text" id="detail_cascading_program" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_cascading_kegiatan">CASCADING KEGIATAN</label>
                        <input type="text" id="detail_cascading_kegiatan" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_cascading_sub_giat">CASCADING SUB KEGIATAN</label>
                        <input type="text" id="detail_cascading_sub_giat" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_satuan_kerja">SATUAN KERJA</label>
                        <input type="text" id="detail_satuan_kerja" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detail_pegawai">PEGAWAI PELAKSANA</label>
                        <input type="text" id="detail_pegawai" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        run_download_excel_sakip();
        window.hak_akses_pegawai = <?php echo $hak_akses_user_pegawai; ?>;
        window.nip_pegawai = <?php echo $nip_user_pegawai; ?>;
        window.get_data_bulanan_message = '<?php echo $get_bulanan_message; ?>';
		window.show_alert_bulanan = '<?php echo $show_alert_bulanan; ?>';
        
        window.tahunAnggaran = <?php echo $input['tahun']; ?>;
        window.today = new Date();
        window.currentYear = today.getFullYear();
        if (window.tahunAnggaran < window.currentYear) {
            window.currentTW = 4;
        } else if (window.tahunAnggaran === window.currentYear) {
            const currentMonth = window.today.getMonth() + 1;
            window.currentTW = Math.ceil(currentMonth / 3);
        }

        if (hak_akses_pegawai != 0) {
            jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-rencana-aksi" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');
        }
        jQuery('#action-sakip').append('<div class="text-center mt-2"><label><input type="checkbox" class="form-check-input" id="show_anggaran_column"> Tampilkan Kolom Rincian Pagu</label></div>');

        window.id_jadwal = <?php echo $id_jadwal; ?>;
        window.id_jadwal_wpsipd = <?php echo $id_jadwal_wpsipd; ?>;
        if (id_jadwal == 0) {
            alert("Jadwal RPJMD/RPD untuk data Pokin belum disetting.\nSetting di admin dashboard di menu E-SAKIP Options -> Laporan Monitor Upload Dokumen Tahun <?php echo $input['tahun']; ?>")
        }
        if (id_jadwal_wpsipd == 0) {
            alert("Jadwal RENSTRA untuk data Cascading di WP-SIPD belum disetting.\nSetting di admin dashboard di menu Monev Rencana Hasil Kerja -> Monev Rencana Hasil Kerja Setting")
        }

        getTablePengisianRencanaAksi();
        getTableIndividu();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });

        jQuery("#tambah-rencana-aksi").on('click', function() {
            lihat_rencana_aksi(0, 1, 0, 0, 0);
            get_data_target_realisasi_bulanan();
        });

        if(get_data_bulanan_message != '-' && show_alert_bulanan == 1){
			alert(get_data_bulanan_message);
		}
		console.log(get_data_bulanan_message)


        jQuery('#show_anggaran_column').on('change', function() {
            if (jQuery(this).is(':checked')) {
                jQuery('.anggaran_column').show();
            } else {
                jQuery('.anggaran_column').hide();
            }
        });

        jQuery('#show_anggaran_column').trigger('change');
    });

    jQuery(document).on('change', '#set_input_rencana_pagu', function() {
        if (jQuery(this).is(":checked")) {
            var id_rhk = jQuery('#id_renaksi').val();
            // jika tambah baru
            if(!id_rhk){
                open_input_rencana_pagu(true);
            // jika edit data existing
            }else{
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    type: 'POST',
                    url: esakip.url,
                    data: {
                        "action": 'cek_validasi_input_rencana_pagu',
                        "api_key": esakip.api_key,
                        "id": id_rhk
                    },
                    dataType: "json",
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        console.log('response', response);
                        if (response.status == 'error') {
                            if(confirm(response.message)){
                                open_input_rencana_pagu(true);
                            }else{
                                jQuery('#set_input_rencana_pagu').prop('checked', false);
                            }
                        }else{
                            open_input_rencana_pagu(true);
                        }
                    },
                    error: function() {
                        alert('Gagal ajax.');
                    }
                });
            }
        } else {
            open_input_rencana_pagu(false);
        }
    });

    function open_input_rencana_pagu(cek){
        var level = jQuery('.modal-footer .btn-success').attr('onclick').split('(')[1].split(')')[0];
        if(cek == true){
            jQuery(".in_setting_input_rencana_pagu").show();
            if(level == 1){
                jQuery('#sub-skpd-cascading').closest('.form-group').show();
                jQuery('#pagu-cascading').closest('.form-group').show();
            }
        }else{
            jQuery(".in_setting_input_rencana_pagu").hide();
            if(level == 1){
                jQuery('#sub-skpd-cascading').closest('.form-group').hide();
                jQuery('#pagu-cascading').closest('.form-group').hide();
            }
        }

        // trigger pilihan nomenklatur renja
        jQuery('#cascading-renstra').trigger('change');
        jQuery('#cascading-renstra-program').trigger('change');
        jQuery('#cascading-renstra-kegiatan').trigger('change');
        jQuery('#cascading-renstra-sub-kegiatan').trigger('change');

        // trigger pilihan history pokin
        jQuery('#pokin-level-1').trigger('change');
        jQuery('#pokin-level-2').trigger('change');
        jQuery('#pokin-level-3').trigger('change');
        jQuery('#pokin-level-4').trigger('change');
        jQuery('#pokin-level-5').trigger('change');
    }

    jQuery(document).on('click', '.verifikasi-renaksi-pemda', function() {
        let get_renaksi_opd = <?php echo json_encode($renaksi_opd); ?>;
        let checklist_renaksi_opd = '';

        if (get_renaksi_opd.length > 0) {
            checklist_renaksi_opd += '<div class="form-group"><label>Label Rencana Hasil Kerja | Level 2</label><div>';
            get_renaksi_opd.forEach(function(item, index) {
                checklist_renaksi_opd += `
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="label_renaksi_opd_${index}" name="checklist_renaksi_opd[]" value="${item.label}" id_label_renaksi_opd="${item.id}">
                    <label class="form-check-label" for="label_renaksi_opd_${index}">${item.label}</label>
                </div>
            `;
            });
            checklist_renaksi_opd += '</div></div>';
        }

        jQuery("#modal-renaksi-pemda").find('.modal-title').html('Verifikasi Rencana Hasil Kerja Pemda');
        jQuery("#modal-renaksi-pemda").find('.modal-body').html(`
        <form id="form-renaksi-pemda">
            <input type="hidden" name="id_renaksi_pemda" value="${jQuery(this).data('id_renaksi_pemda')}"> 
            <div class="form-group">
                <label>Rencana Hasil Kerja Pemda</label>
                <input type="text" id="label_uraian_kegiatan" name="label_uraian_kegiatan" value="${jQuery(this).data('label-sasaran')}" disabled>
            </div>
            <div class="form-group">
                <label>Indikator Rencana Hasil Kerja Pemda</label>
                <input type="text" id="label_indikator" name="label_indikator" value="${jQuery(this).data('label-indikator')}" class="mt-1" disabled>
            </div>
            ${checklist_renaksi_opd}
        </form>
    `);
        jQuery("#modal-renaksi-pemda").find('.modal-footer').html(`
        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success" id="simpan-data-renaksi-pemda" data-action="verify_renaksi-pemda">Simpan</button>
    `);
        jQuery("#modal-renaksi-pemda").modal('show');
    });

    jQuery(document).on('click', '#simpan-data-renaksi-pemda', function() {
        let formData = jQuery('#form-renaksi-pemda').serialize();

        let label_sasaran_strategis = jQuery('#label_sasaran_strategis').val();
        let label_indikator = jQuery('#label_indikator').val();

        jQuery('input[name="checklist_renaksi_opd[]"]:checked').each(function() {
            let idLabelRenaksiOpd = jQuery(this).attr('id_label_renaksi_opd');
            formData += '&id_label_renaksi_opd[]=' + idLabelRenaksiOpd;
        });

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: 'POST',
            url: esakip.url,
            data: formData + '&action=simpan_renaksi_pemda' + '&api_key=' + esakip.api_key + '&id_skpd=' + <?php echo $id_skpd; ?> + '&tahun=' + <?php echo $input['tahun']; ?>,
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.success) {
                    alert(response.data);
                    jQuery("#modal-renaksi-pemda").modal('hide');
                    location.reload();
                } else {
                    alert(response.data);
                }
            },
            error: function() {
                alert('Gagal menyimpan data.');
            }
        });
    });

    jQuery(document).on('change', '#cek-target-teks', function() {
        if (jQuery(this).is(":checked")) {
            jQuery(".target-teks").show();
        } else {
            jQuery(".target-teks").hide();
        }
    });

    function simpan_indikator_renaksi(tipe) {
        var id = jQuery('#id_label_indikator').val();
        var rencana_pagu = jQuery('#rencana_pagu').val();
        var id_label = jQuery('#id_label').val();
        var indikator = jQuery('#indikator').val();
        if (indikator == '') {
            return alert('Indikator tidak boleh kosong!')
        }
        var satuan = jQuery('#satuan_indikator').val();
        if (satuan == '') {
            return alert('Satuan tidak boleh kosong!')
        }
        var target_awal = jQuery('#target_awal').val();
        if (target_awal == '') {
            return alert('Target awal tidak boleh kosong!')
        }
        var target_akhir = jQuery('#target_akhir').val();
        if (target_akhir == '') {
            return alert('Target akhir tidak boleh kosong!')
        }
        var target_tw_1 = jQuery('#target_tw_1').val();
        if (target_tw_1 == '') {
            return alert('Target triwulan 1 tidak boleh kosong!')
        }
        var target_tw_2 = jQuery('#target_tw_2').val();
        if (target_tw_2 == '') {
            return alert('Target triwulan 2 tidak boleh kosong!')
        }
        var target_tw_3 = jQuery('#target_tw_3').val();
        if (target_tw_3 == '') {
            return alert('Target triwulan 3 tidak boleh kosong!')
        }
        var target_tw_4 = jQuery('#target_tw_4').val();
        if (target_tw_4 == '') {
            return alert('Target triwulan 4 tidak boleh kosong!')
        }
        var rumus_capaian_kinerja = jQuery('#rumus_capaian_kinerja').val();
        if (rumus_capaian_kinerja == '') {
            return alert('Rumus Capaian Kinerja tidak boleh kosong!')
        }
        var rencana_pagu_tk = rencana_pagu;
        if(jQuery('#rencana_pagu_tk').length >= 1){
            rencana_pagu_tk = jQuery('#rencana_pagu_tk').val();
        }
        var aspek_rhk = jQuery('#aspek_rhk').val();
        var target_teks_awal = jQuery('#target_teks_awal').val();
        var target_teks_akhir = jQuery('#target_teks_akhir').val();
        var target_teks_tw_1 = jQuery("#target_teks_tw_1").val();
        var target_teks_tw_2 = jQuery("#target_teks_tw_2").val();
        var target_teks_tw_3 = jQuery("#target_teks_tw_3").val();
        var target_teks_tw_4 = jQuery("#target_teks_tw_4").val();
        var set_target_teks = 0;
        if (jQuery("#cek-target-teks").is(":checked")) {
            set_target_teks = 1;
        } else {
            set_target_teks = 0;
        }
        var rumus_indikator = jQuery('#rumus-indikator').val();

        let sumberDanas = [];
        jQuery('#sumber_dana tbody tr').each(function() {
            let sumberDana = jQuery(this).find('select.sumber_dana').val();
            let namaDana = jQuery(this).find('select.sumber_dana option:selected').text();
            let pagu = jQuery(this).find('input').val();

            if (sumberDana) {
                sumberDanas.push({
                    nama_dana: namaDana,
                    id_dana: sumberDana,
                    pagu: pagu
                });
            }
        });

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": 'create_indikator_renaksi',
                "api_key": esakip.api_key,
                "tipe": tipe,
                "tipe_pokin": "opd",
                "id_label_indikator": id,
                "id_label": id_label,
                "indikator": indikator,
                "satuan": satuan,
                "target_awal": target_awal,
                "target_akhir": target_akhir,
                "target_tw_1": target_tw_1,
                "target_tw_2": target_tw_2,
                "target_tw_3": target_tw_3,
                "target_tw_4": target_tw_4,
                "rencana_pagu": rencana_pagu,
                "rencana_pagu_tk": rencana_pagu_tk,
                "tahun_anggaran": <?php echo $input['tahun']; ?>,
                "id_skpd": <?php echo $id_skpd; ?>,
                "aspek_rhk": aspek_rhk,
                "target_teks_awal": target_teks_awal,
                "target_teks_akhir": target_teks_akhir,
                "rumus_capaian_kinerja": rumus_capaian_kinerja,
                "target_teks_tw_1": target_teks_tw_1,
                "target_teks_tw_2": target_teks_tw_2,
                "target_teks_tw_3": target_teks_tw_3,
                "target_teks_tw_4": target_teks_tw_4,
                "set_target_teks": set_target_teks,
                "rumus_indikator": rumus_indikator,
                "sumber_danas": sumberDanas
            },
            dataType: "json",
            success: function(res) {
                jQuery('#wrap-loading').hide();
                alert(res.message);
                if (res.status == 'success') {
                    jQuery("#modal-crud").modal('hide');
                    if (tipe == 1) {
                        lihat_rencana_aksi(0, 1, 0, 0, 0);
                    } else if (tipe == 2) {
                        var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                        var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                        var parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
                        var parent_sub_skpd = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd);
                    } else if (tipe == 3) {
                        var parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                        var parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                        var parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
                        var parent_sub_skpd = jQuery('#tabel_uraian_rencana_aksi').attr('parent_sub_skpd');
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd);
                    } else if (tipe == 4) {
                        var parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                        var parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                        var parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
                        var parent_sub_skpd = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_sub_skpd');
                        lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd);
                    }
                    getTablePengisianRencanaAksi(1);
                }
            }
        });
    };

    function get_tujuan_sasaran_cascading(jenis = 'sasaran', parent_cascading = 'x.xx', id_sub_skpd_cascading = 0) {
        return new Promise(function(resolve, reject) {
            if (typeof data_sasaran_cascading == 'undefined') {
                data_sasaran_cascading = {};
            }
            if (typeof data_program_cascading == 'undefined') {
                data_program_cascading = {};
            }
            if (typeof data_kegiatan_cascading == 'undefined') {
                data_kegiatan_cascading = {};
            }
            if (typeof data_sub_kegiatan_cascading == 'undefined') {
                data_sub_kegiatan_cascading = {};
            }
            var key = jenis + '-' + parent_cascading;
            let id_skpd = <?php echo $id_skpd; ?>;
            /** Dibedakan karena ada kode yang sama tapi sub skpdnya berbeda */
            if (id_sub_skpd_cascading != 0) {
                key = jenis + '-' + parent_cascading + '-' + id_sub_skpd_cascading;
                id_skpd = id_sub_skpd_cascading;
            }
            if (jenis == 'sasaran') {
                if (typeof data_sasaran_cascading[key] == 'undefined') {
                    jQuery('#wrap-loading').show();
                    jQuery.ajax({
                        url: esakip.url,
                        type: "post",
                        data: {
                            "action": 'get_tujuan_sasaran_cascading',
                            "api_key": esakip.api_key,
                            "id_skpd": <?php echo $id_skpd; ?>,
                            "tahun_anggaran": <?php echo $input['tahun']; ?>,
                            "jenis": jenis,
                            "id_jadwal_wpsipd": id_jadwal_wpsipd
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                window.data_sasaran_cascading[key] = response;
                            } else {
                                alert("Data cascading tidak ditemukan");
                            }
                            resolve();
                        }
                    });
                } else {
                    resolve();
                }
            } else if (jenis == 'program') {
                if (typeof data_program_cascading[key] == 'undefined') {
                    jQuery('#wrap-loading').show();
                    jQuery.ajax({
                        url: esakip.url,
                        type: "post",
                        data: {
                            "action": 'get_tujuan_sasaran_cascading',
                            "api_key": esakip.api_key,
                            "id_skpd": <?php echo $id_skpd; ?>,
                            "tahun_anggaran": <?php echo $input['tahun']; ?>,
                            "jenis": jenis,
                            "parent_cascading": parent_cascading,
                            "id_jadwal_wpsipd": id_jadwal_wpsipd
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                window.data_program_cascading[key] = response;
                            } else {
                                alert("Data cascading tidak ditemukan");
                            }
                            resolve();
                        }
                    });
                } else {
                    resolve();
                }
            } else if (jenis == 'kegiatan') {
                if (typeof data_kegiatan_cascading[key] == 'undefined') {
                    jQuery('#wrap-loading').show();
                    jQuery.ajax({
                        url: esakip.url,
                        type: "post",
                        data: {
                            "action": 'get_tujuan_sasaran_cascading',
                            "api_key": esakip.api_key,
                            "id_skpd": id_skpd,
                            "tahun_anggaran": <?php echo $input['tahun']; ?>,
                            "jenis": jenis,
                            "parent_cascading": parent_cascading,
                            "id_jadwal_wpsipd": id_jadwal_wpsipd
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                window.data_kegiatan_cascading[key] = response;
                            } else {
                                alert("Data cascading tidak ditemukan");
                            }
                            resolve();
                        }
                    });
                } else {
                    resolve();
                }
            } else if (jenis == 'sub_kegiatan') {
                if (typeof data_sub_kegiatan_cascading[key] == 'undefined') {
                    jQuery('#wrap-loading').show();
                    jQuery.ajax({
                        url: esakip.url,
                        type: "post",
                        data: {
                            "action": 'get_tujuan_sasaran_cascading',
                            "api_key": esakip.api_key,
                            "id_skpd": id_skpd,
                            "tahun_anggaran": <?php echo $input['tahun']; ?>,
                            "jenis": jenis,
                            "parent_cascading": parent_cascading,
                            "id_jadwal_wpsipd": id_jadwal_wpsipd
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                window.data_sub_kegiatan_cascading[key] = response;
                            } else {
                                alert("Data cascading tidak ditemukan");
                            }
                            resolve();
                        }
                    });
                } else {
                    resolve();
                }
            } else {
                resolve();
            }
        });
    }

    function setting_cascading(rhk, isEdit=false){
        var tipe = rhk.level;

        /** menghapus attr onchange sementara agar tidak bentrok dengan fungsi onchange-nya dibawah ini */
        jQuery('#cascading-renstra').removeAttr('onchange');
        jQuery('#cascading-renstra-program').removeAttr('onchange');
        jQuery('#cascading-renstra-kegiatan').removeAttr('onchange');
        jQuery('#cascading-renstra-sub-kegiatan').removeAttr('onchange');

        // jika cek_parent input pagu terchecklist maka harus diuncheck
        open_input_rencana_pagu(false);
        if(
            rhk.input_rencana_pagu_level == 1
            && cek_parent_global.input_pagu == 0
        ){
            jQuery('#set_input_rencana_pagu').prop('checked', true);
        }else{
            jQuery('#set_input_rencana_pagu').prop('checked', false);
        }

        if(cek_parent_global.input_pagu == 1){
            open_input_rencana_pagu(true);
            rhk = cek_parent_global.data[cek_parent_global.level];
            jQuery('#cascading-renstra').attr('disabled', true);
            jQuery('#cascading-renstra-program').attr('disabled', true);
            jQuery('#cascading-renstra-kegiatan').attr('disabled', true);
            jQuery('#cascading-renstra-sub-kegiatan').attr('disabled', true);
        }else if(rhk.input_rencana_pagu_level == 1){
            open_input_rencana_pagu(true);
        }

        var kode_cascading_renstra_program = rhk.kode_cascading_program+'_'+rhk.id_skpd;
        
        new Promise(function(resolve, reject){
            jQuery('#cascading-renstra').val(rhk.kode_cascading_sasaran).trigger('change');
            
            if(
                rhk.input_rencana_pagu_level == 1
                && tipe < 2
            ){
                console.log('kosongkan cascading-renstra-program');
                jQuery("#cascading-renstra-program").empty();
                get_cascading_input_rencana_pagu('program').then(function() {
                    resolve();
                });
            }else{
                resolve();
            }
        }).then(function(){
            return new Promise(function(resolve, reject){
                jQuery('#cascading-renstra-program').val(kode_cascading_renstra_program).trigger('change');
                
                if(
                    (
                        rhk.input_rencana_pagu_level == 1
                        || cek_parent_global.input_pagu == 1
                    )
                    && tipe < 3
                ){
                    if (rhk.id_sub_skpd_cascading) {
                        kode_cascading_renstra_program = rhk.kode_cascading_program+'_'+rhk.id_sub_skpd_cascading;
                    }
                    console.log('kosongkan cascading-renstra-kegiatan');
                    jQuery("#cascading-renstra-kegiatan").empty();
                    get_cascading_input_rencana_pagu('kegiatan').then(function() {
                        resolve();
                    });
                }else{
                    resolve();
                }
            });
        }).then(function(){
            return new Promise(function(resolve, reject){
                jQuery('#cascading-renstra-kegiatan').val(rhk.kode_cascading_kegiatan).trigger('change');
                
                if(
                    (
                        rhk.input_rencana_pagu_level == 1
                        || cek_parent_global.input_pagu == 1
                    )
                    && tipe < 4
                ){
                    console.log('kosongkan cascading-renstra-sub-kegiatan');
                    jQuery("#cascading-renstra-sub-kegiatan").empty();
                    get_cascading_input_rencana_pagu('sub_kegiatan').then(function() {
                        resolve();
                    });
                }else{
                    resolve();
                }
            });
        }).then(function(){
            console.log(
                'cek_parent_global.input_pagu='+cek_parent_global.input_pagu,
                'rhk.input_rencana_pagu_level='+rhk.input_rencana_pagu_level,
                'rhk.kode_cascading_sasaran='+rhk.kode_cascading_sasaran, 
                'kode_cascading_renstra_program='+kode_cascading_renstra_program,
                'rhk.kode_cascading_kegiatan='+rhk.kode_cascading_kegiatan,
                'rhk.kode_cascading_sub_kegiatan='+rhk.kode_cascading_sub_kegiatan
            );
            jQuery('#cascading-renstra-sub-kegiatan').val(rhk.kode_cascading_sub_kegiatan).trigger('change');
            
            setTimeout(function() {
                
                let pokin_displayed = false;
                
                if(rhk.kode_cascading_sasaran){
                    var id_unik_sasaran = get_id_unik_from_cascading('sasaran', rhk.kode_cascading_sasaran, rhk.label_cascading_sasaran);
                    if(id_unik_sasaran) {
                        console.log('Menampilkan pokin sasaran');
                        let result = tampilkan_pokin_dari_cascading(id_unik_sasaran, 'sasaran');
                        if(result) pokin_displayed = true;
                    }
                }
                
                if(rhk.kode_cascading_program){
                    setTimeout(function() {
                        var id_sub_skpd = rhk.id_sub_skpd_cascading || rhk.id_skpd;
                        var id_unik_program = get_id_unik_from_cascading('program', rhk.kode_cascading_program, rhk.label_cascading_program, id_sub_skpd);
                        if(id_unik_program) {
                            console.log('Menampilkan pokin program');
                            let result = tampilkan_pokin_dari_cascading(id_unik_program, 'program');
                            if(result) pokin_displayed = true;
                            else {
                                reset_pokin_cascading(3);
                            }
                        }
                    }, 200);
                }
                
                if(rhk.kode_cascading_kegiatan){
                    setTimeout(function() {
                        var id_unik_kegiatan = get_id_unik_from_cascading('kegiatan', rhk.kode_cascading_kegiatan, rhk.label_cascading_kegiatan, rhk.id_sub_skpd_cascading);
                        if(id_unik_kegiatan) {
                            console.log('Menampilkan pokin kegiatan');
                            let result = tampilkan_pokin_dari_cascading(id_unik_kegiatan, 'kegiatan');
                            if(result) pokin_displayed = true;
                            else {
                                reset_pokin_cascading(4);
                            }
                        }
                    }, 400);
                }
                
                if(rhk.kode_cascading_sub_kegiatan){
                    setTimeout(function() {
                        var id_unik_sub = get_id_unik_from_cascading('sub_kegiatan', rhk.kode_cascading_sub_kegiatan, rhk.label_cascading_sub_kegiatan, rhk.id_sub_skpd_cascading);
                        if(id_unik_sub) {
                            console.log('Menampilkan pokin sub kegiatan');
                            let result = tampilkan_pokin_dari_cascading(id_unik_sub, 'sub_kegiatan');
                            if(result) pokin_displayed = true;
                            else {
                                reset_pokin_cascading(5);
                            }
                        }
                    }, 600);
                }
            }, 500);
            
            /** kembalikan attr onchange */
            jQuery('#cascading-renstra').attr('onchange', `
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                
                jQuery('#cascading-renstra-program').html('<option value="">Pilih Program Cascading</option>').trigger('change');
                jQuery('#cascading-renstra-kegiatan').html('<option value="">Pilih Kegiatan Cascading</option>').trigger('change');
                jQuery('#cascading-renstra-sub-kegiatan').html('<option value="">Pilih Sub Kegiatan Cascading</option>').trigger('change');
                
                for(let i = 1; i <= 5; i++) {
                    jQuery('#pokin-level-' + i).html('').trigger('change');
                }
                
                if(id_unik) {
                    setTimeout(function() {
                        let result = tampilkan_pokin_dari_cascading(id_unik, 'sasaran');
                        if(!result) {
                            console.log('Sasaran tidak mempunyai pokin');
                        }
                    }, 100);
                }
                get_cascading_input_rencana_pagu('program');
            `);

            jQuery('#cascading-renstra-program').attr('onchange', `
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                console.log('Program onchange - id_unik:', id_unik);
                
                jQuery('#cascading-renstra-kegiatan').html('<option value="">Pilih Kegiatan Cascading</option>').trigger('change');
                jQuery('#cascading-renstra-sub-kegiatan').html('<option value="">Pilih Sub Kegiatan Cascading</option>').trigger('change');
                
                jQuery('#pokin-level-3').html('').trigger('change');
                jQuery('#pokin-level-4').html('').trigger('change');
                jQuery('#pokin-level-5').html('').trigger('change');
                
                if(id_unik && id_unik.toString().trim() !== '') {
                    setTimeout(function() {
                        let result = tampilkan_pokin_dari_cascading(id_unik, 'program');
                        if(!result) {
                            console.log('Program tidak mempunyai pokin');
                        }
                    }, 100);
                } else {
                    console.log('Program tidak dipilih atau tidak punya id_unik');
                }
                get_cascading_input_rencana_pagu('kegiatan');
            `);

            jQuery('#cascading-renstra-kegiatan').attr('onchange', `
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                
                jQuery('#cascading-renstra-sub-kegiatan').html('<option value="">Pilih Sub Kegiatan Cascading</option>').trigger('change');
                
                jQuery('#pokin-level-4').html('').trigger('change');
                jQuery('#pokin-level-5').html('').trigger('change');
                
                if(id_unik && id_unik.toString().trim() !== '') {
                    setTimeout(function() {
                        let result = tampilkan_pokin_dari_cascading(id_unik, 'kegiatan');
                        if(!result) {
                            console.log('Kegiatan tidak mempunyai pokin');
                        }
                    }, 100);
                } else {
                    console.log('Kegiatan tidak dipilih atau tidak punya id_unik');
                }
                get_cascading_input_rencana_pagu('sub_kegiatan');
            `);

            jQuery('#cascading-renstra-sub-kegiatan').attr('onchange', `
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                
                jQuery('#pokin-level-5').html('').trigger('change');
                
                if(id_unik && id_unik.toString().trim() !== '') {
                    setTimeout(function() {
                        let result = tampilkan_pokin_dari_cascading(id_unik, 'sub_kegiatan');
                        if(!result) {
                            console.log('Sub Kegiatan tidak punya pokin');
                        }
                    }, 100);
                } else {
                    console.log('Sub Kegiatan tidak dipilih atau tidak punya id_unik');
                }
                get_cascading_input_rencana_pagu();
            `);

            // setting OPD dan pagu cascading setelah selesai setting cascading
            if(
                rhk.input_rencana_pagu_level == 1
                || cek_parent_global.input_pagu == 1
                || tipe == 4
            ){
                get_cascading_input_rencana_pagu();
            }else if(tipe == 2){
                set_view_cascading('cascading-renstra-program');
            }else if(tipe == 3){
                set_view_cascading('cascading-renstra-kegiatan');
            }
        });
    }

    function setting_edit_pokin(rhk){
        var tipe = rhk.level;

        /** menghapus attr onchange sementara agar tidak bentrok dengan fungsi dibawah ini */
        jQuery('#pokin-level-1').removeAttr('onchange');
        jQuery('#pokin-level-2').removeAttr('onchange');
        jQuery('#pokin-level-3').removeAttr('onchange');
        jQuery('#pokin-level-4').removeAttr('onchange');

        if(cek_parent_global.input_pagu == 1){
            // rhk = cek_parent_global.data[cek_parent_global.level];
            jQuery('#pokin-level-1').attr('disabled', true);
            jQuery('#pokin-level-2').attr('disabled', true);
            jQuery('#pokin-level-3').attr('disabled', true);
            jQuery('#pokin-level-4').attr('disabled', true);
            jQuery('#pokin-level-5').attr('disabled', true);
        }

        let selected_pokin_1 = [];
        let selected_pokin_2 = [];
        let selected_pokin_3 = [];
        let selected_pokin_4 = [];
        let selected_pokin_5 = [];
        rhk.pokin.map(function(b) {
            selected_pokin_1.push(b.id);
        });
        rhk.pokin_2.map(function(b) {
            selected_pokin_2.push(b.id);
        });
        rhk.pokin_3.map(function(b) {
            selected_pokin_3.push(b.id);
        });
        rhk.pokin_4.map(function(b) {
            selected_pokin_4.push(b.id);
        });
        rhk.pokin_5.map(function(b) {
            selected_pokin_5.push(b.id);
        });
        new Promise(function(resolve, reject){
            if(
                tipe == 1
                || rhk.input_rencana_pagu_level == 1
                || cek_parent_global.input_pagu == 1
            ){
                console.log('kosongkan pokin-level-2');
                jQuery("#pokin-level-2").empty();
                get_data_pokin_2(selected_pokin_1, 2, "pokin-level-2", false).then(function() {
                    resolve();
                });
            }else{
                resolve();
            }
        }).then(function(){
            return new Promise(function(resolve, reject){
                if(
                    (
                        rhk.input_rencana_pagu_level == 1
                        || cek_parent_global.input_pagu == 1
                    )
                    && tipe < 2

                ){
                    console.log('kosongkan pokin-level-3');
                    jQuery("#pokin-level-3").empty();
                    get_data_pokin_2(selected_pokin_2, 3, "pokin-level-3", false).then(function() {
                        resolve();
                    });
                }else{
                    resolve();
                }
            });
        }).then(function(){
            return new Promise(function(resolve, reject){
                if(
                    (
                        rhk.input_rencana_pagu_level == 1
                        || cek_parent_global.input_pagu == 1
                    )
                    && tipe < 3
                ){
                    console.log('kosongkan pokin-level-4');
                    jQuery("#pokin-level-4").empty();
                    get_data_pokin_2(selected_pokin_3, 4, "pokin-level-4", false).then(function() {
                        resolve();
                    });
                }else{
                    resolve();
                }
            });
        }).then(function(){
            return new Promise(function(resolve, reject){
                if(
                    (
                        rhk.input_rencana_pagu_level == 1
                        || cek_parent_global.input_pagu == 1
                    )
                    && tipe < 4
                ){
                    console.log('kosongkan pokin-level-5');
                    jQuery("#pokin-level-5").empty();
                    get_data_pokin_2(selected_pokin_4, 5, "pokin-level-5", false).then(function() {
                        resolve();
                    });
                }else{
                    resolve();
                }
            });
        }).then(function(){
            console.log(
                'selected_pokin_1', selected_pokin_1, 
                'rhk.input_rencana_pagu_level='+rhk.input_rencana_pagu_level, 
                'cek_parent_global.input_pagu='+cek_parent_global.input_pagu, 
                'selected_pokin_2=', selected_pokin_2, 
                'selected_pokin_3=', selected_pokin_3, 
                'selected_pokin_4=', selected_pokin_4, 
                'selected_pokin_5=', selected_pokin_5
            );

            jQuery('#pokin-level-1').val(selected_pokin_1).trigger('change');
            jQuery('#pokin-level-2').val(selected_pokin_2).trigger('change');
            jQuery('#pokin-level-3').val(selected_pokin_3).trigger('change');
            jQuery('#pokin-level-4').val(selected_pokin_4).trigger('change');
            jQuery('#pokin-level-5').val(selected_pokin_5).trigger('change');

            /** kembalikan attr onchange */
            jQuery('#pokin-level-1').attr('onchange', 'get_data_pokin_2(this.value, 2, "pokin-level-2", true)');
            jQuery('#pokin-level-2').attr('onchange', 'get_data_pokin_2(this.value, 3, "pokin-level-3", true)');
            jQuery('#pokin-level-3').attr('onchange', 'get_data_pokin_2(this.value, 4, "pokin-level-4", true)');
            jQuery('#pokin-level-4').attr('onchange', 'get_data_pokin_2(this.value, 5, "pokin-level-5", true)');
        });
    }

    function edit_rencana_aksi(id, tipe) {
        tambah_renaksi_2(tipe, true).then(function() {
            var parent_id_input_pagu = '';
            var parent_level_input_pagu = '';
            if(cek_parent_global.input_pagu == 1){
                parent_id_input_pagu = cek_parent_global.data[cek_parent_global.level].id;
                parent_level_input_pagu = cek_parent_global.level;
            }
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_rencana_aksi',
                    api_key: esakip.api_key,
                    id: id,
                    parent_id_input_pagu: parent_id_input_pagu,
                    parent_level_input_pagu: parent_level_input_pagu,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>'
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status == 'error') {
                        alert(response.message);
                    } else if (response.data != null) {
                        jQuery('#id_renaksi').val(response.data.id);
                        setting_cascading(response.data);
                        if (tipe == 1) {
                            jQuery("#modal-crud").find('.modal-title').html('Edit Kegiatan Utama');
                        }else if (tipe == 2) {
                            jQuery("#modal-crud").find('.modal-title').html('Edit Rencana Hasil Kerja');
                            jQuery('#label_renaksi_opd_').val(response.data.label_renaksi_opd_);
                            jQuery('#label_sasaran_strategis').val(response.data.label_sasaran_strategis);
                            jQuery('#label_indikator_uraian_kegiatan').val(response.data.label_indikator_uraian_kegiatan);

                            var renaksi_pemda = "";
                            response.data.renaksi_pemda.map(function(b, i) {
                                let label_sasaran = (b.id_iku && b.ik_label_sasaran) 
                                    ? b.ik_label_sasaran 
                                    : (b.label_sasaran || '');

                                let label_indikator = (b.id_iku && b.ik_label_indikator) 
                                    ? b.ik_label_indikator 
                                    : (b.label_indikator || '');
                                renaksi_pemda += `
                                <tr>
                                    <td><input class="text-right" type="checkbox" class="form-check-input" id="label_renaksi_pemda" name="checklist_renaksi_pemda[]" value="${label_sasaran}" id_label_renaksi_pemda="${b.id_renaksi}" ${b.id_label != null ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <label class="form-check-label" id="label_sasaran_strategis" for="label_sasaran_strategis">${label_sasaran}</label>
                                    </td>
                                    <td>
                                        <label class="form-check-label" id="label_indikator_uraian_kegiatan" for="label_indikator_uraian_kegiatan">${label_indikator}</label>
                                    </td>
                                </tr>
                            `;
                            });
                            if (renaksi_pemda.length > 0) {
                                let checklist_renaksi_pemda = `
                                <div class="form-group">
                                    <label>Rencana Hasil Kerja Pemerintah Daerah</label>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input class="text-center" type="checkbox" id="check_all" class="form-check-input">
                                                </th>
                                                <th>Sasaran Strategis</th>
                                                <th>Indikator Kinerja</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${renaksi_pemda}
                                        </tbody>
                                    </table>
                                </div>`;

                                jQuery("#modal-crud").find('.modal-body').append(checklist_renaksi_pemda);

                                jQuery("#check_all").on('change', function() {
                                    var checklist_all = jQuery(this).prop('checked');
                                    jQuery("input[name='checklist_renaksi_pemda[]']").prop('checked', checklist_all);
                                });
                            }
                        } else if (tipe == 3) {
                            jQuery("#modal-crud").find('.modal-title').html('Edit Uraian Rencana Hasil Kerja');
                        } else if (tipe == 4) {
                            jQuery("#modal-crud").find('.modal-title').html('Edit Uraian Teknis Kegiatan');
                        }

                        if (
                            response.data 
                            && response.data.jabatan 
                            && response.data.jabatan.satker_id
                        ) {
                            jQuery('#satker_id').val(response.data.jabatan.satker_id).trigger('change');
                        }
                        if (
                            response.data 
                            && response.data.pegawai 
                            && response.data.pegawai.nip_baru
                        ) {
                            var id_jabatan_asli = '';
                            if(response.data.id_jabatan_asli != null){
                                id_jabatan_asli = response.data.id_jabatan_asli;
                            }
                            var id = response.data.pegawai.nip_baru+'-'+response.data.id_jabatan+'-'+id_jabatan_asli;
                            console.log('id_pegawai', id);
                            jQuery('#pegawai').val(id).trigger('change');
                        }

                        jQuery('#label_renaksi').val(response.data.label);
                        set_dasar_pelaksanaan(response.data);
                    }

                    if (hak_akses_pegawai == 1) {
                        jQuery('#satker_id').prop('disabled', false);
                        jQuery('#pegawai').prop('disabled', false);
                    } else {
                        jQuery('#satker_id').prop('disabled', true);
                        jQuery('#pegawai').prop('disabled', true);
                    }
                }
            });
        });
    }

    function set_dasar_pelaksanaan(rhk){
        var disable = '';
        var display = 'display: none';
        if(rhk){
            if(cek_parent_global.input_pagu == 1){
                disable = 'disabled="true"';
                rhk = cek_parent_global.data[cek_parent_global.level];
                display = '';
            }else if(rhk.input_rencana_pagu_level == 1){
                display = '';
            }
        // jika ini input renaksi baru
        }else{
            rhk = {};
        }
        var checklist_dasar_pelaksanaan = `
            <div class="form-group in_setting_input_rencana_pagu" id="dasar_pelaksanaan_wrap" style="${display}">
                <label>Pilih Dasar Pelaksanaan</label>
                <div>
                    <label><input ${disable} type="checkbox" name="dasar_pelaksanaan[]" id="mandatori_pusat" ${rhk.mandatori_pusat == 1 ? 'checked' : ''}> Mandatori Pusat</label><br>
                    <label><input ${disable} type="checkbox" name="dasar_pelaksanaan[]" id="inisiatif_kd" ${rhk.inisiatif_kd == 1 ? 'checked' : ''}> Inisiatif Kepala Daerah</label><br>
                    <label><input ${disable} type="checkbox" name="dasar_pelaksanaan[]" id="musrembang" ${rhk.musrembang == 1 ? 'checked' : ''}> MUSREMBANG (Musyawarah Rencana Pembangunan)</label><br>
                    <label><input ${disable} type="checkbox" name="dasar_pelaksanaan[]" id="pokir" ${rhk.pokir == 1 ? 'checked' : ''}> Pokir (Pokok Pikiran)</label>
                </div>
            </div>
        `;
        jQuery('#dasar_pelaksanaan_wrap').remove();
        jQuery("#modal-crud").find('.modal-body').append(checklist_dasar_pelaksanaan);
    }

    function get_data_pokin_2(parent, level, tag, getParentManual = false) {
        return new Promise(function(resolve, reject) {
            
            // kondisi ketika select pokin onchange maka getParentManual = true
            if (getParentManual) {

                // kondisi jika input pagu dibuat false maka tidak perlu get pokin child nya
                if(
                    jQuery('#set_input_rencana_pagu').is(':checked') == false
                    && tag != 'pokin-level-2'
                ){
                    console.log('input pagu false get_data_pokin_2 level', level, 'id', tag);
                    return resolve();
                }
                if(tag == 'pokin-level-2'){
                    parent = jQuery("#pokin-level-1").val();
                }else if(tag == 'pokin-level-3'){
                    parent = jQuery("#pokin-level-2").val();
                }else if(tag == 'pokin-level-4'){
                    parent = jQuery("#pokin-level-3").val();
                }else if(tag == 'pokin-level-5'){
                    parent = jQuery("#pokin-level-4").val();
                }
            }
            if (typeof parent === 'string') {
                /**memastikan input pokin itu dalam bentuk array */
                parent = parent.split(',');
            }
            if(parent == ''){
                console.log('parent pokin kosong level', level, 'id', tag);
                return resolve();
            }else{
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": "get_data_pokin",
                        "level": level,
                        "parent": parent,
                        "api_key": esakip.api_key,
                        "tipe_pokin": "opd",
                        "id_jadwal": id_jadwal,
                        "id_skpd": <?php echo $id_skpd; ?>
                    },
                    dataType: "json",
                    success: function(res) {
                        var default_value = jQuery('#' + tag).attr('val-id');
                        var html = '';
                        res.data.map(function(value, index) {
                            var selected = '';
                            if (value.id == default_value) {
                                selected = 'selected';
                            }
                            html += '<option value="' + value.id + '" ' + selected + '>' + value.label + '</option>';
                        });
                        // reset default value
                        jQuery('#' + tag).attr('val-id', '');
                        jQuery('#' + tag).html(html).trigger('change');
                        jQuery('#wrap-loading').hide();
                        resolve();
                    }
                });
            }
        });
    }

    function getTablePengisianRencanaAksi(no_loading = false) {
        if (!no_loading) {
            jQuery('#wrap-loading').show();
        }

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_input_rencana_aksi',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (!no_loading) {
                    jQuery('#wrap-loading').hide();
                }
                console.log(response);

                if (response.status === 'success') {
                    jQuery('.table_dokumen_rencana_aksi tbody').html(response.data);

                    if (response.data_pemda && response.data_pemda.trim() !== '') {
                        jQuery('#notifikasi-title').show();
                        jQuery('.table_notifikasi_pemda').show();
                        jQuery('.table_notifikasi_pemda tbody').html(response.data_pemda);

                        if (jQuery.fn.DataTable.isDataTable('.table_notifikasi_pemda')) {
                            jQuery('.table_notifikasi_pemda').DataTable().clear().destroy();
                        }

                        jQuery('.table_notifikasi_pemda').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            fixedHeader: true,
                            scrollX: true, // Enables horizontal scrolling
                            scrollY: '600px',
                            scrollCollapse: true,
                            pageLength: 10, // Default number of rows per page
                            lengthMenu: [10, 25, 50, 100, 200] // Options for rows per page
                        });
                        
                    } else {
                        jQuery('#notifikasi-title').hide();
                        jQuery('.table_notifikasi_pemda').hide();

                        if (jQuery.fn.DataTable.isDataTable('.table_notifikasi_pemda')) {
                            jQuery('.table_notifikasi_pemda').DataTable().clear().destroy();
                        }
                    }
                    jQuery('#show_anggaran_column').trigger('change');
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data Rencana Hasil Kerja!');
            }
        });
    }

    function get_sub_keg_rka_wpsipd(kode_sbl, sumber_dana) {
        return new Promise(function(resolve, reject) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": 'get_sub_keg_rka_wpsipd',
                    "api_key": esakip.api_key,
                    "tahun_anggaran": <?php echo $input['tahun']; ?>,
                    "kode_sbl": kode_sbl,
                    "sumber_dana": true
                },
                dataType: "json",
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status == 'success') {
                        response.data.forEach(item => {
                            jQuery(".sumber_dana").append(
                                `<option value="${item.id_sumber_dana}">${item.kode_dana} ${item.nama_dana}</option>`
                            );
                            jQuery(".sumber_dana").select2({
                                width: '100%'
                            });
                        });
                        if(sumber_dana){
                            sumber_dana.map(function(value, index) {
                                let id = index + 1;
                                new Promise(function(resolve, reject) {
                                    if (id > 1) {
                                        tambahSumberDana()
                                        .then(function() {
                                            resolve(value);
                                        });
                                    } else {
                                        resolve(value);
                                    }
                                })
                                .then(function(value) {
                                    jQuery("#sumber_dana_" + id).val(value.id_sumber_dana).trigger('change');
                                    jQuery("#pagu_sumber_dana_" + id).val(value.rencana_pagu).trigger('keyup');
                                });
                            });
                        }
                    } else {
                        alert("Error get data sumber dana, " + response.message);
                    }
                    resolve();
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data sumber dana!');
                    resolve();
                }
            });
        });
    }

    function tambah_indikator_rencana_aksi_baru(id, tipe, total_pagu, set_input_rencana_pagu, kode_sbl = false, sumber_dana = false) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: 'POST',
            url: esakip.url,
            data: {
                "action": 'cek_validasi_input_rencana_pagu',
                "api_key": esakip.api_key,
                "id": id
            },
            dataType: "json",
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if(!response.rencana_pagu){
                    response.rencana_pagu = 0;
                }
                tambah_indikator_rencana_aksi(id, tipe, +response.rencana_pagu, set_input_rencana_pagu, kode_sbl);
            },
            error: function() {
                alert('Gagal melihat data.');
            }
        });
    }

    function tambah_indikator_rencana_aksi(id, tipe, total_pagu, set_input_rencana_pagu, kode_sbl = false, sumber_dana = false) {
        return new Promise(function(resolve, reject){
            cek_input_pagu_parent(tipe)
            .then(function(){
                var title = '';
                let input_pagu = '';
                let input_sumber_dana = '';
                if (tipe == 1) {
                    title = 'Indikator Kegiatan Utama | RHK Level 1';
                } else if (tipe == 2) {
                    title = 'Indikator Rencana Hasil Kerja | RHK Level 2';
                } else if (tipe == 3) {
                    title = 'Indikator Uraian Kegiatan Rencana Hasil Kerja | RHK Level 3';
                }else if (tipe == 4){
                    title = 'Indikator Uraian Teknis Kegiatan | RHK Level 4';
                    set_input_rencana_pagu = 1;
                }

                if(
                    set_input_rencana_pagu == 1
                    && cek_parent_global.input_pagu != 1
                ) {
                    input_pagu = '' +
                        `<div class="form-group row">` +
                            '<div class="col-md-2">' +
                                `<label for="rencana_pagu">Rencana Pagu</label>` +
                            '</div>' +
                            '<div class="col-md-10">' +
                                `<input type="number" class="form-control format-rupiah" id="rencana_pagu" disabled>` +
                            '</div>' +
                        `</div>`;

                    input_sumber_dana = '' +
                        `<div class="form-group">` +
                            `<label for="sumber_dana">Sumber Dana</label>` +
                            `<table id="sumber_dana" class="input_sumber_dana" style="margin: 0;">` +
                                `<tr data-id="1">` +
                                    `<td style="width: 60%; max-width:100px;">` +
                                        `<select class="form-control input_select_2 sumber_dana" id="sumber_dana_1" name="input_sumber_dana[1]">` +
                                            `<option value="">Pilih Sumber Dana</option>` +
                                        `</select>` +
                                    `</td>` +
                                    `<td>` +
                                        `<input class="form-control input_number text-left" min="0" id="pagu_sumber_dana_1" type="number" name="input_pagu_sumber_dana[1]" onkeyup="set_anggaran(this);" onblur="set_anggaran(this);" onclick="set_anggaran(this);"/>` +
                                    `</td>` +
                                    `<td style="width: 70px" class="text-center detail_tambah">` +
                                        `<button class="btn btn-warning btn-sm" onclick="tambahSumberDana(); return false;"><i class="dashicons dashicons-plus"></i></button>` +
                                    `</td>` +
                                `</tr>` +
                            `</table>` +
                        `</div>`;

                    if (kode_sbl) {
                        get_sub_keg_rka_wpsipd(kode_sbl, sumber_dana)
                    }
                }else{
                    var pagu_level = 4;
                    if(cek_parent_global.input_pagu == 1){
                        pagu_level = cek_parent_global.level;
                        input_pagu = '' +
                            `<div class="form-group row">` +
                                '<div class="col-md-12">' +
                                    `<h4 class="text-center">Input Pagu di RHK Level ${pagu_level}</h4>` +
                                    `<input type="hidden" disabled class="form-control text-right" id="rencana_pagu" value="0" />` +
                                '</div>'+
                            '</div>';
                    }else{
                        input_pagu = '' +
                            `<div class="form-group row">` +
                                '<div class="col-md-2">' +
                                    `<label for="rencana_pagu">Akumulasi Pagu</label>` +
                                '</div>' +
                                '<div class="col-md-10">' +
                                    `<div class="input-group">` +
                                        `<input type="number" disabled class="form-control text-right" id="rencana_pagu_tk" value="` + total_pagu + `"/>` +
                                    `</div>` +
                                '</div>' +
                            '</div>' +
                            `<div class="form-group row">` +
                                '<div class="col-md-2">' +
                                    `<label for="total_rincian">Jumlah Persen</label>` +
                                '</div>' +
                                '<div class="col-md-2">' +
                                    `<div class="input-group">` +
                                        `<input type="number" class="form-control" id="total_rincian" max="100" value="100"/>` +
                                    `<span class="input-group-text">%</span>` +
                                    `</div>` +
                                '</div>' +
                                '<div class="col-md-1">' +
                                '</div>' +
                                '<div class="col-md-2">' +
                                    `<label for="total_rincian">Rencana Pagu</label>` +
                                '</div>' +
                                '<div class="col-md-5">' +
                                    `<div class="input-group">` +
                                        `<input type="number" class="form-control text-right" id="rencana_pagu" value="` + total_pagu + `" />` +
                                    `</div>` +
                                '</div>' +
                            '</div>';
                    }
                }
                var tr = jQuery('#kegiatan_utama_' + id);
                var label_pokin = tr.find('.label_pokin').text();
                var label_renaksi = tr.find('.label_renaksi').text();
                jQuery("#modal-crud").find('.modal-title').html('Tambah ' + title);
                jQuery("#modal-crud").find('.modal-body').html('' +
                    `<form id="form-renaksi">` +
                        '<input type="hidden" value="" id="id_label_indikator">' +
                        '<input type="hidden" value="' + id + '" id="id_label">' +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="label_pokin_indikator">Label Pokin</label>` +
                        '</div>' +
                        '<div class="col-md-10">' +
                            '<input class="form-control" type="text" disabled id="label_pokin_indikator" value="' + label_pokin + '"/>' +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="kegiatan_utama_indikator">Aspek</label>` +
                        '</div>' +
                        '<div class="col-md-10">' +
                            '<select class="form-control" id="aspek_rhk">' +
                                '<option value="1">Kuantitas (Jumlah yang bisa dihitung. Pcs, Dokumen, %, Kegiatan, Dst.)</option>' +
                                '<option value="2">Kualitas (Nilai mutu produk atau layanan. %, Dst.)</option>' +
                                '<option value="3">Waktu (Hari, Bulan, Tahun, Dst.)</option>' +
                                '<option value="4">Biaya (Rp.)</option>' +
                            '</select>' +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="kegiatan_utama_indikator">` + title.replace("Indikator", "") + `</label>` +
                        '</div>' +
                        '<div class="col-md-10">' +
                            '<input type="text" disabled class="form-control" id="kegiatan_utama_indikator" value="' + label_renaksi + '"/>' +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            '<label for="indikator">Indikator</label>' +
                        '</div>' +
                        '<div class="col-md-10">' +
                            `<textarea class="form-control" name="label" id="indikator" placeholder="Tuliskan Indikator..."></textarea>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        `<div class="col-md-2">` +
                            `<label for="rumus-indikator">Rumus Capaian Kinerja</label>` +
                        `</div>` +
                        `<div class="col-md-10">` +
                            `<select class="form-control" name="rumus_capaian_kinerja" id="rumus_capaian_kinerja">
                                <option value="1">Indikator Tren Positif</option>
                                <option value="2">Nilai Akhir / %</option>
                                <option value="3">Indikator Tren Negatif</option>
                            </select>` +
                            `<small class="text-muted">
                                <ul>
                                    <li>Tren Positif : (Akumulasi Realisasi / Akumulasi Target) * 100.</li>
                                    <li>Nilai Akhir / % : (Nilai Akhir Realisasi / Nilai Akhir Target) * 100.</li>
                                    <li>Tren Negatif : (Akumulasi Target / Akumulasi Realisasi) * 100.</li>
                                    <li>Kedua rumus dihitung berdasarkan Realisasi dan Target triwulan berjalan.</li>
                                </ul>
                            </small>` +
                        `</div>` +
                    `</div>` +
                    `<div class="form-group row">` +
                        `<div class="col-md-2">` +
                            `<label for="rumus-indikator">Rumus Indikator Teks / Formulasi Perhitungan Indikator</label>` +
                        `</div>` +
                        `<div class="col-md-10">` +
                            `<textarea class="form-control" name="label" id="rumus-indikator" placeholder="Tuliskan Rumus Indikator...">(Realisasi Indikator / Target Indikator) * 100 = Capaian </textarea>` +
                        `</div>` +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="satuan_indikator">Satuan</label>` +
                        '</div>' +
                        '<div class="col-md-10">' +
                            `<input type="text" class="form-control" id="satuan_indikator"/>` +
                        '</div>' +
                    `</div>` +
                    `${input_sumber_dana}` +
                    `${input_pagu}` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="target_awal">Target Teks</label>` +
                        '</div>' +
                        '<div class="col-md-10">' +
                            `<div class="form-check" style="margin-top: 5px;">` +
                                `<input class="form-check-input" type="checkbox" id="cek-target-teks">` +
                                `<label class="form-check-label" for="cek-target-teks">Target Teks</label>` +
                            `</div>` +
                            `<small id="passwordHelpBlock" class="form-text text-muted">Untuk menampung target yang berupa teks!</small>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="target_awal">Target Awal</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="number" class="form-control" id="target_awal"/>` +
                        '</div>' +
                        '<div class="col-md-2">' +
                            `<label for="target_akhir">Target Akhir</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="number" class="form-control" id="target_akhir"/>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row target-teks" style="display: none; margin-bottom: 2rem;">` +
                        '<div class="col-md-2">' +
                            `<label for="target_awal">Target Awal Teks</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_teks_awal"/>` +
                        '</div>' +
                        '<div class="col-md-2">' +
                            `<label for="target_akhir">Target Akhir Teks</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_teks_akhir"/>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_1">TW 1</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="number" class="form-control" id="target_tw_1"/>` +
                        '</div>' +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_2">TW 2</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="number" class="form-control" id="target_tw_2"/>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row target-teks" style="display:none; margin-bottom: 2rem;">` +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_1">Target Teks TW 1</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_teks_tw_1"/>` +
                        '</div>' +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_2">Target Teks TW 2</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_teks_tw_2"/>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row">` +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_3">TW 3</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_tw_3"/>` +
                        '</div>' +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_4">TW 4</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_tw_4"/>` +
                        '</div>' +
                    `</div>` +
                    `<div class="form-group row target-teks" style="display: none; margin-bottom: 2rem;">` +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_3">Target Teks TW 3</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_teks_tw_3"/>` +
                        '</div>' +
                        '<div class="col-md-2">' +
                            `<label for="target_tw_4">Terget Teks TW 4</label>` +
                        '</div>' +
                        '<div class="col-md-4">' +
                            `<input type="text" class="form-control" id="target_teks_tw_4"/>` +
                        '</div>' +
                    `</div>` +
                    `</form>`);
                jQuery("#modal-crud").find('.modal-footer').html('' +
                    '<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>' +
                    '<button type="button" class="btn btn-success" onclick="simpan_indikator_renaksi(' + tipe + ')" data-view="kegiatanUtama">Simpan</button>');
                jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '1000px');
                jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                jQuery("#modal-crud").modal('show');

                jQuery("#total_rincian").on('input', function() {
                    var persen = parseFloat(+jQuery(this).val());
                    var persen_rencana_pagu = parseFloat(jQuery("#rencana_pagu_tk").val());

                    if (persen > 100) {
                        jQuery(this).val(100);
                        persen = 100;
                        alert('tidak boleh lebih dari 100%');
                    } else if (persen < 0) {
                        jQuery(this).val(0);
                        persen = 0;
                        alert('tidak boleh kurang dari 0%');
                    }

                    var get_total_pagu = (persen_rencana_pagu * persen) / 100;
                    jQuery("#rencana_pagu").val(setToFixed(get_total_pagu));
                });
                jQuery("#rencana_pagu").on('input', function(){
                    var total_pagu = parseFloat(jQuery("#rencana_pagu_tk").val());
                    var rencana_pagu = jQuery(this).val();
                    if(total_pagu == 0){
                        jQuery("#total_rincian").val(100).trigger('input');
                    }else if(rencana_pagu == 0){
                        jQuery("#total_rincian").val(0).trigger('input');
                    }else{
                        jQuery("#total_rincian").val((rencana_pagu/total_pagu)*100).trigger('input');
                    }
                });
                resolve();
            });
        });
    }

    function tambahSumberDana() {
        return new Promise(function(resolve, reject) {
            jQuery('.input_sumber_dana > tbody tr select').select2("destroy");
            var lastRow = jQuery('.input_sumber_dana > tbody tr').last();
            var id = +lastRow.attr('data-id');
            var newId = id + 1;

            var trNewUsulan = lastRow.clone();
            trNewUsulan.attr('data-id', newId);

            trNewUsulan.find('*').each(function() {
                var oldId = '_' + id;
                var newIdAttr = '_' + newId;

                if (this.id) {
                    this.id = this.id.replace(oldId, newIdAttr);
                }
                if (this.name) {
                    this.name = this.name.replace('[' + id + ']', '[' + newId + ']');
                }
            });

            jQuery('.input_sumber_dana > tbody').append(trNewUsulan);
            jQuery('.input_sumber_dana > tbody > tr select').select2({
                width: '100%'
            });
            jQuery('#pagu_sumber_dana_'+newId).val(0);

            var tr = jQuery('.input_sumber_dana > tbody > tr');
            tr.each(function(i, row) {
                var btnHtml = (i === 0) ?
                    '<button class="btn btn-warning btn-sm" onclick="tambahSumberDana(); return false;"><i class="dashicons dashicons-plus"></i></button>' :
                    '<button class="btn btn-danger btn-sm" onclick="hapusSumberDana(this); return false;"><i class="dashicons dashicons-trash"></i></button>';

                jQuery(row).find('>td').last().html(btnHtml);
            });

            resolve();
        });
    }

    function hapusSumberDana(that) {
        var id = jQuery(that).closest('tr').attr('data-id');
        jQuery('.input_sumber_dana > tbody').find('tr[data-id="' + id + '"]').remove();

        // ubah pagu sub kegiatan setelah sumber dana dihapus
        set_anggaran(jQuery('#pagu_sumber_dana_' + id));
    }

    function set_anggaran(that) {
        let that_id = jQuery(that).attr('id');
        var tbody = jQuery('.input_sumber_dana > tbody');
        var tr = tbody.find('>tr');
        let total = 0;
        tr.map(function(i, b) {
            let id = i + 1;
            let dana = jQuery("#pagu_sumber_dana_" + id).val()
            if (dana) {
                total = total + parseInt(dana);
            }
        });
        jQuery("#rencana_pagu").val(total);
    }

    function edit_indikator(id, tipe, total_pagu, kode_sbl = false) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_indikator_rencana_aksi',
                api_key: esakip.api_key,
                id: id,
                tahun_anggaran: '<?php echo $input['tahun'] ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (
                    response.status == 'success' &&
                    response.data != null
                ) {
                    if(!response.data.total_pagu){
                        response.data.total_pagu = 0;
                    }
                    total_pagu = +response.data.total_pagu;

                    let rencana_pagu = response.data.rencana_pagu != null ? response.data.rencana_pagu : 0;

                    var persen = 0;
                    if(total_pagu <= 0){
                        persen = 100;
                    }else{
                        persen = setToFixed((rencana_pagu / total_pagu) * 100);
                    }

                    var setting_input_rencana_pagu = 0;
                    if(response.data.data_rhk_khusus.input_rencana_pagu_level != undefined){
                        setting_input_rencana_pagu = response.data.data_rhk_khusus.input_rencana_pagu_level;
                    }

                    tambah_indikator_rencana_aksi(response.data.id_renaksi, tipe, total_pagu, setting_input_rencana_pagu, kode_sbl, response.data.sumber_dana).then(function(){
                        jQuery("#modal-crud").find('.modal-title').text(jQuery("#modal-crud").find('.modal-title').text().replace('Tambah', 'Edit'));
                        jQuery('#id_label_indikator').val(id);
                        jQuery('#indikator').val(response.data.indikator);
                        jQuery('#satuan_indikator').val(response.data.satuan);
                        jQuery('#target_awal').val(response.data.target_awal);
                        jQuery('#target_akhir').val(response.data.target_akhir);
                        jQuery('#target_tw_1').val(response.data.target_1);
                        jQuery('#target_tw_2').val(response.data.target_2);
                        jQuery('#target_tw_3').val(response.data.target_3);
                        jQuery('#target_tw_4').val(response.data.target_4);
                        jQuery('#aspek_rhk').val(response.data.aspek_rhk);
                        jQuery('#target_teks_awal').val(response.data.target_teks_awal);
                        jQuery('#target_teks_akhir').val(response.data.target_teks_akhir);
                        jQuery('#target_teks_tw_1').val(response.data.target_teks_1);
                        jQuery('#target_teks_tw_2').val(response.data.target_teks_2);
                        jQuery('#target_teks_tw_3').val(response.data.target_teks_3);
                        jQuery('#target_teks_tw_4').val(response.data.target_teks_4);
                        jQuery('#rumus_capaian_kinerja').val(response.data.rumus_capaian_kinerja);

                        if(persen < 0){
                            persen = 100;
                        }
                        jQuery('#total_rincian').val(persen).trigger('input');
                        jQuery('#rencana_pagu_tk').val(total_pagu);
                        if (response.data.rumus_indikator) {
                            jQuery('#rumus-indikator').val(response.data.rumus_indikator);
                        } else {
                            jQuery('#rumus-indikator').val('(Realisasi Indikator / Target Indikator) * 100 = Capaian');
                        }

                        if (response.data.set_target_teks == 1) {
                            jQuery('#cek-target-teks').prop('checked', true);
                            jQuery(".target-teks").show();
                        } else {
                            jQuery('#cek-target-teks').prop('checked', false);
                            jQuery(".target-teks").hide();
                        }
                        jQuery('#wrap-loading').hide();
                    });
                } else if (response.status == 'error') {
                    alert(response.message);
                    jQuery('#wrap-loading').hide();
                }
            }
        });
    }

    function hapus_indikator(id, tipe) {
        var title = '';
        var parent_renaksi = 0;
        var parent_pokin = 0;
        var parent_cascading = 0;
        var parent_sub_skpd = 0;
        if (tipe == 1) {
            title = 'Kegiatan Utama';
        } else if (tipe == 2) {
            title = 'Rencana Hasil Kerja';
            var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
            var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
            var parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
            var parent_sub_skpd = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
        } else if (tipe == 3) {
            title = 'Uraian Kegiatan Rencana Hasil Kerja';
            var parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
            var parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
            var parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
            var parent_sub_skpd = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
        } else if (tipe == 4) {
            title = 'Uraian Teknis Kegiatan';
            var parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
            var parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
            var parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
            var parent_sub_skpd = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_sub_skpd');
        }
        if (confirm('Apakah kamu yakin untuk menghapus indikator ' + title + '?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'hapus_indikator_rencana_aksi',
                    api_key: esakip.api_key,
                    id: id,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>'
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);
                    alert(response.message);
                    if (response.status === 'success') {
                        if (tipe == 1) {
                            lihat_rencana_aksi(0, 1, 0, 0, 0);
                        } else {
                            lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data Rencana Hasil Kerja!');
                }
            });
        }
    }

    function hapus_rencana_aksi(id, tipe) {
        var title = '';
        var parent_pokin = 0;
        var parent_renaksi = 0;
        var parent_cascading = 0;
        var parent_sub_skpd = 0;
        if (tipe == 1) {
            title = 'Kegiatan Utama';
        } else if (tipe == 2) {
            title = 'Rencana Hasil Kerja';
            parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
            parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
            parent_sub_skpd = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
        } else if (tipe == 3) {
            title = 'Uraian Kegiatan Rencana Hasil Kerja';
            parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
            parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
            parent_sub_skpd = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
        } else if (tipe == 4) {
            title = 'Uraian Teknis Kegiatan';
            parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
            parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
            parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
            parent_sub_skpd = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_sub_skpd');
        }

        if (confirm('Apakah kamu yakin untuk menghapus ' + title + '?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'hapus_rencana_aksi',
                    api_key: esakip.api_key,
                    id: id,
                    tipe: tipe,
                    tahun_anggaran: '<?php echo $input['tahun'] ?>'
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);
                    alert(response.message);
                    if (response.status === 'success') {
                        if (tipe == 1) {
                            lihat_rencana_aksi(0, 1, 0, 0, 0);
                        } else {
                            console.log(parent_renaksi)
                            lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data Rencana Hasil Kerja!');
                }
            });
        }
    }

    function lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd = 0) {
        jQuery("#wrap-loading").show();
        return new Promise(function(resolve, reject) {
            var title = '';
            var fungsi_tambah = '';
            var id_tabel = '';
            var header_dasar_pelaksanaan = `<th class="text-center" style="width:50px;">Dasar Pelaksanaan</th>`;
            var header_pagu = `<th class="text-center" style="width:170px;">Rencana Pagu</th>`;
            let title_cascading = '';
            let rhk_level = '';

            // rencana aksi
            if (tipe == 1) {
                id_tabel = 'kegiatanUtama';
                title = 'Kegiatan Utama';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Sasaran Cascading';
                rhk_level = '1';
            } else if (tipe == 2) {
                id_tabel = 'tabel_rencana_aksi';
                title = 'Rencana Hasil Kerja';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Program Cascading';
                rhk_level = '2';
            } else if (tipe == 3) {
                id_tabel = 'tabel_uraian_rencana_aksi';
                title = 'Uraian Kegiatan Rencana Hasil Kerja';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Kegiatan Cascading';
                rhk_level = '3';
            } else if (tipe == 4) {
                id_tabel = 'tabel_uraian_teknis_kegiatan';
                title = 'Uraian Teknis Kegiatan';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Sub Kegiatan Cascading';
                rhk_level = '4';
            }
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_renaksi",
                    "level": tipe,
                    "parent": parent_renaksi,
                    "api_key": esakip.api_key,
                    "tipe_pokin": "opd",
                    "id_skpd": <?php echo $id_skpd; ?>,
                    "tahun_anggaran": '<?php echo $input['tahun'] ?>'
                },
                dataType: "json",
                success: function(res) {
                    jQuery('#wrap-loading').hide();
                    let renaksi = ``;

                    if (hak_akses_pegawai == 1) {
                        renaksi += `<div style="margin-top:10px">` +
                            `<button type="button" class="btn btn-success mb-2" onclick="` + fungsi_tambah + `(` + tipe + `);"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data ` + title + `</button>` +
                            `</div>`;
                    } else {
                        renaksi += `<div style="margin-top:10px">` +
                            `</div>`;
                    }

                    if(res.data_parent.length >= 1){
                        renaksi += `` +
                            `<table class="table">` +
                            `<thead>`;
                        res.data_parent.map(function(value, index) {
                            if (value != null) {
                                let label_parent = '';
                                switch (index + 1) {
                                    case 1:
                                        label_parent = "Kegiatan Utama"
                                        break;

                                    case 2:
                                        label_parent = "Rencana Hasil Kerja"
                                        break;

                                    case 3:
                                        label_parent = "Uraian Kegiatan Rencana Hasil Kerja"
                                        break;

                                    default:
                                        label_parent = "-";
                                        break;
                                }

                                let tipe_parent = tipe - 1;
                                let detail_parent = "<a href='javascript:void(0)' onclick='detail_parent(" + tipe_parent + "); return false;' title='Detail Parent'><i class='dashicons dashicons-info'></i></a>";
                                renaksi += `` +
                                    `<tr>` +
                                    `<th class="text-center" style="width: 160px;">${label_parent}</th>` +
                                    `<th class="text-left">${value} ${detail_parent}</th>` +
                                    `</tr>`;
                            }
                        });
                        renaksi += `</thead>` +
                            `</table>` ;
                    }

                    renaksi += ''+
                        '<table class="table" id="' + id_tabel + '" parent_renaksi="' + parent_renaksi + '" parent_pokin="' + parent_pokin + '" parent_cascading="' + parent_cascading + '" parent_sub_skpd="' + parent_sub_skpd + '">' +
                            `<thead>` +
                                `<tr class="table-secondary">` +
                                    `<th class="text-center" style="width:40px;">No</th>` +
                                    `<th class="text-center" style="width:300px;">Pohon Kinerja Perangkat Daerah</th>` +
                                    `<th class="text-center">` + title + ` | RHK Level ` + rhk_level + `</th>` +
                                    `<th class="text-center" style="width:300px;">` + title_cascading + `</th>` +
                                    `${header_dasar_pelaksanaan}` +
                                    `<th class="text-center" style="width:300px;">Pegawai Pelaksana</th>` +
                                    `<th class="text-center" style="width:60px;">Aksi</th>` +
                                `</tr>` +
                            `</thead>` +
                            `<tbody>`;
                    res.data.map(function(value, index) {
                        var id_pokin = [];
                        var tombol_detail = '';
                        var id_parent_cascading = 0;
                        var label_cascading = '-';
                        var label_dasar_pelaksanaan = '';
                        var total_pagu = 0;
                        let get_data_dasar_pelaksanaan = [];
                        let label_pokin = '-';
                        let id_parent_sub_skpd_cascading = 0;
                        let set_input_rencana_pagu = 0;

                        var nama_pegawai = '';
                        if (value.detail_satker && value.detail_satker.nama) {
                            nama_pegawai += value.detail_satker.nama + '<br>';
                        }
                        if (value.detail_pegawai && value.detail_pegawai.nip_baru) {
                            nama_pegawai += '<span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value.detail_pegawai.nip_baru + ' ' + value.detail_pegawai.nama_pegawai + '</span>';
                        }
                        if(value.input_rencana_pagu_level){
                            set_input_rencana_pagu = value.input_rencana_pagu_level;
                        }

                        total_pagu = value.total_pagu;
                        value.get_data_dasar.forEach(function(dasar) {
                            if (dasar.mandatori_pusat == 1 && !get_data_dasar_pelaksanaan.includes('Mandatori Pusat')) {
                                get_data_dasar_pelaksanaan.push('Mandatori Pusat');
                            }
                            if (dasar.inisiatif_kd == 1 && !get_data_dasar_pelaksanaan.includes('Inisiatif Kepala Daerah')) {
                                get_data_dasar_pelaksanaan.push('Inisiatif Kepala Daerah');
                            }
                            if (dasar.musrembang == 1 && !get_data_dasar_pelaksanaan.includes('MUSREMBANG (Musyawarah Rencana Pembangunan)')) {
                                get_data_dasar_pelaksanaan.push('MUSREMBANG (Musyawarah Rencana Pembangunan)');
                            }
                            if (dasar.pokir == 1 && !get_data_dasar_pelaksanaan.includes('POKIR (Pokok Pikiran)')) {
                                get_data_dasar_pelaksanaan.push('POKIR (Pokok Pikiran)');
                            }
                        });

                        if (get_data_dasar_pelaksanaan.length > 0) {
                            label_dasar_pelaksanaan = `<ul style="margin: 0;"><li>${get_data_dasar_pelaksanaan.join('</li><li>')}</li></ul>`;
                        }

                        // jika input pagu maka ditampilkan semua pokin
                        if(set_input_rencana_pagu == 1){
                            if (value.pokin && value.pokin.length > 0) {
                                label_pokin = `<ul style="margin: 0;">`;
                                value.pokin.forEach(function(get_pokin) {
                                    label_pokin += `<li>lv.${get_pokin.level} ${get_pokin.pokin_label}</li>`;
                                    id_pokin.push(+get_pokin.id_pokin);
                                });
                                label_pokin += `</ul>`;
                            }
                        }else{
                            if (value.pokin && value.pokin.length > 0) {
                                label_pokin = `<ul style="margin: 0;">`;
                                value.pokin.forEach(function(get_pokin) {
                                    if(
                                        (
                                            tipe == 1
                                            && (
                                                get_pokin.level == 1
                                                || get_pokin.level == 2
                                            )
                                        )
                                        || (
                                            (tipe+1) == get_pokin.level
                                        )
                                    ){
                                        label_pokin += `<li>lv.${get_pokin.level} ${get_pokin.pokin_label}</li>`;
                                        id_pokin.push(+get_pokin.id_pokin);
                                    }
                                });
                                label_pokin += `</ul>`;
                            }
                        }

                        if (tipe == 1) {
                            id_parent_cascading = value['kode_cascading_sasaran'];
                            label_cascading = value['label_cascading_sasaran'] != null ? value['label_cascading_sasaran'] : '-';
                            tombol_detail = `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm mb-1 btn-warning" onclick="lihat_rencana_aksi(${value.id}, ` + (tipe + 1) + `, ` + JSON.stringify(id_pokin) + `, '` + id_parent_cascading + `')" title="Lihat Rencana Hasil Kerja"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                        } else if (tipe == 2) {
                            id_parent_cascading = value['kode_cascading_program'];
                            id_parent_sub_skpd_cascading = value['id_sub_skpd_cascading'] != null ? value['id_sub_skpd_cascading'] : 0;

                            if (value['label_cascading_program']) {
                                let nama_prog = value['label_cascading_program'];
                                var pagu = ' | Rp. ' + formatRupiah(value['pagu_cascading']);
                                if(set_input_rencana_pagu == 1){
                                    pagu = '';
                                }
                                label_cascading = value['kode_cascading_program'] + ' ' + nama_prog + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + pagu + '</span>';
                            }

                            tombol_detail = `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm mb-1 btn-warning" onclick="lihat_rencana_aksi(${value.id}, ${ (tipe + 1) }, ${ JSON.stringify(id_pokin) }, '${ id_parent_cascading }', ${ id_parent_sub_skpd_cascading })" title="Lihat Uraian Kegiatan Rencana Hasil Kerja"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                        } else if (tipe == 3) {
                            id_parent_cascading = value['kode_cascading_kegiatan'];
                            id_parent_sub_skpd_cascading = value['id_sub_skpd_cascading'] != null ? value['id_sub_skpd_cascading'] : 0;

                            if (value['label_cascading_kegiatan']) {
                                let nama_keg = value['label_cascading_kegiatan'];
                                var pagu = ' | Rp. ' + formatRupiah(value['pagu_cascading']);
                                if(set_input_rencana_pagu == 1){
                                    pagu = '';
                                }
                                label_cascading = value['kode_cascading_kegiatan'] + ' ' + nama_keg + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + pagu + '</span>';
                            }

                            tombol_detail = `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm mb-1 btn-warning" onclick="lihat_rencana_aksi(${value.id}, ${ (tipe + 1) }, ${ JSON.stringify(id_pokin) }, '${ id_parent_cascading }', ${id_parent_sub_skpd_cascading})" title="Lihat Uraian Teknis Kegiatan"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                        } else if (tipe == 4) {
                            id_pokin = value['id_pokin_5'];

                            if (value['label_cascading_sub_kegiatan']) {
                                let nama_subkeg = value['label_cascading_sub_kegiatan'].split(" ").slice(1).join(" ");
                                label_cascading = value['kode_cascading_sub_kegiatan'] + ' ' + nama_subkeg + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + ' | Rp. ' + formatRupiah(value['pagu_cascading']) + '</span>';
                            }
                        }

                        // jika input pagu maka ditampilkan sampai sub kegiatan
                        var bg_input_pagu = '';
                        if(set_input_rencana_pagu == 1){
                            if(tipe <= 1) {
                                if (value['label_cascading_program']) {
                                    let nama_prog = value['label_cascading_program'];
                                    label_cascading += '</br>'+value['kode_cascading_program'] + ' ' + nama_prog + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + '</span>';
                                }
                            }
                            if(tipe <= 2) {
                                if (value['label_cascading_kegiatan']) {
                                    let nama_keg = value['label_cascading_kegiatan'];
                                    label_cascading += '</br>'+value['kode_cascading_kegiatan'] + ' ' + nama_keg + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + '</span>';
                                }
                            }
                            if(tipe <= 3) {
                                if (value['label_cascading_sub_kegiatan']) {
                                    let nama_subkeg = value['label_cascading_sub_kegiatan'].split(" ").slice(1).join(" ");
                                    label_cascading += '</br>'+value['kode_cascading_sub_kegiatan'] + ' ' + nama_subkeg + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + ' | Rp. ' + formatRupiah(value['pagu_cascading']) + '</span>';
                                }
                            }
                            bg_input_pagu = 'style="background: #a1fe86;" title="Checklist Input Pagu RHK"';
                        }

                        renaksi += `` +
                            `<tr id="kegiatan_utama_${value.id}" class="table-warning">` +
                                `<td class="text-center">${index + 1}</td>` +
                                `<td class="label_pokin">${label_pokin}</td>` +
                                `<td class="label_renaksi font-weight-bold">${value.label}</td>` +
                                `<td class="label_renaksi">${label_cascading}</td>` +
                                `<td class="label_renaksi">${label_dasar_pelaksanaan}</td>` +
                                `<td class="pegawai_pelaksana">${nama_pegawai}</td>` +
                                `<td class="text-center" ${bg_input_pagu}>`;

                        // untuk validasi tombol user kepala dan pegawai
                        if (
                            hak_akses_pegawai == 1 
                            || (
                                hak_akses_pegawai == 2 
                                && value.detail_pegawai 
                                && value.detail_pegawai.nip_baru 
                                && nip_pegawai == value.detail_pegawai.nip_baru
                            )
                        ) {
                            renaksi += `<a href="javascript:void(0)" class="btn btn-sm mb-1 btn-success" onclick="tambah_indikator_rencana_aksi_baru(${value.id}, ${tipe},${total_pagu}, ${ set_input_rencana_pagu }, '${value.kode_sbl}')" title="Tambah Indikator (Total Pagu: ${formatRupiah(total_pagu)})"><i class="dashicons dashicons-plus"></i></a> `;
                            renaksi += tombol_detail;
                            renaksi += `<a href="javascript:void(0)" onclick="edit_rencana_aksi(${value.id}, ` + tipe + `)" data-id="${value.id}" class="btn btn-sm mb-1 btn-primary edit-kegiatan-utama" title="Edit"><i class="dashicons dashicons-edit"></i></a>`;
                        }else{
                            renaksi += tombol_detail;
                        }

                        if (hak_akses_pegawai == 1) {
                            renaksi += `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm mb-1 btn-danger" onclick="hapus_rencana_aksi(${value.id}, ${tipe})" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`;
                        }
                        renaksi += `` +
                                `</td>` +
                            `</tr>`;

                        let indikator = value.indikator;
                        if (indikator.length > 0) {
                            renaksi += '' +
                                '<td colspan="7" style="padding: 10px;">' +
                                    `<table class="table" style="margin: 0;">` +
                                        `<thead>` +
                                            `<tr class="table-info">` +
                                                `<th class="text-center" style="width:20px">No</th>` +
                                                `<th class="text-center">Aspek</th>` +
                                                `<th class="text-center">Indikator</th>` +
                                                `<th class="text-center" style="width:120px;">Satuan</th>` +
                                                `<th class="text-center" style="width:50px;">Target Awal</th>` +
                                                `<th class="text-center" style="width:50px;">Target Akhir</th>` +
                                                `${header_pagu}` +
                                                `<th class="text-center" style="width:125px">Aksi</th>` +
                                            `</tr>` +
                                        `</thead>` +
                                    `<tbody>`;

                            indikator.map(function(b, i) {
                                let rencana_pagu = b.rencana_pagu != null ? b.rencana_pagu : 0;
                                let realisasi_pagu = b.realisasi_pagu != null ? b.realisasi_pagu : 0;
                                let val_pagu = '';
                                let aspek_rhk = ["Kuantitas", "Kualitas", "Waktu", "Biaya"];
                                let text_aspek_rhk = '';
                                if (b.aspek_rhk != null || b.aspek_rhk != undefined) {
                                    text_aspek_rhk = aspek_rhk[b.aspek_rhk - 1];
                                }
                                let target_teks_awal = '';
                                if (b.target_teks_awal != null || b.target_teks_awal != undefined) {
                                    target_teks_awal = `</br>(${b.target_teks_awal})`;
                                }
                                let target_teks_akhir = '';
                                if (b.target_teks_akhir != null || b.target_teks_akhir != undefined) {
                                    target_teks_akhir = `</br>(${b.target_teks_akhir})`;
                                }
                                let val_rumus_indikator = '(Realisasi Indikator / Target Indikator) * 100 = Capaian';
                                if (b.rumus_indikator) {
                                    val_rumus_indikator = b.rumus_indikator;
                                }

                                const targetAkhir = parseFloat(String(b['target_akhir'] || '0').replace(',', '.'));

                                const realisasi_1 = parseFloat(String(b['realisasi_tw_1'] || '0').replace(',', '.'));
                                const realisasi_2 = parseFloat(String(b['realisasi_tw_2'] || '0').replace(',', '.'));
                                const realisasi_3 = parseFloat(String(b['realisasi_tw_3'] || '0').replace(',', '.'));
                                const realisasi_4 = parseFloat(String(b['realisasi_tw_4'] || '0').replace(',', '.'));

                                const target_1 = parseFloat(String(b['target_1'] || '0').replace(',', '.'));
                                const target_2 = parseFloat(String(b['target_2'] || '0').replace(',', '.'));
                                const target_3 = parseFloat(String(b['target_3'] || '0').replace(',', '.'));
                                const target_4 = parseFloat(String(b['target_4'] || '0').replace(',', '.'));

                                const allRealisasiTW = {
                                    'realisasi_1': realisasi_1,
                                    'realisasi_2': realisasi_2,
                                    'realisasi_3': realisasi_3,
                                    'realisasi_4': realisasi_4
                                };

                                const allTargetTW = {
                                    'target_1': target_1,
                                    'target_2': target_2,
                                    'target_3': target_3,
                                    'target_4': target_4
                                };

                                renaksi += '' +
                                    `<tr>` +
                                        `<td class="text-center">${index + 1}.${i + 1}</td>` +
                                        `<td>${text_aspek_rhk}</td>` +
                                        `<td><a href="<?php echo $rincian_tagging_url; ?>&id_indikator=${b.id}" title="Rincian Belanja" target="_blank">${b.indikator}</a></td>` +
                                        `<td class="text-center">${b.satuan}</td>` +
                                        `<td class="text-center">${b.target_awal} ${target_teks_awal}</td>` +
                                        `<td class="text-center">${b.target_akhir} ${target_teks_akhir}</td>` +
                                        `<td class="text-right">${formatRupiah(b.rencana_pagu) || 0}</td>` +
                                        `<td class="text-center">` +
                                            `<input type="checkbox" title="Lihat Rencana Hasil Kerja Per Bulan" class="lihat_bulanan" data-id="${b.id}" onclick="lihat_bulanan(this);" style="margin: 0 6px 0 0;">`;
                                if (
                                    hak_akses_pegawai == 1 
                                    || (
                                        hak_akses_pegawai == 2 
                                        && value.detail_pegawai 
                                        && value.detail_pegawai.nip_baru 
                                        && nip_pegawai == value.detail_pegawai.nip_baru
                                    )
                                ) {
                                    renaksi += `` +
                                        `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-primary" onclick="edit_indikator(${b.id}, ` + tipe + `,${total_pagu}, '${value.kode_sbl}')" title="Edit"><i class="dashicons dashicons-edit"></i></a> ` +
                                        `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-danger" onclick="hapus_indikator(${b.id}, ` + tipe + `);" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`;
                                }
                                renaksi += `` +
                                        `</td>` +
                                    `</tr>`;

                                renaksi += `` +
                                    `<tr style="display: none;" class="data_bulanan_${b.id}">` +
                                        `<td colspan="8" style="padding: 10px;">` +
                                        `<div style="display: none; margin: 1rem auto;" class="data_bulanan_${b.id}">` +
                                        `<h4 class="text-center" style="margin: 10px;">Rumus Indikator Teks / Formulasi Perhitungan Indikator</h4>` +
                                        `<textarea class="form-control" id="show-rumus-indikator">${val_rumus_indikator}</textarea>` +
                                        `</div>` +
                                        `</td>` +
                                    `</tr>`;

                                const get_bulan = [
                                    "Januari", 
                                    "Februari", 
                                    "Maret", 
                                    "April", 
                                    "Mei", 
                                    "Juni",
                                    "Juli", 
                                    "Agustus", 
                                    "September", 
                                    "Oktober", 
                                    "November", 
                                    "Desember"
                                ];

                                let bulanan = b.bulanan || [];
                                let sum_realisasi_triwulan = 0;

                                renaksi += '' +
                                    `<tr style="display: none;" class="data_bulanan_${b.id}">` +
                                        `<td colspan="8" style="padding: 10px;">` +
                                            `<h3 class="text-center" style="margin: 10px;">Rencana Aksi Per Bulan</h3>` +
                                            `<table class="table" style="margin: 0;">` +
                                                `<thead>` +
                                                    `<tr class="table-secondary">` +
                                                        `<th class="text-center">Bulan/TW</th>` +
                                                        `<th class="text-center">Rencana Aksi</th>` +
                                                        `<th class="text-center" style="width:100px;">Target</th>` +
                                                        `<th class="text-center" style="width:100px;">Satuan</th>` +
                                                        `<th class="text-center" style="width:150px;">Realisasi</th>` +
                                                        `<th class="text-center" style="width:60px">Capaian</th>` +
                                                        `<th class="text-center">Tanggapan Atasan</th>` +
                                                        `<th class="text-center" style="width:60px">Aksi</th>` +
                                                    `</tr>` +
                                                `</thead>` +
                                            `<tbody>`;

                                let isdisabled = <?php echo $set_renaksi == 1 ? 'true' : 'false'; ?>;
                                get_bulan.forEach((bulan, bulan_index) => {
                                    let get_data_bulanan = b.bulanan.find(bulanan => bulanan.bulan == (bulan_index + 1)) || {};

                                    let unserialize = (data) => {
                                        try {
                                            return data ? JSON.parse(data) : "";
                                        } catch (e) {
                                            return data || "";
                                        }
                                    };

                                   let parseSerializedData = (data) => {
                                        if (!data) {
                                            return [];
                                        }
                                        
                                        const matches = data.matchAll(/N;|s:\d+:"(.*?)"/g);
                                        
                                        const result = Array.from(matches, m => {
                                            if (m[1] !== undefined) {
                                                return m[1];
                                            } else {
                                                return "";
                                            }
                                        });

                                        return result;
                                    };

                                    let rencana_aksi = parseSerializedData(get_data_bulanan.rencana_aksi);
                                    let volume = parseSerializedData(get_data_bulanan.volume);
                                    let satuan = parseSerializedData(get_data_bulanan.satuan);
                                    let realisasi = parseSerializedData(get_data_bulanan.realisasi);
                                    let capaian = parseSerializedData(get_data_bulanan.capaian);
                                    let keterangan = parseSerializedData(get_data_bulanan.keterangan);

                                    renaksi += `
                                        <tr>
                                            <td class="text-center">${bulan}</td>
                                            <td class="text-center">`;

                                    rencana_aksi.forEach((aksi, index) => {
                                        renaksi += `<textarea class="form-control" name="rencana_aksi_${b.id}_${bulan_index + 1}_${index}" id="rencana_aksi_${b.id}_${bulan_index + 1}_${index}" ${isdisabled ? 'disabled' : ''}>${aksi}</textarea>`;
                                    });
                                    renaksi += `
                                            </td>
                                            <td class="text-center">`;
                                    volume.forEach((vol, index) => {
                                        renaksi += `<input type="text" class="form-control" name="volume_${b.id}_${bulan_index + 1}_${index}" id="volume_${b.id}_${bulan_index + 1}_${index}" value="${vol}" ${isdisabled ? 'disabled' : ''}>`;
                                    });
                                    renaksi += `
                                            </td>
                                            <td class="text-center">`;

                                    satuan.forEach((sat, index) => {
                                        renaksi += `<input type="text" class="form-control" name="satuan_bulan_${b.id}_${bulan_index + 1}_${index}" id="satuan_bulan_${b.id}_${bulan_index + 1}_${index}" value="${sat}" ${isdisabled ? 'disabled' : ''}>`;
                                    });
                                    renaksi += `
                                            </td>
                                            <td class="text-center">`;

                                    realisasi.forEach((real, index) => {
                                        renaksi += `<input type="number" class="form-control" name="realisasi_${b.id}_${bulan_index + 1}_${index}" id="realisasi_${b.id}_${bulan_index + 1}_${index}" value="${real}" ${isdisabled ? 'disabled' : ''}>`;
                                    });
                                    renaksi += `
                                            </td>
                                            <td class="text-center">`;
                                    capaian.forEach((cap, index) => {
                                        renaksi += `<input type="text" class="form-control" name="capaian_${b.id}_${bulan_index + 1}_${index}" id="capaian_${b.id}_${bulan_index + 1}_${index}" value="${cap}" ${isdisabled ? 'disabled' : ''}>`;
                                    });
                                    renaksi += `
                                            </td>
                                            <td class="text-center">`;

                                    keterangan.forEach((ket, index) => {
                                        renaksi += `<textarea class="form-control" name="keterangan_${b.id}_${bulan_index + 1}_${index}" id="keterangan_${b.id}_${bulan_index + 1}_${index}" ${isdisabled ? 'disabled' : ''}>${ket}</textarea>`;
                                    });
                                    renaksi += `
                                            </td>
                                            <td class="text-center">`;

                                    if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                        renaksi += isdisabled ? `-` : `<a href="javascript:void(0)" data-id="${b.id}" data-bulan="${bulan_index + 1}" class="btn btn-sm btn-success" onclick="simpan_bulanan(${b.id}, ${bulan_index + 1})" title="Simpan"><i class="dashicons dashicons-yes"></i></a>`;
                                    }
                                    renaksi += `
                                            </td>
                                        </tr>`;
                                    if ((bulan_index + 1) % 3 == 0) {
                                        var triwulan = (bulan_index + 1) / 3;

                                        let target = allTargetTW['target_' + triwulan];
                                        let realisasi = allRealisasiTW['realisasi_' + triwulan];

                                        let capaian_triwulanan = target && target !== 0
                                        ? parseFloat(((realisasi / target) * 100).toFixed(2))
                                        : 0;

                                        sum_realisasi_triwulan = sum_realisasi_triwulan + realisasi;
                                        
                                        renaksi += '' +
                                            `<tr style="background: #FDFFB6;">` +
                                                `<td class="text-center">Triwulan ${triwulan}</td>` +
                                                `<td class="text-center">${b.indikator}</td>` +
                                                `<td class="text-center">${b['target_' + triwulan]}</td>` +
                                                `<td class="text-center">${b.satuan}</td>` +
                                                `<td class="text-center">` +
                                                    `<input type="number" class="form-control" name="realisasi_${b.id}_tw_${triwulan}" id="realisasi_${b.id}_tw_${triwulan}" ${isdisabled ? 'disabled' : ''} value="${allRealisasiTW['realisasi_' + triwulan] || 0}">` +
                                                `</td>` +
                                                `<td class="text-center font-weight-bold">${capaian_triwulanan}%</td>` +
                                                `<td class="text-center">` +
                                                    `<textarea class="form-control" name="keterangan_${b.id}_tw_${triwulan}" id="keterangan_${b.id}_tw_${triwulan}" ${isdisabled ? 'disabled' : ''}>${b['ket_tw_' + triwulan] || ''}</textarea>` +
                                                `</td>` +
                                                `<td class="text-center">`;
                                        if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                            renaksi += `` +
                                                (isdisabled ?
                                                    `-` :
                                                    `<a href="javascript:void(0)" data-id="${b.id}" data-tw="${triwulan}" class="btn btn-sm btn-success" onclick="simpan_triwulan(${b.id}, ${triwulan})" title="Simpan"><i class="dashicons dashicons-yes"></i></a>`
                                                );
                                        }
                                        renaksi += `` +
                                                `</td>` +
                                            `</tr>`;
                                    }

                                });

                                // ----- capaian tahunan -----
                                let capaian_kinerja_tahunan = getCapaianTahunanRealisasiByType(
                                    b['rumus_capaian_kinerja'],
                                    targetAkhir, 
                                    allRealisasiTW, 
                                    <?php echo $input['tahun']; ?>
                                );

                                if (capaian_kinerja_tahunan === null || isNaN(capaian_kinerja_tahunan)) {
                                    capaian_kinerja_tahunan = 'N/A';
                                } else {
                                    capaian_kinerja_tahunan = capaian_kinerja_tahunan + '%';
                                }

                                renaksi += '' +
                                    `<tr class="table-secondary">` +
                                        `<th class="text-center">Total</th>` +
                                        `<td class="text-center">${b.indikator}</td>` +
                                        `<td class="text-center">${b.target_akhir}</td>` +
                                        `<td class="text-center">${b.satuan}</td>` +
                                        `<td class="text-center"><input type="number" class="form-control" name="realisasi_akhir_${b.id}" id="realisasi_akhir_${b.id}" value="${sum_realisasi_triwulan}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center font-weight-bold">${capaian_kinerja_tahunan}</td>` +
                                        `<td class="text-center"><textarea class="form-control" name="ket_total_${b.id}" id="ket_total_${b.id}" ${isdisabled ? 'disabled' : ''}>${b['ket_total'] || ''}</textarea></td>` +
                                        `<td class="text-center">`;
                                if (
                                    hak_akses_pegawai == 1 
                                    || (
                                        hak_akses_pegawai == 2 
                                        && value.detail_pegawai 
                                        && value.detail_pegawai.nip_baru 
                                        && nip_pegawai == value.detail_pegawai.nip_baru
                                    )
                                ) {
                                    renaksi += `` +
                                        (isdisabled ?
                                            `-` :
                                            `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-success" onclick="simpan_total(${b.id})" title="Simpan Total"><i class="dashicons dashicons-yes"></i></a>`
                                        );
                                }
                                renaksi += `` +
                                                    `</td>` +
                                                `</tr>` +
                                            `</tbody>` +
                                        `</table>` +
                                    `</td>` +
                                `</tr>`;
                            });

                            renaksi += '' +
                                        `</tbody>` +
                                    `</table>` +
                                `</td>`;
                        }

                    });
                    renaksi += '' +
                            `<tbody>` +
                        `</table>`;

                    jQuery("#nav-level-" + tipe).html(renaksi);
                    jQuery('.nav-tabs a[href="#nav-level-' + tipe + '"]').tab('show');
                    if(tipe == 1){
                        jQuery('#modal-renaksi').modal('show');
                    }
                    resolve();
                }
            });
        });
    }

    function lihat_bulanan(that) {
        var id_ind = jQuery(that).attr('data-id');
        if (jQuery(that).is(':checked')) {
            jQuery('.data_bulanan_' + id_ind).show();
        } else {
            jQuery('.data_bulanan_' + id_ind).hide();
        }
    }

    function getCapaianTahunanRealisasiByType(type, target, realisasi, tahunAnggaran, triwulan = null) {
        type = Number(type);
        target = parseFloat(target);
        tahunAnggaran = Number(tahunAnggaran);

        let limitQuarter;
        let targetUntukPerhitungan;

        if (triwulan !== null && triwulan !== undefined) {
            // KASUS 1: 'triwulan' DIISI -> Hitung spesifik untuk triwulan tsb.
            
            triwulan = Number(triwulan);
            if (![1, 2, 3, 4].includes(triwulan)) {
                console.error("Parameter 'triwulan' tidak valid. Harap masukkan angka antara 1 dan 4.");
                return null;
            }
            
            limitQuarter = triwulan;
            targetUntukPerhitungan = target;

        } else {
            // KASUS 2: 'triwulan' TIDAK DIISI -> Gunakan logika waktu (default).

            const today = new Date();
            const currentYear = today.getFullYear();
            
            if (tahunAnggaran < currentYear) {
                limitQuarter = 4;
            } else if (tahunAnggaran === currentYear) {
                const currentMonth = today.getMonth() + 1;
                limitQuarter = Math.ceil(currentMonth / 3);
            } else {
                return 0.0; // Capaian masa depan
            }

            targetUntukPerhitungan = target;
        }

        // --- Perhitungan ---

        let pembilang = 0.0;
        let penyebut = 0.0;

        let realisasiKumulatif = 0.0;
        for (let i = 1; i <= limitQuarter; i++) {
            realisasiKumulatif += realisasi['realisasi_' + i] ?? 0;
        }

        switch (type) {
            case 1:
                pembilang = realisasiKumulatif;
                penyebut = targetUntukPerhitungan;
                break;
            case 2:
                pembilang = realisasi['realisasi_' + limitQuarter] ?? 0;
                penyebut = targetUntukPerhitungan;
                break;
            case 3:
                pembilang = targetUntukPerhitungan;
                penyebut = realisasiKumulatif;
                break;
            default:
                return null;
        }

        if (penyebut === 0) {
            // KHUSUS untuk Tipe 3 (Tren Negatif)
            if (type === 3) {
                // Jika Realisasi adalah 0, cek targetnya ('pembilang').
                // Jika Target juga 0, maka capaian 100%. Jika tidak, capaian 0%.
                return (pembilang === 0) ? 100.0 : 0.0;
            }
            return 0.0; // Hindari pembagian dengan nol
        }

        let hasil = 0.0;
        if (type === 3) {
            hasil = ((pembilang - penyebut) / pembilang) * 100;
        } else {
            hasil = (pembilang / penyebut) * 100;
        }

        return parseFloat(hasil.toFixed(2));
    }

    function detail_parent(tipe_parent) {
        jQuery('.nav-tabs a[href="#nav-level-' + tipe_parent + '"]').tab('show');
    }

    function cek_input_pagu_parent(tipe){
        return new Promise(function(resolve, reject){
            var parent_renaksi = 0;
            switch (tipe) {
                case 2:
                    parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                    break;

                case 3:
                    parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                    break;

                case 4:
                    parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                    break;
            }
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": 'cek_input_pagu_parent',
                    "api_key": esakip.api_key,
                    "id_parent": parent_renaksi
                },
                dataType: "json",
                success: function(res) {
                    window.cek_parent_global = res;
                    jQuery('#wrap-loading').hide();
                    resolve(res);
                }
            })
        });
    }

    function tambah_renaksi_2(tipe, isEdit = false) {
        return new Promise(function(resolve, reject){
            new Promise(function(resolve2, reject2){
                if(tipe == 1){
                    resolve2(false);
                }else{
                    cek_input_pagu_parent(tipe).then(function(){
                        resolve2();
                    });
                }
            })
            .then(function(response){
                tambah_renaksi_2_final(tipe, isEdit, response)
                .then(function(){
                    resolve();
                });
            });
        });
    }

    function get_id_unik_from_cascading(jenis, kode_cascading, label_cascading = null, id_sub_skpd = null) {
        let sumber_data_cascading = {
            'sasaran': data_sasaran_cascading,
            'program': data_program_cascading,
            'kegiatan': data_kegiatan_cascading,
            'sub_kegiatan': data_sub_kegiatan_cascading
        };
        let data_cascading = sumber_data_cascading[jenis];
        
        if (typeof data_cascading === 'undefined' || !kode_cascading) {
            console.log(`Data cascading untuk ${jenis} tidak ditemukan atau kode kosong`);
            return null;
        }
        
        
        for (let key in data_cascading) {
            if (data_cascading[key] && data_cascading[key].data) {
                let found = data_cascading[key].data.find(item => {
                    let isdata = false;
                    switch(jenis) {
                        case 'sasaran':
                            if (item.kode_bidang_urusan === kode_cascading) {
                                if (label_cascading) {
                                    isdata = item.sasaran_teks === label_cascading;
                                } else {
                                    isdata = true;
                                }
                            }
                            break;
                        case 'program':
                            if (item.kode_program === kode_cascading) {
                                if (id_sub_skpd && String(item.id_sub_skpd) !== String(id_sub_skpd)) {
                                    isdata = false;
                                } else if (label_cascading) {
                                    isdata = item.nama_program === label_cascading;
                                } else {
                                    isdata = true;
                                }
                            }
                            break;
                        case 'kegiatan':
                            if (item.kode_giat === kode_cascading) {
                                if (id_sub_skpd && String(item.id_sub_skpd) !== String(id_sub_skpd)) {
                                    isdata = false;
                                } else if (label_cascading) {
                                    isdata = item.nama_giat === label_cascading;
                                } else {
                                    isdata = true;
                                }
                            }
                            break;
                        case 'sub_kegiatan':                            
                            if (item.kode_sub_giat === kode_cascading) {
                                if (id_sub_skpd && String(item.id_sub_skpd) !== String(id_sub_skpd)) {
                                    isdata = false;
                                } else if (label_cascading) {
                                    let get_label_rhk = item.nama_sub_giat.split(' ');
                                    get_label_rhk.shift();
                                    let label_rhk = get_label_rhk.join(' ').trim();
                                    let label = label_cascading.trim();
                                    
                                    if (label_rhk === label) {
                                        isdata = true;
                                    } else {
                                        isdata = false;
                                    }
                                } else {
                                    isdata = true;
                                }
                            } else {
                                isdata = false;
                            }
                            break;
                    }
                    return isdata;
                });
                
                if (found) {
                    if (jenis === 'sasaran' && found.id_unik) {
                        console.log(`id_unik Ditemukan `);
                        return found.id_unik;
                    }
                    
                    if (['program', 'kegiatan', 'sub_kegiatan'].includes(jenis)) {
                        if (found.get_pokin_renstra && Array.isArray(found.get_pokin_renstra) && found.get_pokin_renstra.length > 0) {
                            if (found.get_pokin_renstra[0].id_unik) {
                                console.log(`id_unik Ditemukan`);
                                return found.get_pokin_renstra[0].id_unik;
                            }
                        }
                    }
                }
            }
        }
        
        console.log(`Tidak ditemukan id_unik untuk ${jenis}`);
        return null;
    }

    function tampilkan_pokin_dari_cascading(id_unik, jenis) {
        if (!id_unik || id_unik.trim() === '') {
            console.log('ID unik kosong atau tidak valid');
            return false;
        }
        
        let sumber_data_cascading = {
            'sasaran': data_sasaran_cascading,
            'program': data_program_cascading,
            'kegiatan': data_kegiatan_cascading,
            'sub_kegiatan': data_sub_kegiatan_cascading
        };
        
        let level_pokin_cascading = {
            'sasaran': [1, 2],
            'program': [3],
            'kegiatan': [4],
            'sub_kegiatan': [5]
        };
        
        let data_cascading = sumber_data_cascading[jenis];
        
        if (typeof data_cascading === 'undefined') {
            console.log(`Data ${jenis} cascading tidak ditemukan`);
            return false;
        }
        
        let get_pokin = null;
        let all_data_pokin = [];
        
        let id_unik_array = id_unik.includes(',') ? id_unik.split(',') : [id_unik];
        
        if (jenis === 'program' || jenis === 'kegiatan' || jenis === 'sub_kegiatan') {
            for (let key in data_cascading) {
                if (data_cascading[key] && data_cascading[key].data) {
                    for (let item of data_cascading[key].data) {
                        if (item.get_pokin_renstra && Array.isArray(item.get_pokin_renstra)) {
                            let found_pokin = item.get_pokin_renstra.find(pokin => 
                                id_unik_array.includes(pokin.id_unik)
                            );
                            if (found_pokin) {
                                all_data_pokin = item.get_pokin_renstra;
                                get_pokin = item;
                                break;
                            }
                        }
                    }
                    if (get_pokin) break;
                }
            }
        } else {
            for (let key in data_cascading) {
                if (data_cascading[key] && data_cascading[key].data) {
                    get_pokin = data_cascading[key].data.find(item => item.id_unik === id_unik);
                    if (get_pokin) {
                        all_data_pokin = get_pokin.get_pokin_renstra || [];
                        break;
                    }
                }
            }            
        }
        
        console.log(`Data pokin yang ditemukan:`, all_data_pokin);
        
        if (!get_pokin || all_data_pokin.length === 0) {
            console.log(`Data pokin ${jenis} kosong`);
            return false;
        }
        
        let all_data_level = level_pokin_cascading[jenis];
        
        let pokin_by_level = { 1: [], 2: [], 3: [], 4: [], 5: [] };
        
        all_data_pokin.forEach(pokin => {
            let level_pokin = parseInt(pokin.level);
            
            if (all_data_level.includes(level_pokin)) {
                if (!pokin_by_level[level_pokin].find(p => p.id_pokin === pokin.id_pokin)) {
                    pokin_by_level[level_pokin].push(pokin);
                }
            }
            
            if (level_pokin === 2 && pokin.pokin_level_1 && Array.isArray(pokin.pokin_level_1)) {
                pokin.pokin_level_1.forEach(pl1 => {
                    if (!pokin_by_level[1].find(p => p.id_pokin === pl1.id_pokin)) {
                        pokin_by_level[1].push(pl1);
                    }
                });
            }
            
            if (level_pokin === 3 && pokin.pokin_level_2 && Array.isArray(pokin.pokin_level_2)) {
                pokin.pokin_level_2.forEach(pl2 => {
                    if (!pokin_by_level[2].find(p => p.id_pokin === pl2.id_pokin)) {
                        pokin_by_level[2].push(pl2);
                    }
                    if (pl2.pokin_level_1 && Array.isArray(pl2.pokin_level_1)) {
                        pl2.pokin_level_1.forEach(pl1 => {
                            if (!pokin_by_level[1].find(p => p.id_pokin === pl1.id_pokin)) {
                                pokin_by_level[1].push(pl1);
                            }
                        });
                    }
                });
            }
            
            if (level_pokin === 4 && pokin.pokin_level_3 && Array.isArray(pokin.pokin_level_3)) {
                pokin.pokin_level_3.forEach(pl3 => {
                    if (!pokin_by_level[3].find(p => p.id_pokin === pl3.id_pokin)) {
                        pokin_by_level[3].push(pl3);
                    }
                    if (pl3.pokin_level_2 && Array.isArray(pl3.pokin_level_2)) {
                        pl3.pokin_level_2.forEach(pl2 => {
                            if (!pokin_by_level[2].find(p => p.id_pokin === pl2.id_pokin)) {
                                pokin_by_level[2].push(pl2);
                            }
                            if (pl2.pokin_level_1 && Array.isArray(pl2.pokin_level_1)) {
                                pl2.pokin_level_1.forEach(pl1 => {
                                    if (!pokin_by_level[1].find(p => p.id_pokin === pl1.id_pokin)) {
                                        pokin_by_level[1].push(pl1);
                                    }
                                });
                            }
                        });
                    }
                });
            }
            
            if (level_pokin === 5 && pokin.pokin_level_4 && Array.isArray(pokin.pokin_level_4)) {
                pokin.pokin_level_4.forEach(pl4 => {
                    if (!pokin_by_level[4].find(p => p.id_pokin === pl4.id_pokin)) {
                        pokin_by_level[4].push(pl4);
                    }
                    if (pl4.pokin_level_3 && Array.isArray(pl4.pokin_level_3)) {
                        pl4.pokin_level_3.forEach(pl3 => {
                            if (!pokin_by_level[3].find(p => p.id_pokin === pl3.id_pokin)) {
                                pokin_by_level[3].push(pl3);
                            }
                            if (pl3.pokin_level_2 && Array.isArray(pl3.pokin_level_2)) {
                                pl3.pokin_level_2.forEach(pl2 => {
                                    if (!pokin_by_level[2].find(p => p.id_pokin === pl2.id_pokin)) {
                                        pokin_by_level[2].push(pl2);
                                    }
                                    if (pl2.pokin_level_1 && Array.isArray(pl2.pokin_level_1)) {
                                        pl2.pokin_level_1.forEach(pl1 => {
                                            if (!pokin_by_level[1].find(p => p.id_pokin === pl1.id_pokin)) {
                                                pokin_by_level[1].push(pl1);
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        
        all_data_level.forEach(level => {
            jQuery(`#pokin-level-${level}`).html('');
            
            if (pokin_by_level[level].length > 0) {
                pokin_by_level[level].forEach(pokin => {
                    jQuery(`#pokin-level-${level}`).append(
                        `<option value="${pokin.id_pokin}" selected>${pokin.label}</option>`
                    );
                });
                jQuery(`#pokin-level-${level}`).trigger('change');
            }
        });
        
        return true;
    }

    function reset_pokin_cascading(start_level = 1) {
        for (let i = start_level; i <= 5; i++) {
            jQuery(`#pokin-level-${i}`).html('').trigger('change');
        }
    }

    function tampil_pokin(id, tampil=false){
        var display = 'display: none;';
        var _class = 'in_setting_input_rencana_pagu';
        if(tampil){
            display = '';
            _class = '';
        }
        return `
            <div class="form-group ${_class}" style="${display}"> 
                <label for="pokin-level-${id}">Pokin Level ${id}</label> 
                <select class="form-control" multiple name="pokin-level-${id}" id="pokin-level-${id}" disabled> 
                </select> 
            </div>
        `;
    }

    function tampil_cascading(id, tampil=false){
        var display = 'display: none;';
        var _class = 'in_setting_input_rencana_pagu';
        if(tampil){
            display = '';
            _class = '';
        }

        var id_html = '';
        var label = '';
        var onchange = '';
        if(id == 'sasaran'){
            id_html = 'cascading-renstra';
            label = 'Sasaran';  
            onchange = `onchange="
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                if(id_unik) {
                    tampilkan_pokin_dari_cascading(id_unik, 'sasaran');
                }
                get_cascading_input_rencana_pagu('program');
            "`;
            // "get_cascading_input_rencana_pagu('program')";
        }else if(id == 'program'){
            id_html = 'cascading-renstra-program';
            label = 'Program';
            onchange = `onchange="
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                if(id_unik) {
                    tampilkan_pokin_dari_cascading(id_unik, 'program');
                }
                get_cascading_input_rencana_pagu('kegiatan')
            "`;
            // "get_cascading_input_rencana_pagu('kegiatan')";
        }else if(id == 'kegiatan'){
            id_html = 'cascading-renstra-kegiatan';
            label = 'Kegiatan';
            onchange = `onchange="
                var selectedOption = jQuery(this).find('option:selected');
                var id_unik = selectedOption.data('id-unik');
                if(id_unik) {
                    tampilkan_pokin_dari_cascading(id_unik, 'kegiatan');
                }
                get_cascading_input_rencana_pagu('sub_kegiatan')
            "`;

            // "get_cascading_input_rencana_pagu('sub_kegiatan')";
        }else if(id == 'sub-kegiatan'){
            id_html = 'cascading-renstra-sub-kegiatan';
            label = 'Sub Kegiatan';
            onchange = `onchange="
            var selectedOption = jQuery(this).find('option:selected');
            var id_unik = selectedOption.data('id-unik');
            if(id_unik) {
                tampilkan_pokin_dari_cascading(id_unik, 'sub_kegiatan');
            }
            get_cascading_input_rencana_pagu()
        "`;
            // "get_cascading_input_rencana_pagu()";
        }
        return `
            <div class="form-group ${_class}" style="${display}"> 
                <label for="${id_html}">Pilih ${label} Cascading</label>
                <select class="form-control" name="${id_html}" id="${id_html}" ${onchange}></select>
            </div>    
        `;
    }

    function tambah_renaksi_2_final(tipe, isEdit = false, cek_parent) {
        let jenis = '';
        let parent_cascading = '';
        let jenis_cascading = '';
        let id_sub_skpd_cascading = 0;

        switch (tipe) {
            case 1:
                jenis = "sasaran";
                jenis_cascading = "Sasaran";
                break;

            case 2:
                jenis = "program";
                jenis_cascading = "Program";
                parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
                id_sub_skpd_cascading = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
                break;

            case 3:
                jenis = "kegiatan";
                jenis_cascading = "Kegiatan";
                parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
                id_sub_skpd_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_sub_skpd');
                break;

            case 4:
                jenis = "sub_kegiatan";
                jenis_cascading = "Sub Kegiatan";
                parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
                id_sub_skpd_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_sub_skpd');
                break;
        }

        return get_tujuan_sasaran_cascading(jenis, parent_cascading, id_sub_skpd_cascading)
        .then(function() {
            return new Promise(function(resolve, reject) {
                var parent_pokin = '';
                var parent_renaksi = '';
                var level_pokin = 0;
                var title = '';
                var key = jenis + '-' + parent_cascading;
                if (id_sub_skpd_cascading != 0) {
                    key = jenis + '-' + parent_cascading + '-' + id_sub_skpd_cascading;
                }
                let data_cascading = [];

                let get_renaksi_pemda = <?php echo json_encode($get_data_pemda); ?>;
                let checklist_renaksi_pemda = '';
                let html_input_sub_keg_cascading = '';
                let html_pokin_input_rencana_pagu = '';
                let html_cascading_turunan = '';
                let html_cascading_turunan_id = '';
                var hide_pagu_level1 = '';
                var hide_cek_parent_global = '';

                if(tipe == 1) {
                    hide_pagu_level1 = 'display: none;';
                    title = 'Kegiatan Utama | RHK Level 1';
                    data_cascading = data_sasaran_cascading[key];
                    level_pokin = 1;
                    html_cascading_turunan_id = 'cascading-renstra';
                    html_cascading_turunan = `onchange="
                        var selectedOption = jQuery(this).find('option:selected');
                        var id_unik = selectedOption.data('id-unik');
                        if(id_unik) {
                            tampilkan_pokin_dari_cascading(id_unik, 'sasaran');
                        }
                        get_cascading_input_rencana_pagu('program');
                    "`;
                    
                    html_pokin_input_rencana_pagu += tampil_pokin(1, true);
                    html_pokin_input_rencana_pagu += tampil_pokin(2, true);
                    html_pokin_input_rencana_pagu += tampil_pokin(3, false);
                    html_pokin_input_rencana_pagu += tampil_pokin(4, false);
                    html_pokin_input_rencana_pagu += tampil_pokin(5, false);

                    html_input_sub_keg_cascading += tampil_cascading('program', false);
                    html_input_sub_keg_cascading += tampil_cascading('kegiatan', false);
                    html_input_sub_keg_cascading += tampil_cascading('sub-kegiatan', false);
                }else if(tipe == 2) {
                    title = 'Rencana Hasil Kerja | RHK Level 2';
                    data_cascading = data_program_cascading[key];
                    parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                    parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                    level_pokin = 3;
                    html_pokin_input_rencana_pagu += tampil_pokin(3, true);
                    html_pokin_input_rencana_pagu += tampil_pokin(4, false);
                    html_pokin_input_rencana_pagu += tampil_pokin(5, false);

                    html_input_sub_keg_cascading += tampil_cascading('kegiatan', false);
                    html_input_sub_keg_cascading += tampil_cascading('sub-kegiatan', false);

                    html_cascading_turunan_id = 'cascading-renstra-program';
                    html_cascading_turunan = `onchange="
                        var selectedOption = jQuery(this).find('option:selected');
                        var id_unik = selectedOption.data('id-unik');
                        if(id_unik) {
                            tampilkan_pokin_dari_cascading(id_unik, 'program');
                        }
                        get_cascading_input_rencana_pagu('kegiatan')
                    "`;
                }else if(tipe == 3) {
                    level_pokin = 4;
                    title = 'Uraian Rencana Hasil Kerja | RHK Level 3';
                    parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                    parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                    data_cascading = data_kegiatan_cascading[key];
                    
                    html_pokin_input_rencana_pagu += tampil_pokin(4, true);
                    html_pokin_input_rencana_pagu += tampil_pokin(5, false);

                    html_input_sub_keg_cascading += tampil_cascading('sub-kegiatan', false);

                    html_cascading_turunan_id = 'cascading-renstra-kegiatan';
                    html_cascading_turunan = `onchange="
                        var selectedOption = jQuery(this).find('option:selected');
                        var id_unik = selectedOption.data('id-unik');
                        if(id_unik) {
                            tampilkan_pokin_dari_cascading(id_unik, 'kegiatan');
                        }
                        get_cascading_input_rencana_pagu('sub_kegiatan')
                    "`;
                    
                } else if(tipe == 4) {
                    level_pokin = 5;
                    title = 'Uraian Teknis Kegiatan | RHK Level 4';
                    parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                    parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                    data_cascading = data_sub_kegiatan_cascading[key];
                    html_pokin_input_rencana_pagu += tampil_pokin(5, true);
                    html_input_sub_keg_cascading = '';
                    html_cascading_turunan_id = 'cascading-renstra-sub-kegiatan';
                    html_cascading_turunan = `onchange="
                        var selectedOption = jQuery(this).find('option:selected');
                        var id_unik = selectedOption.data('id-unik');
                        if(id_unik) {
                            tampilkan_pokin_dari_cascading(id_unik, 'sub_kegiatan');
                        }
                        get_cascading_input_rencana_pagu()
                    "`;
                }

                // menampilkan rhk pemda hanya di level 2 rhk opd
                if (!isEdit && tipe === 2) {
                    checklist_renaksi_pemda += `
                        <label>Rencana Hasil Kerja Pemerintah Daerah</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="select_all"></th>
                                    <th>Sasaran Strategis</th>
                                    <th>Indikator Kinerja</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    get_renaksi_pemda.forEach(function(item, index) {
                        let label_sasaran = (item.id_iku && item.ik_label_sasaran) 
                            ? item.ik_label_sasaran 
                            : (item.label_sasaran || '');

                        let label_indikator = (item.id_iku && item.ik_label_indikator) 
                            ? item.ik_label_indikator 
                            : (item.label_indikator || '');
                        checklist_renaksi_pemda += `
                            <tr>
                                <td><input class="text-right" type="checkbox" class="form-check-input" id="label_renaksi_pemda${index}" name="checklist_renaksi_pemda[]" value="${label_sasaran}" id_label_renaksi_pemda="${item.id_detail}">
                                    </td>
                                <td>${label_sasaran}</td>
                                <td>${label_indikator}</td>
                            </tr>
                        `;
                    });

                    checklist_renaksi_pemda += '</tbody></table>';
                }

                // ketika tipe 1, masih undefined
                if(typeof cek_parent_global == 'undefined'){
                    window.cek_parent_global = {
                        input_pagu: 0
                    };
                }

                let html_setting_input_rencana_pagu = '';
                if(tipe != 4){
                    var hide = 'style="display:none"';

                    // jika parent rhk tidak ada yang input pagu maka checklist input pagu ditampilkan
                    if(cek_parent_global.input_pagu == 0){
                        hide = '';
                    }
                    html_setting_input_rencana_pagu = `
                        <div class="form-group form-check" ${hide}>
                            <label class="form-check-label" for="set_input_rencana_pagu">
                                <input class="form-check-input" type="checkbox" id="set_input_rencana_pagu" name="set_input_rencana_pagu">
                                Pengaturan Input Rencana Pagu
                            </label>
                            <small class="form-text text-muted">
                                Pengaturan Input Rencana Pagu Menjadikan Level Ini Menjadi Level RHK Terakhir.
                            </small>
                        </div>`;
                }

                // sembunyikan pilihan cascading jika parent sudah diset input pagu
                if(cek_parent_global.input_pagu == 1){
                    hide_cek_parent_global = 'display: none;'
                }

                jQuery('#wrap-loading').show();
                
                var get_pegawai = <?php echo json_encode($select_pegawai); ?>;
                jQuery('#wrap-loading').hide();
                jQuery("#modal-crud").find('.modal-title').html('Tambah ' + title);

                jQuery("#modal-crud").find('.modal-body').html(`
                    <form>
                        <input type="hidden" id="id_renaksi" value=""/>
                        ${html_pokin_input_rencana_pagu}
                        <div class="form-group">
                            <textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan ${title}..."></textarea>
                        </div>
                        <div class="form-group" style="${hide_cek_parent_global}">
                            <label for="${html_cascading_turunan_id}">Pilih ${jenis_cascading} Cascading</label>
                            <select class="form-control" name="${html_cascading_turunan_id}" id="${html_cascading_turunan_id}" ${html_cascading_turunan}></select>
                        </div>
                        ${ html_input_sub_keg_cascading }
                        <div class="form-group" style="${hide_pagu_level1} ${hide_cek_parent_global}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="sub-skpd-cascading">OPD Cascading</label>
                                </div>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" id="sub-skpd-cascading" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="${hide_pagu_level1} ${hide_cek_parent_global}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="pagu-cascading">Pagu Cascading</label>
                                </div>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" id="pagu-cascading" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label for="satker_id">Pilih Satuan Kerja</label> 
                                <select class="form-control select2" id="satker_id" name="satker_id"><?php echo $select_satker; ?> 
                                </select> 
                        </div> 
                        <div class="form-group"> 
                            <label for="pegawai">Pilih Pegawai Pelaksana</label> 
                                <select class="form-control select2" id="pegawai" name="pegawai"> ${get_pegawai}
                                </select> 
                        </div> 
                        ${html_setting_input_rencana_pagu}
                        <?php if (!empty($get_data_pemda)): ?>
                            ${checklist_renaksi_pemda}  
                        <?php endif; ?>
                    </form>
                `);
                set_dasar_pelaksanaan();

                jQuery("#modal-crud").find('.modal-footer').html(`
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" onclick="simpan_data_renaksi(${tipe})">Simpan</button>
                `);

                jQuery('#select_all').on('click', function() {
                    var isChecked = this.checked;
                    jQuery('input[name="checklist_renaksi_pemda[]"]').prop('checked', isChecked);
                });

                jQuery('input[name="checklist_renaksi_pemda[]"]').on('change', function() {
                    var allChecked = jQuery('input[name="checklist_renaksi_pemda[]"]').length === jQuery('input[name="checklist_renaksi_pemda[]"]:checked').length;
                    jQuery('#select_all').prop('checked', allChecked);
                });
                jQuery("#modal-crud").modal('show');

                jQuery('#pokin-level-1').select2({
                    width: '100%'
                });
                jQuery('#pokin-level-2').select2({
                    width: '100%'
                });
                jQuery('#pokin-level-3').select2({
                    width: '100%'
                });
                jQuery('#pokin-level-4').select2({
                    width: '100%'
                });
                jQuery('#pokin-level-5').select2({
                    width: '100%'
                });
                jQuery('#satker_id').select2({
                    width: '100%'
                });
                jQuery('#pegawai').select2({
                    width: '100%'
                });
                
                if (data_cascading && Array.isArray(data_cascading.data)) {
                    let html_cascading = '<option value="">Pilih ' + jenis_cascading + ' Cascading</option>';
                    data_cascading.data.map(value => {
                        if (value.id_unik_indikator == null) {
                            switch (tipe) {
                                case 1:
                                    html_cascading += '<option value="' + value.kode_bidang_urusan + '" data-id-unik="' + value.id_unik + '">' + value.sasaran_teks + '</option>';
                                    break;
                                case 2:
                                    let id_unik_program = '';
                                    if (value.get_pokin_renstra && value.get_pokin_renstra.length > 0) {
                                        id_unik_program = value.get_pokin_renstra.map(p => p.id_unik).join(',');
                                    }
                                    html_cascading += `<option value="${value.kode_program}_${value.id_sub_skpd}" data-id-unik="${id_unik_program}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${value.kode_program} ${value.nama_program} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )</option>`;
                                    break;
                                case 3:
                                    let id_unik_kegiatan = '';
                                    if (value.get_pokin_renstra && value.get_pokin_renstra.length > 0) {
                                        id_unik_kegiatan = value.get_pokin_renstra.map(p => p.id_unik).join(',');
                                    }
                                    html_cascading += `<option value="${value.kode_giat}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-id-unik="${id_unik_kegiatan}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${value.kode_giat} ${value.nama_giat} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )</option>`;
                                    break;
                                case 4:
                                    let id_unik_sub_kegiatan = '';
                                    if (value.get_pokin_renstra && value.get_pokin_renstra.length > 0) {
                                        id_unik_sub_kegiatan = value.get_pokin_renstra.map(p => p.id_unik).join(',');
                                    }
                                    let nama_sub_giat = `${value.kode_sub_giat} ${value.nama_sub_giat.replace(value.kode_sub_giat, '')} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )`;
                                    html_cascading += `<option data-kodesbl="${value.kode_sbl}" value="${value.kode_sub_giat}" data-id-unik="${id_unik_sub_kegiatan}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${nama_sub_giat}</option>`;
                                    break;
                            }
                        }
                    });
                    jQuery("#"+html_cascading_turunan_id).html(html_cascading);
                    jQuery('#'+html_cascading_turunan_id).select2({
                        width: '100%'
                    });
                } else {
                    alert("Data Cascading Kosong!");
                }

                resolve();
            });
        });
    }

    function get_cascading_input_rencana_pagu(jenis_cascading = false) {
        return new Promise(function(resolve, reject) {
            var id_parent = '';
            var id_cascading = '';
            var reset_pokin_level = null;
            
            if(jenis_cascading == 'program'){
                id_parent = 'cascading-renstra';
                id_cascading = 'cascading-renstra-program';
                reset_pokin_level = 3;
            }else if(jenis_cascading == 'kegiatan'){
                id_parent = 'cascading-renstra-program';
                id_cascading = 'cascading-renstra-kegiatan';
                reset_pokin_level = 4;
            }else if(jenis_cascading == 'sub_kegiatan'){
                id_parent = 'cascading-renstra-kegiatan';
                id_cascading = 'cascading-renstra-sub-kegiatan';
                reset_pokin_level = 5;
            }else{
                set_view_cascading('cascading-renstra-sub-kegiatan');
                return resolve();
            }
            
            var selectedData = jQuery('#'+id_parent+' option:selected');
            if(selectedData.length == 0){
                if(reset_pokin_level) {
                    reset_pokin_cascading(reset_pokin_level);
                }
                return resolve();
            }

            let parent_cascading = selectedData.val();
            let id_sub_skpd_cascading = selectedData.data('id-sub-skpd-cascading');

            let set_input_rencana_pagu = jQuery("#set_input_rencana_pagu").is(":checked");
            
            if(
                parent_cascading != ''
                && set_input_rencana_pagu == true
            ){
                parent_cascading = parent_cascading.split("_")[0];
                
                if(reset_pokin_level) {
                    reset_pokin_cascading(reset_pokin_level);
                }
                
                return get_tujuan_sasaran_cascading(jenis_cascading, parent_cascading, id_sub_skpd_cascading)
                .then(function() {
                    let key = jenis_cascading + '-' + parent_cascading;
                    if (
                        id_sub_skpd_cascading != 0 
                        && id_sub_skpd_cascading != undefined
                        && id_sub_skpd_cascading != ''
                    ) {
                        key = jenis_cascading + '-' + parent_cascading + '-' + id_sub_skpd_cascading;
                    }

                    let data_cascading = [];
                    let nama_jenis = '';
                    if(jenis_cascading == 'program'){
                        data_cascading = data_program_cascading[key];
                        nama_jenis = 'Program';
                    }else if(jenis_cascading == 'kegiatan'){
                        data_cascading = data_kegiatan_cascading[key];
                        nama_jenis = 'Kegiatan';
                    }else if(jenis_cascading == 'sub_kegiatan'){
                        data_cascading = data_sub_kegiatan_cascading[key];
                        nama_jenis = 'Sub Kegiatan';
                    }

                    let html_cascading = `<option value="">Pilih ${nama_jenis} Cascading</option>`;
                    if (
                        data_cascading 
                        && Array.isArray(data_cascading.data)
                    ) {
                        data_cascading.data.map(value => {
                            if (value.id_unik_indikator == null) {
                                switch (jenis_cascading) {
                                    case 'program':
                                        let id_unik_program = '';
                                        if (value.get_pokin_renstra && value.get_pokin_renstra.length > 0) {
                                            id_unik_program = value.get_pokin_renstra.map(p => p.id_unik).join(',');
                                        }
                                        html_cascading += `<option value="${value.kode_program}_${value.id_sub_skpd}" data-id-unik="${id_unik_program}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${value.kode_program} ${value.nama_program} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )</option>`;
                                        break;
                                    case 'kegiatan':
                                        let id_unik_kegiatan = '';
                                        if (value.get_pokin_renstra && value.get_pokin_renstra.length > 0) {
                                            id_unik_kegiatan = value.get_pokin_renstra.map(p => p.id_unik).join(',');
                                        }
                                        html_cascading += `<option value="${value.kode_giat}" data-id-unik="${id_unik_kegiatan}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${value.kode_giat} ${value.nama_giat} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )</option>`;
                                        break;
                                    default:
                                        let id_unik_sub_kegiatan = '';
                                        if (value.get_pokin_renstra && value.get_pokin_renstra.length > 0) {
                                            id_unik_sub_kegiatan = value.get_pokin_renstra.map(p => p.id_unik).join(',');
                                        }
                                        let nama_sub_giat = `${value.kode_sub_giat} ${value.nama_sub_giat.replace(value.kode_sub_giat, '')} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )`;
                                        html_cascading += `<option data-kodesbl="${value.kode_sbl}" value="${value.kode_sub_giat}" data-id-unik="${id_unik_sub_kegiatan}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${nama_sub_giat}</option>`;
                                        break;
                                }
                            }
                        });
                        jQuery("#"+id_cascading).html(html_cascading)
                            .select2({
                                width: '100%'
                            });
                    } else {
                        alert("Data Cascading Kosong!");
                    }
                    jQuery('#wrap-loading').hide();

                    console.log('set_view_cascading(id_cascading)', id_cascading);
                    set_view_cascading(id_cascading);
                    return resolve();
                });
            }else{
                console.log('set_view_cascading(id_parent)', id_parent);
                set_view_cascading(id_parent);
                return resolve();
            }
        });
    }

    function set_view_cascading(id) {
        if(jQuery('#set_input_rencana_pagu').is(':checked')){
            switch (id) {
                case 'cascading-renstra-program':
                    jQuery('#cascading-renstra-kegiatan').html('').trigger('change');
                    break;
                case 'cascading-renstra-kegiatan':
                    jQuery('#cascading-renstra-sub-kegiatan').html('').trigger('change');
                    break;
            }

            var nama_sub_skpd_cascading = jQuery('#cascading-renstra-sub-kegiatan option:selected').data('nama-sub-skpd');
            var pagu_cascading = jQuery('#cascading-renstra-sub-kegiatan option:selected').data('pagu-cascading');
        }else{
            // untuk level 1 tidak perlu setting opd dan pagu
            if(id == 'cascading-renstra'){
                return;
            }
            var nama_sub_skpd_cascading = jQuery('#'+id).find('option:selected').data('nama-sub-skpd');
            var pagu_cascading = jQuery('#'+id).find('option:selected').data('pagu-cascading');
        }
        console.log('nama_sub_skpd_cascading', nama_sub_skpd_cascading, 'id', id, 'pagu_cascading', pagu_cascading);

        if (nama_sub_skpd_cascading) {
            jQuery('#sub-skpd-cascading').val(nama_sub_skpd_cascading)
        }else{
            jQuery('#sub-skpd-cascading').val('');
        }
        if (pagu_cascading) {
            jQuery('#pagu-cascading').val(formatRupiah(pagu_cascading))
        }else{
            jQuery('#pagu-cascading').val('');
        }
    };

    function simpan_data_renaksi(tipe) {
        var parent_pokin = 0;
        var parent_renaksi = 0;
        var parent_cascading = 0;
        var parent_sub_skpd = 0;
        var kode_cascading_renstra = '';
        var label_cascading_renstra = '';
        switch (tipe) {
            case 1:
                kode_cascading_renstra = jQuery('#cascading-renstra').val();
                label_cascading_renstra = jQuery('#cascading-renstra option:selected').text();
                break;
            case 2:
                kode_cascading_renstra = jQuery('#cascading-renstra-program').val();
                if (kode_cascading_renstra != '') {
                    kode_sementara = kode_cascading_renstra.split("_");
                    kode_cascading_renstra = kode_sementara[0];

                    label_cascading_renstra = jQuery('#cascading-renstra-program option:selected').text();
                    let new_label = label_cascading_renstra.split('(');
                    if (Array.isArray(new_label)) {
                        label_cascading_renstra = new_label[0].trim();
                    }
                }

                parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
                break;
            case 3:
                kode_cascading_renstra = jQuery('#cascading-renstra-kegiatan').val();
                if (kode_cascading_renstra != '') {
                    kode_sementara = kode_cascading_renstra.split("_");
                    kode_cascading_renstra = kode_sementara[0];
                    
                    label_cascading_renstra = jQuery('#cascading-renstra-kegiatan option:selected').text();
                    let new_label = label_cascading_renstra.split('(');
                    if (Array.isArray(new_label)) {
                        label_cascading_renstra = new_label[0].trim();
                    }
                }

                parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
                parent_sub_skpd = jQuery('#tabel_uraian_rencana_aksi').attr('parent_sub_skpd');
                break;
            case 4:
                kode_cascading_renstra = jQuery('#cascading-renstra-sub-kegiatan').val();
                if (kode_cascading_renstra != '') {
                    kode_sementara = kode_cascading_renstra.split("_");
                    kode_cascading_renstra = kode_sementara[0];
                    
                    label_cascading_renstra = jQuery('#cascading-renstra-sub-kegiatan option:selected').text();
                    let new_label = label_cascading_renstra.split('(');
                    if (Array.isArray(new_label)) {
                        label_cascading_renstra = new_label[0].trim();
                    }
                }

                parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
                parent_sub_skpd = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_sub_skpd');
                break;
        }

        var id_pokin_1 = jQuery('#pokin-level-1').val();
        var id_pokin_2 = jQuery('#pokin-level-2').val();
        var label_pokin_1 = jQuery('#pokin-level-1 option:selected').text();
        var label_pokin_2 = jQuery('#pokin-level-2 option:selected').text();
        var label_renaksi = jQuery('#label_renaksi').val();
        var setting_input_rencana_pagu = jQuery('#set_input_rencana_pagu').is(':checked') ? 1 : 0;
        
        var id_sub_skpd_cascading = 0;
        var pagu_cascading = 0;
        if (tipe == 2) {
            id_pokin_1 = jQuery('#pokin-level-3').val();
            label_pokin_1 = jQuery('#pokin-level-3 option:selected').text();
            id_sub_skpd_cascading = jQuery('#cascading-renstra-program option:selected').data('id-sub-skpd-cascading');
            pagu_cascading = jQuery('#cascading-renstra-program option:selected').data('pagu-cascading');
        }else if (tipe == 3) {
            id_pokin_1 = jQuery('#pokin-level-4').val();
            label_pokin_1 = jQuery('#pokin-level-4 option:selected').text();
            id_sub_skpd_cascading = jQuery('#cascading-renstra-kegiatan option:selected').data('id-sub-skpd-cascading');
            pagu_cascading = jQuery('#cascading-renstra-kegiatan option:selected').data('pagu-cascading');
        }else if (tipe == 4) {
            id_pokin_1 = jQuery('#pokin-level-5').val();
            label_pokin_1 = jQuery('#pokin-level-5 option:selected').text();
            id_sub_skpd_cascading = jQuery('#cascading-renstra-sub-kegiatan option:selected').data('id-sub-skpd-cascading');
            pagu_cascading = jQuery('#cascading-renstra-sub-kegiatan option:selected').data('pagu-cascading');
        }
        var kode_sbl = jQuery('#cascading-renstra-sub-kegiatan option:selected').data('kodesbl');

        var id_pokin_3 = jQuery('#pokin-level-3').val();
        var label_pokin_3 = jQuery('#pokin-level-3 option:selected').text();
        var id_pokin_4 = jQuery('#pokin-level-4').val();
        var label_pokin_4 = jQuery('#pokin-level-4 option:selected').text();
        var id_pokin_5 = jQuery('#pokin-level-5').val();
        var label_pokin_5 = jQuery('#pokin-level-5 option:selected').text();

        var kode_cascading_renstra_program = jQuery('#cascading-renstra-program').val();
        var label_cascading_renstra_program = jQuery('#cascading-renstra-program option:selected').text();
        if (kode_cascading_renstra_program) {
            var kode_cascading_renstra_program_1 = kode_cascading_renstra_program.split("_");
            kode_cascading_renstra_program = kode_cascading_renstra_program_1[0];
            let new_label = label_cascading_renstra_program.split('(');
            if (Array.isArray(new_label)) {
                label_cascading_renstra_program = new_label[0].trim();
            }
        }

        var kode_cascading_renstra_kegiatan = jQuery('#cascading-renstra-kegiatan').val();
        var label_cascading_renstra_kegiatan = jQuery('#cascading-renstra-kegiatan option:selected').text();
        if (kode_cascading_renstra_kegiatan) {
            let new_label = label_cascading_renstra_kegiatan.split('(');
            if (Array.isArray(new_label)) {
                label_cascading_renstra_kegiatan = new_label[0].trim();
            }
        }

        var kode_cascading_renstra_sub_kegiatan = jQuery('#cascading-renstra-sub-kegiatan').val();
        var label_cascading_renstra_sub_kegiatan = jQuery('#cascading-renstra-sub-kegiatan option:selected').text();
        if (kode_cascading_renstra_sub_kegiatan) {
            let new_label = label_cascading_renstra_sub_kegiatan.split('(');
            if (Array.isArray(new_label)) {
                label_cascading_renstra_sub_kegiatan = new_label[0].trim();
            }
        }

        if (tipe == 1) {
            if (label_renaksi == '') {
                return alert('Kegiatan Utama tidak boleh kosong!');
            }
        } else if (tipe == 2) {
            if (label_renaksi == '') {
                return alert('Rencana Hasil Kerja tidak boleh kosong!');
            }
        } else if (tipe == 3) {
            if (label_renaksi == '') {
                return alert('Uraian Kegiatan tidak boleh kosong!');
            }
        } else if (tipe == 4) {
            if (label_renaksi == '') {
                return alert('Uraian Teknis Kegiatan tidak boleh kosong!');
            }
        }
        // if (id_pokin_1 == '' && tipe == 1) {
        //     return alert('Level 1 pohon kinerja tidak boleh kosong!');
        // } else if (id_pokin_2 == '' && tipe == 1) {
        //     return alert('Level 2 pohon kinerja tidak boleh kosong!');
        // } else if (id_pokin_1 == '' && tipe == 2) {
        //     return alert('Level 3 pohon kinerja tidak boleh kosong!');
        // } else if (id_pokin_1 == '' && tipe == 3) {
        //     return alert('Level 4 pohon kinerja tidak boleh kosong!');
        // } else if (id_pokin_1 == '' && tipe == 4) {
        //     return alert('Level 5 pohon kinerja tidak boleh kosong!');
        // }

        var selectedChecklistPemda = jQuery('input[name="checklist_renaksi_pemda[]"]:checked');
        var checklistDataPemda = [];
        selectedChecklistPemda.each(function() {
            var id = jQuery(this).attr('id_label_renaksi_pemda');
            checklistDataPemda.push({
                id_data_renaksi_pemda: id
            });
        });
        console.log(checklistDataPemda); // Debug untuk melihat isinya
        if (checklistDataPemda.length > 0) {
            console.log('First ID:', checklistDataPemda[0].id_data_renaksi_pemda); // Bukan [id_data_renaksi_pemda]
        }


        // if (tipe === 2 && selectedChecklistPemda.length === 0) {
        //     return alert("Pilih salah satu label Rencana Hasil Kerja Pemerintah Daerah!");
        // }
        var get_dasar_pelaksanaan = {
            mandatori_pusat: jQuery('#mandatori_pusat').is(':checked') ? 1 : 0,
            inisiatif_kd: jQuery('#inisiatif_kd').is(':checked') ? 1 : 0,
            musrembang: jQuery('#musrembang').is(':checked') ? 1 : 0,
            pokir: jQuery('#pokir').is(':checked') ? 1 : 0
        };
        // if (Object.values(get_dasar_pelaksanaan).every(value => value === 0) && tipe === 4) {
        //     return alert('Pilih salah satu dasar pelaksanaan!');
        // }

        let satker_id = jQuery('#satker_id').val();
        var id_jabatan_asli = '';
        let nip = jQuery('#pegawai').val();
        if(nip){
            id_jabatan_asli = nip.split('-')[2];
            nip = nip.split('-')[0];
        } 
        let satker_id_pegawai = jQuery('#pegawai option:selected').attr('satker-id'); // Mengambil atribut satker-id dari option yang dipilih

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                "action": 'create_renaksi',
                "api_key": esakip.api_key,
                "tipe_pokin": "opd",
                "id": jQuery('#id_renaksi').val(),
                "id_pokin_1": id_pokin_1,
                "label_pokin_1": label_pokin_1,
                "id_pokin_2": id_pokin_2,
                "label_pokin_2": label_pokin_2,
                "label_renaksi": label_renaksi,
                "level": tipe,
                "parent": parent_renaksi,
                "tahun_anggaran": <?php echo $input['tahun']; ?>,
                "id_jadwal": id_jadwal,
                "id_skpd": <?php echo $id_skpd; ?>,
                "kode_cascading_renstra": kode_cascading_renstra,
                "label_cascading_renstra": label_cascading_renstra,
                "kode_sbl": kode_sbl,
                "checklistDataPemda": checklistDataPemda,
                "get_dasar_pelaksanaan": get_dasar_pelaksanaan,
                "nip": nip,
                "satker_id": satker_id,
                "satker_id_pegawai": satker_id_pegawai,
                "id_jabatan_asli": id_jabatan_asli,
                "id_sub_skpd_cascading": id_sub_skpd_cascading,
                "pagu_cascading": pagu_cascading,
                "setting_input_rencana_pagu": setting_input_rencana_pagu,
                "kode_cascading_renstra_program": kode_cascading_renstra_program,
                "label_cascading_renstra_program": label_cascading_renstra_program,
                "kode_cascading_renstra_kegiatan": kode_cascading_renstra_kegiatan,
                "label_cascading_renstra_kegiatan": label_cascading_renstra_kegiatan,
                "kode_cascading_renstra_sub_kegiatan": kode_cascading_renstra_sub_kegiatan,
                "label_cascading_renstra_sub_kegiatan": label_cascading_renstra_sub_kegiatan,
                "id_pokin_3": id_pokin_3,
                "label_pokin_3": label_pokin_3,
                "id_pokin_4": id_pokin_4,
                "label_pokin_4": label_pokin_4,
                "id_pokin_5": id_pokin_5,
                "label_pokin_5": label_pokin_5,
            },
            dataType: "json",
            success: function(res) {
                jQuery('#wrap-loading').hide();
                alert(res.message);
                if (res.status == 'success') {
                    jQuery("#modal-crud").modal('hide');
                    lihat_rencana_aksi(parent_renaksi, tipe, parent_pokin, parent_cascading, parent_sub_skpd);
                }
            }
        });
    }

    function check_all_rekening() {
        const allChecked = jQuery('.input_checkbox_rekening:checked').length === jQuery('.input_checkbox_rekening').length;

        // Toggle all checkboxes
        jQuery('.input_checkbox_rekening').prop('checked', !allChecked);
    }

    function get_data_rekening_akun_wp_sipd(kode_sbl = '0') {
        return new Promise(function(resolve, reject) {
            if (typeof data_rekening_akun == 'undefined') {
                window.data_rekening_akun = {};
            }
            if (typeof data_rekening_akun[kode_sbl] == 'undefined') {
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": 'get_data_rekening_akun_wp_sipd',
                        "api_key": esakip.api_key,
                        "id_skpd": <?php echo $id_skpd; ?>,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "kode_sbl": kode_sbl
                    },
                    dataType: "json",
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        if (response.status == 'success') {
                            data_rekening_akun[kode_sbl] = response;
                        } else {
                            alert("Error get data dari DPA, " + response.message);
                        }
                        resolve();
                    }
                });
            } else {
                resolve();
            }
        });
    }

    function get_data_rincian_belanja(kode_akun, kode_sbl) {
        let val_check = jQuery("input[name='jenis_tagging']:checked").val()
        // form_input_rka_dpa
        if (val_check == 'rka_dpa') {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_rincian_belanja",
                    "api_key": esakip.api_key,
                    "tahun_anggaran": <?php echo $input['tahun']; ?>,
                    "id_skpd": <?php echo $id_skpd; ?>,
                    "kode_sbl": kode_sbl,
                    "kode_akun": kode_akun
                },
                dataType: "json",
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    // menampilkan popup
                    if (response.status == 'success') {
                        let html_rincian_belanja = '';
                        let no = 0;
                        html_rincian_belanja += `` +
                            `<table class="table" style="margin: 0 0 2rem;">` +
                            `<thead>` +
                            `<tr class="table-secondary">` +
                            `<th class="text-center" style="width:20px">` +
                            `<div class="form-check">` +
                            `<input class="form-check-input" type="checkbox" name="input_rincian_belanja_all" id="rincian-belanja-all">` +
                            `</div>` +
                            `</th>` +
                            `<th class="text-center">Uraian Rka</th>` +
                            `<th class="text-center">Volume Satuan</th>` +
                            `<th class="text-center">Nilai Rka</th>` +
                            `<th class="text-center">Uraian Tagging</th>` +
                            `<th class="text-center">Volume Satuan Tagging</th>` +
                            `<th class="text-center">Nilai Tagging</th>` +
                            `</tr>` +
                            `</thead>` +
                            `<tbody>`;
                        response.data.map(function(b, i) {
                            number = no++;
                            html_rincian_belanja += `` +
                                `<tr>` +
                                `<td>` +
                                `<div class="form-check">` +
                                `<input class="form-check-input" type="checkbox" name="input_rincian_belanja" value="${b.id_rinci_sub_bl}" id="rincian-belanja-${number}" number="${number}" onchange="copy_rincian(${number});">` +
                                `</div>` +
                                `</td>` +
                                `<td>` +
                                `<div class="form-group">` +
                                `<textarea class="form-control" rows="2" cols="50" id="uraian-rka-${number}" name="uraian_rka" required disabled>` +
                                `${b.nama_komponen} ${b.spek_komponen}` +
                                `</textarea>` +
                                `</div>` +
                                `</td>` +
                                `<td>` +
                                `<div class="form-group">` +
                                `<input type="text" class="form-control" id="volume-satuan-rka-${number}" name="volume_satuan_rka" value="${b.koefisien}" required disabled>` +
                                `</div>` +
                                `</td>` +
                                `<td>` +
                                `<div class="form-group">` +
                                `<input type="text" class="form-control" id="nilai-rka-${number}" name="nilai_rka" value="${parseFloat(b.rincian)}" required disabled>` +
                                `</div>` +
                                `</td>` +
                                `<td>` +
                                `<div class="form-group">` +
                                `<textarea rows="2" cols="50" id="uraian-tagging-${number}" name="uraian_tagging" required></textarea>` +
                                `</div>` +
                                `</td>` +
                                `<td>` +
                                `<div class="form-group">` +
                                `<input type="text" class="form-control" id="volume-satuan-tagging-${number}" name="volume_satuan_tagging" required>` +
                                `</div>` +
                                `</td>` +
                                `<td>` +
                                `<div class="form-group">` +
                                `<input type="text" class="form-control" id="nilai-tagging-${number}" name="nilai_tagging" required>` +
                                `</div>` +
                                `</td>` +
                                `</tr>`;
                        });
                        html_rincian_belanja += `` +
                            `</tbody>` +
                            `</table>`
                        jQuery('#form_input_rka_dpa').html(html_rincian_belanja);
                    }
                }
            });
        }
    }

    function simpan_rincian_belanja_renaksi(id_indikator, id_uraian_teknis_kegiatan, kode_sbl) {
        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            let form = getFormData(jQuery("#form-tagging-renaksi"));
            try {
                if (Object.keys(form).length === 0) {
                    alert("Inputan Kosong!")
                    jQuery('#wrap-loading').hide();
                    throw new Error("Inputan Kosong!");
                    return;
                }
                Object.entries(form).forEach(([key, value]) => {
                    Object.entries(value).forEach(([k_rincian, v_rincian]) => {
                        if (v_rincian === undefined || v_rincian === "") {
                            alert("Pastikan semua inputan terisi!")
                            jQuery('#wrap-loading').hide();
                            throw new Error("Harap Semua Inputan Yang Terchecklist Terisi!");
                            return;
                        }
                    })
                })

                jQuery.ajax({
                    url: esakip.url,
                    type: "post",
                    data: {
                        "action": "crate_tagging_rincian_belanja",
                        "api_key": esakip.api_key,
                        "tahun_anggaran": <?php echo $input['tahun']; ?>,
                        "id_skpd": <?php echo $id_skpd; ?>,
                        "id_indikator_teknis_kegiatan": id_indikator,
                        "id_uraian_teknis_kegiatan": id_uraian_teknis_kegiatan,
                        "kode_sbl": kode_sbl,
                        "data": JSON.stringify(form)
                    },
                    dataType: "json",
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        if (response.status == 'success') {
                            alert(response.message)
                            jQuery("#modal-crud").modal('hide');
                        } else {
                            alert(response.message)
                        }
                    }
                });
            } catch (error) {
                console.error(error.message);
            }
        }
    }

    function getFormData($form) {
        let unindexed_array = $form.serializeArray();
        var data = {};
        let terceklist = [];
        let jenis_tagging = '';
        unindexed_array.map(function(b, i) {
            if (b.name === 'jenis_tagging') {
                jenis_tagging = b.value
            }
            let nama_baru = b.name.split('_');
            let number = nama_baru[nama_baru.length - 1];
            nama_baru.pop(); // Remove the last element
            nama_baru = nama_baru.join('_');
            if (nama_baru === 'input_rekening_belanja') {
                terceklist.push(number);
            }

            if (terceklist.includes(number)) {
                if (!data[number]) {
                    data[number] = {};
                }
                data[number][nama_baru] = b.value;
                data[number]['jenis_tagging'] = jenis_tagging;
            }
        })
        console.log('data', data);
        return data;
    }

    function copy_rincian(that) {
        if (jQuery("#rincian-belanja-" + that).is(':checked')) {
            uraian_rka = jQuery("#uraian-rka-" + that).val();
            jQuery("#uraian-tagging-" + that).val(uraian_rka);
            volume_satuan_rka = jQuery("#volume-satuan-rka-" + that).val();
            jQuery("#volume-satuan-tagging-" + that).val(volume_satuan_rka);
            nilai_rka = jQuery("#nilai-rka-" + that).val();
            jQuery("#nilai-tagging-" + that).val(nilai_rka);
        } else {
            jQuery("#uraian-tagging-" + that).val("");
            jQuery("#volume-satuan-tagging-" + that).val("");
            jQuery("#nilai-tagging-" + that).val("");
        }
    }

    function set_jenis_tagging(jenis_tagging) {
        let val_check = jQuery("input[name='jenis_tagging']:checked").val()
        if (val_check == 'rka_dpa') {
            jQuery(".set_rka_dpa").show();
            jQuery(".set_manual").hide();
        } else {
            jQuery(".set_rka_dpa").hide();
            jQuery(".set_manual").show();
        }
    }

    function simpan_bulanan(id, bulan) {
        var volume = jQuery(`input[name="volume_${id}_${bulan}"]`).val();
        var rencana_aksi = jQuery(`textarea[name="rencana_aksi_${id}_${bulan}"]`).val();
        var satuan_bulan = jQuery(`input[name="satuan_bulan_${id}_${bulan}"]`).val();
        var realisasi = jQuery(`input[name="realisasi_${id}_${bulan}"]`).val();
        var capaian = jQuery(`input[name="capaian_${id}_${bulan}"]`).val();
        var keterangan = jQuery(`textarea[name="keterangan_${id}_${bulan}"]`).val();

        // jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: 'simpan_bulanan_renaksi_opd',
                api_key: esakip.api_key,
                id_indikator_renaksi_opd: id,
                bulan: bulan,
                volume: volume,
                rencana_aksi: rencana_aksi,
                satuan_bulan: satuan_bulan,
                realisasi: realisasi,
                capaian: capaian,
                keterangan: keterangan,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: "json",
            success: function(res) {
                // jQuery('#wrap-loading').hide();
                alert(res.message);
            }
        });
    }

    function simpan_triwulan(id, triwulan) {
        const realisasi = jQuery(`#realisasi_${id}_tw_${triwulan}`).val();
        const keterangan = jQuery(`#keterangan_${id}_tw_${triwulan}`).val();
        // jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: 'simpan_triwulan_renaksi_opd',
                api_key: esakip.api_key,
                id: id,
                triwulan: triwulan,
                realisasi: realisasi,
                keterangan: keterangan,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: "json",
            success: function(res) {
                // jQuery('#wrap-loading').hide();
                alert(res.message);
            }
        });
    }

    function simpan_total(id) {
        var realisasi_akhir = jQuery(`input[name="realisasi_akhir_${id}"]`).val();
        var ket_total = jQuery(`textarea[name="ket_total_${id}"]`).val();

        // jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: 'simpan_total_bulanan',
                api_key: esakip.api_key,
                id: id,
                realisasi_akhir: realisasi_akhir,
                ket_total: ket_total,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: "json",
            success: function(res) {
                // jQuery('#wrap-loading').hide();
                alert(res.message);
            }
        });
    }

    function help_rhk(id, tipe) {
        jQuery("#wrap-loading").show();
        if (id == undefined) {
            alert("Id tidak ditemukan");
            return;
        }

        jQuery.ajax({
            method: 'POST',
            url: esakip.url,
            data: {
                action: "help_rhk",
                api_key: esakip.api_key,
                id: id,
                tipe: tipe
            },
            dataType: 'json',
            success: function(response) {
                jQuery("#wrap-loading").hide();
                if (response.status) {
                    jQuery("#modal-detail-renaksi").modal('show');

                    var satker = response.get_satker && response.get_satker.satker_id && response.get_satker.nama ?
                        response.get_satker.satker_id + ' ' + response.get_satker.nama : '';
                    var pegawai = response.get_pegawai && response.get_pegawai.nip_baru && response.get_pegawai.nama_pegawai ?
                        response.get_pegawai.nip_baru + ' ' + response.get_pegawai.nama_pegawai : '';
                    var get_cascading_sasaran = response.data && response.data.kode_cascading_sasaran && response.data.label_cascading_sasaran ? response.data.kode_cascading_sasaran + ' ' + response.data.label_cascading_sasaran :
                        '';
                    var get_cascading_program = response.data && response.data.kode_cascading_program && response.data.label_cascading_program ? response.data.kode_cascading_program + ' ' + response.data.label_cascading_program :
                        '';
                    var get_cascading_kegiatan = response.data && response.data.kode_cascading_kegiatan && response.data.label_cascading_kegiatan ? response.data.kode_cascading_kegiatan + ' ' + response.data.label_cascading_kegiatan :
                        '';
                    var get_cascading_sub_kegiatan = response.data && response.data.kode_cascading_sub_kegiatan && response.data.label_cascading_sub_kegiatan ? response.data.kode_cascading_sub_kegiatan + ' ' + response.data.label_cascading_sub_kegiatan :
                        '';

                    if (tipe === 1) {
                        jQuery('label[for="detail_kegiatan_utama"]').show();
                        jQuery('#detail_kegiatan_utama').val(response.data.label).show();
                        jQuery('label[for="detail_rhk"]').hide();
                        jQuery('#detail_rhk').hide();
                        jQuery('label[for="detail_uraian_kegiatan"]').hide();
                        jQuery('#detail_uraian_kegiatan').hide();
                        jQuery('label[for="detail_uraian_tk"]').hide();
                        jQuery('#detail_uraian_tk').hide();
                        jQuery('label[for="detail_pokin_2"]').show();
                        var pokin_1 = response.get_pokin_2.map(pokin => pokin.pokin_label).join(" - ");
                        jQuery('#detail_pokin_2').val(pokin_1).show();
                        jQuery('label[for="detail_pokin_2"]').show();
                        var pokin_2 = response.get_pokin_2.map(pokin => pokin.pokin_label).join(" - ");
                        jQuery('#detail_pokin_2').val(pokin_2).show();
                        jQuery('label[for="detail_pokin_3"]').hide();
                        jQuery('#detail_pokin_3').hide();
                        jQuery('label[for="detail_pokin_4"]').hide();
                        jQuery('#detail_pokin_4').hide();
                        jQuery('label[for="detail_pokin_5"]').hide();
                        jQuery('#detail_pokin_5').hide();
                        jQuery('label[for="detail_cascading_sasaran"]').show();
                        jQuery('#detail_cascading_sasaran').val(get_cascading_sasaran).show();
                        jQuery('label[for="detail_cascading_program"]').hide();
                        jQuery('#detail_cascading_program').hide();
                        jQuery('label[for="detail_cascading_kegiatan"]').hide();
                        jQuery('#detail_cascading_kegiatan').hide();
                        jQuery('label[for="detail_cascading_sub_giat"]').hide();
                        jQuery('#detail_cascading_sub_giat').hide();
                        jQuery('#detail_satuan_kerja').val(satker).show();
                        jQuery('#detail_pegawai').val(pegawai).show();
                    } else if (tipe === 2) {
                        jQuery('label[for="detail_kegiatan_utama"]').hide();
                        jQuery('#detail_kegiatan_utama').hide();
                        jQuery('label[for="detail_rhk"]').show();
                        jQuery('#detail_rhk').val(response.data.label).show();
                        jQuery('label[for="detail_uraian_kegiatan"]').hide();
                        jQuery('#detail_uraian_kegiatan').hide();
                        jQuery('label[for="detail_uraian_tk"]').hide();
                        jQuery('#detail_uraian_tk').hide();
                        jQuery('label[for="detail_pokin_1"]').hide();
                        jQuery('#detail_pokin_1').hide();
                        jQuery('label[for="detail_pokin_2"]').hide();
                        jQuery('#detail_pokin_2').hide();
                        jQuery('label[for="detail_pokin_3"]').show();
                        var pokin_3 = response.get_pokin_3.map(pokin => pokin.pokin_label).join(" - ");
                        jQuery('#detail_pokin_3').val(pokin_3).show();
                        jQuery('label[for="detail_pokin_4"]').hide();
                        jQuery('#detail_pokin_4').hide();
                        jQuery('label[for="detail_pokin_5"]').hide();
                        jQuery('#detail_pokin_5').hide();
                        jQuery('label[for="detail_cascading_sasaran"]').hide();
                        jQuery('#detail_cascading_sasaran').hide();
                        jQuery('label[for="detail_cascading_program"]').show();
                        jQuery('#detail_cascading_program').val(get_cascading_program).show();
                        jQuery('label[for="detail_cascading_kegiatan"]').hide();
                        jQuery('#detail_cascading_kegiatan').hide();
                        jQuery('label[for="detail_cascading_sub_giat"]').hide();
                        jQuery('#detail_cascading_sub_giat').hide();
                        jQuery('#detail_satuan_kerja').val(satker).show();
                        jQuery('#detail_pegawai').val(pegawai).show();
                    } else if (tipe === 3) {
                        jQuery('label[for="detail_kegiatan_utama"]').hide();
                        jQuery('#detail_kegiatan_utama').hide();
                        jQuery('label[for="detail_rhk"]').hide();
                        jQuery('#detail_rhk').hide();
                        jQuery('label[for="detail_uraian_kegiatan"]').show();
                        jQuery('#detail_uraian_kegiatan').val(response.data.label).show();
                        jQuery('label[for="detail_uraian_tk"]').hide();
                        jQuery('#detail_uraian_tk').hide();
                        jQuery('label[for="detail_pokin_1"]').hide();
                        jQuery('#detail_pokin_1').hide();
                        jQuery('label[for="detail_pokin_2"]').hide();
                        jQuery('#detail_pokin_2').hide();
                        jQuery('label[for="detail_pokin_3"]').hide();
                        jQuery('#detail_pokin_3').hide();
                        jQuery('label[for="detail_pokin_4"]').show();
                        var pokin_4 = response.get_pokin_4.map(pokin => pokin.pokin_label).join(" - ");
                        jQuery('#detail_pokin_4').val(pokin_4).show();
                        jQuery('label[for="detail_pokin_5"]').hide();
                        jQuery('#detail_pokin_5').hide();
                        jQuery('label[for="detail_cascading_sasaran"]').hide();
                        jQuery('#detail_cascading_sasaran').hide();
                        jQuery('label[for="detail_cascading_program"]').hide();
                        jQuery('#detail_cascading_program').hide();
                        jQuery('label[for="detail_cascading_kegiatan"]').show();
                        jQuery('#detail_cascading_kegiatan').val(get_cascading_kegiatan).show();
                        jQuery('label[for="detail_cascading_sub_giat"]').hide();
                        jQuery('#detail_cascading_sub_giat').hide();
                        jQuery('#detail_satuan_kerja').val(satker).show();
                        jQuery('#detail_pegawai').val(pegawai).show();
                    } else if (tipe === 4) {
                        jQuery('label[for="detail_kegiatan_utama"]').hide();
                        jQuery('#detail_kegiatan_utama').hide();
                        jQuery('label[for="detail_rhk"]').hide();
                        jQuery('#detail_rhk').hide();
                        jQuery('label[for="detail_uraian_kegiatan"]').hide();
                        jQuery('#detail_uraian_kegiatan').hide();
                        jQuery('label[for="detail_uraian_tk"]').show();
                        jQuery('#detail_uraian_tk').val(response.data.label).show();
                        jQuery('label[for="detail_pokin_1"]').hide();
                        jQuery('#detail_pokin_1').hide();
                        jQuery('label[for="detail_pokin_2"]').hide();
                        jQuery('#detail_pokin_2').hide();
                        jQuery('label[for="detail_pokin_3"]').hide();
                        jQuery('#detail_pokin_3').hide();
                        jQuery('label[for="detail_pokin_4"]').hide();
                        jQuery('#detail_pokin_4').hide();
                        jQuery('label[for="detail_pokin_5"]').show();
                        var pokin_5 = response.get_pokin_5.map(pokin => pokin.pokin_label).join(" - ");
                        jQuery('#detail_pokin_5').val(pokin_5).show();
                        jQuery('label[for="detail_cascading_sasaran"]').hide();
                        jQuery('#detail_cascading_sasaran').hide();
                        jQuery('label[for="detail_cascading_program"]').hide();
                        jQuery('#detail_cascading_program').hide();
                        jQuery('label[for="detail_cascading_kegiatan"]').hide();
                        jQuery('#detail_cascading_kegiatan').hide();
                        jQuery('label[for="detail_cascading_sub_giat"]').show();
                        jQuery('#detail_cascading_sub_giat').val(get_cascading_sub_kegiatan).show();
                        jQuery('#detail_satuan_kerja').val(satker).show();
                        jQuery('#detail_pegawai').val(pegawai).show();
                    }
                } else {
                    alert("Gagal memuat data");
                }
            }
        });
    };

    function get_data_target_realisasi_bulanan() {
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_data_target_bulanan_ekinerja',
                api_key: esakip.api_key,
                tahun: <?php echo $tahun; ?>,
                satker_id: <?php echo $satker_id_pegawai_indikator_string; ?>,
		        id_skpd: <?php echo $id_skpd; ?>,
		        tipe: 'satker'
            },
            success: function(response) {
                if (response.status == 'success') {
                    if(response.show_alert_bulanan == 1 && response.message != '-'){
                        alert(response.message);
                    }
                    console.log(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('Gagal mengambil data:', error);
                alert('Terjadi kesalahan saat mengambil data target realisasi bulanan!.');
            }
        });
    }

    function getTableIndividu() {
        jQuery('#wrap-loading').show();
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_table_rhk_individu',
                    api_key: esakip.api_key,
                    id_skpd: <?php echo $skpd['id_skpd']; ?>,
                    tahun_anggaran: <?php echo $tahun; ?>
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);

                    if (response.status === 'success') {
                        jQuery('#table-rhk-individu tbody').html(response.data);
                    } else {
                        alert(response.message);
                    }
                    resolve();
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data!');
                    resolve();
                }
            });
        });
    }
</script>