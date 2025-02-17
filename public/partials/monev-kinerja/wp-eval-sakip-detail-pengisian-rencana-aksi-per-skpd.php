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
        nama_skpd
    FROM esakip_data_unit
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $input['tahun']),
    ARRAY_A
);
// print_r($skpd); die($wpdb->last_query);

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$user_nip = $current_user->data->user_login;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$is_administrator = in_array('administrator', $user_roles);

$admin_role_pemda = array(
    'admin_bappeda',
    'admin_ortala'
);

$this_jenis_role = (in_array($user_roles[0], $admin_role_pemda)) ? 1 : 2;

$cek_settingan_menu = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT 
        jenis_role
    FROM esakip_menu_dokumen 
    WHERE nama_dokumen='Rencana Aksi'
      AND user_role='perangkat_daerah' 
      AND active = 1
      AND tahun_anggaran=%d
",
        $input['tahun']
    )
);

$hak_akses_user = ($cek_settingan_menu == $this_jenis_role || $cek_settingan_menu == 3 || $is_administrator) ? true : false;

// hak akses user pegawai
$data_user_pegawai = $wpdb->get_row(
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
if (!empty($data_user_pegawai)) {
    $satker_pegawai_simpeg = substr($data_user_pegawai['satker_id'], 0, 2);

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
            $input['tahun'],
            1,
            $input['tahun'],
            1
        ),
        ARRAY_A
    );
}

// TIPE HAK AKSES USER PEGAWAI | 0 = TIDAK ADA | 1 = ALL | 2 = HANYA RHK TERKAIT
$hak_akses_user_pegawai = 0;
$nip_user_pegawai = 0;
if (!empty($skpd_user_pegawai)) {
    if (($skpd_user_pegawai['id_skpd'] == $id_skpd && $data_user_pegawai['tipe_pegawai_id'] == 11 && strlen($data_user_pegawai['satker_id']) == 2) || $is_administrator) {
        $hak_akses_user_pegawai = 1;
    } else if ($skpd_user_pegawai['id_skpd'] == $id_skpd) {
        $hak_akses_user_pegawai = 2;
    }
    $nip_user_pegawai = $data_user_pegawai['nip_baru'];
} else {
    if ($is_administrator) {
        $hak_akses_user_pegawai = 1;
        $nip_user_pegawai = 0;
    }
}

////////////end////////////

$renaksi_pemda = $wpdb->get_results(
    $wpdb->prepare("
        SELECT
            p.*,
            p.id AS id_data_renaksi_pemda,
            i.*,
            i.id AS id_data_indikator,
            u.*,
            u.id AS id_data_unit
        FROM esakip_data_rencana_aksi_pemda AS p
        INNER JOIN esakip_data_rencana_aksi_indikator_pemda AS i
            ON p.id = i.id_renaksi
            AND p.tahun_anggaran = i.tahun_anggaran
            AND p.active = i.active
        INNER JOIN esakip_data_unit AS u
            ON i.id_skpd = u.id_skpd
            AND i.tahun_anggaran = u.tahun_anggaran
            AND i.active = u.active
        WHERE i.id_skpd = %d
    ", $id_skpd),
    ARRAY_A
);
// print_r($renaksi_pemda); die($wpdb->last_query);
$renaksi_opd = $wpdb->get_results(
    $wpdb->prepare("
        SELECT
            *
        FROM esakip_data_rencana_aksi_opd
        WHERE id_skpd = %d
          AND active = 1
          AND level = 2
          AND parent IS NOT NULL
    ", $id_skpd),
    ARRAY_A
);

$html_renaksi_pemda = '';
$no_renaksi_pemda = 1;

if (!empty($renaksi_pemda)) {
    foreach ($renaksi_pemda as $k_renaksi_pemda => $v_renaksi_pemda) {
        $renaksi_opd_label = !empty($renaksi_opd) ? esc_attr($renaksi_opd[0]['label']) : '';
        $renaksi_opd_id = !empty($renaksi_opd) ? esc_attr($renaksi_opd[0]['id']) : '';

        $aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-success verifikasi-renaksi-pemda" data-id="' . $renaksi_opd_id . '" data-label="' . esc_attr($v_renaksi_pemda['label']) . '" data-id_renaksi_pemda="' . esc_attr($v_renaksi_pemda['id_data_renaksi_pemda']) . '" data-id_indikator="' . esc_attr($v_renaksi_pemda['id_data_indikator']) . '" data-indikator="' . esc_attr($v_renaksi_pemda['indikator']) . '"data-satuan="' . esc_attr($v_renaksi_pemda['satuan']) . '" data-target_akhir="' . esc_attr($v_renaksi_pemda['target_akhir']) . '" data-renaksi-opd="' . $renaksi_opd_label . '" title="Verifikasi Rencana Hasil Kerja"><span class="dashicons dashicons-yes"></span></a>';

        $html_renaksi_pemda .= '
            <tr>
                <td>' . $no_renaksi_pemda++ . '</td>
                <td class="text-left">' . esc_html($v_renaksi_pemda['label']) . '</td>
                <td class="text-left">' . esc_html($v_renaksi_pemda['indikator']) . '</td>
                <td class="text-center">' . esc_html($v_renaksi_pemda['satuan']) . '</td>
                <td class="text-center">' . esc_html($v_renaksi_pemda['target_akhir']) . '</td>
                <td>' . $aksi . '</td>
            </tr>';
    }
}
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
}
$get_pegawai = $wpdb->get_results(
    $wpdb->prepare('
        SELECT 
            *
        FROM esakip_data_pegawai_simpeg
        WHERE satker_id like %s
          AND active = 1
        ORDER BY satker_id ASC, tipe_pegawai_id ASC, nama_pegawai ASC
    ', $get_mapping . '%'),
    ARRAY_A
);
$select_pegawai = '<option value="">Pilih Pegawai Pelaksana</option>';
foreach ($get_pegawai as $pegawai) {
    $select_pegawai .= '<option value="' . $pegawai['nip_baru'] . '" satker-id="' . $pegawai['satker_id'] . '">' . $pegawai['jabatan'] . ' | ' . $pegawai['nip_baru'] . ' | ' . $pegawai['nama_pegawai'] . '</option>';
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
            <h1 class="text-center" style="margin:3rem;">Rencana Hasil Kerja <br><?php echo !empty($skpd['nama_skpd']) ? $skpd['nama_skpd'] : '' ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <h4 id="notifikasi-title" style="text-align: center; margin-top: 10px; font-weight: bold;margin-bottom: .5em;">Notifikasi Rencana Hasil Kerja Pemda</h4>
            <div title="Notifikasi Rencana Hasil Kerja Pemda" style="padding: 5px; overflow: auto; display:flex; justify-content:center;">
                <table class="table_notifikasi_pemda" style="width: 50em;text-align: center;">
                    <thead>
                        <tr>
                            <th>Rencana Hasil Kerja</th>
                            <th>Indikator Rencana Hasil Kerja</th>
                            <th>Satuan</th>
                            <th>Target Akhir</th>
                            <th style="min-width: 10em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <?php if (!$is_admin_panrb && $hak_akses_user): ?>
                <div id="action" class="action-section hide-excel"></div>
            <?php endif; ?>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">KETERANGAN</th>
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
                            <th class="text-center" rowspan="2" style="width: 140px;">ALOKASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">PAGU SUB KEGIATAN</th>
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

        if (hak_akses_pegawai != 0) {
            jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-rencana-aksi" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');
        }

        window.id_jadwal = <?php echo $id_jadwal; ?>;
        window.id_jadwal_wpsipd = <?php echo $id_jadwal_wpsipd; ?>;
        if (id_jadwal == 0) {
            alert("Jadwal RPJMD/RPD untuk data Pokin belum disetting.\nSetting di admin dashboard di menu E-SAKIP Options -> Laporan Monitor Upload Dokumen Tahun <?php echo $input['tahun']; ?>")
        }
        if (id_jadwal_wpsipd == 0) {
            alert("Jadwal RENSTRA untuk data Cascading di WP-SIPD belum disetting.\nSetting di admin dashboard di menu Monev Rencana Hasil Kerja -> Monev Rencana Hasil Kerja Setting")
        }

        getTablePengisianRencanaAksi();
        jQuery("#fileUpload").on('change', function() {
            var id_dokumen = jQuery('#idDokumen').val();
            if (id_dokumen == '') {
                var name = jQuery("#fileUpload").prop('files')[0].name;
                jQuery('#nama_file').val(name);
            }
        });

        jQuery("#tambah-rencana-aksi").on('click', function() {
            kegiatanUtama();
        });

    });
    jQuery(document).on('click', '.verifikasi-renaksi-pemda', function() {
        let id = jQuery(this).data('id');
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
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="id_indikator_renaksi_pemda" value="${jQuery(this).data('id_indikator')}"> 
            <input type="hidden" name="id_renaksi_pemda" value="${jQuery(this).data('id_renaksi_pemda')}"> 
            <div class="form-group">
                <label>Rencana Hasil Kerja Pemda</label>
                <input type="text" id="label_uraian_kegiatan" name="label_uraian_kegiatan" value="${jQuery(this).data('label')}" disabled>
            </div>
            <div class="form-group">
                <label>Indikator Rencana Hasil Kerja Pemda</label>
                <input type="text" id="label_indikator" name="label_indikator" value="${jQuery(this).data('indikator')}" class="mt-1" disabled>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <input type="text" id="satuan" name="satuan" value="${jQuery(this).data('satuan')}" disabled>
            </div>
            <div class="form-group">
                <label>Target Akhir</label>
                <input type="text" id="target_akhir" name="target_akhir" value="${jQuery(this).data('target_akhir')}" class="mt-1" disabled>
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

        let label_uraian_kegiatan = jQuery('#label_uraian_kegiatan').val();
        let label_indikator = jQuery('#label_indikator').val();

        jQuery('input[name="checklist_renaksi_opd[]"]:checked').each(function() {
            let idLabelRenaksiOpd = jQuery(this).attr('id_label_renaksi_opd');
            formData += '&id_label_renaksi_opd[]=' + idLabelRenaksiOpd;
        });

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            type: 'POST',
            url: esakip.url,
            data: formData + '&action=simpan_renaksi_pemda' + '&api_key=' + esakip.api_key + '&id_skpd=' + <?php echo $id_skpd; ?>,
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
        var rencana_pagu_tk = jQuery('#rencana_pagu_tk').val();
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
                        kegiatanUtama();
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

    function tambah_rencana_aksi() {
        return get_tujuan_sasaran_cascading('sasaran')
            .then(function() {
                return new Promise(function(resolve, reject) {
                    jQuery('#wrap-loading').show();
                    jQuery.ajax({
                        url: esakip.url,
                        type: "post",
                        data: {
                            "action": "get_data_pokin",
                            "level": 1,
                            "parent": 0,
                            "api_key": esakip.api_key,
                            "tipe_pokin": "opd",
                            "id_jadwal": id_jadwal,
                            "id_skpd": <?php echo $id_skpd; ?>
                        },
                        dataType: "json",
                        success: function(res) {
                            var html = '';
                            res.data.map(function(value, index) {
                                html += '<option value="' + value.id + '">' + value.label + '</option>';
                            });
                            var get_pegawai = <?php echo json_encode($select_pegawai); ?>;
                            jQuery('#wrap-loading').hide();
                            jQuery("#modal-crud").find('.modal-title').html('Tambah Kegiatan Utama | RHK Level 1');
                            jQuery("#modal-crud").find('.modal-body').html('' +
                                `<form id="form-renaksi">` +
                                '<input type="hidden" id="id_renaksi" value=""/>' +
                                `<div class="form-group">` +
                                `<label for="pokin-level-1">Pilih Pokin Level 1</label>` +
                                `<select class="form-control" multiple name="pokin-level-1" id="pokin-level-1" onchange="get_data_pokin_2(this.value, 2, 'pokin-level-2', true)">` +
                                html +
                                `</select>` +
                                `</div>` +
                                `<div class="form-group">` +
                                `<label for="pokin-level-2">Pilih Pokin Level 2</label>` +
                                `<select class="form-control" multiple name="pokin-level-2" id="pokin-level-2">` +
                                `</select>` +
                                `</div>` +
                                `<div class="form-group">` +
                                `<textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan Kegiatan Utama | RHK Level 1..."></textarea>` +
                                `</div>` +
                                `<div class="form-group">` +
                                `<label for="sasaran-cascading">Pilih Sasaran Cascading</label>` +
                                `<select class="form-control" name="cascading-renstra" id="cascading-renstra">` +
                                `</select>` +
                                `</div>` +
                                `<div class="form-group">` +
                                `<label for="satker_id">Pilih Satuan Kerja</label>` +
                                `<select class="form-control select2" id="satker_id" name="satker_id"><?php echo $select_satker; ?></select>` +
                                `</div>` +
                                `<div class="form-group">` +
                                `<label for="pegawai">Pilih Pegawai Pelaksana</label>` +
                                `<select class="form-control select2" id="pegawai" name="pegawai">${get_pegawai}</select>` +
                                `</div>` +
                                `</form>`);
                            jQuery("#modal-crud").find('.modal-footer').html('' +
                                '<button type="button" class="btn btn-danger" data-dismiss="modal">' +
                                'Tutup' +
                                '</button>' +
                                '<button type="button" class="btn btn-success" onclick="simpan_data_renaksi(1)">' +
                                'Simpan' +
                                '</button>');
                            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                            jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                            jQuery("#modal-crud").modal('show');
                            jQuery('#pokin-level-1').select2({
                                width: '100%'
                            });
                            jQuery('#pokin-level-2').select2({
                                width: '100%',
                                placeholder: "Pilih Pokin Level 2"
                            }).place;
                            jQuery('#satker_id').select2({
                                width: '100%'
                            });
                            jQuery('#pegawai').select2({
                                width: '100%'
                            });
                            var key = 'sasaran' + '-' + 'x.xx';
                            if (data_sasaran_cascading[key] != undefined) {
                                let html_cascading = '<option value="">Pilih Sasaran Cascading</option>';
                                if (data_sasaran_cascading[key].data !== null) {
                                    data_sasaran_cascading[key].data.map(function(value, index) {
                                        if (value.id_unik_indikator == null) {
                                            html_cascading += '<option value="' + value.kode_bidang_urusan + '">' + value.sasaran_teks + '</option>';
                                        }
                                    });
                                }
                                jQuery("#cascading-renstra").html(html_cascading);
                                jQuery('#cascading-renstra').select2({
                                    width: '100%'
                                });
                            }

                            resolve();
                        }
                    });
                });
            });
    }

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
                            "parent_cascading": parent_cascading
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
                            "parent_cascading": parent_cascading
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
                            "parent_cascading": parent_cascading
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

    function edit_rencana_aksi(id, tipe) {
        if (tipe == 1) {
            tambah_rencana_aksi().then(function() {
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: 'POST',
                    data: {
                        action: 'get_rencana_aksi',
                        api_key: esakip.api_key,
                        id: id,
                        tahun_anggaran: '<?php echo $input['tahun'] ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        if (response.status == 'error') {
                            alert(response.message)
                        } else if (response.data != null) {
                            jQuery('#id_renaksi').val(id);
                            jQuery("#modal-crud").find('.modal-title').html('Edit Kegiatan Utama');
                            jQuery('#cascading-renstra').val(response.data.kode_cascading_sasaran).trigger('change');
                            /** menghapus attr onchange sementara agar tiak bentrok dengan fungsi dibawah ini */
                            jQuery('#pokin-level-1').removeAttr('onchange');
                            let selected_pokin_1 = [];
                            response.data.pokin.map(function(b) {
                                selected_pokin_1.push(b.id);
                            });
                            jQuery('#pokin-level-1').val(selected_pokin_1);
                            jQuery('#pokin-level-1').trigger('change');
                            jQuery("#pokin-level-2").empty();
                            get_data_pokin_2(selected_pokin_1, 2, "pokin-level-2", false).then(function() {
                                let selected_pokin_2 = [];
                                response.data.pokin_2.map(function(b) {
                                    selected_pokin_2.push(b.id);
                                });
                                jQuery('#pokin-level-2').val(selected_pokin_2);
                                jQuery("#pokin-level-2").trigger('change');
                            })
                            /** kembalikan attr onchange */
                            jQuery('#pokin-level-1').attr('onchange', 'get_data_pokin_2(this.value, 2, "pokin-level-2", true)');
                            jQuery('#label_renaksi').val(response.data.label);
                            jQuery('#cascading-renstra').val(response.data.kode_cascading_sasaran).trigger('change');
                            if (response.data && response.data.jabatan && response.data.jabatan.satker_id) {
                                jQuery('#satker_id').val(response.data.jabatan.satker_id).trigger('change');
                            }

                            if (response.data && response.data.pegawai && response.data.pegawai.nip_baru) {
                                jQuery('#pegawai').val(response.data.pegawai.nip_baru).trigger('change');
                            }

                            if (hak_akses_pegawai == 1) {
                                jQuery('#satker_id').prop('disabled', false);
                                jQuery('#pegawai').prop('disabled', false);
                            } else {
                                jQuery('#satker_id').prop('disabled', true);
                                jQuery('#pegawai').prop('disabled', true);
                            }

                        }
                    }
                });
            });
        } else {
            tambah_renaksi_2(tipe, true).then(function() {
                jQuery('#wrap-loading').show();
                jQuery.ajax({
                    url: esakip.url,
                    type: 'POST',
                    data: {
                        action: 'get_rencana_aksi',
                        api_key: esakip.api_key,
                        id: id,
                        tahun_anggaran: '<?php echo $input['tahun'] ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery('#wrap-loading').hide();
                        if (response.status == 'error') {
                            alert(response.message);
                        } else if (response.data != null) {
                            jQuery('#id_renaksi').val(id);
                            if (tipe == 2) {
                                jQuery("#modal-crud").find('.modal-title').html('Edit Rencana Hasil Kerja');
                                let kode_cascading_renstra = `${response.data.kode_cascading_program}_${response.data.id_skpd}`;
                                if (response.data.id_sub_skpd_cascading) {
                                    kode_cascading_renstra = `${response.data.kode_cascading_program}_${response.data.id_sub_skpd_cascading}`;
                                }
                                jQuery('#cascading-renstra').val(kode_cascading_renstra).trigger('change');
                                jQuery('#label_renaksi_opd_').val(response.data.label_renaksi_opd_);
                                jQuery('#label_uraian_kegiatan').val(response.data.label_uraian_kegiatan);
                                jQuery('#label_indikator_uraian_kegiatan').val(response.data.label_indikator_uraian_kegiatan);
                                jQuery('#sub-skpd-cascading').val(response.data.nama_skpd_cascading || '-');
                                let pagu_cascading = response.data.pagu_cascading != null ? formatRupiah(response.data.pagu_cascading) : '0'
                                jQuery('#pagu-cascading').val(pagu_cascading);
                                let selected_pokin_3 = [];
                                response.data.pokin_3.map(function(b) {
                                    selected_pokin_3.push(b.id);
                                });
                                jQuery('#pokin-level-1').val(selected_pokin_3);
                                jQuery("#pokin-level-1").trigger('change');

                                if (response.data && response.data.jabatan && response.data.jabatan.satker_id) {
                                    jQuery('#satker_id').val(response.data.jabatan.satker_id).trigger('change');
                                }

                                if (response.data && response.data.pegawai && response.data.pegawai.nip_baru) {
                                    jQuery('#pegawai').val(response.data.pegawai.nip_baru).trigger('change');
                                }
                                var renaksi_pemda = "";
                                response.data.renaksi_pemda.map(function(b, i) {
                                    renaksi_pemda += `
                                    <tr>
                                        <td><input class="text-right" type="checkbox" class="form-check-input" id="label_renaksi_pemda"name="checklist_renaksi_pemda[]" value="${b.label_uraian_kegiatan}" id_label_renaksi_pemda="${b.id_pemda}"id_label_indikator_renaksi_pemda="${b.id_indikator}" ${b.id_label != null ? 'checked' : ''}>
                                        </td>
                                        <td>
                                            <label class="form-check-label" id="label_uraian_kegiatan" for="label_uraian_kegiatan">${b.label_uraian_kegiatan}</label>
                                        </td>
                                        <td>
                                            <label class="form-check-label" id="label_indikator_uraian_kegiatan" for="label_indikator_uraian_kegiatan">${b.label_indikator_uraian_kegiatan}</label>
                                        </td>
                                        <td  class="text-center">
                                            <label class="form-check-label" id="satuan" for="satuan">${b.satuan_pemda}</label>
                                        </td>
                                        <td  class="text-center">
                                            <label class="form-check-label" id="target_akhir" for="target_akhir">${b.target_akhir_pemda}</label>
                                        </td>
                                    </tr>
                                `;
                                });
                                if (renaksi_pemda.length > 0) {
                                    let checklist_renaksi_pemda = `
                                    <div class="form-group">
                                        <label>Rencana Hasil Kerja Pemerintah Daerah | Level 4</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input class="text-right" type="checkbox" id="check_all" class="form-check-input">
                                                    </th>
                                                    <th>Rencana Hasil Kerja</th>
                                                    <th>Indikator Rencana Hasil Kerja</th>
                                                    <th>Satuan</th>
                                                    <th>Target Akhir</th>
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
                                let selected_pokin_4 = [];
                                response.data.pokin_4.map(function(b) {
                                    selected_pokin_4.push(b.id);
                                });
                                jQuery('#pokin-level-1').val(selected_pokin_4);
                                jQuery("#pokin-level-1").trigger('change');
                                jQuery('#cascading-renstra').val(response.data.kode_cascading_kegiatan).trigger('change');
                                if (response.data && response.data.jabatan && response.data.jabatan.satker_id) {
                                    jQuery('#satker_id').val(response.data.jabatan.satker_id).trigger('change');
                                }

                                if (response.data && response.data.pegawai && response.data.pegawai.nip_baru) {
                                    jQuery('#pegawai').val(response.data.pegawai.nip_baru).trigger('change');
                                }
                            } else if (tipe == 4) {
                                jQuery("#modal-crud").find('.modal-title').html('Edit Uraian Teknis Kegiatan');
                                let selected_pokin_5 = [];
                                response.data.pokin_5.map(function(b) {
                                    selected_pokin_5.push(b.id);
                                });
                                jQuery('#pokin-level-1').val(selected_pokin_5);
                                jQuery("#pokin-level-1").trigger('change');
                                jQuery('#cascading-renstra').val(response.data.kode_cascading_sub_kegiatan).trigger('change');
                                if (response.data && response.data.jabatan && response.data.jabatan.satker_id) {
                                    jQuery('#satker_id').val(response.data.jabatan.satker_id).trigger('change');
                                }

                                if (response.data && response.data.pegawai && response.data.pegawai.nip_baru) {
                                    jQuery('#pegawai').val(response.data.pegawai.nip_baru).trigger('change');
                                }
                                var checklist_dasar_pelaksanaan = `
                                <div class="form-group">
                                    <label>Pilih Dasar Pelaksanaan</label>
                                    <div>
                                        <label><input type="checkbox" name="dasar_pelaksanaan[]" id="mandatori_pusat" ${response.data.mandatori_pusat == 1 ? 'checked' : ''}> Mandatori Pusat</label><br>
                                        <label><input type="checkbox" name="dasar_pelaksanaan[]" id="inisiatif_kd" ${response.data.inisiatif_kd == 1 ? 'checked' : ''}> Inisiatif Kepala Daerah</label><br>
                                        <label><input type="checkbox" name="dasar_pelaksanaan[]" id="musrembang" ${response.data.musrembang == 1 ? 'checked' : ''}> MUSREMBANG (Musyawarah Rencana Pembangunan)</label><br>
                                        <label><input type="checkbox" name="dasar_pelaksanaan[]" id="pokir" ${response.data.pokir == 1 ? 'checked' : ''}> Pokir (Pokok Pikiran)</label>
                                    </div>
                                </div>
                            `;
                                jQuery("#modal-crud").find('.modal-body').append(checklist_dasar_pelaksanaan);
                            }
                            jQuery('#label_renaksi').val(response.data.label);
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
    }

    function get_data_pokin_2(parent, level, tag, getParentManual = false) {
        if (getParentManual) {
            parent = jQuery("#pokin-level-1").val();
        }
        jQuery('#wrap-loading').show();
        if (typeof parent === 'string') {
            /**memastikan input pokin itu dalam bentuk array */
            parent = parent.split(',');
        }
        return new Promise(function(resolve, reject) {
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
                    var html = '<option value="">Pilih Pokin Level ' + level + '</option>';
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
        });
    }

    function kegiatanUtama() {
        jQuery("#wrap-loading").show();
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_renaksi",
                    "level": 1,
                    "parent": 0,
                    "api_key": esakip.api_key,
                    "tipe_pokin": "opd",
                    "id_skpd": <?php echo $id_skpd; ?>,
                    "tahun_anggaran": '<?php echo $input['tahun'] ?>'
                },
                dataType: "json",
                success: function(res) {
                    jQuery('#wrap-loading').hide();
                    let button_tambah = ``;
                    if (hak_akses_pegawai == 1) {
                        button_tambah = `` +
                            `<div style="margin-top:10px">` +
                            `<button type="button" class="btn btn-success mb-2" onclick="tambah_rencana_aksi();"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data Kegiatan Utama</button>` +
                            `</div>`;
                    } else {
                        button_tambah = `` +
                            `<div style="margin-top:10px">` +
                            `</div>`;
                    }
                    let kegiatanUtama = `` +
                        `${button_tambah}` +
                        `<table class="table" id="kegiatanUtama">` +
                        `<thead>` +
                        `<tr class="table-secondary">` +
                        `<th class="text-center" style="width:40px;">No</th>` +
                        `<th class="text-center" style="width:300px;">Label Pokin Level 2</th>` +
                        `<th class="text-center">Kegiatan Utama | RHK Level 1</th>` +
                        `<th class="text-center">Sasaran Cascading</th>` +
                        `<th class="text-center">Dasar Pelaksanaan</th>` +
                        `<th class="text-center">Pegawai Pelaksana</th>` +
                        `<th class="text-center" style="width:200px;">Aksi</th>` +
                        `</tr>` +
                        `</thead>` +
                        `<tbody>`;
                    res.data.map(function(value, index) {
                        var label_dasar_pelaksanaan = '';
                        let get_data_dasar_pelaksanaan = [];
                        total_pagu = 0;
                        value.get_dasar_2.forEach(function(dasar_level_2) {
                            dasar_level_2.get_dasar_to_level_3.forEach(function(dasar_to_level_3) {
                                dasar_to_level_3.get_dasar_to_level_4.forEach(function(dasar_to_level_4) {
                                    if (dasar_to_level_4.get_pagu_2 && dasar_to_level_4.get_pagu_2.length > 0) {
                                        total_pagu += parseFloat(dasar_to_level_4.get_pagu_2[0].total_pagu) || 0;
                                    }
                                    if (dasar_to_level_4.mandatori_pusat == 1 && !get_data_dasar_pelaksanaan.includes('Mandatori Pusat')) {
                                        get_data_dasar_pelaksanaan.push('Mandatori Pusat');
                                    }
                                    if (dasar_to_level_4.inisiatif_kd == 1 && !get_data_dasar_pelaksanaan.includes('Inisiatif Kepala Daerah')) {
                                        get_data_dasar_pelaksanaan.push('Inisiatif Kepala Daerah');
                                    }
                                    if (dasar_to_level_4.musrembang == 1 && !get_data_dasar_pelaksanaan.includes('MUSREMBANG (Musyawarah Rencana Pembangunan)')) {
                                        get_data_dasar_pelaksanaan.push('MUSREMBANG (Musyawarah Rencana Pembangunan)');
                                    }
                                    if (dasar_to_level_4.pokir == 1 && !get_data_dasar_pelaksanaan.includes('POKIR (Pokok Pikiran)')) {
                                        get_data_dasar_pelaksanaan.push('POKIR (Pokok Pikiran)');
                                    }
                                });
                            });
                        });

                        if (get_data_dasar_pelaksanaan.length > 0) {
                            label_dasar_pelaksanaan = `<ul><li>${get_data_dasar_pelaksanaan.join('</li><li>')}</li></ul>`;
                        }
                        let label_cascading = value.label_cascading_sasaran != null ? value.label_cascading_sasaran : '-';
                        var nama_pegawai = '';
                        if (value.detail_satker && value.detail_satker.nama) {
                            nama_pegawai += value.detail_satker.nama + '<br>';
                        }
                        if (value.detail_pegawai && value.detail_pegawai.nip_baru) {
                            nama_pegawai += '<span class="badge badge-primary p-2 mt-2 text-center">' + value.detail_pegawai.nip_baru + ' ' + value.detail_pegawai.nama_pegawai + '</span>';
                        }
                        let label_pokin = '-';
                        let id_pokin_parent = [];
                        if (value.pokin && value.pokin.length > 0) {
                            label_pokin = `<ul style="margin: 0;">`;
                            value.pokin.forEach(function(get_pokin) {
                                label_pokin += `<li>${get_pokin.pokin_label}</li>`;
                                id_pokin_parent.push(+get_pokin.id_pokin);
                            });
                            label_pokin += `</ul>`;
                        }

                        kegiatanUtama += `` +
                            `<tr id="kegiatan_utama_${value.id}">` +
                            `<td class="text-center">${index+1}</td>` +
                            `<td class="label_pokin">${label_pokin}</td>` +
                            `<td class="label_renaksi">${value.label}</td>` +
                            `<td class="label_cascading font-weight-bold">${label_cascading}</td>` +
                            `<td class="label_renaksi">${label_dasar_pelaksanaan}</td>` +
                            `<td class="detail_pegawai font-weight-bold">${nama_pegawai}</td>` +
                            `<td class="text-center">`;
                        // validasi akses tombol untuk admin dan pegawai
                        if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                            kegiatanUtama += `` +
                                `<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="tambah_indikator_rencana_aksi(${value.id}, 1, ${total_pagu})" title="Tambah Indikator (Total Pagu: ${formatRupiah(total_pagu)})"><i class="dashicons dashicons-plus"></i></a> `;
                        }
                        kegiatanUtama += `` +
                            `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, 2, ${JSON.stringify(id_pokin_parent)}, '${value.kode_cascading_sasaran}')" title="Lihat Rencana Hasil Kerja"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;

                        if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                            kegiatanUtama += `` +
                                `<a href="javascript:void(0)" onclick="edit_rencana_aksi(${value.id}, 1)" data-id="${value.id}" class="btn btn-sm btn-primary edit-kegiatan-utama" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`;
                        }
                        if (hak_akses_pegawai == 1) {
                            kegiatanUtama += `` +
                                `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger" onclick="hapus_rencana_aksi(${value.id}, 1)" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`;
                        }
                        kegiatanUtama += `` +
                            `</td>` +
                            `</tr>`;

                        let indikator = value.indikator;
                        if (indikator.length > 0) {
                            kegiatanUtama += `` +
                                '<td colspan="7" style="padding: 0;">' +
                                `<table class="table" id="indikatorKegiatanUtama" style="margin: .5rem 0 2rem;">` +
                                `<thead>` +
                                `<tr class="table-secondary">` +
                                `<th class="text-center" style="width:20px">No</th>` +
                                `<th class="text-center">Aspek</th>` +
                                `<th class="text-center">Indikator</th>` +
                                `<th class="text-center" style="width:120px;">Satuan</th>` +
                                `<th class="text-center" style="width:50px;">Target Awal</th>` +
                                `<th class="text-center" style="width:50px;">Target Akhir</th>` +
                                `<th class="text-center" style="width:120px;">Rencana Pagu</th>` +
                                `<th class="text-center" style="width:135px">Aksi</th>` +
                                `</tr>` +
                                `</thead>` +
                                `<tbody>`;
                            indikator.map(function(b, i) {
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
                                kegiatanUtama += `` +
                                    `<tr>` +
                                    `<td class="text-center">${index+1}.${i+1}</td>` +
                                    `<td>${text_aspek_rhk}</td>` +
                                    `<td>${b.indikator}</td>` +
                                    `<td class="text-center">${b.satuan}</td>` +
                                    `<td class="text-center">${b.target_awal} ${target_teks_awal}</td>` +
                                    `<td class="text-center">${b.target_akhir} ${target_teks_akhir}</td>` +
                                    `<td class="text-right">${formatRupiah(b.rencana_pagu) || 0}</td>` +
                                    `<td class="text-center">` +
                                    `<input type="checkbox" title="Lihat Rencana Hasil Kerja Per Bulan" class="lihat_bulanan" data-id="${b.id}" onclick="lihat_bulanan(this);" style="margin: 0 6px;">`;

                                if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                    kegiatanUtama += `` +
                                        `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-primary" onclick="edit_indikator(${b.id}, 1, ${total_pagu})" title="Edit"><i class="dashicons dashicons-edit"></i></a> ` +
                                        `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-danger" onclick="hapus_indikator(${b.id}, 1);" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`;
                                }
                                kegiatanUtama += `` +
                                    `</td>` +
                                    `</tr>`;

                                kegiatanUtama += `` +
                                    `<tr style="display: none;" class="data_bulanan_${b.id}">` +
                                    `<td colspan="8" style="padding: 10px;">` +
                                    `<div style="display: none; margin: 1rem auto;" class="data_bulanan_${b.id}">` +
                                    `<h4 class="text-center" style="margin: 10px;">Rumus Indikator</h4>` +
                                    `<textarea class="form-control" id="show-rumus-indikator">${val_rumus_indikator}</textarea>` +
                                    `</div>` +
                                    `</td>` +
                                    `</tr>`;

                                const get_bulan = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                                ];

                                let bulanan = b.bulanan || [];

                                kegiatanUtama += '' +
                                    `<tr style="display: none;" class="data_bulanan_${b.id}">` +
                                    `<td colspan="11" style="padding: 10px;">` +
                                    `<h3 class="text-center" style="margin: 10px;">Rencana Aksi Per Bulan</h3>` +
                                    `<table class="table" style="margin: 0;">` +
                                    `<thead>` +
                                    `<tr class="table-secondary">` +
                                    `<th class="text-center">Bulan/TW</th>` +
                                    `<th class="text-center">Rencana Hasil Kerja</th>` +
                                    `<th class="text-center" style="width:100px;">Target</th>` +
                                    `<th class="text-center" style="width:100px;">Satuan</th>` +
                                    `<th class="text-center" style="width:150px;">Realisasi</th>` +
                                    `<th class="text-center" style="width:60px">Capaian</th>` +
                                    `<th class="text-center">Tanggapan Atasan</th>` +
                                    `<th class="text-center" style="width:60px">Aksi</th>` +
                                    `</tr>` +
                                    `</thead>` +
                                    `<tbody>`;

                                get_bulan.forEach((bulan, bulan_index) => {
                                    let get_data_bulanan = b.bulanan.find(bulanan => bulanan.bulan == (bulan_index + 1)) || {};
                                    let isdisabled = <?php echo $set_renaksi == 1 ? 'true' : 'false'; ?>;

                                    kegiatanUtama += '' +
                                        `<tr>` +
                                        `<td class="text-center">${bulan}</td>` +
                                        `<td class="text-center"><textarea class="form-control" name="rencana_aksi_${b.id}_${bulan_index + 1}" id="rencana_aksi_${b.id}_${bulan_index + 1}" ${isdisabled ? 'disabled' : ''}>${get_data_bulanan.rencana_aksi || ''}</textarea></td>` +
                                        `<td class="text-center"><input type="text" class="form-control" name="volume_${b.id}_${bulan_index + 1}" id="volume_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.volume || ''}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center"><input type="text" class="form-control" name="satuan_bulan_${b.id}_${bulan_index + 1}" id="satuan_bulan_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.satuan || ''}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center"><input type="number" class="form-control" name="realisasi_${b.id}_${bulan_index + 1}" id="realisasi_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.realisasi || ''}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center" name="capaian_${b.id}_${bulan_index + 1}" id="capaian_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.capaian || ''}"></td>` +
                                        `<td class="text-center"><textarea class="form-control" name="keterangan_${b.id}_${bulan_index + 1}" id="keterangan_${b.id}_${bulan_index + 1}" ${isdisabled ? 'disabled' : ''}>${get_data_bulanan.keterangan || ''}</textarea></td>` +
                                        `<td class="text-center">`;

                                    if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                        kegiatanUtama += `` +
                                            (isdisabled ?
                                                `-` :
                                                `<a href="javascript:void(0)" data-id="${b.id}" data-bulan="${bulan_index + 1}" class="btn btn-sm btn-success" onclick="simpan_bulanan(${b.id}, ${bulan_index + 1})" title="Simpan"><i class="dashicons dashicons-yes"></i></a>`
                                            );
                                    }
                                    kegiatanUtama += `` +
                                        `</td>` +
                                        `</tr>`;

                                    if ((bulan_index + 1) % 3 == 0) {
                                        var triwulan = (bulan_index + 1) / 3;
                                        kegiatanUtama += '' +
                                            `<tr style="background: #FDFFB6;">` +
                                            `<td class="text-center">triwulan ${triwulan}</td>` +
                                            `<td class="text-center">${b.indikator}</td>` +
                                            `<td class="text-center">${b['target_' + triwulan]}</td>` +
                                            `<td class="text-center">${b.satuan}</td>` +
                                            `<td class="text-center">` +
                                            `<input type="number" class="form-control" name="realisasi_${b.id}_tw_${triwulan}" id="realisasi_${b.id}_tw_${triwulan}" ${isdisabled ? 'disabled' : ''} value="${b['realisasi_tw_' + triwulan] || ''}">` +
                                            `</td>` +
                                            `<td class="text-center"></td>` +
                                            `<td class="text-center">` +
                                            `<textarea class="form-control" name="keterangan_${b.id}_tw_${triwulan}" id="keterangan_${b.id}_tw_${triwulan}" ${isdisabled ? 'disabled' : ''}>${b['ket_tw_' + triwulan] || ''}</textarea>` +
                                            `</td>` +
                                            `<td class="text-center">`;
                                        if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                            kegiatanUtama += `` +
                                                (isdisabled ?
                                                    `-` :
                                                    `<a href="javascript:void(0)" data-id="${b.id}" data-tw="${triwulan}" class="btn btn-sm btn-success" onclick="simpan_triwulan(${b.id}, ${triwulan})" title="Simpan"><i class="dashicons dashicons-yes"></i></a>`
                                                );
                                        }
                                        kegiatanUtama += `` +
                                            `</td>` +
                                            `</tr>`;
                                    }

                                });

                                kegiatanUtama += '' +
                                    `<tr class="table-secondary">` +
                                    `<th class="text-center">Total</th>` +
                                    `<td class="text-center">${b.indikator}</td>` +
                                    `<td class="text-center">${b.target_akhir}</td>` +
                                    `<td class="text-center">${b.satuan}</td>` +
                                    `<td class="text-center"><input type="number" class="form-control" name="realisasi_akhir_${b.id}" id="realisasi_akhir_${b.id}" value="${b['realisasi_akhir'] || ''}"></td>` +
                                    `<td class="text-center"></td>` +
                                    `<td class="text-center"><textarea class="form-control" name="ket_total_${b.id}" id="ket_total_${b.id}">${b['ket_total'] || ''}</textarea></td>` +
                                    `<td class="text-center">`;
                                if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                    kegiatanUtama += `` +
                                        `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-success" onclick="simpan_total(${b.id})" title="Simpan Total"><i class="dashicons dashicons-yes"></i></a>`;
                                }
                                kegiatanUtama += `` +
                                    `</td>` +
                                    `</tr>` +
                                    `</tbody>` +
                                    `</table>` +
                                    `</td>` +
                                    `</tr>`;


                            });
                            kegiatanUtama += `` +
                                '</tbody>' +
                                '</table>' +
                                '</td>';
                        }
                    });
                    kegiatanUtama += '' +
                        `<tbody>` +
                        `</table>`;

                    jQuery("#nav-level-1").html(kegiatanUtama);
                    jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
                    jQuery('#modal-renaksi').modal('show');
                    resolve();
                }
            });
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

    function get_sub_keg_rka_wpsipd(kode_sbl) {
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

    function tambah_indikator_rencana_aksi(id, tipe, total_pagu, kode_sbl = false) {
        var title = '';
        let input_pagu = '';
        let input_sumber_dana = '';
        if (tipe == 1) {
            title = 'Indikator Kegiatan Utama | RHK Level 1';
            input_pagu = '' +
                `<div class="form-group row">` +
                '<div class="col-md-2">' +
                `<label for="rencana_pagu">Total RHK Level 4</label>` +
                '</div>' +
                '<div class="col-md-10">' +
                `<div class="input-group">` +
                `<input type="number" disabled class="form-control text-right" id="rencana_pagu_tk" value="` + total_pagu + `"/>` +
                `</div>` +
                '</div>' +
                '</div>' +
                `<div class="form-group row">` +
                '<div class="col-md-2">' +
                `<label for="total_rincian">Rencana Pagu</label>` +
                '</div>' +
                '<div class="col-md-5">' +
                `<div class="input-group">` +
                `<input type="number" class="form-control" id="total_rincian" max="100" value="100"/>` +
                `<span class="input-group-text">%</span>` +
                `</div>` +
                '</div>' +
                '<div class="col-md-5">' +
                `<div class="input-group">` +
                `<input type="number" disabled class="form-control text-right" id="rencana_pagu" value="` + total_pagu + `" />` +
                `</div>` +
                '</div>' +
                '</div>'
        } else if (tipe == 2) {
            title = 'Indikator Rencana Hasil Kerja | RHK Level 2';
            input_pagu = '' +
                `<div class="form-group row">` +
                '<div class="col-md-2">' +
                `<label for="rencana_pagu">Total RHK Level 4</label>` +
                '</div>' +
                '<div class="col-md-10">' +
                `<div class="input-group">` +
                `<input type="number" disabled class="form-control text-right" id="rencana_pagu_tk" value="` + total_pagu + `"/>` +
                `</div>` +
                '</div>' +
                '</div>' +
                `<div class="form-group row">` +
                '<div class="col-md-2">' +
                `<label for="total_rincian">Rencana Pagu</label>` +
                '</div>' +
                '<div class="col-md-5">' +
                `<div class="input-group">` +
                `<input type="number" class="form-control" id="total_rincian" max="100" value="100"/>` +
                `<span class="input-group-text">%</span>` +
                `</div>` +
                '</div>' +
                '<div class="col-md-5">' +
                `<div class="input-group">` +
                `<input type="number" disabled class="form-control text-right" id="rencana_pagu" value="` + total_pagu + `" />` +
                `</div>` +
                '</div>' +
                '</div>'
        } else if (tipe == 3) {
            title = 'Indikator Uraian Kegiatan Rencana Hasil Kerja | RHK Level 3';
            input_pagu = '' +
                `<div class="form-group row">` +
                '<div class="col-md-2">' +
                `<label for="rencana_pagu">Total RHK Level 4</label>` +
                '</div>' +
                '<div class="col-md-10">' +
                `<div class="input-group">` +
                `<input type="number" disabled class="form-control text-right" id="rencana_pagu_tk" value="` + total_pagu + `"/>` +
                `</div>` +
                '</div>' +
                '</div>' +
                `<div class="form-group row">` +
                '<div class="col-md-2">' +
                `<label for="total_rincian">Rencana Pagu</label>` +
                '</div>' +
                '<div class="col-md-5">' +
                `<div class="input-group">` +
                `<input type="number" class="form-control" id="total_rincian" max="100" value="100"/>` +
                `<span class="input-group-text">%</span>` +
                `</div>` +
                '</div>' +
                '<div class="col-md-5">' +
                `<div class="input-group">` +
                `<input type="number" disabled class="form-control text-right" id="rencana_pagu" value="` + total_pagu + `" />` +
                `</div>` +
                '</div>' +
                '</div>'
        } else if (tipe == 4) {
            title = 'Indikator Uraian Teknis Kegiatan | RHK Level 4';
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
                get_sub_keg_rka_wpsipd(kode_sbl)
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
            `<label for="rumus-indikator">Rumus Indikator</label>` +
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
            '<button type="button" class="btn btn-danger" data-dismiss="modal">' +
            'Tutup' +
            '</button>' +
            '<button type="button" class="btn btn-success" onclick="simpan_indikator_renaksi(' + tipe + ')" data-view="kegiatanUtama">' +
            'Simpan' +
            '</button>');
        jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '1000px');
        jQuery("#modal-crud").find('.modal-dialog').css('width', '');
        jQuery("#modal-crud").modal('show');

        jQuery("#total_rincian").on('input', function() {
            var persen = parseFloat(jQuery(this).val());
            var persen_rencana_pagu = parseFloat(jQuery("#rencana_pagu_tk").val());

            if (persen > 100) {
                jQuery(this).val(100);
                persen = 100;
            } else if (persen < 0) {
                jQuery(this).val(0);
                persen = 0;
            }

            var get_total_pagu = (persen_rencana_pagu * persen) / 100;
            jQuery("#rencana_pagu").val(get_total_pagu.toFixed(0));
        });
    }

    function tambahSumberDana() {
        return new Promise(function(resolve, reject) {
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

            trNewUsulan.find('.select2').remove();
            trNewUsulan.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden');

            jQuery('.input_sumber_dana > tbody').append(trNewUsulan);

            trNewUsulan.find('select').select2({
                width: '100%'
            });

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
                    let rencana_pagu = response.data.rencana_pagu != null ? response.data.rencana_pagu : 0;
                    let persen = 0;
                    persen = (rencana_pagu / total_pagu) * 100;

                    tambah_indikator_rencana_aksi(response.data.id_renaksi, tipe, total_pagu);
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
                    jQuery('#total_rincian').val(persen.toFixed(0));
                    jQuery('#rencana_pagu_tk').val(total_pagu);
                    if (response.data.rumus_indikator) {
                        jQuery('#rumus-indikator').val(response.data.rumus_indikator);
                    } else {
                        jQuery('#rumus-indikator').val('(Realisasi Indikator / Target Indikator) * 100 = Capaian');
                    }


                    /** Memunculkan data sumber dana */
                    if (kode_sbl) {
                        get_sub_keg_rka_wpsipd(kode_sbl).then(() => {
                            response.data.sumber_dana.map(function(value, index) {
                                let id = index + 1;
                                new Promise(function(resolve, reject) {
                                        if (id > 1) {
                                            tambahSumberDana()
                                                .then(function() {
                                                    resolve(value);
                                                })
                                        } else {
                                            resolve(value);
                                        }
                                    })
                                    .then(function(value) {
                                        jQuery("#sumber_dana_" + id).val(value.id_sumber_dana).trigger('change');
                                        jQuery("#pagu_sumber_dana_" + id).val(value.rencana_pagu).trigger('keyup');
                                    });
                            })
                        })
                    }

                    if (response.data.set_target_teks == 1) {
                        jQuery('#cek-target-teks').prop('checked', true);
                        jQuery(".target-teks").show();
                    } else {
                        jQuery('#cek-target-teks').prop('checked', false);
                        jQuery(".target-teks").hide();
                    }
                } else if (response.status == 'error') {
                    alert(response.message);
                }
                jQuery('#wrap-loading').hide();
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
                            kegiatanUtama();
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
                            kegiatanUtama();
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
            var header_pagu = `<th class="text-center" style="width:50px;">Rencana Pagu</th>`;
            let title_cascading = '';
            let label_pokin = '';
            let rhk_level = '';

            // rencana aksi
            if (tipe == 1) {
                id_tabel = 'kegiatanUtama';
                title = 'Kegiatan Utama';
                fungsi_tambah = 'tambah_rencana_aksi';
                title_cascading = 'Sasaran Cascading';
                label_pokin = '2';
                rhk_level = '1';
            } else if (tipe == 2) {
                id_tabel = 'tabel_rencana_aksi';
                title = 'Rencana Hasil Kerja';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Program Cascading';
                label_pokin = '3';
                rhk_level = '2';
            } else if (tipe == 3) {
                id_tabel = 'tabel_uraian_rencana_aksi';
                title = 'Uraian Kegiatan Rencana Hasil Kerja';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Kegiatan Cascading';
                label_pokin = '4';
                rhk_level = '3';
            } else if (tipe == 4) {
                id_tabel = 'tabel_uraian_teknis_kegiatan';
                title = 'Uraian Teknis Kegiatan';
                fungsi_tambah = 'tambah_renaksi_2';
                title_cascading = 'Sub Kegiatan Cascading';
                label_pokin = '5';
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
                        `</table>` +
                        '<table class="table" id="' + id_tabel + '" parent_renaksi="' + parent_renaksi + '" parent_pokin="' + parent_pokin + '" parent_cascading="' + parent_cascading + '" parent_sub_skpd="' + parent_sub_skpd + '">' +
                        `<thead>` +
                        `<tr class="table-secondary">` +
                        `<th class="text-center" style="width:40px;">No</th>` +
                        `<th class="text-center" style="width:300px;">Label Pokin Level ` + label_pokin + `</th>` +
                        `<th class="text-center">` + title + ` | RHK Level ` + rhk_level + `</th>` +
                        `<th class="text-center">` + title_cascading + `</th>` +
                        `${header_dasar_pelaksanaan}` +
                        `<th class="text-center" style="width:300px;">Pegawai Pelaksana</th>` +
                        `<th class="text-center" style="width:200px;">Aksi</th>` +
                        `</tr>` +
                        `</thead>` +
                        `<tbody>`;
                    res.data.map(function(value, index) {
                        var id_pokin = 0;
                        var tombol_detail = '';
                        var id_parent_cascading = 0;
                        var label_cascading = '-';
                        var data_tagging_rincian = '';
                        var label_dasar_pelaksanaan = '';
                        var total_pagu = 0;
                        let get_data_dasar_pelaksanaan = [];
                        let label_pokin = '-';
                        let id_parent_sub_skpd_cascading = 0;

                        var nama_pegawai = '';
                        if (value.detail_satker && value.detail_satker.nama) {
                            nama_pegawai += value.detail_satker.nama + '<br>';
                        }
                        if (value.detail_pegawai && value.detail_pegawai.nip_baru) {
                            nama_pegawai += '<span class="badge badge-primary p-2 mt-2 text-center">' + value.detail_pegawai.nip_baru + ' ' + value.detail_pegawai.nama_pegawai + '</span>';
                        }
                        if (tipe == 1) {
                            id_parent_cascading = value['kode_cascading_sasaran'];
                            label_cascading = value['label_cascading_sasaran'] != null ? value['label_cascading_sasaran'] : '-';
                            value.get_dasar_2.forEach(function(dasar_level_2) {
                                dasar_level_2.get_dasar_to_level_3.forEach(function(dasar_to_level_3) {
                                    dasar_to_level_3.get_dasar_to_level_4.forEach(function(dasar_to_level_4) {
                                        if (dasar_to_level_4.get_pagu_2 && dasar_to_level_4.get_pagu_2.length > 0) {
                                            total_pagu += parseFloat(dasar_to_level_4.get_pagu_2[0].total_pagu) || 0;
                                        }
                                        if (dasar_to_level_4.mandatori_pusat == 1 && !get_data_dasar_pelaksanaan.includes('Mandatori Pusat')) {
                                            get_data_dasar_pelaksanaan.push('Mandatori Pusat');
                                        }
                                        if (dasar_to_level_4.inisiatif_kd == 1 && !get_data_dasar_pelaksanaan.includes('Inisiatif Kepala Daerah')) {
                                            get_data_dasar_pelaksanaan.push('Inisiatif Kepala Daerah');
                                        }
                                        if (dasar_to_level_4.musrembang == 1 && !get_data_dasar_pelaksanaan.includes('MUSREMBANG (Musyawarah Rencana Pembangunan)')) {
                                            get_data_dasar_pelaksanaan.push('MUSREMBANG (Musyawarah Rencana Pembangunan)');
                                        }
                                        if (dasar_to_level_4.pokir == 1 && !get_data_dasar_pelaksanaan.includes('POKIR (Pokok Pikiran)')) {
                                            get_data_dasar_pelaksanaan.push('POKIR (Pokok Pikiran)');
                                        }
                                    });
                                });
                            });

                            if (get_data_dasar_pelaksanaan.length > 0) {
                                label_dasar_pelaksanaan = `<ul><li>${get_data_dasar_pelaksanaan.join('</li><li>')}</li></ul>`;
                            }
                            id_pokin = [];
                            if (value.pokin && value.pokin.length > 0) {
                                label_pokin = `<ul style="margin: 0;">`;
                                value.pokin.forEach(function(get_pokin) {
                                    label_pokin += `<li>${get_pokin.pokin_label}</li>`;
                                    id_pokin.push(+get_pokin.id_pokin);
                                });
                                label_pokin += `</ul>`;
                            }
                            tombol_detail = '' +
                                `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, ` + (tipe + 1) + `, ` + JSON.stringify(id_pokin) + `, '` + id_parent_cascading + `')" title="Lihat Rencana Hasil Kerja"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                        } else if (tipe == 2) {
                            id_parent_cascading = value['kode_cascading_program'];
                            id_parent_sub_skpd_cascading = value['id_sub_skpd_cascading'] != null ? value['id_sub_skpd_cascading'] : 0;

                            if (value['label_cascading_program']) {
                                let nama_prog = value['label_cascading_program'];
                                label_cascading = value['kode_cascading_program'] + ' ' + nama_prog + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + ' | Rp. ' + formatRupiah(value['pagu_cascading']) + '</span>';
                            }
                            total_pagu = 0;
                            value.get_dasar_level_3.forEach(function(dasar_level_3) {
                                dasar_level_3.get_dasar_level_4.forEach(function(dasar) {
                                    if (dasar.get_pagu_3 && dasar.get_pagu_3.length > 0) {
                                        total_pagu += parseFloat(dasar.get_pagu_3[0].total_pagu) || 0;
                                    }
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
                            });

                            if (get_data_dasar_pelaksanaan.length > 0) {
                                label_dasar_pelaksanaan = `<ul><li>${get_data_dasar_pelaksanaan.join('</li><li>')}</li></ul>`;
                            }
                            id_pokin = [];
                            if (value.pokin_3 && value.pokin_3.length > 0) {
                                label_pokin = `<ul style="margin: 0;">`;
                                value.pokin_3.forEach(function(get_pokin_2) {
                                    label_pokin += `<li>${get_pokin_2.pokin_label}</li>`;
                                    id_pokin.push(+get_pokin_2.id_pokin);
                                });
                                label_pokin += `</ul>`;
                            }

                            tombol_detail = '' +
                                `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, ${ (tipe + 1) }, ${ JSON.stringify(id_pokin) }, '${ id_parent_cascading }', ${ id_parent_sub_skpd_cascading })" title="Lihat Uraian Kegiatan Rencana Hasil Kerja"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                        } else if (tipe == 3) {
                            id_parent_cascading = value['kode_cascading_kegiatan'];
                            id_parent_sub_skpd_cascading = value['id_sub_skpd_cascading'] != null ? value['id_sub_skpd_cascading'] : 0;

                            if (value['label_cascading_kegiatan']) {
                                let nama_keg = value['label_cascading_kegiatan'];
                                label_cascading = value['kode_cascading_kegiatan'] + ' ' + nama_keg + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + ' | Rp. ' + formatRupiah(value['pagu_cascading']) + '</span>';
                            }
                            total_pagu = value.total_pagu_4 || 0;

                            value.get_data_dasar_4.forEach(function(dasar_4) {
                                if (dasar_4.mandatori_pusat == 1 && !get_data_dasar_pelaksanaan.includes('Mandatori Pusat')) {
                                    get_data_dasar_pelaksanaan.push('Mandatori Pusat');
                                }
                                if (dasar_4.inisiatif_kd == 1 && !get_data_dasar_pelaksanaan.includes('Inisiatif Kepala Daerah')) {
                                    get_data_dasar_pelaksanaan.push('Inisiatif Kepala Daerah');
                                }
                                if (dasar_4.musrembang == 1 && !get_data_dasar_pelaksanaan.includes('MUSREMBANG (Musyawarah Rencana Pembangunan)')) {
                                    get_data_dasar_pelaksanaan.push('MUSREMBANG (Musyawarah Rencana Pembangunan)');
                                }
                                if (dasar_4.pokir == 1 && !get_data_dasar_pelaksanaan.includes('POKIR (Pokok Pikiran)')) {
                                    get_data_dasar_pelaksanaan.push('POKIR (Pokok Pikiran)');
                                }
                            });

                            if (get_data_dasar_pelaksanaan.length > 0) {
                                label_dasar_pelaksanaan = `<ul><li>${get_data_dasar_pelaksanaan.join('</li><li>')}</li></ul>`;
                            }
                            id_pokin = [];
                            if (value.pokin_4 && value.pokin_4.length > 0) {
                                label_pokin = `<ul style="margin: 0;">`;
                                value.pokin_4.forEach(function(get_pokin_4) {
                                    label_pokin += `<li>${get_pokin_4.pokin_label}</li>`;
                                    id_pokin.push(+get_pokin_4.id_pokin);
                                });
                                label_pokin += `</ul>`;
                            }

                            tombol_detail = '' +
                                `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" onclick="lihat_rencana_aksi(${value.id}, ${ (tipe + 1) }, ${ JSON.stringify(id_pokin) }, '${ id_parent_cascading }', ${id_parent_sub_skpd_cascading})" title="Lihat Uraian Teknis Kegiatan"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `;
                        } else if (tipe == 4) {
                            id_pokin = value['id_pokin_5'];

                            if (value['label_cascading_sub_kegiatan']) {
                                let nama_subkeg = value['label_cascading_sub_kegiatan'].split(" ").slice(1).join(" ");
                                label_cascading = value['kode_cascading_sub_kegiatan'] + ' ' + nama_subkeg + '</br><span class="badge badge-primary p-2 mt-2 text-center text-wrap">' + value['kode_sub_skpd'] + ' ' + value['nama_sub_skpd'] + ' | Rp. ' + formatRupiah(value['pagu_cascading']) + '</span>';
                            }
                            data_tagging_rincian = '<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning" title="Lihat Data Tagging Rincian Belanja"><i class="dashicons dashicons dashicons-arrow-down-alt2"></i></a> ';

                            if (value.mandatori_pusat == 1) get_data_dasar_pelaksanaan.push('Mandatori Pusat');
                            if (value.inisiatif_kd == 1) get_data_dasar_pelaksanaan.push('Inisiatif Kepala Daerah');
                            if (value.musrembang == 1) get_data_dasar_pelaksanaan.push('MUSREMBANG (Musyawarah Rencana Pembangunan)');
                            if (value.pokir == 1) get_data_dasar_pelaksanaan.push('POKIR (Pokok Pikiran)');

                            if (get_data_dasar_pelaksanaan.length > 0) {
                                label_dasar_pelaksanaan = `<ul><li>${get_data_dasar_pelaksanaan.join('</li><li>')}</li></ul>`;
                            }
                            if (value.pokin_5 && value.pokin_5.length > 0) {
                                label_pokin = `<ul style="margin: 0;">`;
                                value.pokin_5.forEach(function(get_pokin_5) {
                                    label_pokin += `<li>${get_pokin_5.pokin_label}</li>`;
                                });
                                label_pokin += `</ul>`;
                            }
                        }

                        renaksi += `` +
                            `<tr id="kegiatan_utama_${value.id}">` +
                            `<td class="text-center">${index + 1}</td>` +
                            `<td class="label_pokin">${label_pokin}</td>` +
                            `<td class="label_renaksi">${value.label}</td>` +
                            `<td class="label_renaksi font-weight-bold">${label_cascading}</td>` +
                            `<td class="label_renaksi">${label_dasar_pelaksanaan}</td>` +
                            `<td class="pegawai_pelaksana font-weight-bold">${nama_pegawai}</td>` +
                            `<td class="text-center">`;
                        // untuk validasi tombol user kepala dan pegawai
                        if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                            renaksi += `<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="tambah_indikator_rencana_aksi(${value.id}, ${tipe},${total_pagu}, '${value.kode_sbl}')" title="Tambah Indikator (Total Pagu: ${formatRupiah(total_pagu)})"><i class="dashicons dashicons-plus"></i></a> `;
                        }
                        renaksi += `` +
                            tombol_detail;
                        if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                            renaksi += `<a href="javascript:void(0)" onclick="edit_rencana_aksi(${value.id}, ` + tipe + `)" data-id="${value.id}" class="btn btn-sm btn-primary edit-kegiatan-utama" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`;
                        }
                        if (hak_akses_pegawai == 1) {
                            renaksi += `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger" onclick="hapus_rencana_aksi(${value.id}, ${tipe})" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`;
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
                                `<th class="text-center" style="width:200px">Aksi</th>` +
                                `</tr>` +
                                `</thead>` +
                                `<tbody>`;

                            indikator.map(function(b, i) {
                                let rencana_pagu = b.rencana_pagu != null ? b.rencana_pagu : 0;
                                let realisasi_pagu = b.realisasi_pagu != null ? b.realisasi_pagu : 0;
                                let val_pagu = '';
                                let html_akun = '';
                                if (tipe == 4) {
                                    html_akun = `<a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="tambah_rincian_belanja_rencana_aksi(${b.id},${value.id},'${value.kode_sbl}')" title="Tambah Rincian Belanja"><i class="dashicons dashicons-plus"></i></a> `;
                                }

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

                                renaksi += '' +
                                    `<tr>` +
                                    `<td class="text-center">${index + 1}.${i + 1}</td>` +
                                    `<td>${text_aspek_rhk}</td>` +
                                    `<td>${b.indikator}</td>` +
                                    `<td class="text-center">${b.satuan}</td>` +
                                    `<td class="text-center">${b.target_awal} ${target_teks_awal}</td>` +
                                    `<td class="text-center">${b.target_akhir} ${target_teks_akhir}</td>` +
                                    `<td class="text-right">${formatRupiah(b.rencana_pagu) || 0}</td>` +
                                    `<td class="text-center">` +
                                    `<input type="checkbox" title="Lihat Rencana Hasil Kerja Per Bulan" class="lihat_bulanan" data-id="${b.id}" onclick="lihat_bulanan(this);" style="margin: 0 6px;">` +
                                    data_tagging_rincian;
                                if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
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
                                    `<h4 class="text-center" style="margin: 10px;">Rumus Indikator</h4>` +
                                    `<textarea class="form-control" id="show-rumus-indikator">${val_rumus_indikator}</textarea>` +
                                    `</div>` +
                                    `</td>` +
                                    `</tr>`;

                                const get_bulan = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                                ];

                                let bulanan = b.bulanan || [];

                                renaksi += '' +
                                    `<tr style="display: none;" class="data_bulanan_${b.id}">` +
                                    `<td colspan="8" style="padding: 10px;">` +
                                    `<h3 class="text-center" style="margin: 10px;">Rencana Aksi Per Bulan</h3>` +
                                    `<table class="table" style="margin: 0;">` +
                                    `<thead>` +
                                    `<tr class="table-secondary">` +
                                    `<th class="text-center">Bulan/TW</th>` +
                                    `<th class="text-center">Rencana Hasil Kerja</th>` +
                                    `<th class="text-center" style="width:100px;">Target</th>` +
                                    `<th class="text-center" style="width:100px;">Satuan</th>` +
                                    `<th class="text-center" style="width:150px;">Realisasi</th>` +
                                    `<th class="text-center" style="width:60px">Capaian</th>` +
                                    `<th class="text-center">Tanggapan Atasan</th>` +
                                    `<th class="text-center" style="width:60px">Aksi</th>` +
                                    `</tr>` +
                                    `</thead>` +
                                    `<tbody>`;

                                get_bulan.forEach((bulan, bulan_index) => {
                                    let get_data_bulanan = b.bulanan.find(bulanan => bulanan.bulan == (bulan_index + 1)) || {};
                                    let isdisabled = <?php echo $set_renaksi == 1 ? 'true' : 'false'; ?>;

                                    renaksi += '' +
                                        `<tr>` +
                                        `<td class="text-center">${bulan}</td>` +
                                        `<td class="text-center"><textarea class="form-control" name="rencana_aksi_${b.id}_${bulan_index + 1}" id="rencana_aksi_${b.id}_${bulan_index + 1}" ${isdisabled ? 'disabled' : ''}>${get_data_bulanan.rencana_aksi || ''}</textarea></td>` +
                                        `<td class="text-center"><input type="text" class="form-control" name="volume_${b.id}_${bulan_index + 1}" id="volume_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.volume || ''}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center"><input type="text" class="form-control" name="satuan_bulan_${b.id}_${bulan_index + 1}" id="satuan_bulan_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.satuan || ''}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center"><input type="number" class="form-control" name="realisasi_${b.id}_${bulan_index + 1}" id="realisasi_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.realisasi || ''}" ${isdisabled ? 'disabled' : ''}></td>` +
                                        `<td class="text-center" name="capaian_${b.id}_${bulan_index + 1}" id="capaian_${b.id}_${bulan_index + 1}" value="${get_data_bulanan.capaian || ''}"></td>` +
                                        `<td class="text-center"><textarea class="form-control" name="keterangan_${b.id}_${bulan_index + 1}" id="keterangan_${b.id}_${bulan_index + 1}" ${isdisabled ? 'disabled' : ''}>${get_data_bulanan.keterangan || ''}</textarea></td>` +
                                        `<td class="text-center">`;
                                    if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                        renaksi += `` +
                                            (isdisabled ?
                                                `-` :
                                                `<a href="javascript:void(0)" data-id="${b.id}" data-bulan="${bulan_index + 1}" class="btn btn-sm btn-success" onclick="simpan_bulanan(${b.id}, ${bulan_index + 1})" title="Simpan"><i class="dashicons dashicons-yes"></i></a>`
                                            );
                                    }
                                    renaksi += `` +
                                        `</td>` +
                                        `</tr>`;

                                    if ((bulan_index + 1) % 3 == 0) {
                                        var triwulan = (bulan_index + 1) / 3;
                                        renaksi += '' +
                                            `<tr style="background: #FDFFB6;">` +
                                            `<td class="text-center">triwulan ${triwulan}</td>` +
                                            `<td class="text-center">${b.indikator}</td>` +
                                            `<td class="text-center">${b['target_' + triwulan]}</td>` +
                                            `<td class="text-center">${b.satuan}</td>` +
                                            `<td class="text-center">` +
                                            `<input type="number" class="form-control" name="realisasi_${b.id}_tw_${triwulan}" id="realisasi_${b.id}_tw_${triwulan}" ${isdisabled ? 'disabled' : ''} value="${b['realisasi_tw_' + triwulan] || ''}">` +
                                            `</td>` +
                                            `<td class="text-center"></td>` +
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

                                renaksi += '' +
                                    `<tr class="table-secondary">` +
                                    `<th class="text-center">Total</th>` +
                                    `<td class="text-center">${b.indikator}</td>` +
                                    `<td class="text-center">${b.target_akhir}</td>` +
                                    `<td class="text-center">${b.satuan}</td>` +
                                    `<td class="text-center"><input type="number" class="form-control" name="realisasi_akhir_${b.id}" id="realisasi_akhir_${b.id}" value="${b['realisasi_akhir'] || ''}"></td>` +
                                    `<td class="text-center"></td>` +
                                    `<td class="text-center"><textarea class="form-control" name="ket_total_${b.id}" id="ket_total_${b.id}">${b['ket_total'] || ''}</textarea></td>` +
                                    `<td class="text-center">`;
                                if (hak_akses_pegawai == 1 || (hak_akses_pegawai == 2 && value.detail_pegawai && value.detail_pegawai.nip_baru && nip_pegawai == value.detail_pegawai.nip_baru)) {
                                    renaksi += `` +
                                        `<a href="javascript:void(0)" data-id="${b.id}" class="btn btn-sm btn-success" onclick="simpan_total(${b.id})" title="Simpan Total"><i class="dashicons dashicons-yes"></i></a>`;
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

    function detail_parent(tipe_parent) {
        jQuery('.nav-tabs a[href="#nav-level-' + tipe_parent + '"]').tab('show');
    }

    function tambah_renaksi_2(tipe, isEdit = false) {
        let jenis = '';
        let parent_cascading = '';
        let jenis_cascading = '';
        let id_sub_skpd_cascading = 0;

        switch (tipe) {
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

            default:
                jenis = "program";
                jenis_cascading = "Program";
                parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
                id_sub_skpd_cascading = jQuery('#tabel_rencana_aksi').attr('parent_sub_skpd');
                break;
        }

        return get_tujuan_sasaran_cascading(jenis, parent_cascading, id_sub_skpd_cascading)
            .then(function() {
                return new Promise(function(resolve, reject) {
                    var parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                    var parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                    var level_pokin = 3;
                    var title = 'Rencana Hasil Kerja | RHK Level 2';
                    var key = jenis + '-' + parent_cascading;
                    if (id_sub_skpd_cascading != 0) {
                        key = jenis + '-' + parent_cascading + '-' + id_sub_skpd_cascading;
                    }
                    let data_cascading = data_program_cascading[key];

                    var key = jenis + '-' + parent_cascading;
                    if (id_sub_skpd_cascading != 0) {
                        key = jenis + '-' + parent_cascading + '-' + id_sub_skpd_cascading;
                    }
                    let get_renaksi_pemda = <?php echo json_encode($renaksi_pemda); ?>;
                    let checklist_renaksi_pemda = '';
                    let checklist_dasar_pelaksanaan = '';

                    if (tipe == 3) {
                        level_pokin = 4;
                        title = 'Uraian Rencana Hasil Kerja | RHK Level 3';
                        parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                        parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                        data_cascading = data_kegiatan_cascading[key];
                    } else if (tipe == 4) {
                        level_pokin = 5;
                        title = 'Uraian Teknis Kegiatan | RHK Level 4';
                        parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                        parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                        data_cascading = data_sub_kegiatan_cascading[key];
                    }
                    parent_pokin = parent_pokin.split(",");
                    if (!isEdit && tipe === 4) {
                        checklist_dasar_pelaksanaan = `
                    <div class="form-group">
                        <label>Pilih Dasar Pelaksanaan</label>
                        <div>
                            <label><input type="checkbox" name="dasar_pelaksanaan[]" value="0" id="mandatori_pusat"> Mandatori Pusat</label><br>
                            <label><input type="checkbox" name="dasar_pelaksanaan[]" value="0" id="inisiatif_kd"> Inisiatif Kepala Daerah</label><br>
                            <label><input type="checkbox" name="dasar_pelaksanaan[]" value="0" id="musrembang"> MUSREMBANG (Musyawarh Rencana Pembangunan)</label><br>
                            <label><input type="checkbox" name="dasar_pelaksanaan[]" value="0" id="pokir"> Pokir (Pokok Pikiran)</label>
                        </div>
                    </div>
                `;
                    }

                    if (!isEdit && tipe === 2) {
                        checklist_renaksi_pemda += `

                    <label>Rencana Hasil Kerja Pemerintah Daerah | Level 4</label>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" id="select_all"></th>
                                <th>Rencana Hasil Kerja</th>
                                <th>Indikator Rencana Hasil Kerja</th>
                                <th>Satuan</th>
                                <th>Target Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                        get_renaksi_pemda.forEach(function(item, index) {
                            checklist_renaksi_pemda += `
                        <tr>
                            <td><input class="text-right" type="checkbox" class="form-check-input" id="label_renaksi_pemda${index}"name="checklist_renaksi_pemda[]" value="${item.label}" id_label_renaksi_pemda="${item.id_data_renaksi_pemda}"id_label_indikator_renaksi_pemda="${item.id_data_indikator}"></td>
                            <td for="label_renaksi_pemda${index}">${item.label}</td>
                            <td for="label_renaksi_pemda${index}">${item.indikator}</td>
                            <td class="text-center" for="label_renaksi_pemda${index}">${item.satuan}</td>
                            <td class="text-center" for="label_renaksi_pemda${index}">${item.target_akhir}</td>
                        </tr>
                    `;
                        });
                        checklist_renaksi_pemda += '</tbody></table>';
                    }

                    var option = {
                        "action": "get_data_pokin",
                        "level": level_pokin,
                        "parent": [],
                        "api_key": esakip.api_key,
                        "tipe_pokin": "opd",
                        "id_jadwal": id_jadwal,
                        "id_skpd": <?php echo $id_skpd; ?>
                    };
                    parent_pokin.map(function(b, i) {
                        option.parent.push(b);
                    })
                    jQuery('#wrap-loading').show();
                    jQuery.ajax({
                        url: esakip.url,
                        type: "post",
                        data: option,
                        dataType: "json",
                        success: function(res) {
                            let html = '';

                            if (Array.isArray(res.data)) {
                                res.data.map(value => {
                                    html += '<option value="' + value.id + '">' + value.label + '</option>';
                                });
                            } else {
                                alert("Data Pokin Kosong atau Tidak Sesuai!");
                            }
                            var get_pegawai = <?php echo json_encode($select_pegawai); ?>;
                            jQuery('#wrap-loading').hide();
                            jQuery("#modal-crud").find('.modal-title').html('Tambah ' + title);

                            jQuery("#modal-crud").find('.modal-body').html(`
                                <form>
                                    <input type="hidden" id="id_renaksi" value=""/>
                                    <div class="form-group">
                                        <label for="pokin-level-1">Pilih Pokin Level ${level_pokin}</label>
                                        <select class="form-control" multiple name="pokin-level-1" id="pokin-level-1">
                                            ${html}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="label" id="label_renaksi" placeholder="Tuliskan ${title}..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="cascading-renstra">Pilih ${jenis_cascading} Cascading</label>
                                        <select class="form-control" name="cascading-renstra" id="cascading-renstra"></select>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="sub-skpd-cascading">OPD Cascading</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" id="sub-skpd-cascading" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
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
                                    <?php if (!empty($renaksi_pemda)): ?>
                                        ${checklist_renaksi_pemda}  
                                    <?php endif; ?>
                                    ${checklist_dasar_pelaksanaan}  
                                </form>
                            `);

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
                            if (tipe === 3) {
                                jQuery('#pokin-level-2').select2({
                                    width: '100%'
                                });
                            }
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
                                            case 3:
                                                html_cascading += `<option value="${value.kode_giat}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${value.kode_giat} ${value.nama_giat} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )</option>`;
                                                break;
                                            case 4:
                                                let nama_sub_giat = `${value.kode_sub_giat} ${value.nama_sub_giat.replace(value.kode_sub_giat, '')} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )`;
                                                html_cascading += `<option data-kodesbl="${value.kode_sbl}" value="${value.kode_sub_giat}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${nama_sub_giat}</option>`;
                                                break;
                                            default:
                                                html_cascading += `<option value="${value.kode_program}_${value.id_sub_skpd}" data-id-sub-skpd-cascading="${value.id_sub_skpd}" data-nama-sub-skpd="${value.kode_sub_skpd} ${value.nama_sub_skpd}" data-pagu-cascading="${value.pagu}">${value.kode_program} ${value.nama_program} ( ${value.kode_sub_skpd} ${value.nama_sub_skpd} | Rp. ${formatRupiah(value.pagu)} )</option>`;
                                                break;
                                        }
                                    }
                                });
                                jQuery("#cascading-renstra").html(html_cascading);
                                jQuery('#cascading-renstra').select2({
                                    width: '100%'
                                });
                            } else {
                                alert("Data Cascading Kosong!");
                            }


                            resolve();
                        },
                        error: function() {
                            jQuery('#wrap-loading').hide();
                            alert("Gagal memuat data Pokin.");
                            reject();
                        }
                    });
                });
            });
    }

    jQuery(document).on('change', '#cascading-renstra', function() {
        let nama_sub_skpd_cascading = jQuery('#cascading-renstra option:selected').data('nama-sub-skpd');
        let pagu_cascading = jQuery('#cascading-renstra option:selected').data('pagu-cascading');
        if (nama_sub_skpd_cascading) {
            jQuery('#sub-skpd-cascading').val(nama_sub_skpd_cascading)
        }
        if (pagu_cascading) {
            jQuery('#pagu-cascading').val(formatRupiah(pagu_cascading))
        }
    });

    function simpan_data_renaksi(tipe) {
        var parent_pokin = 0;
        var parent_renaksi = 0;
        var parent_cascading = 0;
        var parent_sub_skpd = 0;
        switch (tipe) {
            case 2:
                parent_pokin = jQuery('#tabel_rencana_aksi').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_rencana_aksi').attr('parent_renaksi');
                parent_cascading = jQuery('#tabel_rencana_aksi').attr('parent_cascading');
                break;
            case 3:
                parent_pokin = jQuery('#tabel_uraian_rencana_aksi').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_rencana_aksi').attr('parent_renaksi');
                parent_cascading = jQuery('#tabel_uraian_rencana_aksi').attr('parent_cascading');
                parent_sub_skpd = jQuery('#tabel_uraian_rencana_aksi').attr('parent_sub_skpd');
                break;
            case 4:
                parent_pokin = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_pokin');
                parent_renaksi = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_renaksi');
                parent_cascading = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_cascading');
                parent_sub_skpd = jQuery('#tabel_uraian_teknis_kegiatan').attr('parent_sub_skpd');
                break;
            default:
                parent_pokin = 0;
                parent_renaksi = 0;
                parent_cascading = 0;
                parent_sub_skpd = 0;
        }

        var id_pokin_1 = jQuery('#pokin-level-1').val();
        var id_pokin_2 = jQuery('#pokin-level-2').val();
        var label_pokin_1 = jQuery('#pokin-level-1 option:selected').text();
        var label_pokin_2 = jQuery('#pokin-level-2 option:selected').text();
        var label_renaksi = jQuery('#label_renaksi').val();
        var kode_cascading_renstra = '';
        if (tipe == 2) {
            kode_cascading_renstra = jQuery('#cascading-renstra').val();
            kode_sementara = kode_cascading_renstra.split("_");
            kode_cascading_renstra = kode_sementara[0];
        } else {
            kode_cascading_renstra = jQuery('#cascading-renstra').val();
        }
        var label_cascading_renstra = '';
        if (kode_cascading_renstra !== '') {
            label_cascading_renstra = jQuery('#cascading-renstra option:selected').text();
            let new_label = label_cascading_renstra.split('(');
            if (Array.isArray(new_label)) {
                label_cascading_renstra = new_label[0].trim();
            }
        }
        var kode_sbl = '';
        var id_sub_skpd_cascading = 0;
        var pagu_cascading = 0;
        if (tipe != 1) {
            id_sub_skpd_cascading = jQuery('#cascading-renstra option:selected').data('id-sub-skpd-cascading');
            pagu_cascading = jQuery('#cascading-renstra option:selected').data('pagu-cascading');
        }
        if (tipe == 4) {
            kode_sbl = jQuery('#cascading-renstra option:selected').data('kodesbl');
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
        if (id_pokin_1 == '' && tipe == 1) {
            return alert('Level 1 pohon kinerja tidak boleh kosong!');
        } else if (id_pokin_2 == '' && tipe == 1) {
            return alert('Level 2 pohon kinerja tidak boleh kosong!');
        } else if (id_pokin_1 == '' && tipe == 2) {
            return alert('Level 3 pohon kinerja tidak boleh kosong!');
        } else if (id_pokin_1 == '' && tipe == 3) {
            return alert('Level 4 pohon kinerja tidak boleh kosong!');
        } else if (id_pokin_1 == '' && tipe == 4) {
            return alert('Level 5 pohon kinerja tidak boleh kosong!');
        }

        var selectedChecklistPemda = jQuery('input[name="checklist_renaksi_pemda[]"]:checked');
        var checklistDataPemda = [];
        selectedChecklistPemda.each(function() {
            var row = jQuery(this).closest('tr');
            checklistDataPemda.push({
                id_data_renaksi_pemda: jQuery(this).attr('id_label_renaksi_pemda'),
                id_data_indikator: jQuery(this).attr('id_label_indikator_renaksi_pemda')
            });
        });

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
        let nip = jQuery('#pegawai').val(); 
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
                "id_pokin_2": id_pokin_2,
                "label_pokin_1": label_pokin_1,
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
                "id_sub_skpd_cascading": id_sub_skpd_cascading,
                "pagu_cascading": pagu_cascading
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


    function tambah_rincian_belanja_rencana_aksi(id_indikator, id_uraian_teknis_kegiatan, kode_sbl) {
        if (kode_sbl == '' || kode_sbl == 'null') {
            alert("Harap perbarui data Uraian Teknis Kegiatan\nCukup \"Edit\" lalu \"Simpan\" jika tidak ada perubahan.")
            return;
        }
        get_data_rekening_akun_wp_sipd(kode_sbl)
            .then(function() {
                jQuery("#modal-crud").find('.modal-title').html('Tambah Tagging Rincian Belanja');
                jQuery("#modal-crud").find('.modal-body').html('' +
                    '<form id="form-tagging-renaksi">' +
                    '<div class="form-group row">' +
                    '<label class="d-block col-sm-3">Jenis Tagging</label>' +
                    '<div class="col-sm-9">' +
                    '<label for="rka_dpa"><input onclick="set_jenis_tagging(this.value)" type="radio" class="ml-2 jenis_tagging" id="rka_dpa" name="jenis_tagging" value="rka_dpa" checked> Rincian Belanja RKA/DPA</label>' +
                    '<label style="margin-left: 30px;" for="manual"><input onclick="set_jenis_tagging(this.value)" type="radio" class="jenis_tagging" id="manual" name="jenis_tagging" value="manual"> Rincian Belanja Manual</label>' +
                    '</div>' +
                    '</div>'
                    // +'<div class="form-group row">'
                    //     +'<div class="col-md-3">'
                    //         +'<label for="label_rekening_belanja">Rekening Belanja</label>'
                    //     +'</div>'
                    //     +'<div class="col-md-9" id="html_rekening_akun">'
                    //         // +'<select class="form-control" name="rekening_akun" id="rekening_akun" onchange="get_data_rincian_belanja(this.value,\''+ kode_sbl +'\')">'
                    //         //     +'<option value="">Pilih Rekening</option>'
                    //         // +'</select>'
                    //     +'</div>'
                    //     +'<div class="col-md-10" id="form_rincian_belanja">'
                    //     +'</div>'
                    // +'</div>'
                    // +'<div class="form-group set_manual" style="display: none;" id="form_input_rincian_manual">'
                    //     +'<div class="form-group row">'
                    //         +'<div class="col-md-3">'
                    //             +'<label for="uraian_tagging_manual">Uraian Tagging</label>'
                    //         +'</div>'
                    //         +'<div class="col-md-9">'
                    //             +'<input type="text" class="form-control" id="uraian_tagging_manual" name="uraian_tagging_manual" required>'
                    //         +'</div>'
                    //     +'</div>'
                    //     +'<div class="form-group row">'
                    //         +'<div class="col-md-3">'
                    //             +'<label for="volume_satuan_tagging">Volume Satuan Tagging</label>'
                    //         +'</div>'
                    //         +'<div class="col-md-9">'
                    //             +'<input type="text" class="form-control" id="volume_satuan_tagging" name="volume_satuan_tagging" required>'
                    //         +'</div>'
                    //     +'</div>'
                    //     +'<div class="form-group row">'
                    //         +'<div class="col-md-3">'
                    //             +'<label for="nilai_tagging">Nilai Tagging</label>'
                    //         +'</div>'
                    //         +'<div class="col-md-9">'
                    //             +'<input type="number" class="form-control" id="nilai_tagging" name="nilai_tagging" required>'
                    //         +'</div>'
                    //     +'</div>'
                    // +'</div>'
                    +
                    '<div class="form-group" id="html_rekening_akun">' +
                    '</div>' +
                    '<div class="form-group set_rka_dpa" id="form_input_rka_dpa">' +
                    '</div>' +
                    '</form>');
                jQuery("#modal-crud").find('.modal-footer').html('' +
                    '<button type="button" class="btn btn-danger" data-dismiss="modal">' +
                    'Tutup' +
                    '</button>' +
                    '<button type="button" class="btn btn-success" onclick="simpan_rincian_belanja_renaksi(' + id_indikator + ',' + id_uraian_teknis_kegiatan + ',\'' + kode_sbl + '\')" data-view="kegiatanUtama">' +
                    'Simpan' +
                    '</button>');
                jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '1400px');
                jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                jQuery("#modal-crud").modal('show');

                if (data_rekening_akun[kode_sbl] != undefined) {
                    let html_rekening_belanja = '';
                    let no = 0;
                    html_rekening_belanja += `` +
                        `<table class="table" style="margin: 0 0 2rem;">` +
                        `<thead>` +
                        `<tr class="table-secondary">` +
                        `<th class="text-center" style="width:20px">` +
                        `<div class="form-check">` +
                        `<input class="form-check-input" type="checkbox" name="input_rekening_belanja_all" id="rekening-belanja-all" onchange="check_all_rekening();">` +
                        `</div>` +
                        `</th>` +
                        `<th class="text-center">Kode Rekening</th>` +
                        `<th class="text-center">Nama Rekening</th>` +
                        `<th class="text-center">Nilai Rincian</th>` +
                        `<th class="text-center">Nilai Tertagging</th>` +
                        `<th class="text-center set_manual" style="display: none;">Uraian Tagging</th>` +
                        `<th class="text-center set_manual" style="display: none;">Volume Satuan Tagging</th>` +
                        `<th class="text-center set_manual" style="display: none;">Nilai Tagging</th>`
                        // +`<th class="text-center">Aksi</th>`
                        +
                        `</tr>` +
                        `</thead>` +
                        `<tbody>`;
                    for (var i in data_rekening_akun[kode_sbl].akun) {
                        var b = data_rekening_akun[kode_sbl].akun[i];
                        number = no++;
                        html_rekening_belanja += `` +
                            `<tr>` +
                            `<td>` +
                            `<div class="form-check">` +
                            `<input class="form-check-input input_checkbox_rekening" type="checkbox" name="input_rekening_belanja_${number}" value="${b.kode_akun}" id="rekening-belanja-${number}" number="${number}">` +
                            `</div>` +
                            `</td>` +
                            `<td>` +
                            `<div class="form-group">` +
                            b.kode_akun +
                            `<input type="hidden" class="form-control" id="kode-rekening-${number}" name="kode_rekening_${number}" required disabled value="${b.kode_akun}">` +
                            `</div>` +
                            `</td>` +
                            `<td>` +
                            `<div class="form-group">` +
                            b.nama_akun.replace(b.kode_akun + ' ', '') +
                            `<textarea class="form-control hide" rows="2" cols="50" id="nama-rekening-${number}" required disabled>` +
                            `${b.nama_akun.replace(b.kode_akun+' ', '')}` +
                            `</textarea>` +
                            `</div>` +
                            `</td>` +
                            `<td>` +
                            `<div class="form-group">` +
                            b.total +
                            `<input type="hidden" class="form-control text-right" id="nilai-rincian-${number}" required disabled value="${b.total}">` +
                            `</div>` +
                            `</td>` +
                            `<td>` +
                            `<div class="form-group">` +
                            `<input type="text" class="form-control text-right" id="nilai-tertaggin-${number}" value="0" required disabled>` +
                            `</div>` +
                            `</td>` +
                            `<td class="set_manual" style="display: none;">` +
                            `<div class="form-group">` +
                            `<textarea class="form-control" rows="2" cols="50" id="uraian-tagging-${number}" name="uraian_tagging_${number}" required></textarea>` +
                            `</div>` +
                            `</td>` +
                            `<td class="set_manual" style="display: none;">` +
                            `<div class="form-group">` +
                            `<input type="text" class="form-control" id="volume-satuan-tagging-${number}" name="volume_satuan_tagging_${number}" required>` +
                            `</div>` +
                            `</td>` +
                            `<td class="set_manual" style="display: none;">` +
                            `<div class="form-group">` +
                            `<input type="number" class="form-control" id="nilai-tagging-${number}" name="nilai_tagging_${number}" required>` +
                            `</div>` +
                            `</td>`
                            // +`<td>`
                            //     +`<div class="form-group">`
                            //         // +`<input type="text" class="form-control" id="aksi-${number}" name="nilai_rincian" value="0" required disabled>`
                            //     +`</div>`
                            // +`</td>`
                            +
                            `</tr>`;
                    };
                    html_rekening_belanja += `` +
                        `</tbody>` +
                        `</table>`
                    jQuery('#html_rekening_akun').html(html_rekening_belanja);
                } else {
                    alert("Data Rekening Akun Kosong!")
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

    function get_pegawai_rhk() {
        var satker_id = jQuery("#satker_id").val();

        jQuery("#pegawai").empty();
        jQuery("#pegawai").append('<option value="">Pilih Pegawai</option>');
        jQuery("#pegawai").val('').trigger("change");
        jQuery('#wrap-loading').show();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_pegawai_rhk',
                api_key: esakip.api_key,
                satker_id: satker_id,
            },
            success: function(response) {
                jQuery('#wrap-loading').hide();

                if (response.status === 'success') {
                    response.data.forEach(item => {
                        jQuery("#pegawai").append(
                            `<option value="${item.nip_baru}">${item.nama}</option>`
                        );
                    });

                    jQuery("#pegawai").select2({
                        width: '100%',
                        dropdownParent: jQuery('#modal-crud'),
                        placeholder: 'Pilih Pegawai',
                        allowClear: true
                    });
                } else {
                    alert('Data pegawai tidak ditemukan.');
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('Gagal mengambil data:', error);
                alert('Terjadi kesalahan saat mengambil data pegawai.');
            }
        });
    }
</script>