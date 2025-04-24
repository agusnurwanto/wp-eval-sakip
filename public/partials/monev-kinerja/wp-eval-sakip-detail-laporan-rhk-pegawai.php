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
if (!empty($_GET) && !empty($_GET['nip'])) {
    $nip_baru = $_GET['nip'];
}
if (!empty($_GET) && !empty($_GET['satker_id'])) {
    $satker_id = $_GET['satker_id'];
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

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

$get_pegawai = $wpdb->get_results(
    $wpdb->prepare('
        SELECT 
            *
        FROM esakip_data_pegawai_simpeg
        WHERE satker_id = %d
          AND nip_baru = %d
          AND active = 1
        ORDER BY satker_id ASC, tipe_pegawai_id ASC, nama_pegawai ASC
    ', $satker_id, $nip_baru),
    ARRAY_A
);
$gelar_depan = '';
$nama_pegawai = '';
$gelar_belakang = '';
foreach ($get_pegawai as $pegawai) {
    $gelar_depan = $pegawai['gelar_depan'];
    $nama_pegawai = $pegawai['nama_pegawai'];
    $gelar_belakang = $pegawai['gelar_belakang'];
}

$get_renaksi_opd = $wpdb->get_results($wpdb->prepare("
    SELECT
        *
    FROM esakip_data_rencana_aksi_opd
    WHERE id_skpd=%d
        AND tahun_anggaran=%d
        AND nip=%d
        AND satker_id=%s
        AND active=1
", $id_skpd, $input['tahun'], $nip_baru, $satker_id), ARRAY_A);

$set_pagu_renaksi = get_option('_crb_set_pagu_renaksi');
$no = 1;
$body = '';

foreach ($get_renaksi_opd as $renaksi_opd) {
    $parents = [];
    $id_level_2 = null;

    function get_parent($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
            SELECT * FROM esakip_data_rencana_aksi_opd 
            WHERE id = %d AND active = 1
        ", $id), ARRAY_A);
    }

    if ($renaksi_opd['level'] == 4) {
        $parent3 = get_parent($renaksi_opd['parent']);
        if ($parent3) {
            $parents[] = $parent3;
            $parent2 = get_parent($parent3['parent']);
            if ($parent2) {
                $parents[] = $parent2;
                $id_level_2 = $parent2['id'];
                $parent1 = get_parent($parent2['parent']);
                if ($parent1) {
                    $parents[] = $parent1;
                }
            }
        }
    } elseif ($renaksi_opd['level'] == 3) {
        $parent2 = get_parent($renaksi_opd['parent']);
        if ($parent2) {
            $parents[] = $parent2;
            $id_level_2 = $parent2['id'];
            $parent1 = get_parent($parent2['parent']);
            if ($parent1) {
                $parents[] = $parent1;
            }
        }
    } elseif ($renaksi_opd['level'] == 2) {
        $id_level_2 = $renaksi_opd['id'];
        $parent1 = get_parent($renaksi_opd['parent']);
        if ($parent1) {
            $parents[] = $parent1;
        }
    }

    $get_renaksi_pemda = [];
    if ($id_level_2 !== null) {
        $get_renaksi_pemda = $wpdb->get_results($wpdb->prepare("
            SELECT 
                l.*, 
                r.*, 
                r.id AS id_renaksi
            FROM esakip_data_label_rencana_aksi AS l
            INNER JOIN esakip_data_rencana_aksi_pemda AS r
                ON r.id = l.parent_renaksi_pemda
                AND r.tahun_anggaran = l.tahun_anggaran
                AND r.active = l.active
            WHERE l.parent_renaksi_opd = %d
        ", $id_level_2), ARRAY_A);
    }

    foreach (array_reverse($parents) as $parent) {
        $nama_pokin_pemda = '';
        $level_pokin_pemda = '';

        $get_indikator = $wpdb->get_results($wpdb->prepare("
            SELECT 
                * 
            FROM esakip_data_rencana_aksi_indikator_opd 
            WHERE id_renaksi = %d 
              AND active = 1
        ", $parent['id']), ARRAY_A);

        $get_pokin = $wpdb->get_results($wpdb->prepare("
            SELECT
                p.label,
                p.level
            FROM esakip_data_pokin_rhk_opd AS o
            INNER JOIN esakip_pohon_kinerja_opd AS p 
                ON o.id_pokin = p.id
                    AND o.level_pokin = p.level
            WHERE o.id_rhk_opd = %d
                AND o.level_rhk_opd = %d
        ", $parent['id'], $parent['level']), ARRAY_A);

        $get_data_renaksi_pemda = array_filter($get_renaksi_pemda, function($item) use ($parent) {
            return $item['parent_renaksi_opd'] == $parent['id'];
        });

        foreach ($get_data_renaksi_pemda as $renaksi_pemda) {
            $get_pokin_pemda = $wpdb->get_results($wpdb->prepare("
                SELECT
                    p.label,
                    p.level
                FROM esakip_data_pokin_rhk_pemda AS o
                INNER JOIN esakip_pohon_kinerja AS p 
                    ON o.id_pokin = p.id
                    AND o.level_pokin = p.level
                WHERE o.id_rhk_pemda = %d
                    AND o.level_rhk_pemda = %d
            ", $renaksi_pemda['id'], $renaksi_pemda['level']), ARRAY_A);

            foreach ($get_pokin_pemda as $pokin_pemda) {
                $nama_pokin_pemda  .= $pokin_pemda['label'] . '<br>';  
                $level_pokin_pemda .= $pokin_pemda['level'] . '<br>';  
            }
        }
        $indikator = '';
        $satuan = '';
        $target_awal = '';
        $target_1 = '';
        $target_2 = '';
        $target_3 = '';
        $target_4 = '';
        $target_akhir = '';
        $realisasi_tw_1 = '';
        $realisasi_tw_2 = '';
        $realisasi_tw_3 = '';
        $realisasi_tw_4 = '';
        $capaian_realisasi = ''; 
        $rencana_pagu = '';
        $total_harga_tagging_rincian = 0;
        $total_realisasi_tagging_rincian = 0;
        $capaian_realisasi_pagu = ''; 
        $label_cascading = '';
        $nama_pokin = '';
        $level_pokin = '';

        $rincian_tagging = $this->functions->generatePage(array(
            'nama_page' => 'Halaman Tagging Rincian Belanja',
            'content' => '[tagging_rincian_sakip]',
            'show_header' => 1,
            'post_status' => 'private'
        ));
        if ($get_indikator) {
            foreach ($get_indikator as $key => $ind) {
                $indikator       .= '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $input['tahun'] . '&id_skpd=' . $id_skpd . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>' . '<br>';   
                $satuan          .= $ind['satuan'] . '<br>';  
                $target_awal     .= $ind['target_awal'] . '<br>';  
                $target_1        .= $ind['target_1'] . '<br>';  
                $target_2        .= $ind['target_2'] . '<br>';  
                $target_3        .= $ind['target_3'] . '<br>';  
                $target_4        .= $ind['target_4'] . '<br>';  
                $target_akhir    .= $ind['target_akhir'] . '<br>';
                $realisasi_tw_1  .= $ind['realisasi_tw_1'] . '<br>';  
                $realisasi_tw_2  .= $ind['realisasi_tw_2'] . '<br>';  
                $realisasi_tw_3  .= $ind['realisasi_tw_3'] . '<br>';  
                $realisasi_tw_4  .= $ind['realisasi_tw_4'] . '<br>';
                $total_realisasi_tw = $ind['realisasi_tw_1'] + $ind['realisasi_tw_2'] + $ind['realisasi_tw_3'] + $ind['realisasi_tw_4'];
                if (!empty($total_realisasi_tw) && !empty($ind['target_akhir'])) {
                    $capaian = number_format(($total_realisasi_tw / $ind['target_akhir']) * 100, 0 ) . "%";
                } else {
                    $capaian = "0%";
                }

                $capaian_realisasi .= $capaian . '<br>';
                $rencana_pagu .= ($set_pagu_renaksi == 1) ? '0<br>' : (!empty($ind['rencana_pagu']) ? number_format((float)$ind['rencana_pagu'], 0, ",", ".") . '<br>' : '0<br>');

                $data_tagging = $wpdb->get_results(
                    $wpdb->prepare("
                        SELECT 
                            * 
                        FROM esakip_tagging_rincian_belanja 
                        WHERE active = 1 
                          AND id_skpd = %d
                          AND id_indikator = %d
                          AND kode_sbl = %s
                    ", $parent['id_skpd'], $ind['id'], $parent['kode_sbl']),
                    ARRAY_A
                );

                if(!empty($data_tagging)){
                    foreach ($data_tagging as $value) {
                        $harga_satuan = $value['harga_satuan'];
                        $volume = $value['volume'];
                        $total_harga_tagging_rincian += $volume * $harga_satuan;
                        $total_realisasi_tagging_rincian += $value['realisasi'];
                    }
                }
            }
        }

        // Hitung capaian realisasi pagu
        if (!empty($total_realisasi_tagging_rincian) && !empty($total_harga_tagging_rincian)) {
            $capaian_pagu = number_format(($total_realisasi_tagging_rincian / $total_harga_tagging_rincian) * 100, 0 ) . "%";
        } else {
            $capaian_pagu = "0%";
        }
        $capaian_realisasi_pagu .= $capaian_pagu . '<br>';

        if ($get_pokin) {
            foreach ($get_pokin as $key => $pokin) {
                $nama_pokin .= $pokin['label'] . '<br>';  
                $level_pokin .= $pokin['level'] . '<br>';  
            }
        }

        $label_cascading = '';
        if (!empty(trim($parent['kode_cascading_program'] . $parent['label_cascading_program']))) {
            $label_cascading = $parent['kode_cascading_program'] . ' ' . $parent['label_cascading_program'];
        } elseif (!empty(trim($parent['kode_cascading_sasaran'] . $parent['label_cascading_sasaran']))) {
            $label_cascading = $parent['kode_cascading_sasaran'] . ' ' . $parent['label_cascading_sasaran'];
        } elseif (!empty(trim($parent['kode_cascading_kegiatan'] . $parent['label_cascading_kegiatan']))) {
            $label_cascading = $parent['kode_cascading_kegiatan'] . ' ' . $parent['label_cascading_kegiatan'];
        } elseif (!empty(trim($parent['kode_cascading_sub_kegiatan'] . $parent['label_cascading_sub_kegiatan']))) {
            $label_cascading = $parent['kode_cascading_sub_kegiatan'] . ' ' . $parent['label_cascading_sub_kegiatan'];
        }


        $body .= '
            <tr>
                <td class="text_tengah">' . $no++ . '</td>
                <td class="text_tengah">' . $parent['level'] . '</td>
                <td class="text_kiri">' . $parent['label'] . '</td>
                <td class="text_kiri">' . $indikator . '</td>
                <td class="text_tengah">' . $satuan . '</td>
                <td class="text_tengah">' . $target_awal . '</td>
                <td class="text_tengah">' . $target_1 . '</td>
                <td class="text_tengah">' . $target_2 . '</td>
                <td class="text_tengah">' . $target_3 . '</td>
                <td class="text_tengah">' . $target_4 . '</td>
                <td class="text_tengah">' . $target_akhir . '</td>  
                <td class="text_tengah">' . $realisasi_tw_1 . '</td>
                <td class="text_tengah">' . $realisasi_tw_2 . '</td>
                <td class="text_tengah">' . $realisasi_tw_3 . '</td>
                <td class="text_tengah">' . $realisasi_tw_4 . '</td>     
                <td class="text_tengah">' . $capaian_realisasi . '</td>
                <td class="text_kanan">' . $rencana_pagu . '</td>
                <td class="text_kanan">' . number_format((float)$total_harga_tagging_rincian, 0, ",", ".") . '</td>
                <td class="text_kanan">' . number_format((float)$total_realisasi_tagging_rincian, 0, ",", ".") . '</td>
                <td class="text_tengah">' . $capaian_realisasi_pagu . '</td>
                <td class="text_kanan">' . number_format((float)$parent['pagu_cascading'], 0, ",", ".") . '</td>
                <td class="text_kiri">' . $label_cascading . '</td>
                <td class="text_kiri">' . $nama_pokin . '</td>
                <td class="text_tengah">' . $level_pokin . '</td>
                <td class="text_kiri">' . $label_cascading . '</td>
                <td class="text_kiri">' . $nama_pokin_pemda . '</td>
                <td class="text_tengah">' . $level_pokin_pemda . '</td>
            </tr>';
    }

    $indikator = '';
    $satuan = '';
    $target_awal = '';
    $target_1 = '';
    $target_2 = '';
    $target_3 = '';
    $target_4 = '';
    $target_akhir = '';
    $realisasi_tw_1 = '';
    $realisasi_tw_2 = '';
    $realisasi_tw_3 = '';
    $realisasi_tw_4 = '';
    $capaian_realisasi = ''; 
    $rencana_pagu = '';
    $capaian_realisasi_pagu = ''; 
    $total_harga_tagging_rincian = 0;
    $total_realisasi_tagging_rincian = 0;
    $nama_pokin = '';
    $level_pokin = '';

    $get_indikator = $wpdb->get_results($wpdb->prepare("
        SELECT 
            * 
        FROM esakip_data_rencana_aksi_indikator_opd 
        WHERE id_renaksi = %d 
          AND active = 1
    ", $renaksi_opd['id']), ARRAY_A);
    if ($get_indikator) {
        foreach ($get_indikator as $key => $ind) {
            $indikator       .= $indikator       .= '<a href="' . $this->functions->add_param_get($rincian_tagging['url'], '&tahun=' . $input['tahun'] . '&id_skpd=' . $id_skpd . '&id_indikator=' . $ind['id']) . '" target="_blank">' . $ind['indikator'] . '</a>' . '<br>';  
            $satuan          .= $ind['satuan'] . '<br>';  
            $target_awal     .= $ind['target_awal'] . '<br>';  
            $target_1        .= $ind['target_1'] . '<br>';  
            $target_2        .= $ind['target_2'] . '<br>';  
            $target_3        .= $ind['target_3'] . '<br>';  
            $target_4        .= $ind['target_4'] . '<br>';  
            $target_akhir    .= $ind['target_akhir'] . '<br>';
            $realisasi_tw_1  .= $ind['realisasi_tw_1'] . '<br>';  
            $realisasi_tw_2  .= $ind['realisasi_tw_2'] . '<br>';  
            $realisasi_tw_3  .= $ind['realisasi_tw_3'] . '<br>';  
            $realisasi_tw_4  .= $ind['realisasi_tw_4'] . '<br>';

            // Hitung capaian realisasi
            $total_realisasi_tw = $ind['realisasi_tw_1'] + $ind['realisasi_tw_2'] + $ind['realisasi_tw_3'] + $ind['realisasi_tw_4'];
            if (!empty($total_realisasi_tw) && !empty($ind['target_akhir'])) {
                $capaian = number_format(($total_realisasi_tw / $ind['target_akhir']) * 100, 0 ) . "%";
            } else {
                $capaian = "0%";
            }

            $capaian_realisasi .= $capaian . '<br>';
            $rencana_pagu .= ($set_pagu_renaksi == 1) ? '0<br>' : (!empty($ind['rencana_pagu']) ? number_format((float)$ind['rencana_pagu'], 0, ",", ".") . '<br>' : '0<br>');


            $capaian_realisasi .= $capaian . '<br>';

            $data_tagging = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        * 
                    FROM esakip_tagging_rincian_belanja 
                    WHERE active = 1 
                      AND id_skpd = %d
                      AND id_indikator = %d
                      AND kode_sbl = %s
                ", $renaksi_opd['id_skpd'], $ind['id'], $renaksi_opd['kode_sbl']),
                ARRAY_A
            );

            if(!empty($data_tagging)){
                foreach ($data_tagging as $value) {
                    $harga_satuan = $value['harga_satuan'];
                    $volume = $value['volume'];
                    $total_harga_tagging_rincian += $volume * $harga_satuan;
                    $total_realisasi_tagging_rincian += $value['realisasi'];
                }
            }
        }
    }

    // Hitung capaian realisasi pagu
    if (!empty($total_realisasi_tagging_rincian) && !empty($total_harga_tagging_rincian)) {
        $capaian_pagu = number_format(($total_realisasi_tagging_rincian / $total_harga_tagging_rincian) * 100, 0 ) . "%";
    } else {
        $capaian_pagu = "0%";
    }
    $capaian_realisasi_pagu .= $capaian_pagu . '<br>';

    $label_cascading = '';
    if (!empty(trim($renaksi_opd['kode_cascading_program'] . $renaksi_opd['label_cascading_program']))) {
        $label_cascading = $renaksi_opd['kode_cascading_program'] . ' ' . $renaksi_opd['label_cascading_program'];
    } elseif (!empty(trim($renaksi_opd['kode_cascading_sasaran'] . $renaksi_opd['label_cascading_sasaran']))) {
        $label_cascading = $renaksi_opd['kode_cascading_sasaran'] . ' ' . $renaksi_opd['label_cascading_sasaran'];
    } elseif (!empty(trim($renaksi_opd['kode_cascading_kegiatan'] . $renaksi_opd['label_cascading_kegiatan']))) {
        $label_cascading = $renaksi_opd['kode_cascading_kegiatan'] . ' ' . $renaksi_opd['label_cascading_kegiatan'];
    } elseif (!empty(trim($renaksi_opd['kode_cascading_sub_kegiatan'] . $renaksi_opd['label_cascading_sub_kegiatan']))) {
        $label_cascading = $renaksi_opd['kode_cascading_sub_kegiatan'] . ' ' . $renaksi_opd['label_cascading_sub_kegiatan'];
    }

    $get_pokin = $wpdb->get_results($wpdb->prepare("
        SELECT
            p.label,
            p.level
        FROM esakip_data_pokin_rhk_opd AS o
        INNER JOIN esakip_pohon_kinerja_opd AS p 
            ON o.id_pokin = p.id
                AND o.level_pokin = p.level
        WHERE o.id_rhk_opd = %d
            AND o.level_rhk_opd = %d
    ", $renaksi_opd['id'], $renaksi_opd['level']), ARRAY_A);


    if ($get_pokin) {
        foreach ($get_pokin as $key => $pokin) {
            $nama_pokin .= $pokin['label'] . '<br>';  
            $level_pokin .= $pokin['level'] . '<br>';  
        }
    }
    if ($renaksi_opd['level'] == 2) {
        foreach ($get_renaksi_pemda as $renaksi_pemda) {
            $get_pokin_pemda = $wpdb->get_results($wpdb->prepare("
                SELECT
                    p.label,
                    p.level
                FROM esakip_data_pokin_rhk_pemda AS o
                INNER JOIN esakip_pohon_kinerja AS p 
                    ON o.id_pokin = p.id
                    AND o.level_pokin = p.level
                WHERE o.id_rhk_pemda = %d
                    AND o.level_rhk_pemda = %d
            ", $renaksi_pemda['id'], $renaksi_pemda['level']), ARRAY_A);

            foreach ($get_pokin_pemda as $pokin_pemda) {
                $nama_pokin_pemda  .= $pokin_pemda['label'] . '<br>';  
                $level_pokin_pemda .= $pokin_pemda['level'] . '<br>';  
            }
        }
    }
    $body .= '
        <tr>
            <td class="text_tengah">' . $no++ . '</td>
            <td class="text_tengah">' . $renaksi_opd['level'] . '</td>
            <td class="text_kiri">' . $renaksi_opd['label'] . '</td>
            <td class="text_kiri">' . $indikator . '</td>            
            <td class="text_tengah">' . $satuan . '</td>
            <td class="text_tengah">' . $target_awal . '</td>
            <td class="text_tengah">' . $target_1 . '</td>
            <td class="text_tengah">' . $target_2 . '</td>
            <td class="text_tengah">' . $target_3 . '</td>
            <td class="text_tengah">' . $target_4 . '</td>
            <td class="text_tengah">' . $target_akhir . '</td>  
            <td class="text_tengah">' . $realisasi_tw_1 . '</td>
            <td class="text_tengah">' . $realisasi_tw_2 . '</td>
            <td class="text_tengah">' . $realisasi_tw_3 . '</td>
            <td class="text_tengah">' . $realisasi_tw_4 . '</td>     
            <td class="text_tengah">' . $capaian_realisasi . '</td>     
            <td class="text_kanan">' . $rencana_pagu . '</td>     
            <td class="text_kanan">' . number_format((float)$total_harga_tagging_rincian, 0, ",", ".") . '</td>
            <td class="text_kanan">' . number_format((float)$total_realisasi_tagging_rincian, 0, ",", ".") . '</td>
            <td class="text_tengah">' . $capaian_realisasi_pagu . '</td>
            <td class="text_kanan">' . number_format((float)$renaksi_opd['pagu_cascading'], 0, ",", ".") . '</td>
            <td class="text_kiri">' . $label_cascading . '</td>
            <td class="text_kiri">' . $nama_pokin . '</td>
            <td class="text_tengah">' . $level_pokin . '</td>
            <td class="text_kiri">' . $label_cascading . '</td>
            <td class="text_kiri">' . $nama_pokin_pemda . '</td>
            <td class="text_tengah">' . $level_pokin_pemda . '</td>
        </tr>';

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

    .table_laporan_rhk {
        font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        border-collapse: collapse;
        width: 2900px;
        table-layout: fixed;
        overflow-wrap: break-word;
        font-size: 90%;
    }

    .table_laporan_rhk thead {
        position: sticky;
        top: -6px;
    }

    .table_laporan_rhk .badge {
        white-space: normal;
        line-height: 1.3;
    }
</style>

<!-- Table -->
<div class="container-md">
    <div id="cetak" title="Laporan Rencana Hasil Kerja">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Laporan Rencana Hasil Kerja <br><?php echo $gelar_depan . ' ' . $nama_pegawai . ', ' . $gelar_belakang; ?><br> Tahun Anggaran <?php echo $input['tahun']; ?></h1>
            <div id="action" class="action-section hide-excel"></div>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_laporan_rhk table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="text-center" rowspan="2" style="width: 85px;">LEVEL</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">RHK</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">INDIKATOR</th>
                            <th class="text-center" rowspan="2" style="width: 100px;">SATUAN</th>
                            <th class="text-center" colspan="6" style="width: 400px;">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" colspan="4" style="width: 250px;">REALISASI KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI (%)</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">RENCANA PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">PAGU RINCIAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">CAPAIAN REALISASI PAGU</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">PAGU RENJA</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">NOMENKLATUR RENJA</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">POKIN OPD</th>
                            <th class="text-center" rowspan="2" style="width: 85px;">LEVEL</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">CASCADING</th>
                            <th class="text-center" rowspan="2" style="width: 300px;">POKIN PEMDA</th>
                            <th class="text-center" rowspan="2" style="width: 85px;">LEVEL</th>
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
                            <th>16<br>= ((12 + 13 + 14 + 15 / 11) * 100)</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20<br>= (19 / 18) * 100</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>24</th>
                            <th>25</th>
                            <th>26</th>
                            <th>27</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $body; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        run_download_excel_sakip();     
    });
</script>