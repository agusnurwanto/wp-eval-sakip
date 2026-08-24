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

$nama_kepala_daerah = get_option('_crb_kepala_daerah');
$status_jabatan_kepala_daerah = get_option('_crb_status_jabatan_kepala_daerah');

$data_pk = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            pk.*,
            ik.label_sasaran as ik_label_sasaran,
            ik.label_indikator as ik_label_indikator,
            ik.satuan as ik_satuan
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
$data_simpan = [];
if (!empty($data_pk)) {
    foreach ($data_pk as $k_pk => $v_pk) {
        $is_target_realisasi_teks = $v_pk['is_target_teks'] == 1;

        $target_tahunan = ($is_target_realisasi_teks) ? $v_pk['target_teks'] : $v_pk['target'];
        $realisasi_1 = ($is_target_realisasi_teks) ? $v_pk['realisasi_teks_1'] : $v_pk['realisasi_1'];
        $realisasi_2 = ($is_target_realisasi_teks) ? $v_pk['realisasi_teks_2'] : $v_pk['realisasi_2'];
        $realisasi_3 = ($is_target_realisasi_teks) ? $v_pk['realisasi_teks_3'] : $v_pk['realisasi_3'];
        $realisasi_4 = ($is_target_realisasi_teks) ? $v_pk['realisasi_teks_4'] : $v_pk['realisasi_4'];

        if (!empty($v_pk['id_iku'])) {
            // jika dari iku pemda
            $label_sasaran  = $v_pk['ik_label_sasaran'];
            $indikator      = $v_pk['ik_label_indikator'];
            $satuan         = $v_pk['ik_satuan'];

        } else {
            // jika custom (dari pk pemda tambah manual)
            $label_sasaran          = $v_pk['label_sasaran'];
            $indikator              = $v_pk['label_indikator'];
            $satuan                 = $v_pk['satuan'];
        }

        $data_simpan[] = [
            'label_sasaran'    => $label_sasaran,
            'label_indikator'  => $indikator,
            'target'           => $target_tahunan,
            'realisasi_1'      => $realisasi_1,
            'realisasi_2'      => $realisasi_2,
            'realisasi_3'      => $realisasi_3,
            'realisasi_4'      => $realisasi_4,
            'pagu'             => $v_pk['pagu'],
            'id_jadwal'        => $v_pk['id_jadwal'],
            'tahun_anggaran'   => $v_pk['tahun_anggaran'],
        ];

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

$card = '';
$jumlah_data = array();
$nama_tahapan = array();

if (!empty($data_tahapan)) {
    foreach ($data_tahapan as $v) {
        $tanggal_dokumen = $this->format_tanggal_indo($v['tanggal_dokumen']);
        $get_nama_tahapan = $v['nama_tahapan'] . '|' . $tanggal_dokumen . '|' . $v['id'];

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

    .badge-lg {
        font-size: 14px;
        padding: 0.6em 1em;
        margin: 4px 10px;
    }

    .table_pk thead th,
    .table_finalisasi_pk thead th {
        vertical-align: middle;
    }
</style>
<div class="container-md mx-auto" style="width: 900px;">
    <div class="text-center" id="action-sakip">
        <button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button>

        <div class="d-inline-flex align-items-center ml-2">
            <button class="btn btn-warning" onclick="showModalEditPK();"><i class="dashicons dashicons-edit"></i>Edit Target</button>

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
                <input type="number" min="0" id="font-size" class="form-control form-control-sm mr-3" value="16" min="1" max="48" onkeyup="updateFont()" style="width: 80px;">
            </div>
        </div>
    </div>

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
        <div class="d-flex p-2 justify-content-center">
            <?php if (!empty($logo_garuda)) : ?>
                <img style="max-width: 15%; height: auto;" src="<?php echo $logo_garuda; ?>" alt="Logo Garuda">
            <?php endif; ?>
        </div>
        <p class="title-laporan mt-3 mb-2">BUPATI <?php echo strtoupper($pemda); ?></p>
        <p class="title-laporan mt-3 mb-2">PERJANJIAN KINERJA<br>TAHUN <?php echo $input['tahun']; ?></p>
        <p class="text-left mt-5">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
        <table id="table-1" class="text-left ">
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
        <p class="text-left ">Berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan.</p>
        </br>
        <p class="text-left ">Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab kami.</p>
        <table id="table_data_pejabat" style="margin-top: 3rem;" class="">
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
        <div class="d-flex p-2 justify-content-center">
            <?php if (!empty($logo_garuda)) : ?>
                <img style="max-width: 15%; height: auto;" src="<?php echo $logo_garuda; ?>" alt="Logo Garuda">
            <?php endif; ?>
        </div>
        <table class="table_pk mt-5 table-sm">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center">No</th>
                    <th rowspan="2" class="text-center">Sasaran Strategis</th>
                    <th rowspan="2" class="text-center">Indikator Kinerja</th>
                    <th rowspan="2" class="text-center">Target</th>
                    <th colspan="4" class="text-center">Realisasi</th>
                    <th rowspan="2" class="text-center">Satuan</th>
                </tr>
                <tr>
                    <th class="text-center">TW 1</th>
                    <th class="text-center">TW 2</th>
                    <th class="text-center">TW 3</th>
                    <th class="text-center">TW 4</th>
                </tr>
            </thead>
            <tbody class="table-pk-body-without-action">
            </tbody>
        </table>
        <!-- menampilkan data program dan pagu opd -->
        <?php if (!empty($html_renaksi_opd)) : ?>
            <?php echo $html_renaksi_opd; ?>
        <?php endif; ?>

        <table class="table_finalisasi_pk mt-5" style="display: none;">
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
        <table id="table_data_pejabat" style="margin-top: 3rem;" class="">
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
                <input type="hidden" name="id_data" value="">
                <!-- Informasi IKU -->
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Data Perjanjian Kinerja</strong>
                    </div>
                    <div class="card-body">
                        <table class="table_pk">
                            <thead class="bg-dark text-light">
                                <tr>
                                    <th rowspan="2" class="text-center">No</th>
                                    <th rowspan="2" class="text-center">Sasaran Strategis</th>
                                    <th rowspan="2" class="text-center">Indikator Kinerja</th>
                                    <th rowspan="2" class="text-center">Target</th>
                                    <th colspan="4" class="text-center">Realisasi</th>
                                    <th rowspan="2" class="text-center">Satuan</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="min-width: 100px">TW 1</th>
                                    <th class="text-center" style="min-width: 100px">TW 2</th>
                                    <th class="text-center" style="min-width: 100px">TW 3</th>
                                    <th class="text-center" style="min-width: 100px">TW 4</th>
                                </tr>
                            </thead>
                            <tbody class="table-pk-body-without-action">
                            </tbody>
                        </table>
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
                <button type="submit" class="btn btn-primary" onclick="simpanFinalisasi()" disabled>Simpan</button>
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
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Perjanjian Kinerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="max-height: 90vh; overflow-y: auto;">
                    <button type="button" class="btn btn-primary mb-2" onclick="addSasaran()"><span class="dashicons dashicons-insert"></span> Tambah Sasaran</button>
                    <table class="table_pk">
                        <thead class="bg-dark text-light">
                            <tr>
                                <th rowspan="2" class="text-center">No</th>
                                <th rowspan="2" class="text-center">Sasaran Strategis</th>
                                <th rowspan="2" class="text-center">Indikator Kinerja</th>
                                <th rowspan="2" class="text-center">Target</th>
                                <th colspan="4" class="text-center">Realisasi</th>
                                <th rowspan="2" class="text-center">Satuan</th>
                                <th rowspan="2" class="text-center">Aksi</th>
                            </tr>
                            <tr>
                                <th class="text-center">TW 1</th>
                                <th class="text-center">TW 2</th>
                                <th class="text-center">TW 3</th>
                                <th class="text-center">TW 4</th>
                            </tr>
                        </thead>
                        <tbody class="table-pk-body-with-action">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal crud tambah / edit sasaran -->
<div class="modal fade" id="modal-crud-sasaran">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-sasaran">
                    <input type="hidden" name="id_data" value="">
                    <input type="hidden" name="id_iku" value="">
                    <div class="card bg-light shadow-md mb-3">
                        <div class="card-header">
                            <strong>Data Sasaran</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="label_sasaran">Sasaran</label>
                                        <input type="text" class="form-control" name="label_sasaran">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="label_indikator">Indikator</label>
                                        <input type="text" class="form-control" name="label_indikator">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="satuan">Satuan</label>
                                        <input type="text" class="form-control" name="satuan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rumus_capaian_kinerja">Rumus Capaian Kinerja</label>
                                        <select name="rumus_capaian_kinerja">
                                            <option value="1">Indikator Tren Positif</option>
                                            <option value="2">Nilai Akhir</option>
                                            <option value="3">Indikator Tren Negatif</option>
                                        </select>
                                        <small class="text-muted">
                                            <ul>
                                                <li>Tren Positif : (Akumulasi Realisasi / Akumulasi Target) * 100.</li>
                                                <li>Nilai Akhir : (Nilai Akhir Realisasi / Nilai Akhir Target) * 100.</li>
                                                <li>Tren Negatif : (Akumulasi Target / Akumulasi Realisasi) * 100.</li>
                                                <li>Kedua rumus dihitung berdasarkan Realisasi dan Target triwulan berjalan.</li>
                                            </ul>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6" id="hide-iku-pemda">
                                </div>
                                <div class="col-md-12">
                                    <small id="help_text"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light shadow-md mb-3">
                                    <div class="card-header">
                                        <strong>Target dan Realisasi</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="target">Target Tahunan</label>
                                                <input type="number" min="0" class="form-control" name="target">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="realisasi_1">Realisasi TW 1</label>
                                                <input type="number" min="0" class="form-control" name="realisasi_1">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="realisasi_2">Realisasi TW 2</label>
                                                <input type="number" min="0" class="form-control" name="realisasi_2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="realisasi_3">Realisasi TW 3</label>
                                                <input type="number" min="0" class="form-control" name="realisasi_3">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="realisasi_4">Realisasi TW 4</label>
                                                <input type="number" min="0" class="form-control" name="realisasi_4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="card-target-teks">
                                <div class="card bg-light shadow-md mb-3">
                                    <div class="card-header">
                                        <strong>Target dan Realisasi Teks</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="target_teks">Target Tahunan Teks</label>
                                                <input type="text" class="form-control" name="target_teks">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="realisasi_1_teks">Realisasi TW 1 Teks</label>
                                                <input type="text" class="form-control" name="realisasi_1_teks">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="realisasi_2_teks">Realisasi TW 2 Teks</label>
                                                <input type="text" class="form-control" name="realisasi_2_teks">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="realisasi_3_teks">Realisasi TW 3 Teks</label>
                                                <input type="text" class="form-control" name="realisasi_3_teks">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="realisasi_4_teks">Realisasi TW 4 Teks</label>
                                                <input type="text" class="form-control" name="realisasi_4_teks">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="is_target_teks">
                            <input type="checkbox" id="is_target_teks" name="is_target_teks" value="1"> Target dan Realisasi Berupa Teks.
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="upsertIku()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        upsertDataFromIkuPemda();
        window.tahun_anggaran = <?php echo $input['tahun']; ?>;
        window.id_jadwal = <?php echo $input['periode']; ?>;

        loadDataAndGenerateTable(tahun_anggaran, id_jadwal);
        window.dataToFinalize = [];

        jQuery('#is_target_teks').on('change', function() {
            jQuery('#card-target-teks').toggleClass('d-none', !this.checked);
        });
    });

    function addSasaran() {
        resetFormValues("#form-sasaran");
        jQuery("#modal-crud-sasaran .modal-title").text("Tambah Data");
        jQuery('#hide-iku-pemda').empty();
        jQuery("#modal-crud-sasaran").modal("show");
    }

    function scrollCarousel(direction) {
        const carousel = jQuery('#card-carousel');
        const scrollAmount = carousel[0].offsetWidth;
        const currentScroll = carousel.scrollLeft();

        carousel.animate({
            scrollLeft: currentScroll + direction * scrollAmount
        }, 500);
    }

    function showModalFinalisasi() {
        jQuery('#modalFinalisasi').modal('show')
    }

    function showModalEditFinalisasi() {
        jQuery('#modalEditFinalisasi').modal('show')
    }

    function simpanFinalisasi() {
        return alert('Fitur finalisasi dokumen sedang dalam pengembangan.');
        let confirmFinalisasi = confirm('Apakah anda yakin ingin menyimpan data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        let dataPk = {
            nama_tahapan: jQuery('#nama_tahapan').val(),
            tanggal_dokumen: jQuery('#tanggal_dokumen').val()
        };

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "simpan_finalisasi_pk_pemda",
                api_key: esakip.api_key,
                data_pk: dataPk,
                data_simpan: window.dataToFinalize
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#modalFinalisasi').modal('hide');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. mohon hubungi admin.');
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
                alert('Gagal menyimpan data. mohon hubungi admin.');
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
                alert('Gagal menyimpan data. mohon hubungi admin.');
            },
        });
    }

    function showModalEditPK() {
        jQuery("#editModalLabel").show();
        jQuery("#ModalLabel").show();
        jQuery("#Modal").modal('show');
    }

    function upsertIku() {
        let all_data = getFormData(jQuery("#form-sasaran"));
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "submit_data_pk_pemda",
                api_key: esakip.api_key,
                id_jadwal: <?php echo $input['periode']; ?>,
                tahun_anggaran: <?php echo $input['tahun']; ?>,
                data: all_data
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status) {
                    alert(response.message);
                    loadDataAndGenerateTable(tahun_anggaran, id_jadwal);
                    jQuery("#modal-crud-sasaran").modal('hide');
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', error);
                alert('Gagal menyimpan data. hubungi admin.');
            }
        });
    }

    function deleteDataSasaran(id) {
        let confirmHapus = confirm('Apakah anda yakin ingin menghapus data ini?');
        if (!confirmHapus) {
            return;
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "hapus_sasaran_pk",
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    loadDataAndGenerateTable(tahun_anggaran, id_jadwal);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error('AJAX Error:', error);
                alert('Gagal menghapus data. mohon hubungi admin.');
            }
        });
    }

    function get_data_pk_pemda_by_id_ajax(id) {
        return jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "get_data_pk_pemda_by_id_ajax",
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json'
        });
    }

    function getAllDataPkPemdaAjax(tahun_anggaran, id_jadwal) {
        return jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "get_all_data_pk_pemda_by_tahun_and_id_jadwal_ajax",
                api_key: esakip.api_key,
                tahun_anggaran: tahun_anggaran,
                id_jadwal: id_jadwal
            },
            dataType: 'json'
        });
    }

    async function loadDataAndGenerateTable(tahun, id_jadwal) {
        const tableBodyWithAction = jQuery('.table-pk-body-with-action');
        const tableBodyWithoutAction = jQuery('.table-pk-body-without-action');

        try {
            jQuery('#wrap-loading').show();

            const response = await getAllDataPkPemdaAjax(tahun, id_jadwal);

            if (!response.status) {
                throw new Error(response.message);
            }

            if (response.data.length === 0) {
                const emptyRow = '<tr><td colspan="10" class="text-center">Tidak ada data yang tersedia.</td></tr>';
                tableBodyWithAction.html(emptyRow);
                tableBodyWithoutAction.html(emptyRow.replace('colspan="10"', 'colspan="9"'));
                return;
            }

            let htmlWithAction = '';
            let htmlWithoutAction = '';
            let no = 1;
            let no_2 = 1;

            response.data.forEach(item => {
                let aksi = '';

                const isFromIku = (item.id_iku && item.id_iku.length > 0);
                const isTargetTeks = (item.is_target_teks == 1);
                const isHidden = (item.is_hidden == 1);

                const hideShowText = isHidden ?
                    `<span class="badge badge-info badge-sm font-italic">Tidak digunakan di tahun ini (<?php echo $input['tahun']; ?>)</span>` :
                    ``;

                let buttonsHtml = `
                    <button class="btn btn-sm btn-warning" title="Edit Sasaran" onclick="editDataSasaran(${item.id})"><span class="dashicons dashicons-edit"></span></button>
                `;

                if (!isFromIku) {
                    buttonsHtml += `
                    <button class="btn btn-sm btn-danger mt-2" title="Hapus Sasaran" onclick="deleteDataSasaran(${item.id})"><span class="dashicons dashicons-trash"></span></button>
                `;
                }

                aksi = `<div class="btn-group-vertical" role="group">${buttonsHtml}</div>`;

                const label_sasaran = isFromIku ? item.ik_label_sasaran : item.label_sasaran;
                const indikator = isFromIku ? item.ik_label_indikator : item.label_indikator;
                const satuan = isFromIku ? item.ik_satuan : item.satuan;
                const target = isTargetTeks ? item.target_teks : item.target;
                const realisasi_1 = isTargetTeks ? item.realisasi_teks_1 : item.realisasi_1;
                const realisasi_2 = isTargetTeks ? item.realisasi_teks_2 : item.realisasi_2;
                const realisasi_3 = isTargetTeks ? item.realisasi_teks_3 : item.realisasi_3;
                const realisasi_4 = isTargetTeks ? item.realisasi_teks_4 : item.realisasi_4;

                htmlWithAction += `
                    <tr data-id="${item.id}">
                        <td class="text-center">${no_2}</td>
                        <td class="text-left">${label_sasaran || ''}<br>${hideShowText}</td>
                        <td class="text-left">${indikator || ''}</td>
                        <td class="text-center">${target || ''}</td>
                        <td class="text-right">${realisasi_1 || ''}</td>
                        <td class="text-right">${realisasi_2 || ''}</td>
                        <td class="text-right">${realisasi_3 || ''}</td>
                        <td class="text-right">${realisasi_4 || ''}</td>
                        <td class="text-center">${satuan || ''}</td>
                        <td class="text-center">${aksi}</td>
                    </tr>
                `;

                htmlWithoutAction += `
                    <tr data-id="${item.id}" ${isHidden ? 'style="display:none;"' : ''}>
                        <td class="text-center">${no}</td>
                        <td class="text-left">${label_sasaran || ''}</td>
                        <td class="text-left">${indikator || ''}</td>
                        <td class="text-center">${target || ''}</td>
                        <td class="text-right">${realisasi_1 || ''}</td>
                        <td class="text-right">${realisasi_2 || ''}</td>
                        <td class="text-right">${realisasi_3 || ''}</td>
                        <td class="text-right">${realisasi_4 || ''}</td>
                        <td class="text-center">${satuan || ''}</td>
                    </tr>
                `;

                if (!isHidden) no++;
                no_2++;
            });

            tableBodyWithAction.empty();
            tableBodyWithoutAction.empty();
            
            tableBodyWithAction.html(htmlWithAction);
            tableBodyWithoutAction.html(htmlWithoutAction);

            window.dataToFinalize = response.data;

        } catch (error) {
            const errorMessage = error.responseJSON ? error.responseJSON.message : error.message;
            alert('Terjadi kesalahan saat memuat data: ' + errorMessage);
        } finally {
            jQuery('#wrap-loading').hide();
        }
    }

    async function editDataSasaran(id) {
        try {
            resetFormValues("#form-sasaran");

            jQuery('#wrap-loading').show();

            let response = await get_data_pk_pemda_by_id_ajax(id);
            const data = response.data;

            const isFromIku = (data.id_iku) ? true : false;
            const isTargetTeks = (data.is_target_teks == 1) ? true : false;

            jQuery('input[name="id_data"]').val(data.id);
            jQuery('#hide-iku-pemda').empty();
            if (isFromIku) {
                jQuery('#hide-iku-pemda').append(`
                     <div class="form-group">
                        <label for="is_hidden">Status IKU di tahun ini (<?php echo $input['tahun']; ?>)</label>
                        <select name="is_hidden">
                            <option value="2">Digunakan</option>
                            <option value="1">Tidak Digunakan</option>
                        </select>
                    </div>
                `);

                jQuery('select[name="is_hidden"]').val(data.is_hidden);
                jQuery('input[name="id_iku"]').val(data.id_iku);
                jQuery('input[name="label_sasaran"]').val(data.ik_label_sasaran).prop("disabled", isFromIku);
                jQuery('input[name="label_indikator"]').val(data.ik_label_indikator).prop("disabled", isFromIku);
                jQuery('input[name="satuan"]').val(data.ik_satuan).prop("disabled", isFromIku);
                jQuery('#help_text').text("Catatan : Data Sasaran berasal dari IKU Pemda, tidak dapat diubah disini.");
            } else {
                jQuery('input[name="label_sasaran"]').val(data.label_sasaran).prop("disabled", isFromIku);
                jQuery('input[name="label_indikator"]').val(data.label_indikator).prop("disabled", isFromIku);
                jQuery('input[name="satuan"]').val(data.satuan).prop("disabled", isFromIku);
                jQuery('#help_text').text("");
            }

            jQuery('input[name="target"]').val(data.target);
            jQuery('input[name="realisasi_1"]').val(data.realisasi_1);
            jQuery('input[name="realisasi_2"]').val(data.realisasi_2);
            jQuery('input[name="realisasi_3"]').val(data.realisasi_3);
            jQuery('input[name="realisasi_4"]').val(data.realisasi_4);

            if (isTargetTeks) {
                jQuery('input[name="target_teks"]').val(data.target_teks);
                jQuery('input[name="realisasi_1_teks"]').val(data.realisasi_teks_1);
                jQuery('input[name="realisasi_2_teks"]').val(data.realisasi_teks_2);
                jQuery('input[name="realisasi_3_teks"]').val(data.realisasi_teks_3);
                jQuery('input[name="realisasi_4_teks"]').val(data.realisasi_teks_4);
            }

            jQuery('select[name="rumus_capaian_kinerja"]').val(data.rumus_capaian_kinerja);
            jQuery('input[name="is_target_teks"]').prop("checked", isTargetTeks).trigger("change");

            jQuery('#wrap-loading').hide();
            jQuery("#modal-crud-sasaran .modal-title").text("Edit Data");
            jQuery("#modal-crud-sasaran").modal("show");
        } catch (error) {
            jQuery('#wrap-loading').hide();
            alert('Terjadi kesalahan: ' + error.message);
        }
    }

    function resetFormValues(containerSelector) {
        const container = jQuery(containerSelector);

        if (container.length === 0) {
            console.error("Error: Container not found with selector:", containerSelector);
            return;
        }

        container.find('input[type="hidden"], input[type="number"], input[type="text"], input[type="checkbox"], textarea, select').each(function() {
            const input = jQuery(this);
            input.prop('disabled', false);

            if (input.is(':checkbox')) {
                input.prop('checked', false).trigger("change");
            } else {
                input.val('');
            }
        });

        console.log(`Form values within '${containerSelector}' have been reset.`);
    }

    function updateFont() {
        const fontFamily = document.getElementById('font-select').value;
        const fontSize = document.getElementById('font-size').value + 'px';

        document.querySelectorAll('.page-print').forEach(el => {
            el.style.fontFamily = fontFamily;
            el.style.fontSize = fontSize;
        });
    }

    function upsertDataFromIkuPemda() {
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
</script>