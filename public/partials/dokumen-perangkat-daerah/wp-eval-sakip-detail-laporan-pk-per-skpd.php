<?php
if (!defined('WPINC')) {
    die;
}
$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

$id_skpd = $_GET['id_skpd'] ?? '';
$id_satker_simpeg = $_GET['satker_id'] ?? '';
$nip = $_GET['nip'] ?? '';
$id_jabatan = $_GET['id_jabatan'] ?? '';
$id_pegawai = $_GET['id_pegawai'] ?? '';

if (empty($id_skpd) || empty($nip) || empty($id_satker_simpeg) || empty($input['tahun']) || empty($id_pegawai)) {
    die('parameter tidak lengkap!.');
}
global $wpdb;

$skpd = $wpdb->get_row(
    $wpdb->prepare("
        SELECT 
            u.nama_skpd,
            d.alamat_kantor
        FROM esakip_data_unit u
        LEFT JOIN esakip_detail_data_unit d
               ON d.id_skpd = u.id_skpd
        WHERE u.id_skpd=%d
          AND u.tahun_anggaran=%d
          AND u.active = 1
    ", $id_skpd, $input['tahun']),
    ARRAY_A
);

$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
$pemda = explode(" ", $nama_pemda);
array_shift($pemda);
$pemda = implode(" ", $pemda);
$logo_pemda = get_option('_crb_logo_dashboard');
if (empty($logo_pemda)) {
    $logo_pemda = '';
}

$text_tanggal_hari_ini = $this->format_tanggal_indo(current_datetime()->format('Y-m-d'));

// $error_message = array();
//get data simpeg pihak pertama
// $simpeg_pihak_pertama = $this->get_pegawai_simpeg('asn', $data_satker['nip_baru'], $data_satker['satker_id'], $data_satker['jabatan']);
// $response_1 = json_decode($simpeg_pihak_pertama, true);
// if (!isset($response_1['status']) || $response_1['status'] === false) {
//     array_push($error_message, 'Pihak Pertama : ' . $response_1['message']);
// }

$pihak_pertama = array(
    'nama_pegawai'    => '-',
    'nip_pegawai'     => '-',
    'jabatan_pegawai' => '-',
    'nama_bidang'     => '-',
    'pangkat'         => '',
    'gelar_depan'     => '',
    'gelar_belakang'  => '',
    'status_jabatan'  => ''
);
if (!empty($_GET['inactive']) && $_GET['inactive'] == 1) {
    $data_pegawai_1 = $this->get_data_pegawai_simpeg_inactive_by_id($id_pegawai);
} else {
    $data_pegawai_1 = $this->get_data_pegawai_simpeg_by_id($id_pegawai);
}
if (!empty($data_pegawai_1)) {
    $pihak_pertama['nama_pegawai']    = $data_pegawai_1->nama_pegawai;
    $pihak_pertama['nip_pegawai']     = $data_pegawai_1->nip_baru;
    $pihak_pertama['jabatan_pegawai'] = $data_pegawai_1->jabatan . ' ' . $data_pegawai_1->nama_bidang ?: '-';
    $pihak_pertama['nama_bidang']     = $data_pegawai_1->nama_bidang;
    $pihak_pertama['pangkat']         = $data_pegawai_1->pangkat;
    $pihak_pertama['gelar_depan']     = $data_pegawai_1->gelar_depan;
    $pihak_pertama['gelar_belakang']  = ', ' . $data_pegawai_1->gelar_belakang;
    $pihak_pertama['status_jabatan']  = $data_pegawai_1->jabatan;

    //jika custom jabatan ada, gunakan itu
    if (!empty($data_pegawai_1->custom_jabatan)) {
        $pihak_pertama['jabatan_pegawai'] = $data_pegawai_1->custom_jabatan;
    }
    if ($data_pegawai_1->plt_plh == 1 && !empty($data_pegawai_1->plt_plh_teks)) {
        $pihak_pertama['jabatan_pegawai'] = $data_pegawai_1->plt_plh_teks . ' ' . $pihak_pertama['jabatan_pegawai'];
    }

    $data_atasan = $this->_get_atasan_definitif($data_pegawai_1);
    if (empty($data_atasan) && !empty($data_pegawai_1->id_atasan)) {
        $data_atasan = $this->get_data_pegawai_simpeg_by_id($data_pegawai_1->id_atasan);
    }

    $options = array(
        'id_skpd'   => $id_skpd,
        'satker_id' => $data_pegawai_1->satker_id,
        'tahun'     => $input['tahun'],
        'nip_baru'  => $data_pegawai_1->nip_baru
    );
    $html_pk = $this->get_pk_html($options);
}

$pihak_kedua = array(
    'nama_pegawai'    => '-',
    'nip_pegawai'     => '-',
    'jabatan_pegawai' => '-',
    'nama_bidang'     => '-',
    'pangkat'         => '',
    'gelar_depan'     => '',
    'gelar_belakang'  => '',
    'status_jabatan'  => ''
);

if (!empty($data_atasan) && $data_atasan->id != 0) {
    $pihak_kedua['nama_pegawai']    = $data_atasan->nama_pegawai;
    $pihak_kedua['nip_pegawai']     = $data_atasan->nip_baru;
    $pihak_kedua['jabatan_pegawai'] = $data_atasan->jabatan . ' ' . $data_atasan->nama_bidang ?: '-';
    $pihak_kedua['nama_bidang']     = $data_atasan->nama_bidang;
    $pihak_kedua['pangkat']         = $data_atasan->pangkat;
    $pihak_kedua['gelar_depan']     = $data_atasan->gelar_depan;
    $pihak_kedua['gelar_belakang']  = ', ' . $data_atasan->gelar_belakang;
    $pihak_kedua['status_jabatan']  = $data_atasan->jabatan;

    //jika custom jabatan ada, gunakan itu
    if (!empty($data_atasan->custom_jabatan)) {
        $pihak_kedua['jabatan_pegawai'] = $data_atasan->custom_jabatan;
    }
    if ($data_atasan->plt_plh == 1 && !empty($data_atasan->plt_plh_teks)) {
        $pihak_kedua['jabatan_pegawai'] = $data_atasan->plt_plh_teks . ' ' . $pihak_kedua['jabatan_pegawai'];
    }
} elseif (!empty($data_atasan) && $data_atasan->id == 0) {
    $pihak_kedua['nama_pegawai']    = $data_atasan->nama_pegawai;
    $pihak_kedua['nip_pegawai']     = $data_atasan->nip_baru;
    $pihak_kedua['jabatan_pegawai'] = $data_atasan->jabatan;
}

if (empty($data_atasan)) {
    die('Pihak Kedua tidak ditemukan, mohon sinkron data / atur atasan di halaman Pengaturan Perjanjian Kinerja');
}
$date_hari_ini = current_datetime()->format('Y-m-d H:i:s');

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$user_nip = $current_user->data->user_login;
$is_administrator = in_array('administrator', $user_roles);
$is_admin_panrb = in_array('admin_panrb', $user_roles);

$admin_role_pemda = array(
    'admin_bappeda',
    'admin_ortala'
);

$this_admin_pemda = (array_intersect($admin_role_pemda, $user_roles)) ? 1 : 2;

// hak akses user pegawai
$data_user_pegawai = $wpdb->get_results(
    $wpdb->prepare("
        SELECT
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
        ORDER BY satker_id ASC, tipe_pegawai_id ASC
    ", $user_nip, 1),
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
                $input['tahun'],
                1,
                $input['tahun'],
                1
            ),
            ARRAY_A
        );

        // TIPE HAK AKSES USER PEGAWAI | 0 = TIDAK ADA | 1 = ALL | 2 = HANYA RHK TERKAIT
        if (!empty($skpd_user_pegawai)) {
            if (($skpd_user_pegawai['id_skpd'] == $id_skpd && $v_user['tipe_pegawai_id'] == 11 && strlen($v_user['satker_id']) == 2) || $is_administrator) {
                $hak_akses_user_pegawai = 1;
            } else if ($skpd_user_pegawai['id_skpd'] == $id_skpd) {
                $hak_akses_user_pegawai = 2;
            }
            $nip_user_pegawai = $v_user['nip_baru'];
            if (empty($hak_akses_user_pegawai_per_skpd[$skpd_user_pegawai['id_skpd']])) {
                $hak_akses_user_pegawai_per_skpd[$skpd_user_pegawai['id_skpd']] = $hak_akses_user_pegawai;
            }
        }
    }
}

if ($is_administrator || $this_admin_pemda == 1) {
    $hak_akses_user_pegawai = 1;
    $nip_user_pegawai = 0;
} else {
    // ----- hak akses by skpd terkait ----- //
    $hak_akses_user_pegawai = $hak_akses_user_pegawai_per_skpd[$id_skpd];
}

//////// end setting hak akses ////////

$data_tahapan = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            *
        FROM esakip_finalisasi_tahap_laporan_pk
        WHERE nip = %d
          AND tahun_anggaran = %d
          AND active = 1
        ORDER BY updated_at DESC
    ", $pihak_pertama['nip_pegawai'], $input['tahun']),
    ARRAY_A
);
$card = '';
$jumlah_data = array();
if ($data_tahapan) {
    foreach ($data_tahapan as $v) {
        $tanggal_dokumen = $this->format_tanggal_indo($v['tanggal_dokumen']);
        $data_skpd = $this->get_data_skpd_by_id($v['id_skpd'], $v['tahun_anggaran']) ?? '-';

        //count jumlah data per skpd
        if (!isset($jumlah_data[$v['id_skpd']])) {
            $jumlah_data[$v['id_skpd']] = [
                'nama_skpd' => $data_skpd['nama_skpd'],
                'jumlah' => 0
            ];
        }

        $jumlah_data[$v['id_skpd']]['jumlah']++;

        $card .= '
        <div class="cr-item" id="card-tahap-' . $v['id'] . '" title="' . $v['nama_tahapan'] . '">
            <div class="cr-card">
                <h3 class="truncate-multiline" id="nama-tahapan-' . $v['id'] . '">' . $v['nama_tahapan'] . '</h3>
                <div class="badge badge-sm badge-primary m-0 ml-2 mr-2 text-light text-wrap">' . $data_skpd['nama_skpd'] .
            '</div>
                <div class="year" id="tanggal-tahapan-' . $v['id'] . '">' . $tanggal_dokumen . '</div>
                <div class="cr-actions">
                    <div class="cr-view-btn" id="view-btn-' . $v['id'] . '" onclick="viewDokumen(\'' . $v['id'] . '\', this)" title="Lihat Dokumen">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>';
        if ($hak_akses_user_pegawai == 1 || ($hak_akses_user_pegawai == 2 && $pihak_pertama && $pihak_pertama['nip_pegawai'] == $nip_user_pegawai)) {
            $card .= '
                        <div class="cr-view-btn-danger" onclick="deleteDokumen(\'' . $v['id'] . '\')" title="Hapus Dokumen">
                            <span class="dashicons dashicons-trash"></span>
                        </div>';
        }

        $card .= '
                </div>
                <div class="badge-container">
                    <span class="badge badge-sm badge-warning badge-sedang-dilihat" id="badge-sedang-dilihat-' . $v['id'] . '" style="display:none">
                        Sedang Dilihat
                    </span>
                </div>
            </div>
        </div>';
    }
}

$ttd_orientasi = 'text-left';
// $ttd_orientasi = 'text-center';
?>

<style>
    body {
        font-size: 16px;
        line-height: 24px;
    }

    @media print {
        .page-print {
            max-width: 900px !important;
            height: auto !important;
            margin: 0 auto;
            /* font-size: 12pt; */
        }

        /*.f-12 {
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }*/

        @page {
            size: portrait;
        }

        #action-sakip,
        .site-header,
        .site-footer,
        .hide-display-print {
            display: none;
        }

        .break-print {
            break-after: page;
        }

        td[contenteditable="true"] {
            background: none !important;
        }
    }

    #action-sakip {
        padding-top: 20px;
    }

    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

    #table_dokumen_perjanjian_kinerja th {
        vertical-align: middle;
    }

    .page-print {
        font-family: Arial, Helvetica, sans-serif;
        margin-right: auto;
        margin-left: auto;
        background-color: var(--white-color);
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15)
    }

    .page-print p {
        margin: 0pt;
    }

    .page-print table,
    td {
        border: none;
    }

    #table-1 tr td:first-child {
        padding-left: 0;
    }

    #table-1 td:nth-child(1) {
        width: 130px;
    }

    #table-1 td:nth-child(2) {
        width: 0%;
    }

    tr,
    td {
        vertical-align: top;
    }

    .ttd-pejabat {
        padding: 0;
        font-weight: 700;
        text-decoration: underline;
        width: 50%;
    }

    .title-laporan {
        font-weight: 700;
        font-size: 16pt;
    }

    .title-pk-1 {
        font-size: 19px;
    }

    .title-pk-2 {
        font-size: 21px;
        font-weight: 700;
    }

    .table_data_anggaran tr,
    .table_data_anggaran td,
    .table_data_anggaran th {
        border: solid 1px #000;
    }

    .table_data_anggaran tr td:first-child {
        width: 3rem;
    }

    td[contenteditable="true"] {
        background: #ff00002e;
    }

    /* carousel */
    .cr-container {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    .cr-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 24px;
        color: #23282d;
        padding-left: 10px;
    }

    .cr-carousel-wrapper {
        position: relative;
        padding: 0 10px;
    }

    .cr-carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 20px;
        padding: 10px 0;
    }

    .cr-carousel::-webkit-scrollbar {
        display: none;
    }

    .cr-item {
        flex: 0 0 calc(25% - 15px);
        scroll-snap-align: start;
    }

    .cr-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #dcdcde;
        border-radius: 8px;
        padding: 20px;
        width: 250px;
        /* Atur ukuran card */
        height: 220px;
        /* Atur tinggi card */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .cr-card h3 {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin: 0;
        word-wrap: break-word;
        /* Menghindari teks keluar dari batas */
    }

    .cr-card .year {
        font-size: 14px;
        color: #666;
        margin: 4px 0;
    }

    .cr-actions {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .cr-card .cr-view-btn,
    .cr-card .cr-view-btn-danger {
        background-color: #fff;
        border: 1px solid #dcdcde;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cr-card .cr-view-btn:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-card .cr-view-btn .dashicons {
        font-size: 18px;
        color: #007cba;
    }

    .cr-card .cr-view-btn-danger:hover {
        border-color: #ff686b;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-card .cr-view-btn-danger .dashicons {
        font-size: 18px;
        color: #ff686b;
    }

    .badge-container {
        text-align: center;
    }


    .cr-card:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: #fff;
        border: 1px solid #dcdcde;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cr-scroll-btn:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-scroll-btn-left {
        left: -8px;
    }

    .cr-scroll-btn-right {
        right: -8px;
    }

    .truncate-multiline {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 1024px) {
        .cr-item {
            flex: 0 0 calc(33.333% - 14px);
        }
    }

    @media (max-width: 768px) {
        .cr-item {
            flex: 0 0 calc(33.333% - 10px);
        }
    }

    @media (max-width: 480px) {
        .cr-item {
            flex: 0 0 33.333%;
        }
    }

    .cr-card .year {
        font-size: 14px;
        color: #666;
        margin: 4px 0;
    }

    .cr-actions {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .cr-card .cr-view-btn,
    .cr-card .cr-view-btn-danger {
        background-color: #fff;
        border: 1px solid #dcdcde;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cr-card .cr-view-btn:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-card .cr-view-btn .dashicons {
        font-size: 18px;
        color: #007cba;
    }

    .cr-card .cr-view-btn-danger:hover {
        border-color: #ff686b;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-card .cr-view-btn-danger .dashicons {
        font-size: 18px;
        color: #ff686b;
    }

    .badge-container {
        text-align: center;
    }


    .cr-card:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: #fff;
        border: 1px solid #dcdcde;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .cr-scroll-btn:hover {
        border-color: #007cba;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cr-scroll-btn-left {
        left: -8px;
    }

    .cr-scroll-btn-right {
        right: -8px;
    }

    .truncate-multiline {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 1024px) {
        .cr-item {
            flex: 0 0 calc(33.333% - 14px);
        }
    }

    @media (max-width: 768px) {
        .cr-item {
            flex: 0 0 calc(33.333% - 10px);
        }
    }

    @media (max-width: 480px) {
        .cr-item {
            flex: 0 0 33.333%;
        }
    }
</style>
<div class="container-md mx-auto" style="width: 900px;">
    <div class="text-center" id="action-sakip">
        <div class="d-inline-flex align-items-center">
            <button class="btn btn-primary btn-large mr-3" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button>
            <?php if ($hak_akses_user_pegawai == 1 || ($hak_akses_user_pegawai == 2 && $pihak_pertama && $pihak_pertama['nip_pegawai'] == $nip_user_pegawai)): ?>
                <button class="btn btn-warning mr-3" onclick="get_alamat();"><i class="dashicons dashicons-edit"></i> Edit Alamat</button>
            <?php endif; ?>
            <div class="form-inline">
                <label for="font-select" class="mr-2">Jenis Font:</label>
                <select id="font-select" class="form-control mr-3" onchange="updateFont()" style="width: 300px;">
                    <option value="Arial" selected>Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Calibri">Calibri</option>
                    <option value="inherit">Inherit</option>
                    <option value="Courier">Courier</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Helvetica">Helvetica</option>
                    <option value="Trebuchet">Trebuchet</option>
                    <option value="Verdana">Verdana</option>
                </select>
            </div>
        </div>
        <div class="d-inline-flex align-items-center mt-3">
            <div class="form-inline">
                <label for="font-size" class="mr-3">Ukuran Font Konten:
                    <input type="number" id="font-size" class="form-control form-control-sm text-right ml-1 mr-1" value="16" min="1" max="100" onkeyup="updateFont()" onchange="updateFont()" style="width: 80px;"> px
                </label>
                <label for="font-size" class="mr-3">Ukuran Font Judul 1:
                    <input type="number" id="font-size-1" class="form-control form-control-sm text-right ml-1 mr-1" value="19" min="1" max="100" onkeyup="updateFont()" onchange="updateFont()" style="width: 80px;"> px
                </label>
                <label for="font-size" class="mr-3">Ukuran Font Judul 2:
                    <input type="number" id="font-size-2" class="form-control form-control-sm text-right ml-1 mr-1" value="21" min="1" max="100" onkeyup="updateFont()" onchange="updateFont()" style="width: 80px;"> px
                </label>
            </div>
        </div>
        <div class="d-inline-flex align-items-center mt-3">
            <div class="form-inline">
                <label class="mr-3"><input class="mr-1" type="radio" name="jenis_pk" onchange="updateFont()" value="1" checked> PK Murni</label>
                <label class="mr-3"><input class="mr-1" type="radio" name="jenis_pk" onchange="updateFont()" value="2"> PK Perubahan</label>
            </div>
        </div>
        <!-- Error Message -->
        <?php if (!empty($error_message) && is_array($error_message)) : ?>
            <div class="alert alert-danger mt-3 hide-display-print">
                <p>Terjadi Kesalahan mendapatkan data pegawai SIMPEG!</p>
                <ul class="mb-0">
                    <?php echo implode('', array_map(fn($msg) => "<li>{$msg}</li>", $error_message)); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <!-- Jumlah Data Per SKPD -->
    <?php if (!empty($jumlah_data) && is_array($jumlah_data)) : ?>
        <div class="cr-container m-4 hide-display-print">
            <h2 class="cr-title">Jumlah Dokumen Finalisasi Per SKPD</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark" style="pointer-events: none;">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama SKPD</th>
                            <th class="text-center">Jumlah Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_dokumen = 0;
                        foreach ($jumlah_data as $v) :
                            $total_dokumen += $v['jumlah'];
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-left"><?php echo $v['nama_skpd']; ?></td>
                                <td class="text-right"><?php echo $v['jumlah']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table font-weight-bold" style="pointer-events: none;">
                        <tr>
                            <td colspan="2" class="text-center">Total Keseluruhan</td>
                            <td class="text-right"><?php echo $total_dokumen; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="cr-container m-4 hide-display-print">
        <h2 class="cr-title">Pilih Laporan Perjanjian Kinerja</h2>
        <div class="cr-carousel-wrapper">
            <div id="card-carousel" class="cr-carousel">
                <div class="cr-item" title="Perjanjian Kinerja Real Time">
                    <div class="cr-card">
                        <h3>Perjanjian Kinerja Sekarang</h3>
                        <div class="badge badge-sm badge-primary m-2 text-light text-wrap"><?php echo $skpd['nama_skpd']; ?></div>
                        <div class="year"><?php echo $text_tanggal_hari_ini; ?></div>
                        <div class="cr-view-btn" style="display: none;" id="display-btn-first" onclick="location.reload()">
                            <span class="dashicons dashicons-visibility"></span>
                        </div>
                        <span class="badge badge-info mt-2">
                            <i class="dashicons dashicons-clock align-middle"></i> Real Time
                        </span>
                        <div class="text-center badge-sedang-dilihat">
                            <span class='badge badge-sm badge-warning m-2'>Sedang Dilihat</span>
                        </div>
                    </div>
                </div>
                <?php echo $card; ?>
            </div>
            <div class="cr-scroll-btn cr-scroll-btn-left" onclick="scrollCarousel(-1)">
                <span class="dashicons dashicons-arrow-left-alt2"></span>
            </div>
            <div class="cr-scroll-btn cr-scroll-btn-right" onclick="scrollCarousel(1)">
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </div>
        </div>
    </div>

    <div class="text-center page-print">
        <div class="text-right m-2">
            <?php if ($hak_akses_user_pegawai == 1 || ($hak_akses_user_pegawai == 2 && $pihak_pertama && $pihak_pertama['nip_pegawai'] == $nip_user_pegawai)): ?>
                <button class="btn btn-sm btn-success hide-display-print" id="finalisasi-btn" onclick="showModalFinalisasi()">
                    <span class="dashicons dashicons-saved" title="Finalisasikan dokumen (Menyimpan dokumen sesuai data terkini)"></span>
                    Finalisasi Dokumen
                </button>
                <button class="btn btn-sm btn-warning hide-display-print" id="edit-btn" onclick="showModalEditFinalisasi()" style="display: none;">
                    <span class="dashicons dashicons-edit" title="Edit Label"></span>
                    Edit Finalisasi Dokumen
                </button>
            <?php endif; ?>
        </div>
        <div class="row" style="border-bottom: 7px solid;">
            <div class="col-2" style="display: flex; align-items: center; height: 200px;">
                <?php if (!empty($logo_pemda)) : ?>
                    <img style="max-width: 100%; height: auto;" src="<?php echo $logo_pemda; ?>" alt="Logo Pemda">
                <?php endif; ?>
            </div>
            <div class="col my-auto">
                <p class="title-pk-1">PEMERINTAH <?php echo strtoupper($nama_pemda); ?></p>
                <p class="title-pk-2 m-2 nama-skpd-view"><?php echo strtoupper($skpd['nama_skpd']); ?></p>
                <div class="title-pk-3 alamat-kantor-view" id="alamat_kantor"><?php echo $skpd['alamat_kantor']; ?></div>
            </div>
            <div class="col-1"></div>
        </div>
        <p class="title-laporan mt-3 mb-2"><span class="jenis_pk_text">PERJANJIAN KINERJA</span> TAHUN <?php echo $input['tahun']; ?></p>
        <p class="text-left f-12 mt-5">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
        <table id="table-1" class="text-left f-12">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td class="nama-pegawai-view"><?php echo $pihak_pertama['gelar_depan'] . ' ' . $pihak_pertama['nama_pegawai'] .  $pihak_pertama['gelar_belakang']; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td class="status-jabatan-pegawai-1 jabatan-pegawai-view"><?php echo $pihak_pertama['jabatan_pegawai']; ?></td>
            </tr>
            <tr>
                <td colspan="3">Selanjutnya disebut pihak pertama</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td class="nama-pegawai-atasan-view"><?php echo $pihak_kedua['gelar_depan'] . ' ' . $pihak_kedua['nama_pegawai'] .  $pihak_kedua['gelar_belakang']; ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td class="status-jabatan-pegawai-2 jabatan-pegawai-atasan-view" id="jabatan_pegawai_atasan"><?php echo $pihak_kedua['jabatan_pegawai']; ?></td>
            </tr>
            <tr>
                <td colspan="3">Selaku atasan langsung pihak pertama, selanjutnya disebut pihak kedua</td>
            </tr>
        </table>
        <p class="text-left f-12">Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target tersebut menjadi tanggung jawab kami.</p>
        </br>
        <p class="text-left f-12">Pihak kedua akan memberikan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka memberikan penghargaan dan sanksi.</p>
        <table id="table_data_pejabat" style="margin-top: 3rem;" class="f-12">
            <tbody>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td></td>
                    <td style="padding: 0 0 0 20px;" contenteditable="true" title="Klik untuk ganti teks!" class="editable-field">
                        <?php echo $pemda; ?>, <span class="tanggal-dokumen-view"><?php echo $text_tanggal_hari_ini; ?></span>
                    </td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;">Pihak Kedua,</td>
                    <td style="padding: 0 0 0 20px;">Pihak Pertama,</td>
                </tr>
                <tr style="height: 7em;">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;" class="ttd-pejabat nama-pegawai-atasan-view" id="nama_pegawai_atasan">
                        <?php echo $pihak_kedua['gelar_depan'] . ' ' . $pihak_kedua['nama_pegawai'] . $pihak_kedua['gelar_belakang']; ?>
                    </td>
                    <td style="padding: 0 0 0 20px;" class="ttd-pejabat nama-pegawai-view">
                        <?php echo $pihak_pertama['gelar_depan'] . ' ' . $pihak_pertama['nama_pegawai'] . $pihak_pertama['gelar_belakang']; ?>
                    </td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;" id="pangkat_pegawai_atasan" class="pangkat-pegawai-atasan-view">
                        <?php if ($data_atasan->id != 0) : ?>
                            <?php echo $pihak_kedua['pangkat']; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0 0 0 20px;" class="pangkat-pegawai-view">
                        <?php echo $pihak_pertama['pangkat']; ?>
                    </td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;" id="nip_pegawai_atasan">
                        <?php if ($data_atasan->id != 0) : ?>
                            NIP. <span class="nip-pegawai-atasan-view"><?php echo $pihak_kedua['nip_pegawai']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0 0 0 20px;">
                        NIP. <span class="nip-pegawai-view"><?php echo $pihak_pertama['nip_pegawai']; ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="break-print"></div>
    <div class="page-print mt-5 text-center">
        <p class="title-laporan mt-3"><span class="jenis_pk_text">PERJANJIAN KINERJA</span> TAHUN <?php echo $input['tahun']; ?></p>
        <p class="title-laporan mb-5 nama-satker-view"><?php echo $pihak_pertama['bidang_pegawai']; ?></p>
        <?php if (!empty($html_pk['html_sasaran'])) : ?>
            <table class="table_data_anggaran" id="table-sasaran-view">
                <thead>
                    <tr>
                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                        <th class="esakip-text_tengah" style="width: 470px;">Sasaran</th>
                        <th class="esakip-text_tengah" style="width: 180px;">Indikator</th>
                        <th class="esakip-text_tengah">Target</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $html_pk['html_sasaran']; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (!empty($html_pk['html_program'])) : ?>
            <table class="table_data_anggaran" id="table-program-view">
                <thead>
                    <tr>
                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                        <th class="esakip-text_tengah" style="width: 470px;">Program</th>
                        <th class="esakip-text_tengah" style="width: 180px;">Anggaran</th>
                        <th class="esakip-text_tengah">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $html_pk['html_program']; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (!empty($html_pk['html_kegiatan'])) : ?>
            <table class="table_data_anggaran" id="table-kegiatan-view">
                <thead>
                    <tr>
                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                        <th class="esakip-text_tengah" style="width: 470px;">Kegiatan</th>
                        <th class="esakip-text_tengah" style="width: 180px;">Anggaran</th>
                        <th class="esakip-text_tengah">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $html_pk['html_kegiatan']; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (!empty($html_pk['html_sub_kegiatan'])) : ?>
            <table class="table_data_anggaran" id="table-subkegiatan-view">
                <thead>
                    <tr>
                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                        <th class="esakip-text_tengah" style="width: 470px;">Sub Kegiatan</th>
                        <th class="esakip-text_tengah" style="width: 180px;">Anggaran</th>
                        <th class="esakip-text_tengah">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $html_pk['html_sub_kegiatan']; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <table id="table_data_pejabat" class="f-12 mt-5">
            <tbody>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;"></td>
                    <td style="padding: 0 0 0 20px;" contenteditable="true" title="Klik untuk ganti teks!" class="editable-field">
                        <?php echo $pemda; ?>, <span class="tanggal-dokumen-view"><?php echo $text_tanggal_hari_ini; ?></span>
                    </td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;" class="jabatan-pegawai-atasan-view status-jabatan-pegawai-2"><?php echo $pihak_kedua['jabatan_pegawai']; ?></td>
                    <td style="padding: 0 0 0 20px;" class="jabatan-pegawai-view status-jabatan-pegawai-1"><?php echo $pihak_pertama['jabatan_pegawai']; ?>,</td>
                </tr>
                <tr style="height: 7em;">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;" class="ttd-pejabat nama-pegawai-atasan-view">
                        <?php echo $pihak_kedua['gelar_depan'] . ' ' . $pihak_kedua['nama_pegawai'] .  $pihak_kedua['gelar_belakang']; ?>
                    </td>
                    <td style="padding: 0 0 0 20px;" class="ttd-pejabat nama-pegawai-view">
                        <?php echo $pihak_pertama['gelar_depan'] . ' ' . $pihak_pertama['nama_pegawai'] .  $pihak_pertama['gelar_belakang']; ?>
                    </td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;" class="pangkat-pegawai-atasan-view">
                        <?php if ($data_atasan->id != 0) : ?>
                            <?php echo $pihak_kedua['pangkat']; ?>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0 0 0 20px;" class="pangkat-pegawai-view">
                        <?php echo $pihak_pertama['pangkat']; ?>
                    </td>
                </tr>
                <tr class="<?php echo $ttd_orientasi; ?>">
                    <td style="padding: 0 20px 0 0;">
                        <?php if ($data_atasan->id != 0) : ?>
                            NIP. <span class="nip-pegawai-atasan-view"><?php echo $pihak_kedua['nip_pegawai']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 0 0 0 20px;">
                        NIP. <span class="nip-pegawai-view"><?php echo $pihak_pertama['nip_pegawai']; ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="hide-display-print container mt-4 p-4 mb-4 border rounded bg-light">
        <h4 class="font-weight-bold mb-3 text-dark">Catatan Finalisasi Dokumen PK:</h4>
        <ul class="pl-3 text-muted">
            <li class="text-danger font-weight-bold">⚠️ Dokumen PK yang sudah difinalisasi tidak dapat diedit, tapi bisa dihapus jika ada kesalahan! <br> (Hanya nama tahapan dan tanggal dokumen yang masih bisa diubah.)</li>
            <li>Dokumen <strong>"Perjanjian Kinerja Sekarang"</strong> dapat difinalisasikan per tahap.</li>
            <li>Data pegawai dan Data atasan pegawai dalam dokumen PK diambil secara <strong>real-time</strong> dari aplikasi SIMPEG.</li>
            <li>Pegawai Berstatus <strong>Plt./Plh./Pj.</strong> dapat mengisi form status jabatan yang muncul saat halaman pertama kali di muat.</li>
            <li>Finalisasi dokumen akan menyimpan data pegawai dan Data atasan pegawai saat ini, termasuk:
                <ul>
                    <li>NIP</li>
                    <li>Pangkat dan Jabatan</li>
                    <li>Nama (beserta gelar)</li>
                    <li>Nama Perangkat Daerah saat ini</li>
                    <li>Nama Satuan Kerja saat ini</li>
                    <li>Status Jabatan (Plt./Plh./Pj.)</li>
                </ul>
                <strong>Pastikan data pegawai dan Data atasan pegawai sudah benar sebelum melakukan finalisasi.</strong>
            </li>
            <li>Finalisasi dokumen juga menyimpan data <strong>Plotting RHK (Rencana Hasil Kerja)</strong> per pegawai, meliputi:
                <ul>
                    <li>Sasaran</li>
                    <li>Program</li>
                    <li>Kegiatan</li>
                    <li>Subkegiatan</li>
                </ul>
                <strong>Pastikan data plotting RHK sudah sesuai sebelum finalisasi.</strong> Data ini dapat disesuaikan melalui halaman RHK.
            </li>
            <li><strong>Pegawai dapat memiliki dokumen PK di lebih dari satu Perangkat Daerah.</strong> Dokumen PK yang ditampilkan adalah berdasarkan data per pegawai dan per tahun.</li>
        </ul>
    </div>
</div>
</div>

<div class="modal fade mt-4" id="modalFinalisasi" tabindex="-1" role="dialog" aria-labelledby="modalFinalisasi" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-label">Finalisasi Dokumen Perjanjian Kinerja <?php echo $input['tahun']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi Pegawai -->
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Informasi Pegawai</strong>
                    </div>
                    <div class="card-body">
                        <table class="borderless-table mb-4">
                            <tbody>
                                <tr>
                                    <td class="text-left" style="width: 20%;">
                                        <strong>Nama Pegawai</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="nama_pegawai"><?php echo $pihak_pertama['gelar_depan'] . ' ' . $pihak_pertama['nama_pegawai'] . $pihak_pertama['gelar_belakang']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>NIP</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="nip_pegawai"><?php echo $pihak_pertama['nip_pegawai']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Pangkat</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="pangkat_pegawai"><?php echo $pihak_pertama['pangkat'] ?: '-'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Jabatan</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="jabatan_pegawai"><?php echo $pihak_pertama['jabatan_pegawai']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Satuan Kerja</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="bidang_pegawai"><?php echo strtoupper($pihak_pertama['bidang_pegawai']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>OPD</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="nama_skpd"><?php echo strtoupper($skpd['nama_skpd']); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Informasi RHK -->
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>RHK</strong>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($html_pk['html_sasaran'])) : ?>
                            <table class="table_data_anggaran" id="table_sasaran">
                                <thead class="bg-dark text-light">
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 46px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 470px;">Sasaran</th>
                                        <th class="esakip-text_tengah" style="width: 180px;">Indikator</th>
                                        <th class="esakip-text_tengah">Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_pk['html_sasaran']; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($html_pk['html_program'])) : ?>
                            <table class="table_data_anggaran" id="table_program">
                                <thead class="bg-dark text-light">
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 470px;">Program</th>
                                        <th class="esakip-text_tengah" style="width: 180px;">Anggaran</th>
                                        <th class="esakip-text_tengah">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_pk['html_program']; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($html_pk['html_kegiatan'])) : ?>
                            <table class="table_data_anggaran" id="table_kegiatan">
                                <thead class="bg-dark text-light">
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 470px;">Kegiatan</th>
                                        <th class="esakip-text_tengah" style="width: 180px;">Anggaran</th>
                                        <th class="esakip-text_tengah">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_pk['html_kegiatan']; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($html_pk['html_sub_kegiatan'])) : ?>
                            <table class="table_data_anggaran" id="table_subkegiatan">
                                <thead class="bg-dark text-light">
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 470px;">Sub Kegiatan</th>
                                        <th class="esakip-text_tengah" style="width: 180px;">Anggaran</th>
                                        <th class="esakip-text_tengah">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_pk['html_sub_kegiatan']; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Perjanjian Kinerja</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama_dokumen">Nama Tahapan</label>
                                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" placeholder="ex : Perjanjian Kinerja tahun <?php echo $input['tahun']; ?>" maxlength="48" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tanggal_dokumen">Tanggal Dokumen</label>
                                <input type="date" class="form-control" id="tanggal_dokumen" name="tanggal_dokumen" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <small class="form-text text-muted">Pastikan data yang tertera benar, laporan yang sudah difinalisasi akan disimpan dan tidak dapat di edit kembali.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="simpanFinalisasi()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade mt-4" id="modalEditFinalisasi" tabindex="-1" role="dialog" aria-labelledby="modalEditFinalisasi" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-label">Edit Finalisasi Dokumen Perjanjian Kinerja <?php echo $input['tahun']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_data" name="id_data" value="">

                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Perjanjian Kinerja</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama_tahap_finalisasi">Nama Tahapan</label>
                                <input type="text" class="form-control" id="nama_tahap_finalisasi" name="nama_tahap_finalisasi" placeholder="ex : Perjanjian Kinerja tahun <?php echo $input['tahun']; ?>" maxlength="48">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tanggal_tahap_finalisasi">Tanggal Dokumen</label>
                                <input type="date" class="form-control" id="tanggal_tahap_finalisasi" name="tanggal_tahap_finalisasi">
                            </div>
                        </div>
                        <small class="form-text text-muted">Dokumen yang sudah difinalisasi hanya dapat diubah nama label nya.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="simpanEditFinalisasi()">Perbarui</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- modal alamat -->
<div class="modal fade" id="modalAlamat" data-backdrop="static" role="dialog" aria-labelledby="modalAlamat" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Edit Alamat Kantor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" value="" id="id_skpd">
                    <div class="form-group">
                        <label for="alamat">Alamat Kantor</label>
                        <?php
                        $content = '';
                        $editor_id = 'alamat';
                        $settings = array(
                            'textarea_name' => 'alamat',
                            'media_buttons' => false,
                            'teeny' => false,
                            'quicktags' => true
                        );
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary d-flex text-right" onclick="submit_edit_alamat(this); return false">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        window.hak_akses_user_pegawai = <?php echo $hak_akses_user_pegawai ?>;
        window.nip_akses_user_pegawai = <?php echo $nip_user_pegawai ?>;
        window.nip_pihak_pertama = <?php echo $pihak_pertama['nip_pegawai'] ?>;
        updateFont();

        if (hak_akses_user_pegawai == 2 && nip_pihak_pertama != nip_akses_user_pegawai) {
            jQuery(".editable-field").attr("title", "").attr("contenteditable", "false");
        }
    });

    function get_alamat(id_skpd) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_alamat_kantor',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    if (response.data != null || response.data != undefined) {
                        let data = response.data;
                        if (tinymce.get('alamat')) {
                            tinymce.get('alamat').setContent(data.alamat_kantor);
                        } else {
                            jQuery('#alamat').val(data.alamat_kantor);
                        }
                    }
                    jQuery('#modalAlamat').modal('show');
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

    function submit_edit_alamat() {
        let alamat = tinymce.get('alamat') ? tinymce.get('alamat').getContent() : jQuery('#alamat').val();
        if (alamat == '') {
            return alert('Alamat kantor tidak boleh kosong');
        }

        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "submit_alamat_kantor",
                api_key: esakip.api_key,
                alamat: alamat,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload()
                    jQuery('#modalAlamat').modal('hide')
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }

    function scrollCarousel(direction) {
        const carousel = jQuery('#card-carousel');
        const scrollAmount = carousel[0].offsetWidth;
        const currentScroll = carousel.scrollLeft();

        carousel.animate({
            scrollLeft: currentScroll + direction * scrollAmount
        }, 500);
    }

    function viewDokumen(idTahap) {
        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "get_laporan_pk_by_id",
                api_key: esakip.api_key,
                id_tahap: idTahap,
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                console.log(response.message);
                if (response.status === 'success') {
                    jQuery('.nama-skpd-view').text(response.data.nama_skpd)
                    jQuery('.nama-satker-view').text(response.data.satuan_kerja)
                    jQuery('.alamat-kantor-view').empty() // clear previous content
                    jQuery('.alamat-kantor-view').append(response.data.alamat_kantor)
                    jQuery('.tanggal-dokumen-view').text(formatTanggalIndonesia(response.data.tanggal_dokumen))

                    jQuery('.nama-pegawai-view').text(response.data.nama_pegawai)
                    jQuery('.nip-pegawai-view').text(response.data.nip_pegawai)
                    jQuery('.pangkat-pegawai-view').text(response.data.pangkat_pegawai)
                    jQuery('.jabatan-pegawai-view').text(response.data.jabatan_pegawai)

                    jQuery('.nama-pegawai-atasan-view').text(response.data.nama_pegawai_atasan)
                    jQuery('.jabatan-pegawai-atasan-view').text(response.data.jabatan_pegawai_atasan)
                    jQuery('.pangkat-pegawai-atasan-view').text(response.data.pangkat_pegawai_atasan)
                    jQuery('.nip-pegawai-atasan-view').text(response.data.nip_pegawai_atasan)

                    jQuery('#id_data').val(response.data.id)
                    jQuery('#nama_tahap_finalisasi').val(response.data.nama_tahapan)
                    jQuery('#tanggal_tahap_finalisasi').val(response.data.tanggal_dokumen)

                    // generate tbody untuk lembar kedua
                    // Hapus isi tbody sebelum menambahkan data baru
                    jQuery("#table-sasaran-view tbody, #table-program-view tbody, #table-kegiatan-view tbody, #table-subkegiatan-view tbody").empty();
                    jQuery("#table-sasaran-view, #table-program-view, #table-kegiatan-view, #table-subkegiatan-view").hide();
                    if (response.data.html_sasaran) {
                        jQuery(`#table-sasaran-view tbody`).html(response.data.html_sasaran)
                        jQuery(`#table-sasaran-view`).show()
                    }
                    if (response.data.html_program) {
                        jQuery(`#table-program-view tbody`).html(response.data.html_program)
                        jQuery(`#table-program-view`).show()
                    }
                    if (response.data.html_kegiatan) {
                        jQuery(`#table-kegiatan-view tbody`).html(response.data.html_kegiatan)
                        jQuery(`#table-kegiatan-view`).show()

                    }
                    if (response.data.html_subkegiatan) {
                        jQuery(`#table-subkegiatan-view tbody`).html(response.data.html_subkegiatan)
                        jQuery(`#table-subkegiatan-view`).show()
                    }

                    //disable contenteditable
                    jQuery(".editable-field").attr("title", "").attr("contenteditable", "false");


                    jQuery(".cr-view-btn").show(); // another view button
                    jQuery(`#view-btn-${idTahap}`).hide(); //current view button

                    jQuery(`.badge-sedang-dilihat`).hide() //another badge
                    jQuery(`#badge-sedang-dilihat-${response.data.id}`).show() //current badge

                    jQuery('#finalisasi-btn').hide()
                    jQuery('#edit-btn').show()

                    jQuery('#display-btn-first').show() //view real time view btn
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }

    function showModalFinalisasi() {
        jQuery('#modalFinalisasi').modal('show')
    }

    function showModalEditFinalisasi() {
        jQuery('#modalEditFinalisasi').modal('show')
    }

    function deleteDokumen(idTahap) {
        let confirmHapus = confirm('Apakah anda yakin ingin menghapus data ini?');
        if (!confirmHapus) {
            return;
        }
        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "hapus_finalisasi_laporan_pk",
                api_key: esakip.api_key,
                id_tahap: idTahap
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery(`#card-tahap-${idTahap}`).hide()

                    //jika dokumen sedang dibuka, reload!
                    if (idTahap == jQuery(`#id_data`).val()) {
                        location.reload()

                        //disable btn
                        jQuery(".cr-actions .cr-view-btn, .cr-actions .cr-view-btn-danger").prop("disabled", true).css("pointer-events", "none").css("opacity", "0.5");
                    } else {
                        jQuery('#wrap-loading').hide()
                    }
                } else {
                    jQuery('#wrap-loading').hide()
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('GAGAL: ' + response.message);
            },
        });
    }

    function simpanFinalisasi() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin menyimpan data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        let dokumenPk = {
            nama_tahapan: jQuery('#nama_dokumen').val(),
            tanggal_dokumen: jQuery('#tanggal_dokumen').val()
        };

        let option_js = {
            id_skpd: '<?php echo $id_skpd; ?>',
            satker_id: '<?php echo $data_pegawai_1->satker_id; ?>',
            tahun: '<?php echo $input['tahun']; ?>',
            nip_baru: '<?php echo $nip; ?>'
        };

        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "simpan_finalisasi_laporan_pk",
                api_key: esakip.api_key,
                data_pk: dokumenPk,
                id_pertama: '<?php echo $data_pegawai_1->id; ?>',
                id_kedua: '<?php echo $data_atasan->id ?? 0; ?>',
                id_skpd: '<?php echo $id_skpd; ?>',
                id_satker_pertama: '<?php echo $data_pegawai_1->satker_id; ?>',
                id_satker_kedua: '<?php echo (!empty($data_atasan) && isset($data_atasan->satker_id)) ? $data_atasan->satker_id : ($data_atasan->satker_id ?? ""); ?>',
                tahun_anggaran: '<?php echo $input['tahun']; ?>',
                options: option_js
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload()
                    jQuery('#modalFinalisasi').modal('hide')
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }

    function simpanEditFinalisasi() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin perbarui data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        const id_data = jQuery('#id_data').val()
        const namaTahapan = jQuery('#nama_tahap_finalisasi').val()
        const tanggalTahapan = jQuery('#tanggal_tahap_finalisasi').val()

        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "edit_finalisasi_laporan_pk",
                api_key: esakip.api_key,
                id_data: id_data,
                nama_tahap: namaTahapan,
                tanggal_tahap: tanggalTahapan
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery(`#nama-tahapan-${id_data}`).text(namaTahapan)
                    jQuery(`#card-tahap-${id_data}`).attr("title", `${namaTahapan}`)

                    jQuery(`#tanggal-tahapan-${id_data}`).text(formatTanggalIndonesia(tanggalTahapan))
                    jQuery('.tanggal-dokumen-view').text(formatTanggalIndonesia(tanggalTahapan))
                    jQuery('#modalEditFinalisasi').modal('hide')
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
    }

    function updateFont() {
        const fontFamily = document.getElementById('font-select').value;
        const fontSize = document.getElementById('font-size').value + 'px';
        const fontSize1 = document.getElementById('font-size-1').value + 'px';
        const fontSize2 = document.getElementById('font-size-2').value + 'px';

        document.querySelectorAll('.page-print').forEach(el => {
            el.style.fontFamily = fontFamily;
            el.style.fontSize = fontSize;
        });

        document.querySelectorAll('.title-pk-1').forEach(el => {
            el.style.fontFamily = fontFamily;
            el.style.fontSize = fontSize1;
        });

        document.querySelectorAll('.title-pk-2').forEach(el => {
            el.style.fontFamily = fontFamily;
            el.style.fontSize = fontSize2;
        });

        var jenis_pk = jQuery('input[name="jenis_pk"]:checked').val();
        if (jenis_pk == 1) {
            jQuery('.jenis_pk_text').text('PERJANJIAN KINERJA');
        } else {
            jQuery('.jenis_pk_text').text('PERJANJIAN KINERJA PERUBAHAN');
        }
    }
</script>