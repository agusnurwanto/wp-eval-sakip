<?php
if (!defined('WPINC')) {
    die;
}
$input = shortcode_atts(array(
    'tahun' => '2022',
    'periode' => '',
), $atts);

global $wpdb;

$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
$pemda = explode(" ", $nama_pemda);
array_shift($pemda);
$pemda = implode(" ", $pemda);
$logo_garuda = get_option('_crb_logo_garuda');
if (empty($logo_garuda)) {
    $logo_garuda = '';
}
$text_tanggal_hari_ini = $this->format_tanggal_indo(current_datetime()->format('Y-m-d'));
$error_message = array();

$nama_kepala_daerah = get_option('_crb_kepala_daerah');
$status_jabatan_kepala_daerah = get_option('_crb_status_jabatan_kepala_daerah');

$data_pk = $wpdb->get_results( 
    $wpdb->prepare("
        SELECT 
            pk.*,
            ik.label_sasaran as ik_label_sasaran,
            ik.label_indikator as ik_label_indikator
        FROM esakip_laporan_pk_pemda pk
        LEFT JOIN esakip_data_iku_pemda ik
            ON pk.id_iku = ik.id 
            AND pk.id_jadwal = ik.id_jadwal
        WHERE pk.active = 1
            AND pk.tahun_anggaran = %d
            AND pk.id_jadwal = %d
        ORDER BY pk.id ASC
    ", $input['tahun'], $input['periode']),
    ARRAY_A
);

$no = 1;
$no_pk = 1;
$html = '';
$html_2 = '';
$data_simpan = []; 

if (!empty($data_pk)) {
    foreach ($data_pk as $k_pk => $v_pk) {
        if (!empty($v_pk['id_iku']) && !empty($v_pk['ik_label_sasaran'])) {
            $label_sasaran = $v_pk['ik_label_sasaran'];
            $label_indikator = $v_pk['ik_label_indikator'];
        } else {
            $label_sasaran = $v_pk['label_sasaran'] ?? '';
            $label_indikator = $v_pk['label_indikator'] ?? '';
        }

        $data_simpan[] = [
            'label_sasaran'    => $label_sasaran,
            'label_indikator'  => $label_indikator,
            'target'           => $v_pk['target'] ?? '', 
            'pagu'             => $v_pk['pagu'] ?? '', 
            'id_jadwal'        => $v_pk['id_jadwal'] ?? '', 
            'tahun_anggaran'   => $v_pk['tahun_anggaran'] ?? '', 
        ]; 

        $html .= '<tr>';
        $html .= '<td class="text-left atas kanan bawah kiri">' . $no++ . '</td>';
        $html .= '<td class="text-left atas kanan bawah kiri">' . $label_sasaran . '</td>';
        $html .= '<td class="text-left atas kanan bawah kiri">' . $label_indikator . '</td>';
        $html .= '<td class="text-left atas kanan bawah kiri"><input type="number" class="form-control form-control-sm input-target" name="target[' . $v_pk['id'] . ']" value="' . $v_pk['target'] . '"></td>';
        $html .= '<td class="text-left atas kanan bawah kiri">' . $v_pk['satuan'] . '</td>';
        $html .= '</tr>';

        $html_2 .= '<tr>';
        $html_2 .= '<td class="text-left atas kanan bawah kiri">' . $no_pk++ . '</td>';
        $html_2 .= '<td class="text-left atas kanan bawah kiri">' . $label_sasaran . '</td>';
        $html_2 .= '<td class="text-left atas kanan bawah kiri">' . $label_indikator . '</td>';
        $html_2 .= '<td class="text-left atas kanan bawah kiri">' . $v_pk['target'] . '</td>';
        $html_2 .= '<td class="text-left atas kanan bawah kiri">' . $v_pk['satuan'] . '</td>';
        $html_2 .= '</tr>';

        $detail = $wpdb->get_results($wpdb->prepare("
            SELECT 
                r.*,
                l.id AS id_label,
                l.id_skpd AS id_skpd_label,
                l.parent_renaksi_opd
            FROM esakip_detail_rencana_aksi_pemda AS r
            LEFT JOIN esakip_data_label_rencana_aksi AS l
                ON l.parent_renaksi_pemda = r.id 
                AND l.active = r.active 
            WHERE r.active = 1
                AND r.id_pk = %d
                AND r.tahun_anggaran = %d
        ", $v_pk['id'], $input['tahun']), ARRAY_A);

        $parent_opd = array_column($detail, 'parent_renaksi_opd');

        if (!empty($parent_opd)) {
            $parent = implode(',', array_fill(0, count($parent_opd), '%d'));

            $get_renaksi_opd = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    label,
                    label_cascading_program,
                    pagu_cascading
                FROM esakip_data_rencana_aksi_opd
                WHERE active = 1
                    AND id IN ($parent)
                ORDER BY id ASC
            ", $parent_opd), ARRAY_A);

            $group_renaksi_opd = array(); 

            if (!empty($get_renaksi_opd)) {
                foreach ($get_renaksi_opd as $renaksi_opd) {
                    $label = isset($renaksi_opd['label_cascading_program']) ? $renaksi_opd['label_cascading_program'] : '';
                    if (!empty($label)) {
                        if (!isset($group_renaksi_opd[$label])) {
                            $group_renaksi_opd[$label] = 0;
                        }
                        $group_renaksi_opd[$label] += (float) $renaksi_opd['pagu_cascading'];
                    }
                }
            }

            $html_renaksi_opd = '';

            if (!empty($group_renaksi_opd)) {
                $html_renaksi_opd .= '
                <div>
                    <div style="display: flex; font-weight: bold; margin-bottom: 10px;">
                        <div style="width: 20px;">&nbsp;</div>
                        <div style="flex: 1;">Program</div>
                        <div style="width: 150px; text-align: left;">Anggaran</div>
                    </div>
                ';

                $no_renaksi_opd = 1;
                foreach ($group_renaksi_opd as $program => $pagu) {
                    $html_renaksi_opd .= '
                    <div style="display: flex; margin-bottom: 6px;">
                        <div style="width: 40px; text-align: center;">' . $no_renaksi_opd++ . '.</div>
                        <div style="flex: 1; padding-left: 6px; text-align: left;">' . htmlspecialchars($program) . '</div>
                        <div style="width: 180px; text-align: center;">' . number_format($pagu, 0, ',', '.') . ',-</div>
                    </div>';
                }

                $html_renaksi_opd .= '</div>';
            }
        }
    }
} else {
    $html = '<tr><td class="text-center" colspan="4">Data tidak tersedia.</td></tr>';
    $html_2 = '<tr><td class="text-center" colspan="5">Data tidak tersedia.</td></tr>';
}

$data_tahapan = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            t.*,
            f.id_tahap,         
            f.label_sasaran,    
            f.label_indikator,  
            f.target,        
            f.pagu,      
            f.id_jadwal
        FROM esakip_finalisasi_tahap_pk_pemda AS t
        INNER JOIN esakip_finalisasi_pk_pemda AS f ON f.id_tahap = t.id
            AND f.tahun_anggaran = t.tahun_anggaran
        WHERE t.id_jadwal = %d
          AND t.tahun_anggaran = %d
          AND t.active = 1
          AND f.active = 1
        ORDER BY t.tanggal_dokumen, t.updated_at DESC
    ", $input['periode'], $input['tahun']),
    ARRAY_A
);
// print_r($data_tahapan); die($wpdb->last_query);
$card = '';
$jumlah_data = array();
$nama_tahapan = array();

if (!empty($data_tahapan)) {
    foreach ($data_tahapan as $v) {
        $tanggal_dokumen = $this->format_tanggal_indo($v['tanggal_dokumen']);
        $get_nama_tahapan = $v['nama_tahapan'] . '|' . $tanggal_dokumen . '|'. $v['id'];

        if (!isset($nama_tahapan[$get_nama_tahapan])) {
            $nama_tahapan[$get_nama_tahapan] = [];
        }
        $nama_tahapan[$get_nama_tahapan][] = $v['id'];

        if (!isset($jumlah_data[$v['nama_tahapan']])) {
            $jumlah_data[$v['nama_tahapan']] = [
                'jumlah' => 0
            ];
        }
        $jumlah_data[$v['nama_tahapan']]['jumlah']++;
    }

    foreach ($nama_tahapan as $key => $get_pk) {
        list($nama_tahapan_item, $tanggal_dokumen, $id_tahap) = explode('|', $key);

        $card .= '
        <div class="cr-item" id="card-tahap-' . $id_tahap . '" title="' . $nama_tahapan_item . '">
            <div class="cr-card">
                <h3 class="truncate-multiline" id="nama-tahapan-' . $id_tahap . '">' . $nama_tahapan_item . '</h3>
                <div class="badge badge-sm badge-primary m-0 ml-2 mr-2 text-light text-wrap">Pemerintah Daerah</div>
                <div class="year" id="tanggal-tahapan-' . $id_tahap . '">' . $tanggal_dokumen . '</div>
                <div class="cr-actions">
                    <div class="cr-view-btn" id="view-btn-' . $id_tahap . '" onclick="viewDokumen(\'' . $id_tahap . '\', this)" title="Lihat Dokumen">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <div class="cr-view-btn-danger" onclick="deleteDokumen(\'' . $id_tahap . '\')" title="Hapus Dokumen">
                        <span class="dashicons dashicons-trash"></span>
                    </div>
                </div>
                <div class="badge-container">
                    <span class="badge badge-sm badge-warning badge-sedang-dilihat" id="badge-sedang-dilihat-' . $id_tahap . '" style="display:none">
                        Sedang Dilihat
                    </span>
                </div>
            </div>
        </div>';
    }
} 
?>

<head>
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
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
            width: 60%;
        }

        .title-laporan {
            font-weight: 700;
            font-size: 16pt;
        }

        .title-pk-1 {
            font-size: 14pt;
        }

        .title-pk-2 {
            font-size: 16pt;
            font-weight: 700;
        }

        .table_pk tr,
        .table_pk td,
        .table_pk th {
            border: solid 1px #000;
        }

        .table_pk tr td:first-child {
            width: 3rem;
        }

        .table_finalisasi_pk tr,
        .table_finalisasi_pk td,
        .table_finalisasi_pk th {
            border: solid 1px #000;
        }

        .table_finalisasi_pk tr td:first-child {
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
    </style>
</head>

<body>
    <div class="container-md mx-auto" style="width: 900px;">
        <div class="text-center" id="action-sakip">
            <button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button>

            <div class="d-inline-flex align-items-center ml-2">
                <button class="btn btn-warning" onclick="get_data();"><i class="dashicons dashicons-edit"></i>Edit Target</button>

                <div class="form-inline">
                    <label for="font-select" class="mr-2" style="padding-left: 10px">Jenis Font:</label>
                    <select id="font-select" class="form-control form-control-sm mr-3" onchange="updateFont()">
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
                    <label for="font-size" class="mr-2">Ukuran Font:</label>
                    <input type="number" id="font-size" class="form-control form-control-sm mr-3" value="16" min="1" max="48" onkeyup ="updateFont()" style="width: 80px;">
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <?php if (!empty($error_message) && is_array($error_message)) : ?>
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    <?php echo implode('', array_map(fn($msg) => "<li>{$msg}</li>", $error_message)); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="cr-container m-4 hide-display-print">
            <h2 class="cr-title">Pilih Laporan Perjanjian Kinerja</h2>
            <div class="cr-carousel-wrapper">
                <div id="card-carousel" class="cr-carousel">
                    <div class="cr-item" title="Perjanjian Kinerja Real Time">
                        <div class="cr-card">
                            <h3>Perjanjian Kinerja Sekarang</h3>
                            <div class="badge badge-sm badge-primary m-2 text-light text-wrap">Pemerintah Daerah </div>
                            <div class="year"></div>
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
                <button class="btn btn-sm btn-success hide-display-print" id="finalisasi-btn" onclick="showModalFinalisasi()">
                    <span class="dashicons dashicons-saved" title="Finalisasikan dokumen (Menyimpan dokumen sesuai data terkini)"></span>
                    Finalisasi Dokumen
                </button>
                <button class="btn btn-sm btn-warning hide-display-print" id="edit-btn" onclick="showModalEditFinalisasi()" style="display: none;">
                    <span class="dashicons dashicons-edit" title="Edit Label"></span>
                    Edit Finalisasi Dokumen
                </button>
            </div>
            <div class="col-12" style="display: flex; justify-content: center; align-items: center; height: 20vh;">
                <?php if (!empty($logo_garuda)) : ?>
                    <img style="max-width: 15%; height: auto;" src="<?php echo $logo_garuda; ?>" alt="Logo Garuda">
                <?php endif; ?>
            </div>
            <p class="title-laporan mt-3 mb-2">BUPATI <?php echo strtoupper($pemda); ?></p>
            <p class="title-laporan mt-3 mb-2">PERJANJIAN KINERJA<br>TAHUN <?php echo $input['tahun']; ?></p>
            <p class="text-left f-12 mt-5">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
            <table id="table-1" class="text-left f-12">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td class="nama-pegawai-view"><?php echo $nama_kepala_daerah; ?></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td class="status-jabatan-pegawai-1 jabatan-pegawai-view"><?php echo $status_jabatan_kepala_daerah; ?> <?php echo $pemda; ?></td>
                </tr>
            </table>
            <p class="text-left f-12">Berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan.</p>
            </br>
            <p class="text-left f-12">Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab kami.</p>
            <table id="table_data_pejabat" style="margin-top: 3rem;" class="f-12">
                <thead>
                    <tr class="text-center">
                        <td></td>
                        <td contenteditable="true" title="Klik untuk ganti teks!" class="editable-field">
                            <?php echo $pemda; ?>, <span class="tanggal-dokumen-view"><?php echo $text_tanggal_hari_ini; ?></span>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td></td>
                        <td><?php echo $status_jabatan_kepala_daerah; ?> <?php echo $pemda; ?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 7em;">
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="text-center">
                        <td class="ttd-pejabat"></td>
                        <td class="ttd-pejabat nama-pegawai-view"></td>
                    </tr>
                    <tr class="text-center">
                        <td></td>
                        <td style="padding: 0;" class="pangkat-pegawai-view">
                            <?php echo $nama_kepala_daerah; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="break-print"></div>
        <div class="page-print mt-5 text-center">
            <div class="col-12" style="display: flex; justify-content: center; align-items: center; height: 20vh;">
                <?php if (!empty($logo_garuda)) : ?>
                    <img style="max-width: 15%; height: auto;" src="<?php echo $logo_garuda; ?>" alt="Logo Garuda">
                <?php endif; ?>
            </div>
            <table class="table_pk f-12 mt-5">
                <thead>
                    <tr>
                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                        <th class="esakip-text_tengah" style="width: 300px;">Sasaran Strategis</th>
                        <th class="esakip-text_tengah" style="width: 150px;">Indikator Kinerja</th>
                        <th class="esakip-text_tengah" style="width: 10px;">Target</th>
                        <th class="esakip-text_tengah" style="width: 10px;">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $html_2 ?>
                </tbody>
            </table>
            <!-- menampilkan data program dan pagu opd -->
           <?php if (!empty($html_renaksi_opd)) : ?>
                <?php echo $html_renaksi_opd; ?>
            <?php endif; ?>

            <table class="table_finalisasi_pk f-12 mt-5"style="display: none;">
                <thead>
                    <tr>
                        <th class="esakip-text_tengah" style="width: 45px;">No</th>
                        <th class="esakip-text_tengah" style="width: 300px;">Sasaran Strategis</th>
                        <th class="esakip-text_tengah" style="width: 150px;">Indikator Kinerja</th>
                        <th class="esakip-text_tengah" style="width: 10px;">Target</th>
                        <th class="esakip-text_tengah" style="width: 150px;">Pagu Anggaran</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <table id="table_data_pejabat" style="margin-top: 3rem;" class="f-12">
                <thead>
                    <tr class="text-center">
                        <td></td>
                        <td contenteditable="true" title="Klik untuk ganti teks!" class="editable-field">
                            <?php echo $pemda; ?>, <span class="tanggal-dokumen-view"><?php echo $text_tanggal_hari_ini; ?></span>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td></td>
                        <td><?php echo $status_jabatan_kepala_daerah; ?> <?php echo $pemda; ?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 7em;">
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="text-center">
                        <td class="ttd-pejabat">
                            
                        </td>
                        <td class="ttd-pejabat nama-pegawai-view">
                            
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td></td>
                        <td style="padding: 0;" class="pangkat-pegawai-view">
                            <?php echo $nama_kepala_daerah; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal finalisasi -->
    <div class="modal fade mt-4" id="modalFinalisasi" tabindex="-1" role="dialog" aria-labelledby="modalFinalisasi" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-label">Finalisasi Perjanjian Kinerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_data" value="">
                    <!-- Informasi IKU -->
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <strong>Data Perjanjian Kinerja</strong>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($html)) : ?>
                                <table class="table_pk">
                                    <thead class="bg-dark text-light">
                                        <tr>
                                            <th class="text-center atas kanan bawah kiri">No</th>
                                            <th class="text-center atas kanan bawah kiri">Sasaran Strategis</th>
                                            <th class="text-center atas kanan bawah kiri">Indikator Kinerja</th>
                                            <th class="text-center atas kanan bawah kiri">Target</th>
                                            <th class="text-center atas kanan bawah kiri">Pagu Anggaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $html_2; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nama_tahapan">Nama Tahapan</label>
                                    <input type="text" class="form-control" id="nama_tahapan" name="nama_tahapan" placeholder="ex : Perjanjian Kinerja" maxlength="48" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tanggal_dokumen">Tanggal Finalisasi</label>
                                    <input type="date" class="form-control" id="tanggal_dokumen" name="tanggal_dokumen" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <small class="form-text text-muted">Pastikan data yang tertera benar, data yang sudah difinalisasi akan disimpan dan tidak dapat di edit kembali.</small>
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
    <!-- Modal edit finalisasi -->
    <div class="modal fade mt-4" id="modalEditFinalisasi" tabindex="-1" role="dialog" aria-labelledby="modalEditFinalisasi" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-label">Edit Finalisasi Dokumen Perjanjian Kinerja</h5>
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
                                    <input type="text" class="form-control" id="nama_tahap_finalisasi" name="nama_tahap_finalisasi" placeholder="ex : Perjanjian Kinerja" maxlength="48">
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
    <!-- Modal Target -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 70%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Target Perjanjian Kinerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary mb-2 btn-tambah-sasaran"><i class="dashicons dashicons-plus" style="margin-buttom: 10px;"></i> Tambah Sasaran</button>
                    <form enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $input['periode']; ?>" id="idJadwal">
                        <input type="hidden" value="<?php echo $input['tahun']; ?>" id="tahunAnggaran">
                        <input type="hidden" name="id_pk[]" value="<?php echo $v_pk['id']; ?>">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <?php if (!empty($html)) : ?>
                                    <table class="table_pk">
                                        <thead>
                                            <tr>
                                                <th class="text-center atas kanan bawah kiri">No</th>
                                                <th class="text-center atas kanan bawah kiri">Sasaran Strategis</th>
                                                <th class="text-center atas kanan bawah kiri">Indikator Kinerja</th>
                                                <th class="text-center atas kanan bawah kiri">Target</th>
                                                <th class="text-center atas kanan bawah kiri">Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $html; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary" onclick="submit_data_target(this); return false">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- Modal crud tambah sasaran -->
<div class="modal fade" id="modal-crud-sasaran">
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
<script>
    jQuery(document).ready(function() {
        getTable();
    });

    jQuery(document).on('click', '.btn-tambah-sasaran', function() {
        var that = this;
        return new Promise(function(resolve, reject) {

            let sasaranModal = jQuery("#modal-crud-sasaran");
            let html = '' +
                '<form id="form-sasaran">' +
                    '<div class="form-group">' +
                        '<label for="label_sasaran">Sasaran</label>' +
                        '<input class="form-control" name="label_sasaran"></input>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label for="label_indikator">Indikator</label>' +
                        '<input class="form-control" name="label_indikator"></input>' +
                    '</div>' +
                    `<div class="form-group row">` +
                        '<div class="col-md-6">' +
                            '<label for="target">Target</label>' +
                            '<input type="number" class="form-control" name="target"/>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<label for="satuan">Satuan</label>' +
                            '<input class="form-control" name="satuan"></input>' +
                        '</div>' +
                    `</div>` +
                '</form>';

            sasaranModal.find('.modal-body').html('');
            sasaranModal.find('.modal-title').html('Tambah Sasaran');
            sasaranModal.find('.modal-body').html(html);
            sasaranModal.find('.modal-footer').html('' +
                '<button type="button" class="btn btn-warning" data-dismiss="modal">' +
                    '<i class="dashicons dashicons-no" style="margin-top: 2px;"></i> Tutup' +
                '</button>' +
                '<button type="button" class="btn btn-success" id="btn-simpan-data-sasaran" data-action="submit_sasaran_pk">' +
                    '<i class="dashicons dashicons-yes" style="margin-top: 2px;"></i> Simpan' +
                '</button>');
            sasaranModal.find('.modal-dialog').css('maxWidth', '50%');
            sasaranModal.find('.modal-dialog').css('width', '');
            sasaranModal.modal('show');
            jQuery('#wrap-loading').hide();
        });
    });

    jQuery(document).on('click', '#btn-simpan-data-sasaran', function() {

        jQuery('#wrap-loading').show();
        let sasaranModal = jQuery("#modal-crud-sasaran");
        let action = jQuery(this).data('action');
        let view = jQuery(this).data('view');
        let form = getFormData(jQuery("#form-sasaran"));

        jQuery.ajax({
            method: 'POST',
            url: esakip.url,
            dataType: 'json',
            data: {
                'action': action,
                'api_key': esakip.api_key,
                'data': JSON.stringify(form),
                'tahun_anggaran': <?php echo $input['tahun']; ?>,
                'id_jadwal': <?php echo $input['periode']?>
            },
            success: function(response) {
                jQuery('#wrap-loading').hide();
                alert(response.message);
                if (response.status) {
                    location.reload();
                    sasaranModal.modal('hide');
                }
            }
        })
    });

    function runFunction(name, arguments) {
        var fn = window[name];
        if (typeof fn !== 'function')
            return;

        fn.apply(window, arguments);
    }

    function scrollCarousel(direction) {
        const carousel = jQuery('#card-carousel');
        const scrollAmount = carousel[0].offsetWidth;
        const currentScroll = carousel.scrollLeft();

        carousel.animate({
            scrollLeft: currentScroll + direction * scrollAmount
        }, 500);
    }

    function getTable() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_pk_pemda',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun']?>,
                id_jadwal: <?php echo $input['periode']?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat tabel!');
            }
        });
    }

    function showModalFinalisasi() {
        jQuery('#modalFinalisasi').modal('show')
    }

    function showModalEditFinalisasi() {
        jQuery('#modalEditFinalisasi').modal('show')
    }

    function simpanFinalisasi() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin menyimpan data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        let datapk = {
            nama_tahapan: jQuery('#nama_tahapan').val(),
            tanggal_dokumen: jQuery('#tanggal_dokumen').val()
        };
        let data_simpan = <?php echo json_encode($data_simpan); ?>;

        if (!Array.isArray(data_simpan) || data_simpan.length === 0) {
            alert('Tidak ada data yang dapat disimpan!');
            return;
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "simpan_finalisasi_pk_pemda",
                api_key: esakip.api_key,
                data_pk: datapk,
                data_simpan: data_simpan
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                    jQuery('#modalFinalisasi').modal('hide');
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
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
                action: "edit_finalisasi_pk_pemda",
                api_key: esakip.api_key,
                id_data: id_data,
                nama_tahap: namaTahapan,
                id_jadwal: <?php echo $input['periode']; ?>,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                tanggal_tahap: tanggalTahapan
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                if (response.status === 'success') {
                    alert(response.message);
                    id_data.split(',').forEach(id => {
                        jQuery(`#nama-tahapan-${id}`).text(namaTahapan);
                        jQuery(`#card-tahap-${id}`).attr("title", `${namaTahapan}`);
                        jQuery(`#tanggal-tahapan-${id}`).text(formatTanggalIndonesia(tanggalTahapan));
                    });
                    jQuery('#modalEditFinalisasi').modal('hide')
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide()
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            },
        });
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
                action: "hapus_finalisasi_pk_pemda",
                api_key: esakip.api_key,
                id_tahap: idTahap
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery(`#card-tahap-${idTahap}`).hide()

                    if (idTahap == jQuery(`#id_data`).val()) {
                        location.reload()

                        jQuery(".cr-actions .cr-view-btn, .cr-actions .cr-view-btn-danger").prop("disabled", true).css("pointer-events", "none").css("opacity", "0.5");
                    } else {
                        jQuery('#wrap-loading').hide()
                    }
                } else {
                    jQuery('#wrap-loading').hide()
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
        });
    }

    function viewDokumen(idTahap) {
        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "get_finalisasi_pk_pemda_by_id",
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                id_tahap: idTahap
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                console.log(response.message);
                if (response.status === 'success') {
                    jQuery(".editable-field").attr("title", "").attr("contenteditable", "false");
                    jQuery(".cr-view-btn").show();
                    jQuery(`#view-btn-${idTahap}`).hide();


                    jQuery('.table_finalisasi_pk').show();
                    jQuery('.table_pk').hide();
                    jQuery('#tambah-pk-pemda').hide();

                    jQuery('.table_finalisasi_pk tbody').html(response.html);

                    jQuery('#id_data').val(idTahap);
                    jQuery('#nama_tahap_finalisasi').val(response.nama_tahapan);
                    jQuery('#tanggal_tahap_finalisasi').val(response.tanggal_dokumen);

                    jQuery(`.badge-sedang-dilihat`).hide();
                    jQuery(`#badge-sedang-dilihat-${idTahap}`).show();
                    jQuery('#finalisasi-btn').hide();
                    jQuery('#display-btn-first').show();
                    jQuery('#edit-btn').show();
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
    function get_data() {
        jQuery("#editModalLabel").show();
        jQuery("#ModalLabel").show();
        jQuery("#Modal").modal('show');
    }
    function submit_data_target() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin perbarui data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        let targetData = {};
        jQuery('.input-target').each(function () {
            let name = jQuery(this).attr('name'); 
            let match = name.match(/target\[(\d+)\]/);
            if (match) {
                let id = match[1];
                targetData[id] = jQuery(this).val();
            }
        });

        let id_jadwal = jQuery('#idJadwal').val();
        let tahun_anggaran = jQuery('#tahunAnggaran').val();

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "submit_target_pk_pemda",
                api_key: esakip.api_key,
                id_jadwal: id_jadwal,
                tahun_anggaran: tahun_anggaran,
                target: targetData
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. Silakan coba lagi.');
            }
        });
    }

    function updateFont() {
        const fontFamily = document.getElementById('font-select').value;
        const fontSize = document.getElementById('font-size').value + 'px';

        document.querySelectorAll('.page-print').forEach(el => {
            el.style.fontFamily = fontFamily;
            el.style.fontSize = fontSize;
        });
    }
</script>