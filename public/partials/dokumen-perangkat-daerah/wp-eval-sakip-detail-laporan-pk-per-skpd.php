<?php
if (!defined('WPINC')) {
    die;
}
global $wpdb;

$input = shortcode_atts(array(
    'tahun' => '2022',
), $atts);

$id_skpd = $nip = '';
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
}
if (!empty($_GET) && !empty($_GET['nip'])) {
    $nip = $_GET['nip'];
}

function formatTanggalIndonesia($tanggal)
{
    // Array untuk nama bulan dalam bahasa Indonesia
    $bulan = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );

    $day = date('d', strtotime($tanggal));
    $month = $bulan[date('n', strtotime($tanggal))];
    $year = date('Y', strtotime($tanggal));

    return "$day $month $year";
}

$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
$pemda = explode(" ", $nama_pemda);
array_shift($pemda);
$pemda = implode(" ", $pemda);

$skpd = array();
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
$nama_skpd = (!empty($skpd)) ? $skpd['nama_skpd'] : '';
$alamat_kantor = (!empty($skpd)) ? $skpd['alamat_kantor'] : '';

$error_api = array(
    'status' => 0,
    'message' => 'Berhasil Get API'
);
if (!empty($nip)) {
    $data_satker = $wpdb->get_row(
        $wpdb->prepare("
            SELECT 
                p.*,
                ds.nama AS nama_bidang
            FROM esakip_data_pegawai_simpeg p
            LEFT JOIN esakip_data_satker_simpeg ds
                   ON ds.satker_id = p.satker_id
            WHERE p.nip_baru=%d
              AND p.active = 1
        ", $nip),
        ARRAY_A
    );

    $data_atasan = array();
    $cek_kepala_skpd = 0;
    $cek_nama_kepala_daerah = 0;
    $cek_status_jabatan_kepala_daerah = 0;
    $cek_status_jabatan_kepala_pihak_pertama = 0;
    $cek_status_jabatan_kepala_pihak_kedua = 0;
    $status_jabatan_kepala_daerah = '';
    $dataPegawai = array();
    $data_detail = array(
        'nama_pegawai' => '',
        'nip_pegawai' => '',
        'bidang_pegawai' => '',
        'jabatan_pegawai' => '',
        'nama_golruang' => '',
        'gelar_depan' => '',
        'gelar_belakang' => '',
        'status_jabatan' => '',
        'nama_pegawai_lengkap' => ''
    );
    $data_detail_atasan = array(
        'nama_pegawai_atasan' => '',
        'jabatan_pegawai_atasan' => '',
        'nip_pegawai_atasan' => '',
        'nama_golruang_atasan' => '',
        'gelar_depan' => '',
        'gelar_belakang' => '',
        'nama_pegawai_atasan_lengkap' => ''
    );
    if (!empty($data_satker)) {
        $data_detail['nama_pegawai'] = $data_satker['nama_pegawai'];
        $data_detail['nama_pegawai_lengkap'] = $data_satker['nama_pegawai'];
        $data_detail['nip_pegawai'] = $data_satker['nip_baru'];
        $data_detail['bidang_pegawai'] = $data_satker['nama_bidang'];
        $data_detail['jabatan_pegawai'] = $data_satker['jabatan'] . ' ' . $data_satker['nama_bidang'];

        $cek_kepala = strlen($data_satker['satker_id']);
        $date_hari_ini = current_datetime()->format('Y-m-d H:i:s');
        $status_kepala_skpd = 0;
        if ($data_satker['plt_plh'] == 1 && ($data_satker['tmt_sk_plth'] < $date_hari_ini && $date_hari_ini < $data_satker['berakhir'])) {
            $status_kepala_skpd = 1;
            $cek_status_jabatan_kepala_pihak_pertama = 1;
        }

        /** cek pegawai kepala opd atau bukan */
        if (($cek_kepala == 2 && $data_satker['tipe_pegawai_id'] == 11 && $status_kepala_skpd == 1) || ($cek_kepala == 2 && $data_satker['tipe_pegawai_id'] == 11 && $data_satker['plt_plh'] == 0)) {
            $cek_kepala_skpd = 1;
            $nama_kepala_daerah = get_option('_crb_kepala_daerah');
            $status_jabatan_kepala_daerah = get_option('_crb_status_jabatan_kepala_daerah');
            if (!empty($nama_kepala_daerah)) {
                $cek_nama_kepala_daerah = 1;
            }
            $jabatan_kepala = '';
            if (!empty($status_jabatan_kepala_daerah)) {
                $cek_status_jabatan_kepala_daerah = 1;
                $jabatan_kepala = $status_jabatan_kepala_daerah;
            }
            $data_atasan = [
                'nama_pegawai' => $nama_kepala_daerah,
                'jabatan' => $jabatan_kepala . ' ' . $pemda,
                'status_kepala' => 'kepala_daerah'
            ];
        }
        if (empty($data_atasan)) {
            if ($data_satker['tipe_pegawai_id'] == 11 && $data_satker['plt_plh'] == 1) {
                /**Jika satker id sama dengan kepala || setelah menjadi PJ selesai */
                $data_atasan = $wpdb->get_row(
                    $wpdb->prepare("
                        SELECT
                            p.*,
                            ds.nama AS nama_bidang
                        FROM esakip_data_pegawai_simpeg p
                        LEFT JOIN esakip_data_satker_simpeg ds
                            ON ds.satker_id = p.satker_id
                        WHERE p.satker_id=%s 
                          AND p.tipe_pegawai_id=%d 
                          AND p.active=1
                        ORDER BY p.tipe_pegawai_id, p.berakhir DESC 
                    ", $data_satker['satker_id'], 11),
                    ARRAY_A
                );
            } else if ($data_satker['tipe_pegawai_id'] == 11) {
                /**Jika status jabatan kepala, berarti mengambil id satker atasannya */
                $satker_id_atasan = substr($data_satker['satker_id'], 0, -2);
                $data_atasan = $wpdb->get_row(
                    $wpdb->prepare("
                        SELECT
                            p.*,
                            ds.nama AS nama_bidang
                        FROM esakip_data_pegawai_simpeg p
                        LEFT JOIN esakip_data_satker_simpeg ds
                               ON ds.satker_id = p.satker_id
                        WHERE p.satker_id=%s 
                          AND p.tipe_pegawai_id=%d 
                          AND p.active=1
                        ORDER BY p.tipe_pegawai_id, p.berakhir DESC 
                    ", $satker_id_atasan, 11),
                    ARRAY_A
                );
            } else {
                $data_atasan = $wpdb->get_row(
                    $wpdb->prepare("
                        SELECT
                            p.*,
                            ds.nama AS nama_bidang
                        FROM esakip_data_pegawai_simpeg p
                        LEFT JOIN esakip_data_satker_simpeg ds
                               ON ds.satker_id = p.satker_id
                        WHERE p.satker_id=%s 
                          AND p.tipe_pegawai_id=%d 
                          AND p.active=1
                        ORDER BY p.tipe_pegawai_id, p.berakhir DESC 
                    ", $data_satker['satker_id'], 11),
                    ARRAY_A
                );
            }
        }
        $data_detail_atasan['nama_pegawai_atasan'] = (!empty($data_atasan['nama_pegawai'])) ? $data_atasan['nama_pegawai'] : '';
        $data_detail_atasan['nama_pegawai_atasan_lengkap'] = (!empty($data_atasan['nama_pegawai'])) ? $data_atasan['nama_pegawai'] : '';
        $data_detail_atasan['nip_pegawai_atasan'] = (!empty($data_atasan['nip_baru'])) ? $data_atasan['nip_baru'] : '';

        if (!empty($data_atasan['plt_plh'])) {
            if ($data_atasan['plt_plh'] == 1 && ($data_atasan['tmt_sk_plth'] < $date_hari_ini && $date_hari_ini < $data_atasan['berakhir'])) {
                $cek_status_jabatan_kepala_pihak_kedua = 1;
            }
        }
        if (!empty($data_atasan['status_kepala']) && !empty($data_atasan['jabatan'])) {
            $data_detail_atasan['jabatan_pegawai_atasan'] = $data_atasan['jabatan'];
        } else if (!empty($data_atasan['jabatan'])) {
            $data_detail_atasan['jabatan_pegawai_atasan'] = $data_atasan['jabatan'] . ' ' . $data_atasan['nama_bidang'];
        }

        if (empty($data_atasan['status_kepala'])) {
            $path = 'api/pegawai/' . $data_detail_atasan['nip_pegawai_atasan'] . '/jabatan';
            $option = array(
                'url' => get_option('_crb_url_api_simpeg') . $path,
                'type' => 'get',
                'header' => array('Authorization: Basic ' . get_option('_crb_authorization_api_simpeg'))
            );

            $response = $this->functions->curl_post($option);

            if (empty($response)) {
                $error_api = array(
                    'status' => 1,
                    'message' => 'Respon API kosong!'
                );
            } else if ($response == 'Unauthorized') {
                $error_api = array(
                    'status' => 1,
                    'message' => $response . ' ' . json_encode($opsi)
                );
            }

            $dataPegawaiAtasan = json_decode($response, true);
            if (!empty($dataPegawaiAtasan[0]['nmgolruang'])) {
                $data_detail_atasan['nama_golruang_atasan'] = $dataPegawaiAtasan[0]['nmgolruang'];
                $data_detail_atasan['gelar_depan'] = $dataPegawaiAtasan[0]['gelar_depan'];
                $data_detail_atasan['gelar_belakang'] = $dataPegawaiAtasan[0]['gelar_belakang'];
                $data_detail_atasan['nama_pegawai_atasan_lengkap'] = $data_detail_atasan['gelar_depan'] . ' ' . $data_detail_atasan['nama_pegawai_atasan'] . ', ' . $data_detail_atasan['gelar_belakang'];
            }
        }

        // hasil ploting di halaman RHK
        $data_ploting_rhk = $wpdb->get_results(
            $wpdb->prepare("
                SELECT 
                    id,
                    label,
                    level
                FROM esakip_data_rencana_aksi_opd
                WHERE id_skpd=%d 
                  AND tahun_anggaran=%d 
                  AND nip=%d 
                  AND active=1
            ", $id_skpd, $input['tahun'], $data_satker['nip_baru']),
            ARRAY_A
        );

        $html_sasaran = '';
        $data_anggaran = array(
            'sasaran'       => array(),
            'program'       => array(),
            'kegiatan'      => array(),
            'sub_kegiatan'  => array()
        );
        $no_2 = 1;
        if (!empty($data_ploting_rhk)) {
            foreach ($data_ploting_rhk as $v_rhk) {
                $data_indikator_ploting_rhk = $wpdb->get_results(
                    $wpdb->prepare("
                        SELECT
                            indikator,
                            satuan,
                            target_awal,
                            target_akhir
                        FROM esakip_data_rencana_aksi_indikator_opd
                        WHERE id_renaksi=%d 
                          AND active=1
                    ", $v_rhk['id']),
                    ARRAY_A
                );
                $html_indikator = '';
                $p_i = 1;
                if (!empty($data_indikator_ploting_rhk)) {
                    if (count($data_indikator_ploting_rhk) > 0) {
                        $p_i = count($data_indikator_ploting_rhk) + 1;
                    }
                    foreach ($data_indikator_ploting_rhk as $v_indikator) {
                        $html_indikator .= '<tr>
                            <td class="text-left">' . $v_indikator['indikator'] . '</td>
                            <td class="text-left" style="width: 5rem;">' . $v_indikator['target_akhir'] . ' ' . $v_indikator['satuan'] . '</td></tr>';
                    }
                }
                $html_indikator_if = !empty($html_indikator) ? '' : "<td></td><td></td>";
                $html_sasaran .= '<tr>
                    <td rowspan="' . $p_i . '" class="text-center" style="width: 3rem;">' . $no_2++ . '</td>
                    <td rowspan="' . $p_i . '" class="text-left" style="width: 20rem;">' . $v_rhk['label'] . '</td>
                    ' . $html_indikator_if . '
                    </tr>';

                $html_sasaran .= $html_indikator;

                $data_rhk_child = $wpdb->get_results(
                    $wpdb->prepare("
                        SELECT *
                        FROM esakip_data_rencana_aksi_opd 
                        WHERE parent=%d 
                          AND level=%d 
                          AND id_skpd=%d
                          AND active=1
                        ORDER BY id
                    ", $v_rhk['id'], $v_rhk['level'] + 1, $id_skpd),
                    ARRAY_A
                );

                $jenis_level = array(
                    '1' => 'sasaran',
                    '2' => 'program',
                    '3' => 'kegiatan',
                    '4' => 'sub_kegiatan'
                );
                $no = 1;
                if (!empty($data_rhk_child)) {
                    foreach ($data_rhk_child as $v_rhk_child) {
                        if (!empty($v_rhk_child['label_cascading_' . $jenis_level[$v_rhk_child['level']]])) {
                            if (empty($data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']])) {
                                $nama_label = preg_replace('/^[A-Za-z0-9.]+ /', '', $v_rhk_child['label_cascading_' . $jenis_level[$v_rhk_child['level']]]);

                                $data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']] = array(
                                    'nama' => $nama_label,
                                    'kode' => $v_rhk_child['kode_cascading_' . $jenis_level[$v_rhk_child['level']]],
                                    'total_anggaran' => 0,
                                );
                            }

                            $data_indikator_anggaran = $wpdb->get_results(
                                $wpdb->prepare("
                                    SELECT rencana_pagu
                                    FROM esakip_data_rencana_aksi_indikator_opd 
                                    WHERE id_renaksi=%d 
                                      AND active = 1
                                ", $v_rhk_child['id']),
                                ARRAY_A
                            );
                            if (!empty($data_indikator_anggaran)) {
                                foreach ($data_indikator_anggaran as $v_indikator_anggaran) {
                                    $data_anggaran[$jenis_level[$v_rhk_child['level']]][$v_rhk_child['kode_cascading_sub_kegiatan']]['total_anggaran'] += $v_indikator_anggaran['rencana_pagu'];
                                }
                            }
                        }
                    }
                }

                $html_program = '';
                if (!empty($data_anggaran['program'])) {
                    $no = 1;
                    foreach ($data_anggaran['program'] as $v_program) {
                        $html_program .= '<tr>
                            <td class="text-center">' . $no++ . '</td>
                            <td class="text-left">' . $v_program['kode'] . '</td>
                            <td class="text-left">' . $v_program['nama'] . '</td>
                            <td class="text-right">' . number_format($v_program['total_anggaran'], 0, ",", ".") . '</td>
                            <td></td>
                        </tr>';
                    }
                }

                $html_kegiatan = '';
                if (!empty($data_anggaran['kegiatan'])) {
                    $no = 1;
                    foreach ($data_anggaran['kegiatan'] as $v_kegiatan) {
                        $html_kegiatan .= '<tr>
                            <td class="text-center">' . $no++ . '</td>
                            <td class="text-left">' . $v_kegiatan['kode'] . '</td>
                            <td class="text-left">' . $v_kegiatan['nama'] . '</td>
                            <td class="text-right">' . number_format($v_kegiatan['total_anggaran'], 0, ",", ".") . '</td>
                            <td></td>
                        </tr>';
                    }
                }

                $html_sub_kegiatan = '';
                if (!empty($data_anggaran['sub_kegiatan'])) {
                    $no = 1;
                    foreach ($data_anggaran['sub_kegiatan'] as $v_sub_kegiatan) {
                        $html_sub_kegiatan .= '<tr>
                            <td class="text-center">' . $no++ . '</td>
                            <td class="text-left">' . $v_sub_kegiatan['kode'] . '</td>
                            <td class="text-left">' . $v_sub_kegiatan['nama'] . '</td>
                            <td class="text-right">' . number_format($v_sub_kegiatan['total_anggaran'], 0, ",", ".") . '</td>
                            <td></td>
                        </tr>';
                    }
                }
            }
        }
    } else {
        echo "Data Pegawai Tidak Ditemukan!";
        die();
    }

    $path = 'api/pegawai/' . $nip . '/jabatan';
    $option = array(
        'url'    => get_option('_crb_url_api_simpeg') . $path,
        'type'   => 'get',
        'header' => array('Authorization: Basic ' . get_option('_crb_authorization_api_simpeg'))
    );

    $response = $this->functions->curl_post($option);

    if (empty($response)) {
        $error_api = array(
            'status'  => 1,
            'message' => 'Respon API kosong!'
        );
    } else if ($response == 'Unauthorized') {
        $error_api = array(
            'status'  => 1,
            'message' => $response . ' ' . json_encode($opsi)
        );
    }

    $dataPegawai = json_decode($response, true);
    if (!empty($dataPegawai[0]['nmgolruang'])) {
        $data_detail['nama_golruang'] = $dataPegawai[0]['nmgolruang'];
        $data_detail['gelar_depan'] = $dataPegawai[0]['gelar_depan'];
        $data_detail['gelar_belakang'] = $dataPegawai[0]['gelar_belakang'];
        $data_detail['nama_pegawai_lengkap'] = $data_detail['gelar_depan'] . ' ' . $data_detail['nama_pegawai'] . ', ' . $data_detail['gelar_belakang'];
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        $error_api = array(
            'status'  => 1,
            'message' => "Terjadi kesalahan ketika mengakses API, Error : " . json_last_error_msg()
        );
    }

    $text_tanggal_hari_ini = formatTanggalIndonesia(current_datetime()->format('Y-m-d'));
}

$data_tahapan = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            nama_tahapan,
            tanggal_dokumen
        FROM esakip_finalisasi_tahap_laporan_pk
        WHERE id_skpd=%d 
          AND nip=%d
          AND tahun_anggaran=%d
          AND active=1
    ", $id_skpd, $data_detail['nip_pegawai'], $input['tahun']),
    ARRAY_A
);
$card = '';
if ($data_tahapan) {
    foreach ($data_tahapan as $v) {
        $tanggal_dokumen = formatTanggalIndonesia($v['tanggal_dokumen']);
        $nama_tahapan = $v['nama_tahapan'];

        $card .= '
        <div class="cr-item">
            <div class="cr-card">
                <h3>' . htmlspecialchars($nama_tahapan) . '</h3>
                <div class="year">' . htmlspecialchars($tanggal_dokumen) . '</div>
                <div class="cr-view-btn" onclick="viewReport(\'' . htmlspecialchars($nama_tahapan) . '\')">
                    <span class="dashicons dashicons-visibility"></span>
                </div>
            </div>
        </div>';
    }
}


$logo_pemda = get_option('_crb_logo_dashboard');
if (empty($logo_pemda)) {
    $logo_pemda = '';
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

            .f-12 {
                font-size: 16px;
                line-height: 24px;
                color: #555;
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
            font-size: 14pt;
        }

        .title-pk-2 {
            font-size: 16pt;
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
            padding: 16px;
            width: 200px;
            /* Atur ukuran card */
            height: 150px;
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

        .cr-card .cr-view-btn {
            background-color: #fff;
            border: 1px solid #dcdcde;
            border-radius: 50%;
            width: 36px;
            height: 36px;
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

        @media (max-width: 1024px) {
            .cr-item {
                flex: 0 0 calc(33.333% - 14px);
            }
        }

        @media (max-width: 768px) {
            .cr-item {
                flex: 0 0 calc(50% - 10px);
            }
        }

        @media (max-width: 480px) {
            .cr-item {
                flex: 0 0 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container-md mx-auto" style="width: 900px;">
        <div class="text-center" id="action-sakip">
            <button class="btn btn-primary btn-large" onclick="window.print();"><i class="dashicons dashicons-printer"></i> Cetak / Print</button><br>
        </div>
        <div class="cr-container m-4 hide-display-print">
            <h2 class="cr-title">Pilih Laporan Perjanjian Kinerja</h2>
            <div class="cr-carousel-wrapper">
                <div id="reportCarousel" class="cr-carousel">
                    <div class="cr-item">
                        <div class="cr-card">
                            <h3>Perjanjian Kinerja Sekarang</h3>
                            <div class="year"><?php echo $text_tanggal_hari_ini; ?></div>
                            <div class="text-center">
                                <span class='badge badge-sm badge-primary m-2'>Sedang Dilihat</span>
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
                <button class="btn btn-sm btn-success hide-display-print" onclick="showModalFinalisasi()">
                    <span class="dashicons dashicons-saved" title="Finalisasikan dokumen untuk disimpan"></span>
                    Finalisasi Dokumen
                </button>
            </div>
            <div class="row" style="border-bottom: 7px solid;">
                <div class="col-2" style="display: flex; align-items: center; height: 200px;">
                    <?php if (!empty($logo_pemda)) : ?>
                        <img style="max-width: 100%; height: auto;" src="<?php echo $logo_pemda; ?>" alt="Logo Pemda">
                    <?php endif; ?>
                </div>
                <div class="col my-auto">
                    <p class="title-pk-1">PEMERINTAH <?php echo strtoupper($nama_pemda); ?></p>
                    <p class="title-pk-2"><?php echo strtoupper($nama_skpd); ?></p>
                    <p class="title-pk-1" id="alamat_kantor"><?php echo $alamat_kantor ?></p>
                </div>
                <div class="col-1"></div>
            </div>
            <p class="title-laporan mt-3 mb-2">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
            <p class="text-left f-12 mt-5">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</p>
            <table id="table-1" class="text-left f-12">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?php echo $data_detail['gelar_depan'] . ' ' . $data_detail['nama_pegawai'] . ', ' . $data_detail['gelar_belakang']; ?></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td class="status-jabatan-pegawai-1"><?php echo $data_detail['jabatan_pegawai']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Selanjutnya disebut pihak pertama</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?php echo $data_detail_atasan['gelar_depan'] . ' ' . $data_detail_atasan['nama_pegawai_atasan'] . ', ' . $data_detail_atasan['gelar_belakang']; ?></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td class="status-jabatan-pegawai-2"><?php echo $data_detail_atasan['jabatan_pegawai_atasan']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Selaku atasan langsung pihak pertama, selanjutnya disebut pihak kedua</td>
                </tr>
            </table>
            <p class="text-left f-12">Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target tersebut menjadi tanggung jawab kami.</p>
            </br>
            <p class="text-left f-12">Pihak kedua akan memberikan supervisi yang diperlukan serta akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka memberikan penghargaan dan sanksi.</p>
            <table id="table_data_pejabat" style="margin-top: 3rem;" class="f-12">
                <thead>
                    <tr class="text-center">
                        <td></td>
                        <td contenteditable="true" title="Klik untuk ganti teks!">
                            <?php echo $pemda; ?>, <?php echo $text_tanggal_hari_ini; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Pihak Kedua,</td>
                        <td>Pihak Pertama,</td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 7em;">
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="text-center">
                        <td class="ttd-pejabat" id="nama_pegawai_atasan">
                            <?php echo $data_detail_atasan['gelar_depan'] . ' ' . $data_detail_atasan['nama_pegawai_atasan'] . ', ' . $data_detail_atasan['gelar_belakang']; ?>
                        </td>
                        <td class="ttd-pejabat">
                            <?php echo $data_detail['gelar_depan'] . ' ' . $data_detail['nama_pegawai'] . ', ' . $data_detail['gelar_belakang']; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;" id="pangkat_pegawai_atasan">
                            <?php if (empty($data_atasan['status_kepala'])) : ?>
                                <?php echo $data_detail_atasan['nama_golruang_atasan']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            <?php echo $data_detail['nama_golruang']; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;" id="nip_pegawai_atasan">
                            <?php if (empty($data_atasan['status_kepala'])) : ?>
                                NIP. <?php echo $data_detail_atasan['nip_pegawai_atasan']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            NIP. <?php echo $data_detail['nip_pegawai']; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="break-print"></div>
        <div class="page-print mt-5 text-center">
            <p class="title-laporan mt-3">PERJANJIAN KINERJA TAHUN <?php echo $input['tahun']; ?></p>
            <p class="title-laporan mb-5"><?php echo $data_detail['bidang_pegawai']; ?></p>
            <?php if ($html_sasaran != '') : ?>
                <table class="table_data_anggaran">
                    <thead>
                        <tr>
                            <th class="esakip-text_tengah" style="width: 5px;">No</th>
                            <th class="esakip-text_tengah" style="width: 50%;">Sasaran</th>
                            <th class="esakip-text_tengah" style="width: 30%;">Indikator</th>
                            <th class="esakip-text_tengah" style="width: 20%;">Target</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $html_sasaran; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (!empty($html_program)) : ?>
                <table class="table_data_anggaran">
                    <thead>
                        <tr>
                            <th class="esakip-text_tengah" style="width: 5px;">No</th>
                            <th class="esakip-text_tengah" style="width: 150px;">Kode</th>
                            <th class="esakip-text_tengah" style="width: 50%;">Program</th>
                            <th class="esakip-text_tengah">Anggaran</th>
                            <th class="esakip-text_tengah">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $html_program; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (!empty($html_kegiatan)) : ?>
                <table class="table_data_anggaran">
                    <thead>
                        <tr>
                            <th class="esakip-text_tengah" style="width: 5px;">No</th>
                            <th class="esakip-text_tengah" style="width: 150px;">Kode</th>
                            <th class="esakip-text_tengah" style="width: 50%;">Kegiatan</th>
                            <th class="esakip-text_tengah">Anggaran</th>
                            <th class="esakip-text_tengah">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $html_kegiatan; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (!empty($html_sub_kegiatan)) : ?>
                <table class="table_data_anggaran">
                    <thead>
                        <tr>
                            <th class="esakip-text_tengah" style="width: 5px;">No</th>
                            <th class="esakip-text_tengah" style="width: 150px;">Kode</th>
                            <th class="esakip-text_tengah" style="width: 50%;">Sub Kegiatan</th>
                            <th class="esakip-text_tengah">Anggaran</th>
                            <th class="esakip-text_tengah">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $html_sub_kegiatan; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <table id="table_data_pejabat" class="f-12 mt-5">
                <thead>
                    <tr class="text-center">
                        <td></td>
                        <td contenteditable="true" title="Klik untuk ganti teks!">
                            <?php echo $pemda; ?>, <?php echo $text_tanggal_hari_ini; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td id="status-jabatan-ttd-1"><?php echo $data_detail_atasan['jabatan_pegawai_atasan']; ?></td>
                        <td id="status-jabatan-ttd-2"><?php echo $data_detail['jabatan_pegawai']; ?>,</td>
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
                            <?php echo $data_detail_atasan['gelar_depan'] . ' ' . $data_detail_atasan['nama_pegawai_atasan'] . ', ' . $data_detail_atasan['gelar_belakang']; ?>
                        </td>
                        <td class="ttd-pejabat">
                            <?php echo $data_detail['gelar_depan'] . ' ' . $data_detail['nama_pegawai'] . ', ' . $data_detail['gelar_belakang']; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;">
                            <?php if (empty($data_atasan['status_kepala'])) : ?>
                                <?php echo $data_detail_atasan['nama_golruang_atasan']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            <?php echo $data_detail['nama_golruang']; ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="padding: 0;">
                            <?php if (empty($data_atasan['status_kepala'])) : ?>
                                NIP. <?php echo $data_detail_atasan['nip_pegawai_atasan']; ?>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 0;">
                            NIP. <?php echo $data_detail['nip_pegawai']; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

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
                <input type="hidden" id="id_data" name="id_data">

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
                                    <td class="text-left" id="nama_pegawai"><?php echo $data_detail['gelar_depan'] . ' ' . $data_detail['nama_pegawai'] . ', ' . $data_detail['gelar_belakang']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>NIP</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="nip_pegawai"><?php echo $data_detail['nip_pegawai']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Pangkat</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="pangkat_pegawai"><?php echo $data_detail['nama_golruang']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Jabatan</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="jabatan_pegawai"><?php echo $data_detail['jabatan_pegawai']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Satuan Kerja</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="bidang_pegawai"><?php echo strtoupper($data_detail['bidang_pegawai']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>OPD</strong>
                                    </td>
                                    <td class="text-left">
                                        <strong>:</strong>
                                    </td>
                                    <td class="text-left" id="nama_skpd"><?php echo strtoupper($nama_skpd); ?>
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
                        <?php if ($html_sasaran != '') : ?>
                            <table class="table_data_anggaran" id="table_sasaran">
                                <thead>
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 5px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 50%;">Sasaran</th>
                                        <th class="esakip-text_tengah" style="width: 30%;">Indikator</th>
                                        <th class="esakip-text_tengah" style="width: 20%;">Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_sasaran; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($html_program)) : ?>
                            <table class="table_data_anggaran" id="table_program">
                                <thead>
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 5px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 150px;">Kode</th>
                                        <th class="esakip-text_tengah" style="width: 50%;">Program</th>
                                        <th class="esakip-text_tengah">Anggaran</th>
                                        <th class="esakip-text_tengah">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_program; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($html_kegiatan)) : ?>
                            <table class="table_data_anggaran" id="table_kegiatan">
                                <thead>
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 5px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 150px;">Kode</th>
                                        <th class="esakip-text_tengah" style="width: 50%;">Kegiatan</th>
                                        <th class="esakip-text_tengah">Anggaran</th>
                                        <th class="esakip-text_tengah">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_kegiatan; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($html_sub_kegiatan)) : ?>
                            <table class="table_data_anggaran" id="table_subkegiatan">
                                <thead>
                                    <tr>
                                        <th class="esakip-text_tengah" style="width: 5px;">No</th>
                                        <th class="esakip-text_tengah" style="width: 150px;">Kode</th>
                                        <th class="esakip-text_tengah" style="width: 50%;">Sub Kegiatan</th>
                                        <th class="esakip-text_tengah">Anggaran</th>
                                        <th class="esakip-text_tengah">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $html_sub_kegiatan; ?>
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
                                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" placeholder="ex : Perjanjian Kinerja tahun <?php echo $input['tahun']; ?>" maxlength="48">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tanggal_dokumen">Tanggal Dokumen</label>
                                <input type="date" class="form-control" id="tanggal_dokumen" name="tanggal_dokumen">
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
<script>
    jQuery(document).ready(function() {
        let cek_kepala_skpd = <?php echo $cek_kepala_skpd; ?>;
        let cek_nama_kepala_daerah = <?php echo $cek_nama_kepala_daerah; ?>;
        let cek_status_jabatan_kepala_daerah = <?php echo $cek_status_jabatan_kepala_daerah; ?>;
        if (cek_kepala_skpd == 1 && (cek_nama_kepala_daerah == 0)) {
            alert("Harap Isi Nama Kepala Daerah Di Esakip Options!");
        }
        if (cek_kepala_skpd == 1 && (cek_status_jabatan_kepala_daerah == 0)) {
            alert("Harap Isi Status Jabatan Kepala Daerah Di Esakip Options!");
        }
        let status_error_api = <?php echo $error_api['status']; ?>;
        if (status_error_api == 1) {
            console.log("<?php echo $error_api['message']; ?>");
        }
        let cek_status_jabatan_kepala_pihak_pertama = <?php echo $cek_status_jabatan_kepala_pihak_pertama; ?>;
        let nama_pejabat = "<?php echo $data_detail['nama_pegawai_lengkap']; ?>";
        let input_status_jabatan_pertama = "";
        if (cek_status_jabatan_kepala_pihak_pertama == 1) {
            input_status_jabatan_pertama = window.prompt("Harap isi status jabatan (PJ, PLT, PLH) atas nama " + nama_pejabat + " sebagai pihak pertama!");
        }

        if (input_status_jabatan_pertama !== null && input_status_jabatan_pertama.trim() !== "") {
            jQuery(".status-jabatan-pegawai-1").prepend(input_status_jabatan_pertama + " ");
            jQuery("#status-jabatan-ttd-1").prepend(input_status_jabatan_pertama + " ");
            window.status_jabatan_1 = input_status_jabatan_pertama;
        }

        let cek_status_jabatan_kepala_pihak_kedua = <?php echo $cek_status_jabatan_kepala_pihak_kedua; ?>;
        console.log(cek_status_jabatan_kepala_pihak_kedua);
        let nama_pejabat_kedua = "<?php echo $data_detail_atasan['nama_pegawai_atasan_lengkap']; ?>";
        let input_status_jabatan_kedua = "";
        if (cek_status_jabatan_kepala_pihak_kedua == 1) {
            input_status_jabatan_kedua = window.prompt("Harap isi status jabatan (PJ, PLT, PLH) atas nama " + nama_pejabat_kedua + " sebagai pihak kedua!");
        }

        if (input_status_jabatan_kedua !== null && input_status_jabatan_kedua.trim() !== "") {
            jQuery(".status-jabatan-pegawai-2").prepend(input_status_jabatan_kedua + " ");
            jQuery("#status-jabatan-ttd-2").prepend(input_status_jabatan_kedua + " ");
            window.status_jabatan_2 = input_status_jabatan_kedua;
        }
    });

    function scrollCarousel(direction) {
        const carousel = jQuery('#reportCarousel');
        const scrollAmount = carousel[0].offsetWidth;
        const currentScroll = carousel.scrollLeft();

        carousel.animate({
            scrollLeft: currentScroll + direction * scrollAmount
        }, 500);
    }


    function viewReport(title) {
        alert(`Viewing report: ${title}`);
        // // jQuery('#wrap-loading').show()
        // // jQuery.ajax({
        // //     url: esakip.url,
        // //     method: 'POST',
        // //     data: {
        // //         action: "simpan_finalisasi_laporan_pk",
        // //         api_key: esakip.api_key,
               
        // //         tahun_anggaran: '<?php echo $input['tahun']; ?>',
        // //         id_skpd: '<?php echo $id_skpd; ?>'
        // //     },
        // //     dataType: 'json',
        // //     success: function(response) {
        // //         jQuery('#wrap-loading').hide()
        // //         if (response.status === 'success') {
        // //             alert(response.message);
        // //         } else {
        // //             alert('Terjadi kesalahan: ' + response.message);
        // //         }
        // //     },
        // //     error: function(xhr, status, error) {
        // //         jQuery('#wrap-loading').hide()
        // //         console.error('AJAX Error:', error);
        // //         alert('Gagal menyimpan data. Silakan coba lagi.');
        // //     },
        // });
    }

    function showModalFinalisasi() {
        jQuery('#modalFinalisasi').modal('show')
    }

    function simpanFinalisasi() {
        let confirmFinalisasi = confirm('Apakah anda yakin ingin menyimpan data ini?');
        if (!confirmFinalisasi) {
            return;
        }

        let pegawaiData = {
            nama: jQuery('#nama_pegawai').text(),
            nip: jQuery('#nip_pegawai').text(),
            pangkat: jQuery('#pangkat_pegawai').text(),
            jabatan: jQuery('#jabatan_pegawai').text(),
            nama_skpd: jQuery('#nama_skpd').text(),
            alamat_kantor: jQuery('#alamat_kantor').text(),
            satuan_kerja: jQuery('#bidang_pegawai').text(),
            nama_atasan: jQuery('#nama_pegawai_atasan').text().trim(),
            pangkat_atasan: jQuery('#pangkat_pegawai_atasan').text().trim(),
            nip_atasan: jQuery('#nip_pegawai_atasan').text().trim().replace(/^NIP\.?\s?/, "")
        };

        let dokumenPk = {
            nama_tahapan: jQuery('#nama_dokumen').val(),
            tanggal_dokumen: jQuery('#tanggal_dokumen').val()
        };

        let sasarans = [];
        let currentSasaran = null;

        jQuery('#table_sasaran tbody tr').each(function() {
            let sasaranCell = jQuery(this).find('td:nth-child(2)'); // Kolom Sasaran
            let indikatorCell = jQuery(this).find('td:nth-child(3)').text().trim(); // Kolom Indikator
            let targetCell = jQuery(this).find('td:nth-child(4)').text().trim(); // Kolom Target

            if (sasaranCell.length && sasaranCell.attr('rowspan')) {
                if (currentSasaran) {
                    sasarans.push(currentSasaran);
                }

                currentSasaran = {
                    sasaran: sasaranCell.text().trim(),
                    indikator: []
                };
            }

            if (currentSasaran) {
                currentSasaran.indikator.push({
                    nama: indikatorCell || "Tidak ada indikator",
                    target: targetCell || "Tidak ada target"
                });
            }
        });

        if (currentSasaran) {
            sasarans.push(currentSasaran);
        }

        let programs = [];
        jQuery('#table_program tbody tr').each(function() {
            let row = {
                kode: jQuery(this).find('td:nth-child(2)').text().trim(),
                program: jQuery(this).find('td:nth-child(3)').text().trim(),
                anggaran: jQuery(this).find('td:nth-child(4)').text().trim().replace(/\./g, ''),
                keterangan: jQuery(this).find('td:nth-child(5)').text().trim(),
            };
            programs.push(row);
        });

        let kegiatans = [];
        jQuery('#table_kegiatan tbody tr').each(function() {
            let row = {
                kode: jQuery(this).find('td:nth-child(2)').text().trim(),
                kegiatan: jQuery(this).find('td:nth-child(3)').text().trim(),
                anggaran: jQuery(this).find('td:nth-child(4)').text().trim().replace(/\./g, ''),
                keterangan: jQuery(this).find('td:nth-child(5)').text().trim(),
            };
            kegiatans.push(row);
        });

        let subkegiatans = [];
        jQuery('#table_subkegiatan tbody tr').each(function() {
            let row = {
                kode: jQuery(this).find('td:nth-child(2)').text().trim(),
                subkegiatan: jQuery(this).find('td:nth-child(3)').text().trim(),
                anggaran: jQuery(this).find('td:nth-child(4)').text().trim().replace(/\./g, ''),
                keterangan: jQuery(this).find('td:nth-child(5)').text().trim(),
            };
            subkegiatans.push(row);
        });

        jQuery('#wrap-loading').show()
        jQuery.ajax({
            url: esakip.url,
            method: 'POST',
            data: {
                action: "simpan_finalisasi_laporan_pk",
                api_key: esakip.api_key,
                sasaran: sasarans,
                program: programs,
                kegiatan: kegiatans,
                subkegiatan: subkegiatans,
                data_pegawai: pegawaiData,
                data_pk: dokumenPk,
                tahun_anggaran: '<?php echo $input['tahun']; ?>',
                id_skpd: '<?php echo $id_skpd; ?>'
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide()
                if (response.status === 'success') {
                    alert(response.message);
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
</script>